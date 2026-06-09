<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items']);

        if ($request->status) $query->where('status', $request->status);
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('order_number', 'like', "%{$request->search}%")
                  ->orWhere('full_name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        return response()->json($query->latest()->paginate(15));
    }

    public function show(Order $order)
    {
        return response()->json($order->load(['user', 'items.product', 'payment']));
    }

    public function updateNotes(Request $request, Order $order)
    {
        $order->update(['admin_notes' => $request->admin_notes]);
        return response()->json(['message' => 'Notes saved']);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,completed,cancelled',
        ]);

        $statusTimestamps = [
            'confirmed' => 'confirmed_at',
            'out_for_delivery' => 'shipped_at',
            'completed' => 'delivered_at',
            'cancelled' => 'cancelled_at',
        ];

        $data = ['status' => $request->status];

        if (isset($statusTimestamps[$request->status])) {
            $data[$statusTimestamps[$request->status]] = now();
        }

        if ($request->status === 'cancelled' && $request->cancellation_reason) {
            $data['cancellation_reason'] = $request->cancellation_reason;
        }

        if ($request->status === 'completed') {
            $order->payment()->update(['status' => 'paid', 'paid_at' => now()]);
            $data['payment_status'] = 'paid';
        }

        $order->update($data);

        return response()->json(['message' => 'Order status updated', 'order' => $order]);
    }
}

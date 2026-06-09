<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\CustomFurnitureInquiry;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalOrders = Order::count();
        $totalRevenue = Order::whereNotIn('status', ['cancelled'])->sum('total');
        $pendingOrders = Order::where('status', 'pending')->count();
        $completedOrders = Order::where('status', 'completed')->count();
        $totalCustomers = User::where('is_admin', false)->count();
        $totalProducts = Product::where('is_active', true)->count();
        $lowStockProducts = Product::where('stock', '<=', 5)->where('is_active', true)->count();

        $recentOrders = Order::with('items')
            ->latest()
            ->limit(10)
            ->get();

        $salesData = Order::whereNotIn('status', ['cancelled'])
            ->where('created_at', '>=', now()->subDays(30))
            ->selectRaw('DATE(created_at) as date, SUM(total) as revenue, COUNT(*) as orders')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $topProducts = Product::withCount(['cartItems as total_sold'])
            ->orderBy('total_sold', 'desc')
            ->limit(5)
            ->get();

        $unreadMessages = ContactMessage::where('is_read', false)->count();
        $unreadInquiries = CustomFurnitureInquiry::where('is_read', false)->count();

        $lowStockItems = Product::where('stock', '<=', 5)->where('is_active', true)->get();
        $activeCoupons = \App\Models\Coupon::where('is_active', true)->count();

        return response()->json([
            'total_orders' => $totalOrders,
            'revenue' => $totalRevenue,
            'revenue_change' => 0,
            'orders_change' => 0,
            'pending_orders' => $pendingOrders,
            'completed_orders' => $completedOrders,
            'completed_change' => 0,
            'total_customers' => $totalCustomers,
            'customers_change' => 0,
            'total_products' => $totalProducts,
            'low_stock_count' => $lowStockProducts,
            'active_coupons' => $activeCoupons,
            'recent_orders' => $recentOrders,
            'sales_data' => $salesData,
            'top_products' => $topProducts,
            'low_stock' => $lowStockItems,
            'unread_messages' => $unreadMessages,
            'unread_inquiries' => $unreadInquiries,
        ]);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomFurnitureInquiry;
use Illuminate\Http\Request;

class InquiryController extends Controller
{
    public function index(Request $request)
    {
        $query = CustomFurnitureInquiry::query();
        if ($request->status) $query->where('status', $request->status);
        return response()->json($query->latest()->paginate(15));
    }

    public function show(CustomFurnitureInquiry $inquiry)
    {
        $inquiry->update(['is_read' => true]);
        return response()->json($inquiry);
    }

    public function updateStatus(Request $request, CustomFurnitureInquiry $inquiry)
    {
        $request->validate(['status' => 'required|in:new,in_progress,quoted,accepted,rejected,completed']);
        $inquiry->update(['status' => $request->status, 'admin_notes' => $request->admin_notes]);
        return response()->json(['message' => 'Inquiry updated']);
    }

    public function destroy(CustomFurnitureInquiry $inquiry)
    {
        $inquiry->delete();
        return response()->json(['message' => 'Inquiry deleted']);
    }
}

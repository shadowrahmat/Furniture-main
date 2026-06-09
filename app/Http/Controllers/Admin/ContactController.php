<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $query = ContactMessage::query();
        if ($request->read === 'unread') $query->where('is_read', false);
        return response()->json($query->latest()->paginate(20));
    }

    public function markAsRead(ContactMessage $message)
    {
        $message->update(['is_read' => true, 'read_at' => now()]);
        return response()->json(['message' => 'Marked as read']);
    }

    public function show(ContactMessage $message)
    {
        $message->update(['is_read' => true, 'read_at' => now()]);
        return response()->json($message);
    }

    public function destroy(ContactMessage $message)
    {
        $message->delete();
        return response()->json(['message' => 'Message deleted']);
    }
}

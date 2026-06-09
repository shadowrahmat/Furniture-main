<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('is_admin', false)
            ->withCount('orders')
            ->withSum('orders as total_spent', 'total');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        if ($request->status === 'active') $query->where('is_active', true);
        if ($request->status === 'inactive') $query->where('is_active', false);

        return response()->json($query->latest()->paginate(20));
    }

    public function show(User $user)
    {
        return response()->json($user->load('orders'));
    }

    public function toggleStatus(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
        return response()->json(['message' => 'User status updated', 'is_active' => $user->is_active]);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['message' => 'User deleted']);
    }
}

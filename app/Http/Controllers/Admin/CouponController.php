<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        return response()->json(Coupon::latest()->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:coupons',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'expires_at' => 'nullable|date',
        ]);

        $data = $request->except(['max_uses']);
        $data['usage_limit'] = $request->max_uses ?: null;
        $data['code'] = strtoupper($request->code);

        $coupon = Coupon::create($data);
        return response()->json($coupon, 201);
    }

    public function update(Request $request, Coupon $coupon)
    {
        $data = $request->except(['max_uses']);
        if ($request->has('max_uses')) $data['usage_limit'] = $request->max_uses ?: null;
        if ($request->has('code')) $data['code'] = strtoupper($request->code);
        $coupon->update($data);
        return response()->json($coupon);
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return response()->json(['message' => 'Coupon deleted']);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function apply(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'subtotal' => 'required|numeric|min:0',
        ]);

        $coupon = Coupon::where('code', strtoupper($request->code))->first();

        if (!$coupon || !$coupon->isValid()) {
            return response()->json(['message' => 'Invalid or expired coupon code'], 422);
        }

        if ($request->subtotal < $coupon->min_order_amount) {
            return response()->json([
                'message' => "Minimum order amount is ৳{$coupon->min_order_amount} for this coupon",
            ], 422);
        }

        $discount = $coupon->calculateDiscount($request->subtotal);

        return response()->json([
            'message' => 'Coupon applied successfully',
            'coupon' => $coupon,
            'discount' => $discount,
        ]);
    }
}

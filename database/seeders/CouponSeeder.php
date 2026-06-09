<?php

namespace Database\Seeders;

use App\Models\Coupon;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        $coupons = [
            [
                'code' => 'WELCOME10',
                'type' => 'percentage',
                'value' => 10,
                'min_order_amount' => 2000,
                'max_discount' => 1000,
                'usage_limit' => 100,
                'is_active' => true,
                'description' => '10% off for new customers',
                'expires_at' => now()->addYear(),
            ],
            [
                'code' => 'SAVE500',
                'type' => 'fixed',
                'value' => 500,
                'min_order_amount' => 5000,
                'is_active' => true,
                'description' => 'Flat ৳500 off on orders above ৳5000',
                'expires_at' => now()->addMonths(3),
            ],
            [
                'code' => 'LUXURY15',
                'type' => 'percentage',
                'value' => 15,
                'min_order_amount' => 10000,
                'max_discount' => 3000,
                'usage_limit' => 50,
                'is_active' => true,
                'description' => '15% off on luxury furniture',
                'expires_at' => now()->addMonths(6),
            ],
        ];

        foreach ($coupons as $coupon) {
            Coupon::create($coupon);
        }
    }
}

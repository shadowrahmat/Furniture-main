<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@furniturestore.com'],
            [
                'name' => 'Admin',
                'email' => 'admin@furniturestore.com',
                'phone' => '+8801700000000',
                'password' => Hash::make('admin123'),
                'is_admin' => true,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // Demo customer
        User::updateOrCreate(
            ['email' => 'customer@example.com'],
            [
                'name' => 'Rahim Ahmed',
                'email' => 'customer@example.com',
                'phone' => '+8801800000001',
                'password' => Hash::make('password'),
                'is_admin' => false,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
    }
}

<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Living Room Furniture',
                'slug' => 'living-room-furniture',
                'description' => 'Premium sofas, coffee tables, TV units, and accent chairs for your living space.',
                'icon' => 'sofa',
                'sort_order' => 1,
                'is_featured' => true,
            ],
            [
                'name' => 'Bedroom Furniture',
                'slug' => 'bedroom-furniture',
                'description' => 'Elegant beds, wardrobes, dressing tables, and bedside tables for restful spaces.',
                'icon' => 'bed',
                'sort_order' => 2,
                'is_featured' => true,
            ],
            [
                'name' => 'Dining Furniture',
                'slug' => 'dining-furniture',
                'description' => 'Stylish dining tables, chairs, and buffet cabinets for memorable meals.',
                'icon' => 'dining',
                'sort_order' => 3,
                'is_featured' => true,
            ],
            [
                'name' => 'Office Furniture',
                'slug' => 'office-furniture',
                'description' => 'Ergonomic desks, office chairs, and storage solutions for productive workspaces.',
                'icon' => 'office',
                'sort_order' => 4,
                'is_featured' => true,
            ],
            [
                'name' => 'Storage & Cabinets',
                'slug' => 'storage-cabinets',
                'description' => 'Smart storage solutions including bookshelves, cabinets, and wardrobes.',
                'icon' => 'cabinet',
                'sort_order' => 5,
                'is_featured' => true,
            ],
            [
                'name' => 'Home Decor',
                'slug' => 'home-decor',
                'description' => 'Decorative mirrors, lamps, wall art, and accent pieces to elevate your home.',
                'icon' => 'decor',
                'sort_order' => 6,
                'is_featured' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}

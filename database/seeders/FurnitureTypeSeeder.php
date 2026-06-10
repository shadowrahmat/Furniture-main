<?php

namespace Database\Seeders;

use App\Models\FurnitureType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class FurnitureTypeSeeder extends Seeder
{
    public function run(): void
    {
        $furnitureTypes = [
            [
                'name' => 'Sofa',
                'description' => 'Custom-built sofas and sectionals tailored to your space and seating needs.',
                'starting_price' => 25000,
                'display_order' => 1,
            ],
            [
                'name' => 'Bed',
                'description' => 'Custom bed frames with optional storage, headboard styles, and sizes.',
                'starting_price' => 18000,
                'display_order' => 2,
            ],
            [
                'name' => 'Dining Table',
                'description' => 'Custom dining tables built to fit your dining room and seating capacity.',
                'starting_price' => 15000,
                'display_order' => 3,
            ],
            [
                'name' => 'Chair',
                'description' => 'Custom chairs and seating, including dining, accent, and lounge chairs.',
                'starting_price' => 4000,
                'display_order' => 4,
            ],
            [
                'name' => 'Wardrobe',
                'description' => 'Custom wardrobes with configurable shelving, drawers, and hanging space.',
                'starting_price' => 22000,
                'display_order' => 5,
            ],
            [
                'name' => 'Cabinet',
                'description' => 'Custom storage cabinets for living rooms, kitchens, and offices.',
                'starting_price' => 12000,
                'display_order' => 6,
            ],
            [
                'name' => 'Bookshelf',
                'description' => 'Custom bookshelves and display units in a range of sizes and finishes.',
                'starting_price' => 9000,
                'display_order' => 7,
            ],
            [
                'name' => 'TV Unit',
                'description' => 'Custom TV units and entertainment centers designed for your living room.',
                'starting_price' => 13000,
                'display_order' => 8,
            ],
            [
                'name' => 'Office Table',
                'description' => 'Custom office desks and workstations for home or commercial offices.',
                'starting_price' => 10000,
                'display_order' => 9,
            ],
            [
                'name' => 'Dressing Table',
                'description' => 'Custom dressing tables with mirrors, drawers, and storage options.',
                'starting_price' => 11000,
                'display_order' => 10,
            ],
            [
                'name' => 'Coffee Table',
                'description' => 'Custom coffee tables to match your living room furniture set.',
                'starting_price' => 6000,
                'display_order' => 11,
            ],
        ];

        foreach ($furnitureTypes as $furnitureType) {
            $furnitureType['slug'] = Str::slug($furnitureType['name']);
            $furnitureType['is_active'] = true;
            FurnitureType::create($furnitureType);
        }
    }
}

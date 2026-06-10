<?php

namespace Database\Seeders;

use App\Models\WoodType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class WoodTypeSeeder extends Seeder
{
    public function run(): void
    {
        $woodTypes = [
            [
                'name' => 'Teak',
                'short_description' => 'Premium hardwood prized for durability and natural water resistance.',
                'description' => 'Teak is a tropical hardwood known for its exceptional strength, natural oils that resist moisture and pests, and a beautiful golden-brown finish that deepens with age. A top choice for heirloom-quality furniture.',
                'durability' => 'Very High - 50+ years',
                'recommended_use' => 'Outdoor furniture, dining tables, beds',
                'color_texture' => 'Golden brown with a straight, tight grain',
                'price_adjustment_type' => 'percentage',
                'price_adjustment_value' => 25,
                'availability' => 'in_stock',
                'display_order' => 1,
            ],
            [
                'name' => 'Mahogany',
                'short_description' => 'Rich reddish-brown wood with an elegant, fine-grained finish.',
                'description' => 'Mahogany is a classic furniture-making hardwood valued for its warm reddish-brown tone, fine grain, and excellent workability. It takes polish beautifully and is a favorite for traditional and luxury furniture.',
                'durability' => 'High - 30+ years',
                'recommended_use' => 'Cabinets, wardrobes, dining sets',
                'color_texture' => 'Reddish-brown, fine and even grain',
                'price_adjustment_type' => 'percentage',
                'price_adjustment_value' => 20,
                'availability' => 'in_stock',
                'display_order' => 2,
            ],
            [
                'name' => 'Oak',
                'short_description' => 'Strong, versatile hardwood with a distinctive grain pattern.',
                'description' => 'Oak is a durable, widely available hardwood with a prominent grain pattern. It offers excellent strength-to-weight ratio and accepts stains and finishes well, making it suitable for a wide range of furniture styles.',
                'durability' => 'High - 30+ years',
                'recommended_use' => 'Tables, chairs, bookshelves',
                'color_texture' => 'Light tan to medium brown, prominent grain',
                'price_adjustment_type' => 'percentage',
                'price_adjustment_value' => 15,
                'availability' => 'in_stock',
                'display_order' => 3,
            ],
            [
                'name' => 'Walnut',
                'short_description' => 'Luxurious dark hardwood with a smooth, refined grain.',
                'description' => 'Walnut is a premium hardwood with a rich, dark chocolate tone and smooth, straight grain. It is highly sought after for high-end furniture and offers excellent dimensional stability.',
                'durability' => 'High - 30+ years',
                'recommended_use' => 'Premium furniture, accent pieces, desks',
                'color_texture' => 'Dark chocolate brown, smooth straight grain',
                'price_adjustment_type' => 'percentage',
                'price_adjustment_value' => 22,
                'availability' => 'in_stock',
                'display_order' => 4,
            ],
            [
                'name' => 'Pine',
                'short_description' => 'Affordable softwood with a light, natural appearance.',
                'description' => 'Pine is a lightweight, affordable softwood with a pale color and visible knots that give furniture a rustic, casual look. It is easy to work with but softer than hardwoods.',
                'durability' => 'Medium - 10-15 years',
                'recommended_use' => 'Casual furniture, shelving, kids\' furniture',
                'color_texture' => 'Pale yellow with visible knots',
                'price_adjustment_type' => 'fixed',
                'price_adjustment_value' => 0,
                'availability' => 'in_stock',
                'display_order' => 5,
            ],
            [
                'name' => 'Beech',
                'short_description' => 'Smooth, light-colored hardwood ideal for curved designs.',
                'description' => 'Beech is a fine-grained, pale hardwood that is strong and shock-resistant. It bends well under steam, making it popular for chairs and curved furniture components.',
                'durability' => 'Medium - 15-20 years',
                'recommended_use' => 'Chairs, curved frames, kitchen furniture',
                'color_texture' => 'Pale cream to light brown, fine even grain',
                'price_adjustment_type' => 'percentage',
                'price_adjustment_value' => 8,
                'availability' => 'in_stock',
                'display_order' => 6,
            ],
            [
                'name' => 'Engineered Wood',
                'short_description' => 'Budget-friendly manufactured wood with consistent quality.',
                'description' => 'Engineered wood is manufactured by binding wood fibers or veneers with adhesives under heat and pressure, producing a stable, uniform material that resists warping and is more affordable than solid wood.',
                'durability' => 'Medium - 10-15 years',
                'recommended_use' => 'Wardrobes, modular furniture, office furniture',
                'color_texture' => 'Uniform finish, available in various laminate colors',
                'price_adjustment_type' => 'fixed',
                'price_adjustment_value' => 0,
                'availability' => 'in_stock',
                'display_order' => 7,
            ],
            [
                'name' => 'Plywood',
                'short_description' => 'Layered wood panels offering strength and stability.',
                'description' => 'Plywood is made from thin layers of wood veneer glued together with grains running in alternating directions, providing excellent strength, stability, and resistance to warping.',
                'durability' => 'Medium - 10-20 years',
                'recommended_use' => 'Cabinets, shelving units, structural panels',
                'color_texture' => 'Layered edge, smooth surface finish',
                'price_adjustment_type' => 'fixed',
                'price_adjustment_value' => 0,
                'availability' => 'in_stock',
                'display_order' => 8,
            ],
            [
                'name' => 'MDF',
                'short_description' => 'Smooth, dense board perfect for painted finishes.',
                'description' => 'Medium-density fibreboard (MDF) is made from wood fibers bonded with resin under heat and pressure. Its smooth, uniform surface is ideal for painting and detailed designs at an affordable cost.',
                'durability' => 'Medium - 8-12 years',
                'recommended_use' => 'Painted furniture, decorative panels, TV units',
                'color_texture' => 'Smooth uniform surface, no visible grain',
                'price_adjustment_type' => 'fixed',
                'price_adjustment_value' => 0,
                'availability' => 'in_stock',
                'display_order' => 9,
            ],
        ];

        foreach ($woodTypes as $woodType) {
            $woodType['slug'] = Str::slug($woodType['name']);
            $woodType['is_active'] = true;
            WoodType::create($woodType);
        }
    }
}

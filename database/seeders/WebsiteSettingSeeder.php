<?php

namespace Database\Seeders;

use App\Models\WebsiteSetting;
use Illuminate\Database\Seeder;

class WebsiteSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'site_name', 'value' => 'Furniture Store', 'type' => 'text', 'group' => 'general', 'label' => 'Site Name'],
            ['key' => 'site_email', 'value' => 'hello@furniturestore.com', 'type' => 'text', 'group' => 'general', 'label' => 'Contact Email'],
            ['key' => 'site_phone', 'value' => '+880 1700-000000', 'type' => 'text', 'group' => 'general', 'label' => 'Contact Phone'],
            ['key' => 'site_address', 'value' => '123 Furniture Street, Dhaka 1200, Bangladesh', 'type' => 'text', 'group' => 'general', 'label' => 'Address'],
            ['key' => 'delivery_charge', 'value' => '150', 'type' => 'text', 'group' => 'shipping', 'label' => 'Delivery Charge (BDT)'],
            ['key' => 'free_delivery_above', 'value' => '5000', 'type' => 'text', 'group' => 'shipping', 'label' => 'Free Delivery Above (BDT)'],
            ['key' => 'currency', 'value' => 'BDT', 'type' => 'text', 'group' => 'general', 'label' => 'Currency'],
            ['key' => 'currency_symbol', 'value' => '৳', 'type' => 'text', 'group' => 'general', 'label' => 'Currency Symbol'],
            ['key' => 'facebook_url', 'value' => 'https://facebook.com/furniturestore', 'type' => 'text', 'group' => 'social', 'label' => 'Facebook URL'],
            ['key' => 'instagram_url', 'value' => 'https://instagram.com/furniturestore', 'type' => 'text', 'group' => 'social', 'label' => 'Instagram URL'],
            ['key' => 'youtube_url', 'value' => '', 'type' => 'text', 'group' => 'social', 'label' => 'YouTube URL'],
            ['key' => 'hero_title', 'value' => 'Elegant Furniture for Modern Living', 'type' => 'text', 'group' => 'homepage', 'label' => 'Hero Title'],
            ['key' => 'hero_subtitle', 'value' => 'Discover premium sofas, beds, dining tables, chairs, storage solutions, and home decor crafted for comfort and style.', 'type' => 'text', 'group' => 'homepage', 'label' => 'Hero Subtitle'],
        ];

        foreach ($settings as $setting) {
            WebsiteSetting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            [
                'key' => 'website_name',
                'value' => json_encode(['ar' => 'عقارات', 'en' => 'Property Units']),
                'type' => 'json',
            ],
            [
                'key' => 'website_logo',
                'value' => null,
                'type' => 'file',
            ],
            [
                'key' => 'phone_numbers',
                'value' => json_encode(['+20 100 123 4567']),
                'type' => 'array',
            ],
            [
                'key' => 'emails',
                'value' => json_encode(['info@property.com']),
                'type' => 'array',
            ],
            [
                'key' => 'whatsapp_numbers',
                'value' => json_encode(['+20 100 123 4567']),
                'type' => 'array',
            ],
            [
                'key' => 'admin_email',
                'value' => 'admin@property.com',
                'type' => 'string',
            ],
            [
                'key' => 'facebook_url',
                'value' => null,
                'type' => 'string',
            ],
            [
                'key' => 'instagram_url',
                'value' => null,
                'type' => 'string',
            ],
            [
                'key' => 'twitter_url',
                'value' => null,
                'type' => 'string',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                ['value' => $setting['value'], 'type' => $setting['type']]
            );
        }
    }
}

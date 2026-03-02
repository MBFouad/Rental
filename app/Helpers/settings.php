<?php

use App\Models\Setting;

if (! function_exists('setting')) {
    function setting(string $key, mixed $default = null): mixed
    {
        return Setting::get($key, $default);
    }
}

if (! function_exists('setting_array')) {
    function setting_array(string $key, array $default = []): array
    {
        return Setting::getArray($key, $default);
    }
}

if (! function_exists('site_name')) {
    function site_name(): string
    {
        $names = Setting::get('website_name', ['ar' => 'عقارات', 'en' => 'Property Units']);
        if (is_string($names)) {
            $names = json_decode($names, true) ?? ['ar' => 'عقارات', 'en' => 'Property Units'];
        }

        return $names[app()->getLocale()] ?? $names['en'] ?? 'Property Units';
    }
}

if (! function_exists('site_logo')) {
    function site_logo(): ?string
    {
        $logo = Setting::get('website_logo');

        return $logo ? asset('storage/'.$logo) : null;
    }
}

if (! function_exists('currency_symbol')) {
    function currency_symbol(): string
    {
        $currency = Setting::get('currency', 'EGP');

        return match ($currency) {
            'EGP' => __('EGP'),
            'SAR' => __('SAR'),
            'USD' => __('USD'),
            default => __('EGP'),
        };
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'type'];

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget('settings'));
        static::deleted(fn () => Cache::forget('settings'));
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        $settings = static::getAllSettings();

        return $settings[$key] ?? $default;
    }

    public static function set(string $key, mixed $value, string $type = 'string'): void
    {
        if (is_array($value)) {
            $value = json_encode($value);
            $type = 'array';
        }

        static::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'type' => $type]
        );
    }

    public static function getArray(string $key, array $default = []): array
    {
        $value = static::get($key);
        if (is_array($value)) {
            return $value;
        }
        if (is_string($value)) {
            return json_decode($value, true) ?? $default;
        }

        return $default;
    }

    public static function getAllSettings(): array
    {
        return Cache::rememberForever('settings', function () {
            return static::all()->mapWithKeys(function ($setting) {
                $value = $setting->value;
                if (in_array($setting->type, ['array', 'json']) && is_string($value)) {
                    $value = json_decode($value, true);
                }

                return [$setting->key => $value];
            })->toArray();
        });
    }
}

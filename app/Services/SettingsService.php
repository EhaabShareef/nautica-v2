<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Collection;

class SettingsService
{
    public static function get(string $key, $default = null)
    {
        return Cache::remember("setting:{$key}", 3600, function () use ($key, $default) {
            $setting = Setting::where('key', $key)->where('is_active', true)->first();
            return $setting ? $setting->value : $default;
        });
    }

    public static function getGroup(string $group): Collection
    {
        return Cache::remember("settings:group:{$group}", 3600, function () use ($group) {
            return Setting::where('group', $group)
                         ->where('is_active', true)
                         ->get()
                         ->pluck('value', 'key');
        });
    }

    public static function clearCache(?string $key = null)
    {
        if ($key) {
            Cache::forget("setting:{$key}");
        } else {
            Cache::flush();
        }
    }
}

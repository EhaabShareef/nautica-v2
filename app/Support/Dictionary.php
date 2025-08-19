<?php

namespace App\Support;

use App\Models\AppType;
use Illuminate\Support\Facades\Cache;

class Dictionary
{
    public static function options(string $group): array
    {
        return Cache::remember("app_types.$group", 60, fn () =>
            AppType::where('group', $group)
                ->where('is_active', true)
                ->pluck('label', 'code')
                ->toArray()
        );
    }

    public static function label(string $group, string $code, ?string $default = null): ?string
    {
        return self::options($group)[$code] ?? $default;
    }

    public static function keys(string $group): array
    {
        return array_keys(self::options($group));
    }

    public static function isValid(string $group, string $code): bool
    {
        return in_array($code, self::keys($group), true);
    }
}

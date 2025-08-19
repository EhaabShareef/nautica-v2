<?php

namespace App\Support;

class Dictionary
{
    public static function options(string $key): array
    {
        return config("nautica.dictionaries.$key", []);
    }

    public static function label(string $key, string $value, ?string $default = null): ?string
    {
        return self::options($key)[$value] ?? $default;
    }

    public static function keys(string $key): array
    {
        return array_keys(self::options($key));
    }

    public static function isValid(string $key, string $value): bool
    {
        return in_array($value, self::keys($key), true);
    }
}

<?php

namespace App\Support;

class Dictionary
{
    /**
     * Retrieve a configured dictionary by key from the `nautica.dictionaries` config.
     *
     * Returns the dictionary array for the given key, or an empty array if the key is not defined.
     *
     * @param string $key Config key under `nautica.dictionaries` (e.g. 'statuses', 'types').
     * @return array Associative array representing the dictionary (typically value => label).
     */
    public static function options(string $key): array
    {
        return config("nautica.dictionaries.$key", []);
    }

    /**
     * Return the label for a given dictionary value or a fallback default.
     *
     * Looks up the configured dictionary identified by $key and returns the entry
     * for $value if present; otherwise returns $default.
     *
     * @param string $key Name of the dictionary (config key under nautica.dictionaries).
     * @param string $value Dictionary value whose label is requested.
     * @param string|null $default Value to return when the label is not found.
     * @return string|null The label for $value, or $default if not present.
     */
    public static function label(string $key, string $value, ?string $default = null): ?string
    {
        return self::options($key)[$value] ?? $default;
    }

    /**
     * Return the list of keys for a named dictionary.
     *
     * @param string $key The dictionary identifier (lookup under `nautica.dictionaries.$key` in config).
     * @return array<string> Array of dictionary keys.
     */
    public static function keys(string $key): array
    {
        return array_keys(self::options($key));
    }

    /**
     * Determine whether a given value exists as a key in the named dictionary.
     *
     * Checks the configured dictionary at "nautica.dictionaries.{key}" and returns
     * true if `$value` is present among that dictionary's keys (strict comparison).
     *
     * @param string $key   Dictionary identifier (looked up under `nautica.dictionaries.$key`).
     * @param string $value Candidate key to validate.
     * @return bool True if the candidate exists as a key in the dictionary, false otherwise.
     */
    public static function isValid(string $key, string $value): bool
    {
        return in_array($value, self::keys($key), true);
    }
}

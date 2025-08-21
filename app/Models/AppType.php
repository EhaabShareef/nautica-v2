<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class AppType extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'group',
        'code',
        'label',
        'description',
        'sort_order',
        'extra',
        'is_protected',
        'is_active',
    ];

    protected $casts = [
        'extra' => 'array',
        'is_protected' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Scope: only active app types.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: filter by group with ordering.
     */
    public function scopeByGroup($query, $group)
    {
        return $query->where('group', $group)
                     ->orderBy('sort_order')
                     ->orderBy('label');
    }

    /**
     * Retrieve cached app types for a group.
     */
    public static function getByGroup($group)
    {
        return \Cache::remember("types:{$group}", 3600, function () use ($group) {
            return static::active()->byGroup($group)->get();
        });
    }

    /**
     * Get validation rules for app type with composite uniqueness
     */
    public static function getValidationRules(?string $appTypeId = null, ?string $group = null): array
    {
        $codeRule = Rule::unique('app_types', 'code');
        
        if ($appTypeId) {
            $codeRule->ignore($appTypeId);
        }
        
        if ($group) {
            $codeRule->where('group', $group);
        }

        return [
            'group' => 'required|string|max:100',
            'code' => ['required', 'string', 'max:100', $codeRule],
            'label' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'sort_order' => 'integer|min:0',
            'extra' => 'nullable|array',
            'is_protected' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Clear cache for a specific group
     */
    public static function clearGroupCache(string $group)
    {
        \Cache::forget("types:{$group}");
    }
}

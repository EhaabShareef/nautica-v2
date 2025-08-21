<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}

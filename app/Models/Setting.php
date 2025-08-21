<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $primaryKey = 'key';
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * Mass assignable attributes.
     */
    protected $fillable = [
        'key',
        'group',
        'value',
        'label',
        'description',
        'is_protected',
        'is_active',
    ];

    /**
     * Attribute casting.
     */
    protected $casts = [
        // value handled via accessor
        'is_protected' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get the value attribute with proper JSON handling for scalars and composites
     */
    protected function value(): Attribute
    {
        return Attribute::make(
            get: function (mixed $value): mixed {
                if (is_null($value)) {
                    return null;
                }

                $decoded = json_decode($value, true);

                // If json_decode failed, return the original value
                if (json_last_error() !== JSON_ERROR_NONE) {
                    return $value;
                }

                return $decoded;
            },
            set: function (mixed $value): string {
                $encoded = json_encode($value);

                if ($encoded === false) {
                    throw new \InvalidArgumentException(
                        'Failed to JSON encode value: ' . json_last_error_msg()
                    );
                }

                return $encoded;
            }
        );
    }

    /**
     * Scope: only settings accessible to admins (active ones).
     */
    public function scopeAccessible($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: filter settings by group.
     */
    public function scopeByGroup($query, $group)
    {
        return $query->where('group', $group);
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $primaryKey = 'setting_key';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['setting_key', 'value'];

    protected $casts = [
        // Removed 'value' => 'array' to use custom accessor below
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
}

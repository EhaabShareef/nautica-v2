<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class Block extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'property_id', 'name', 'code', 'location', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function zones()
    {
        return $this->hasMany(Zone::class);
    }

    /**
     * Get validation rules for block with scoped uniqueness
     */
    public static function getValidationRules(?string $blockId = null, ?string $propertyId = null): array
    {
        $codeRule = Rule::unique('blocks', 'code');
        
        if ($blockId) {
            $codeRule->ignore($blockId);
        }
        
        if ($propertyId) {
            $codeRule->where('property_id', $propertyId);
        }

        return [
            'property_id' => 'required|exists:properties,id',
            'name' => 'required|string|max:255',
            'code' => ['required', 'string', 'max:50', $codeRule],
            'location' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ];
    }
}

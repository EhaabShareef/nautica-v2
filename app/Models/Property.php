<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class Property extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'name', 'code', 'timezone', 'currency', 'address', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function blocks()
    {
        return $this->hasMany(Block::class);
    }

    /**
     * Get validation rules for property
     */
    public static function getValidationRules(?string $propertyId = null): array
    {
        $codeRule = Rule::unique('properties', 'code');
        
        if ($propertyId) {
            $codeRule->ignore($propertyId);
        }

        return [
            'name' => 'required|string|max:255',
            'code' => ['required', 'string', 'max:50', $codeRule],
            'timezone' => 'nullable|string|max:100',
            'currency' => 'nullable|string|max:3',
            'address' => 'nullable|string',
            'is_active' => 'boolean',
        ];
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class Zone extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'block_id', 'name', 'code', 'location', 'notes', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function block()
    {
        return $this->belongsTo(Block::class);
    }

    public function slots()
    {
        return $this->hasMany(Slot::class);
    }

    /**
     * Get validation rules for zone with scoped uniqueness
     */
    public static function getValidationRules(?string $zoneId = null, ?string $blockId = null): array
    {
        $codeRule = Rule::unique('zones', 'code');
        
        if ($zoneId) {
            $codeRule->ignore($zoneId);
        }
        
        if ($blockId) {
            $codeRule->where('block_id', $blockId);
        }

        return [
            'block_id' => 'required|exists:blocks,id',
            'name' => 'required|string|max:255',
            'code' => ['required', 'string', 'max:50', $codeRule],
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
        ];
    }
}

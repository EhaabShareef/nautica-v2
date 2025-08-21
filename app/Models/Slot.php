<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use App\Models\Booking;
use App\Models\Contract;

class Slot extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'zone_id', 'name', 'code', 'location', 'length', 'width', 'depth', 'amenities', 'base_rate', 'is_active',
    ];

    protected $casts = [
        'amenities' => 'array',
        'is_active' => 'boolean',
        'length' => 'decimal:2',
        'width' => 'decimal:2',
        'depth' => 'decimal:2',
        'base_rate' => 'decimal:2',
    ];

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }

    /**
     * Get validation rules for slot with scoped uniqueness
     */
    public static function getValidationRules(?string $slotId = null, ?string $zoneId = null): array
    {
        $codeRule = Rule::unique('slots', 'code');
        
        if ($slotId) {
            $codeRule->ignore($slotId);
        }
        
        if ($zoneId) {
            $codeRule->where('zone_id', $zoneId);
        }

        return [
            'zone_id' => 'required|exists:zones,id',
            'name' => 'required|string|max:255',
            'code' => ['required', 'string', 'max:50', $codeRule],
            'location' => 'required|string|max:255',
            'length' => 'nullable|numeric|min:0|max:999.99',
            'width' => 'nullable|numeric|min:0|max:999.99',
            'depth' => 'nullable|numeric|min:0|max:999.99',
            'amenities' => 'nullable|array',
            'base_rate' => 'nullable|numeric|min:0|max:99999999.99',
            'is_active' => 'boolean',
        ];
    }
}

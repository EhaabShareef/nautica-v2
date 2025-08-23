<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vessel extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'owner_client_id',
        'renter_client_id',
        'name',
        'registration_number',
        'type',
        'length',
        'width',
        'draft',
        'specifications',
        'is_active',
        // 'created_by', 'updated_by' - Removed from fillable to prevent mass assignment
        // These audit fields are automatically managed by VesselObserver
    ];

    protected $casts = [
        'specifications' => 'array',
        'is_active' => 'boolean',
        'length' => 'decimal:2',
        'width' => 'decimal:2',
        'draft' => 'decimal:2',
    ];

    /**
     * Get validation rules for vessel creation/update
     */
    public function getValidationRules($isUpdate = false): array
    {
        $registrationRule = $isUpdate 
            ? 'required|string|unique:vessels,registration_number,' . $this->id 
            : 'required|string|unique:vessels,registration_number';
        
        return [
            'name' => 'required|string|max:255',
            'registration_number' => $registrationRule,
            'owner_client_id' => 'required|exists:users,id',
            'renter_client_id' => 'nullable|different:owner_client_id|exists:users,id',
            'type' => 'nullable|string|max:100',
            'length' => 'nullable|numeric|min:0|max:999.99',
            'width' => 'nullable|numeric|min:0|max:999.99',
            'draft' => 'nullable|numeric|min:0|max:999.99',
            'specifications' => 'nullable|array',
            'is_active' => 'boolean',
        ];
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_client_id');
    }

    public function renter()
    {
        return $this->belongsTo(User::class, 'renter_client_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get display name with registration number
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->name . ' (' . $this->registration_number . ')';
    }

    /**
     * Get vessel type display name from app_types if available
     */
    public function getTypeDisplayAttribute(): string
    {
        if (!$this->type) {
            return 'N/A';
        }
        
        // Try to get from app_types table if it exists
        $appType = \App\Models\AppType::where('group', 'vessel_types')
                                      ->where('code', $this->type)
                                      ->first();
                                      
        return $appType ? $appType->label : $this->type;
    }

    /**
     * Scope for vessels with a specific owner
     */
    public function scopeOwnedBy($query, $userId)
    {
        return $query->where('owner_client_id', $userId);
    }

    /**
     * Scope for vessels with a specific renter
     */
    public function scopeRentedBy($query, $userId)
    {
        return $query->where('renter_client_id', $userId);
    }

    /**
     * Scope for active vessels only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}

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
}

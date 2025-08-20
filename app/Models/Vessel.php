<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vessel extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'user_id', 'name', 'registration_number', 'type', 'length', 'width', 'draft', 'specifications', 'is_active'
    ];

    protected $casts = [
        'specifications' => 'array',
        'is_active' => 'boolean',
        'length' => 'decimal:2',
        'width' => 'decimal:2',
        'draft' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}

<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'booking_number', 'user_id', 'vessel_id', 'slot_id',
        'start_date', 'end_date', 'hold_expires_at', 'status', 'booking_type',
        'total_amount', 'additional_data', 'notes'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'hold_expires_at' => 'datetime',
        'total_amount' => 'decimal:2',
        'additional_data' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vessel()
    {
        return $this->belongsTo(Vessel::class);
    }

    public function slot()
    {
        return $this->belongsTo(Slot::class);
    }

    public function logs()
    {
        return $this->hasMany(BookingLog::class);
    }

    public function invoices()
    {
        return $this->morphMany(Invoice::class, 'invoiceable');
    }
}

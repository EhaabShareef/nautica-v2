<?php

namespace App\Models;

use App\Models\User;
use App\Support\Dictionary;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id','vessel_id','property_id','block_id','zone_id','slot_id',
        'start_at','end_at','status','type','priority','hold_expires_at',
        'notes','admin_notes'
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'hold_expires_at' => 'datetime',
    ];

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function vessel()
    {
        return $this->belongsTo(Vessel::class);
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function block()
    {
        return $this->belongsTo(Block::class);
    }

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    public function slot()
    {
        return $this->belongsTo(Slot::class);
    }

    public function logs()
    {
        return $this->hasMany(BookingLog::class);
    }

    public function setStatusAttribute($value)
    {
        if (!Dictionary::isValid('booking_status', $value)) {
            throw new \InvalidArgumentException('Invalid booking status');
        }
        $this->attributes['status'] = $value;
    }
}

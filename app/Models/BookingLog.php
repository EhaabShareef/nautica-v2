<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingLog extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['booking_id','status','notes','created_at'];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * Get the booking this log belongs to.
     *
     * Defines an inverse one-to-many relationship to the Booking model using the `booking_id` foreign key.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Booking>
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}

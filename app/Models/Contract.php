<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = ['booking_id','status','effective_from','effective_to','terms_json','total'];

    protected $casts = [
        'effective_from' => 'date',
        'effective_to' => 'date',
        'terms_json' => 'array',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}

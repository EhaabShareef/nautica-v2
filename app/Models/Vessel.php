<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vessel extends Model
{
    use HasFactory;

    protected $fillable = ['client_id', 'name', 'length', 'width', 'attributes'];

    protected $casts = [
        'attributes' => 'array',
    ];

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}

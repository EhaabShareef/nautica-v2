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

    /**
     * Get the user that owns the vessel.
     *
     * Returns the Eloquent belongs-to relationship linking this vessel to its client
     * via the `client_id` foreign key.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    /**
     * Get the bookings associated with the vessel.
     *
     * Defines a one-to-many Eloquent relationship: a vessel can have multiple Booking records.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}

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
        'client_id','vessel_id','property_id','resource_id','slot_id',
        'start_at','end_at','status','type','priority','hold_expires_at',
        'notes','admin_notes'
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'hold_expires_at' => 'datetime',
    ];

    /**
     * Get the booking's client relationship.
     *
     * Returns the User that is the client for this booking via the `client_id` foreign key.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    /**
     * Get the vessel associated with this booking.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Vessel>
     */
    public function vessel()
    {
        return $this->belongsTo(Vessel::class);
    }

    /**
     * Get the property associated with this booking.
     *
     * Returns an Eloquent BelongsTo relationship to the Property model (the booking's property).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Get the resource associated with the booking.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }

    /**
     * Get the slot that this booking belongs to.
     *
     * Defines an inverse one-to-many (belongsTo) relationship to the Slot model.
     * Uses the conventional foreign key (`slot_id`) on the bookings table.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function slot()
    {
        return $this->belongsTo(Slot::class);
    }

    /**
     * Get the booking's logs.
     *
     * Returns a has-many relationship to BookingLog models linked to this booking.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function logs()
    {
        return $this->hasMany(BookingLog::class);
    }

    /**
     * Mutator for the `status` attribute.
     *
     * Validates the provided status against the `booking_statuses` dictionary and sets
     * the model's `status` attribute when valid. Throws an exception for invalid values.
     *
     * @param string $value The status value to assign.
     * @throws \InvalidArgumentException If the status is not a valid `booking_statuses` entry.
     * @return void
     */
    public function setStatusAttribute($value)
    {
        if (!Dictionary::isValid('booking_statuses', $value)) {
            throw new \InvalidArgumentException('Invalid booking status');
        }
        $this->attributes['status'] = $value;
    }
}

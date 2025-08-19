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

    /**
     * Get the booking that this contract belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Get the invoices for the contract.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany Relationship to Invoice models.
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}

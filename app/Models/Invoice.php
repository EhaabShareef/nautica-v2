<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = ['contract_id','booking_id','invoice_no','status','currency','total','issued_at','due_at'];

    protected $casts = [
        'issued_at' => 'datetime',
        'due_at' => 'datetime',
    ];

    /**
     * Get the contract that owns this invoice.
     *
     * Returns the inverse one-to-many Eloquent relation linking the invoice to its Contract.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    /**
     * Get the booking that this invoice belongs to.
     *
     * Defines an inverse one-to-many relation to the Booking model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Get the invoice line items for this invoice.
     *
     * Defines a one-to-many Eloquent relationship to App\Models\InvoiceLine.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function lines()
    {
        return $this->hasMany(InvoiceLine::class);
    }

    /**
     * Get the payments for this invoice.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany The payments associated with the invoice.
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = ['invoice_id','method','amount','paid_at','reference'];

    protected $casts = [
        'paid_at' => 'datetime',
    ];

    /**
     * Get the invoice this payment belongs to.
     *
     * Defines an inverse one-to-many relationship pointing to App\Models\Invoice.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}

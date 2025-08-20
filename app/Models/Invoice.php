<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'invoice_number', 'user_id', 'invoiceable_type', 'invoiceable_id',
        'issue_date', 'due_date', 'status', 'subtotal', 'tax_amount', 'total_amount',
        'billing_details', 'notes'
    ];

    protected $casts = [
        'issue_date' => 'date',
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'billing_details' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function invoiceable()
    {
        return $this->morphTo();
    }

    public function lines()
    {
        return $this->hasMany(InvoiceLine::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}

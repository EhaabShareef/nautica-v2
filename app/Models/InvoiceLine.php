<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceLine extends Model
{
    use HasFactory;

    protected $fillable = ['invoice_id','description','qty','unit_price','tax_rate','amount'];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}

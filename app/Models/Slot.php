<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slot extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'zone_id', 'name', 'code', 'length', 'width', 'depth', 'amenities', 'base_rate', 'is_active',
    ];

    protected $casts = [
        'amenities' => 'array',
        'is_active' => 'boolean',
        'length' => 'decimal:2',
        'width' => 'decimal:2',
        'depth' => 'decimal:2',
        'base_rate' => 'decimal:2',
    ];

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }
}

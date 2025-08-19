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
        'zone_id', 'code', 'location', 'max_loa_m', 'max_beam_m', 'max_draft_m', 'shore_power', 'is_active',
    ];

    protected $casts = [
        'shore_power' => 'boolean',
        'is_active' => 'boolean',
        'max_loa_m' => 'float',
        'max_beam_m' => 'float',
        'max_draft_m' => 'float',
    ];

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }
}

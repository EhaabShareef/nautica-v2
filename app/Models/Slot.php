<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slot extends Model
{
    use HasFactory;

    protected $fillable = ['resource_id', 'start_at', 'end_at', 'is_available'];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'is_available' => 'boolean',
    ];

    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }
}

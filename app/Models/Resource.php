<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    use HasFactory;

    protected $fillable = ['property_id', 'name', 'capacity', 'attributes'];

    protected $casts = [
        'attributes' => 'array',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function slots()
    {
        return $this->hasMany(Slot::class);
    }
}

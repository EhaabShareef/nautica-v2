<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppType extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id', 'group', 'code', 'label', 'extra', 'is_active',
    ];

    protected $casts = [
        'extra' => 'array',
        'is_active' => 'boolean',
    ];
}

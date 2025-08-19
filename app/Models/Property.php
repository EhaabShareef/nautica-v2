<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'address'];

    /**
     * Get the resources that belong to this property.
     *
     * Represents a one-to-many Eloquent relationship: a Property may have multiple Resource models.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function resources()
    {
        return $this->hasMany(Resource::class);
    }
}

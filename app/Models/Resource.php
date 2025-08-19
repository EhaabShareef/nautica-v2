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

    /**
     * Get the property that this resource belongs to.
     *
     * Returns a BelongsTo relationship linking the Resource to its parent Property.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Get the one-to-many relationship for slots associated with this resource.
     *
     * Defines that a Resource has many Slot models.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function slots()
    {
        return $this->hasMany(Slot::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Venue extends Model
{
    protected $fillable = ['name', 'address', 'city', 'capacity', 'layout_image'];

    protected $casts = [
        'capacity' => 'integer',
    ];

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    public function sections(): HasMany
    {
        return $this->hasMany(VenueSection::class);
    }
}

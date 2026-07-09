<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VenueSection extends Model
{
    protected $fillable = [
        'venue_id', 'name', 'capacity', 'remaining_capacity',
        'sold_count', 'price', 'description', 'color_code',
        'position_x', 'position_y', 'path_koordinat',
        'label_x', 'label_y',
    ];

    protected $casts = [
        'price'              => 'integer',
        'capacity'           => 'integer',
        'remaining_capacity' => 'integer',
        'sold_count'         => 'integer',
    ];

    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'section_id');
    }

    public function isSoldOut(): bool
    {
        return $this->remaining_capacity <= 0;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventSection extends Model
{
    protected $fillable = [
        'event_id', 'venue_section_id', 'price', 'quota',
        'remaining_quota', 'sold_count',
    ];

    protected $casts = [
        'price'           => 'integer',
        'quota'            => 'integer',
        'remaining_quota' => 'integer',
        'sold_count'       => 'integer',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function venueSection(): BelongsTo
    {
        return $this->belongsTo(VenueSection::class);
    }

    public function isSoldOut(): bool
    {
        return $this->remaining_quota <= 0;
    }
}

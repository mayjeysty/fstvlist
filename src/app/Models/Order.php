<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Order extends Model
{
    protected $fillable = [
        'user_id', 'event_id', 'section_id', 'qty', 'status', 'waiting_number',
        'booking_deadline', 'payment_deadline',
        'subtotal', 'service_fee', 'total_price',
        'midtrans_transaction_id', 'midtrans_order_id',
        'payment_channel', 'payment_type', 'snap_token',
        'paid_at', 'expired_at',
    ];

    protected $casts = [
        'booking_deadline'  => 'datetime',
        'payment_deadline'  => 'datetime',
        'paid_at'           => 'datetime',
        'expired_at'        => 'datetime',
    ];

    const STATUS_WAITING         = 'waiting';
    const STATUS_RESERVED        = 'reserved';
    const STATUS_WAITING_PAYMENT = 'waiting_payment';
    const STATUS_PAID            = 'paid';
    const STATUS_CANCELLED       = 'cancelled';
    const STATUS_EXPIRED         = 'expired';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(VenueSection::class, 'section_id');
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function isExpired(): bool
    {
        return in_array($this->status, [self::STATUS_EXPIRED, self::STATUS_CANCELLED]);
    }
}

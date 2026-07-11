<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Order extends Model
{
    protected $fillable = [
        'user_id', 'event_id', 'section_id', 'qty', 'status',
        'payment_deadline',
        'subtotal', 'service_fee', 'total_price',
        'gross_amount', 'settlement_time', 'fraud_status', 'transaction_status',
        'midtrans_transaction_id', 'midtrans_order_id',
        'payment_channel', 'payment_type', 'snap_token',
        'paid_at', 'expired_at',
    ];

    protected $casts = [
        'payment_deadline'  => 'datetime',
        'settlement_time'   => 'datetime',
        'paid_at'           => 'datetime',
        'expired_at'        => 'datetime',
    ];

    const STATUS_PENDING   = 'pending';
    const STATUS_PAID      = 'paid';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_EXPIRED   = 'expired';

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

    public function isPaid(): bool
    {
        return $this->status === self::STATUS_PAID;
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }
}

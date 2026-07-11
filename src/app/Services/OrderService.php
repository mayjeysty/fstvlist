<?php

namespace App\Services;

use App\Models\EventSection;
use App\Models\Order;
use App\Models\VenueSection;
use App\Services\PaymentService;
use Exception;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function create(int $userId, int $eventId, int $sectionId, int $qty): Order
    {
        if ($qty < 1 || $qty > 4) {
            throw new Exception('Jumlah tiket harus antara 1 dan 4.');
        }

        return DB::transaction(function () use ($userId, $eventId, $sectionId, $qty) {
            $eventSection = EventSection::where('event_id', $eventId)
                ->where('venue_section_id', $sectionId)
                ->lockForUpdate()
                ->first();

            if ($eventSection) {
                if ($eventSection->remaining_quota < $qty) {
                    throw new Exception('Kuota tidak mencukupi.');
                }
                $eventSection->decrement('remaining_quota', $qty);
                $price = $eventSection->price;
            } else {
                $section = VenueSection::lockForUpdate()->findOrFail($sectionId);
                if ($section->remaining_capacity < $qty) {
                    throw new Exception('Kuota tidak mencukupi.');
                }
                $section->decrement('remaining_capacity', $qty);
                $price = $section->price;
            }

            $subtotal   = $price * $qty;
            $serviceFee = (int) ($subtotal * config('ticketing.service_fee_rate'));

            return Order::create([
                'user_id'          => $userId,
                'event_id'         => $eventId,
                'section_id'       => $sectionId,
                'qty'              => $qty,
                'status'           => Order::STATUS_PENDING,
                'payment_deadline' => now()->addMinutes(15),
                'subtotal'         => $subtotal,
                'service_fee'      => $serviceFee,
                'total_price'      => $subtotal + $serviceFee,
            ]);
        });
    }

    public function proceedToPayment(Order $order): void
    {
        app(PaymentService::class)->createTransaction($order);
    }

    public function rollbackQuota(Order $order): void
    {
        DB::transaction(function () use ($order) {
            $qty = $order->tickets->isNotEmpty()
                ? $order->tickets->groupBy('section_id')->map->count()
                : collect([$order->section_id => $order->qty]);

            foreach ($qty as $sectionId => $count) {
                $eventSection = EventSection::where('event_id', $order->event_id)
                    ->where('venue_section_id', $sectionId)
                    ->lockForUpdate()
                    ->first();

                if ($eventSection) {
                    $eventSection->increment('remaining_quota', $count);
                } else {
                    VenueSection::lockForUpdate()->find($sectionId)
                        ?->increment('remaining_capacity', $count);
                }
            }

            $order->update(['status' => Order::STATUS_EXPIRED, 'expired_at' => now()]);
        });
    }
}

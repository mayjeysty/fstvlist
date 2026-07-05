<?php

namespace App\Services;

use App\Models\EventSection;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\VenueSection;
use Illuminate\Support\Str;

class TicketService
{
    /**
     * Generate tickets for a paid order.
     * $items = [['section_id' => X, 'qty' => Y], ...]
     */
    public function generate(Order $order, array $items): void
    {
        foreach ($items as $item) {
            $section = VenueSection::findOrFail($item['section_id']);

            for ($i = 0; $i < $item['qty']; $i++) {
                Ticket::create([
                    'order_id'    => $order->id,
                    'event_id'    => $order->event_id,
                    'section_id'  => $section->id,
                    'user_name'   => $order->user->name,
                    'user_email'  => $order->user->email,
                    'ticket_code' => strtoupper(config('ticketing.ticket_prefix') . Str::random(8)),
                    'qr_token'    => Str::uuid(),
                ]);
            }

            $section->increment('sold_count', $item['qty']);

            EventSection::where('event_id', $order->event_id)
                ->where('venue_section_id', $section->id)
                ->increment('sold_count', $item['qty']);
        }
    }
}

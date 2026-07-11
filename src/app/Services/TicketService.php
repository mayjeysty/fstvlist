<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Ticket;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class TicketService
{
    public function generate(Order $order): void
    {
        $latestTicket = Ticket::where('event_id', $order->event_id)
            ->whereNotNull('ticket_number')
            ->orderByDesc('id')
            ->first();

        $prefix  = config('ticketing.ticket_number_prefix', 'TKT');
        $padding = config('ticketing.ticket_number_padding', 6);

        if ($latestTicket && preg_match('/\d+$/', $latestTicket->ticket_number, $m)) {
            $counter = (int) $m[0] + 1;
        } else {
            $counter = 1;
        }

        for ($i = 0; $i < $order->qty; $i++) {
            $ticketNumber = $prefix . str_pad((string) $counter, $padding, '0', STR_PAD_LEFT);
            $counter++;

            Ticket::create([
                'order_id'      => $order->id,
                'event_id'      => $order->event_id,
                'section_id'    => $order->section_id,
                'user_name'     => $order->user->name,
                'user_email'    => $order->user->email,
                'ticket_code'   => strtoupper(config('ticketing.ticket_prefix') . Str::random(8)),
                'ticket_number' => $ticketNumber,
                'qr_token'      => (string) Str::uuid(),
            ]);
        }

        DB::statement("
            UPDATE venue_sections 
            SET sold_count = sold_count + ? 
            WHERE id = ?
        ", [$order->qty, $order->section_id]);

        DB::statement("
            UPDATE event_sections 
            SET sold_count = sold_count + ? 
            WHERE event_id = ? AND venue_section_id = ?
        ", [$order->qty, $order->event_id, $order->section_id]);
    }
}

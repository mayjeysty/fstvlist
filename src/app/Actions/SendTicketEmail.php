<?php

namespace App\Actions;

use App\Mail\TicketPurchasedMail;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendTicketEmail
{
    public function handle(Order $order): void
    {
        $order->load(['event.venue', 'tickets.section', 'user']);

        try {
            if ($this->shouldQueue()) {
                Mail::to($order->user->email)->queue(new TicketPurchasedMail($order));
            } else {
                Mail::to($order->user->email)->send(new TicketPurchasedMail($order));
            }

            $order->tickets()->update(['email_sent_at' => now()]);

            Log::info('Ticket email sent', [
                'order_id' => $order->id,
                'email'    => $order->user->email,
                'queued'   => $this->shouldQueue(),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send ticket email', [
                'order_id' => $order->id,
                'error'    => $e->getMessage(),
            ]);
        }
    }

    private function shouldQueue(): bool
    {
        $queueConnection = config('queue.default', 'sync');

        return $queueConnection !== 'sync';
    }
}

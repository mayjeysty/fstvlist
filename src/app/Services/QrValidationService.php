<?php

namespace App\Services;

use App\Models\Ticket;
use Exception;

class QrValidationService
{
    /**
     * Validate a QR token and check-in the ticket.
     *
     * @throws Exception on invalid or already used ticket
     */
    public function validate(string $qrToken, int $validatorUserId): Ticket
    {
        $ticket = Ticket::where('qr_token', $qrToken)
            ->with(['event', 'section', 'order'])
            ->first();

        if (! $ticket) {
            throw new Exception('Tiket tidak ditemukan.');
        }

        if ($ticket->order->status !== 'paid') {
            throw new Exception('Tiket belum dibayar.');
        }

        if ($ticket->checked_in_at !== null) {
            throw new Exception('Tiket sudah digunakan pada ' . $ticket->checked_in_at->format('d/m/Y H:i'));
        }

        $ticket->update([
            'checked_in_at' => now(),
            'checked_in_by' => $validatorUserId,
        ]);

        return $ticket->fresh(['event', 'section']);
    }
}

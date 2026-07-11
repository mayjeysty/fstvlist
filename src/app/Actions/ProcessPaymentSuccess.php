<?php

namespace App\Actions;

use App\Models\Order;
use App\Services\TicketService;
use App\Services\QrCodeService;
use App\Services\TicketPdfService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessPaymentSuccess
{
    public function __construct(
        protected TicketService $ticketService,
        protected QrCodeService $qrCodeService,
        protected TicketPdfService $ticketPdfService,
        protected SendTicketEmail $sendTicketEmail,
    ) {}

    public function handle(Order $order): void
    {
        DB::transaction(function () use ($order) {
            $this->ticketService->generate($order);
        });

        $order->load('tickets');

        foreach ($order->tickets as $ticket) {
            try {
                $this->qrCodeService->generate($ticket);
            } catch (\Exception $e) {
                Log::error('QR generation failed for ticket', [
                    'ticket_id' => $ticket->id,
                    'error'     => $e->getMessage(),
                ]);
            }
        }

        try {
            $this->ticketPdfService->generate($order);
        } catch (\Exception $e) {
            Log::error('PDF generation failed for order', [
                'order_id' => $order->id,
                'error'    => $e->getMessage(),
            ]);
        }

        $this->sendTicketEmail->handle($order);
    }
}

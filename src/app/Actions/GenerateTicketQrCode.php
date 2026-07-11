<?php

namespace App\Actions;

use App\Models\Ticket;
use App\Services\QrCodeService;

class GenerateTicketQrCode
{
    public function __construct(
        protected QrCodeService $qrCodeService,
    ) {}

    public function handle(Ticket $ticket): string
    {
        return $this->qrCodeService->generate($ticket);
    }
}

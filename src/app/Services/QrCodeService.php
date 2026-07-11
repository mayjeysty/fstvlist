<?php

namespace App\Services;

use App\Models\Ticket;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeService
{
    public function generate(Ticket $ticket): string
    {
        $qrContent = $ticket->qr_token;
        $fileName  = 'qr/' . $ticket->ticket_code . '.png';

        $image = QrCode::format('png')
            ->size(config('ticketing.qr_size', 300))
            ->errorCorrection(config('ticketing.qr_error_correction', 'H'))
            ->generate($qrContent);

        Storage::disk('public')->put($fileName, $image);

        $ticket->update(['qr_path' => $fileName]);

        return $fileName;
    }
}

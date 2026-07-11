<?php

namespace App\Services;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class TicketPdfService
{
    public function generate(Order $order): string
    {
        $order->load(['event.venue', 'tickets.section', 'user']);

        $fileName = 'pdfs/order-' . $order->id . '-' . time() . '.pdf';

        $pdf = Pdf::loadView('pdf.eticket', compact('order'))
            ->setPaper('a4', 'portrait');

        Storage::disk('public')->put($fileName, $pdf->output());

        $order->tickets()->update(['pdf_path' => $fileName]);

        return $fileName;
    }
}

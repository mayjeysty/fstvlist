<?php

namespace App\Mail;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class EticketMail extends Mailable
{
    public Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order->load(['event.venue', 'tickets.section']);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'E-Ticket — ' . $this->order->event->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.eticket',
            with: ['order' => $this->order],
        );
    }

    public function attachments(): array
    {
        $pdf = Pdf::loadView('pdf.eticket', ['order' => $this->order])
            ->setPaper('a4', 'portrait');

        return [
            Attachment::fromData(fn () => $pdf->output(), 'E-Ticket_' . $this->order->event->name . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}

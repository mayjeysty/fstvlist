<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; margin: 0; padding: 24px; color: #18181b; }
        .header { text-align: center; border-bottom: 3px solid #1e1b4b; padding-bottom: 12px; margin-bottom: 16px; }
        .header h1 { font-size: 22px; margin: 0; color: #1e1b4b; }
        .header p { font-size: 13px; margin: 4px 0 0; color: #71717a; }
        .event-info { margin-bottom: 16px; }
        .event-info h2 { font-size: 16px; margin: 0 0 4px; }
        .event-info p { font-size: 12px; margin: 2px 0; color: #52525b; }
        .tickets {  }
        .ticket { border: 1px solid #d4d4d8; border-radius: 6px; padding: 12px; margin-bottom: 12px; page-break-inside: avoid; }
        .ticket-header { margin-bottom: 8px; }
        .ticket-code { font-family: monospace; font-size: 13px; font-weight: bold; color: #4f46e5; }
        .ticket-zone { font-size: 12px; color: #71717a; float: right; }
        .qr-wrapper { text-align: center; margin: 8px 0; }
        .qr-wrapper img { width: 120px; height: 120px; }
        .ticket-info { font-size: 11px; color: #a1a1aa; text-align: center; margin-top: 6px; }
        .footer { text-align: center; font-size: 11px; color: #a1a1aa; margin-top: 20px; padding-top: 12px; border-top: 1px solid #e4e4e7; }
        .total { text-align: right; font-size: 14px; font-weight: bold; margin-top: 12px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>E-Ticket</h1>
        <p>FSTVLIST</p>
    </div>

    <div class="event-info">
        <h2>{{ $order->event->name }}</h2>
        <p>{{ $order->event->venue->name }} &middot; {{ $order->event->start_time->format('d M Y, H:i') }} WIB</p>
        <p>Pemesan: {{ $order->user->name }} ({{ $order->user->email }})</p>
        <p>Jumlah Tiket: {{ $order->tickets->count() }}</p>
    </div>

    <div class="tickets">
        @foreach($order->tickets as $index => $ticket)
            <div class="ticket">
                <div class="ticket-header">
                    <span class="ticket-code">{{ $ticket->ticket_code }}</span>
                    <span class="ticket-zone">Zona: {{ $ticket->section->name }}</span>
                </div>
                <div class="qr-wrapper">
                    <img src="data:image/svg+xml;base64,{{ base64_encode(
                        \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
                            ->size(200)
                            ->errorCorrection('H')
                            ->generate($ticket->qr_token)
                    ) }}" alt="QR Code">
                </div>
                <div class="ticket-info">
                    {{ $ticket->user_name }} &middot; ID: {{ $ticket->qr_token }}
                </div>
            </div>
        @endforeach
    </div>

    <div class="total">
        Total: Rp {{ number_format($order->total_price, 0, ',', '.') }}
    </div>

    <div class="footer">
        Tunjukkan QR code ini di pintu masuk untuk validasi.<br>
        Setiap tiket hanya berlaku untuk satu kali pemindaian.
    </div>
</body>
</html>

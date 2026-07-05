<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f5; margin: 0; padding: 0; }
        .container { max-width: 560px; margin: 0 auto; padding: 24px 16px; }
        .header { background: #1e1b4b; color: #fff; text-align: center; padding: 24px 16px; border-radius: 12px 12px 0 0; }
        .header h1 { margin: 0; font-size: 20px; }
        .header p { margin: 4px 0 0; font-size: 13px; color: #a5b4fc; }
        .body { background: #fff; padding: 24px 16px; border-radius: 0 0 12px 12px; }
        .event-info { margin-bottom: 16px; }
        .event-info h2 { margin: 0 0 4px; font-size: 18px; color: #1e1b4b; }
        .event-info p { margin: 2px 0; font-size: 13px; color: #71717a; }
        .ticket { border: 1px solid #e4e4e7; border-radius: 8px; padding: 16px; margin-bottom: 12px; }
        .ticket-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; }
        .ticket-code { font-family: monospace; font-size: 14px; font-weight: bold; color: #4f46e5; }
        .ticket-zone { font-size: 13px; color: #71717a; }
        .qr-wrapper { text-align: center; margin-bottom: 8px; }
        .qr-wrapper img { width: 140px; height: 140px; padding: 8px; background: #fff; border-radius: 8px; border: 1px solid #e4e4e7; }
        .ticket-footer { font-size: 11px; color: #a1a1aa; text-align: center; }
        .btn { display: inline-block; background: #4f46e5; color: #fff; text-decoration: none; padding: 12px 24px; border-radius: 8px; font-size: 14px; font-weight: 600; margin-top: 16px; }
        .footer-note { font-size: 11px; color: #a1a1aa; text-align: center; margin-top: 16px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>E-Ticket</h1>
            <p>{{ $order->event->name }}</p>
        </div>
        <div class="body">
            <div class="event-info">
                <h2>{{ $order->event->name }}</h2>
                <p>{{ $order->event->venue->name }} &middot; {{ $order->event->start_time->format('d M Y, H:i') }}</p>
                <p>Jumlah Tiket: {{ $order->tickets->count() }}</p>
            </div>

            @foreach($order->tickets as $ticket)
                <div class="ticket">
                    <div class="ticket-header">
                        <span class="ticket-code">{{ $ticket->ticket_code }}</span>
                        <span class="ticket-zone">{{ $ticket->section->name }}</span>
                    </div>
                    <div class="qr-wrapper">
                        <img src="data:image/svg+xml;base64,{{ base64_encode(
                            \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
                                ->size(200)
                                ->errorCorrection('H')
                                ->generate($ticket->qr_token)
                        ) }}" alt="QR Code">
                    </div>
                    <div class="ticket-footer">
                        {{ $ticket->user_name }} &middot; {{ $ticket->user_email }}
                    </div>
                </div>
            @endforeach

            <div style="text-align:center;">
                <a href="{{ route('tickets.show', $order) }}" class="btn">Lihat E-Ticket di Browser</a>
            </div>

            <p class="footer-note">
                Tunjukkan QR code ini di pintu masuk untuk validasi.<br>
                E-Ticket juga terlampir dalam bentuk PDF di email ini.
            </p>
        </div>
    </div>
</body>
</html>

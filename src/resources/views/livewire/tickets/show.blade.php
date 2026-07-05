@php
    $tz = config('ticketing.timezone_label');
    $doorsOpenHours = config('ticketing.doors_open_offset_hours');
    $taxRate = config('ticketing.tax_rate');
    $orderPrefix = config('ticketing.order_prefix');
    $orderCode = $orderPrefix . $order->event->start_time->format('Y-m-d') . '-' . str_pad($order->id, 4, '0', STR_PAD_LEFT);
    $ticket = $order->tickets[$activeTicketIndex] ?? $order->tickets->first();
@endphp
<div>
    <div class="ds-stepper ds-mb-6">
        <span class="ds-stepper__step ds-stepper__step--completed">
            <span class="ds-stepper__circle"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></span>
            <span class="ds-stepper__label">Pilih Zona</span>
        </span>
        <span class="ds-stepper__connector ds-stepper__connector--completed"></span>
        <span class="ds-stepper__step ds-stepper__step--completed">
            <span class="ds-stepper__circle"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></span>
            <span class="ds-stepper__label">Data diri</span>
        </span>
        <span class="ds-stepper__connector ds-stepper__connector--completed"></span>
        <span class="ds-stepper__step ds-stepper__step--completed">
            <span class="ds-stepper__circle"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></span>
            <span class="ds-stepper__label">Pembayaran</span>
        </span>
        <span class="ds-stepper__connector ds-stepper__connector--completed"></span>
        <span class="ds-stepper__step ds-stepper__step--completed">
            <span class="ds-stepper__circle"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></span>
            <span class="ds-stepper__label">Selesai</span>
        </span>
    </div>

    {{-- HERO --}}
    <div class="ds-relative ds-overflow-hidden" style="background:#000;border-radius:var(--radius-2xl);padding:var(--space-8) var(--space-6);margin-bottom:var(--space-6);{{ $order->event->banner ? 'background-image:url(' . Storage::url($order->event->banner) . ');background-size:cover;background-position:center;' : '' }}">
        <div class="ds-absolute" style="inset:0;{{ $order->event->banner ? 'background:linear-gradient(135deg, rgba(0,0,0,0.85) 0%, rgba(0,0,0,0.4) 100%);' : 'background:radial-gradient(ellipse at 50% 0%, rgba(232,255,0,0.12) 0%, transparent 60%);' }}pointer-events:none;"></div>
        <div class="ds-absolute" style="top:-40px;right:-40px;width:160px;height:160px;background:radial-gradient(circle, rgba(232,255,0,0.06) 0%, transparent 60%);pointer-events:none;"></div>
        <div class="ds-relative" style="z-index:1;">
            <h1 style="font-family:'Fraunces',Georgia,serif;font-size:1.75rem;font-weight:900;color:#fff;line-height:1.15;margin-bottom:var(--space-2);">
                Tiket kamu sudah <span style="color:#E8FF00;">siap!</span>
            </h1>
            <p class="ds-text-xs" style="color:rgba(255,255,255,0.5);max-width:420px;line-height:1.5;">Pesanan dikonfirmasi. QR Code tiket akan dikirim ke emailmu — tunjukkan saat masuk venue.</p>
            <div class="ds-inline-flex ds-items-center ds-gap-2 ds-mt-3" style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.08);border-radius:var(--radius-pill);padding:var(--space-1) var(--space-3) var(--space-1) var(--space-2);">
                <span class="ds-dot-success" style="width:6px;height:6px;border-radius:50%;animation:pulse 1s infinite;"></span>
                <span style="font-size:9px;color:rgba(255,255,255,0.5);letter-spacing:0.02em;">E-tiket (PDF + QR Code) dikirim ke {{ $order->user->email }}</span>
            </div>
        </div>
    </div>

    {{-- MAIN LAYOUT --}}
    <div class="ds-grid ds-grid-2" style="gap:var(--space-5);align-items:start;">

        {{-- LEFT: Ticket Card --}}
        <div>
            <div>
                {{-- Top (black) --}}
                <div style="background:#000;color:#fff;padding:var(--space-5);border-radius:var(--radius-lg) var(--radius-lg) 0 0;">
                    <h2 style="font-family:'Fraunces',Georgia,serif;font-size:1.25rem;font-weight:900;margin-bottom:var(--space-3);">{{ $order->event->name }}</h2>
                    <div class="ds-grid ds-grid-2" style="gap:var(--space-2) var(--space-4);font-size:10px;">
                        <div>
                            <span class="ds-uppercase ds-block" style="font-weight:700;color:rgba(255,255,255,0.35);letter-spacing:0.06em;margin-bottom:1px;">Tanggal</span>
                            <span style="color:#fff;font-weight:500;">{{ $order->event->start_time->isoFormat('dddd, D MMMM YYYY') }}</span>
                        </div>
                        <div>
                            <span class="ds-uppercase ds-block" style="font-weight:700;color:rgba(255,255,255,0.35);letter-spacing:0.06em;margin-bottom:1px;">Waktu mulai</span>
                            <span style="color:#fff;font-weight:500;">{{ $order->event->start_time->format('H.i') }} {{ $tz }}</span>
                        </div>
                        <div>
                            <span class="ds-uppercase ds-block" style="font-weight:700;color:rgba(255,255,255,0.35);letter-spacing:0.06em;margin-bottom:1px;">Pintu dibuka</span>
                            <span style="color:#fff;font-weight:500;">{{ $order->event->start_time->copy()->subHours($doorsOpenHours)->format('H.i') }} {{ $tz }}</span>
                        </div>
                        <div>
                            <span class="ds-uppercase ds-block" style="font-weight:700;color:rgba(255,255,255,0.35);letter-spacing:0.06em;margin-bottom:1px;">Venue</span>
                            <span style="color:#fff;font-weight:500;">{{ $order->event->venue->name }}</span>
                        </div>
                    </div>
                    <div style="margin-top:var(--space-1);font-size:10px;">
                        <span class="ds-uppercase ds-block" style="font-weight:700;color:rgba(255,255,255,0.35);letter-spacing:0.06em;margin-bottom:1px;">Alamat</span>
                        <span style="color:rgba(255,255,255,0.6);">{{ $order->event->venue->address }}</span>
                    </div>
                </div>

                {{-- Zone pill + qty + organizer --}}
                <div class="ds-flex ds-items-center" style="background:#000;padding:0 var(--space-5) var(--space-3) var(--space-5);gap:var(--space-3);">
                    <span class="ds-badge ds-badge--brand" style="font-size:9px;letter-spacing:0.15em;padding:2px 10px;border-radius:2px;">{{ $ticket->section->name ?? $order->section->name }}</span>
                    <span style="font-size:9px;color:rgba(255,255,255,0.4);">{{ $order->qty }} tiket dipesan</span>
                    <span class="ds-ml-auto" style="font-size:9px;color:rgba(255,255,255,0.25);">Penyelenggara: {{ $order->event->name }}</span>
                </div>

                {{-- Perforation --}}
                <div class="ds-relative ds-overflow-hidden" style="height:20px;background:#000;">
                    <div class="ds-absolute" style="inset:0;border-top:1px dashed rgba(255,255,255,0.15);"></div>
                    <div class="ds-absolute" style="left:-8px;top:50%;transform:translateY(-50%);width:16px;height:16px;border-radius:50%;background:var(--color-bg-primary);"></div>
                    <div class="ds-absolute" style="right:-8px;top:50%;transform:translateY(-50%);width:16px;height:16px;border-radius:50%;background:var(--color-bg-primary);"></div>
                </div>

                {{-- Bottom (cream) --}}
                <div class="ds-bg-secondary ds-border" style="border-top:none;border-radius:0 0 var(--radius-lg) var(--radius-lg);padding:var(--space-5);">

                    {{-- QR info strip --}}
                    <div class="ds-flex ds-items-center ds-gap-3 ds-mb-4" style="background:#C8A84E;border-radius:var(--radius-sm);padding:var(--space-3) var(--space-4);">
                        <div class="ds-flex ds-flex-center" style="width:32px;height:32px;background:rgba(0,0,0,0.1);border-radius:4px;flex-shrink:0;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><line x1="3" y1="10" x2="3" y2="14"/><line x1="10" y1="3" x2="14" y2="3"/><line x1="21" y1="10" x2="21" y2="14"/><line x1="10" y1="21" x2="14" y2="21"/></svg>
                        </div>
                        <div style="flex:1;">
                            <p class="ds-uppercase ds-font-bold" style="font-size:10px;color:#000;letter-spacing:0.05em;">QR Code dikirim via email</p>
                            <p style="font-size:9px;color:rgba(0,0,0,0.55);margin-top:1px;line-height:1.4;">Buka emailmu di {{ $order->user->email }} — scan QR saat masuk venue. Satu QR hanya bisa digunakan sekali.</p>
                        </div>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;"><polyline points="9 18 15 12 9 6"/></svg>
                    </div>

                    {{-- 2x3 detail grid --}}
                    <div class="ds-grid ds-grid-3" style="gap:var(--space-3);font-size:10px;">
                        <div>
                            <p class="ds-uppercase ds-font-bold ds-text-tertiary" style="letter-spacing:0.08em;margin-bottom:1px;">Nama pemesan</p>
                            <p class="ds-font-semibold" style="color:#000;">{{ $ticket->user_name }}</p>
                        </div>
                        <div>
                            <p class="ds-uppercase ds-font-bold ds-text-tertiary" style="letter-spacing:0.08em;margin-bottom:1px;">Zona</p>
                            <p class="ds-font-semibold" style="color:#000;">{{ $ticket->section->name ?? $order->section->name }}</p>
                        </div>
                        <div>
                            <p class="ds-uppercase ds-font-bold ds-text-tertiary" style="letter-spacing:0.08em;margin-bottom:1px;">Jumlah tiket</p>
                            <p class="ds-font-semibold" style="color:#000;">{{ $order->qty }} tiket</p>
                        </div>
                        <div>
                            <p class="ds-uppercase ds-font-bold ds-text-tertiary" style="letter-spacing:0.08em;margin-bottom:1px;">Harga per tiket</p>
                            <p class="ds-font-semibold" style="color:#000;">Rp {{ number_format(intval($order->subtotal / max($order->qty, 1)), 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <p class="ds-uppercase ds-font-bold ds-text-tertiary" style="letter-spacing:0.08em;margin-bottom:1px;">Kategori</p>
                            <p class="ds-font-semibold" style="color:#000;">{{ $ticket->section->description ?? $ticket->section->name ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="ds-uppercase ds-font-bold ds-text-tertiary" style="letter-spacing:0.08em;margin-bottom:1px;">Status tiket</p>
                            <p class="ds-font-semibold ds-text-success ds-flex ds-items-center" style="gap:4px;"><span class="ds-badge-dot ds-badge-dot--success"></span>Aktif</p>
                        </div>
                    </div>

                    {{-- Ticket IDs --}}
                    <div class="ds-border-t ds-mt-4" style="padding-top:var(--space-3);font-size:9px;color:var(--color-text-tertiary);">
                        ID Tiket:
                        @foreach($order->tickets as $t)
                            <span class="ds-font-semibold" style="font-family:monospace;">{{ $t->ticket_code }}</span>@if(!$loop->last) <span style="color:var(--color-border);">·</span> @endif
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Ticket Selector --}}
            @if($order->tickets->count() > 1)
            <div class="ds-text-center ds-mt-5">
                <p class="ds-caption ds-mb-3">Pilih tiket untuk lihat detail</p>
                <div class="ds-flex ds-justify-center ds-gap-2">
                    @foreach($order->tickets as $idx => $t)
                    <button wire:click="switchToTicket({{ $idx }})" class="ds-flex ds-items-center ds-gap-2 ds-font-semibold" style="padding:var(--space-2) var(--space-4);border-radius:var(--radius-pill);border:1px solid {{ $idx === $activeTicketIndex ? '#000' : 'var(--color-border)' }};background:{{ $idx === $activeTicketIndex ? '#000' : 'transparent' }};color:{{ $idx === $activeTicketIndex ? '#fff' : 'var(--color-text-primary)' }};font-size:10px;cursor:pointer;transition:all 0.15s;">
                        <span>Tiket {{ $idx + 1 }}</span>
                        <span style="opacity:0.6;font-family:monospace;font-size:8px;">{{ Str::limit($t->ticket_code, 16) }}</span>
                    </button>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        {{-- RIGHT: Sidebar --}}
        <div style="position:sticky;top:var(--space-8);">

            {{-- Ringkasan pesanan --}}
            <div class="ds-card-summary ds-card-summary--elevated ds-mb-4">
                <p class="ds-card-summary__title">Ringkasan pesanan</p>
                <div class="ds-mb-3">
                    <p class="ds-font-semibold ds-text-small">{{ $order->event->name }}</p>
                    <p class="ds-text-xs ds-text-tertiary">{{ $order->event->venue->name }}, {{ $order->event->venue->city ?? $order->event->venue->address }}</p>
                    <p class="ds-text-xs ds-text-tertiary">{{ $order->event->start_time->isoFormat('dddd, D MMM YYYY') }} · {{ $order->event->start_time->format('H.i') }} {{ $tz }}</p>
                </div>
                <div class="ds-flex-col" style="display:flex;gap:var(--space-2);font-size:var(--text-small);">
                    <div class="ds-card-summary__row">
                        <span class="ds-card-summary__label">{{ $order->section->name ?? '—' }} × {{ $order->qty }}</span>
                        <span class="ds-card-summary__value">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="ds-card-summary__row">
                        <span class="ds-card-summary__label">Biaya layanan</span>
                        <span class="ds-card-summary__value">Rp {{ number_format($order->service_fee, 0, ',', '.') }}</span>
                    </div>
                    @php $tax = intval($order->subtotal * $taxRate); @endphp
                    <div class="ds-card-summary__row">
                        <span class="ds-card-summary__label">Pajak ({{ intval($taxRate * 100) }}%)</span>
                        <span class="ds-card-summary__value">Rp {{ number_format($tax, 0, ',', '.') }}</span>
                    </div>
                    <hr class="ds-divider">
                    <div class="ds-card-summary__total-row">
                        <span class="ds-card-summary__total-label">Total dibayar</span>
                        <span class="ds-card-summary__total-value">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            {{-- ID Pesanan --}}
            <div class="ds-card-summary ds-card-summary--elevated ds-mb-4 ds-flex ds-items-center ds-justify-between" style="padding:var(--space-4) var(--space-5);">
                <div>
                    <p class="ds-caption">ID Pesanan</p>
                    <p class="ds-font-semibold" style="font-family:monospace;font-size:var(--text-xs);">{{ $orderCode }}</p>
                </div>
                <button onclick="navigator.clipboard.writeText('{{ $orderCode }}');this.textContent='Tersalin!';setTimeout(()=>this.textContent='Salin',2000)" class="ds-btn ds-btn--sm ds-btn--secondary" style="flex-shrink:0;white-space:nowrap;">Salin</button>
            </div>

            {{-- E-tiket status --}}
            <div class="ds-card-summary ds-card-summary--elevated ds-mb-4" style="padding:var(--space-4) var(--space-5);">
                <div class="ds-flex ds-items-center ds-gap-2 ds-mb-1">
                    <span class="ds-badge-dot ds-badge-dot--success" style="animation:blink 1s infinite;"></span>
                    <p class="ds-text-success ds-font-bold" style="font-size:10px;letter-spacing:0.05em;">E-tiket sedang dikirim</p>
                </div>
                <p class="ds-text-xs ds-text-tertiary" style="line-height:1.4;">PDF + QR Code dikirim ke {{ $order->user->email }}. Cek inbox dalam 1–2 menit. Jika tidak ada, cek folder Spam.</p>
            </div>

            {{-- Action buttons --}}
            <div class="ds-flex ds-flex-col ds-gap-2 ds-mb-4">
                <button wire:click="download" class="ds-btn ds-btn--primary ds-btn--block">
                    <svg class="ds-btn__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    Unduh E-Tiket PDF
                </button>
                <a href="{{ route('orders.index') }}" class="ds-btn ds-btn--secondary ds-btn--block">Lihat riwayat transaksi</a>
                <a href="{{ route('home') }}" class="ds-btn ds-btn--secondary ds-btn--block">Kembali ke beranda</a>
            </div>

            {{-- 3-step guide --}}
            <div class="ds-card-summary">
                <p class="ds-card-summary__title">Yang perlu kamu lakukan</p>
                <div class="ds-flex ds-flex-col ds-gap-3">
                    <div class="ds-flex" style="align-items:flex-start;gap:var(--space-3);">
                        <span class="ds-flex ds-flex-center ds-font-bold" style="width:22px;height:22px;border-radius:50%;background:#000;color:#E8FF00;font-size:10px;flex-shrink:0;">1</span>
                        <div>
                            <p class="ds-text-xs ds-font-semibold">Cek emailmu</p>
                            <p class="ds-text-xs ds-text-tertiary" style="line-height:1.4;">QR Code dikirim ke {{ $order->user->email }}. Simpan atau screenshot untuk hari acara.</p>
                        </div>
                    </div>
                    <div class="ds-flex" style="align-items:flex-start;gap:var(--space-3);">
                        <span class="ds-flex ds-flex-center ds-font-bold" style="width:22px;height:22px;border-radius:50%;background:#000;color:#E8FF00;font-size:10px;flex-shrink:0;">2</span>
                        <div>
                            <p class="ds-text-xs ds-font-semibold">Datang sebelum jam {{ $order->event->start_time->copy()->subHours($doorsOpenHours)->format('H.i') }}</p>
                            <p class="ds-text-xs ds-text-tertiary" style="line-height:1.4;">Pintu venue {{ $order->event->venue->name }} dibuka pukul {{ $order->event->start_time->copy()->subHours($doorsOpenHours)->format('H.i') }} {{ $tz }}. Hindari antrean panjang dengan datang lebih awal.</p>
                        </div>
                    </div>
                    <div class="ds-flex" style="align-items:flex-start;gap:var(--space-3);">
                        <span class="ds-flex ds-flex-center ds-font-bold" style="width:22px;height:22px;border-radius:50%;background:#000;color:#E8FF00;font-size:10px;flex-shrink:0;">3</span>
                        <div>
                            <p class="ds-text-xs ds-font-semibold">Tunjukkan QR Code</p>
                            <p class="ds-text-xs ds-text-tertiary" style="line-height:1.4;">Petugas gerbang akan scan QR dari email atau cetakan kamu. Tiket hanya berlaku sekali.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>@keyframes pulse { 0%,100% { opacity:1; } 50% { opacity:0.3; } } @keyframes blink { 0%,100% { opacity:1; } 50% { opacity:0.3; } }</style>
</div>
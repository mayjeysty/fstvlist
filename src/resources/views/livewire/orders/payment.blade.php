@php
    $tz = config('ticketing.timezone_label');
    $doorsOpenHours = config('ticketing.doors_open_offset_hours');
    $taxRate = config('ticketing.tax_rate');
    $orderPrefix = config('ticketing.order_prefix');
    $orderCode = $orderPrefix . $order->event->start_time->format('Y-m-d') . '-' . str_pad($order->id, 4, '0', STR_PAD_LEFT);
    $ticket = $order->tickets[$activeTicketIndex] ?? $order->tickets->first();
@endphp
<div>
    {{-- STEPPER --}}
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
        <span class="ds-stepper__connector {{ $showTicket ? 'ds-stepper__connector--completed' : '' }}"></span>
        <span class="ds-stepper__step {{ $showTicket ? 'ds-stepper__step--completed' : 'ds-stepper__step--active' }}">
            <span class="ds-stepper__circle">
                @if($showTicket)
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                @else
                    3
                @endif
            </span>
            <span class="ds-stepper__label">Pembayaran</span>
        </span>
        <span class="ds-stepper__connector {{ $showTicket ? 'ds-stepper__connector--completed' : '' }}"></span>
        <span class="ds-stepper__step {{ $showTicket ? 'ds-stepper__step--completed' : 'ds-stepper__step--inactive' }}">
            <span class="ds-stepper__circle">
                @if($showTicket)
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                @else
                    4
                @endif
            </span>
            <span class="ds-stepper__label">Selesai</span>
        </span>
    </div>

    @if($errors->any())
        <div class="ds-bg-error ds-text-error ds-text-xs ds-radius-md ds-p-3 ds-mb-4" style="border:1px solid rgba(226,75,74,0.25);">{{ $errors->first() }}</div>
    @endif

    {{-- ================================================================
         MODE: TICKET DETAIL (after successful payment)
         ================================================================ --}}
    @if($showTicket)
    {{-- HERO --}}
    <div class="ds-relative ds-overflow-hidden" style="background:#000;border-radius:var(--radius-2xl);padding:var(--space-8) var(--space-6);margin-bottom:var(--space-6);{{ $order->event->banner ? 'background-image:url(' . Storage::url($order->event->banner) . ');background-size:cover;background-position:center;' : '' }}">
        <div class="ds-absolute" style="inset:0;{{ $order->event->banner ? 'background:linear-gradient(135deg, rgba(0,0,0,0.85) 0%, rgba(0,0,0,0.4) 100%);' : 'background:radial-gradient(ellipse at 50% 0%, rgba(232,255,0,0.12) 0%, transparent 60%);' }}pointer-events:none;"></div>
        <div class="ds-relative" style="z-index:1;">
            <h1 style="font-family:'ClashDisplay-Semibold','Fraunces',Georgia,serif;font-size:1.75rem;font-weight:650;color:#fff;line-height:1.15;margin-bottom:var(--space-2);">
                Tiket kamu sudah <span style="color:#E8FF00;">siap!</span>
            </h1>
            <p class="ds-text-xs" style="color:rgba(255,255,255,0.5);max-width:420px;line-height:1.5;">QR code tiket sudah dikirim ke <strong style="color:rgba(255,255,255,0.7);">{{ $order->user->email }}</strong> — tunjukkan saat masuk venue.</p>
        </div>
    </div>

    <div class="ds-grid ds-grid-2" style="gap:var(--space-5);align-items:start;">
        {{-- LEFT: Ticket Card --}}
        <div>
            <div>
                {{-- Top (black) --}}
                <div style="background:#000;color:#fff;padding:var(--space-5);border-radius:var(--radius-lg) var(--radius-lg) 0 0;">
                    <h2 style="font-family:'ClashDisplay-Semibold','Fraunces',Georgia,serif;font-size:1.25rem;font-weight:650;margin-bottom:var(--space-3);">{{ $order->event->name }}</h2>
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

                {{-- Zone pill + qty --}}
                <div class="ds-flex ds-items-center" style="background:#000;padding:0 var(--space-5) var(--space-3) var(--space-5);gap:var(--space-3);">
                    <span class="ds-badge ds-badge--brand" style="font-size:9px;letter-spacing:0.15em;padding:2px 10px;border-radius:2px;">{{ $ticket->section->name ?? $order->section->name }}</span>
                    <span style="font-size:9px;color:rgba(255,255,255,0.4);">{{ $order->qty }} tiket dipesan</span>
                    <span class="ds-ml-auto" style="font-size:9px;color:rgba(255,255,255,0.25);">Penyelenggara: {{ $order->event->venue->name }}</span>
                </div>

                {{-- Perforation --}}
                <div class="ds-relative ds-overflow-hidden" style="height:20px;background:#000;">
                    <div class="ds-absolute" style="inset:0;border-top:1px dashed rgba(255,255,255,0.15);"></div>
                    <div class="ds-absolute" style="left:-8px;top:50%;transform:translateY(-50%);width:16px;height:16px;border-radius:50%;background:var(--color-bg-primary);"></div>
                    <div class="ds-absolute" style="right:-8px;top:50%;transform:translateY(-50%);width:16px;height:16px;border-radius:50%;background:var(--color-bg-primary);"></div>
                </div>

                {{-- Bottom (cream) --}}
                <div class="ds-bg-secondary ds-border" style="border-top:none;border-radius:0 0 var(--radius-lg) var(--radius-lg);padding:var(--space-5);">
                    <div class="ds-flex ds-items-center ds-gap-3 ds-mb-4" style="background:#C8A84E;border-radius:var(--radius-sm);padding:var(--space-3) var(--space-4);">
                        <div class="ds-flex ds-flex-center" style="width:32px;height:32px;background:rgba(0,0,0,0.1);border-radius:4px;flex-shrink:0;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                        </div>
                        <div style="flex:1;">
                            <p class="ds-uppercase ds-font-bold" style="font-size:10px;color:#000;letter-spacing:0.05em;">QR Code dikirim via email</p>
                            <p style="font-size:9px;color:rgba(0,0,0,0.55);margin-top:1px;line-height:1.4;">Buka emailmu di {{ $order->user->email }} — scan QR saat masuk venue. Satu QR hanya bisa digunakan sekali.</p>
                        </div>
                    </div>

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

                    <div class="ds-border-t ds-mt-4" style="padding-top:var(--space-3);font-size:9px;color:var(--color-text-tertiary);">
                        ID Tiket:
                        @foreach($order->tickets as $t)
                            <span class="ds-font-semibold" style="font-family:monospace;">{{ $t->ticket_code }}</span>@if(!$loop->last) <span style="color:var(--color-border);">·</span> @endif
                        @endforeach
                    </div>
                </div>
            </div>

            @if($order->tickets->count() > 1)
            <div class="ds-text-center ds-mt-5">
                <p class="ds-caption ds-mb-3">Pilih tiket untuk lihat detail</p>
                <div class="ds-flex ds-justify-center ds-gap-2">
                    @foreach($order->tickets as $idx => $t)
                    <button wire:click="switchToTicket({{ $idx }})" style="padding:var(--space-2) var(--space-4);border-radius:var(--radius-pill);border:1px solid {{ $idx === $activeTicketIndex ? '#000' : 'var(--color-border)' }};background:{{ $idx === $activeTicketIndex ? '#000' : 'transparent' }};color:{{ $idx === $activeTicketIndex ? '#fff' : 'var(--color-text-primary)' }};font-size:10px;cursor:pointer;font-weight:600;transition:all 0.15s;">
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
            <div class="ds-card-summary ds-card-summary--elevated ds-mb-4">
                <p class="ds-card-summary__title">Ringkasan pesanan</p>
                <div class="ds-mb-3">
                    <p class="ds-font-semibold ds-text-small">{{ $order->event->name }}</p>
                    <p class="ds-text-xs ds-text-tertiary">{{ $order->event->venue->name }}, {{ $order->event->venue->city ?? '' }}</p>
                    <p class="ds-text-xs ds-text-tertiary">{{ $order->event->start_time->isoFormat('dddd, D MMM YYYY') }} · {{ $order->event->start_time->format('H.i') }} {{ $tz }}</p>
                </div>
                <div style="display:flex;flex-direction:column;gap:var(--space-2);font-size:var(--text-small);">
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

            <div class="ds-card-summary ds-card-summary--elevated ds-mb-4 ds-flex ds-items-center ds-justify-between" style="padding:var(--space-4) var(--space-5);">
                <div>
                    <p class="ds-caption">ID Pesanan</p>
                    <p class="ds-font-semibold" style="font-family:monospace;font-size:var(--text-xs);">{{ $orderCode }}</p>
                </div>
                <button onclick="navigator.clipboard.writeText('{{ $orderCode }}');this.textContent='Tersalin!';setTimeout(()=>this.textContent='Salin',2000)" style="font-size:10px;font-weight:600;text-transform:uppercase;letter-spacing:0.06em;border:1px solid var(--color-border);border-radius:var(--radius-pill);padding:4px 12px;background:transparent;cursor:pointer;white-space:nowrap;transition:all 0.15s;">Salin</button>
            </div>

            <div class="ds-flex ds-flex-col ds-gap-2 ds-mb-6">
                <button wire:click="download" class="ds-btn ds-btn--primary ds-btn--block">
                    <svg class="ds-btn__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/></svg>
                    Unduh E-Tiket PDF
                </button>
            </div>

            <div class="ds-card-summary">
                <p class="ds-card-summary__title">Yang perlu kamu lakukan</p>
                <div style="display:flex;flex-direction:column;gap:var(--space-3);">
                    <div class="ds-flex" style="align-items:flex-start;gap:var(--space-3);">
                        <span class="ds-flex ds-flex-center ds-font-bold" style="width:22px;height:22px;border-radius:50%;background:#000;color:#E8FF00;font-size:10px;flex-shrink:0;">1</span>
                        <div><p class="ds-text-xs ds-font-semibold">Cek emailmu</p><p class="ds-text-xs ds-text-tertiary" style="line-height:1.4;">QR Code dikirim ke {{ $order->user->email }}.</p></div>
                    </div>
                    <div class="ds-flex" style="align-items:flex-start;gap:var(--space-3);">
                        <span class="ds-flex ds-flex-center ds-font-bold" style="width:22px;height:22px;border-radius:50%;background:#000;color:#E8FF00;font-size:10px;flex-shrink:0;">2</span>
                        <div><p class="ds-text-xs ds-font-semibold">Datang sebelum jam {{ $order->event->start_time->copy()->subHours($doorsOpenHours)->format('H.i') }}</p><p class="ds-text-xs ds-text-tertiary" style="line-height:1.4;">Pintu {{ $order->event->venue->name }} dibuka {{ $order->event->start_time->copy()->subHours($doorsOpenHours)->format('H.i') }} {{ $tz }}.</p></div>
                    </div>
                    <div class="ds-flex" style="align-items:flex-start;gap:var(--space-3);">
                        <span class="ds-flex ds-flex-center ds-font-bold" style="width:22px;height:22px;border-radius:50%;background:#000;color:#E8FF00;font-size:10px;flex-shrink:0;">3</span>
                        <div><p class="ds-text-xs ds-font-semibold">Tunjukkan QR Code</p><p class="ds-text-xs ds-text-tertiary" style="line-height:1.4;">Petugas akan scan QR dari email. Tiket hanya berlaku sekali.</p></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else

    {{-- ================================================================
         MODE: PAYMENT FORM
         ================================================================ --}}
    {{-- TIMER --}}
    @php $secsLeft = max(0, now()->diffInSeconds($order->payment_deadline, false)); @endphp
    <div wire:poll.1s class="ds-flex ds-items-center ds-justify-between ds-mb-5" style="background:#000;border-radius:var(--radius-xl);padding:var(--space-4) var(--space-5);">
        <div class="ds-flex ds-items-center ds-gap-3">
            <span style="width:8px;height:8px;border-radius:50%;background:#E8FF00;animation:pulse 1s infinite;"></span>
            <div>
                <p class="ds-uppercase ds-font-bold" style="font-size:10px;color:rgba(255,255,255,0.4);letter-spacing:0.12em;margin-bottom:1px;">Selesaikan Sebelum</p>
                <p style="font-family:'ClashDisplay-Semibold','Fraunces',Georgia,serif;font-size:var(--text-subheading-2);font-weight:650;color:{{ $secsLeft < 120 ? '#FF6B6B' : '#E8FF00' }};font-variant-numeric:tabular-nums;">{{ sprintf('%02d:%02d', floor($secsLeft/60), $secsLeft%60) }}</p>
            </div>
        </div>
        <p class="ds-uppercase ds-text-xs" style="color:rgba(255,255,255,0.3);letter-spacing:0.06em;max-width:140px;text-align:right;line-height:1.4;">Pesanan otomatis dibatalkan &amp; kuota dikembalikan jika timer habis</p>
    </div>

    <div class="ds-grid ds-grid-2" style="gap:var(--space-5);margin-bottom:var(--space-8);align-items:start;">
        {{-- LEFT: Metode Pembayaran --}}
        <div>
            <p class="ds-label ds-mb-3" style="color:var(--color-text-secondary);">Metode Pembayaran</p>
            <p class="ds-text-xs ds-text-tertiary ds-mb-4">Pilih metode yang ingin kamu gunakan</p>

            {{-- Transfer Bank --}}
            <label class="ds-block ds-mb-3" style="background:#fff;border:1.5px solid {{ $method === 'transfer' ? '#000' : 'var(--color-border)' }};border-radius:var(--radius-lg);padding:var(--space-4);cursor:pointer;transition:all 0.15s;">
                <input type="radio" wire:model="method" value="transfer" class="ds-hidden">
                <div class="ds-flex ds-gap-3" style="align-items:flex-start;">
                    <div class="ds-flex ds-flex-center" style="width:28px;height:28px;border-radius:6px;background:#2563EB;flex-shrink:0;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
                    </div>
                    <div style="flex:1;">
                        <p class="ds-text-small ds-font-bold ds-text-primary">Transfer Bank</p>
                        <p class="ds-text-xs ds-text-tertiary">Virtual Account — BCA · BNI · Mandiri · BRI</p>
                    </div>
                    <div class="ds-flex ds-flex-center" style="width:18px;height:18px;border-radius:50%;border:2px solid {{ $method === 'transfer' ? '#000' : 'var(--color-border)' }};flex-shrink:0;margin-top:5px;">
                        @if($method === 'transfer')<span style="display:block;width:10px;height:10px;border-radius:50%;background:#000;"></span>@endif
                    </div>
                </div>
            </label>

            {{-- E-Wallet --}}
            <label class="ds-block ds-mb-3" style="background:#fff;border:1.5px solid {{ $method === 'e-wallet' ? '#000' : 'var(--color-border)' }};border-radius:var(--radius-lg);padding:var(--space-4);cursor:pointer;transition:all 0.15s;">
                <input type="radio" wire:model="method" value="e-wallet" class="ds-hidden">
                <div class="ds-flex ds-gap-3" style="align-items:flex-start;">
                    <div class="ds-flex ds-flex-center" style="width:28px;height:28px;border-radius:6px;background:#7C3AED;flex-shrink:0;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 12V8H6a2 2 0 0 1-2-2c0-1.1.9-2 2-2h12v4"/><path d="M4 6v12c0 1.1.9 2 2 2h14v-4"/><path d="M18 12a2 2 0 0 0 0 4h4v-4Z"/></svg>
                    </div>
                    <div style="flex:1;">
                        <p class="ds-text-small ds-font-bold ds-text-primary">E-Wallet</p>
                        <p class="ds-text-xs ds-text-tertiary">GoPay · OVO · DANA · ShopeePay</p>
                    </div>
                    <div class="ds-flex ds-flex-center" style="width:18px;height:18px;border-radius:50%;border:2px solid {{ $method === 'e-wallet' ? '#000' : 'var(--color-border)' }};flex-shrink:0;margin-top:5px;">
                        @if($method === 'e-wallet')<span style="display:block;width:10px;height:10px;border-radius:50%;background:#000;"></span>@endif
                    </div>
                </div>
            </label>

            {{-- QRIS --}}
            <label class="ds-block ds-mb-3" style="background:#fff;border:1.5px solid {{ $method === 'qris' ? '#000' : 'var(--color-border)' }};border-radius:var(--radius-lg);padding:var(--space-4);cursor:pointer;transition:all 0.15s;">
                <input type="radio" wire:model="method" value="qris" class="ds-hidden">
                <div class="ds-flex ds-gap-3" style="align-items:flex-start;">
                    <div class="ds-flex ds-flex-center" style="width:28px;height:28px;border-radius:6px;background:#000;flex-shrink:0;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#E8FF00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                    </div>
                    <div style="flex:1;">
                        <p class="ds-text-small ds-font-bold ds-text-primary">QRIS</p>
                        <p class="ds-text-xs ds-text-tertiary">Bayar dengan scan QR dari semua aplikasi</p>
                    </div>
                    <div class="ds-flex ds-flex-center" style="width:18px;height:18px;border-radius:50%;border:2px solid {{ $method === 'qris' ? '#000' : 'var(--color-border)' }};flex-shrink:0;margin-top:5px;">
                        @if($method === 'qris')<span style="display:block;width:10px;height:10px;border-radius:50%;background:#000;"></span>@endif
                    </div>
                </div>
            </label>

            @if(app(\App\Services\PaymentService::class)->isSimulated())
            <div class="ds-mt-4" style="background:rgba(232,255,0,0.08);border:1px solid rgba(232,255,0,0.4);border-radius:var(--radius-lg);padding:var(--space-4);">
                <div class="ds-flex" style="align-items:flex-start;gap:var(--space-2);">
                    <img src="/icons/warning.svg" alt="" width="18" height="18" style="flex-shrink:0;">
                    <div><p class="ds-uppercase ds-font-bold" style="font-size:11px;color:var(--color-brand-dark);letter-spacing:0.06em;margin-bottom:2px;">Mode Simulasi Pembayaran</p><p class="ds-text-xs ds-text-tertiary" style="line-height:1.5;">Platform ini menggunakan simulasi pembayaran — tidak terhubung ke payment gateway produksi. Klik "Bayar Sekarang" untuk mensimulasikan pembayaran berhasil.</p></div>
                </div>
            </div>
            @endif

            <button wire:click="initiatePayment" wire:loading.attr="disabled" wire:target="initiatePayment" class="ds-btn ds-btn--primary ds-btn--block ds-mt-5" style="padding:var(--space-4);font-size:var(--text-small);">
                <span wire:loading.remove wire:target="initiatePayment">Bayar Sekarang</span>
                <span wire:loading wire:target="initiatePayment">Memproses...</span>
            </button>

            <a href="{{ route('events.show', $order->event) }}" class="ds-block ds-text-center ds-no-underline ds-mt-3" style="font-size:10px;color:#E24B4A;padding:var(--space-2);transition:opacity 0.15s;" onmouseover="this.style.opacity='0.7'" onmouseout="this.style.opacity='1'">← Batalkan pesanan</a>

            <div class="ds-flex ds-items-center ds-justify-center ds-gap-2 ds-mt-4 ds-border-t" style="padding-top:var(--space-3);">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="var(--color-text-tertiary)" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                <span class="ds-uppercase ds-text-tertiary" style="font-size:9px;letter-spacing:0.06em;">Pembayaran aman &amp; terenkripsi {{ config('app.name') }}</span>
            </div>
        </div>

        {{-- RIGHT: Ringkasan Pesanan --}}
        <div style="position:sticky;top:var(--space-8);">
            <p class="ds-label ds-mb-4" style="color:var(--color-text-secondary);">Ringkasan Pesanan</p>
            <div class="ds-card-summary ds-card-summary--elevated">
                <div class="ds-flex ds-items-center ds-gap-2 ds-mb-3">
                    <span style="width:8px;height:8px;border-radius:50%;background:{{ $order->section->color_code ?? '#000' }};"></span>
                    <p class="ds-uppercase ds-font-bold ds-text-xs" style="letter-spacing:0.06em;">ZONA {{ strtoupper($order->section->name ?? '') }}</p>
                </div>
                @for($i = 1; $i <= $order->qty; $i++)
                <div class="ds-bg-primary ds-radius-md ds-p-3 ds-mb-2">
                    <p class="ds-caption ds-text-tertiary ds-mb-1" style="font-size:10px;">Tiket {{ $order->section->name ?? '' }} #{{ $i }}</p>
                    <p class="ds-text-small ds-font-semibold">{{ $order->event->name }}</p>
                    <p class="ds-text-xs ds-text-tertiary">{{ $order->event->start_time->format('d M Y') }}</p>
                    <p class="ds-text-small ds-font-bold ds-mt-1">Rp {{ number_format($order->subtotal / $order->qty, 0, ',', '.') }}</p>
                </div>
                @endfor
                <hr class="ds-divider">
                <div class="ds-card-summary__row"><span class="ds-card-summary__label">Subtotal ({{ $order->qty }} tiket)</span><span class="ds-card-summary__value">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span></div>
                <div class="ds-card-summary__row"><span class="ds-card-summary__label">Biaya layanan</span><span class="ds-card-summary__value">Rp {{ number_format($order->service_fee, 0, ',', '.') }}</span></div>
                <hr class="ds-divider">
                <div class="ds-card-summary__total-row"><span class="ds-card-summary__total-label">Total Bayar</span><span class="ds-card-summary__total-value">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span></div>
            </div>
            <a href="{{ route('orders.checkout', $order) }}" class="ds-block ds-text-center ds-text-tertiary ds-no-underline ds-mt-3" style="font-size:10px;padding:var(--space-2);transition:color 0.15s;">← Kembali ke data diri</a>
        </div>
    </div>
    @endif

    {{-- ================================================================
         SIMULATED PAYMENT PANEL
         ================================================================ --}}
    @if($showSimulatedPanel && !empty($simulatedPayment))
    <div class="ds-overlay" style="z-index:200;">
        <div class="ds-overlay__panel" style="max-width:420px;position:relative;">
            <div class="ds-text-center ds-mb-4">
                <div style="width:48px;height:48px;border-radius:12px;background:linear-gradient(135deg, #E8FF00, #FFB800);display:flex;align-items:center;justify-content:center;margin:0 auto var(--space-3);">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
                </div>
                <p class="ds-uppercase ds-font-bold" style="font-size:10px;color:var(--color-warning);letter-spacing:0.12em;margin-bottom:2px;">Mode Simulasi</p>
                <h3 style="font-family:'ClashDisplay-Semibold','Fraunces',serif;font-size:1.25rem;font-weight:650;margin-bottom:4px;">Instruksi Pembayaran</h3>
                <p class="ds-text-xs ds-text-tertiary">Salin detail pembayaran di bawah, lalu konfirmasi</p>
            </div>

            <div style="background:#fff;border:1.5px solid var(--color-border);border-radius:var(--radius-lg);padding:var(--space-5);margin-bottom:var(--space-4);">
                @if($simulatedPayment['is_qris'])
                    <div class="ds-text-center ds-mb-4">
                        <div style="width:160px;height:160px;margin:0 auto;background:#fff;border:2px solid #000;border-radius:12px;display:flex;align-items:center;justify-content:center;overflow:hidden;">
                            <svg width="140" height="140" viewBox="0 0 140 140">
                                @for($y = 0; $y < 7; $y++)
                                    @for($x = 0; $x < 7; $x++)
                                        @if(($x + $y) % 3 !== 0)<rect x="{{ 15 + $x * 16 }}" y="{{ 15 + $y * 16 }}" width="14" height="14" fill="#000" rx="2" />@endif
                                    @endfor
                                @endfor
                                <rect x="6" y="6" width="28" height="28" fill="#000" rx="3" /><rect x="10" y="10" width="20" height="20" fill="#fff" rx="2" /><rect x="14" y="14" width="12" height="12" fill="#000" rx="1" />
                                <rect x="106" y="6" width="28" height="28" fill="#000" rx="3" /><rect x="110" y="10" width="20" height="20" fill="#fff" rx="2" /><rect x="114" y="14" width="12" height="12" fill="#000" rx="1" />
                                <rect x="6" y="106" width="28" height="28" fill="#000" rx="3" /><rect x="10" y="110" width="20" height="20" fill="#fff" rx="2" /><rect x="14" y="114" width="12" height="12" fill="#000" rx="1" />
                            </svg>
                        </div>
                    </div>
                    <div class="ds-flex ds-items-center ds-justify-between ds-mb-1" style="font-size:11px;"><span class="ds-text-tertiary">Kode QRIS</span><span class="ds-font-bold ds-font-mono" style="letter-spacing:0.05em;">{{ $simulatedPayment['payment_code'] }}</span></div>
                @elseif($simulatedPayment['is_ewallet'])
                    <div class="ds-text-center ds-mb-4">
                        <p class="ds-text-xs ds-font-bold ds-uppercase" style="letter-spacing:0.06em;margin-bottom:8px;">{{ $simulatedPayment['bank'] }}</p>
                        <div style="width:120px;height:120px;margin:0 auto;background:linear-gradient(135deg, #f0f0f0, #e0e0e0);border-radius:50%;display:flex;align-items:center;justify-content:center;">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#999" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 12V8H6a2 2 0 0 1-2-2c0-1.1.9-2 2-2h12v4"/><path d="M4 6v12c0 1.1.9 2 2 2h14v-4"/><path d="M18 12a2 2 0 0 0 0 4h4v-4Z"/></svg>
                        </div>
                    </div>
                    <div class="ds-flex ds-items-center ds-justify-between ds-mb-1" style="font-size:11px;"><span class="ds-text-tertiary">Kode Bayar</span><span class="ds-font-bold ds-font-mono" style="letter-spacing:0.05em;">{{ $simulatedPayment['payment_code'] }}</span></div>
                @else
                    <div class="ds-flex ds-items-center ds-gap-3 ds-mb-4"><img src="/icons/bank.svg" alt="" width="28" height="28" style="flex-shrink:0;"><div><p class="ds-font-bold ds-text-small">{{ $simulatedPayment['bank'] }}</p><p class="ds-text-xs ds-text-tertiary">Virtual Account</p></div></div>
                    <div class="ds-mb-4" style="background:var(--color-bg-primary);border-radius:var(--radius-md);padding:var(--space-3);text-align:center;">
                        <p class="ds-text-xs ds-text-tertiary ds-mb-1" style="font-size:9px;letter-spacing:0.08em;text-transform:uppercase;">Nomor Virtual Account</p>
                        <p class="ds-font-mono ds-font-bold" style="font-size:1.25rem;letter-spacing:0.06em;word-break:break-all;">{{ chunk_split($simulatedPayment['va_number'], 4, ' ') }}</p>
                    </div>
                @endif

                <hr style="border-color:var(--color-border);margin:var(--space-3) 0;">
                <div class="ds-flex ds-items-center ds-justify-between ds-mb-1" style="font-size:11px;"><span class="ds-text-tertiary">Total Bayar</span><span class="ds-font-bold">Rp {{ number_format($simulatedPayment['amount'], 0, ',', '.') }}</span></div>
                <div class="ds-flex ds-items-center ds-justify-between ds-mb-1" style="font-size:11px;"><span class="ds-text-tertiary">Referensi</span><span class="ds-font-mono" style="font-size:10px;color:#666;">{{ $simulatedPayment['reference'] }}</span></div>
            </div>

            <div style="display:flex;gap:var(--space-3);">
                <button wire:click="cancelSimulatedPayment" wire:target="cancelSimulatedPayment" style="flex:1;padding:var(--space-3);border:1.5px solid #000;border-radius:var(--radius-pill);background:transparent;color:#000;font-size:var(--text-xs);font-weight:600;text-transform:uppercase;letter-spacing:0.06em;cursor:pointer;transition:all 0.15s;" onmouseover="this.style.background='#000';this.style.color='#fff';" onmouseout="this.style.background='transparent';this.style.color='#000';">Batal</button>
                <button wire:click="confirmSimulatedPayment" wire:loading.attr="disabled" wire:target="confirmSimulatedPayment" style="flex:2;padding:var(--space-3);border:none;border-radius:var(--radius-pill);background:#E8FF00;color:#000;font-size:var(--text-xs);font-weight:700;text-transform:uppercase;letter-spacing:0.08em;cursor:pointer;transition:all 0.15s;" onmouseover="this.style.background='var(--color-cream)';" onmouseout="this.style.background='#E8FF00';">
                    <span wire:loading.remove wire:target="confirmSimulatedPayment">Konfirmasi Pembayaran</span>
                    <span wire:loading wire:target="confirmSimulatedPayment">Memproses...</span>
                </button>
            </div>

            <div wire:loading wire:target="confirmSimulatedPayment" class="ds-flex ds-flex-center" style="position:absolute;inset:0;background:rgba(255,255,255,0.9);border-radius:var(--radius-xl);flex-direction:column;gap:var(--space-3);z-index:5;">
                <div style="width:36px;height:36px;border:3px solid var(--color-border);border-top-color:#000;border-radius:50%;animation:spin 0.6s linear infinite;"></div>
                <span style="font-size:10px;color:var(--color-text-secondary);font-weight:600;">Memproses pembayaran...</span>
            </div>

            <p class="ds-text-center ds-mt-3" style="font-size:9px;color:var(--color-text-tertiary);">Ini adalah simulasi — tidak ada transaksi sungguhan yang terjadi</p>
        </div>
    </div>
    @endif

    @push('scripts')
    <script src="{{ config('midtrans.snap_url') }}" data-client-key="{{ config('midtrans.client_key') }}"></script>
    @endpush
    <style>@keyframes pulse { 0%,100% { opacity:1; } 50% { opacity:0.3; } } @keyframes spin { to { transform:rotate(360deg); } }</style>
</div>

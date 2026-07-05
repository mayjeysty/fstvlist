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
        <span class="ds-stepper__connector ds-stepper__connector--completed"></span>
        <span class="ds-stepper__step ds-stepper__step--active">
            <span class="ds-stepper__circle">3</span>
            <span class="ds-stepper__label">Pembayaran</span>
        </span>
        <span class="ds-stepper__connector"></span>
        <span class="ds-stepper__step ds-stepper__step--inactive">
            <span class="ds-stepper__circle">4</span>
            <span class="ds-stepper__label">Selesai</span>
        </span>
    </div>

    @if($errors->any())
        <div class="ds-bg-error ds-text-error ds-text-xs ds-radius-md ds-p-3 ds-mb-4" style="border:1px solid rgba(226,75,74,0.25);">{{ $errors->first() }}</div>
    @endif

    {{-- TIMER --}}
    @php $secsLeft = max(0, now()->diffInSeconds($order->payment_deadline, false)); @endphp
    <div wire:poll.1s class="ds-flex ds-items-center ds-justify-between ds-mb-5" style="background:#000;border-radius:var(--radius-xl);padding:var(--space-4) var(--space-5);">
        <div class="ds-flex ds-items-center ds-gap-3">
            <span style="width:8px;height:8px;border-radius:50%;background:#E8FF00;animation:pulse 1s infinite;"></span>
            <div>
                <p class="ds-uppercase ds-font-bold" style="font-size:10px;color:rgba(255,255,255,0.4);letter-spacing:0.12em;margin-bottom:1px;">Selesaikan Sebelum</p>
                <p style="font-family:'Fraunces',Georgia,serif;font-size:var(--text-subheading-2);font-weight:700;color:{{ $secsLeft < 120 ? '#FF6B6B' : '#E8FF00' }};font-variant-numeric:tabular-nums;">{{ sprintf('%02d:%02d', floor($secsLeft/60), $secsLeft%60) }}</p>
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
                <div class="ds-flex ds-items-center ds-gap-3">
                    <span style="font-size:1.25rem;">🏦</span>
                    <div>
                        <p class="ds-text-small ds-font-bold ds-text-primary">Transfer Bank</p>
                        <p class="ds-text-xs ds-text-tertiary">Virtual Account — BCA · BNI · Mandiri · BRI</p>
                    </div>
                    <span class="ds-ml-auto ds-flex ds-flex-center" style="width:18px;height:18px;border-radius:50%;border:2px solid {{ $method === 'transfer' ? '#000' : 'var(--color-border)' }};">
                        @if($method === 'transfer')
                        <span style="width:10px;height:10px;border-radius:50%;background:#000;"></span>
                        @endif
                    </span>
                </div>
            </label>

            {{-- E-Wallet --}}
            <label class="ds-block ds-mb-3" style="background:#fff;border:1.5px solid {{ $method === 'e-wallet' ? '#000' : 'var(--color-border)' }};border-radius:var(--radius-lg);padding:var(--space-4);cursor:pointer;transition:all 0.15s;">
                <input type="radio" wire:model="method" value="e-wallet" class="ds-hidden">
                <div class="ds-flex ds-items-center ds-gap-3">
                    <span style="font-size:1.25rem;">👛</span>
                    <div>
                        <p class="ds-text-small ds-font-bold ds-text-primary">E-Wallet</p>
                        <p class="ds-text-xs ds-text-tertiary">GoPay · OVO · DANA · ShopeePay</p>
                    </div>
                    <span class="ds-ml-auto ds-flex ds-flex-center" style="width:18px;height:18px;border-radius:50%;border:2px solid {{ $method === 'e-wallet' ? '#000' : 'var(--color-border)' }};">
                        @if($method === 'e-wallet')
                        <span style="width:10px;height:10px;border-radius:50%;background:#000;"></span>
                        @endif
                    </span>
                </div>
            </label>

            {{-- QRIS --}}
            <label class="ds-block ds-mb-3" style="background:#fff;border:1.5px solid {{ $method === 'qris' ? '#000' : 'var(--color-border)' }};border-radius:var(--radius-lg);padding:var(--space-4);cursor:pointer;transition:all 0.15s;">
                <input type="radio" wire:model="method" value="qris" class="ds-hidden">
                <div class="ds-flex ds-items-center ds-gap-3">
                    <span style="font-size:1.25rem;">⬛</span>
                    <div>
                        <p class="ds-text-small ds-font-bold ds-text-primary">QRIS</p>
                        <p class="ds-text-xs ds-text-tertiary">Bayar dengan scan QR dari semua aplikasi</p>
                    </div>
                    <span class="ds-ml-auto ds-flex ds-flex-center" style="width:18px;height:18px;border-radius:50%;border:2px solid {{ $method === 'qris' ? '#000' : 'var(--color-border)' }};">
                        @if($method === 'qris')
                        <span style="width:10px;height:10px;border-radius:50%;background:#000;"></span>
                        @endif
                    </span>
                </div>
            </label>

            {{-- Simulasi Mode --}}
            @if(app(\App\Services\PaymentService::class)->isSimulated())
            <div class="ds-mt-4" style="background:rgba(232,255,0,0.08);border:1px solid rgba(232,255,0,0.4);border-radius:var(--radius-lg);padding:var(--space-4);">
                <div class="ds-flex" style="align-items:flex-start;gap:var(--space-2);">
                    <span style="font-size:1rem;">⚠️</span>
                    <div>
                        <p class="ds-uppercase ds-font-bold" style="font-size:11px;color:var(--color-brand-dark);letter-spacing:0.06em;margin-bottom:2px;">Mode Simulasi Pembayaran</p>
                        <p class="ds-text-xs ds-text-tertiary" style="line-height:1.5;">Platform ini menggunakan simulasi pembayaran — tidak terhubung ke payment gateway produksi. Klik "Bayar Sekarang" untuk mensimulasikan pembayaran berhasil.</p>
                    </div>
                </div>
            </div>
            @endif

            {{-- Bayar Sekarang --}}
            <button wire:click="initiatePayment" wire:loading.attr="disabled" wire:target="initiatePayment"
                    class="ds-btn ds-btn--primary ds-btn--block ds-mt-5" style="padding:var(--space-4);font-size:var(--text-small);">
                <span wire:loading.remove wire:target="initiatePayment">Bayar Sekarang</span>
                <span wire:loading wire:target="initiatePayment">Memproses...</span>
            </button>

            {{-- Cancel --}}
            <a href="{{ route('events.show', $order->event) }}" class="ds-block ds-text-center ds-no-underline ds-mt-3" style="font-size:10px;color:#E24B4A;padding:var(--space-2);transition:opacity 0.15s;" onmouseover="this.style.opacity='0.7'" onmouseout="this.style.opacity='1'">← Batalkan pesanan</a>

            {{-- Secure footer --}}
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
                <div class="ds-card-summary__row">
                    <span class="ds-card-summary__label">Subtotal ({{ $order->qty }} tiket)</span>
                    <span class="ds-card-summary__value">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="ds-card-summary__row">
                    <span class="ds-card-summary__label">Biaya layanan</span>
                    <span class="ds-card-summary__value">Rp {{ number_format($order->service_fee, 0, ',', '.') }}</span>
                </div>
                <hr class="ds-divider">
                <div class="ds-card-summary__total-row">
                    <span class="ds-card-summary__total-label">Total Bayar</span>
                    <span class="ds-card-summary__total-value">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                </div>
            </div>

            <a href="{{ route('orders.checkout', $order) }}" class="ds-block ds-text-center ds-text-tertiary ds-no-underline ds-mt-3" style="font-size:10px;padding:var(--space-2);transition:color 0.15s;" onmouseover="this.style.color='var(--color-text-primary)'" onmouseout="this.style.color='var(--color-text-tertiary)'">← Kembali ke data diri</a>
        </div>
    </div>

    {{-- SUCCESS POPUP --}}
    @if($showSuccessModal)
    <div class="ds-overlay" style="z-index:200;" wire:ignore>
        <div class="ds-overlay__panel ds-text-center" style="max-width:380px;">
            <div style="width:64px;height:64px;border-radius:50%;background:#E8FF00;display:flex;align-items:center;justify-content:center;margin:0 auto var(--space-5);">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
            </div>
            <h3 class="ds-success__title" style="font-family:'Fraunces',Georgia,serif;">Pembayaran Berhasil!</h3>
            <p class="ds-text-small ds-text-secondary ds-mb-5">E-tiket sedang dikirim ke email kamu</p>
            <div class="ds-flex ds-items-center ds-gap-2 ds-mb-4 ds-justify-center">
                <span class="ds-badge-dot ds-badge-dot--success" style="animation:pulse 1s infinite;"></span>
                <span class="ds-text-xs ds-text-tertiary">Mengirim pesanan ke email...</span>
            </div>
            <div class="ds-progress ds-mb-5">
                <div class="ds-progress__fill" id="successProgress" style="width:0%;background:#E8FF00;"></div>
            </div>
            <p class="ds-text-xs ds-text-tertiary">Mengalihkan ke halaman tiket...</p>
        </div>
    </div>
    <script>
        (function() {
            var bar = document.getElementById('successProgress');
            if (bar) {
                bar.style.transition = 'width 3s linear';
                setTimeout(function() { bar.style.width = '100%'; }, 50);
            }
        })();
        setTimeout(function() {
            if (typeof Livewire !== 'undefined') {
                Livewire.dispatch('redirect-after-payment');
            }
        }, 3200);
    </script>
    @endif

    @push('scripts')
    <script src="{{ config('midtrans.snap_url') }}" data-client-key="{{ config('midtrans.client_key') }}"></script>
    @endpush
    <style>@keyframes pulse { 0%,100% { opacity:1; } 50% { opacity:0.3; } }</style>
</div>
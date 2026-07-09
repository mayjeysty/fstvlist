<div>
    {{-- ─── STEPPER ─── --}}
    <div class="ds-stepper" style="margin-bottom:var(--space-5);">
        <a href="{{ route('events.show', $order->event) }}" class="ds-stepper__step ds-stepper__step--completed ds-stepper__step--clickable">
            <span class="ds-stepper__circle"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></span>
            <span class="ds-stepper__label">Pilih Zona</span>
        </a>
        <span class="ds-stepper__connector ds-stepper__connector--completed"></span>
        <a href="{{ route('orders.create', ['event' => $order->event, 'section' => $order->section_id]) }}" class="ds-stepper__step ds-stepper__step--completed ds-stepper__step--clickable">
            <span class="ds-stepper__circle"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></span>
            <span class="ds-stepper__label">Jumlah Tiket</span>
        </a>
        <span class="ds-stepper__connector ds-stepper__connector--completed"></span>
        <span class="ds-stepper__step ds-stepper__step--active">
            <span class="ds-stepper__circle">3</span>
            <span class="ds-stepper__label">Data Diri</span>
        </span>
        <span class="ds-stepper__connector"></span>
        <span class="ds-stepper__step ds-stepper__step--inactive">
            <span class="ds-stepper__circle">4</span>
            <span class="ds-stepper__label">Pembayaran</span>
        </span>
    </div>

    {{-- WHITE CARD --}}
    <div class="ds-card">

        <p style="font-size:var(--text-subheading-2);font-weight:var(--font-weight-bold);margin-bottom:var(--space-1);">Data Pemesanan</p>
        <p style="font-size:var(--text-small);color:var(--color-text-secondary);margin-bottom:var(--space-5);">Lengkapi data diri Anda dengan benar.</p>

        <div class="ds-form">
            <div class="ds-form__group">
                <label class="ds-form__label">Gelar</label>
                <div class="ds-form__radio-group">
                    @foreach(['Tuan' => 'Tuan', 'Nyonya' => 'Nyonya', 'Nona' => 'Nona'] as $val => $label)
                        <label class="ds-form__radio">
                            <input type="radio" wire:model="title" value="{{ $val }}">
                            <span class="ds-form__radio-label">{{ $label }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
            <div class="ds-form__group">
                <label class="ds-form__label ds-form__label--required">Nama Lengkap</label>
                <input type="text" wire:model="name" class="ds-form__input @error('name') ds-form__input--error @enderror" placeholder="Nama sesuai KTP">
                @error('name') <p class="ds-form__error">{{ $message }}</p> @enderror
            </div>
            <div class="ds-form__group">
                <label class="ds-form__label ds-form__label--required">Alamat Email</label>
                <input type="email" wire:model="email" class="ds-form__input @error('email') ds-form__input--error @enderror" placeholder="kamu@email.com">
                @error('email') <p class="ds-form__error">{{ $message }}</p> @enderror
            </div>
            <div class="ds-form__group">
                <label class="ds-form__label ds-form__label--required">Nomor WhatsApp</label>
                <div class="ds-input-group">
                    <span class="ds-input-group__prefix">+62</span>
                    <input type="tel" wire:model="whatsapp" class="ds-input-group__input @error('whatsapp') ds-form__input--error @enderror" placeholder="812-3456-7890">
                </div>
                @error('whatsapp') <p class="ds-form__error">{{ $message }}</p> @enderror
            </div>
            <div class="ds-form__group">
                <label class="ds-form__label ds-form__label--required">NIK</label>
                <input type="text" wire:model="nik" class="ds-form__input @error('nik') ds-form__input--error @enderror" placeholder="16 digit NIK" maxlength="16">
                @error('nik') <p class="ds-form__error">{{ $message }}</p> @enderror
            </div>
        </div>

        <hr class="ds-divider" style="margin:var(--space-5) 0;">

        <div class="ds-card-summary" style="background:var(--color-bg-primary);margin-bottom:var(--space-4);">
            <div class="ds-card-summary__row"><span class="ds-card-summary__label">Event</span><span class="ds-card-summary__value">{{ $order->event->name }}</span></div>
            <div class="ds-card-summary__row"><span class="ds-card-summary__label">Lokasi & Tanggal</span><span class="ds-card-summary__value">{{ $order->event->venue->name }} · {{ $order->event->start_time->format('d M Y, H:i') }}</span></div>
            <div class="ds-card-summary__row"><span class="ds-card-summary__label">Zona & Jumlah</span><span class="ds-card-summary__value"><span style="display:inline-block;width:8px;height:8px;border-radius:50%;margin-right:4px;background:{{ $order->section->color_code ?? '#000' }}"></span>{{ $order->section->name ?? '—' }} · {{ $order->qty }} tiket</span></div>
            <hr class="ds-card-summary__divider">
            <div class="ds-card-summary__total-row">
                <span class="ds-card-summary__total-label">Total</span>
                <span class="ds-card-summary__total-value">IDR {{ number_format($order->total_price, 0, ',', '.') }}</span>
            </div>
        </div>

        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:var(--space-3);">
            <span style="font-size:var(--text-small);color:var(--color-text-secondary);">Tidak bisa refund</span>
            <label class="ds-form__checkbox">
                <input type="checkbox" checked>
                <span class="ds-form__checkbox-box"></span>
                <span class="ds-form__checkbox-text">Konfirmasi Instan</span>
            </label>
        </div>

        <div class="ds-timer" wire:poll.1s style="margin-bottom:0;">
            <p class="ds-timer__label">Selesaikan Dalam</p>
            <p class="ds-timer__time">{{ now()->diff($order->booking_deadline)->format('%I:%S') }}</p>
        </div>

    </div>{{-- END CARD --}}

    <div style="display:flex;gap:var(--space-3);margin-bottom:var(--space-8);">
        <button wire:click="attemptProceed" class="ds-btn ds-btn--accent ds-btn--lg" style="flex:1;">Lanjut ke Pembayaran</button>
        <button @click="$wire.set('showCancelModal', true)" class="ds-btn ds-btn--ghost">Batalkan</button>
    </div>

    {{-- ════════ MODAL 1 ════════ --}}
    <x-modal wire:model="showConfirmModal">
        <h2 class="font-display text-2xl font-semibold uppercase tracking-[-0.01em] text-ink mb-2">Yakin dengan data ini?</h2>
        <p class="font-body text-sm text-mid-gray leading-relaxed mb-4">Pastikan nama, email, dan nomor tiket sudah benar sebelum melanjutkan.</p>
        <div class="border border-border-light rounded-xl p-4 mb-4 space-y-2">
            <div><p class="font-body text-[11px] font-semibold text-muted uppercase tracking-[0.06em]">Gelar</p><p class="font-body text-sm font-semibold text-ink">{{ $title }}</p></div>
            <div><p class="font-body text-[11px] font-semibold text-muted uppercase tracking-[0.06em]">Nama</p><p class="font-body text-sm font-semibold text-ink">{{ $name }}</p></div>
            <div><p class="font-body text-[11px] font-semibold text-muted uppercase tracking-[0.06em]">Email</p><p class="font-body text-[13px] text-ink">{{ $email }}</p></div>
            <div><p class="font-body text-[11px] font-semibold text-muted uppercase tracking-[0.06em]">WhatsApp</p><p class="font-body text-[13px] text-ink">+62{{ $whatsapp }}</p></div>
            <div><p class="font-body text-[11px] font-semibold text-muted uppercase tracking-[0.06em]">NIK</p><p class="font-body text-[13px] text-ink">{{ $nik }}</p></div>
            <div><p class="font-body text-[11px] font-semibold text-muted uppercase tracking-[0.06em]">Tiket</p><p class="inline-block font-display font-semibold text-base text-ink bg-accent px-2.5 py-1 rounded-md mt-0.5">{{ $order->qty }} tiket · {{ $order->section->name ?? '—' }}</p></div>
        </div>
        <hr class="border-border-light mb-5">
        <div class="flex gap-3">
            <button wire:click="proceedToPayment" @click="$wire.set('showConfirmModal', false)" class="flex-1 bg-ink text-white font-display font-semibold uppercase text-base rounded-pill py-3">Ya, Lanjutkan</button>
            <button @click="$wire.set('showConfirmModal', false)" class="font-body text-sm text-muted hover:text-ink transition-colors px-3">Kembali</button>
        </div>
    </x-modal>

    {{-- ════════ MODAL 2 ════════ --}}
    <x-modal wire:model="showCancelModal">
        <h2 class="font-display text-2xl font-semibold uppercase tracking-[-0.01em] text-ink mb-3">Batalkan pemesanan?</h2>
        <div class="flex items-start gap-2 mb-4">
            <svg class="w-5 h-5 text-[#F55C5C] shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4.5c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
            <p class="font-body text-sm text-[#F55C5C] leading-relaxed">Apakah Anda yakin ingin membatalkan pemesanan ini?</p>
        </div>
        <div class="bg-cream border border-border-light rounded-lg p-3 mb-4">
            <p class="font-body text-[13px] text-mid-gray">{{ $order->event->name }}</p>
            <p class="font-display font-semibold text-base text-ink mt-0.5">{{ $order->qty }} tiket · {{ $order->section->name ?? '—' }}</p>
        </div>
        <hr class="border-border-light mb-5">
        <div class="flex gap-3">
            <button wire:click="cancelOrder" class="flex-1 bg-[#F55C5C] text-white font-display font-semibold uppercase text-base rounded-pill py-3">Ya, Batalkan</button>
            <button @click="$wire.set('showCancelModal', false)" class="font-body text-sm text-muted hover:text-ink transition-colors px-3">Kembali</button>
        </div>
    </x-modal>
</div>

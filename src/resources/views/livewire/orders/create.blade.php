<div>
    {{-- ─── STEPPER ─── --}}
    <div class="ds-stepper" style="margin-bottom:var(--space-5);">
        <a href="{{ route('events.show', $event) }}" class="ds-stepper__step ds-stepper__step--completed ds-stepper__step--clickable">
            <span class="ds-stepper__circle">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
            </span>
            <span class="ds-stepper__label">Pilih Zona</span>
        </a>
        <span class="ds-stepper__connector ds-stepper__connector--completed"></span>
        <span class="ds-stepper__step ds-stepper__step--active">
            <span class="ds-stepper__circle">2</span>
            <span class="ds-stepper__label">Jumlah Tiket</span>
        </span>
        <span class="ds-stepper__connector"></span>
        <span class="ds-stepper__step ds-stepper__step--inactive">
            <span class="ds-stepper__circle">3</span>
            <span class="ds-stepper__label">Data Diri</span>
        </span>
        <span class="ds-stepper__connector"></span>
        <span class="ds-stepper__step ds-stepper__step--inactive">
            <span class="ds-stepper__circle">4</span>
            <span class="ds-stepper__label">Pembayaran</span>
        </span>
    </div>

    @if($errors->any())
        <div style="background:var(--color-error-bg);border:1px solid rgba(226,75,74,0.25);color:var(--color-error);font-size:var(--text-xs);border-radius:var(--radius-md);padding:var(--space-3);margin-bottom:var(--space-4);">{{ $errors->first() }}</div>
    @endif

    {{-- ════════ WHITE CARD ════════ --}}
    <div style="background:#FFFFFF;border-radius:var(--radius-xl);box-shadow:0 2px 12px rgba(0,0,0,0.08);padding:var(--space-6);margin-bottom:var(--space-5);">

        <p style="font-size:var(--text-subheading-2);font-weight:var(--font-weight-bold);margin-bottom:var(--space-1);">Ringkasan Pilihan</p>
        <p style="font-size:var(--text-small);color:var(--color-text-secondary);margin-bottom:var(--space-5);">Pastikan zona dan jumlah tiket sudah sesuai</p>

        <form wire:submit="reserve">
            @if($selectedSection)
                {{-- Selected Zone Info (compact) --}}
                <div style="background:var(--color-bg-primary);border:1px solid var(--color-border);border-radius:var(--radius-lg);padding:var(--space-3) var(--space-4);margin-bottom:var(--space-4);display:flex;align-items:center;justify-content:space-between;gap:var(--space-3);">
                    <div style="display:flex;align-items:center;gap:var(--space-2);">
                        <span style="width:10px;height:10px;border-radius:50%;flex-shrink:0;background:{{ $selectedSection->color_code }}"></span>
                        <span style="font-size:var(--text-small);font-weight:var(--font-weight-semibold);text-transform:uppercase;">{{ $selectedSection->name }}</span>
                        <span style="font-size:var(--text-xs);color:var(--color-text-tertiary);">Sisa {{ number_format($sectionRemaining) }}</span>
                    </div>
                    <span style="font-size:var(--text-small);font-weight:var(--font-weight-bold);">IDR {{ number_format($sectionPrice, 0, ',', '.') }}</span>
                </div>
            @else
                {{-- Zone Selection List --}}
                <div style="display:flex;flex-direction:column;gap:var(--space-2);margin-bottom:var(--space-5);">
                    <label class="ds-form__label" style="margin-bottom:var(--space-1);">Pilih Zona</label>

                    <div style="border:1px solid var(--color-border);border-radius:var(--radius-lg);overflow:hidden;">
                        @foreach($sections as $idx => $section)
                            @php $sp = $sectionPrices[$section->id] ?? ['price' => $section->price, 'remaining' => $section->remaining_capacity, 'soldOut' => $section->isSoldOut()]; @endphp
                            <label style="display:flex;align-items:center;gap:var(--space-3);padding:var(--space-3) var(--space-4);cursor:{{ $sp['soldOut'] ? 'not-allowed' : 'pointer' }};transition:all var(--transition-fast);{{ $sp['soldOut'] ? 'opacity:0.4;' : '' }}{{ $idx < count($sections)-1 ? 'border-bottom:1px solid var(--color-border);' : '' }}" onmouseover="if(!{{ $sp['soldOut'] ? 'true' : 'false' }}){this.style.background='var(--color-bg-primary)'}" onmouseout="this.style.background='transparent'">
                                <input type="radio" name="sectionId" value="{{ $section->id }}" wire:model.live="sectionId" {{ $sp['soldOut'] ? 'disabled' : '' }} style="accent-color:var(--color-text-primary);width:16px;height:16px;flex-shrink:0;">
                                <div style="flex:1;min-width:0;">
                                    <div style="display:flex;align-items:center;justify-content:space-between;gap:var(--space-2);">
                                        <div style="display:flex;align-items:center;gap:var(--space-2);">
                                            <span style="width:8px;height:8px;border-radius:50%;flex-shrink:0;background:{{ $section->color_code }}"></span>
                                            <span style="font-size:var(--text-small);font-weight:var(--font-weight-semibold);">{{ $section->name }}</span>
                                        </div>
                                        <span style="font-size:var(--text-small);font-weight:var(--font-weight-semibold);color:#0A8C5A;white-space:nowrap;">IDR {{ number_format($sp['price'], 0, ',', '.') }}</span>
                                    </div>
                                    <p style="font-size:var(--text-xs);color:var(--color-text-tertiary);margin-top:2px;">Tiket {{ $section->name }} · {{ $event->venue->name }}</p>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Quantity Selector + Total --}}
            <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:var(--space-4);margin-bottom:var(--space-4);" x-data="">
                <div>
                    <p style="font-size:var(--text-xs);font-weight:var(--font-weight-semibold);color:var(--color-text-secondary);text-transform:uppercase;letter-spacing:0.06em;margin-bottom:var(--space-2);">Jumlah Tiket (maks. 4)</p>
                    <div style="display:flex;border:1px solid var(--color-border);border-radius:var(--radius-md);width:fit-content;overflow:hidden;">
                        <button type="button" @click="$wire.set('qty', $wire.qty > 1 ? $wire.qty - 1 : 1)" style="width:36px;height:36px;display:flex;align-items:center;justify-content:center;background:none;border:none;font-size:16px;color:var(--color-text-primary);cursor:pointer;">−</button>
                        <span style="width:44px;display:flex;align-items:center;justify-content:center;font-size:var(--text-small);font-weight:var(--font-weight-bold);border-left:1px solid var(--color-border);border-right:1px solid var(--color-border);background:var(--color-bg-hover);">{{ $qty }}</span>
                        <button type="button" @click="$wire.set('qty', $wire.qty < 4 ? $wire.qty + 1 : 4)" style="width:36px;height:36px;display:flex;align-items:center;justify-content:center;background:none;border:none;font-size:16px;color:var(--color-text-primary);cursor:pointer;">+</button>
                    </div>
                </div>
                @if($totalPrice > 0)
                    <div style="text-align:right;">
                        <div style="display:flex;justify-content:flex-end;gap:var(--space-6);font-size:var(--text-small);color:var(--color-text-secondary);margin-bottom:var(--space-1);">
                            <span>Subtotal</span>
                            <span style="font-weight:var(--font-weight-semibold);color:var(--color-text-primary);">IDR {{ number_format($sectionPrice * $qty, 0, ',', '.') }}</span>
                        </div>
                        <div style="display:flex;justify-content:flex-end;gap:var(--space-6);font-size:var(--text-small);color:var(--color-text-secondary);margin-bottom:var(--space-1);">
                            <span>Biaya Layanan</span>
                            <span style="font-weight:var(--font-weight-semibold);color:var(--color-text-primary);">IDR {{ number_format($totalPrice - ($sectionPrice * $qty), 0, ',', '.') }}</span>
                        </div>
                        <div style="display:flex;justify-content:flex-end;gap:var(--space-6);font-size:var(--text-body);margin-top:var(--space-2);">
                            <span style="font-weight:var(--font-weight-bold);">Total</span>
                            <span style="font-weight:var(--font-weight-bold);color:#0A8C5A;">IDR {{ number_format($totalPrice, 0, ',', '.') }}</span>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Timer --}}
            <div class="ds-timer" style="margin-bottom:0;">
                <p class="ds-timer__label">Waktu Reservasi</p>
                <p class="ds-timer__time">15:00</p>
                <p style="font-size:11px;color:var(--color-brand-dark);margin-top:var(--space-1);opacity:0.7;">Setelah klik Pesan, Anda punya 15 menit untuk menyelesaikan pembayaran.</p>
            </div>

            {{-- Submit inside card --}}
            <button type="submit" wire:loading.attr="disabled" class="ds-btn ds-btn--accent ds-btn--lg ds-btn--block" style="margin-top:var(--space-5);">
                <span wire:loading.remove>Lanjut Isi Data — IDR {{ number_format($totalPrice, 0, ',', '.') }}</span>
                <span wire:loading>Memproses...</span>
            </button>
        </form>

    </div>{{-- END WHITE CARD --}}

    <div style="text-align:center;padding-bottom:var(--space-8);">
        <p style="font-size:var(--text-xs);color:var(--color-text-tertiary);">Harga sudah termasuk pajak dan biaya layanan.</p>
    </div>
</div>

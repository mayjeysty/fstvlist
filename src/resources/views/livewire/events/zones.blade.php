<div class="min-h-screen bg-cream" x-data="{ highlight: false }">
    {{-- Top Header --}}
    <div class="bg-ink text-cream px-4 py-4 flex items-center justify-between">
        <a href="{{ route('events.show', $event) }}" class="w-8 h-8 bg-surface-2 flex items-center justify-center shrink-0"><svg width="16" height="16" class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px!important;height:16px!important;display:inline-block!important"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg></a>
        <div class="text-right ml-3">
            <h1 class="font-display text-lg font-semibold uppercase leading-none">{{ $event->name }}</h1>
            <p class="font-body text-[10px] text-cream/40 uppercase tracking-[0.12em] mt-0.5">Pilih Zona</p>
        </div>
    </div>

    {{-- Event Summary Banner --}}
    <div class="max-w-7xl mx-auto px-4 md:px-6 mt-4">
        <div class="flex flex-wrap items-end justify-between gap-3 p-4 bg-white rounded-xl border border-border-light/60">
            <div>
                <p class="font-display text-lg font-semibold uppercase text-ink">{{ $event->name }}</p>
                <div class="flex items-center gap-3 mt-1">
                    <span class="font-body text-xs text-mid-gray">{{ $event->start_time->format('d M Y · H:i') }}</span>
                    <span class="font-body text-xs text-mid-gray">·</span>
                    <span class="font-body text-xs text-mid-gray">{{ $event->venue->name }}, {{ $event->venue->city }}</span>
                </div>
            </div>
            @php $minPrice = collect($zoneData)->min('price'); @endphp
            <div class="text-right">
                <p class="font-body text-[10px] text-mid-gray uppercase tracking-[0.06em]">Mulai dari</p>
                <p class="font-display text-lg font-semibold text-ink">Rp{{ number_format($minPrice ?? 0, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    {{-- ================================================================
         VENUE MAP
         ================================================================ --}}
    <div id="pilih-zona-anda"
         class="max-w-7xl mx-4 md:mx-6 mt-6 p-4 transition-all duration-500 scroll-mt-24"
         :class="highlight ? 'ring-2 ring-accent shadow-lg shadow-accent/30 scale-[1.01]' : ''"
         x-ref="venueMap">
        <p class="font-body text-[11px] font-semibold text-mid-gray uppercase tracking-[0.06em] mb-3">Pilih Zona Anda</p>
        <div style="max-width: 640px; margin: 0 auto;">
            <svg viewBox="0 0 700 520" style="width: 100%; height: auto; display: block;" xmlns="http://www.w3.org/2000/svg">
                <rect x="0" y="0" width="700" height="520" fill="#F5F0E8" rx="4"/>
                <rect x="220" y="20" width="260" height="50" rx="6" fill="#000000"/>
                <text x="350" y="51" text-anchor="middle" fill="#E8FF00" font-size="20" font-weight="800" font-family="'Inter',sans-serif" letter-spacing="3">PANGGUNG</text>
                @foreach($zoneData as $zone)
                    @php
                        $sel = ($selectedZone['id'] ?? null) === $zone['id'];
                        $canClick = !$zone['soldOut'] && $event->sales_open;
                    @endphp
                    <g class="{{ $canClick ? 'cursor-pointer' : 'cursor-not-allowed' }}" wire:key="zone-{{ $zone['id'] }}" wire:click="{{ $canClick ? 'selectZone(' . $zone['id'] . ')' : '' }}">
                        <path d="{{ $zone['pathData'] }}" fill="{{ $zone['color'] }}" fill-opacity="{{ $zone['soldOut'] ? '0.3' : '0.95' }}" stroke-linejoin="round" style="{{ $sel ? 'filter:drop-shadow(0 0 12px rgba(0,0,0,0.5));stroke:#000;stroke-width:3;' : '' }}"/>
                        @unless($zone['soldOut'])
                            <text x="{{ $zone['labelX'] }}" y="{{ $zone['labelY'] - 22 }}" fill="{{ $zone['textColor'] }}" font-size="8" opacity="0.75" text-anchor="middle" font-family="'Inter',sans-serif">{{ $zone['remaining'] }} kursi tersisa</text>
                        @endunless
                        <text x="{{ $zone['labelX'] }}" y="{{ $zone['labelY'] - 8 }}" fill="{{ $zone['textColor'] }}" font-size="13" font-weight="700" text-anchor="middle" font-family="'Inter',sans-serif">{{ $zone['name'] }}</text>
                        @if($zone['soldOut'])
                            <text x="{{ $zone['labelX'] }}" y="{{ $zone['labelY'] + 16 }}" fill="#FFFFFF" font-size="12" font-weight="700" text-anchor="middle" opacity="0.9">HABIS</text>
                        @else
                            <text x="{{ $zone['labelX'] }}" y="{{ $zone['labelY'] + 16 }}" fill="{{ $zone['textColor'] }}" font-size="10" text-anchor="middle" opacity="0.85">IDR {{ number_format($zone['price'], 0, ',', '.') }}</text>
                        @endif
                    </g>
                @endforeach
                @php
                    $tribuneZones = collect($zoneData)->filter(fn($z) => $z['zoneType'] === 'tribune');
                    $hasTribuneKanan = $tribuneZones->count() >= 2;
                    $hasRegular = collect($zoneData)->contains(fn($z) => $z['zoneType'] === 'regular');
                @endphp
                @if(!$hasTribuneKanan)
                    @php $tkSel = ($selectedZone['id'] ?? null) === ($tribuneZones->first()['id'] ?? null); @endphp
                    <g class="cursor-pointer" wire:key="zone-tribune-right" wire:click="{{ $tribuneZones->first() ? 'selectZone(' . $tribuneZones->first()['id'] . ')' : '' }}">
                        <path d="M 505 195 L 670 200 L 640 420 L 505 400 Z" fill="#F26B9E" fill-opacity="0.95" stroke-linejoin="round" style="{{ $tkSel ? 'filter:drop-shadow(0 0 12px rgba(0,0,0,0.5));stroke:#000;stroke-width:3;' : '' }}"/>
                        <text x="588" y="278" fill="#FFFFFF" font-size="8" opacity="0.75" text-anchor="middle" font-family="'Inter',sans-serif">{{ $tribuneZones->first()['remaining'] ?? 0 }} kursi tersisa</text>
                        <text x="588" y="292" fill="#FFFFFF" font-size="13" font-weight="700" text-anchor="middle" font-family="'Inter',sans-serif">Tribune Kanan</text>
                        <text x="588" y="316" fill="#FFFFFF" font-size="10" text-anchor="middle" opacity="0.85">IDR {{ number_format($tribuneZones->first()['price'] ?? 0, 0, ',', '.') }}</text>
                    </g>
                @endif
                @if(!$hasRegular)
                    <g class="cursor-not-allowed" wire:key="zone-regular-fallback">
                        <path d="M 195 345 L 505 345 L 470 430 L 230 430 Z" fill="#9E9E9E" fill-opacity="0.3" stroke-linejoin="round"/>
                        <text x="350" y="375" fill="#FFFFFF" font-size="13" font-weight="700" text-anchor="middle" font-family="'Inter',sans-serif">Regular</text>
                        <text x="350" y="399" fill="#FFFFFF" font-size="12" font-weight="700" text-anchor="middle" opacity="0.9">HABIS</text>
                    </g>
                @endif
                <g transform="translate(30, 470)">
                    @php $legendIndex = 0; @endphp
                    @foreach($zoneData as $zone)
                        <g transform="translate({{ $legendIndex++ * 140 }}, 0)">
                            <rect x="0" y="0" width="18" height="18" rx="4" fill="{{ $zone['soldOut'] ? '#9E9E9E' : $zone['color'] }}" opacity="{{ $zone['soldOut'] ? '0.5' : '1' }}"/>
                            <text x="26" y="14" fill="#5F5E5A" font-size="13" font-weight="600" font-family="'Inter',sans-serif">{{ $zone['name'] }}</text>
                        </g>
                    @endforeach
                    @if(!$hasRegular)
                        <g transform="translate({{ $legendIndex * 140 }}, 0)">
                            <rect x="0" y="0" width="18" height="18" rx="4" fill="#9E9E9E" opacity="0.5"/>
                            <text x="26" y="14" fill="#5F5E5A" font-size="13" font-weight="600" font-family="'Inter',sans-serif">Regular</text>
                        </g>
                    @endif
                </g>
            </svg>
        </div>
    </div>

    {{-- Selected Zone Info Panel --}}
    @if($selectedZone)
    <div class="bg-ink text-cream mx-4 md:mx-6 mt-2 rounded-card p-5 max-w-7xl">
        <div class="flex items-start justify-between mb-3">
            <h3 class="font-display text-xl font-semibold uppercase">{{ $selectedZone['name'] }}</h3>
            <button wire:click="selectZone({{ $selectedZone['id'] }})" class="text-cream/30 hover:text-cream">
                <svg width="16" height="16" class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px!important;height:16px!important;display:inline-block!important"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="border-t border-white/10 pt-3 flex items-center justify-between mb-3">
            <span class="font-body text-xs text-white/50 uppercase tracking-[0.06em]">Harga per tiket</span>
            <span class="font-display text-xl font-semibold text-accent">IDR {{ number_format($selectedZone['price'], 0, ',', '.') }}</span>
        </div>
        <div class="flex items-center justify-between mb-4">
            <span class="font-body text-xs text-white/50 uppercase tracking-[0.06em]">Sisa kuota</span>
            <span class="font-body text-sm font-bold">{{ number_format($selectedZone['remaining']) }} tiket</span>
        </div>
    </div>
    @endif

    {{-- CTA --}}
    <div class="max-w-7xl mx-auto px-4 md:px-6 pb-5 pt-3">
        @if(!$event->sales_open)
            <div class="block w-full text-center font-body text-sm font-bold uppercase tracking-[0.15em] bg-surface-2 text-muted rounded-pill py-3.5">Penjualan Ditutup</div>
        @elseif(!$selectedZone || $selectedZone['soldOut'])
            <div class="block w-full text-center font-body text-xs font-semibold uppercase tracking-[0.1em] bg-surface-2 text-muted rounded-pill py-3.5">Pilih Zona Untuk Lanjut</div>
        @else
            <a href="{{ route('orders.create', ['event' => $event, 'section' => $selectedZone['id']]) }}"
               class="block w-full text-center font-body text-sm font-bold uppercase tracking-[0.12em] bg-accent text-ink rounded-pill py-3 hover:bg-cream transition-colors">
                @auth Lanjut Pembelian @else Login untuk Beli Tiket @endauth
            </a>
        @endif
    </div>
</div>

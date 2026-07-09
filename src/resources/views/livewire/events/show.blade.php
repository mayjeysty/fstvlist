<div class="min-h-screen bg-cream" x-data="{ highlight: false }">
    {{-- Top Header --}}
    <div class="bg-ink text-cream px-4 py-4 flex items-center justify-between">
        <a href="{{ route('events.index') }}" class="w-8 h-8 bg-surface-2 flex items-center justify-center shrink-0"><svg width="16" height="16" class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px!important;height:16px!important;display:inline-block!important"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg></a>
        <div class="text-right ml-3">
            <h1 class="font-display text-lg font-semibold uppercase leading-none">{{ $event->name }}</h1>
            <p class="font-body text-[10px] text-cream/40 uppercase tracking-[0.12em] mt-0.5">{{ $event->start_time->format('d M Y') }}</p>
            @if(!$event->sales_open)<span class="inline-block mt-1 font-body text-[10px] font-bold uppercase tracking-[0.15em] bg-error text-white px-2 py-0.5">Ditutup</span>@endif
        </div>
    </div>

    {{-- ================================================================
         Grid: Poster (kiri 45%) + Info (kanan 55%)
         ================================================================ --}}
    <div class="max-w-7xl mx-auto px-4 md:px-6 mt-4">
        <div class="grid grid-cols-1 md:grid-cols-[45%_55%] gap-6">

            {{-- LEFT: Poster --}}
            <div class="aspect-square max-w-[480px] rounded-xl overflow-hidden bg-gradient-to-br from-accent/30 via-accent/5 to-ink/10 relative" style="max-width:480px;">
                @if($event->banner)
                    <img src="{{ Storage::url($event->banner) }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center relative overflow-hidden">
                        <svg class="absolute inset-0 w-full h-full opacity-10" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                            <line x1="0" y1="0" x2="100" y2="100" stroke="#000" stroke-width="1"/>
                            <line x1="100" y1="0" x2="0" y2="100" stroke="#000" stroke-width="1"/>
                            <line x1="50" y1="0" x2="50" y2="100" stroke="#000" stroke-width="0.5"/>
                            <line x1="0" y1="50" x2="100" y2="50" stroke="#000" stroke-width="0.5"/>
                            <polygon points="30,20 70,20 50,50" fill="#000" opacity="0.3"/>
                            <polygon points="20,60 80,60 50,90" fill="#000" opacity="0.15"/>
                        </svg>
                        <svg class="w-16 h-16 text-ink/20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 18V5l12-2v13"/>
                            <circle cx="6" cy="18" r="3"/>
                            <circle cx="18" cy="16" r="3"/>
                        </svg>
                    </div>
                @endif
            </div>

            {{-- RIGHT: Info --}}
            <div class="flex flex-col">
                {{-- Title --}}
                <h1 class="font-display text-3xl md:text-4xl font-semibold uppercase leading-[1.05]">{{ $event->name }}</h1>

                {{-- "oleh" + Bagikan --}}
                <div class="flex items-center justify-between mt-2">
                    <p class="font-body text-sm text-mid-gray">
                        oleh <a href="{{ route('events.index', ['search' => $event->venue->name]) }}" class="font-bold text-ink hover:underline transition-colors">{{ $event->venue->name }}</a>
                    </p>
                    <button onclick="navigator.clipboard?.writeText(window.location.href)"
                            class="font-body text-[11px] font-semibold text-ink uppercase tracking-[0.06em] bg-white border border-border-light rounded-pill px-3.5 py-1.5 hover:bg-ink hover:text-cream transition-colors shrink-0 flex items-center gap-1.5">
                        <svg width="12" height="12" class="w-3 h-3 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:12px!important;height:12px!important;display:inline-block!important">
                            <path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"/>
                            <polyline points="16 6 12 2 8 6"/>
                            <line x1="12" y1="2" x2="12" y2="15"/>
                        </svg>
                        Bagikan
                    </button>
                </div>

                {{-- City --}}
                <p class="flex items-center gap-1.5 mt-4 font-body text-sm text-ink">
                    <svg width="16" height="16" class="w-4 h-4 text-mid-gray shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:16px!important;height:16px!important;display:inline-block!important">
                        <rect x="4" y="2" width="16" height="20" rx="2" ry="2"/>
                        <path d="M9 22v-4h6v4"/>
                        <path d="M8 6h.01"/><path d="M16 6h.01"/>
                        <path d="M12 6h.01"/><path d="M12 10h.01"/>
                        <path d="M12 14h.01"/><path d="M16 10h.01"/>
                        <path d="M16 14h.01"/><path d="M8 10h.01"/>
                        <path d="M8 14h.01"/>
                    </svg>
                    Kota {{ $city ?? $event->venue->city }}
                </p>

                {{-- Address (Google Maps link) --}}
                <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($event->venue->address . ', ' . ($city ?? $event->venue->city)) }}"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="flex items-center gap-1.5 mt-1.5 font-body text-sm text-mid-gray hover:text-ink transition-colors">
                    <svg width="16" height="16" class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:16px!important;height:16px!important;display:inline-block!important">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                        <circle cx="12" cy="10" r="3"/>
                    </svg>
                    <span class="underline underline-offset-2 decoration-mid-gray/40 hover:decoration-ink">{{ $event->venue->address }}, {{ $city ?? $event->venue->city }}</span>
                </a>

                {{-- 3 Info Cards: Tanggal | Waktu | Tipe Event --}}
                <div class="grid grid-cols-3 gap-2.5 mt-6">
                    <div class="bg-white rounded-xl border border-border-light/60 p-3.5">
                        <p class="font-body text-[10px] font-semibold text-mid-gray uppercase tracking-[0.06em]">Tanggal</p>
                        <p class="font-body text-sm font-bold text-ink mt-1">{{ $event->start_time->format('d M Y') }}</p>
                    </div>
                    <div class="bg-white rounded-xl border border-border-light/60 p-3.5">
                        <p class="font-body text-[10px] font-semibold text-mid-gray uppercase tracking-[0.06em] flex items-center gap-1">
                            Waktu
                            <span class="relative" x-data="{ tip: false }">
                                <svg width="12" height="12" @mouseenter="tip = true" @mouseleave="tip = false" class="w-3 h-3 text-mid-gray cursor-help shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:12px!important;height:12px!important;display:inline-block!important">
                                    <circle cx="12" cy="12" r="10"/>
                                    <path d="M12 16v-4"/><path d="M12 8h.01"/>
                                </svg>
                                <div x-show="tip" x-cloak class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-3 py-2 bg-ink text-cream text-[10px] leading-relaxed rounded whitespace-nowrap z-50">Durasi termasuk gate opening</div>
                            </span>
                        </p>
                        <p class="font-body text-sm font-bold text-ink mt-1">{{ $event->start_time->format('H:i') }} - {{ $event->end_time->format('H:i') }} WIB</p>
                    </div>
                    <div class="bg-white rounded-xl border border-border-light/60 p-3.5">
                        <p class="font-body text-[10px] font-semibold text-mid-gray uppercase tracking-[0.06em]">Tipe Event</p>
                        <p class="font-body text-sm font-bold text-ink mt-1">Music Event</p>
                    </div>
                </div>

                {{-- Harga + CTA (terpisah dari 3 kartu di atas) --}}
                <div class="flex items-center gap-3 mt-5 p-4 bg-white rounded-xl border border-border-light/60">
                    <div>
                        <p class="font-body text-[10px] font-semibold text-mid-gray uppercase tracking-[0.06em]">Mulai dari</p>
                        <p class="font-display text-xl font-semibold text-ink mt-1">Rp{{ number_format($minPrice ?? 0, 0, ',', '.') }}</p>
                    </div>
                    <a href="{{ route('events.zones', $event) }}"
                       class="flex-1 text-center font-body text-sm font-bold uppercase tracking-[0.08em] bg-accent text-ink rounded-pill py-3.5 hover:bg-accent-hover transition-colors no-underline">
                        Beli Tiket
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabs: Deskripsi & Info Penyelenggara --}}
    <div class="max-w-7xl mx-auto px-4 md:px-6 mt-8" x-data="{ tab: 'deskripsi', expanded: false }">
        <div class="flex gap-6 border-b border-border-light">
            <button @click="tab = 'deskripsi'" :class="tab === 'deskripsi' ? 'font-bold text-ink border-accent' : 'font-medium text-muted border-transparent'" class="font-body text-xs uppercase tracking-[0.08em] pb-3 border-b-2 transition-colors">Deskripsi</button>
            <button @click="tab = 'info'" :class="tab === 'info' ? 'font-bold text-ink border-accent' : 'font-medium text-muted border-transparent'" class="font-body text-xs uppercase tracking-[0.08em] pb-3 border-b-2 transition-colors">Info Penyelenggara</button>
        </div>

        {{-- Tab: Deskripsi --}}
        <div x-show="tab === 'deskripsi'" class="pt-4">
            @if($event->description)
                <div class="relative">
                    <p class="font-body text-sm text-ink/70 leading-relaxed" :class="expanded ? '' : 'line-clamp-3'" x-ref="descText">
                        {{ $event->description }}
                    </p>
                    @if(strlen($event->description) > 300)
                        <button @click="expanded = !expanded" class="font-body text-xs font-semibold text-ink underline mt-1 hover:text-accent transition-colors" x-text="expanded ? 'Tutup' : 'Selengkapnya'"></button>
                    @endif
                </div>
            @else
                <p class="font-body text-sm text-mid-gray">Belum ada deskripsi untuk event ini.</p>
            @endif
            <button class="mt-4 font-body text-xs font-semibold text-ink uppercase tracking-[0.06em] border border-ink rounded-pill px-5 py-2 hover:bg-ink hover:text-cream transition-colors flex items-center gap-1.5">
                <svg width="14" height="14" class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:14px!important;height:14px!important;display:inline-block!important">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                    <line x1="16" y1="13" x2="8" y2="13"/>
                    <line x1="16" y1="17" x2="8" y2="17"/>
                    <polyline points="10 9 9 9 8 9"/>
                </svg>
                Syarat &amp; Ketentuan
            </button>
        </div>

        {{-- Tab: Info Penyelenggara --}}
        <div x-show="tab === 'info'" class="pt-4" x-cloak>
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-full bg-ink flex items-center justify-center font-display text-2xl font-semibold text-accent shrink-0">
                    {{ strtoupper(mb_substr($event->venue->name, 0, 1)) }}
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <p class="font-body text-base font-bold text-ink flex items-center gap-1.5">
                            <svg width="16" height="16" class="w-4 h-4 text-mid-gray shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:16px!important;height:16px!important;display:inline-block!important">
                                <rect x="4" y="2" width="16" height="20" rx="2" ry="2"/>
                                <path d="M9 22v-4h6v4"/>
                                <path d="M8 6h.01"/><path d="M16 6h.01"/>
                                <path d="M12 6h.01"/><path d="M12 10h.01"/>
                                <path d="M12 14h.01"/><path d="M16 10h.01"/>
                                <path d="M16 14h.01"/><path d="M8 10h.01"/>
                                <path d="M8 14h.01"/>
                            </svg>
                            <a href="{{ route('events.index') }}" class="hover:underline">{{ $event->venue->name }}</a>
                        </p>
                        <span class="inline-flex items-center gap-1 text-accent bg-ink text-[10px] font-bold px-1.5 py-0.5 rounded-sm">VERIFIED</span>
                    </div>
                    <p class="font-body text-xs text-mid-gray mt-1">Penyelenggara event</p>
                </div>
            </div>
            <a href="{{ route('events.index', ['search' => $event->venue->name]) }}" class="inline-block mt-5 font-body text-xs font-semibold text-ink uppercase tracking-[0.06em] border border-ink rounded-pill px-5 py-2.5 hover:bg-ink hover:text-cream transition-colors no-underline">Lihat Semua Event dari Penyelenggara Ini</a>
        </div>
    </div>

    {{-- Tentang Acara --}}
    @if($event->description)
    <div class="max-w-7xl mx-auto px-4 md:px-6 pb-5" style="margin-top: 32px;">
        <p class="font-body text-[11px] font-semibold text-mid-gray uppercase tracking-[0.06em] mb-2">Tentang Acara</p>
        <p class="font-body text-sm text-ink/60 leading-relaxed" style="max-width: 640px;">{{ $event->description }}</p>
    </div>
    @endif
</div>

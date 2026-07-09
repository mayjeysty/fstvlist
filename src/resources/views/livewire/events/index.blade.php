<div class="max-w-7xl mx-auto px-6 md:px-12 pb-24">
    <div class="mb-12">
        <span class="font-body text-[0.65rem] font-semibold tracking-[0.3em] uppercase text-mid-gray mb-3 block">
            Explore
        </span>
        <h1 class="font-display text-4xl md:text-5xl font-semibold italic text-ink leading-none mb-4">
            Daftar Acara
        </h1>
        <p class="font-body text-sm text-mid-gray tracking-[0.02em]">Temukan konser favoritmu</p>

        <div class="flex flex-col sm:flex-row gap-3 mt-8">
            <input
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="Cari acara atau venue..."
                class="flex-1 font-body text-sm bg-white border border-border text-ink px-4 py-3
                       focus:outline-none focus:border-ink uppercase tracking-[0.06em] placeholder:text-mid-gray"
            >
            <select wire:model.live="city"
                    class="font-body text-sm bg-white border border-border text-ink px-4 py-3 cursor-pointer
                           focus:outline-none focus:border-ink uppercase tracking-[0.06em]">
                <option value="">Semua Kota</option>
                @foreach($cities as $c)
                    <option value="{{ $c }}">{{ $c }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse($events as $i => $event)
            <a href="{{ route('events.show', $event) }}"
               class="group block border border-ink/10 hover:border-ink/30 transition-colors bg-white">
                {{-- Gambar --}}
                <div class="aspect-[4/3] bg-ink/5 relative overflow-hidden">
                    @if($event->banner)
                        <img src="{{ Storage::url($event->banner) }}" class="w-full h-full object-cover group-hover:scale-[1.03] transition-transform duration-500">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-accent/30 via-accent/5 to-ink/10 flex items-center justify-center relative overflow-hidden">
                            {{-- Pattern geometris --}}
                            <svg class="absolute inset-0 w-full h-full opacity-10" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                                <rect x="0" y="0" width="100" height="100" fill="none"/>
                                <line x1="0" y1="0" x2="100" y2="100" stroke="#000" stroke-width="1"/>
                                <line x1="100" y1="0" x2="0" y2="100" stroke="#000" stroke-width="1"/>
                                <line x1="50" y1="0" x2="50" y2="100" stroke="#000" stroke-width="0.5"/>
                                <line x1="0" y1="50" x2="100" y2="50" stroke="#000" stroke-width="0.5"/>
                                <polygon points="30,20 70,20 50,50" fill="#000" opacity="0.3"/>
                                <polygon points="20,60 80,60 50,90" fill="#000" opacity="0.15"/>
                            </svg>
                            {{-- Ikon musik --}}
                            <svg class="w-10 h-10 text-ink/20 group-hover:text-accent/50 transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 18V5l12-2v13"/>
                                <circle cx="6" cy="18" r="3"/>
                                <circle cx="18" cy="16" r="3"/>
                            </svg>
                        </div>
                    @endif

                    {{-- Badge status --}}
                    <div class="absolute top-3 left-3 flex gap-1.5">
                        @if(!$event->sales_open)
                            <span class="font-body text-[0.55rem] font-bold uppercase tracking-[0.15em] bg-ink text-cream px-2 py-1">Ditutup</span>
                        @else
                            <span class="font-body text-[0.55rem] font-bold uppercase tracking-[0.15em] bg-accent text-ink px-2 py-1">Segera</span>
                        @endif
                        @php
                            $totalRemaining = $event->eventSections->sum('remaining');
                        @endphp
                        @if($event->sales_open && $totalRemaining > 0 && $totalRemaining <= 50)
                            <span class="font-body text-[0.55rem] font-bold uppercase tracking-[0.15em] bg-coral text-white px-2 py-1">Sisa {{ $totalRemaining }}</span>
                        @endif
                    </div>
                </div>

                {{-- Info --}}
                <div class="p-4">
                    <h3 class="font-display text-base font-semibold leading-tight text-ink mb-1 group-hover:italic transition-all">
                        {{ $event->name }}
                    </h3>
                    <p class="font-body text-[0.6rem] font-semibold tracking-[0.25em] uppercase text-mid-gray mb-2">
                        {{ $event->venue->name }} · {{ $event->venue->city ?? '' }}
                    </p>
                    <div class="flex items-center justify-between font-body text-[0.65rem]">
                        <span class="text-mid-gray tracking-[0.08em] uppercase flex items-center gap-1">
                            <svg class="w-3 h-3 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            {{ $event->start_time->format('d/m/Y') }} · {{ $event->start_time->format('H:i') }}
                        </span>
                        @php
                            $minPrice = $event->eventSections->isNotEmpty()
                                ? $event->eventSections->min('price')
                                : $event->venue->sections->min('price');
                        @endphp
                        @if($minPrice)
                            <div class="text-right">
                                <div class="font-body text-[10px] text-mid-gray leading-tight">Mulai dari</div>
                                <div class="font-body text-xs font-bold text-ink leading-tight">Rp{{ number_format($minPrice, 0, ',', '.') }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </a>
        @empty
            <div class="col-span-full text-center py-24 px-4">
                {{-- Ilustrasi --}}
                <svg class="w-16 h-16 mx-auto text-mid-gray/20 mb-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="M8 14s1.5 2 4 2 4-2 4-2"/>
                    <line x1="9" y1="9" x2="9.01" y2="9"/>
                    <line x1="15" y1="9" x2="15.01" y2="9"/>
                </svg>
                <p class="font-display text-xl font-semibold text-mid-gray mb-2">Belum Ada Acara Tersedia</p>
                <p class="font-body text-sm text-mid-gray/60 max-w-xs mx-auto">Saat ini belum ada konser yang bisa dipesan. Cek kembali nanti untuk update acara terbaru dari penyelenggara favoritmu.</p>
            </div>
        @endforelse
    </div>

    @if($events->hasPages())
        <div class="mt-12 flex justify-center">
            {{ $events->links() }}
        </div>
    @endif
</div>
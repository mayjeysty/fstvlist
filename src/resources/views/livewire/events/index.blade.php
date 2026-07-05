<div class="max-w-7xl mx-auto px-6 md:px-12">
    <div class="mb-12">
        <span class="font-body text-[0.65rem] font-semibold tracking-[0.3em] uppercase text-mid-gray mb-3 block">
            Explore
        </span>
        <h1 class="font-display text-4xl md:text-5xl font-black italic text-ink leading-none mb-4">
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
        @php
            $cardColors = ['bg-card-lavender', 'bg-card-yellow', 'bg-card-coral'];
        @endphp
        @forelse($events as $i => $event)
            <a href="{{ route('events.show', $event) }}"
               class="group {{ $cardColors[$i % 3] }} block border border-ink/10 hover:border-ink/30 transition-colors">
                <div class="h-52 bg-ink/10 relative overflow-hidden">
                    @if($event->banner)
                        <img src="{{ Storage::url($event->banner) }}" class="w-full h-full object-cover mix-blend-multiply opacity-80 group-hover:opacity-100 group-hover:mix-blend-normal transition-all duration-500">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <span class="font-display text-7xl font-black italic text-ink/10">{{ substr($event->name, 0, 1) }}</span>
                        </div>
                    @endif
                    @if(!$event->sales_open)
                        <span class="absolute top-3 right-3 font-body text-[0.55rem] font-bold uppercase tracking-[0.15em] bg-ink text-cream px-2 py-1">Ditutup</span>
                    @endif
                </div>
                <div class="p-5">
                    <span class="font-body text-[0.6rem] font-semibold tracking-[0.25em] uppercase text-ink/50 mb-2 block">
                        {{ $event->venue->city ?? $event->venue->name }}
                    </span>
                    <h3 class="font-display text-lg font-bold leading-tight text-ink mb-3 group-hover:italic transition-all">
                        {{ $event->name }}
                    </h3>
                    <div class="flex items-center justify-between font-body text-xs">
                        <span class="text-ink/50 tracking-[0.08em] uppercase">
                            {{ $event->start_time->format('d/m/Y') }}
                        </span>
                        @php
                            $minPrice = $event->eventSections->isNotEmpty()
                                ? $event->eventSections->min('price')
                                : $event->venue->sections->min('price');
                        @endphp
                        @if($minPrice)
                            <span class="font-bold text-ink">IDR {{ number_format($minPrice, 0, ',', '.') }}</span>
                        @endif
                    </div>
                </div>
            </a>
        @empty
            <div class="col-span-full text-center py-20">
                <span class="font-display text-5xl font-black italic text-mid-gray/20 block mb-4">—</span>
                <p class="font-body text-sm uppercase tracking-[0.15em] text-mid-gray">Tidak ada acara ditemukan</p>
            </div>
        @endforelse
    </div>

    @if($events->hasPages())
        <div class="mt-12 flex justify-center">
            {{ $events->links() }}
        </div>
    @endif
</div>

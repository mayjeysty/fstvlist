<div class="min-h-[calc(100vh-200px)] flex items-center justify-center px-4 py-12"
     wire:poll.10s="refreshStatus">
    <div class="w-full max-w-lg">

        {{-- Event Info --}}
        <div class="text-center mb-10">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-ink/5 text-ink/60 text-[0.65rem] font-semibold uppercase tracking-[0.15em] mb-4">
                <span class="w-2 h-2 rounded-full bg-accent animate-blink"></span>
                Antrian
            </div>
            <h1 class="font-display text-3xl md:text-4xl font-semibold text-ink leading-tight">
                {{ $event->name }}
            </h1>
            @if($event->venue)
                <p class="text-sm text-mid-gray mt-2 tracking-wide">
                    {{ $event->venue->name }}, {{ $event->venue->city }}
                </p>
            @endif
            <p class="text-xs text-mid-gray/60 mt-1 uppercase tracking-[0.1em]">
                {{ $event->start_time->format('D, d M Y · H:i') }} WIB
            </p>
        </div>

        {{-- Queue Status Card --}}
        <div class="bg-white rounded-2xl shadow-[0_2px_20px_rgba(0,0,0,0.06)] border border-border-light overflow-hidden">

            @if($queueEntry && $queueEntry->status === 'waiting')
                {{-- WAITING STATE --}}
                <div class="px-8 pt-10 pb-8 text-center">
                    {{-- Animated Queue Icon --}}
                    <div class="relative w-24 h-24 mx-auto mb-6">
                        <div class="absolute inset-0 rounded-full bg-accent/20 animate-ping opacity-75"></div>
                        <div class="relative w-24 h-24 rounded-full bg-ink flex items-center justify-center">
                            <svg class="w-10 h-10 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                            </svg>
                        </div>
                    </div>

                    <h2 class="font-display text-2xl font-semibold text-ink mb-2">
                        {{ $waitingAhead > 0 ? 'Kamu di Antrian!' : 'Giliranmu Segera!' }}
                    </h2>

                    <p class="text-sm text-mid-gray mb-8 leading-relaxed max-w-xs mx-auto">
                        {{ $waitingAhead > 0
                            ? "Masih ada {$waitingAhead} orang di depanmu. Halaman ini akan memperbarui secara otomatis."
                            : 'Kamu yang berikutnya! Harap tunggu sebentar.' }}
                    </p>

                    {{-- Queue Number --}}
                    <div class="inline-flex items-baseline gap-2 mb-6">
                        <span class="text-[0.65rem] font-semibold uppercase tracking-[0.15em] text-mid-gray">Antrian</span>
                        <span class="font-display text-6xl font-semibold text-ink leading-none">
                            {{ $queueEntry->queue_number }}
                        </span>
                    </div>

                    {{-- Progress Bar --}}
                    <div class="w-full bg-cream rounded-full h-2 mb-3 overflow-hidden">
                        <div class="bg-accent h-2 rounded-full transition-all duration-1000 ease-out"
                             style="width: {{ $totalWaiting > 0 ? ((($totalWaiting - $waitingAhead) / $totalWaiting) * 100) : 0 }}%">
                        </div>
                    </div>
                    <p class="text-xs text-mid-gray/60">
                        @if($waitingAhead > 0)
                            {{ $waitingAhead }} dari {{ $totalWaiting }} orang di depan
                        @else
                            Giliran selanjutnya
                        @endif
                    </p>
                </div>

                {{-- Waiting Tips --}}
                <div class="border-t border-border-light px-8 py-4 bg-cream/50">
                    <div class="flex items-start gap-3">
                        <svg class="w-4 h-4 text-accent mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 18v-5.25m0 0a6.01 6.01 0 001.5-.189m-1.5.189a6.01 6.01 0 01-1.5-.189m3.75 7.478a12.06 12.06 0 01-4.5 0m3.75 2.383a14.406 14.406 0 01-3 0M14.25 18v-.192c0-.983.658-1.823 1.508-2.316a7.5 7.5 0 10-7.517 0c.85.493 1.509 1.333 1.509 2.316V18" />
                        </svg>
                        <p class="text-[0.7rem] text-mid-gray leading-relaxed">
                            Halaman ini akan otomatis mengarahkanmu ke halaman pemesanan saat giliranmu tiba. <strong>Jangan tutup halaman ini.</strong>
                        </p>
                    </div>
                </div>

            @elseif($queueEntry && $queueEntry->isActive())
                {{-- ACTIVE STATE: redirect already happens in component, but show fallback --}}
                <div class="px-8 py-12 text-center">
                    <div class="w-16 h-16 rounded-full bg-success/20 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-success" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                    </div>
                    <h2 class="font-display text-2xl font-semibold text-ink mb-2">Giliranmu Tiba!</h2>
                    <p class="text-sm text-mid-gray mb-6">Kamu akan diarahkan ke halaman pemesanan...</p>
                    <a href="{{ route('orders.create', $event) }}" class="ds-btn ds-btn--accent">
                        Pesan Tiket Sekarang
                    </a>
                </div>

            @elseif($queueEntry && $queueEntry->status === 'expired')
                {{-- EXPIRED STATE --}}
                <div class="px-8 py-12 text-center">
                    <div class="w-16 h-16 rounded-full bg-error/10 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-error" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                    </div>
                    <h2 class="font-display text-2xl font-semibold text-ink mb-2">Waktu Habis</h2>
                    <p class="text-sm text-mid-gray mb-6">Waktu antrianmu telah habis. Silakan coba lagi.</p>
                    <a href="{{ route('events.show', $event) }}" class="ds-btn ds-btn--primary">
                        Kembali ke Event
                    </a>
                </div>

            @else
                {{-- ERROR/UNKNOWN STATE --}}
                <div class="px-8 py-12 text-center">
                    <div class="w-16 h-16 rounded-full bg-error/10 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-error" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                        </svg>
                    </div>
                    <h2 class="font-display text-2xl font-semibold text-ink mb-2">Terjadi Kesalahan</h2>
                    <p class="text-sm text-mid-gray mb-6">Gagal bergabung ke antrian. Silakan coba lagi.</p>
                    <a href="{{ route('events.show', $event) }}" class="ds-btn ds-btn--primary">
                        Kembali ke Event
                    </a>
                </div>
            @endif
        </div>

        {{-- Auto-refresh indicator --}}
        @if($queueEntry && $queueEntry->status === 'waiting')
            <div class="text-center mt-6">
                <div class="inline-flex items-center gap-2 text-[0.6rem] text-mid-gray/40 uppercase tracking-[0.15em] font-semibold">
                    <svg class="w-3 h-3 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                    </svg>
                    Memperbarui otomatis setiap 10 detik
                </div>
            </div>
        @endif

    </div>
</div>



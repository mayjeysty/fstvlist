@extends('layouts.app')

@section('content')
{{-- HERO (100vh, Blok 1) --}}
<div data-theme="dark" class="relative bg-ink text-white min-h-screen flex items-end overflow-hidden">
    <div class="absolute top-0 left-0 w-[500px] h-[500px] rounded-full opacity-[0.08]" style="background: radial-gradient(circle, #B0A0F8 0%, transparent 70%);"></div>
    <div class="absolute bottom-0 right-0 w-[400px] h-[400px] rounded-full opacity-[0.07]" style="background: radial-gradient(circle, #F26B9E 0%, transparent 70%);"></div>

    <div class="relative max-w-7xl mx-auto px-4 pb-12 md:pb-16 w-full pt-20 md:pt-24">
        <span class="font-body text-[11px] font-semibold text-accent uppercase tracking-[0.12em] block mb-4">
            Platform tiket konser #1 Indonesia
        </span>
        <h1 class="font-display text-[clamp(48px,8vw,96px)] font-black uppercase leading-[0.92] tracking-tight">
            RASAKAN<br>KONSER<br>LANGSUNG<span class="text-accent">.</span>
        </h1>
        <p class="font-body text-sm text-white/50 max-w-[300px] leading-relaxed mt-4">
            Temukan konser terbaik, pilih zona impianmu, dan dapatkan e-tiket instan dengan QR code.
        </p>
        <div class="flex flex-wrap gap-3 mt-7">
            <a href="{{ route('events.index') }}" class="inline-flex items-center font-body text-[13px] font-bold uppercase tracking-[0.04em] bg-accent text-ink rounded-pill px-7 py-3.5 hover:bg-cream transition-colors">
                Jelajahi Konser
            </a>
            <a href="#about" class="inline-flex items-center font-body text-[13px] font-bold uppercase tracking-[0.04em] border border-solid border-white/30 text-white rounded-pill px-7 py-3.5 hover:border-white hover:bg-white hover:text-ink transition-colors">
                Cara Kerja
            </a>
        </div>
    </div>

    {{-- Mini Player (bottom-right) --}}
    <div class="absolute bottom-6 right-6 hidden lg:flex items-center gap-3 bg-surface-2/90 backdrop-blur-md rounded-xl border border-white/10 px-4 py-3 min-w-[200px]">
        <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-accent to-ember"></div>
        <div class="flex-1 min-w-0">
            <p class="font-body text-xs font-semibold truncate">{{ $featuredEvents->first()?->name ?? 'Konser Tropis' }}</p>
            <p class="font-body text-[10px] text-muted">{{ $featuredEvents->first()?->venue?->city ?? 'Jakarta' }}</p>
        </div>
        <div class="flex items-center gap-1.5">
            <button class="text-white/30 hover:text-accent text-xs">⏮</button>
            <button class="text-accent text-xs">⏸</button>
            <button class="text-white/30 hover:text-accent text-xs">⏭</button>
        </div>
    </div>
</div>

{{-- Marquee Ticker --}}
<div data-theme="light" class="bg-cream border-t border-solid border-border-light py-3.5 overflow-hidden whitespace-nowrap">
    <div class="inline-flex gap-12 animate-ticker">
        @foreach(range(1, 2) as $i)
            <span class="font-display text-xl font-bold text-ink uppercase flex items-center gap-4">
                <span class="w-2.5 h-2.5 bg-accent inline-block"></span> Konser Tropis
            </span>
            <span class="font-display text-xl font-bold text-ink uppercase flex items-center gap-4">
                <span class="w-2.5 h-2.5 bg-accent inline-block"></span> Neon Lights Fest
            </span>
            <span class="font-display text-xl font-bold text-ink uppercase flex items-center gap-4">
                <span class="w-2.5 h-2.5 bg-accent inline-block"></span> Indie Vibes Vol.3
            </span>
            <span class="font-display text-xl font-bold text-ink uppercase flex items-center gap-4">
                <span class="w-2.5 h-2.5 bg-accent inline-block"></span> Summer Sonic ID
            </span>
            <span class="font-display text-xl font-bold text-ink uppercase flex items-center gap-4">
                <span class="w-2.5 h-2.5 bg-accent inline-block"></span> Jakarta Sound Fest
            </span>
        @endforeach
    </div>
</div>

{{-- Upcoming Events --}}
<div data-theme="light" class="bg-cream py-16 md:py-24">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-end justify-between mb-10">
            <div>
                <h2 class="font-display text-[clamp(28px,4vw,36px)] font-bold uppercase text-ink leading-[1.05]">Konser<br>Mendatang</h2>
            </div>
            <a href="{{ route('events.index') }}" class="font-body text-xs font-semibold text-ink uppercase border-b-2 border-ink pb-1 hover:text-accent hover:border-accent transition-colors">
                Lihat Semua →
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
            @forelse($featuredEvents as $i => $event)
                @php
                    $gradients = [
                        'from-accent to-purple-soft',
                        'from-coral to-ember',
                        'from-purple-soft to-success',
                    ];
                @endphp
                <a href="{{ route('events.show', $event) }}" class="group block">
                    <div class="aspect-[4/3] rounded-t-xl overflow-hidden bg-gradient-to-br {{ $gradients[$i % 3] }} flex items-center justify-center">
                        @if($event->banner)
                            <img src="{{ Storage::url($event->banner) }}" class="w-full h-full object-cover group-hover:scale-[1.04] transition-transform duration-300">
                        @else
                            <span class="font-display text-5xl font-black text-white/20 uppercase">{{ substr($event->name, 0, 1) }}</span>
                        @endif
                    </div>
                    <div class="bg-ink text-white rounded-b-xl px-4 py-3.5">
                        <h3 class="font-display font-bold text-base uppercase leading-tight">{{ $event->name }}</h3>
                        <p class="font-body text-[11px] text-muted mt-1">{{ $event->venue->name }} · {{ $event->venue->city ?? '' }} · {{ $event->start_time->format('d M Y') }}</p>
                        <div class="flex items-center justify-between mt-2">
                            @php
                                $minPrice = $event->eventSections->isNotEmpty()
                                    ? $event->eventSections->min('price')
                                    : $event->venue->sections->min('price');
                            @endphp
                            <span class="font-body text-xs font-bold text-accent">IDR {{ number_format($minPrice ?? 0, 0, ',', '.') }}</span>
                            <span class="font-body text-[10px] font-semibold uppercase bg-accent text-ink rounded-pill px-2.5 py-1">Segera</span>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-full text-center py-16">
                    <p class="font-display text-2xl font-bold text-muted mb-2">Segera Hadir</p>
                    <p class="font-body text-sm text-mid-gray">Cek kembali nanti untuk update acara terbaru.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

{{-- ================================================================
     Section: Cara Kerja (id="about")
     Empat langkah alur pemesanan — grid 4 kolom desktop, 1 kolom mobile
     ================================================================ --}}
<div data-theme="dark" id="about" class="bg-ink py-20 md:py-28">
    <div class="max-w-7xl mx-auto px-4">
        {{-- Eyebrow --}}
        <span class="font-body text-[11px] font-semibold text-accent uppercase tracking-[0.12em] block mb-3">
            ALUR PEMESANAN
        </span>

        {{-- Title --}}
        <h2 class="font-display text-[clamp(28px,4vw,36px)] font-bold uppercase text-white leading-[1.05] mb-3">
            Empat Langkah Menuju<br>Konser Impianmu.
        </h2>

        {{-- Subtitle --}}
        <p class="font-body text-sm text-cream/60 max-w-xl leading-relaxed mb-12">
            Dari pilih acara sampai tiket di tangan, semua bisa kamu lakukan dalam hitungan menit.
        </p>

        {{-- Steps Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 md:gap-4">
            {{-- Step 01 --}}
            <div class="bg-surface-1 border border-white/10 rounded-card p-6 flex flex-col">
                <span class="font-display text-5xl font-black text-accent/30 leading-none mb-4">01</span>
                <div class="w-10 h-10 rounded-lg bg-accent/15 flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <h3 class="font-display text-lg font-bold text-white mb-2">Pilih Acara</h3>
                <p class="font-body text-sm text-cream/60 leading-relaxed flex-1">
                    Jelajahi daftar konser yang tersedia dan temukan acara yang paling kamu tunggu.
                </p>
            </div>

            {{-- Step 02 --}}
            <div class="bg-surface-1 border border-white/10 rounded-card p-6 flex flex-col">
                <span class="font-display text-5xl font-black text-accent/30 leading-none mb-4">02</span>
                <div class="w-10 h-10 rounded-lg bg-accent/15 flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <h3 class="font-display text-lg font-bold text-white mb-2">Pilih Zona</h3>
                <p class="font-body text-sm text-cream/60 leading-relaxed flex-1">
                    Lihat layout venue secara visual dan pilih zona sesuai budget dan posisi favoritmu.
                </p>
            </div>

            {{-- Step 03 --}}
            <div class="bg-surface-1 border border-white/10 rounded-card p-6 flex flex-col">
                <span class="font-display text-5xl font-black text-accent/30 leading-none mb-4">03</span>
                <div class="w-10 h-10 rounded-lg bg-accent/15 flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <h3 class="font-display text-lg font-bold text-white mb-2">Bayar</h3>
                <p class="font-body text-sm text-cream/60 leading-relaxed flex-1">
                    Selesaikan pembayaran dengan aman dalam waktu 15 menit sebelum kuota dilepas kembali.
                </p>
            </div>

            {{-- Step 04 --}}
            <div class="bg-surface-1 border border-white/10 rounded-card p-6 flex flex-col">
                <span class="font-display text-5xl font-black text-accent/30 leading-none mb-4">04</span>
                <div class="w-10 h-10 rounded-lg bg-accent/15 flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h-4M4 12h3m0 0h3"/>
                    </svg>
                </div>
                <h3 class="font-display text-lg font-bold text-white mb-2">Terima E-Tiket</h3>
                <p class="font-body text-sm text-cream/60 leading-relaxed flex-1">
                    E-tiket lengkap dengan QR code otomatis terkirim ke emailmu, siap dipakai di hari-H.
                </p>
            </div>
        </div>
    </div>
</div>

{{-- ================================================================
     Section: FAQ
     ================================================================ --}}
<div data-theme="dark" class="bg-ink py-20 md:py-28">
    <div class="max-w-2xl mx-auto px-4">
        <div class="text-center mb-8">
            <span class="font-body text-[11px] font-semibold text-accent uppercase tracking-[0.12em] block mb-2">FAQ</span>
            <h2 class="font-display text-4xl font-black uppercase text-white leading-none">Pertanyaan Umum</h2>
        </div>

        <div class="space-y-3" x-data="{ open: null }">
            @php $faqs = [
                ['q' => 'Bagaimana cara membeli tiket?', 'a' => 'Cari acara favoritmu di daftar konser. Pilih zona pada layout venue interaktif. Tentukan jumlah tiket (maks 4). Isi data diri dan konfirmasi pesanan. Bayar dalam waktu 15 menit. E-tiket langsung dikirim ke email Anda.'],
                ['q' => 'Berapa lama waktu pembayaran?', 'a' => 'Anda memiliki waktu 15 menit untuk menyelesaikan pembayaran setelah pemesanan dibuat. Jika melebihi batas waktu, tiket akan dilepas kembali dan kuota dikembalikan untuk pengguna lain.'],
                ['q' => 'Bagaimana cara masuk ke venue?', 'a' => 'Tunjukkan QR code dari e-tiket Anda di pintu masuk. Petugas gate akan memindai kode QR. Satu tiket hanya berlaku untuk satu kali masuk. Pastikan baterai ponsel cukup atau cetak e-tiket sebagai cadangan.'],
                ['q' => 'Apakah tiket bisa di-refund?', 'a' => 'Tiket yang sudah dibeli tidak dapat di-refund atau dikembalikan. Pastikan data diri, zona, dan jumlah tiket sudah benar sebelum menyelesaikan pembayaran.'],
            ]; @endphp

            @foreach($faqs as $i => $faq)
                <div class="bg-surface-1 border border-white/10 rounded-xl overflow-hidden">
                    <button @click="open === {{ $i }} ? open = null : open = {{ $i }}"
                            class="w-full flex items-center justify-between p-4 text-left font-display text-base font-bold text-white hover:text-accent transition-colors">
                        {{ $faq['q'] }}
                        <span class="text-xl shrink-0 ml-4 transition-transform duration-300" :class="open === {{ $i }} ? 'rotate-45' : ''">+</span>
                    </button>
                    <div x-show="open === {{ $i }}" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                        <div class="px-4 pb-4 font-body text-sm text-cream/60 leading-relaxed">
                            {{ $faq['a'] }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

{{-- ================================================================
     Section: CTA Akhir — high contrast sebelum footer
     ================================================================ --}}
<div data-theme="light" class="bg-accent py-20 md:py-24">
    <div class="max-w-3xl mx-auto px-4 text-center">
        <h2 class="font-display text-[clamp(32px,5vw,52px)] font-black uppercase text-ink leading-[1.05] mb-4">
            Siap Rasakan<br>Konser Impianmu?
        </h2>
        <p class="font-body text-sm text-ink/60 max-w-lg mx-auto leading-relaxed mb-8">
            Ribuan zona terbaik menunggu untuk kamu klaim. Jangan sampai kehabisan tempat di barisan depan.
        </p>
        <a href="{{ route('events.index') }}"
           class="inline-flex items-center font-body text-[13px] font-bold uppercase tracking-[0.04em] bg-ink text-white rounded-pill px-8 py-4 hover:bg-surface-1 transition-colors">
            Jelajahi Konser Sekarang
        </a>
    </div>
</div>

{{-- ================================================================
     Section: Sponsor Strip (DIHAPUS — disimpan sebagai referensi)
     ================================================================ --}}
{{--
<div data-theme="dark" class="bg-ink border-t border-solid border-white/10">
    <div class="max-w-7xl mx-auto px-4 grid grid-cols-5 divide-x divide-white/10">
        @foreach(['Telkomsel', 'BCA', 'Tokopedia', 'GoJek', 'Kompas'] as $sponsor)
            <div class="flex items-center justify-center py-5">
                <span class="font-body text-[11px] font-semibold text-white/35 uppercase tracking-[0.08em]">{{ $sponsor }}</span>
            </div>
        @endforeach
    </div>
</div>
--}}
@endsection

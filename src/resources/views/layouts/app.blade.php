<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'FSTVLIST') }} — @yield('title', $title ?? 'Discover Events')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,700;0,9..144,900;1,9..144,700&family=Inter:ital,opsz,wght@0,14..32,400;0,14..32,500;0,14..32,600;0,14..32,700;1,14..32,400&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    @stack('styles')
</head>
<body class="font-body bg-cream text-ink overflow-x-hidden">

    {{-- ================================================================
         NAVBAR: Adaptive, Hide/Show on Scroll, Glassmorphism
         ================================================================
         (a) Hide/show scroll — Alpine @scroll.window detects direction
         (b) Adaptive color    — IntersectionObserver reads [data-theme]
         (c) Glassmorphism     — backdrop-blur + semi-transparent bg on scroll
         --}}
    @php
        $isHome = request()->routeIs('home');
        $isLoginPage = request()->routeIs('login');
    @endphp

    @if($isLoginPage)
    {{-- Minimal header for Login / Register pages --}}
    <div class="flex items-center justify-between px-5 py-3 bg-ink pl-8">
        <a href="{{ route('home') }}" class="font-display text-xl font-semibold no-underline text-white flex items-baseline gap-[0.12rem] shrink-0">
            FSTV<span class="inline-block w-[5px] h-[5px] align-middle mb-[1px] bg-accent"></span>LIST
        </a>
        <a href="{{ url('/') }}" class="font-body text-sm text-white/60 no-underline hover:text-accent transition-colors">&larr; Kembali ke Beranda</a>
    </div>
    @endif
    @unless($isLoginPage)
    <nav id="mainNavbar"
         x-data="{
             mobileOpen: false,
             isHidden: false,
             lastY: 0,
             activeTheme: {{ $isHome ? "'dark'" : "'light'" }},
             isScrolled: false
         }"
         x-init="
             /* (b) Adaptive color — IntersectionObserver per section */
             $nextTick(() => {
                 const observer = new IntersectionObserver((entries) => {
                     entries.forEach(entry => {
                         if (entry.isIntersecting) activeTheme = entry.target.dataset.theme || 'dark'
                     })
                 }, { rootMargin: '-80px 0px 0px 0px' })
                 document.querySelectorAll('[data-theme]').forEach(el => observer.observe(el))
             })
         "
         @scroll.window="
             const y = window.pageYOffset
             isScrolled = y > 20
             if (y === 0) {
                 isHidden = false
             } else if (y > lastY) {
                 isHidden = true           /* (a) scroll down → hide */
             } else if (y < lastY) {
                 isHidden = false           /* (a) scroll up   → show */
             }
             lastY = y
         "
         :class="{
              'backdrop-blur-xl':         isScrolled,
              'bg-black/30':              isScrolled && activeTheme === 'dark',
              'bg-white/80':              isScrolled && activeTheme === 'light',
              'bg-transparent':           !isScrolled && activeTheme === 'dark',
              'bg-cream':                 !isScrolled && activeTheme === 'light',
          }"
         :style="isHidden ? 'transform:translateY(-100%)' : 'transform:translateY(0)'"
         class="fixed top-0 left-0 right-0 z-[100] flex items-center justify-between px-6 md:px-10 h-16 transition-all duration-500"
         style="border:none;outline:none;box-shadow:none;">

        {{-- Logo — warna menurut activeTheme --}}
        <a href="{{ route('home') }}"
           :class="activeTheme === 'dark' ? 'text-white' : 'text-ink'"
           class="font-display text-xl font-semibold no-underline flex items-baseline gap-[0.12rem] shrink-0 transition-colors duration-500">
            FSTV<span class="inline-block w-[5px] h-[5px] align-middle mb-[1px] bg-accent"></span>LIST
        </a>

        {{-- Right side: nav links + profile/login --}}
        <div class="flex items-center gap-6">
            {{-- Nav link Acara --}}
            <a href="{{ route('events.index') }}"
               :class="activeTheme === 'dark' ? 'text-white/60 hover:text-white' : 'text-mid-gray hover:text-ink'"
               class="hidden md:inline font-body text-xs font-semibold tracking-[0.06em] uppercase no-underline transition-colors duration-500">
                Acara
            </a>
            {{-- Nav link Tentang --}}
            <a href="{{ $isHome ? '#about' : route('home').'#about' }}"
               :class="activeTheme === 'dark' ? 'text-white/60 hover:text-white' : 'text-mid-gray hover:text-ink'"
               class="hidden md:inline font-body text-xs font-semibold tracking-[0.06em] uppercase no-underline transition-colors duration-500">
                Tentang
            </a>
            {{-- Nav link Kontak --}}
            <a href="#contact"
               :class="activeTheme === 'dark' ? 'text-white/60 hover:text-white' : 'text-mid-gray hover:text-ink'"
               class="hidden md:inline font-body text-xs font-semibold tracking-[0.06em] uppercase no-underline transition-colors duration-500">
                Kontak
            </a>

            <div class="flex items-center gap-3">
                @auth
                    <div class="relative" x-data="{ profileOpen: false }">
                        <button @click="profileOpen = !profileOpen"
                                :class="activeTheme === 'dark' ? 'text-white' : 'text-ink'"
                                class="flex items-center gap-2 cursor-pointer font-body text-xs font-semibold tracking-[0.06em] uppercase transition-colors duration-500">
                            <div class="w-7 h-7 flex items-center justify-center font-display text-sm font-semibold"
                                 :class="activeTheme === 'dark' ? 'bg-accent text-ink' : 'bg-ink text-cream'">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <span class="hidden lg:inline"
                                  :class="activeTheme === 'dark' ? 'text-white/50' : 'text-mid-gray'">{{ auth()->user()->name }}</span>
                        </button>
                        <div x-show="profileOpen" @click.away="profileOpen = false" x-transition
                             class="absolute right-0 mt-3 w-48 bg-white border border-border-light py-2 z-50">
                            <a href="{{ route('orders.index') }}" class="block px-4 py-2 text-xs text-ink hover:bg-cream transition font-body uppercase tracking-[0.06em]">Pesanan Saya</a>
                            <a href="{{ route('orders.index') }}" class="block px-4 py-2 text-xs text-ink hover:bg-cream transition font-body uppercase tracking-[0.06em]">Pesanan</a>
                            <hr class="border-border-light my-1">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="w-full text-left px-4 py-2 text-xs text-error hover:bg-cream transition font-body uppercase tracking-[0.06em]">Keluar</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}"
                       :class="activeTheme === 'dark'
                           ? 'border-accent text-accent hover:bg-accent hover:text-ink'
                           : 'border-ink text-ink hover:bg-ink hover:text-cream'"
                       class="font-body text-xs font-semibold tracking-[0.06em] uppercase no-underline rounded-pill px-5 py-2 border transition-colors duration-500">
                        Masuk
                    </a>
                    <a href="{{ route('login', ['tab' => 'register']) }}"
                       class="font-body text-xs font-semibold tracking-[0.06em] uppercase no-underline rounded-pill px-5 py-2 bg-accent text-ink transition-colors duration-500 hover:bg-accent-hover">
                        Daftar
                    </a>
                @endauth

                <button @click="mobileOpen = !mobileOpen"
                        :class="activeTheme === 'dark' ? 'text-white' : 'text-ink'"
                        class="md:hidden transition-colors duration-500">
                    <svg x-show="!mobileOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <svg x-show="mobileOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div x-show="mobileOpen" x-transition @click.away="mobileOpen = false"
             class="absolute top-16 left-0 right-0 bg-cream border-b border-border-light md:hidden z-50">
            <div class="px-6 py-4 space-y-3">
                <a href="{{ route('events.index') }}" class="block text-sm font-semibold text-ink py-1 uppercase tracking-[0.06em]">Acara</a>
                <a href="#about" class="block text-sm font-semibold text-ink py-1 uppercase tracking-[0.06em]">Tentang</a>
                <a href="#contact" class="block text-sm font-semibold text-ink py-1 uppercase tracking-[0.06em]">Kontak</a>
                <hr class="border-border-light">
                @auth
                    <a href="{{ route('orders.index') }}" class="block text-sm text-ink py-1 uppercase tracking-[0.06em]">Pesanan Saya</a>
                    <a href="{{ route('orders.index') }}" class="block text-sm text-ink py-1 uppercase tracking-[0.06em]">Pesanan</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="w-full text-left text-sm text-error py-1 uppercase tracking-[0.06em]">Keluar</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="block text-sm font-semibold text-ink py-1 uppercase tracking-[0.06em]">Login</a>
                @endauth
            </div>
        </div>
    </nav>
    @endunless

    {{-- FLASH MESSAGES --}}
    <div class="fixed top-[76px] right-1.5 md:right-6 z-[200] flex flex-col gap-2 max-w-none md:max-w-[360px] left-4 md:left-auto font-body" id="flashContainer">
        @if(session('error'))
            <div class="py-3 px-5 text-xs font-medium flex items-start gap-2 animate-slide-in bg-ink text-cream border-l-2 border-ember" data-auto-dismiss>
                <span>{{ session('error') }}</span>
            </div>
        @endif
        @if(session('errors') && session('errors')->any())
            <div class="py-3 px-5 text-xs font-medium flex items-start gap-2 animate-slide-in bg-ink text-cream border-l-2 border-ember" data-auto-dismiss>
                <div>
                    @foreach(session('errors')->all() as $err)
                        <div>{{ $err }}</div>
                    @endforeach
                </div>
            </div>
        @endif
        @if(session('success'))
            <div class="py-3 px-5 text-xs font-medium flex items-start gap-2 animate-slide-in bg-ink text-cream border-l-2 border-accent" data-auto-dismiss>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if($errors->any())
            <div class="py-3 px-5 text-xs font-medium flex items-start gap-2 animate-slide-in bg-ink text-cream border-l-2 border-ember" data-auto-dismiss>
                <div>
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    {{-- MAIN CONTENT: pt-16 di-skip untuk home agar hero menutupi area di belakang navbar --}}
    <main class="{{ $isHome || $isLoginPage ? '' : 'pt-16' }} min-h-screen">
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    @unless($isLoginPage)
    {{-- TICKER TAPE --}}
    <div class="bg-ink text-cream py-2 overflow-hidden whitespace-nowrap font-body text-xs font-semibold tracking-[0.2em] uppercase">
        <div class="inline-flex gap-16 animate-ticker">
            @foreach(range(1, 2) as $i)
                <span class="flex items-center gap-3"><span class="w-[5px] h-[5px] bg-accent inline-block"></span> UPCOMING EVENTS</span>
                <span class="flex items-center gap-3"><span class="w-[5px] h-[5px] bg-accent inline-block"></span> BOOK YOUR TICKETS NOW</span>
                <span class="flex items-center gap-3"><span class="w-[5px] h-[5px] bg-accent inline-block"></span> LIVE MUSIC &amp; FESTIVALS</span>
                <span class="flex items-center gap-3"><span class="w-[5px] h-[5px] bg-accent inline-block"></span> LIMITED SEATS AVAILABLE</span>
                <span class="flex items-center gap-3"><span class="w-[5px] h-[5px] bg-accent inline-block"></span> FSTVLIST — YOUR STAGE AWAITS</span>
            @endforeach
        </div>
    </div>

    {{-- FOOTER --}}
    <footer id="contact" class="bg-ink text-cream py-16 px-6 md:px-10 font-body">
        <div class="max-w-[1200px] mx-auto grid grid-cols-1 md:grid-cols-[2fr_1fr_1fr] gap-10 md:gap-16">
            <div>
                <div class="font-display text-4xl md:text-5xl font-semibold italic text-cream leading-none mb-4">FSTVLIST</div>
                <p class="text-xs text-cream/40 leading-relaxed max-w-[280px] uppercase tracking-[0.06em]">
                    Your go-to platform for discovering and booking the best live events, concerts, and festivals.
                </p>
            </div>
            <div>
                <p class="text-[0.65rem] tracking-[0.2em] uppercase text-cream/25 mb-5">Navigation</p>
                <ul class="list-none flex flex-col gap-2.5">
                    <li><a href="{{ route('events.index') }}" class="text-cream/60 no-underline text-xs transition-colors hover:text-accent uppercase tracking-[0.08em]">Acara</a></li>
                    @auth
                    <li><a href="{{ route('orders.index') }}" class="text-cream/60 no-underline text-xs transition-colors hover:text-accent uppercase tracking-[0.08em]">Pesanan Saya</a></li>
                    @endauth
                </ul>
            </div>
            <div>
                <p class="text-[0.65rem] tracking-[0.2em] uppercase text-cream/25 mb-5">Legal</p>
                <ul class="list-none flex flex-col gap-2.5">
                    <li><a href="#" class="text-cream/60 no-underline text-xs transition-colors hover:text-accent uppercase tracking-[0.08em]">Syarat &amp; Ketentuan</a></li>
                    <li><a href="#" class="text-cream/60 no-underline text-xs transition-colors hover:text-accent uppercase tracking-[0.08em]">Kebijakan Privasi</a></li>
                    <li><a href="#" class="text-cream/60 no-underline text-xs transition-colors hover:text-accent uppercase tracking-[0.08em]">Hubungi Kami</a></li>
                </ul>
            </div>
        </div>
        <div class="max-w-[1200px] mx-auto mt-12 pt-6 border-t border-cream/10 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 text-[0.65rem] text-cream/25 uppercase tracking-[0.15em]">
            <span>&copy; {{ date('Y') }} FSTVLIST. All rights reserved.</span>
            <span>Made with passion for live music</span>
        </div>
    </footer>
    @endunless

    @livewireScripts

    <script>
        document.querySelectorAll('[data-auto-dismiss]').forEach(el => {
            setTimeout(() => {
                el.style.transition = 'opacity 0.4s, transform 0.4s';
                el.style.opacity = '0';
                el.style.transform = 'translateX(20px)';
                setTimeout(() => el.remove(), 400);
            }, 4500);
        });

        {{-- Navbar logic now handled by Alpine x-data in the <nav> element above --}}
    </script>

    @stack('scripts')
</body>
</html>

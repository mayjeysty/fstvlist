<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>FSTVLIST — Gate Validator</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,700;0,9..144,900;1,9..144,700&family=Inter:ital,opsz,wght@0,14..32,400;0,14..32,500;0,14..32,600;0,14..32,700;1,14..32,400&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/qr-scanner.js'])
    @livewireStyles

    @stack('styles')
</head>
<body class="font-body bg-ink text-cream min-h-screen">

    {{-- GATE NAVBAR --}}
    <nav class="flex items-center justify-between px-8 h-16 border-b border-cream/10 sticky top-0 bg-ink z-50">
        <a href="{{ route('events.index') }}"
           class="font-display text-xl font-black italic text-cream no-underline">
            FSTV<span class="text-accent">●</span>LIST
        </a>

        <div class="flex items-center gap-2 font-body text-[0.6rem] font-bold tracking-[0.2em] uppercase bg-accent/10 border border-accent/30 text-accent py-1 px-3 animate-blink">
            Gate Validator
        </div>

        <div class="flex items-center gap-4 font-body text-xs">
            @auth
                <div class="flex items-center gap-2 text-cream/60">
                    <div class="w-7 h-7 bg-accent text-ink flex items-center justify-center font-display text-sm italic font-black">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <span class="uppercase tracking-[0.1em] text-xs">{{ auth()->user()->name }}</span>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button class="text-xs uppercase tracking-[0.12em] border border-cream/20 text-cream/40 px-3 py-1.5 hover:border-ember hover:text-ember transition-colors">
                        Logout
                    </button>
                </form>
            @endauth
        </div>
    </nav>

    {{-- GATE FLASH MESSAGES --}}
    <div class="fixed top-[76px] right-6 z-[200] flex flex-col gap-2 max-w-[360px]">
        @if(session('error'))
            <div class="py-[0.85rem] px-5 rounded-xl text-[0.82rem] font-medium flex items-start gap-[0.6rem] animate-slide-in shadow-[0_4px_20px_rgba(0,0,0,0.3)] bg-error/15 border border-error/40 text-error" data-auto-dismiss>
                <span>✕</span>
                <span>{{ session('error') }}</span>
            </div>
        @endif
        @if(session('success'))
            <div class="py-[0.85rem] px-5 rounded-xl text-[0.82rem] font-medium flex items-start gap-[0.6rem] animate-slide-in shadow-[0_4px_20px_rgba(0,0,0,0.3)] bg-success/15 border border-success/40 text-success" data-auto-dismiss>
                <span>✓</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if($errors->any())
            <div class="py-[0.85rem] px-5 rounded-xl text-[0.82rem] font-medium flex items-start gap-[0.6rem] animate-slide-in shadow-[0_4px_20px_rgba(0,0,0,0.3)] bg-error/15 border border-error/40 text-error" data-auto-dismiss>
                <span>✕</span>
                <div>
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    {{-- GATE MAIN CONTENT --}}
    <main class="min-h-[calc(100vh-64px)]">
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    @livewireScripts

    <script>
        document.querySelectorAll('[data-auto-dismiss]').forEach(el => {
            setTimeout(() => {
                el.style.transition = 'opacity 0.4s, transform 0.4s';
                el.style.opacity = '0';
                el.style.transform = 'translateX(20px)';
                setTimeout(() => el.remove(), 400);
            }, 5000);
        });
    </script>

    @stack('scripts')
</body>
</html>

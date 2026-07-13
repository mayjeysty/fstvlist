<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'FSTVLIST') }} — @yield('title', $title ?? 'Booking')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,700;0,9..144,900&family=Inter:ital,opsz,wght@0,14..32,400;0,14..32,500;0,14..32,600;0,14..32,700&display=swap" rel="stylesheet">

    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="manifest" href="/site.webmanifest">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @stack('styles')
</head>
<body class="font-body bg-cream text-ink overflow-x-hidden" style="min-height:100vh;">

    {{-- ─── TOP NAVBAR (hitam) ─── --}}
    <nav style="background:#000000;color:#FFFFFF;display:flex;align-items:center;justify-content:space-between;padding:var(--space-3) var(--space-6);position:sticky;top:0;z-index:100;">
        <a href="{{ route('home') }}" style="display:flex;align-items:baseline;gap:2px;text-decoration:none;">
            <span style="font-family:'ClashDisplay-Semibold','Fraunces',Georgia,serif;font-size:1.25rem;font-weight:650;color:#FFFFFF;">FSTV</span>
            <span style="display:inline-block;width:5px;height:5px;background:#E8FF00;margin-left:1px;vertical-align:middle;"></span>
            <span style="font-family:'ClashDisplay-Semibold','Fraunces',Georgia,serif;font-size:1.25rem;font-weight:650;color:#FFFFFF;">LIST</span>
        </a>
        <div style="display:flex;align-items:center;gap:var(--space-6);">
            <a href="{{ route('events.index') }}" style="font-size:var(--text-xs);font-weight:600;color:rgba(255,255,255,0.6);text-decoration:none;text-transform:uppercase;letter-spacing:0.06em;transition:color var(--transition-fast);" onmouseover="this.style.color='#E8FF00'" onmouseout="this.style.color='rgba(255,255,255,0.6)'">Acara</a>
            <a href="{{ route('orders.index') }}" style="font-size:var(--text-xs);font-weight:600;color:rgba(255,255,255,0.6);text-decoration:none;text-transform:uppercase;letter-spacing:0.06em;transition:color var(--transition-fast);" onmouseover="this.style.color='#E8FF00'" onmouseout="this.style.color='rgba(255,255,255,0.6)'">Pesanan Saya</a>
            <div style="display:flex;align-items:center;gap:var(--space-2);">
                <span style="width:24px;height:24px;border-radius:50%;background:#E8FF00;color:#000;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                <span style="font-size:var(--text-xs);color:rgba(255,255,255,0.5);">{{ auth()->user()->name }}</span>
            </div>
            <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                @csrf
                <button style="background:none;border:none;color:rgba(255,255,255,0.4);font-size:var(--text-xs);cursor:pointer;text-transform:uppercase;letter-spacing:0.06em;transition:color var(--transition-fast);padding:0;" onmouseover="this.style.color='#E24B4A'" onmouseout="this.style.color='rgba(255,255,255,0.4)'">Keluar</button>
            </form>
        </div>
    </nav>

    {{-- ─── CONTENT ─── --}}
    <main style="max-width:820px;margin:0 auto;padding:var(--space-8) var(--space-4);padding-bottom:var(--space-12);">
        {{ $slot ?? '' }}
    </main>

    {{-- FLASH MESSAGES --}}
    <div class="fixed top-4 right-4 z-[200] flex flex-col gap-2 max-w-[360px] font-body" id="flashContainer">
        @if(session('error'))
            <div class="py-3 px-5 text-xs font-medium flex items-start gap-2 animate-slide-in bg-ink text-cream border-l-2 border-ember" data-auto-dismiss>
                <span>{{ session('error') }}</span>
            </div>
        @endif
        @if(session('errors') && session('errors')->any())
            <div class="py-3 px-5 text-xs font-medium flex items-start gap-2 animate-slide-in bg-ink text-cream border-l-2 border-ember" data-auto-dismiss>
                <div>@foreach(session('errors')->all() as $err)<div>{{ $err }}</div>@endforeach</div>
            </div>
        @endif
        @if(session('success'))
            <div class="py-3 px-5 text-xs font-medium flex items-start gap-2 animate-slide-in bg-ink text-cream border-l-2 border-accent" data-auto-dismiss>
                <span>{{ session('success') }}</span>
            </div>
        @endif
    </div>

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
    </script>
    @stack('scripts')
</body>
</html>

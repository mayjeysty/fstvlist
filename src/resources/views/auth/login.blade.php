@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="min-h-screen flex flex-col md:flex-row" x-data="{ tab: '{{ request('tab', 'login') }}', showPw: false, showPwReg: false }">
    {{-- Panel Kiri: Editorial --}}
    <div class="bg-ink text-white hidden md:flex flex-col justify-between min-w-[320px] flex-1 relative overflow-hidden p-10">
        <div class="absolute top-0 left-0 w-[350px] h-[350px] rounded-full opacity-[0.06]" style="background: radial-gradient(circle, #B0A0F8 0%, transparent 70%);"></div>
        <div class="absolute bottom-0 right-0 w-[300px] h-[300px] rounded-full opacity-[0.05]" style="background: radial-gradient(circle, #F26B9E 0%, transparent 70%);"></div>

        <div class="relative">
            <a href="{{ route('home') }}" class="font-display text-xl font-black text-white no-underline">FSTV<span class="text-accent">●</span>LIST</a>
        </div>

        <div class="relative">
            <span class="font-body text-[11px] font-semibold text-accent uppercase tracking-[0.1em] block mb-3" x-text="tab === 'login' ? 'Selamat datang kembali' : 'Bergabung sekarang'"></span>
            <h2 class="font-display text-[clamp(36px,5vw,52px)] font-black uppercase leading-[0.95] tracking-tight"
                x-show="tab === 'login'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                MASUK<br>PILIH<br><span class="text-accent">RASAKAN.</span>
            </h2>
            <h2 class="font-display text-[clamp(36px,5vw,52px)] font-black uppercase leading-[0.95] tracking-tight"
                x-show="tab === 'register'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                BUAT<br>AKUN<br><span class="text-accent">GRATIS.</span>
            </h2>
            <p class="font-body text-[13px] text-white/50 leading-relaxed max-w-[280px] mt-4" x-text="tab === 'login' ? 'Ribuan konser menunggumu. Login untuk pesan tiket, lihat riwayat, dan simpan kursi favoritmu.' : 'Daftar dalam 30 detik dan dapatkan akses ke ribuan konser pop Indonesia. E-tiket instan, langsung ke email.'"></p>

            <div class="flex gap-2 mt-7">
                @foreach([['name'=>'Konser Tropis','color'=>'#E8FF00'],['name'=>'Neon Lights','color'=>'#F26B9E'],['name'=>'Indie Vibes','color'=>'#B0A0F8']] as $ev)
                    <div class="bg-surface-2 border border-white/10 rounded-lg px-3 py-2.5">
                        <div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full inline-block" style="background:{{ $ev['color'] }}"></span><span class="font-body text-[10px] font-bold uppercase">{{ $ev['name'] }}</span></div>
                        <span class="font-body text-[9px] text-muted">Segera</span>
                    </div>
                @endforeach
            </div>
        </div>

        <p class="font-body text-[10px] text-white/20 relative">&copy; {{ date('Y') }} FSTVLIST</p>
    </div>

    {{-- Panel Kanan: Form --}}
    <div class="bg-cream w-full md:w-[420px] flex items-center justify-center px-8 py-12">
        <div class="w-full max-w-[360px]">
            <div class="md:hidden mb-6">
                <a href="{{ route('home') }}" class="font-display text-xl font-black text-ink no-underline">FSTV<span class="text-accent">●</span>LIST</a>
            </div>

            {{-- Tab Toggle --}}
            <div class="bg-ink rounded-pill p-1 flex mb-10 mt-4">
                <button @click="tab = 'login'"
                        :class="tab === 'login' ? 'bg-accent text-ink' : 'text-white/50 hover:text-white'"
                        class="flex-1 font-body text-[12px] font-bold uppercase tracking-[0.04em] rounded-pill px-5 py-2.5 transition-colors">
                    Masuk
                </button>
                <button @click="tab = 'register'"
                        :class="tab === 'register' ? 'bg-accent text-ink' : 'text-white/50 hover:text-white'"
                        class="flex-1 font-body text-[12px] font-bold uppercase tracking-[0.04em] rounded-pill px-5 py-2.5 transition-colors">
                    Daftar
                </button>
            </div>

            {{-- Flash Errors --}}
            @if ($errors->any())
                <div class="bg-error/10 border border-error/30 text-error font-body text-xs rounded-xl px-4 py-3 mb-6">{{ $errors->first() }}</div>
            @endif

            {{-- LOGIN FORM --}}
            <div x-show="tab === 'login'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 -translate-x-4">
                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="font-body text-[11px] font-semibold text-mid-gray uppercase tracking-[0.06em] block mb-2">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus
                               class="w-full bg-white border border-solid border-border-light rounded-pill px-5 py-3.5 font-body text-sm text-ink placeholder:text-text-subtle focus:outline-none focus:border-ink"
                               placeholder="kamu@email.com">
                    </div>
                    <div class="relative">
                        <label class="font-body text-[11px] font-semibold text-mid-gray uppercase tracking-[0.06em] block mb-2">Password</label>
                        <input :type="showPw ? 'text' : 'password'" name="password" required
                               class="w-full bg-white border border-solid border-border-light rounded-pill px-5 py-3.5 pr-12 font-body text-sm text-ink placeholder:text-text-subtle focus:outline-none focus:border-ink"
                               placeholder="········">
                        <button type="button" @click="showPw = !showPw" class="absolute right-4 top-[38px] text-mid-gray hover:text-ink">
                            <svg x-show="!showPw" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg x-show="showPw" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        </button>
                    </div>
                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2 cursor-pointer select-none">
                            <input type="checkbox" name="remember" class="w-4 h-4 rounded border-border-light text-ink focus:ring-ink">
                            <span class="font-body text-[12px] text-mid-gray">Ingatkan saya</span>
                        </label>
                    </div>
                    <button type="submit" class="w-full bg-ink text-accent font-body text-sm font-bold uppercase tracking-[0.04em] rounded-pill py-4 hover:bg-surface-2 transition-colors">Masuk Sekarang</button>
                    <div class="flex items-center gap-4">
                        <span class="flex-1 h-px bg-border-light"></span>
                        <span class="font-body text-[11px] text-muted">atau</span>
                        <span class="flex-1 h-px bg-border-light"></span>
                    </div>
                    <button type="button" class="w-full bg-white border border-solid border-border-light rounded-pill py-4 flex items-center justify-center gap-3 font-body text-[13px] font-semibold text-ink hover:bg-cream transition-colors">
                        <span class="w-3.5 h-3.5 rounded-full bg-gradient-to-br from-accent to-coral"></span>
                        Lanjutkan dengan Google
                    </button>
                </form>
                <p class="font-body text-[12px] text-mid-gray text-center mt-5">
                    Belum punya akun? <button @click="tab = 'register'" class="text-ink font-semibold hover:text-accent border-b border-ink pb-0.5">Daftar di sini</button>
                </p>
            </div>

            {{-- REGISTER FORM --}}
            <div x-show="tab === 'register'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 translate-x-4">
                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="font-body text-[11px] font-semibold text-mid-gray uppercase tracking-[0.06em] block mb-2">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               class="w-full bg-white border border-solid border-border-light rounded-pill px-5 py-3.5 font-body text-sm text-ink placeholder:text-text-subtle focus:outline-none focus:border-ink"
                               placeholder="Nama Anda">
                    </div>
                    <div>
                        <label class="font-body text-[11px] font-semibold text-mid-gray uppercase tracking-[0.06em] block mb-2">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                               class="w-full bg-white border border-solid border-border-light rounded-pill px-5 py-3.5 font-body text-sm text-ink placeholder:text-text-subtle focus:outline-none focus:border-ink"
                               placeholder="kamu@email.com">
                    </div>
                    <div class="relative">
                        <label class="font-body text-[11px] font-semibold text-mid-gray uppercase tracking-[0.06em] block mb-2">Password</label>
                        <input :type="showPwReg ? 'text' : 'password'" name="password" required
                               class="w-full bg-white border border-solid border-border-light rounded-pill px-5 py-3.5 pr-12 font-body text-sm text-ink placeholder:text-text-subtle focus:outline-none focus:border-ink"
                               placeholder="Minimal 8 karakter">
                        <button type="button" @click="showPwReg = !showPwReg" class="absolute right-4 top-[38px] text-mid-gray hover:text-ink">
                            <svg x-show="!showPwReg" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg x-show="showPwReg" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        </button>
                    </div>
                    <div class="relative">
                        <label class="font-body text-[11px] font-semibold text-mid-gray uppercase tracking-[0.06em] block mb-2">Konfirmasi Password</label>
                        <input :type="showPwReg ? 'text' : 'password'" name="password_confirmation" required
                               class="w-full bg-white border border-solid border-border-light rounded-pill px-5 py-3.5 font-body text-sm text-ink placeholder:text-text-subtle focus:outline-none focus:border-ink"
                               placeholder="Ulangi password">
                    </div>
                    <button type="submit" class="w-full bg-ink text-accent font-body text-sm font-bold uppercase tracking-[0.04em] rounded-pill py-4 hover:bg-surface-2 transition-colors">Daftar Sekarang</button>
                    <div class="flex items-center gap-4">
                        <span class="flex-1 h-px bg-border-light"></span>
                        <span class="font-body text-[11px] text-muted">atau</span>
                        <span class="flex-1 h-px bg-border-light"></span>
                    </div>
                    <button type="button" class="w-full bg-white border border-solid border-border-light rounded-pill py-4 flex items-center justify-center gap-3 font-body text-[13px] font-semibold text-ink hover:bg-cream transition-colors">
                        <span class="w-3.5 h-3.5 rounded-full bg-gradient-to-br from-accent to-coral"></span>
                        Lanjutkan dengan Google
                    </button>
                </form>
                <p class="font-body text-[12px] text-mid-gray text-center mt-5">
                    Sudah punya akun? <button @click="tab = 'login'" class="text-ink font-semibold hover:text-accent border-b border-ink pb-0.5">Masuk di sini</button>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

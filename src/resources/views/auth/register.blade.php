@extends('layouts.app')

@section('title', 'Daftar Akun')

@section('content')
<div class="min-h-screen flex flex-col md:flex-row">
    <div class="bg-ink text-white hidden md:flex flex-col justify-between min-w-[320px] flex-1 relative overflow-hidden p-10">
        <div class="absolute top-0 left-0 w-[350px] h-[350px] rounded-full opacity-[0.06]" style="background: radial-gradient(circle, #B0A0F8 0%, transparent 70%);"></div>
        <div class="absolute bottom-0 right-0 w-[300px] h-[300px] rounded-full opacity-[0.05]" style="background: radial-gradient(circle, #F26B9E 0%, transparent 70%);"></div>

        <div class="relative">
            <a href="{{ route('home') }}" class="font-display text-xl font-black text-white no-underline">FSTV<span class="text-accent">●</span>LIST</a>
        </div>

        <div class="relative">
            <span class="font-body text-[11px] font-semibold text-accent uppercase tracking-[0.1em] block mb-3">Bergabung sekarang</span>
            <h2 class="font-display text-[clamp(36px,5vw,52px)] font-black uppercase leading-[0.95] tracking-tight">
                BUAT<br>AKUN<br><span class="text-accent">GRATIS.</span>
            </h2>
            <p class="font-body text-[13px] text-white/50 leading-relaxed max-w-[280px] mt-4">
                Daftar dalam 30 detik dan dapatkan akses ke ribuan konser pop Indonesia. E-tiket instan, langsung ke email.
            </p>
        </div>

        <p class="font-body text-[10px] text-white/20 relative">© {{ date('Y') }} FSTVLIST</p>
    </div>

    <div class="bg-cream w-full md:w-[400px] flex items-center justify-center px-8 py-12">
        <div class="w-full max-w-[340px]">
            <div class="md:hidden mb-6">
                <a href="{{ route('home') }}" class="font-display text-xl font-black text-ink no-underline">FSTV<span class="text-accent">●</span>LIST</a>
            </div>

            <h2 class="font-display text-[28px] font-black uppercase text-ink leading-[1.05] mb-1">Daftar<br>Akun Baru</h2>
            <p class="font-body text-[13px] text-mid-gray mb-8">
                Sudah punya akun? <a href="{{ route('login') }}" class="text-ink font-semibold hover:text-accent">Masuk di sini</a>
            </p>

            @if ($errors->any())
                <div class="bg-error/10 border border-error/30 text-error font-body text-xs rounded-xl px-4 py-3 mb-6">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="font-body text-[11px] font-semibold text-mid-gray uppercase tracking-[0.06em] block mb-2">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}" required autofocus
                           class="w-full bg-white border border-solid border-border-light rounded-pill px-5 py-3.5 font-body text-sm text-ink placeholder:text-text-subtle focus:outline-none focus:border-ink" placeholder="Nama Anda">
                </div>
                <div>
                    <label class="font-body text-[11px] font-semibold text-mid-gray uppercase tracking-[0.06em] block mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="w-full bg-white border border-solid border-border-light rounded-pill px-5 py-3.5 font-body text-sm text-ink placeholder:text-text-subtle focus:outline-none focus:border-ink" placeholder="kamu@email.com">
                </div>
                <div>
                    <label class="font-body text-[11px] font-semibold text-mid-gray uppercase tracking-[0.06em] block mb-2">Password</label>
                    <input type="password" name="password" required
                           class="w-full bg-white border border-solid border-border-light rounded-pill px-5 py-3.5 font-body text-sm text-ink placeholder:text-text-subtle focus:outline-none focus:border-ink" placeholder="Minimal 8 karakter">
                </div>
                <div>
                    <label class="font-body text-[11px] font-semibold text-mid-gray uppercase tracking-[0.06em] block mb-2">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" required
                           class="w-full bg-white border border-solid border-border-light rounded-pill px-5 py-3.5 font-body text-sm text-ink placeholder:text-text-subtle focus:outline-none focus:border-ink" placeholder="Ulangi password">
                </div>
                <button type="submit" class="w-full bg-ink text-accent font-body text-sm font-bold uppercase tracking-[0.04em] rounded-pill py-4 hover:bg-surface-1 transition-colors">
                    Daftar Sekarang
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

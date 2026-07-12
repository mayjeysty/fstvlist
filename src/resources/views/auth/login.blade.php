@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="min-h-screen flex flex-col md:flex-row bg-ink pt-12"
     x-data="{
         tab: '{{ request('tab', 'login') }}',
         showPw: false,
         showPwReg: false,
         loginEmail: '{{ old('email') }}',
         loginPass: '',
         regName: '{{ old('name') }}',
         regEmail: '{{ old('email') }}',
         regPass: '',

         regTerms: false,
         get loginEmailValid() {
             return this.loginEmail.length > 0 && /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.loginEmail);
         },
         get regEmailValid() {
             return this.regEmail.length > 0 && /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.regEmail);
         },
         get regPassStrength() {
             let s = 0;
             if (this.regPass.length >= 8) s++;
             if (/[a-z]/.test(this.regPass) && /[A-Z]/.test(this.regPass)) s++;
             if (/\d/.test(this.regPass)) s++;
             if (/[^a-zA-Z0-9]/.test(this.regPass)) s++;
             return s;
         },
         get regPassLabel() {
             const labels = ['', 'Lemah', 'Sedang', 'Kuat', 'Sangat Kuat'];
             return labels[this.regPassStrength];
         },

     }">
    {{-- Panel Kiri: Editorial --}}
    <div class="bg-ink text-white hidden md:flex flex-col justify-between min-w-[320px] flex-1 relative overflow-hidden p-10">

        <div class="relative">
            <span class="font-body text-[13px] font-semibold text-accent uppercase tracking-[0.1em] block mb-3" x-text="tab === 'login' ? 'Selamat datang kembali' : 'Bergabung sekarang'"></span>
            <h2 class="font-display text-[clamp(42px,5.5vw,58px)] font-semibold uppercase leading-[0.95] tracking-tight"
                x-show="tab === 'login'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                MASUK<br>PILIH<br><span class="text-accent">RASAKAN.</span>
            </h2>
            <h2 class="font-display text-[clamp(42px,5.5vw,58px)] font-semibold uppercase leading-[0.95] tracking-tight"
                x-show="tab === 'register'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                BUAT<br>AKUN<br><span class="text-accent">GRATIS.</span>
            </h2>
            <div x-show="tab === 'login'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                <p class="font-body text-[16px] text-white/60 leading-[1.7] max-w-[400px] mt-6">Masuk ke akun FSTVLIST-mu, pilih konser favorit, dan dapatkan e-ticket langsung setelah pembayaran berhasil.</p>
                <div class="flex flex-col gap-2.5 mt-5">
                    <div class="flex items-center gap-2.5">
                        <span class="text-[16px]">&#127934;</span>
                        <span class="font-body text-[14px] text-white/60">Pembayaran aman</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <span class="text-[16px]">&#128274;</span>
                        <span class="font-body text-[14px] text-white/60">Data terenkripsi</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <span class="text-[16px]">&#128231;</span>
                        <span class="font-body text-[14px] text-white/60">E-ticket otomatis</span>
                    </div>
                </div>
            </div>
            <div x-show="tab === 'register'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                <p class="font-body text-[16px] text-white/60 leading-[1.7] max-w-[400px] mt-6">Daftar gratis, pilih konser favoritmu, dan dapatkan e-ticket langsung setelah pembayaran berhasil.</p>
                <div class="flex flex-col gap-2.5 mt-5">
                    <div class="flex items-center gap-2.5">
                        <span class="text-[16px]">&#127934;</span>
                        <span class="font-body text-[14px] text-white/60">Pembayaran aman</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <span class="text-[16px]">&#128274;</span>
                        <span class="font-body text-[14px] text-white/60">Data terenkripsi</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <span class="text-[16px]">&#128231;</span>
                        <span class="font-body text-[14px] text-white/60">E-ticket otomatis</span>
                    </div>
                </div>
            </div>
        </div>

        <p class="font-body text-[10px] text-white/20 relative">&copy; {{ date('Y') }} FSTVLIST</p>
    </div>

    {{-- Panel Kanan: Form --}}
    <div class="w-full md:w-[620px] flex items-center justify-center px-6 py-6">
        <div class="w-full max-w-[560px] bg-cream rounded-xl shadow-2xl shadow-white/[0.15] p-5 md:p-6">
            {{-- Pill Tabs: Masuk / Daftar --}}
            <div class="bg-ink rounded-pill p-1 flex mb-4">
                <button @click="tab = 'login'"
                        :class="tab === 'login' ? 'bg-accent text-ink' : 'text-white/70 hover:text-white/90'"
                        class="flex-1 font-body text-[13px] font-bold uppercase tracking-[0.04em] rounded-pill px-5 py-2 transition-all duration-200 hover:opacity-80">
                    Masuk
                </button>
                <button @click="tab = 'register'"
                        :class="tab === 'register' ? 'bg-accent text-ink' : 'text-white/70 hover:text-white/90'"
                        class="flex-1 font-body text-[13px] font-bold uppercase tracking-[0.04em] rounded-pill px-5 py-2 transition-all duration-200 hover:opacity-80">
                    Daftar
                </button>
            </div>

            {{-- Flash Errors --}}
            @if ($errors->any())
                <div class="bg-error/10 border border-error/30 text-error font-body text-[13px] rounded-xl px-4 py-3 mb-4">{{ $errors->first() }}</div>
            @endif

            {{-- LOGIN FORM --}}
            <div x-show="tab === 'login'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 -translate-x-4">
                <p class="font-body text-[16px] text-mid-gray leading-[1.7] mb-2">Masuk ke akun FSTVLIST kamu untuk melanjutkan.</p>
                <form method="POST" action="{{ route('login') }}" class="space-y-3">
                    @csrf
                    <div>
                        <label for="loginEmail" class="font-body text-[13px] font-semibold text-mid-gray uppercase tracking-[0.06em] block mb-1.5">Email</label>
                        <input id="loginEmail" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email"
                               x-model="loginEmail"
                               class="w-full bg-white border border-solid border-ink/20 rounded-pill px-5 py-2.5 font-body text-[15px] text-ink placeholder:text-ink/30 focus:outline-none focus:ring-0 focus:border-accent focus:border-2 transition-all duration-200"
                               placeholder="kamu@email.com">
                        <template x-if="loginEmail.length > 0">
                            <p class="flex items-center gap-1 mt-1 font-body text-[12px]" x-show="loginEmailValid" x-cloak>
                                <svg class="w-3.5 h-3.5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                <span class="text-green-700">Email valid</span>
                            </p>
                            <p class="flex items-center gap-1 mt-1 font-body text-[12px]" x-show="!loginEmailValid" x-cloak>
                                <svg class="w-3.5 h-3.5 text-error" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                <span class="text-error">Email tidak valid</span>
                            </p>
                        </template>
                    </div>
                    <div class="relative">
                        <label for="loginPass" class="font-body text-[13px] font-semibold text-mid-gray uppercase tracking-[0.06em] block mb-1.5">Password</label>
                        <input id="loginPass" :type="showPw ? 'text' : 'password'" name="password" required autocomplete="current-password"
                               x-model="loginPass"
                               class="w-full bg-white border border-solid border-ink/20 rounded-pill px-5 py-2.5 pr-14 font-body text-[15px] text-ink placeholder:text-ink/30 focus:outline-none focus:ring-0 focus:border-accent focus:border-2 transition-all duration-200"
                               placeholder="········">
                        <button type="button" @click="showPw = !showPw" class="absolute right-5 top-[38px] text-mid-gray hover:text-ink transition-colors">
                            <svg x-show="!showPw" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg x-show="showPw" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        </button>
                    </div>
                    <div class="flex items-center justify-between pb-2">
                        <label class="flex items-center gap-2 cursor-pointer select-none min-h-[44px]">
                            <input type="checkbox" name="remember" class="w-4 h-4 rounded border-ink/20 text-ink focus:ring-ink/30 focus:ring-2">
                            <span class="font-body text-[14px] text-mid-gray">Ingatkan saya</span>
                        </label>
                    </div>
                    <button type="submit" class="w-full bg-ink text-[#D9FF00] font-body text-[14px] font-bold uppercase tracking-[0.04em] rounded-pill py-3.5 hover:bg-surface-2 transition-all duration-200 min-h-[48px]">Masuk Sekarang</button>
                    <div class="flex items-center gap-4 pb-3">
                        <span class="flex-1 h-px bg-border-light"></span>
                        <span class="font-body text-[12px] text-muted">atau</span>
                        <span class="flex-1 h-px bg-border-light"></span>
                    </div>
                    <a href="{{ route('auth.google.redirect') }}" class="w-full bg-white border border-solid border-ink/20 rounded-pill py-3.5 flex items-center justify-center gap-3 font-body text-[14px] font-semibold text-ink hover:bg-cream transition-all duration-200 min-h-[48px]">
                        <span class="w-4 h-4 rounded-full bg-gradient-to-br from-accent to-coral"></span>
                        Lanjutkan dengan Google
                    </a>
                </form>
            </div>

            {{-- REGISTER FORM --}}
            <div x-show="tab === 'register'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 translate-x-4">
                <p class="font-body text-[16px] text-mid-gray leading-[1.7] mb-2">Buat akun FSTVLIST untuk mulai memesan tiket konser favoritmu.</p>
                <form method="POST" action="{{ route('register') }}" class="space-y-3">
                    @csrf
                    <div>
                        <label for="regName" class="font-body text-[13px] font-semibold text-mid-gray uppercase tracking-[0.06em] block mb-1.5">Nama Lengkap</label>
                        <input id="regName" type="text" name="name" value="{{ old('name') }}" required autocomplete="name"
                               x-model="regName"
                               class="w-full bg-white border border-solid border-ink/20 rounded-pill px-5 py-2.5 font-body text-[15px] text-ink placeholder:text-ink/30 focus:outline-none focus:ring-0 focus:border-accent focus:border-2 transition-all duration-200"
                               placeholder="Nama lengkap Anda">
                    </div>
                    <div>
                        <label for="regEmail" class="font-body text-[13px] font-semibold text-mid-gray uppercase tracking-[0.06em] block mb-1.5">Email</label>
                        <input id="regEmail" type="email" name="email" value="{{ old('email') }}" required autocomplete="email"
                               x-model="regEmail"
                               class="w-full bg-white border border-solid border-ink/20 rounded-pill px-5 py-2.5 font-body text-[15px] text-ink placeholder:text-ink/30 focus:outline-none focus:ring-0 focus:border-accent focus:border-2 transition-all duration-200"
                               placeholder="kamu@email.com">
                        <template x-if="regEmail.length > 0">
                            <p class="flex items-center gap-1 mt-1 font-body text-[12px]"
                               x-show="!regEmailValid" x-cloak>
                                <svg class="w-3.5 h-3.5 text-error" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                <span class="text-error">Email tidak valid</span>
                            </p>
                        </template>
                    </div>
                    <div class="relative">
                        <label for="regPass" class="font-body text-[13px] font-semibold text-mid-gray uppercase tracking-[0.06em] block mb-1.5">Password</label>
                        <input id="regPass" :type="showPwReg ? 'text' : 'password'" name="password" required autocomplete="new-password"
                               x-model="regPass"
                               class="w-full bg-white border border-solid border-ink/20 rounded-pill px-5 py-2.5 pr-14 font-body text-[15px] text-ink placeholder:text-ink/30 focus:outline-none focus:ring-0 focus:border-accent focus:border-2 transition-all duration-200"
                               placeholder="Minimal 8 karakter">
                        <button type="button" @click="showPwReg = !showPwReg" class="absolute right-5 top-[38px] text-mid-gray hover:text-ink transition-colors">
                            <svg x-show="!showPwReg" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg x-show="showPwReg" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        </button>
                        <template x-if="regPass.length > 0">
                            <div class="flex items-center gap-3 mt-2" x-cloak>
                                <div class="flex gap-1 flex-1">
                                    <template x-for="i in 3" :key="i">
                                        <div class="h-1 flex-1 rounded-full transition-all duration-300"
                                             :class="i <= regPassStrength ? (regPassStrength <= 1 ? 'bg-error' : regPassStrength === 2 ? 'bg-amber-500' : 'bg-accent') : 'bg-ink/10'"></div>
                                    </template>
                                </div>
                                <span class="font-body text-[12px] shrink-0"
                                      :class="regPassStrength <= 1 ? 'text-error' : regPassStrength === 2 ? 'text-amber-600' : 'text-green-700'"
                                      x-text="regPassLabel"></span>
                            </div>
                        </template>
                        <template x-if="regPass.length === 0">
                            <p class="font-body text-[12px] text-muted/60 mt-1">Minimal 8 karakter</p>
                        </template>
                    </div>
                    <label class="flex items-start gap-2 cursor-pointer min-h-[44px] pt-1">
                        <input type="checkbox" x-model="regTerms" class="w-4 h-4 rounded border-ink/20 text-ink focus:ring-ink/30 focus:ring-2 mt-0.5 shrink-0">
                        <span class="font-body text-[13px] text-mid-gray leading-relaxed">Saya menyetujui <a href="#" class="text-ink font-semibold underline hover:text-accent">Syarat dan Ketentuan</a></span>
                    </label>
                    <button type="submit" :disabled="!regTerms"
                            class="w-full font-body text-[14px] font-bold uppercase tracking-[0.04em] rounded-pill py-3.5 transition-all duration-200 min-h-[48px]"
                            :class="regTerms ? 'bg-ink text-[#D9FF00] hover:bg-surface-2' : 'bg-ink/30 text-white/30 cursor-not-allowed'">Daftar Sekarang</button>
                    <div class="flex items-center gap-4 pb-2">
                        <span class="flex-1 h-px bg-border-light"></span>
                        <span class="font-body text-[12px] text-muted">atau</span>
                        <span class="flex-1 h-px bg-border-light"></span>
                    </div>
                    <a href="{{ route('auth.google.redirect') }}" class="w-full bg-white border border-solid border-ink/20 rounded-pill py-3.5 flex items-center justify-center gap-3 font-body text-[14px] font-semibold text-ink hover:bg-cream transition-all duration-200 min-h-[48px]">
                        <span class="w-4 h-4 rounded-full bg-gradient-to-br from-accent to-coral"></span>
                        Lanjutkan dengan Google
                    </a>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection
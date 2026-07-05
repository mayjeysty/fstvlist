<div class="min-h-screen bg-ink flex items-center justify-center px-4 py-8" wire:poll.5s="refreshStatus">
    <div class="w-full max-w-[380px]">

        {{-- Logo --}}
        <div class="text-center mb-5">
            <span class="font-display text-lg font-black text-white">FSTV<span class="text-accent">●</span>LIST</span>
        </div>

        @if($queueEntry->status === \App\Models\Queue::STATUS_EXPIRED)
            <div class="bg-error/10 border border-error/30 rounded-2xl p-8 text-center">
                <div class="w-16 h-16 bg-error/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-error" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <p class="font-display text-lg font-bold text-error">Antrian Kadaluarsa</p>
                <p class="font-body text-sm text-muted mt-2">Waktu antrian Anda telah habis.</p>
                <a href="{{ route('events.show', $event) }}" class="inline-block mt-4 font-body text-sm text-accent hover:text-accent-hover">← Kembali ke Event</a>
            </div>

        @elseif($queueEntry->status === \App\Models\Queue::STATUS_WAITING)
            {{-- Kartu Utama --}}
            <div class="bg-cream rounded-3xl p-8">

                {{-- Vinyl Animation --}}
                <div class="flex justify-center mb-6">
                    <div class="w-[100px] h-[100px] rounded-full bg-conic-gradient from-gray-300 via-gray-500 to-gray-700 animate-spin" style="animation-duration:4s; background: conic-gradient(#1a1a1a 0deg 30deg, #2a2a2a 30deg 60deg, #0d0d0d 60deg 90deg, #1a1a1a 90deg 120deg, #2a2a2a 120deg 150deg, #0d0d0d 150deg 180deg, #1a1a1a 180deg 210deg, #2a2a2a 210deg 240deg, #0d0d0d 240deg 270deg, #1a1a1a 270deg 300deg, #2a2a2a 300deg 330deg, #0d0d0d 330deg 360deg);">
                        <div class="w-[28px] h-[28px] bg-accent rounded-full absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
                            <div class="w-[8px] h-[8px] bg-ink rounded-full absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2"></div>
                        </div>
                    </div>
                </div>

                {{-- Label Section --}}
                <p class="font-body text-[11px] font-semibold text-muted uppercase tracking-[0.08em] text-center">Ruang Tunggu</p>

                {{-- Judul Utama --}}
                <h2 class="font-display text-[28px] font-bold uppercase leading-[1.05] tracking-[-0.01em] text-ink text-center mt-2">
                    HAMPIR<br>GILIRANMU!
                </h2>

                {{-- Sub Info --}}
                <p class="font-body text-[13px] text-mid-gray text-center mt-2">{{ $event->name }} · {{ $event->venue->name }}</p>

                {{-- Kotak Nomor Antrean --}}
                <div class="bg-ink rounded-2xl p-5 mt-6 text-center">
                    <p class="font-body text-[10px] text-muted uppercase tracking-[0.05em]">Nomor antreanmu</p>
                    <p class="font-display text-[48px] font-black leading-none text-accent mt-1">{{ number_format($queueEntry->queue_number, 0, '', '.') }}</p>
                    <p class="font-body text-[11px] text-mid-gray mt-1">dari {{ number_format($totalWaiting ?? 6200) }} pengguna aktif</p>
                </div>

                {{-- Progress Bar --}}
                @php $pct = $totalWaiting > 0 ? min(95, round(($queueEntry->queue_number / max($totalWaiting, 1)) * 100)) : 50; @endphp
                <div class="mt-4">
                    <div class="flex justify-between mb-1">
                        <span class="font-body text-[11px] text-mid-gray">Progres antrean</span>
                        <span class="font-body text-[11px] font-bold text-ink">{{ $pct }}%</span>
                    </div>
                    <div class="bg-border-light rounded-full h-[6px] w-full overflow-hidden">
                        <div class="bg-ink h-full rounded-full" style="width:{{ $pct }}%"></div>
                    </div>
                </div>

                {{-- Dua Stat Kotak --}}
                <div class="grid grid-cols-2 gap-2.5 mt-3">
                    <div class="bg-white border border-border-light rounded-xl p-3 text-center">
                        <p class="font-display text-[22px] font-bold text-ink leading-none">{{ number_format($waitingAhead) }}</p>
                        <p class="font-body text-[10px] text-muted uppercase tracking-[0.05em] mt-1">Di depanmu</p>
                    </div>
                    <div class="bg-white border border-border-light rounded-xl p-3 text-center">
                        <p class="font-display text-[22px] font-bold text-ink leading-none">{{ number_format(($totalWaiting ?? 6200) - $queueEntry->queue_number - $waitingAhead) }}</p>
                        <p class="font-body text-[10px] text-muted uppercase tracking-[0.05em] mt-1">Sudah masuk</p>
                    </div>
                </div>

                {{-- Estimasi Waktu --}}
                <div class="bg-accent rounded-xl p-3 flex justify-between items-center mt-3">
                    <span class="font-body text-[12px] font-semibold text-ink">Estimasi giliran</span>
                    <span class="font-display text-[22px] font-bold text-ink leading-none">~{{ max(1, ceil($waitingAhead / 100)) }} mnt</span>
                </div>
            </div>

            {{-- Countdown Timer --}}
            <div class="bg-surface-1 rounded-xl p-3.5 flex justify-between items-center mt-3 max-w-[380px]">
                <span class="font-body text-[12px] text-muted">Jangan tutup halaman ini</span>
                @php
                    $secondsLeft = $queueEntry->expires_at ? max(0, now()->diffInSeconds($queueEntry->expires_at)) : 720;
                    $mins = floor($secondsLeft / 60);
                    $secs = $secondsLeft % 60;
                    $urgent = $secondsLeft < 60;
                @endphp
                <span class="font-display text-[20px] font-bold leading-none {{ $urgent ? 'text-coral' : 'text-accent' }}" style="font-variant-numeric:tabular-nums">@if($secondsLeft > 0){{ sprintf('%02d:%02d', $mins, $secs) }}@else 00:00 @endif</span>
            </div>

            {{-- Auto-refresh hint --}}
            <p class="text-center mt-4 font-body text-[10px] text-muted/40">Halaman ini akan otomatis refresh setiap 5 detik.</p>

        @elseif($queueEntry->status === \App\Models\Queue::STATUS_COMPLETED)
            <div class="bg-success/10 border border-success/30 rounded-2xl p-8 text-center">
                <div class="w-16 h-16 bg-success/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
                <p class="font-display text-lg font-bold text-success">Transaksi Selesai</p>
                <p class="font-body text-sm text-muted mt-2">Tiket Anda sudah siap.</p>
                <a href="{{ route('tickets.index') }}" class="inline-block mt-4 font-body text-sm text-accent hover:text-accent-hover">Lihat Tiket Saya →</a>
            </div>
        @endif

        {{-- Catatan --}}
        <p class="text-center mt-4 font-body text-[11px] leading-relaxed text-white/40">
            Posisimu akan tersimpan selama 15 menit jika browser ditutup.<br>Refresh halaman untuk memperbarui posisi antrean.
        </p>
    </div>
</div>

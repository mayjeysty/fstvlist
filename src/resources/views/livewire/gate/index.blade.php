<div class="space-y-6" x-data="{
    scannerOn: false,
    async toggleScanner() {
        const qrScanner = window.__qrScanner;
        if (!qrScanner) return;
        if (this.scannerOn) {
            await qrScanner.stopQrScanner();
            this.scannerOn = false;
        } else {
            await qrScanner.startQrScanner((token) => {
                document.getElementById('qr_token').value = token;
                $wire.set('qrToken', token);
                $wire.scan();
            });
            this.scannerOn = true;
        }
    }
}">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold">Validasi Tiket</h1>
            <p class="text-gray-400 text-sm mt-1">Scan QR Code atau masukkan kode tiket secara manual.</p>
        </div>
        <button type="button"
                x-on:click="toggleScanner"
                x-bind:class="scannerOn ? 'bg-red-600 hover:bg-red-500' : 'bg-indigo-600 hover:bg-indigo-500'"
                class="text-white font-semibold rounded-xl px-5 py-3 transition flex items-center gap-2 text-sm">
            <svg x-show="!scannerOn" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <svg x-show="scannerOn" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            <span x-text="scannerOn ? 'Stop Scanner' : 'Start Scanner'"></span>
        </button>
    </div>

    {{-- Scanner Viewport --}}
    <div id="qr-reader" class="w-full max-w-lg mx-auto rounded-2xl overflow-hidden border border-gray-700 bg-gray-900 min-h-[300px] flex items-center justify-center">
        <div id="scanner-status" class="hidden items-center gap-2 text-green-400 text-sm">
            <span class="w-2 h-2 bg-green-400 rounded-full animate-blink"></span>
            Scanner Aktif — Arahkan ke QR Code
        </div>
        <div id="scanner-error" class="hidden text-red-400 text-sm p-4 text-center">
            <p class="mb-1">Tidak dapat mengakses kamera.</p>
            <p class="text-gray-500 text-xs">Silakan masukkan kode tiket secara manual atau gunakan perangkat dengan kamera.</p>
        </div>
        <div id="scanner-placeholder" class="text-gray-600 text-sm text-center p-8">
            <svg class="w-12 h-12 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2.48a2.5 2.5 0 00-4.52 0H4m16 0h2m-8-8h.01M12 12h.01M8 12h.01M16 12h.01M12 16h.01M8 16h.01M16 16h.01"/>
            </svg>
            <p>Klik <span class="text-indigo-400 font-semibold">Start Scanner</span> untuk memulai kamera</p>
            <p class="mt-1 text-xs">atau masukkan kode tiket secara manual di bawah</p>
        </div>
    </div>

    {{-- Success Result --}}
    @if($result)
        <div class="bg-green-500/10 border border-green-500/40 rounded-2xl p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <div>
                    <p class="font-bold text-green-400 text-lg">{{ $result['message'] }}</p>
                    <p class="text-gray-400 text-xs">{{ now()->format('d/m/Y H:i:s') }}</p>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3 text-sm">
                <div class="bg-gray-800/60 rounded-lg p-3">
                    <p class="text-gray-400 text-xs mb-1">Nama</p>
                    <p class="font-semibold">{{ $result['user_name'] }}</p>
                </div>
                <div class="bg-gray-800/60 rounded-lg p-3">
                    <p class="text-gray-400 text-xs mb-1">Email</p>
                    <p class="font-semibold">{{ $result['user_email'] }}</p>
                </div>
                <div class="bg-gray-800/60 rounded-lg p-3">
                    <p class="text-gray-400 text-xs mb-1">Event</p>
                    <p class="font-semibold">{{ $result['event_name'] }}</p>
                </div>
                <div class="bg-gray-800/60 rounded-lg p-3">
                    <p class="text-gray-400 text-xs mb-1">Section</p>
                    <p class="font-semibold">{{ $result['section_name'] }}</p>
                </div>
                <div class="bg-gray-800/60 rounded-lg p-3 col-span-2">
                    <p class="text-gray-400 text-xs mb-1">Kode Tiket</p>
                    <p class="font-mono font-semibold">{{ $result['ticket_code'] }}</p>
                </div>
            </div>
        </div>
    @endif

    {{-- Error --}}
    @if($error)
        <div class="bg-red-500/10 border border-red-500/40 rounded-2xl p-6 flex items-center gap-4">
            <div class="w-10 h-10 bg-red-500 rounded-full flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </div>
            <div>
                <p class="font-bold text-red-400">Tiket Tidak Valid</p>
                <p class="text-gray-300 text-sm mt-1">{{ $error }}</p>
            </div>
        </div>
    @endif

    {{-- Scan Form --}}
    <div class="bg-gray-900 border border-gray-800 rounded-2xl p-6">
        <form wire:submit="scan" id="scanForm">
            <label class="block text-sm text-gray-300 mb-2">QR Token / Kode Tiket</label>
            <div class="flex gap-3">
                <input
                    type="text"
                    wire:model="qrToken"
                    id="qr_token"
                    autofocus
                    autocomplete="off"
                    placeholder="Scan QR atau ketik kode..."
                    class="flex-1 bg-gray-800 border border-gray-700 text-white rounded-xl px-4 py-3 font-mono
                           focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm"
                >
                <button type="submit"
                        wire:loading.attr="disabled"
                        class="bg-indigo-600 hover:bg-indigo-500 disabled:bg-gray-600 text-white font-semibold rounded-xl px-6 py-3 transition">
                    <span wire:loading.remove>Validasi</span>
                    <span wire:loading>...</span>
                </button>
            </div>
            <p class="text-gray-500 text-xs mt-2">
                Barcode scanner akan auto-submit. Atau tekan Enter setelah input.
            </p>
        </form>
    </div>

    <script>
        document.getElementById('qr_token')?.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                if (this.value.trim().length > 0) {
                    @this.scan();
                }
            }
        });
    </script>
</div>

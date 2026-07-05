<div
    x-data="{ open: @entangle($attributes->wire('model')) }"
    x-show="open"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-[999] flex items-center justify-center px-4"
    x-cloak
>
    {{-- Overlay --}}
    <div class="absolute inset-0 bg-ink/70 backdrop-blur-sm" @click="open = false"></div>

    {{-- Modal Box --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="relative bg-white rounded-[20px] shadow-2xl w-full max-w-[420px] p-8"
    >
        {{-- Close Button --}}
        <button @click="open = false" class="absolute top-5 right-5 text-muted hover:text-ink transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>

        {{ $slot }}
    </div>
</div>

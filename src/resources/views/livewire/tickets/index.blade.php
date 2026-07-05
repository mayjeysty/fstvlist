<div>
    <h1 class="text-xl font-bold mb-6">Tiket Saya</h1>

    <div class="space-y-4">
        @forelse($tickets as $order)
            <a href="{{ route('tickets.show', $order) }}"
               class="block bg-surface-1 border border-white/10 rounded-xl p-5 hover:border-accent/30 transition">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="font-semibold">{{ $order->event->name }}</h3>
                    <span class="text-xs bg-success/10 text-success border border-success/30 rounded-full px-3 py-0.5">Paid</span>
                </div>
                <div class="text-sm text-muted space-y-1">
                    <p>{{ $order->event->venue->name }}</p>
                    <p>{{ $order->event->start_time->format('d M Y, H:i') }}</p>
                    <p>{{ $order->qty }} tiket · Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                </div>
            </a>
        @empty
            <div class="text-center py-16 text-muted">
                <p class="text-lg mb-1">Belum ada tiket</p>
                <a href="{{ route('events.index') }}" class="text-accent hover:text-accent-hover text-sm">Lihat Event →</a>
            </div>
        @endforelse
    </div>

    @if($tickets->hasPages())
        <div class="mt-8">{{ $tickets->links() }}</div>
    @endif
</div>

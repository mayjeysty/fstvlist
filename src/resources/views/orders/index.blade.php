@extends('layouts.app')
@section('title', 'Pesanan Saya')

@section('content')
<h1 class="font-display text-xl font-semibold mb-6">Pesanan Saya</h1>

@forelse($orders as $order)
    @php
        $statusClasses = match($order->status) {
            'paid'            => 'bg-success/20 text-success border-success/30',
            'reserved'        => 'bg-accent/20 text-accent border-accent/30',
            'waiting_payment' => 'bg-accent/20 text-accent border-accent/30',
            'expired',
            'cancelled'       => 'bg-error/20 text-error border-error/30',
            default           => 'bg-muted/20 text-muted border-muted/30',
        };
    @endphp
    <div class="bg-surface-1 border border-white/10 rounded-xl p-4 mb-3 flex items-center justify-between">
        <div>
            <p class="font-semibold">{{ $order->event->name }}</p>
            <p class="text-muted text-sm">{{ $order->created_at->format('d M Y, H:i') }}</p>
            <span class="inline-block mt-1 text-xs px-2 py-0.5 rounded border {{ $statusClasses }}">
                {{ strtoupper($order->status) }}
            </span>
        </div>
        <div class="text-right">
            <p class="font-bold text-accent">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
            @if($order->status === 'paid')
                <a href="{{ route('tickets.show', $order) }}" class="text-xs text-accent hover:underline">Lihat Tiket</a>
            @elseif($order->status === 'reserved')
                <a href="{{ route('orders.checkout', $order) }}" class="text-xs text-accent hover:underline">Lanjutkan</a>
            @elseif($order->status === 'waiting_payment')
                <a href="{{ route('orders.payment', $order) }}" class="text-xs text-accent hover:underline">Bayar</a>
            @endif
        </div>
    </div>
@empty
    <div class="text-center text-muted py-16">Belum ada pesanan.</div>
@endforelse

{{ $orders->links() }}
@endsection

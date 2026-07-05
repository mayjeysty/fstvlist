<?php

use App\Models\User;
use App\Services\OrderService;
use App\Services\PaymentService;
use App\Services\TicketService;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

beforeEach(function () {
    $this->createRoles();
    $this->venue  = $this->createVenueWithSections();
    $this->event  = $this->createEvent($this->venue);
    $this->section = $this->venue->sections->first();
    $this->user   = User::factory()->create()->assignRole('customer');
});

// ─── Test 1: Open Ticketing ────────────────────────────────────────────────────

test('guest can view event listing', function () {
    get(route('events.index'))
        ->assertOk()
        ->assertSee($this->event->name);
});

test('guest can view event detail with sections and prices', function () {
    get(route('events.show', $this->event))
        ->assertOk()
        ->assertSee($this->event->name)
        ->assertSee($this->venue->name)
        ->assertSee('VIP')
        ->assertSee('Festival')
        ->assertSee('Tribune')
        ->assertSee('1.500.000'); // VIP price
});

test('inactive event is not shown in listing', function () {
    $this->event->update(['is_active' => false]);

    get(route('events.index'))
        ->assertOk()
        ->assertDontSee($this->event->name);
});

test('guest sees login button instead of buy button on event detail', function () {
    get(route('events.show', $this->event))
        ->assertOk()
        ->assertSee('Login untuk Beli Tiket');
});

test('customer sees buy button on event detail', function () {
    actingAs($this->user)
        ->get(route('events.show', $this->event))
        ->assertOk()
        ->assertSee('Beli Tiket');
});

// ─── Test 3: Booking Flow ──────────────────────────────────────────────────────

test('customer can reserve tickets and quota decreases', function () {
    $initialRemaining = $this->section->fresh()->remaining_capacity;
    $qty = 2;

    $order = app(OrderService::class)->reserve(
        $this->user->id,
        $this->event->id,
        $this->section->id,
        $qty
    );

    expect($order->status)->toBe('reserved');
    expect($order->qty)->toBe($qty);
    expect($order->total_price)->toBe(($this->section->price * $qty) + (int) ($this->section->price * $qty * 0.05));
    expect($order->booking_deadline)->not->toBeNull();

    // Quota must decrease
    expect($this->section->fresh()->remaining_capacity)->toBe($initialRemaining - $qty);
});

test('booking page shows zone selection for customer', function () {
    actingAs($this->user)
        ->get(route('orders.create', $this->event))
        ->assertOk()
        ->assertSee('Pilih Tiket')
        ->assertSee('VIP')
        ->assertSee('1.500.000');
});

test('cannot reserve more than 4 tickets', function () {
    app(OrderService::class)->reserve(
        $this->user->id,
        $this->event->id,
        $this->section->id,
        5
    );
})->throws(Exception::class, 'Jumlah tiket harus antara 1 dan 4');

test('cannot reserve zero tickets', function () {
    app(OrderService::class)->reserve(
        $this->user->id,
        $this->event->id,
        $this->section->id,
        0
    );
})->throws(Exception::class, 'Jumlah tiket harus antara 1 dan 4');

// ─── Test 4: Payment Flow ──────────────────────────────────────────────────────

test('customer can proceed to payment and pay', function () {
    $orderService = app(OrderService::class);
    $paymentService = app(PaymentService::class);
    $ticketService = app(TicketService::class);

    $order = $orderService->reserve(
        $this->user->id,
        $this->event->id,
        $this->section->id,
        2
    );

    // Proceed to payment
    $orderService->proceedToPayment($order);
    expect($order->fresh()->status)->toBe('waiting_payment');
    expect($order->fresh()->payment_deadline)->not->toBeNull();

    // Pay
    $paymentService->pay($order->fresh(), 'transfer');
    expect($order->fresh()->status)->toBe('paid');
    expect($order->fresh()->paid_at)->not->toBeNull();

    // Generate tickets
    $ticketService->generate($order->fresh(), [
        ['section_id' => $this->section->id, 'qty' => 2],
    ]);

    expect($order->fresh()->tickets)->toHaveCount(2);
    expect($this->section->fresh()->sold_count)->toBe(2);
});

test('each generated ticket has unique qr_token', function () {
    $orderService = app(OrderService::class);
    $ticketService = app(TicketService::class);

    $order = $orderService->reserve($this->user->id, $this->event->id, $this->section->id, 3);
    $order->update(['status' => 'paid', 'paid_at' => now()]);

    $ticketService->generate($order, [
        ['section_id' => $this->section->id, 'qty' => 3],
    ]);

    $tickets = $order->fresh()->tickets;
    $qrTokens = $tickets->pluck('qr_token')->unique();
    expect($qrTokens)->toHaveCount(3);
});

test('cannot pay if order is not in waiting_payment status', function () {
    $orderService = app(OrderService::class);
    $paymentService = app(PaymentService::class);

    $order = $orderService->reserve($this->user->id, $this->event->id, $this->section->id, 1);
    // Still "reserved", not "waiting_payment"
    $paymentService->pay($order, 'transfer');
})->throws(Exception::class, 'Order tidak dalam status menunggu pembayaran');

test('cannot pay if payment deadline has passed', function () {
    $orderService = app(OrderService::class);
    $paymentService = app(PaymentService::class);

    $order = $orderService->reserve($this->user->id, $this->event->id, $this->section->id, 1);
    $orderService->proceedToPayment($order);

    // Simulate time passing
    $order->update(['payment_deadline' => now()->subMinute()]);

    $paymentService->pay($order->fresh(), 'transfer');
})->throws(Exception::class, 'Waktu pembayaran telah habis');

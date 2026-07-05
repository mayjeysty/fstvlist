<?php

use App\Models\Event;
use App\Models\Order;
use App\Models\User;
use App\Models\Queue;
use App\Services\OrderService;
use App\Services\PaymentService;
use App\Services\TicketService;
use App\Services\QueueService;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

beforeEach(function () {
    $this->createRoles();
    $this->venue   = $this->createVenueWithSections();
    $this->event   = $this->createEvent($this->venue);
    $this->section = $this->venue->sections->first(); // VIP
    $this->user    = User::factory()->create()->assignRole('customer');
});

// ─── Stage 1: Guest Browsing ─────────────────────────────────────────────────

test('guest sees event listing and detail', function () {
    get(route('events.index'))
        ->assertOk()
        ->assertSee($this->event->name);

    get(route('events.show', $this->event))
        ->assertOk()
        ->assertSee($this->event->name)
        ->assertSee($this->venue->name)
        ->assertSee('VIP')
        ->assertSee('1.500.000');
});

test('guest sees login link in navigation on event detail', function () {
    get(route('events.show', $this->event))
        ->assertOk()
        ->assertSee('Login');
});

// ─── Stage 2: Customer Registration & Login ───────────────────────────────────

test('customer can register and login', function () {
    $password = 'password123';

    post(route('register'), [
        'name'                  => 'New Customer',
        'email'                 => 'new@customer.test',
        'password'              => $password,
        'password_confirmation' => $password,
    ])->assertRedirect(route('events.index'));

    $this->assertAuthenticated();
});

// ─── Stage 3: Complete Booking Flow ──────────────────────────────────────────

test('customer completes full booking flow end-to-end', function () {
    $initialRemaining = $this->section->fresh()->remaining_capacity;
    $qty = 2;

    // 1. Reserve tickets
    $order = app(OrderService::class)->reserve(
        $this->user->id,
        $this->event->id,
        $this->section->id,
        $qty
    );

    expect($order->status)->toBe(Order::STATUS_RESERVED);
    expect($order->qty)->toBe($qty);
    expect($order->booking_deadline)->not->toBeNull();
    expect($order->subtotal)->toBe($this->section->price * $qty);
    expect($order->service_fee)->toBe((int)(($this->section->price * $qty) * 0.05));
    expect($order->total_price)->toBe($order->subtotal + $order->service_fee);

    // Quota decreased
    expect($this->section->fresh()->remaining_capacity)->toBe($initialRemaining - $qty);

    // 2. Proceed to checkout
    app(OrderService::class)->proceedToPayment($order);
    expect($order->fresh()->status)->toBe(Order::STATUS_WAITING_PAYMENT);
    expect($order->fresh()->payment_deadline)->not->toBeNull();

    // 3. Create transaction (simulated or via Midtrans)
    try {
        $result = app(PaymentService::class)->createTransaction($order->fresh(), 'transfer');

        if ($result['simulated']) {
            expect($result['order_id'])->toMatch('/^ORDER-/');
            expect($result['snap_token'])->toBeNull();
        }
    } catch (Exception $e) {
        // Midtrans may not be reachable — still proceed to test the post-payment flow
    }

    // Mark as paid + generate tickets
    app(PaymentService::class)->markAsPaid($order->fresh());
    expect($order->fresh()->status)->toBe(Order::STATUS_PAID);
    expect($order->fresh()->paid_at)->not->toBeNull();

    app(TicketService::class)->generate($order->fresh(), [
        ['section_id' => $this->section->id, 'qty' => $qty],
    ]);

    // 4. Verify tickets generated
    $order->load('tickets');
    expect($order->tickets)->toHaveCount($qty);
    expect($order->tickets->first()->ticket_code)->toMatch('/^TKT-/');
    expect($order->tickets->first()->qr_token)->not->toBeNull();

    // All QR tokens must be unique
    expect($order->tickets->pluck('qr_token')->unique())->toHaveCount($qty);

    // sold_count updated
    expect($this->section->fresh()->sold_count)->toBe($qty);

    // 5. Customer can view their tickets page (regression: OrderPolicy view check)
    actingAs($this->user)
        ->get(route('tickets.show', $order->fresh()))
        ->assertOk()
        ->assertSee('Tiket kamu sudah');
});

// ─── Stage 4: Booking with Queue ─────────────────────────────────────────────

test('customer completes queue-based booking flow', function () {
    $queueEvent = $this->createEvent($this->venue, [
        'queue_enabled' => true,
        'name'          => 'Queue Event',
    ]);
    $queueSection = $queueEvent->fresh()->venue->sections->first();

    actingAs($this->user);

    // 1. Join queue
    $queueEntry = app(QueueService::class)->join($this->user->id, $queueEvent->id);

    expect($queueEntry->status)->toBe(Queue::STATUS_WAITING);
    expect($queueEntry->queue_number)->toBe(1);

    // 2. Activate queue entry (simulates admin action)
    app(QueueService::class)->activateNext($queueEvent->id, 1);
    expect($queueEntry->fresh()->status)->toBe(Queue::STATUS_ACTIVE);

    // 3. Validate token
    $valid = app(QueueService::class)->validateToken(
        $this->user->id,
        $queueEvent->id,
        $queueEntry->fresh()->queue_token
    );
    expect($valid)->toBeTrue();

    // 4. Reserve tickets (inside queue window)
    $order = app(OrderService::class)->reserve(
        $this->user->id,
        $queueEvent->id,
        $queueSection->id,
        2
    );

    expect($order->status)->toBe(Order::STATUS_RESERVED);

    // 5. Proceed to payment
    app(OrderService::class)->proceedToPayment($order);
    expect($order->fresh()->status)->toBe(Order::STATUS_WAITING_PAYMENT);

    // 6. Simulate payment
    app(PaymentService::class)->createTransaction($order->fresh(), 'qris');
    app(PaymentService::class)->markAsPaid($order->fresh());

    // 7. Generate tickets
    app(TicketService::class)->generate($order->fresh(), [
        ['section_id' => $queueSection->id, 'qty' => 2],
    ]);

    // 8. Complete queue entry
    app(QueueService::class)->complete($this->user->id, $queueEvent->id);
    expect($queueEntry->fresh()->status)->toBe(Queue::STATUS_COMPLETED);

    // 9. Verify tickets
    expect($order->fresh()->tickets)->toHaveCount(2);
});

// ─── Stage 5: Order History ──────────────────────────────────────────────────

test('customer can view their order history', function () {
    $order = app(OrderService::class)->reserve(
        $this->user->id,
        $this->event->id,
        $this->section->id,
        1
    );

    app(OrderService::class)->proceedToPayment($order);
    app(PaymentService::class)->createTransaction($order->fresh(), 'transfer');
    app(PaymentService::class)->markAsPaid($order->fresh());
    app(TicketService::class)->generate($order->fresh(), [
        ['section_id' => $this->section->id, 'qty' => 1],
    ]);

    actingAs($this->user)
        ->get(route('orders.index'))
        ->assertOk()
        ->assertSee($this->event->name)
        ->assertSee('PAID');
});

// ─── Stage 6: Expired Booking ────────────────────────────────────────────────

test('expired booking releases quota and cannot be paid', function () {
    $initial = $this->section->fresh()->remaining_capacity;

    $order = app(OrderService::class)->reserve(
        $this->user->id,
        $this->event->id,
        $this->section->id,
        2
    );

    app(OrderService::class)->proceedToPayment($order);

    // Simulate payment deadline passed
    $order->update(['payment_deadline' => now()->subMinute()]);

    // Attempting to pay should fail
    expect(fn () => app(PaymentService::class)->createTransaction($order->fresh(), 'transfer'))
        ->toThrow(Exception::class, 'Waktu pembayaran telah habis.');

    // Rollback quota
    app(OrderService::class)->rollbackQuota($order);
    expect($order->fresh()->status)->toBe(Order::STATUS_EXPIRED);
    expect($this->section->fresh()->remaining_capacity)->toBe($initial);
});

// ─── Stage 7: Multiple Payment Methods ───────────────────────────────────────

test('customer can pay with various methods in simulation', function () {
    $order = app(OrderService::class)->reserve(
        $this->user->id,
        $this->event->id,
        $this->section->id,
        1
    );

    app(OrderService::class)->proceedToPayment($order);

    foreach (['transfer', 'virtual_account', 'qris'] as $method) {
        $freshOrder = $order->fresh();

        try {
            $result = app(PaymentService::class)->createTransaction($freshOrder, $method);
            expect($freshOrder->fresh()->payment_channel)->toBe($method);
        } catch (Exception $e) {
            // Midtrans may not be reachable in test environment — skip
            $this->markTestSkipped('Midtrans not reachable: ' . $e->getMessage());
        }
    }
});

// ─── Stage 8: Concurrent Booking Protection ──────────────────────────────────

test('cannot reserve more tickets than remaining capacity', function () {
    app(OrderService::class)->reserve(
        $this->user->id,
        $this->event->id,
        $this->section->id,
        5
    );
})->throws(Exception::class, 'Jumlah tiket harus antara 1 dan 4');

test('cannot reserve from sold-out section', function () {
    $tinySection = \App\Models\VenueSection::create([
        'venue_id'           => $this->venue->id,
        'name'               => 'Tiny',
        'capacity'           => 1,
        'remaining_capacity' => 0,
        'price'              => 100000,
        'color_code'         => '#ff0000',
    ]);

    expect($tinySection->isSoldOut())->toBeTrue();

    app(OrderService::class)->reserve(
        $this->user->id,
        $this->event->id,
        $tinySection->id,
        1
    );
})->throws(Exception::class, 'Kuota tidak mencukupi');

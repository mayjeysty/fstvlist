<?php

use App\Models\Order;
use App\Models\User;
use App\Services\OrderService;
use App\Services\PaymentService;

beforeEach(function () {
    $this->createRoles();
    $this->venue   = $this->createVenueWithSections();
    $this->event   = $this->createEvent($this->venue);
    $this->section = $this->venue->sections->first();
    $this->user    = User::factory()->create()->assignRole('customer');
    $this->orderService = app(OrderService::class);
});

// ─── Test 5: Booking Timeout ───────────────────────────────────────────────────

test('expired booking rolls back quota without tickets generated', function () {
    $initial = $this->section->fresh()->remaining_capacity;

    $order = $this->orderService->reserve($this->user->id, $this->event->id, $this->section->id, 3);

    // Quota decreased
    expect($this->section->fresh()->remaining_capacity)->toBe($initial - 3);

    // Simulate booking deadline passed
    $order->update(['booking_deadline' => now()->subMinute()]);

    // Rollback
    $this->orderService->rollbackQuota($order);

    expect($this->section->fresh()->remaining_capacity)->toBe($initial);
    expect($order->fresh()->status)->toBe(Order::STATUS_EXPIRED);
});

// ─── Test 6: Payment Timeout ───────────────────────────────────────────────────

test('expired payment rolls back quota (no tickets yet)', function () {
    $initial = $this->section->fresh()->remaining_capacity;

    $order = $this->orderService->reserve($this->user->id, $this->event->id, $this->section->id, 2);
    $this->orderService->proceedToPayment($order);

    expect($this->section->fresh()->remaining_capacity)->toBe($initial - 2);

    // Simulate payment deadline passed
    $order->update(['payment_deadline' => now()->subMinute()]);

    // Rollback
    $this->orderService->rollbackQuota($order);

    expect($this->section->fresh()->remaining_capacity)->toBe($initial);
    expect($order->fresh()->status)->toBe(Order::STATUS_EXPIRED);
});

test('expired payment after tickets generated rolls back per ticket', function () {
    $initial = $this->section->fresh()->remaining_capacity;

    $order = $this->orderService->reserve($this->user->id, $this->event->id, $this->section->id, 2);
    $this->orderService->proceedToPayment($order);

    // Simulate payment and ticket generation
    $order->update(['status' => Order::STATUS_PAID, 'paid_at' => now()]);
    app(\App\Services\TicketService::class)->generate($order, [
        ['section_id' => $this->section->id, 'qty' => 2],
    ]);

    // sold_count should now be 2
    expect($this->section->fresh()->sold_count)->toBe(2);

    // Rollback — harus mengembalikan per tiket
    $order->update(['status' => Order::STATUS_WAITING_PAYMENT, 'payment_deadline' => now()->subMinute()]);
    $this->orderService->rollbackQuota($order);

    expect($this->section->fresh()->remaining_capacity)->toBe($initial);
    expect($order->fresh()->status)->toBe(Order::STATUS_EXPIRED);
});

// ─── ExpireBookingJob + ExpirePaymentJob ────────────────────────────────────────

test('ExpireBookingJob processes overdue reserved orders', function () {
    $initial = $this->section->fresh()->remaining_capacity;

    $order = $this->orderService->reserve($this->user->id, $this->event->id, $this->section->id, 3);
    $order->update(['booking_deadline' => now()->subMinute()]);

    // Job handle
    $job = new \App\Jobs\ExpireBookingJob();
    $job->handle($this->orderService);

    expect($this->section->fresh()->remaining_capacity)->toBe($initial);
    expect($order->fresh()->status)->toBe(Order::STATUS_EXPIRED);
});

test('ExpirePaymentJob processes overdue waiting_payment orders', function () {
    $initial = $this->section->fresh()->remaining_capacity;

    $order = $this->orderService->reserve($this->user->id, $this->event->id, $this->section->id, 3);
    $this->orderService->proceedToPayment($order);
    $order->update(['payment_deadline' => now()->subMinute()]);

    $job = new \App\Jobs\ExpirePaymentJob();
    $job->handle($this->orderService);

    expect($this->section->fresh()->remaining_capacity)->toBe($initial);
    expect($order->fresh()->status)->toBe(Order::STATUS_EXPIRED);
});

test('ProcessQueueJob processes queue events', function () {
    $queueEvent = $this->createEvent($this->venue, ['queue_enabled' => true]);
    $queueService = app(\App\Services\QueueService::class);

    // User joins queue
    $queueService->join($this->user->id, $queueEvent->id);

    // Add a stale active entry to expire
    \App\Models\Queue::create([
        'event_id'     => $queueEvent->id,
        'user_id'      => User::factory()->create()->assignRole('customer')->id,
        'queue_number' => 99,
        'queue_token'  => \Illuminate\Support\Str::uuid(),
        'status'       => \App\Models\Queue::STATUS_ACTIVE,
        'expires_at'   => now()->subMinute(),
    ]);

    $job = new \App\Jobs\ProcessQueueJob();
    $job->handle($queueService);

    // The stale one should be expired, waiting one should be active
    expect(\App\Models\Queue::where('status', \App\Models\Queue::STATUS_EXPIRED)->count())->toBe(1);
    expect(\App\Models\Queue::where('status', \App\Models\Queue::STATUS_ACTIVE)->count())->toBe(1);
});

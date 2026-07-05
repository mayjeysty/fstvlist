<?php

use App\Models\Queue;
use App\Models\User;
use App\Services\QueueService;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

beforeEach(function () {
    $this->createRoles();
    $this->venue  = $this->createVenueWithSections();
    $this->event  = $this->createEvent($this->venue, ['queue_enabled' => true]);
    $this->user   = User::factory()->create()->assignRole('customer');
    $this->queueService = app(QueueService::class);
});

// ─── Test 2: Queue Flow ────────────────────────────────────────────────────────

test('customer can join queue for queue-enabled event', function () {
    $entry = $this->queueService->join($this->user->id, $this->event->id);

    expect($entry->status)->toBe(Queue::STATUS_WAITING);
    expect($entry->queue_number)->toBe(1);
    expect($entry->queue_token)->not->toBeNull();
});

test('joining queue twice returns same entry', function () {
    $first  = $this->queueService->join($this->user->id, $this->event->id);
    $second = $this->queueService->join($this->user->id, $this->event->id);

    expect($second->id)->toBe($first->id);
    expect(Queue::count())->toBe(1);
});

test('queue numbers increment sequentially', function () {
    $user2 = User::factory()->create()->assignRole('customer');
    $user3 = User::factory()->create()->assignRole('customer');

    $e1 = $this->queueService->join($this->user->id, $this->event->id);
    $e2 = $this->queueService->join($user2->id, $this->event->id);
    $e3 = $this->queueService->join($user3->id, $this->event->id);

    expect($e1->queue_number)->toBe(1);
    expect($e2->queue_number)->toBe(2);
    expect($e3->queue_number)->toBe(3);
});

test('activateNext moves waiting users to active status', function () {
    $user2 = User::factory()->create()->assignRole('customer');
    $this->queueService->join($this->user->id, $this->event->id);
    $this->queueService->join($user2->id, $this->event->id);

    $count = $this->queueService->activateNext($this->event->id, 10);

    expect($count)->toBe(2);
    expect(Queue::where('status', Queue::STATUS_ACTIVE)->count())->toBe(2);
    expect(Queue::where('status', Queue::STATUS_WAITING)->count())->toBe(0);
});

test('activated queue entries have expiration set', function () {
    $this->queueService->join($this->user->id, $this->event->id);
    $this->queueService->activateNext($this->event->id, 1);

    $entry = Queue::first();
    expect($entry->expires_at)->not->toBeNull();
    expect($entry->isActive())->toBeTrue();
});

test('validateToken returns true for valid active token', function () {
    $entry = $this->queueService->join($this->user->id, $this->event->id);
    $this->queueService->activateNext($this->event->id, 1);

    $valid = $this->queueService->validateToken(
        $this->user->id,
        $this->event->id,
        $entry->fresh()->queue_token
    );

    expect($valid)->toBeTrue();
});

test('validateToken returns false for non-active entry', function () {
    $entry = $this->queueService->join($this->user->id, $this->event->id);
    // Not yet activated

    $valid = $this->queueService->validateToken(
        $this->user->id,
        $this->event->id,
        $entry->queue_token
    );

    expect($valid)->toBeFalse();
});

test('complete marks queue entry as completed', function () {
    $this->queueService->join($this->user->id, $this->event->id);
    $this->queueService->activateNext($this->event->id, 1);
    $this->queueService->complete($this->user->id, $this->event->id);

    expect(Queue::first()->status)->toBe(Queue::STATUS_COMPLETED);
});

test('expireStale moves expired active entries to expired status', function () {
    $this->queueService->join($this->user->id, $this->event->id);
    $this->queueService->activateNext($this->event->id, 1);

    // Simulate expiration
    Queue::first()->update(['expires_at' => now()->subMinute()]);

    $count = $this->queueService->expireStale($this->event->id);

    expect($count)->toBe(1);
    expect(Queue::first()->status)->toBe(Queue::STATUS_EXPIRED);
});

test('queue page accessible for queue-enabled event', function () {
    actingAs($this->user)
        ->get(route('queue.show', $this->event))
        ->assertOk()
        ->assertSee('Ruang Tunggu');
});

test('event without queue shows buy button instead of queue button', function () {
    $normalEvent = $this->createEvent($this->venue, ['queue_enabled' => false]);

    actingAs($this->user)
        ->get(route('events.show', $normalEvent))
        ->assertOk()
        ->assertSee('Beli Tiket')
        ->assertDontSee('Masuk Antrian');
});

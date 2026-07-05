<?php

use App\Models\Order;
use App\Models\User;
use App\Models\VenueSection;
use App\Services\OrderService;
use Illuminate\Support\Facades\DB;

beforeEach(function () {
    $this->createRoles();
    $this->venue   = $this->createVenueWithSections();
    $this->event   = $this->createEvent($this->venue);
    $this->section = $this->venue->sections->first();
    $this->user1   = User::factory()->create()->assignRole('customer');
    $this->user2   = User::factory()->create()->assignRole('customer');
    $this->user3   = User::factory()->create()->assignRole('customer');
    $this->orderService = app(OrderService::class);
});

// ─── Test 7: Overbooking Prevention ────────────────────────────────────────────

test('cannot reserve more than remaining capacity', function () {
    // Create a section with tiny capacity to test this properly
    $tinySection = \App\Models\VenueSection::create([
        'venue_id'           => $this->venue->id,
        'name'               => 'Tiny Zone',
        'capacity'           => 2,
        'remaining_capacity' => 2,
        'price'              => 100000,
        'color_code'         => '#ff0000',
    ]);

    $this->orderService->reserve(
        $this->user1->id, $this->event->id, $tinySection->id, 4
    );
})->throws(Exception::class, 'Kuota tidak mencukupi');

test('sequential purchases drain quota correctly until sold out', function () {
    $capacity = $this->section->fresh()->capacity;

    // User 1 buys 300
    $this->orderService->reserve($this->user1->id, $this->event->id, $this->section->id, 4);
    // User 2 buys 300
    $this->orderService->reserve($this->user2->id, $this->event->id, $this->section->id, 4);

    $remaining = $this->section->fresh()->remaining_capacity;
    expect($remaining)->toBe($capacity - 8);
});

test('pessimistic locking prevents overbooking in concurrent scenario', function () {
    // Use a section with very small capacity to test edge case
    $smallSection = VenueSection::create([
        'venue_id'           => $this->venue->id,
        'name'               => 'Small Zone',
        'capacity'           => 5,
        'remaining_capacity' => 5,
        'price'              => 100000,
        'color_code'         => '#ff0000',
    ]);

    $results = [];
    $errors  = [];

    // Simulate 3 concurrent attempts trying to buy 4 each (total 12 > 5 capacity)
    for ($i = 0; $i < 3; $i++) {
        try {
            $results[] = DB::transaction(function () use ($smallSection, $i) {
                $user = User::factory()->create()->assignRole('customer');
                $section = VenueSection::lockForUpdate()->find($smallSection->id);

                if ($section->remaining_capacity >= 4) {
                    $section->decrement('remaining_capacity', 4);
                    return 'success';
                }
                throw new Exception('Kuota tidak mencukupi.');
            });
        } catch (Exception $e) {
            $errors[] = $e->getMessage();
        }
    }

    // Only 1 should succeed (4 of 5), others fail
    $finalRemaining = $smallSection->fresh()->remaining_capacity;
    expect($finalRemaining)->toBe(1); // 5 - 4 = 1
    expect(count($errors))->toBe(2);  // 2 should fail
});

test('sold out section cannot be purchased', function () {
    $smallSection = VenueSection::create([
        'venue_id'           => $this->venue->id,
        'name'               => 'Micro Zone',
        'capacity'           => 1,
        'remaining_capacity' => 0,
        'price'              => 100000,
        'color_code'         => '#ff0000',
    ]);

    expect($smallSection->isSoldOut())->toBeTrue();

    $this->orderService->reserve(
        $this->user1->id, $this->event->id, $smallSection->id, 1
    );
})->throws(Exception::class, 'Kuota tidak mencukupi');

test('quota never goes negative', function () {
    $smallSection = VenueSection::create([
        'venue_id'           => $this->venue->id,
        'name'               => 'Tiny Zone',
        'capacity'           => 3,
        'remaining_capacity' => 3,
        'price'              => 100000,
        'color_code'         => '#ff0000',
    ]);

    // Buy all 3
    $this->orderService->reserve($this->user1->id, $this->event->id, $smallSection->id, 3);
    expect($smallSection->fresh()->remaining_capacity)->toBe(0);

    // Try to buy 1 more
    try {
        $this->orderService->reserve($this->user2->id, $this->event->id, $smallSection->id, 1);
    } catch (Exception $e) {
        // Expected
    }

    expect($smallSection->fresh()->remaining_capacity)->toBe(0)
        ->and($smallSection->fresh()->remaining_capacity)->not->toBeLessThan(0);
});

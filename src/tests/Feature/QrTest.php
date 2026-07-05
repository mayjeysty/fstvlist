<?php

use App\Models\User;
use App\Services\OrderService;
use App\Services\PaymentService;
use App\Services\TicketService;
use App\Services\QrValidationService;

beforeEach(function () {
    $this->createRoles();
    $this->venue    = $this->createVenueWithSections();
    $this->event    = $this->createEvent($this->venue);
    $this->section  = $this->venue->sections->first();
    $this->customer = User::factory()->create()->assignRole('customer');
    $this->validator = User::factory()->create()->assignRole('validator');
});

function createPaidTicket($customer, $event, $section): \App\Models\Ticket
{
    $orderService  = app(OrderService::class);
    $paymentService = app(PaymentService::class);
    $ticketService  = app(TicketService::class);

    $order = $orderService->reserve($customer->id, $event->id, $section->id, 1);
    $orderService->proceedToPayment($order);
    $order->update(['status' => 'paid', 'paid_at' => now()]);
    $ticketService->generate($order, [['section_id' => $section->id, 'qty' => 1]]);

    return $order->fresh()->tickets->first();
}

// ─── Test 8: QR Validation ─────────────────────────────────────────────────────

test('qr validation succeeds for valid paid ticket', function () {
    $ticket = createPaidTicket($this->customer, $this->event, $this->section);
    $qrService = app(QrValidationService::class);

    $result = $qrService->validate($ticket->qr_token, $this->validator->id);

    expect($result->checked_in_at)->not->toBeNull();
    expect($result->checked_in_by)->toBe($this->validator->id);
    expect($result->isCheckedIn())->toBeTrue();
});

test('qr validation fails for non-existent token', function () {
    $qrService = app(QrValidationService::class);

    $qrService->validate('non-existent-uuid-token', $this->validator->id);
})->throws(Exception::class, 'Tiket tidak ditemukan');

test('qr validation fails for unpaid ticket', function () {
    $orderService = app(OrderService::class);
    $ticketService = app(TicketService::class);

    // Reserve but don't pay
    $order = $orderService->reserve($this->customer->id, $this->event->id, $this->section->id, 1);
    $order->update(['status' => 'reserved']); // Still reserved, not paid
    $ticketService->generate($order, [['section_id' => $this->section->id, 'qty' => 1]]);

    $ticket = $order->fresh()->tickets->first();
    $qrService = app(QrValidationService::class);

    $qrService->validate($ticket->qr_token, $this->validator->id);
})->throws(Exception::class, 'Tiket belum dibayar');

test('duplicate qr scan is rejected', function () {
    $ticket = createPaidTicket($this->customer, $this->event, $this->section);
    $qrService = app(QrValidationService::class);

    // First scan — OK
    $qrService->validate($ticket->qr_token, $this->validator->id);

    // Second scan — must fail
    $qrService->validate($ticket->qr_token, $this->validator->id);
})->throws(Exception::class, 'Tiket sudah digunakan');

test('each ticket has unique uuid qr_token (non-incremental)', function () {
    $ticket1 = createPaidTicket($this->customer, $this->event, $this->section);

    $customer2 = User::factory()->create()->assignRole('customer');
    $ticket2 = createPaidTicket($customer2, $this->event, $this->section);

    // Tokens must be different
    expect($ticket1->qr_token)->not->toBe($ticket2->qr_token);

    // Tokens must be UUID format
    expect(\Illuminate\Support\Str::isUuid($ticket1->qr_token))->toBeTrue();
    expect(\Illuminate\Support\Str::isUuid($ticket2->qr_token))->toBeTrue();
});

test('gate validator page is accessible to validator user', function () {
    $this->actingAs($this->validator)
        ->get(route('gate.index'))
        ->assertOk()
        ->assertSee('Validasi Tiket');
});

test('gate validation endpoint works with valid qr token', function () {
    $ticket = createPaidTicket($this->customer, $this->event, $this->section);

    $this->actingAs($this->validator)
        ->post(route('gate.validate'), ['qr_token' => $ticket->qr_token])
        ->assertSessionHas('success');

    expect($ticket->fresh()->isCheckedIn())->toBeTrue();
});

test('gate page is forbidden for customer', function () {
    $this->actingAs($this->customer)
        ->get(route('gate.index'))
        ->assertForbidden();
});

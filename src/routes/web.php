<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GateValidatorController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\OrderController;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;

/* Livewire asset handling */
Livewire::setUpdateRoute(fn ($handle) => Route::post(config('app.asset_prefix') . '/livewire/update', $handle));
Livewire::setScriptRoute(fn ($handle) => Route::get(config('app.asset_prefix') . '/livewire/livewire.js', $handle));

// ─── Design System Preview ──────────────────────────────────────────────────
Route::get('/design-system', function () {
    return view('ds-preview');
})->name('design-system');

// ─── Diagnostic ───────────────────────────────────────────────────────────────
Route::get('/diagnostic/google', [\App\Http\Controllers\DiagnosticController::class, 'google'])->name('diagnostic.google');
Route::get('/test-google', fn () => view('test-google'))->name('test.google');

// ─── Home ────────────────────────────────────────────────────────────────────
Route::get('/', function () {
    $featuredEvents = \App\Models\Event::where('is_active', true)
        ->with(['venue', 'eventSections'])
        ->orderBy('start_time')
        ->limit(4)
        ->get();

    return view('home', compact('featuredEvents'));
})->name('home');

// ─── Auth (guest only) ───────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',   [AuthController::class, 'login'])->middleware('throttle:login');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register',[AuthController::class, 'register'])->middleware('throttle:login');

    Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirect'])->name('auth.google.redirect');
    Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('auth.google.callback');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ─── Midtrans Webhook ────────────────────────────────────────────────────────
Route::post('/payment/midtrans/notification', [\App\Http\Controllers\MidtransWebhookController::class, 'handle'])
    ->name('midtrans.notification')
    ->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class);

// ─── Public: Events (Livewire full-page components) ──────────────────────────
Route::get('/events',              \App\Livewire\Events\Index::class)->name('events.index');
Route::get('/events/{event}',      \App\Livewire\Events\Show::class)->name('events.show');
Route::get('/events/{event}/zones',\App\Livewire\Events\Zones::class)->name('events.zones');

// ─── Customer (harus login + role customer) ───────────────────────────────────
Route::middleware(['auth', 'customer', 'throttle:booking'])->group(function () {
    // Queue (Livewire)
    Route::get('/queue/{event}',   \App\Livewire\Queue\Show::class)->name('queue.show');

    // Order / Booking (Livewire full-page)
    Route::get('/events/{event}/book',         \App\Livewire\Orders\Create::class)->name('orders.create');
    Route::get('/orders/{order}/checkout',     \App\Livewire\Orders\Checkout::class)->name('orders.checkout');
    Route::get('/orders/{order}/payment',      \App\Livewire\Orders\Payment::class)->name('orders.payment');

    // Orders history
    Route::get('/orders',      [OrderController::class, 'index'])->name('orders.index');

    // Tiket (Livewire)
    Route::get('/my-tickets',           \App\Livewire\Tickets\Index::class)->name('tickets.index');
    Route::get('/my-tickets/{order}',   \App\Livewire\Tickets\Show::class)->name('tickets.show');
    Route::get('/my-tickets/{order}/download', function (\App\Models\Order $order) {
        abort_if($order->user_id !== auth()->id() || $order->status !== 'paid', 404);
        $order->load(['event.venue', 'tickets.section', 'user']);

        return Pdf::loadView('pdf.eticket', compact('order'))
            ->setPaper('a4', 'portrait')
            ->download('E-Ticket_' . $order->event->name . '.pdf');
    })->name('tickets.download');
});

// ─── Gate Validator ──────────────────────────────────────────────────────────
Route::middleware(['auth', 'validator'])->group(function () {
    Route::get('/gate',  \App\Livewire\Gate\Index::class)->name('gate.index');
    Route::post('/gate/validate', [GateValidatorController::class, 'validate'])->name('gate.validate');
});

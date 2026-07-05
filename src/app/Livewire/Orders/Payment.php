<?php

namespace App\Livewire\Orders;

use App\Mail\EticketMail;
use App\Models\Order;
use App\Services\PaymentService;
use App\Services\TicketService;
use App\Services\QueueService;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\On;
use Livewire\Component;

class Payment extends Component
{
    public Order $order;
    public string $method = 'transfer';
    public ?string $snapToken = null;
    public ?string $midtransOrderId = null;
    public bool $showSuccessModal = false;

    protected PaymentService $paymentService;
    protected TicketService $ticketService;
    protected QueueService $queueService;

    public function boot(
        PaymentService $paymentService,
        TicketService $ticketService,
        QueueService $queueService
    ): void {
        $this->paymentService = $paymentService;
        $this->ticketService  = $ticketService;
        $this->queueService   = $queueService;
    }

    public function mount(Order $order): void
    {
        abort_if($order->status !== Order::STATUS_WAITING_PAYMENT, 404);
        abort_if($order->user_id !== auth()->id(), 403);
        $this->order = $order->load(['event.venue', 'section']);
    }

    /**
     * Generate Snap token and open Midtrans popup via JS.
     * If Midtrans keys not configured, process as simulated payment.
     */
    public function initiatePayment(): void
    {
        $this->validate(['method' => 'required|in:transfer,virtual_account,qris']);

        try {
            $result = $this->paymentService->createTransaction($this->order, $this->method);
            $this->midtransOrderId = $result['order_id'];

            if (! empty($result['simulated'])) {
                $this->processSuccess();
                return;
            }

            $this->snapToken = $result['snap_token'];
            $token = $this->snapToken;

            // Direct JS injection — bypasses Alpine event timing issues
            $this->js(<<<JS
                window.snap.pay('{$token}', {
                    onSuccess: function(result) {
                        \$wire.call('handlePaymentSuccess', JSON.stringify(result));
                    },
                    onPending: function() {
                        \$wire.call('handlePaymentPending');
                    },
                    onError: function() {
                        \$wire.call('handlePaymentError');
                    },
                    onClose: function() {
                        \$wire.call('handlePaymentError');
                    }
                });
            JS);
        } catch (\Exception $e) {
            $this->addError('method', $e->getMessage());
        }
    }

    /**
     * Called from Snap callback or simulated flow after payment success.
     */
    public function handlePaymentSuccess(string $resultJson = ''): void
    {
        if (! empty($resultJson)) {
            $result = json_decode($resultJson, true);
            $transactionId = $result['transaction_id'] ?? null;
            $this->order->update(['midtrans_transaction_id' => $transactionId]);
        }

        $this->processSuccess();
    }

    /**
     * Mark paid, generate tickets, send email.
     */
    protected function processSuccess(?string $orderId = null): void
    {
        $this->paymentService->markAsPaid($this->order);

        $this->ticketService->generate($this->order, [
            ['section_id' => $this->order->section_id, 'qty' => $this->order->qty],
        ]);

        Mail::to($this->order->user->email)->queue(new EticketMail($this->order));

        $this->order->tickets()->update(['email_sent_at' => now()]);

        if ($this->order->event->queue_enabled) {
            $this->queueService->complete(auth()->id(), $this->order->event_id);
        }

        $this->showSuccessModal = true;
    }

    /**
     * Redirect after success popup auto-dismiss.
     */
    public function redirectAfterPayment(): void
    {
        $this->redirect(route('tickets.show', $this->order));
    }

    #[On('redirect-after-payment')]
    public function handleRedirectAfterPayment(): void
    {
        $this->redirect(route('tickets.show', $this->order));
    }

    /**
     * Called when Snap popup closes or payment fails.
     */
    public function handlePaymentError(): void
    {
        session()->flash('error', 'Pembayaran dibatalkan atau gagal. Silakan coba lagi.');
        $this->snapToken       = null;
        $this->midtransOrderId = null;
    }

    /**
     * Called when payment is pending (e.g. bank transfer waiting for confirmation).
     */
    public function handlePaymentPending(): void
    {
        session()->flash('info', 'Pembayaran menunggu konfirmasi. Silakan selesaikan pembayaran Anda.');
        $this->snapToken = null;
    }

    public function render()
    {
        return view('livewire.orders.payment')
            ->layout('layouts.booking', ['title' => 'Pembayaran — ' . $this->order->event->name]);
    }
}

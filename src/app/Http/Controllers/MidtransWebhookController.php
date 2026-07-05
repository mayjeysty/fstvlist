<?php

namespace App\Http\Controllers;

use App\Mail\EticketMail;
use App\Models\Order;
use App\Services\PaymentService;
use App\Services\TicketService;
use App\Services\QueueService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MidtransWebhookController extends Controller
{
    protected PaymentService $paymentService;
    protected TicketService $ticketService;
    protected QueueService $queueService;

    public function __construct(
        PaymentService $paymentService,
        TicketService $ticketService,
        QueueService $queueService
    ) {
        $this->paymentService = $paymentService;
        $this->ticketService  = $ticketService;
        $this->queueService   = $queueService;
    }

    /**
     * Handle Midtrans payment notification webhook.
     */
    public function handle(Request $request): string
    {
        $payload = $request->all();

        Log::info('Midtrans Webhook received', $payload);

        $orderId       = $payload['order_id'] ?? null;
        $transactionId = $payload['transaction_id'] ?? null;
        $status        = $payload['transaction_status'] ?? null;
        $fraudStatus   = $payload['fraud_status'] ?? null;

        $order = Order::with(['event', 'user'])->where('midtrans_order_id', $orderId)->first();

        if (! $order) {
            Log::warning('Midtrans webhook: order not found', ['order_id' => $orderId]);
            return 'OK';
        }

        if ($order->status === Order::STATUS_PAID) {
            return 'OK';
        }

        $order->update(['midtrans_transaction_id' => $transactionId]);

        if (in_array($status, ['capture', 'settlement'])) {
            if ($fraudStatus === 'accept' || $fraudStatus === null) {
                $this->paymentService->markAsPaid($order);

                // Generate tickets
                $this->ticketService->generate($order, [
                    ['section_id' => $order->section_id, 'qty' => $order->qty],
                ]);

                // Send e-ticket email
                Mail::to($order->user->email)->queue(new EticketMail($order));
                $order->tickets()->update(['email_sent_at' => now()]);

                // Complete queue if applicable
                if ($order->event->queue_enabled) {
                    $this->queueService->complete($order->user_id, $order->event_id);
                }
            }
        } elseif (in_array($status, ['cancel', 'deny', 'expire', 'failure'])) {
            $this->paymentService->markAsFailed($order);
        }

        return 'OK';
    }
}

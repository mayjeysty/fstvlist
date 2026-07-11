<?php

namespace App\Http\Controllers;

use App\Actions\ProcessPaymentSuccess;
use App\Events\OrderPaid;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MidtransWebhookController extends Controller
{
    public function __construct(
        protected PaymentService $paymentService,
        protected ProcessPaymentSuccess $processPaymentSuccess,
    ) {}

    public function handle(Request $request): string
    {
        $payload = $request->all();

        Log::info('Midtrans Webhook received', [
            'order_id'          => $payload['order_id'] ?? null,
            'transaction_status'=> $payload['transaction_status'] ?? null,
        ]);

        if (! $this->paymentService->verifyWebhookSignature($payload)) {
            Log::warning('Midtrans webhook: invalid signature', [
                'order_id' => $payload['order_id'] ?? null,
            ]);
            return 'OK';
        }

        $orderId = $payload['order_id'] ?? null;
        $order   = \App\Models\Order::where('midtrans_order_id', $orderId)->first();

        if (! $order) {
            Log::warning('Midtrans webhook: order not found', ['order_id' => $orderId]);
            return 'OK';
        }

        if ($order->status === \App\Models\Order::STATUS_PAID) {
            Log::info('Midtrans webhook: order already paid', ['order_id' => $order->id]);
            return 'OK';
        }

        $paidOrder = $this->paymentService->handleNotification($payload);

        if ($paidOrder) {
            OrderPaid::dispatch($paidOrder);
        }

        return 'OK';
    }
}

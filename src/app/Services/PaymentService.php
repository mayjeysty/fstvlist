<?php

namespace App\Services;

use App\Models\Order;
use Exception;
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    public function __construct()
    {
        Config::$serverKey    = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized  = config('midtrans.is_sanitized');
        Config::$is3ds        = config('midtrans.is_3ds');
    }

    public function createTransaction(Order $order): array
    {
        if ($order->status !== Order::STATUS_PENDING) {
            throw new Exception('Order tidak dalam status pending.');
        }

        if ($order->payment_deadline && now()->isAfter($order->payment_deadline)) {
            $order->update(['status' => Order::STATUS_EXPIRED]);
            throw new Exception('Waktu pembayaran telah habis.');
        }

        if ($this->isSimulated()) {
            return $this->simulateTransaction($order);
        }

        $orderId     = config('ticketing.midtrans_order_prefix') . $order->id . '-' . time();
        $grossAmount = (int) $order->total_price;

        $params = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => $grossAmount,
            ],
            'customer_details' => [
                'first_name' => $order->user->name,
                'email'      => $order->user->email,
            ],
            'item_details' => [
                [
                    'id'       => 'TICKET-' . $order->section_id,
                    'price'    => (int) ($order->subtotal / max($order->qty, 1)),
                    'quantity' => $order->qty,
                    'name'     => 'Tiket ' . ($order->section?->name ?? 'Event') . ' — ' . $order->event->name,
                ],
                [
                    'id'       => 'FEE',
                    'price'    => (int) ($order->total_price - $order->subtotal),
                    'quantity' => 1,
                    'name'     => 'Biaya Layanan',
                ],
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);

            $order->update([
                'midtrans_order_id' => $orderId,
                'snap_token'        => $snapToken,
                'gross_amount'      => $grossAmount,
            ]);

            return [
                'snap_token' => $snapToken,
                'order_id'   => $orderId,
                'simulated'  => false,
            ];
        } catch (Exception $e) {
            Log::error('Midtrans Snap Error: ' . $e->getMessage(), [
                'order_id' => $order->id,
            ]);
            throw new Exception('Gagal membuat transaksi pembayaran. Silakan coba lagi.');
        }
    }

    public function verifyWebhookSignature(array $payload): bool
    {
        $serverKey    = config('midtrans.server_key');
        $orderId      = $payload['order_id'] ?? '';
        $statusCode   = $payload['status_code'] ?? '';
        $grossAmount  = $payload['gross_amount'] ?? '';
        $signatureKey = $payload['signature_key'] ?? '';

        $computed = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        return hash_equals($signatureKey, $computed);
    }

    public function handleNotification(array $payload): ?Order
    {
        $transactionId     = $payload['transaction_id'] ?? null;
        $orderId           = $payload['order_id'] ?? null;
        $transactionStatus = $payload['transaction_status'] ?? null;
        $fraudStatus       = $payload['fraud_status'] ?? null;
        $paymentType       = $payload['payment_type'] ?? null;
        $grossAmount       = $payload['gross_amount'] ?? null;
        $settlementTime    = $payload['settlement_time'] ?? null;

        Log::info('Midtrans Notification', compact('transactionStatus', 'orderId', 'transactionId'));

        $order = Order::where('midtrans_order_id', $orderId)->first();

        if (! $order) {
            Log::warning('Order not found for Midtrans notification', ['order_id' => $orderId]);
            return null;
        }

        if ($order->status === Order::STATUS_PAID) {
            Log::info('Order already paid, skipping', ['order_id' => $order->id]);
            return null;
        }

        $order->update([
            'midtrans_transaction_id' => $transactionId,
            'transaction_status'      => $transactionStatus,
            'payment_type'            => $paymentType,
            'gross_amount'            => $grossAmount,
            'settlement_time'         => $settlementTime ? now()->parse($settlementTime) : null,
            'fraud_status'            => $fraudStatus,
        ]);

        if (in_array($transactionStatus, ['capture', 'settlement'])) {
            if ($fraudStatus === 'accept' || $fraudStatus === null) {
                return $this->markAsPaid($order);
            }
        } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire', 'failure'])) {
            $this->markAsFailed($order);
        }

        return null;
    }

    public function markAsPaid(Order $order): ?Order
    {
        if ($order->status === Order::STATUS_PAID) {
            return null;
        }

        $order->update([
            'status'  => Order::STATUS_PAID,
            'paid_at' => now(),
        ]);

        return $order->fresh();
    }

    public function markAsFailed(Order $order): void
    {
        if ($order->status === Order::STATUS_PAID) {
            return;
        }

        $order->update([
            'status'     => Order::STATUS_EXPIRED,
            'expired_at' => now(),
        ]);

        app(OrderService::class)->rollbackQuota($order);
    }

    public function isSimulated(): bool
    {
        $serverKey = config('midtrans.server_key');

        return empty($serverKey) || str_contains($serverKey, 'xxxx');
    }

    private function simulateTransaction(Order $order): array
    {
        $orderId = config('ticketing.midtrans_order_prefix') . $order->id . '-' . time();
        $grossAmount = (int) $order->total_price;

        $order->update([
            'midtrans_order_id' => $orderId,
            'gross_amount'      => $grossAmount,
        ]);

        return [
            'snap_token' => null,
            'order_id'   => $orderId,
            'simulated'  => true,
        ];
    }
}

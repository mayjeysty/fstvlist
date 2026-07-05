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

    /**
     * Create Midtrans Snap transaction and return snap token.
     * Falls back to simulation if Midtrans keys are not configured.
     */
    public function createTransaction(Order $order, string $method): array
    {
        if ($order->status !== Order::STATUS_WAITING_PAYMENT) {
            throw new Exception('Order tidak dalam status menunggu pembayaran.');
        }

        if ($order->payment_deadline && now()->isAfter($order->payment_deadline)) {
            throw new Exception('Waktu pembayaran telah habis.');
        }

        // Fallback to simulation if Midtrans keys not configured
        if ($this->isSimulated()) {
            return $this->simulateTransaction($order, $method);
        }

        $orderId = config('ticketing.midtrans_order_prefix') . $order->id . '-' . time();
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
                    'price'    => (int) ($order->subtotal / $order->qty),
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
                'payment_channel'   => $method,
                'snap_token'        => $snapToken,
            ]);

            return [
                'snap_token' => $snapToken,
                'order_id'   => $orderId,
                'simulated'  => false,
            ];
        } catch (Exception $e) {
            Log::error('Midtrans Snap Error: ' . $e->getMessage(), [
                'order_id' => $order->id,
                'method'   => $method,
            ]);
            throw new Exception('Gagal membuat transaksi pembayaran. Silakan coba lagi.');
        }
    }

    /**
     * Check if Midtrans keys are placeholder (use simulated payment).
     */
    protected function isSimulated(): bool
    {
        $serverKey = config('midtrans.server_key');

        return empty($serverKey) || str_contains($serverKey, 'xxxx');
    }

    /**
     * Simulate payment (always succeeds, for development).
     */
    protected function simulateTransaction(Order $order, string $method): array
    {
        $orderId = config('ticketing.midtrans_order_prefix') . $order->id . '-' . time();

        $order->update([
            'midtrans_order_id' => $orderId,
            'payment_channel'   => $method,
            'payment_type'      => $method,
        ]);

        return [
            'snap_token' => null,
            'order_id'   => $orderId,
            'simulated'  => true,
        ];
    }

    /**
     * Pay an order that is in waiting_payment status.
     * Validates the order status and payment deadline, then marks as paid.
     */
    public function pay(Order $order, string $method): void
    {
        if ($order->status !== Order::STATUS_WAITING_PAYMENT) {
            throw new Exception('Order tidak dalam status menunggu pembayaran');
        }

        if ($order->payment_deadline && now()->isAfter($order->payment_deadline)) {
            throw new Exception('Waktu pembayaran telah habis');
        }

        $this->markAsPaid($order);
    }

    /**
     * Handle Midtrans payment notification (webhook).
     */
    public function handleNotification(array $payload): void
    {
        $transactionStatus = $payload['transaction_status'] ?? null;
        $orderId           = $payload['order_id'] ?? null;
        $transactionId     = $payload['transaction_id'] ?? null;
        $fraudStatus       = $payload['fraud_status'] ?? null;

        Log::info('Midtrans Notification', compact('transactionStatus', 'orderId', 'transactionId'));

        $order = Order::where('midtrans_order_id', $orderId)->first();

        if (! $order) {
            Log::warning('Order not found for Midtrans notification', ['order_id' => $orderId]);
            return;
        }

        // Already paid — skip
        if ($order->status === Order::STATUS_PAID) {
            return;
        }

        $order->update(['midtrans_transaction_id' => $transactionId]);

        if ($transactionStatus === 'capture' || $transactionStatus === 'settlement') {
            if ($fraudStatus === 'accept') {
                $this->markAsPaid($order);
            }
        } elseif ($transactionStatus === 'pending') {
            // Still waiting
        } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire', 'failure'])) {
            $this->markAsFailed($order);
        }
    }

    /**
     * Mark order as paid and generate tickets.
     */
    public function markAsPaid(Order $order): void
    {
        if ($order->status === Order::STATUS_PAID) {
            return;
        }

        $order->update([
            'status'  => Order::STATUS_PAID,
            'paid_at' => now(),
        ]);
    }

    /**
     * Mark order as failed/expired.
     */
    public function markAsFailed(Order $order): void
    {
        if ($order->status !== Order::STATUS_WAITING_PAYMENT) {
            return;
        }

        $order->update(['status' => Order::STATUS_EXPIRED]);

        // Rollback quota
        app(OrderService::class)->rollbackQuota($order);
    }
}

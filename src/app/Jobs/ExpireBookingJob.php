<?php

namespace App\Jobs;

use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ExpireBookingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(OrderService $orderService): void
    {
        Order::where('status', Order::STATUS_PENDING)
            ->where('payment_deadline', '<', now())
            ->with('tickets')
            ->get()
            ->each(function (Order $order) use ($orderService) {
                $orderService->rollbackQuota($order);
            });
    }
}

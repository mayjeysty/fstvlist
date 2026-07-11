<?php

namespace App\Listeners;

use App\Actions\ProcessPaymentSuccess;
use App\Events\OrderPaid;
use App\Services\QueueService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class HandleOrderPaid implements ShouldQueue
{
    public function __construct(
        protected ProcessPaymentSuccess $processPaymentSuccess,
        protected QueueService $queueService,
    ) {}

    public function handle(OrderPaid $event): void
    {
        Log::info('HandleOrderPaid listener triggered', [
            'order_id' => $event->order->id,
        ]);

        $this->processPaymentSuccess->handle($event->order);

        if ($event->order->event->queue_enabled) {
            $this->queueService->complete(
                $event->order->user_id,
                $event->order->event_id
            );
        }
    }
}

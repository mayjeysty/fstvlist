<?php

namespace App\Jobs;

use App\Models\Event;
use App\Services\QueueService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessQueueJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(QueueService $queueService): void
    {
        Event::where('is_active', true)
            ->where('queue_enabled', true)
            ->get()
            ->each(function (Event $event) use ($queueService) {
                // Expire stale active entries first
                $queueService->expireStale($event->id);

                // Activate next batch of waiting users
                $queueService->activateNext($event->id, 10);
            });
    }
}

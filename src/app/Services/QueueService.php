<?php

namespace App\Services;

use App\Models\Event;
use App\Models\Queue;
use Exception;
use Illuminate\Support\Str;

class QueueService
{
    /**
     * Join the queue for an event. Returns existing entry if already queued.
     */
    public function join(int $userId, int $eventId): Queue
    {
        $existing = Queue::where('event_id', $eventId)
            ->where('user_id', $userId)
            ->whereIn('status', [Queue::STATUS_WAITING, Queue::STATUS_ACTIVE])
            ->first();

        if ($existing) {
            return $existing;
        }

        $nextNumber = (Queue::where('event_id', $eventId)->max('queue_number') ?? 0) + 1;

        return Queue::create([
            'event_id'     => $eventId,
            'user_id'      => $userId,
            'queue_number' => $nextNumber,
            'queue_token'  => Str::uuid(),
            'status'       => Queue::STATUS_WAITING,
        ]);
    }

    /**
     * Activate the next N waiting entries in the queue.
     */
    public function activateNext(int $eventId, int $count = 10): int
    {
        $activated = 0;

        Queue::where('event_id', $eventId)
            ->where('status', Queue::STATUS_WAITING)
            ->orderBy('queue_number')
            ->limit($count)
            ->get()
            ->each(function (Queue $queue) use (&$activated) {
                $queue->update([
                    'status'     => Queue::STATUS_ACTIVE,
                    'expires_at' => now()->addMinutes(15),
                ]);
                $activated++;
            });

        return $activated;
    }

    /**
     * Validate that a user has a valid active queue token for an event.
     */
    public function validateToken(int $userId, int $eventId, string $token): bool
    {
        return Queue::where('event_id', $eventId)
            ->where('user_id', $userId)
            ->where('queue_token', $token)
            ->where('status', Queue::STATUS_ACTIVE)
            ->where('expires_at', '>', now())
            ->exists();
    }

    /**
     * Mark queue entry as completed after user finishes booking.
     */
    public function complete(int $userId, int $eventId): void
    {
        Queue::where('event_id', $eventId)
            ->where('user_id', $userId)
            ->where('status', Queue::STATUS_ACTIVE)
            ->update(['status' => Queue::STATUS_COMPLETED]);
    }

    /**
     * Expire queue entries whose time has passed.
     */
    public function expireStale(int $eventId): int
    {
        return Queue::where('event_id', $eventId)
            ->where('status', Queue::STATUS_ACTIVE)
            ->where('expires_at', '<', now())
            ->update(['status' => Queue::STATUS_EXPIRED]);
    }
}

<?php

namespace App\Livewire\Queue;

use App\Models\Event;
use App\Models\Queue;
use App\Services\QueueService;
use Livewire\Component;

class Show extends Component
{
    public Event $event;
    public ?Queue $queueEntry = null;
    public string $queueToken = '';

    protected QueueService $queueService;

    public function boot(QueueService $queueService): void
    {
        $this->queueService = $queueService;
    }

    public function mount(Event $event): void
    {
        abort_if(! $event->queue_enabled, 404);
        $this->event = $event;

        $user = auth()->user();

        // Join queue
        $this->queueEntry = $this->queueService->join($user->id, $event->id);
        $this->queueToken = $this->queueEntry->queue_token;

        session(['queue_token_' . $event->id => $this->queueToken]);

        // Auto-redirect if already active
        if ($this->queueEntry->isActive()) {
            $this->redirect(route('orders.create', $event));
        }
    }

    public function refreshStatus(): void
    {
        $this->queueEntry = $this->queueEntry->fresh();

        if ($this->queueEntry->isActive()) {
            $this->redirect(route('orders.create', $this->event));
        }

        if ($this->queueEntry->status === Queue::STATUS_EXPIRED) {
            session()->forget('queue_token_' . $this->event->id);
        }
    }

    public function render()
    {
        $waitingAhead = Queue::where('event_id', $this->event->id)
            ->where('status', Queue::STATUS_WAITING)
            ->where('queue_number', '<', $this->queueEntry?->queue_number)
            ->count();

        $totalWaiting = Queue::where('event_id', $this->event->id)
            ->where('status', Queue::STATUS_WAITING)
            ->count();

        return view('livewire.queue.show', [
            'waitingAhead' => $waitingAhead,
            'totalWaiting' => $totalWaiting,
        ])->layout('layouts.app', ['title' => 'Antrian — ' . $this->event->name]);
    }
}

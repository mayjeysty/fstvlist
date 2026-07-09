<?php

namespace App\Livewire\Events;

use App\Models\Event;
use Livewire\Component;

class Show extends Component
{
    public Event $event;

    public function mount(Event $event): void
    {
        $this->event = $event;
    }

    private function computeMinPrice(): int
    {
        $eventSections = $this->event->eventSections;
        if ($eventSections->isNotEmpty()) {
            return (int) $eventSections->min('price');
        }

        $this->event->loadMissing('venue.sections');
        return (int) $this->event->venue->sections->min('price');
    }

    public function render()
    {
        $this->event->loadMissing('venue.sections', 'eventSections');

        return view('livewire.events.show', [
            'event'    => $this->event,
            'minPrice' => $this->computeMinPrice(),
            'city'     => $this->event->venue->city,
        ])->layout('layouts.app', ['title' => $this->event->name]);
    }
}

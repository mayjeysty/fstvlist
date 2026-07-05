<?php

namespace App\Livewire\Events;

use App\Models\Event;
use Livewire\Component;

class Show extends Component
{
    public Event $event;
    public ?int $selectedZoneId = null;

    public function mount(Event $event): void
    {
        $this->event = $event;
    }

    public function selectZone(int $sectionId): void
    {
        $this->selectedZoneId = $this->selectedZoneId === $sectionId ? null : $sectionId;
    }

    public function render()
    {
        $this->event->load('venue.sections', 'eventSections.venueSection');

        $eventSections = $this->event->eventSections;
        $useEventSections = $eventSections->isNotEmpty();
        $source = $useEventSections ? $eventSections : $this->event->venue->sections;

        $zoneData = [];
        foreach ($source as $item) {
            if ($useEventSections) {
                $vs = $item->venueSection;
                $zoneData[] = [
                    'id'          => $vs->id,
                    'name'        => $vs->name,
                    'color'       => $vs->color_code,
                    'position_x'  => $vs->position_x,
                    'position_y'  => $vs->position_y,
                    'quota'       => $item->quota,
                    'price'       => $item->price,
                    'remaining'   => $item->remaining_quota,
                    'soldOut'     => $item->isSoldOut(),
                ];
            } else {
                $zoneData[] = [
                    'id'          => $item->id,
                    'name'        => $item->name,
                    'color'       => $item->color_code,
                    'position_x'  => $item->position_x,
                    'position_y'  => $item->position_y,
                    'quota'       => $item->capacity,
                    'price'       => $item->price,
                    'remaining'   => $item->remaining_capacity,
                    'soldOut'     => $item->isSoldOut(),
                ];
            }
        }

        $selectedZone = null;
        if ($this->selectedZoneId) {
            $selectedZone = collect($zoneData)->firstWhere('id', $this->selectedZoneId);
        }

        return view('livewire.events.show', [
            'event'        => $this->event,
            'zoneData'     => $zoneData,
            'selectedZone' => $selectedZone,
            'city'         => $this->event->venue->city,
        ])->layout('layouts.app', ['title' => $this->event->name]);
    }
}

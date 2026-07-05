<?php

namespace App\Livewire\Events;

use App\Models\Event;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $city = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingCity(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $events = Event::query()
            ->where('is_active', true)
            ->when($this->search, fn ($q) =>
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhereHas('venue', fn ($v) => $v->where('name', 'like', '%' . $this->search . '%'))
            )
            ->when($this->city, fn ($q) =>
                $q->whereHas('venue', fn ($v) => $v->where('city', $this->city))
            )
            ->with(['venue', 'eventSections'])
            ->orderBy('start_time')
            ->paginate(12);

        $cities = \App\Models\Venue::whereNotNull('city')
            ->where('city', '!=', '')
            ->distinct()
            ->orderBy('city')
            ->pluck('city');

        return view('livewire.events.index', compact('events', 'cities'))
            ->layout('layouts.app', ['title' => 'Daftar Acara — FSTVLIST']);
    }
}

<?php

namespace App\Livewire\Tickets;

use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public function render()
    {
        $tickets = auth()->user()->orders()
            ->where('status', 'paid')
            ->with(['event', 'tickets.section'])
            ->latest()
            ->paginate(10);

        return view('livewire.tickets.index', compact('tickets'))
            ->layout('layouts.app', ['title' => 'Tiket Saya']);
    }
}

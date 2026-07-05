<?php

namespace App\Livewire\Tickets;

use App\Models\Order;
use Livewire\Component;

class Show extends Component
{
    public Order $order;
    public int $activeTicketIndex = 0;

    public function mount(Order $order): void
    {
        $this->authorize('view', $order);
        abort_if($order->status !== 'paid', 404);
        $this->order = $order->load(['event.venue', 'tickets.section']);
    }

    public function switchToTicket(int $index): void
    {
        $this->activeTicketIndex = $index;
    }

    public function download()
    {
        return redirect()->route('tickets.download', $this->order);
    }

    public function render()
    {
        return view('livewire.tickets.show')
            ->layout('layouts.app', ['title' => 'E-Ticket — ' . $this->order->event->name]);
    }
}

<?php

namespace App\Livewire\Orders;

use App\Models\Order;
use App\Services\OrderService;
use Livewire\Component;

class Checkout extends Component
{
    public Order $order;
    public bool $showConfirmModal = false;
    public bool $showCancelModal  = false;

    protected OrderService $orderService;

    public function boot(OrderService $orderService): void
    {
        $this->orderService = $orderService;
    }

    public function mount(Order $order): void
    {
        abort_if($order->status !== Order::STATUS_RESERVED, 404);
        abort_if($order->user_id !== auth()->id(), 403);
        $this->order = $order->load(['event.venue', 'section', 'user']);
    }

    public function proceedToPayment(): void
    {
        $this->orderService->proceedToPayment($this->order);
        $this->redirect(route('orders.payment', $this->order));
    }

    public function cancelOrder(): void
    {
        $this->orderService->rollbackQuota($this->order);
        $this->redirect(route('events.show', $this->order->event));
    }

    public function render()
    {
        return view('livewire.orders.checkout')
            ->layout('layouts.booking', ['title' => 'Checkout — ' . $this->order->event->name]);
    }
}

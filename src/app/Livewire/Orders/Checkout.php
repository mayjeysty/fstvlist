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

    public string $title = 'Tuan';
    public string $name = '';
    public string $email = '';
    public string $whatsapp = '';
    public string $nik = '';

    protected OrderService $orderService;

    public function boot(OrderService $orderService): void
    {
        $this->orderService = $orderService;
    }

    public function mount(Order $order): void
    {
        abort_if($order->status !== Order::STATUS_PENDING, 404);
        abort_if($order->user_id !== auth()->id(), 403);
        $this->order = $order->load(['event.venue', 'section', 'user']);

        $user = auth()->user();
        $this->name  = $user->name ?? '';
        $this->email = $user->email ?? '';
    }

    public function attemptProceed(): void
    {
        $this->validate([
            'name'     => 'required|string|min:3|max:255',
            'email'    => 'required|email|max:255',
            'whatsapp' => 'required|string|min:10|max:15',
            'nik'      => 'required|string|size:16',
        ]);

        $this->showConfirmModal = true;
    }

    public function proceedToPayment(): void
    {
        $this->validate([
            'name'     => 'required|string|min:3|max:255',
            'email'    => 'required|email|max:255',
            'whatsapp' => 'required|string|min:10|max:15',
            'nik'      => 'required|string|size:16',
        ]);

        try {
            $this->orderService->proceedToPayment($this->order);
            $this->redirect(route('orders.payment', $this->order));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Checkout proceedToPayment failed', [
                'order_id' => $this->order->id,
                'error'    => $e->getMessage(),
            ]);
            session()->flash('error', 'Gagal melanjutkan ke pembayaran: ' . $e->getMessage());
        }
    }

    public function cancelOrder(): void
    {
        try {
            $this->orderService->rollbackQuota($this->order);
            $this->redirect(route('events.show', $this->order->event));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Checkout cancelOrder failed', [
                'order_id' => $this->order->id,
                'error'    => $e->getMessage(),
            ]);
            session()->flash('error', 'Gagal membatalkan pesanan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.orders.checkout')
            ->layout('layouts.booking', ['title' => 'Checkout — ' . $this->order->event->name]);
    }
}

<?php

namespace App\Livewire\Orders;

use App\Models\Event;
use App\Models\EventSection;
use App\Services\OrderService;
use App\Services\QueueService;
use Livewire\Component;

class Create extends Component
{
    public Event $event;
    public ?int $sectionId = null;
    public int $qty = 1;
    public ?string $preselectedSection = null;

    protected OrderService $orderService;
    protected QueueService $queueService;

    public function boot(OrderService $orderService, QueueService $queueService): void
    {
        $this->orderService = $orderService;
        $this->queueService = $queueService;
    }

    public function mount(Event $event): void
    {
        abort_if(! $event->is_active, 404);
        $this->event = $event->load('venue.sections', 'eventSections');

        $this->preselectedSection = request()->query('section');
        if ($this->preselectedSection) {
            $this->sectionId = (int) $this->preselectedSection;
        }
    }

    public function getSelectedSectionProperty()
    {
        if (! $this->sectionId) {
            return null;
        }
        return $this->event->venue->sections->firstWhere('id', $this->sectionId);
    }

    public function getSectionPriceProperty(): int
    {
        if (! $this->sectionId) {
            return 0;
        }

        $eventSection = $this->event->eventSections
            ->firstWhere('venue_section_id', $this->sectionId);

        if ($eventSection) {
            return $eventSection->price;
        }

        $section = $this->event->venue->sections->firstWhere('id', $this->sectionId);
        return $section ? $section->price : 0;
    }

    public function getSectionRemainingProperty(): int
    {
        if (! $this->sectionId) {
            return 0;
        }

        $eventSection = $this->event->eventSections
            ->firstWhere('venue_section_id', $this->sectionId);

        if ($eventSection) {
            return $eventSection->remaining_quota;
        }

        $section = $this->event->venue->sections->firstWhere('id', $this->sectionId);
        return $section ? $section->remaining_capacity : 0;
    }

    public function getSectionSoldOutProperty(): bool
    {
        if (! $this->sectionId) {
            return true;
        }

        $eventSection = $this->event->eventSections
            ->firstWhere('venue_section_id', $this->sectionId);

        if ($eventSection) {
            return $eventSection->isSoldOut();
        }

        $section = $this->event->venue->sections->firstWhere('id', $this->sectionId);
        return $section ? $section->isSoldOut() : true;
    }

    public function getTotalPriceProperty(): int
    {
        $price = $this->sectionPrice;
        if (! $price) return 0;
        $subtotal = $price * $this->qty;
        return $subtotal + (int) ($subtotal * config('ticketing.service_fee_rate'));
    }

    public function getSectionPricesProperty(): array
    {
        $prices = [];
        $eventSections = $this->event->eventSections->keyBy('venue_section_id');

        foreach ($this->event->venue->sections as $section) {
            $es = $eventSections->get($section->id);
            $prices[$section->id] = [
                'price'      => $es ? $es->price : $section->price,
                'remaining'  => $es ? $es->remaining_quota : $section->remaining_capacity,
                'soldOut'    => $es ? $es->isSoldOut() : $section->isSoldOut(),
            ];
        }

        return $prices;
    }

    public function reserve(): void
    {
        $this->validate([
            'sectionId' => 'required|exists:venue_sections,id',
            'qty'       => 'required|integer|min:1|max:4',
        ]);

        if ($this->event->queue_enabled) {
            $token = session('queue_token_' . $this->event->id);
            abort_unless(
                $token && $this->queueService->validateToken(auth()->id(), $this->event->id, $token),
                403, 'Anda belum mendapat giliran dari antrian.'
            );
        }

        try {
            $order = $this->orderService->reserve(
                auth()->id(), $this->event->id, $this->sectionId, $this->qty
            );
        } catch (\Exception $e) {
            $this->addError('sectionId', $e->getMessage());
            return;
        }

        $this->redirect(route('orders.checkout', $order));
    }

    public function render()
    {
        return view('livewire.orders.create', [
            'sections'        => $this->event->venue->sections,
            'sectionPrices'   => $this->sectionPrices,
            'totalPrice'      => $this->totalPrice,
            'selectedSection' => $this->selectedSection,
            'sectionPrice'    => $this->sectionPrice,
            'sectionRemaining'=> $this->sectionRemaining,
            'sectionSoldOut'  => $this->sectionSoldOut,
        ])->layout('layouts.booking', ['title' => 'Pilih Tiket — ' . $this->event->name]);
    }
}


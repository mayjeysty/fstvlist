<?php

namespace App\Livewire\Gate;

use App\Services\QrValidationService;
use Livewire\Component;

class Index extends Component
{
    public string $qrToken = '';
    public ?array $result = null;
    public ?string $error = null;

    protected QrValidationService $qrService;

    public function boot(QrValidationService $qrService): void
    {
        $this->qrService = $qrService;
    }

    public function scan(): void
    {
        $this->validate(['qrToken' => 'required|string']);

        try {
            $ticket = $this->qrService->validate($this->qrToken, auth()->id());
            $this->result = [
                'message'     => 'Tiket valid! Selamat datang.',
                'ticket'      => $ticket->toArray(),
                'ticket_code' => $ticket->ticket_code,
                'user_name'   => $ticket->user_name,
                'user_email'  => $ticket->user_email,
                'event_name'  => $ticket->event->name,
                'section_name' => $ticket->section->name,
            ];
            $this->error = null;
            $this->qrToken = '';
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            $this->result = null;
        }
    }

    public function resetForm(): void
    {
        $this->reset(['qrToken', 'result', 'error']);
    }

    public function render()
    {
        return view('livewire.gate.index')
            ->layout('layouts.gate', ['title' => 'Validasi Tiket']);
    }
}

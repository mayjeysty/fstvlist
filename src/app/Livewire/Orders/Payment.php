<?php

namespace App\Livewire\Orders;

use App\Mail\EticketMail;
use App\Models\Order;
use App\Services\PaymentService;
use App\Services\TicketService;
use App\Services\QueueService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class Payment extends Component
{
    public Order $order;
    public string $method = 'transfer';
    public ?string $snapToken = null;
    public ?string $midtransOrderId = null;
    public bool $showSimulatedPanel = false;
    public bool $showTicket = false;
    public int $activeTicketIndex = 0;
    public array $simulatedPayment = [];

    protected PaymentService $paymentService;
    protected TicketService $ticketService;
    protected QueueService $queueService;

    public function boot(
        PaymentService $paymentService,
        TicketService $ticketService,
        QueueService $queueService
    ): void {
        $this->paymentService = $paymentService;
        $this->ticketService  = $ticketService;
        $this->queueService   = $queueService;
    }

    public function mount(Order $order): void
    {
        if ($order->status === Order::STATUS_PAID) {
            $this->order = $order->load(['event.venue', 'tickets.section']);
            $this->showTicket = true;
            return;
        }

        abort_if($order->status !== Order::STATUS_WAITING_PAYMENT, 404);
        abort_if($order->user_id !== auth()->id(), 403);
        $this->order = $order->load(['event.venue', 'section']);
    }

    public function switchToTicket(int $index): void
    {
        $this->activeTicketIndex = $index;
    }

    public function initiatePayment(): void
    {
        $this->validate(['method' => 'required|in:transfer,e-wallet,qris,virtual_account']);

        try {
            $result = $this->paymentService->createTransaction($this->order, $this->method);
            $this->midtransOrderId = $result['order_id'];

            if (! empty($result['simulated'])) {
                $this->showSimulatedPayment();
                return;
            }

            $this->snapToken = $result['snap_token'];
            $token = $this->snapToken;

            $this->js(<<<JS
                window.snap.pay('{$token}', {
                    onSuccess: function(result) {
                        \$wire.call('handlePaymentSuccess', JSON.stringify(result));
                    },
                    onPending: function() {
                        \$wire.call('handlePaymentPending');
                    },
                    onError: function() {
                        \$wire.call('handlePaymentError');
                    },
                    onClose: function() {
                        \$wire.call('handlePaymentError');
                    }
                });
            JS);
        } catch (\Exception $e) {
            $this->addError('method', $e->getMessage());
        }
    }

    private function showSimulatedPayment(): void
    {
        $banks = ['BCA', 'BNI', 'Mandiri', 'BRI'];
        $bank = match ($this->method) {
            'qris'       => 'QRIS',
            'e-wallet'   => 'GoPay / OVO / DANA',
            'virtual_account' => 'BCA Virtual Account',
            default      => $banks[array_rand($banks)],
        };

        $vaNumber = '8129' . str_pad((string) rand(1000000000, 9999999999), 12, '0', STR_PAD_LEFT);

        if (in_array($this->method, ['qris', 'e-wallet'])) {
            $paymentCode = substr(str_replace('-', '', (string) \Illuminate\Support\Str::uuid()), 0, 12);
        }

        $this->simulatedPayment = [
            'bank'          => $bank,
            'bank_code'      => match ($this->method) {
                'transfer' => '014',
                'virtual_account' => '014',
                'qris'     => 'QR',
                'e-wallet' => 'EW',
                default    => '014',
            },
            'va_number'      => $vaNumber,
            'payment_code'   => $paymentCode ?? null,
            'reference'      => $this->midtransOrderId,
            'amount'         => $this->order->total_price,
            'method'         => $this->method,
            'is_qris'        => $this->method === 'qris',
            'is_ewallet'     => $this->method === 'e-wallet',
            'is_va'          => in_array($this->method, ['transfer', 'virtual_account']),
        ];

        $this->showSimulatedPanel = true;
    }

    public function confirmSimulatedPayment(): void
    {
        $this->processSuccess();
        $this->showSimulatedPanel = false;
    }

    public function cancelSimulatedPayment(): void
    {
        $this->showSimulatedPanel = false;
        $this->midtransOrderId = null;
        $this->simulatedPayment = [];
    }

    public function handlePaymentSuccess(string $resultJson = ''): void
    {
        try {
            if (! empty($resultJson)) {
                $result = json_decode($resultJson, true);
                $transactionId = $result['transaction_id'] ?? null;
                if ($transactionId) {
                    $this->order->update(['midtrans_transaction_id' => $transactionId]);
                }
            }

            $this->processSuccess();
        } catch (\Exception $e) {
            Log::error('Payment callback failed', [
                'order_id' => $this->order->id,
                'error'    => $e->getMessage(),
            ]);
            session()->flash('error', 'Terjadi kesalahan saat mengonfirmasi pembayaran. Silakan hubungi support.');
        }
    }

    public function processSuccess(): void
    {
        try {
            $this->paymentService->markAsPaid($this->order);

            $this->ticketService->generate($this->order, [
                ['section_id' => $this->order->section_id, 'qty' => $this->order->qty],
            ]);

            try {
                $this->sendEticketEmail();
            } catch (\Exception $e) {
                Log::warning('Failed to send e-ticket email', [
                    'order_id' => $this->order->id,
                    'error'    => $e->getMessage(),
                ]);
            }

            if ($this->order->event->queue_enabled) {
                $this->queueService->complete(auth()->id(), $this->order->event_id);
            }

            $this->order->load('tickets.section');
            $this->showTicket = true;
        } catch (\Exception $e) {
            Log::error('Payment processSuccess failed', [
                'order_id' => $this->order->id,
                'error'    => $e->getMessage(),
            ]);
            session()->flash('error', 'Terjadi kesalahan saat memproses pembayaran. Tim kami sedang meninjau. Silakan hubungi support.');
        }
    }

    private function sendEticketEmail(): void
    {
        Mail::to($this->order->user->email)->send(new EticketMail($this->order));
        $this->order->tickets()->update(['email_sent_at' => now()]);
    }

    public function download()
    {
        return redirect()->route('tickets.download', $this->order);
    }

    public function handlePaymentError(): void
    {
        session()->flash('error', 'Pembayaran dibatalkan atau gagal. Silakan coba lagi.');
        $this->snapToken       = null;
        $this->midtransOrderId = null;
    }

    public function handlePaymentPending(): void
    {
        session()->flash('info', 'Pembayaran menunggu konfirmasi. Silakan selesaikan pembayaran Anda.');
        $this->snapToken = null;
    }

    public function render()
    {
        $isMailLog = config('mail.default') === 'log';

        return view('livewire.orders.payment', [
            'isMailLog' => $isMailLog,
        ])->layout('layouts.booking', ['title' => 'Pembayaran — ' . $this->order->event->name]);
    }
}

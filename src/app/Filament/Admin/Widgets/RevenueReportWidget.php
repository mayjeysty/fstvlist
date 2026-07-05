<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Order;
use App\Models\Ticket;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RevenueReportWidget extends BaseWidget
{
    protected static ?int $sort = 5;
    protected ?string $heading = 'Ringkasan Pendapatan';
    protected int|string|array $columnSpan = 'full';

    protected function getStats(): array
    {
        $orderQuery = Order::query()->where('status', 'paid');
        $ticketQuery = Ticket::query();

        $totalRevenue   = (int) $orderQuery->sum('total_price');
        $totalOrders    = $orderQuery->count();
        $totalTickets   = $ticketQuery->count();
        $checkedIn      = (clone $ticketQuery)->whereNotNull('checked_in_at')->count();
        $avgOrderValue  = $totalOrders > 0 ? (int) ($totalRevenue / $totalOrders) : 0;

        return [
            Stat::make('Total Pendapatan', 'Rp ' . number_format($totalRevenue, 0, ',', '.'))
                ->description($totalOrders . ' pesanan berhasil dibayar')
                ->icon('heroicon-o-banknotes')
                ->color('success'),
            Stat::make('Tiket Terjual', number_format($totalTickets))
                ->description($checkedIn . ' sudah check-in')
                ->icon('heroicon-o-ticket')
                ->color('info'),
            Stat::make('Rata-rata Nilai Pesanan', 'Rp ' . number_format($avgOrderValue, 0, ',', '.'))
                ->description('per transaksi')
                ->icon('heroicon-o-shopping-cart')
                ->color('warning'),
            Stat::make('Tiket Tervalidasi', number_format($checkedIn))
                ->description($totalTickets > 0 ? round(($checkedIn / $totalTickets) * 100, 1) . '% dari total tiket' : 'Belum ada')
                ->icon('heroicon-o-check-circle')
                ->color($checkedIn > 0 ? 'primary' : 'gray'),
        ];
    }
}

<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Order;
use App\Models\Queue;
use App\Models\Ticket;
use App\Models\VenueSection;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;
    protected int|string|array $columnSpan = 'full';

    protected function getStats(): array
    {
        $totalRevenue = Order::where('status', 'paid')->sum('total_price');
        $soldTickets  = Ticket::count();
        $remaining    = VenueSection::sum('remaining_capacity');
        $activeQueue  = Queue::where('status', 'waiting')->count();

        return [
            Stat::make('Total Revenue', 'Rp ' . number_format($totalRevenue, 0, ',', '.'))
                ->icon('heroicon-o-banknotes')
                ->color('success'),
            Stat::make('Tickets Sold', number_format($soldTickets))
                ->icon('heroicon-o-ticket')
                ->color('info'),
            Stat::make('Remaining Quota', number_format($remaining))
                ->icon('heroicon-o-users')
                ->color('warning'),
            Stat::make('Queue Waiting', number_format($activeQueue))
                ->icon('heroicon-o-queue-list')
                ->color($activeQueue > 0 ? 'danger' : 'gray'),
        ];
    }
}

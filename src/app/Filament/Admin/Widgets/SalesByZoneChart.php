<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Event;
use App\Models\Order;
use App\Models\VenueSection;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class SalesByZoneChart extends ChartWidget
{
    protected static ?string $heading = 'Penjualan Tiket per Zona';
    protected static ?int $sort = 4;
    protected int|string|array $columnSpan = 'full';

    public ?string $filter = 'all';

    protected function getFilters(): ?array
    {
        $events = Event::where('is_active', true)->pluck('name', 'id')->toArray();
        return ['all' => 'Semua Event', ...$events];
    }

    protected function getData(): array
    {
        $sectionQuery = VenueSection::query();

        if ($this->filter !== 'all') {
            $event = Event::with('venue.sections')->find($this->filter);
            if ($event && $event->venue) {
                $sectionIds = $event->venue->sections->pluck('id');
                $sectionQuery->whereIn('id', $sectionIds);
            }
        }

        $sections = $sectionQuery->orderBy('name')->get();

        $labels = $sections->pluck('name')->toArray();
        $capacities = $sections->pluck('capacity')->toArray();

        $soldQuery = Order::query()
            ->where('status', 'paid')
            ->join('venue_sections', 'orders.section_id', '=', 'venue_sections.id')
            ->select('venue_sections.id', 'venue_sections.name', DB::raw('SUM(orders.qty) as total_sold'));

        if ($this->filter !== 'all') {
            $soldQuery->where('orders.event_id', $this->filter);
        }

        $soldData = $soldQuery->groupBy('venue_sections.id', 'venue_sections.name')
            ->orderBy('venue_sections.name')
            ->get()
            ->keyBy('name');

        $soldPerSection = [];
        foreach ($sections as $section) {
            $soldPerSection[] = (int) ($soldData->get($section->name)?->total_sold ?? 0);
        }

        return [
            'datasets' => [
                [
                    'label'           => 'Tiket Terjual',
                    'data'            => $soldPerSection,
                    'backgroundColor' => '#3b82f6',
                    'borderColor'     => '#2563eb',
                ],
                [
                    'label'           => 'Total Kapasitas',
                    'data'            => $capacities,
                    'backgroundColor' => '#94a3b8',
                    'borderColor'     => '#64748b',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'indexAxis' => 'x',
            'scales'    => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks'       => ['stepSize' => 1],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'position' => 'top',
                ],
            ],
        ];
    }
}

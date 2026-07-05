<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Ticket;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class ValidationLogWidget extends BaseWidget
{
    protected static ?string $heading = 'Log Validasi Tiket';
    protected static ?int $sort = 6;
    protected int|string|array $columnSpan = 'full';

    protected function getTablePollingInterval(): ?string
    {
        return '30s';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Ticket::query()
                    ->whereNotNull('checked_in_at')
                    ->with(['event', 'section', 'checkedInBy'])
                    ->latest('checked_in_at')
                    ->limit(50)
            )
            ->columns([
                Tables\Columns\TextColumn::make('ticket_code')
                    ->label('Kode Tiket')
                    ->searchable()
                    ->copyable()
                    ->size('sm'),
                Tables\Columns\TextColumn::make('user_name')
                    ->label('Pemegang')
                    ->searchable()
                    ->size('sm'),
                Tables\Columns\TextColumn::make('event.name')
                    ->label('Event')
                    ->searchable()
                    ->size('sm'),
                Tables\Columns\TextColumn::make('section.name')
                    ->label('Zona')
                    ->size('sm'),
                Tables\Columns\TextColumn::make('checkedInBy.name')
                    ->label('Validator')
                    ->size('sm')
                    ->default('-'),
                Tables\Columns\TextColumn::make('checked_in_at')
                    ->label('Check-in')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->size('sm'),
            ])
            ->defaultSort('checked_in_at', 'desc')
            ->paginated([10, 25, 50])
            ->defaultPaginationPageOption(10);
    }
}

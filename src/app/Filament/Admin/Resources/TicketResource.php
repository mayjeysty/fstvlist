<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\TicketResource\Pages;
use App\Models\Ticket;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;
    protected static ?string $navigationIcon = 'heroicon-o-ticket';
    protected static ?string $navigationGroup = 'Ticketing';
    protected static ?int $navigationSort = 2;

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('ticket_code')->searchable()->sortable()->copyable(),
                Tables\Columns\TextColumn::make('event.name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('section.name')->sortable()->label('Section'),
                Tables\Columns\TextColumn::make('user_name')->searchable()->label('Name'),
                Tables\Columns\TextColumn::make('user_email')->searchable()->label('Email'),
                Tables\Columns\IconColumn::make('checked_in_at')
                    ->label('Checked In')
                    ->boolean()
                    ->getStateUsing(fn (Ticket $r) => $r->checked_in_at !== null),
                Tables\Columns\TextColumn::make('checked_in_at')->dateTime('d/m/Y H:i')->label('Check-in Time'),
                Tables\Columns\TextColumn::make('created_at')->dateTime('d/m/Y H:i')->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('event')
                    ->relationship('event', 'name'),
                Tables\Filters\TernaryFilter::make('checked_in')
                    ->label('Checked In')
                    ->nullable()
                    ->attribute('checked_in_at'),
            ])
            ->actions([
                Tables\Actions\Action::make('checkIn')
                    ->label('Check In')
                    ->icon('heroicon-o-qr-code')
                    ->color('success')
                    ->visible(fn (Ticket $r) => $r->checked_in_at === null)
                    ->requiresConfirmation()
                    ->action(fn (Ticket $r) => $r->update([
                        'checked_in_at' => now(),
                        'checked_in_by' => auth()->id(),
                    ])),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTickets::route('/'),
        ];
    }
}

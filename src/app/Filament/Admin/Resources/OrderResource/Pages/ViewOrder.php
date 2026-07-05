<?php

namespace App\Filament\Admin\Resources\OrderResource\Pages;

use App\Filament\Admin\Resources\OrderResource;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Infolists\Components\Section::make('Order Info')->schema([
                Infolists\Components\TextEntry::make('id')->label('Order ID'),
                Infolists\Components\TextEntry::make('user.name')->label('Customer'),
                Infolists\Components\TextEntry::make('event.name')->label('Event'),
                Infolists\Components\TextEntry::make('status')->badge()
                    ->color(fn ($state) => match ($state) {
                        'paid'            => 'success',
                        'waiting_payment' => 'info',
                        'reserved'        => 'warning',
                        'cancelled', 'expired' => 'danger',
                        default           => 'gray',
                    }),
                Infolists\Components\TextEntry::make('total_price')->money('IDR'),
                Infolists\Components\TextEntry::make('paid_at')->dateTime('d/m/Y H:i'),
            ])->columns(3),

            Infolists\Components\Section::make('Tickets')->schema([
                Infolists\Components\RepeatableEntry::make('tickets')->schema([
                    Infolists\Components\TextEntry::make('ticket_code'),
                    Infolists\Components\TextEntry::make('section.name')->label('Section'),
                    Infolists\Components\TextEntry::make('user_name'),
                    Infolists\Components\TextEntry::make('checked_in_at')
                        ->dateTime('d/m/Y H:i')
                        ->placeholder('Not checked in'),
                ])->columns(4),
            ]),
        ]);
    }
}

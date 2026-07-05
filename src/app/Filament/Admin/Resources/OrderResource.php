<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationGroup = 'Ticketing';
    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        return (string) Order::whereIn('status', ['reserved', 'waiting_payment'])->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('status')
                ->options([
                    'waiting'         => 'Waiting',
                    'reserved'        => 'Reserved',
                    'waiting_payment' => 'Waiting Payment',
                    'paid'            => 'Paid',
                    'cancelled'       => 'Cancelled',
                    'expired'         => 'Expired',
                ])
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable()->label('ID'),
                Tables\Columns\TextColumn::make('user.name')->searchable()->sortable()->label('Customer'),
                Tables\Columns\TextColumn::make('event.name')->searchable()->sortable()->label('Event'),
                Tables\Columns\TextColumn::make('total_price')->money('IDR')->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'gray'    => 'waiting',
                        'warning' => 'reserved',
                        'info'    => 'waiting_payment',
                        'success' => 'paid',
                        'danger'  => ['cancelled', 'expired'],
                    ]),
                Tables\Columns\TextColumn::make('booking_deadline')->dateTime('d/m/Y H:i')->label('Booking DL'),
                Tables\Columns\TextColumn::make('payment_deadline')->dateTime('d/m/Y H:i')->label('Payment DL'),
                Tables\Columns\TextColumn::make('paid_at')->dateTime('d/m/Y H:i')->label('Paid At'),
                Tables\Columns\TextColumn::make('created_at')->dateTime('d/m/Y H:i')->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'waiting'         => 'Waiting',
                        'reserved'        => 'Reserved',
                        'waiting_payment' => 'Waiting Payment',
                        'paid'            => 'Paid',
                        'cancelled'       => 'Cancelled',
                        'expired'         => 'Expired',
                    ]),
                Tables\Filters\SelectFilter::make('event')
                    ->relationship('event', 'name'),
            ])
            ->actions([
                Tables\Actions\Action::make('markPaid')
                    ->label('Mark Paid')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->visible(fn (Order $r) => $r->status === 'waiting_payment')
                    ->requiresConfirmation()
                    ->action(fn (Order $r) => $r->update(['status' => 'paid', 'paid_at' => now()])),
                Tables\Actions\Action::make('cancel')
                    ->label('Cancel')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (Order $r) => in_array($r->status, ['waiting', 'reserved', 'waiting_payment']))
                    ->requiresConfirmation()
                    ->action(fn (Order $r) => $r->update(['status' => 'cancelled', 'expired_at' => now()])),
                Tables\Actions\ViewAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'view'  => Pages\ViewOrder::route('/{record}'),
        ];
    }
}

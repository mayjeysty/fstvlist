<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\EventResource\Pages;
use App\Models\Event;
use App\Models\Venue;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationGroup = 'Event Management';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('venue_id')
                ->relationship('venue', 'name')
                ->required()->searchable()->preload()
                ->live()->columnSpanFull(),
            Forms\Components\TextInput::make('name')
                ->required()->maxLength(255)->columnSpanFull(),
            Forms\Components\Textarea::make('description')
                ->rows(3)->columnSpanFull(),
            Forms\Components\FileUpload::make('banner')
                ->image()->optimize('webp')
                ->directory('events/banners')
                ->columnSpanFull(),
            Forms\Components\DateTimePicker::make('start_time')->required(),
            Forms\Components\DateTimePicker::make('end_time')->required(),
            Forms\Components\Toggle::make('is_active')->label('Active')->default(false),
            Forms\Components\Toggle::make('sales_open')->label('Sales Open')->default(true),
            Forms\Components\Toggle::make('queue_enabled')->label('Queue Mode')->default(false),

            Forms\Components\Section::make('Ticket Categories per Zone')
                ->description('Tetapkan harga dan kuota untuk setiap zona di event ini.')
                ->schema([
                    Forms\Components\Repeater::make('eventSections')
                        ->relationship()
                        ->schema([
                            Forms\Components\Select::make('venue_section_id')
                                ->label('Zona')
                                ->relationship('venueSection', 'name', fn ($query) =>
                                    $query->with('venue')
                                )
                                ->required()->searchable()->preload()
                                ->getOptionLabelFromRecordUsing(fn ($record) =>
                                    $record->name . ' (' . $record->venue->name . ')'
                                )
                                ->disableOptionsWhenSelectedInSiblingRepeaterItems(),
                            Forms\Components\TextInput::make('price')
                                ->required()->numeric()->prefix('Rp')->minValue(0),
                            Forms\Components\TextInput::make('quota')
                                ->required()->numeric()->minValue(1)
                                ->label('Kuota'),
                        ])
                        ->columns(3)
                        ->columnSpanFull()
                        ->addActionLabel('Tambah Kategori Tiket')
                        ->itemLabel(fn (array $state): ?string =>
                            \App\Models\VenueSection::with('venue')->find($state['venue_section_id'] ?? 0)?->name
                            . ' (' . (\App\Models\VenueSection::with('venue')->find($state['venue_section_id'] ?? 0)?->venue?->name ?? '') . ')'
                            ?? 'Kategori Baru'
                        ),
                ])
                ->visible(fn (Forms\Get $get) => $get('venue_id') !== null),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('banner'),
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('venue.name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('venue.city')->sortable()->label('Kota'),
                Tables\Columns\TextColumn::make('start_time')->dateTime('d/m/Y H:i')->sortable(),
                Tables\Columns\IconColumn::make('is_active')->boolean()->label('Active'),
                Tables\Columns\IconColumn::make('sales_open')->boolean()->label('Sales'),
                Tables\Columns\IconColumn::make('queue_enabled')->boolean()->label('Queue'),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')->label('Active'),
                Tables\Filters\TernaryFilter::make('sales_open')->label('Sales Open'),
                Tables\Filters\TernaryFilter::make('queue_enabled')->label('Queue'),
            ])
            ->actions([
                Tables\Actions\Action::make('toggleActive')
                    ->label(fn (Event $r) => $r->is_active ? 'Deactivate' : 'Activate')
                    ->icon(fn (Event $r) => $r->is_active ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle')
                    ->color(fn (Event $r) => $r->is_active ? 'danger' : 'success')
                    ->action(fn (Event $r) => $r->update(['is_active' => ! $r->is_active])),
                Tables\Actions\Action::make('toggleSales')
                    ->label(fn (Event $r) => $r->sales_open ? 'Close Sales' : 'Open Sales')
                    ->icon(fn (Event $r) => $r->sales_open ? 'heroicon-o-lock-closed' : 'heroicon-o-lock-open')
                    ->color(fn (Event $r) => $r->sales_open ? 'danger' : 'success')
                    ->action(fn (Event $r) => $r->update(['sales_open' => ! $r->sales_open])),
                Tables\Actions\Action::make('toggleQueue')
                    ->label(fn (Event $r) => $r->queue_enabled ? 'Disable Queue' : 'Enable Queue')
                    ->icon('heroicon-o-queue-list')
                    ->color(fn (Event $r) => $r->queue_enabled ? 'warning' : 'info')
                    ->action(fn (Event $r) => $r->update(['queue_enabled' => ! $r->queue_enabled])),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'edit'   => Pages\EditEvent::route('/{record}/edit'),
        ];
    }
}

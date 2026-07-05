<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\VenueResource\Pages;
use App\Models\Venue;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class VenueResource extends Resource
{
    protected static ?string $model = Venue::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';
    protected static ?string $navigationGroup = 'Event Management';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->required()->maxLength(255)->columnSpanFull(),
            Forms\Components\TextInput::make('city')
                ->maxLength(100)->columnSpanFull(),
            Forms\Components\Textarea::make('address')
                ->required()->rows(2)->columnSpanFull(),
            Forms\Components\TextInput::make('capacity')
                ->numeric()->minValue(1)->columnSpan(1),
            Forms\Components\FileUpload::make('layout_image')
                ->label('Layout Image')
                ->image()->optimize('webp')
                ->directory('venues/layouts')
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('city')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('address')->limit(50)->searchable(),
                Tables\Columns\ImageColumn::make('layout_image')->label('Layout'),
                Tables\Columns\TextColumn::make('sections_count')
                    ->counts('sections')->label('Sections'),
                Tables\Columns\TextColumn::make('created_at')->date()->sortable(),
            ])
            ->actions([
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
            'index'  => Pages\ListVenues::route('/'),
            'create' => Pages\CreateVenue::route('/create'),
            'edit'   => Pages\EditVenue::route('/{record}/edit'),
        ];
    }
}

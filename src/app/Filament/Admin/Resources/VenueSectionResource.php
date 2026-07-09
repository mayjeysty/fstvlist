<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\VenueSectionResource\Pages;
use App\Models\VenueSection;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class VenueSectionResource extends Resource
{
    protected static ?string $model = VenueSection::class;
    protected static ?string $navigationIcon = 'heroicon-o-map';
    protected static ?string $navigationGroup = 'Event Management';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('venue_id')
                ->relationship('venue', 'name')
                ->required()->searchable()->preload(),
            Forms\Components\TextInput::make('name')
                ->required()->maxLength(100),
            Forms\Components\TextInput::make('capacity')
                ->required()->numeric()->minValue(1),
            Forms\Components\TextInput::make('remaining_capacity')
                ->required()->numeric()->minValue(0),
            Forms\Components\TextInput::make('price')
                ->required()->numeric()->prefix('Rp')->minValue(0),
            Forms\Components\ColorPicker::make('color_code')
                ->default('#6366f1'),
            Forms\Components\Fieldset::make('Venue Map Position')
                ->schema([
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\TextInput::make('position_x')
                            ->numeric()->minValue(0)->maxValue(100)
                            ->default(50)->label('Position X (%)')
                            ->helperText('Horizontal: 0 = left, 50 = center, 100 = right'),
                        Forms\Components\TextInput::make('position_y')
                            ->numeric()->minValue(0)->maxValue(100)
                            ->default(30)->label('Position Y (%)')
                            ->helperText('Vertical: 0 = near stage, 100 = far back'),
                    ]),
                ])
                ->columnSpanFull(),
            Forms\Components\Textarea::make('path_koordinat')
                ->rows(3)->columnSpanFull()
                ->helperText('SVG path data (d attribute) dalam sistem viewBox 700x520. Biarkan kosong untuk bentuk default.'),
            Forms\Components\Fieldset::make('Label Position')
                ->schema([
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\TextInput::make('label_x')
                            ->numeric()->minValue(0)->maxValue(700)
                            ->default(null)->label('Label X (0-700)')
                            ->helperText('Posisi horizontal teks label dalam viewBox SVG. Biarkan kosong untuk auto.'),
                        Forms\Components\TextInput::make('label_y')
                            ->numeric()->minValue(0)->maxValue(520)
                            ->default(null)->label('Label Y (0-520)')
                            ->helperText('Posisi vertikal teks label dalam viewBox SVG. Biarkan kosong untuk auto.'),
                    ]),
                ])
                ->columnSpanFull(),
            Forms\Components\Textarea::make('description')
                ->rows(2)->columnSpanFull(),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('venue.name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\ColorColumn::make('color_code')->label('Color'),
                Tables\Columns\TextColumn::make('capacity')->sortable(),
                Tables\Columns\TextColumn::make('remaining_capacity')->sortable()->label('Remaining'),
                Tables\Columns\TextColumn::make('sold_count')->sortable()->label('Sold'),
                Tables\Columns\TextColumn::make('price')
                    ->money('IDR')->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('venue')
                    ->relationship('venue', 'name'),
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
            'index'  => Pages\ListVenueSections::route('/'),
            'create' => Pages\CreateVenueSection::route('/create'),
            'edit'   => Pages\EditVenueSection::route('/{record}/edit'),
        ];
    }
}

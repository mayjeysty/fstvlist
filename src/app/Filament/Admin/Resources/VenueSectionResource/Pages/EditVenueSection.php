<?php

namespace App\Filament\Admin\Resources\VenueSectionResource\Pages;

use App\Filament\Admin\Resources\VenueSectionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVenueSection extends EditRecord
{
    protected static string $resource = VenueSectionResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}

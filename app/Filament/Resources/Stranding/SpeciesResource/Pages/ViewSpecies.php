<?php

namespace App\Filament\Resources\Stranding\SpeciesResource\Pages;

use App\Filament\Resources\Stranding\SpeciesResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSpecies extends ViewRecord
{
    protected static string $resource = SpeciesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}

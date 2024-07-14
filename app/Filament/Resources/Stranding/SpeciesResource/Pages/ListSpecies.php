<?php

namespace App\Filament\Resources\Stranding\SpeciesResource\Pages;

use App\Filament\Resources\Stranding\SpeciesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSpecies extends ListRecords
{
    protected static string $resource = SpeciesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

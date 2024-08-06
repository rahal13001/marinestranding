<?php

namespace App\Filament\Kkprl\Resources\RegulationResource\Pages;

use App\Filament\Kkprl\Resources\RegulationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRegulations extends ListRecords
{
    protected static string $resource = RegulationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Kkprl\Resources\RegulationResource\Pages;

use App\Filament\Kkprl\Resources\RegulationResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewRegulation extends ViewRecord
{
    protected static string $resource = RegulationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}

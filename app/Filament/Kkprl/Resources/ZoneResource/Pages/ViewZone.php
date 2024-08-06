<?php

namespace App\Filament\Kkprl\Resources\ZoneResource\Pages;

use App\Filament\Kkprl\Resources\ZoneResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewZone extends ViewRecord
{
    protected static string $resource = ZoneResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}

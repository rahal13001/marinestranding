<?php

namespace App\Filament\Resources\Stranding\ProvinceResource\Pages;

use App\Filament\Resources\Stranding\ProvinceResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewProvince extends ViewRecord
{
    protected static string $resource = ProvinceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}

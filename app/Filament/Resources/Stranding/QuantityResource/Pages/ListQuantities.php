<?php

namespace App\Filament\Resources\Stranding\QuantityResource\Pages;

use App\Filament\Resources\Stranding\QuantityResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListQuantities extends ListRecords
{
    protected static string $resource = QuantityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

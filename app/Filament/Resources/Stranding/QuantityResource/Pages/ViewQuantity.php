<?php

namespace App\Filament\Resources\Stranding\QuantityResource\Pages;

use App\Filament\Resources\Stranding\QuantityResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewQuantity extends ViewRecord
{
    protected static string $resource = QuantityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\Stranding\QuantityResource\Pages;

use App\Filament\Resources\Stranding\QuantityResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditQuantity extends EditRecord
{
    protected static string $resource = QuantityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}

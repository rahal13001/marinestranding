<?php

namespace App\Filament\Resources\Stranding\GeneraResource\Pages;

use App\Filament\Resources\Stranding\GeneraResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGenera extends EditRecord
{
    protected static string $resource = GeneraResource::class;

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

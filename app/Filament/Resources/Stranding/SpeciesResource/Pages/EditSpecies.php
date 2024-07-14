<?php

namespace App\Filament\Resources\Stranding\SpeciesResource\Pages;

use App\Filament\Resources\Stranding\SpeciesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSpecies extends EditRecord
{
    protected static string $resource = SpeciesResource::class;

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

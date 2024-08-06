<?php

namespace App\Filament\Kkprl\Resources\RegulationResource\Pages;

use App\Filament\Kkprl\Resources\RegulationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRegulation extends EditRecord
{
    protected static string $resource = RegulationResource::class;

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

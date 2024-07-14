<?php

namespace App\Filament\Resources\Stranding\MethodResource\Pages;

use App\Filament\Resources\Stranding\MethodResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMethod extends EditRecord
{
    protected static string $resource = MethodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}

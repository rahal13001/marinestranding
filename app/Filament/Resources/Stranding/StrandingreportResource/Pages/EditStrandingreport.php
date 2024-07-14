<?php

namespace App\Filament\Resources\Stranding\StrandingreportResource\Pages;

use App\Filament\Resources\Stranding\StrandingreportResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStrandingreport extends EditRecord
{
    protected static string $resource = StrandingreportResource::class;

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

<?php

namespace App\Filament\Kkprl\Resources\KkprluseResource\Pages;

use App\Filament\Kkprl\Resources\KkprluseResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKkprluse extends EditRecord
{
    protected static string $resource = KkprluseResource::class;

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

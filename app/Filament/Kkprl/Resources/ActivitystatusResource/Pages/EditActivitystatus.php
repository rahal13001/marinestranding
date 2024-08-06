<?php

namespace App\Filament\Kkprl\Resources\ActivitystatusResource\Pages;

use App\Filament\Kkprl\Resources\ActivitystatusResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditActivitystatus extends EditRecord
{
    protected static string $resource = ActivitystatusResource::class;

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

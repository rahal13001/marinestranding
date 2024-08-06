<?php

namespace App\Filament\Kkprl\Resources\KkprluseResource\Pages;

use App\Filament\Kkprl\Resources\KkprluseResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewKkprluse extends ViewRecord
{
    protected static string $resource = KkprluseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}

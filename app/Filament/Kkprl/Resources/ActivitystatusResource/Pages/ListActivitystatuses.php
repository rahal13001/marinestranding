<?php

namespace App\Filament\Kkprl\Resources\ActivitystatusResource\Pages;

use App\Filament\Kkprl\Resources\ActivitystatusResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListActivitystatuses extends ListRecords
{
    protected static string $resource = ActivitystatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

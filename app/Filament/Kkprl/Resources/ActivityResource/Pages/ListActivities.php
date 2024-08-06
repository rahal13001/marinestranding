<?php

namespace App\Filament\Kkprl\Resources\ActivityResource\Pages;

use App\Filament\Kkprl\Resources\ActivityResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListActivities extends ListRecords
{
    protected static string $resource = ActivityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
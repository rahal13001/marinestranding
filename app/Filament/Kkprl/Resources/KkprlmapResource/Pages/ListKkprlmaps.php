<?php

namespace App\Filament\Kkprl\Resources\KkprlmapResource\Pages;

use App\Filament\Kkprl\Resources\KkprlmapResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKkprlmaps extends ListRecords
{
    protected static string $resource = KkprlmapResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
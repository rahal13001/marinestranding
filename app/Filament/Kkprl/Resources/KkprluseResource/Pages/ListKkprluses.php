<?php

namespace App\Filament\Kkprl\Resources\KkprluseResource\Pages;

use App\Filament\Kkprl\Resources\KkprluseResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKkprluses extends ListRecords
{
    protected static string $resource = KkprluseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\Stranding\StrandingreportResource\Pages;

use App\Filament\Resources\Stranding\StrandingreportResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStrandingreports extends ListRecords
{
    protected static string $resource = StrandingreportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

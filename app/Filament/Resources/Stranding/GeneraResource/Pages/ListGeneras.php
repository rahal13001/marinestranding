<?php

namespace App\Filament\Resources\Stranding\GeneraResource\Pages;

use App\Filament\Resources\Stranding\GeneraResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGeneras extends ListRecords
{
    protected static string $resource = GeneraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

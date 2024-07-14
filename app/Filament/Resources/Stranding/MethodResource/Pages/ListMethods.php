<?php

namespace App\Filament\Resources\Stranding\MethodResource\Pages;

use App\Filament\Resources\Stranding\MethodResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMethods extends ListRecords
{
    protected static string $resource = MethodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

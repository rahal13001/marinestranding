<?php

namespace App\Filament\Resources\Stranding\GeneraResource\Pages;

use App\Filament\Resources\Stranding\GeneraResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewGenera extends ViewRecord
{
    protected static string $resource = GeneraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}

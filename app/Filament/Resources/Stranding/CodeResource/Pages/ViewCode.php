<?php

namespace App\Filament\Resources\Stranding\CodeResource\Pages;

use App\Filament\Resources\Stranding\CodeResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewCode extends ViewRecord
{
    protected static string $resource = CodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}

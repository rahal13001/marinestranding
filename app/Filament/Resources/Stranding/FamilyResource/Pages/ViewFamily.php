<?php

namespace App\Filament\Resources\Stranding\FamilyResource\Pages;

use App\Filament\Resources\Stranding\FamilyResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewFamily extends ViewRecord
{
    protected static string $resource = FamilyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}

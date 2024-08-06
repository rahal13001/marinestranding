<?php

namespace App\Filament\Kkprl\Resources\KkprlmapResource\Pages;

use App\Filament\Kkprl\Resources\KkprlmapResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKkprlmap extends EditRecord
{
    protected static string $resource = KkprlmapResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}

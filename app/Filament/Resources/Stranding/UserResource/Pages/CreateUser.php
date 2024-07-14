<?php

namespace App\Filament\Resources\Stranding\UserResource\Pages;

use App\Filament\Resources\Stranding\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
}

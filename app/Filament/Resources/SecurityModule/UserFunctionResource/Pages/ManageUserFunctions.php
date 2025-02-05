<?php

namespace App\Filament\Resources\SecurityModule\UserFunctionResource\Pages;

use App\Filament\Resources\SecurityModule\UserFunctionResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageUserFunctions extends ManageRecords
{
    protected static string $resource = UserFunctionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\SettingModule\UserFunctionResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use App\Filament\Resources\SecurityModule\UserFunctionResource;

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

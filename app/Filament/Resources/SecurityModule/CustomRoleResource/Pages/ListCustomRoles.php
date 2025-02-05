<?php

namespace App\Filament\Resources\SecurityModule\CustomRoleResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\SecurityModule\CustomRoleResource;

class ListCustomRoles extends ListRecords
{
    protected static string $resource = CustomRoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

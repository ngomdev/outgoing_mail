<?php
namespace App\Filament\Resources\SettingModule\SystemSettingResource\Pages;


use App\Filament\Resources\SettingModule\SystemSettingResource;
use Filament\Resources\Pages\ManageRecords;

class ManageSystemSettings extends ManageRecords
{
    protected static string $resource = SystemSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}

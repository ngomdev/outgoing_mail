<?php

namespace App\Filament\Resources\SettingModule\DocSettingResource\Pages;

use App\Filament\Resources\SettingModule\DocSettingResource;
use Filament\Resources\Pages\ManageRecords;

class ManageDocSettings extends ManageRecords
{
    protected static string $resource = DocSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}

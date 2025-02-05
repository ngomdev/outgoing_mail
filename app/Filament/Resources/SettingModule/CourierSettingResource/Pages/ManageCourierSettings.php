<?php

namespace App\Filament\Resources\SettingModule\CourierSettingResource\Pages;

use Filament\Resources\Pages\ManageRecords;
use App\Filament\Resources\SettingModule\CourierSettingResource;

class ManageCourierSettings extends ManageRecords
{
    protected static string $resource = CourierSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}

<?php

namespace App\Filament\Resources\DocumentModule\ExternalInitiatorResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use App\Filament\Resources\DocumentModule\ExternalInitiatorResource;

class ManageExternalInitiators extends ManageRecords
{
    protected static string $resource = ExternalInitiatorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

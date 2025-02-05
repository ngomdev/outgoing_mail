<?php

namespace App\Filament\Resources\SecurityModule\TeamResource\Pages;

use App\Filament\Resources\SecurityModule\TeamResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTeam extends EditRecord
{
    protected static string $resource = TeamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
        ];
    }
}

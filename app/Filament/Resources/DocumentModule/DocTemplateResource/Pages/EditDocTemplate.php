<?php

namespace App\Filament\Resources\DocumentModule\DocTemplateResource\Pages;

use App\Filament\Resources\DocumentModule\DocTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDocTemplate extends EditRecord
{
    protected static string $resource = DocTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
        ];
    }
}

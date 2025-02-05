<?php

namespace App\Filament\Resources\DocumentModule\DocTemplateResource\Pages;

use App\Filament\Resources\DocumentModule\DocTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDocTemplates extends ListRecords
{
    protected static string $resource = DocTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\DocumentModule\DocTemplateResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;
use App\Filament\Resources\DocumentModule\DocTemplateResource;

class ViewDocTemplate extends ViewRecord
{
    protected static string $resource = DocTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    public function getTitle(): string | Htmlable
    {
        return $this->record->name ? ('Modèle ' . $this->record->name) : __('Afficher Document');
    }
}

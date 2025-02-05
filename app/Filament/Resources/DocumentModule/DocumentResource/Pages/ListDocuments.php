<?php

namespace App\Filament\Resources\DocumentModule\DocumentResource\Pages;


use App\Enums\DocStatus;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\DocumentModule\DocumentResource;
use Illuminate\Database\Eloquent\Builder;


class ListDocuments extends ListRecords
{
    protected static string $resource = DocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Tout')
                ->badge(DocumentResource::getEloquentQuery()->count())
                ->badgeColor('primary'),
            'draft' => Tab::make('Drafts')
                ->badge(DocumentResource::getEloquentQuery()
                    ->where('status', DocStatus::DRAFT)
                    ->count())
                ->badgeColor(DocStatus::DRAFT->getColor())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', DocStatus::DRAFT)),
            'initialized' => Tab::make('Initialisés')
                ->badge(DocumentResource::getEloquentQuery()
                    ->where('status', DocStatus::INITIATED)
                    ->count())
                ->badgeColor(DocStatus::INITIATED->getColor())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', DocStatus::INITIATED)),
            'validating' => Tab::make('En cours de validation')
                ->badge(DocumentResource::getEloquentQuery()
                    ->where('status', DocStatus::VALIDATING)
                    ->count())
                ->badgeColor(DocStatus::VALIDATING->getColor())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', DocStatus::VALIDATING)),
            'validated' => Tab::make('Validés')
                ->badge(DocumentResource::getEloquentQuery()
                    ->where('status', DocStatus::VALIDATED)
                    ->count())
                ->badgeColor(DocStatus::VALIDATED->getColor())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', DocStatus::VALIDATED)),
            'signed' => Tab::make('Signés')
                ->badge(DocumentResource::getEloquentQuery()
                    ->where('status', DocStatus::SIGNED)
                    ->count())
                ->badgeColor(DocStatus::SIGNED->getColor())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', DocStatus::SIGNED)),
            'archived' => Tab::make('Archivés')
                ->badge(DocumentResource::getEloquentQuery()
                    ->where('status', DocStatus::ARCHIVED)
                    ->count())
                ->badgeColor(DocStatus::ARCHIVED->getColor())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', DocStatus::ARCHIVED)),
            'cancelled' => Tab::make('Annulés')
                ->badge(DocumentResource::getEloquentQuery()
                    ->where('status', DocStatus::CANCELLED)
                    ->count())
                ->badgeColor(DocStatus::CANCELLED->getColor())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', DocStatus::CANCELLED))
        ];
    }
}

<?php

namespace App\Filament\Resources\CourierModule\CourierResource\Pages;

use Filament\Actions;
use App\Enums\CourierStatus;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\CourierModule\CourierResource;
use Illuminate\Database\Eloquent\Builder;


class ListCouriers extends ListRecords
{
    protected static string $resource = CourierResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Tout')
                ->badge(CourierResource::getEloquentQuery()->count())
                ->badgeColor('primary'),
            'draft' => Tab::make('En préparation')
                ->badge(CourierResource::getEloquentQuery()
                    ->where('status', CourierStatus::DRAFT)
                    ->count())
                ->badgeColor(CourierStatus::DRAFT->getColor())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', CourierStatus::DRAFT)),
            'initialized' => Tab::make('Attente levée')
                ->badge(CourierResource::getEloquentQuery()
                    ->where('status', CourierStatus::INITIATED)
                    ->count())
                ->badgeColor(CourierStatus::INITIATED->getColor())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', CourierStatus::INITIATED)),
            'retrieved' => Tab::make('levés')
                ->badge(CourierResource::getEloquentQuery()
                    ->where('status', CourierStatus::RETRIEVED)
                    ->count())
                ->badgeColor(CourierStatus::RETRIEVED->getColor())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', CourierStatus::RETRIEVED)),
            'delivered' => Tab::make('Distribués')
                ->badge(CourierResource::getEloquentQuery()
                    ->where('status', CourierStatus::DELIVERED)
                    ->count())
                ->badgeColor(CourierStatus::DELIVERED->getColor())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', CourierStatus::DELIVERED)),
            'not_delivered' => Tab::make('Non distribués')
                ->badge(CourierResource::getEloquentQuery()
                    ->where('status', CourierStatus::NOT_DELIVERED)
                    ->count())
                ->badgeColor(CourierStatus::NOT_DELIVERED->getColor())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', CourierStatus::NOT_DELIVERED)),
            'rejected' => Tab::make('Rejetés')
                ->badge(CourierResource::getEloquentQuery()
                    ->where('status', CourierStatus::REJECTED)
                    ->count())
                ->badgeColor(CourierStatus::REJECTED->getColor())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', CourierStatus::REJECTED)),
            'closed' => Tab::make('Clôturés')
                ->badge(CourierResource::getEloquentQuery()
                    ->where('status', CourierStatus::CLOSED)
                    ->count())
                ->badgeColor(CourierStatus::CLOSED->getColor())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', CourierStatus::CLOSED)),
            'cancelled' => Tab::make('Annulés')
                ->badge(CourierResource::getEloquentQuery()
                    ->where('status', CourierStatus::CANCELLED)
                    ->count())
                ->badgeColor(CourierStatus::CANCELLED->getColor())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', CourierStatus::CANCELLED)),
        ];
    }
}

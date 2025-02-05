<?php
namespace App\Filament\Resources\SecurityModule\UserResource\Pages;

use Filament\Actions;
use App\Enums\RoleEnum;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\SecurityModule\UserResource;


class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

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
                ->badge(UserResource::getEloquentQuery()->count())
                ->badgeColor('primary'),
            'internal' => Tab::make('Utilisateurs Internes')
                ->badge(UserResource::getEloquentQuery()->whereHas("roles", fn($q) => $q->where("name", "!=", RoleEnum::COURSER->getLabel()))->count())
                ->badgeColor("secondary")
                ->modifyQueryUsing(fn(Builder $query) => $query->whereHas("roles", fn($q) => $q->where("name", "!=", RoleEnum::COURSER->getLabel()))),
            'coursers' => Tab::make('Coursiers')
                ->badge(UserResource::getEloquentQuery()->whereHas("roles", fn($q) => $q->whereName(RoleEnum::COURSER->getLabel()))->count())
                ->badgeColor("secondary")
                ->modifyQueryUsing(fn(Builder $query) => $query->whereHas("roles", fn($q) => $q->whereName(RoleEnum::COURSER->getLabel()))),
        ];
    }
}

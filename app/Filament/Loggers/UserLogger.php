<?php

namespace App\Filament\Loggers;

use App\Models\User;
use Illuminate\Contracts\Support\Htmlable;
use Noxo\FilamentActivityLog\Loggers\Logger;
use Noxo\FilamentActivityLog\ResourceLogger\Field;
use App\Filament\Resources\SecurityModule\UserResource;
use Noxo\FilamentActivityLog\ResourceLogger\ResourceLogger;
use Noxo\FilamentActivityLog\ResourceLogger\RelationManager;

class UserLogger extends Logger
{
    public static ?string $model = User::class;

    public static function getLabel(): string | Htmlable | null
    {
        return UserResource::getModelLabel();
    }

    public static function resource(ResourceLogger $logger): ResourceLogger
    {
        return $logger
            ->fields([
                Field::make('registration_number')
                    ->label(__('Matricule')),

                Field::make('name')
                    ->label(__('Nom')),

                Field::make('email')
                    ->label(__('Email')),

                Field::make('phone')
                    ->label(__('Télephone')),

                Field::make('is_active')
                    ->boolean()
                    ->label('Compte actif')
                    ->badge(),

                Field::make('roles.name')
                    ->hasMany('roles')
                    ->label(__('Rôles'))
                    ->badge(),
            ])
            ->relationManagers([
                //
            ]);
    }
}

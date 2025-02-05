<?php

namespace App\Filament\Resources\SecurityModule\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\RelationManagers\RelationManager;

class TeamsRelationManager extends RelationManager
{
    protected static string $relationship = 'teams';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name'),
                ViewColumn::make('team')
                    ->label('Manager')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query
                            ->whereHas('manager', fn ($q) => $q->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%"));
                    })
                    ->view('filament.tables.columns.user-info'),
                TextColumn::make('description')
                    ->label('Description'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
            ])
            ->emptyStateDescription('Les entités auquels vous êtes rajoutées apparaitront ici.')
            ->defaultSort('teams.created_at', 'desc');
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('Entités');
    }
}

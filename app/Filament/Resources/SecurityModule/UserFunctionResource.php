<?php

namespace App\Filament\Resources\SecurityModule;

use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\UserFunction;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TernaryFilter;
use BezhanSalleh\FilamentShield\Support\Utils;
use App\Filament\Resources\SecurityModule\UserFunctionResource\Pages;

class UserFunctionResource extends Resource
{
    protected static ?string $model = UserFunction::class;

    protected static ?int $navigationSort = 3;
    protected static ?string $slug = 'security/user-functions';

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $navigationLabel = 'Fonctions';
    protected static ?string $modelLabel = 'Fonction';
    protected static ?string $pluralModelLabel = 'Fonctions';
    protected static bool $shouldRegisterNavigation = true;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make()
                    ->schema([
                        TextInput::make('name')
                            ->label(__('Nom'))
                            ->required(),

                        Textarea::make('description')
                            ->label('Description')
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('Nom'))
                    ->wrap()
                    ->searchable(),
                TextColumn::make('description')
                    ->label(__('Description'))
                    ->searchable()
                    ->wrap(),
                TextColumn::make('is_active')
                    ->label('Statut')
                    ->badge()
                    ->formatStateUsing(fn ($state): string =>  $state ? 'Actif' : 'Inactif')
                    ->color(fn ($state): string => $state ? 'success' : 'danger')
            ])
            ->filters([
                TernaryFilter::make('Fonctions')
                    ->placeholder('Tout')
                    ->trueLabel('Actives')
                    ->falseLabel('Inactives')
                    ->queries(
                        true: fn (Builder $query) => $query->where('is_active', true),
                        false: fn (Builder $query) => $query->where('is_active', false),
                        blank: fn (Builder $query) => $query,
                    )
                    ->attribute('is_active'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Action::make('deactivate')
                    ->requiresConfirmation()
                    ->label(fn (?UserFunction $record): string => $record->is_active ? 'Désactiver' : 'Activer')
                    ->icon(fn (?UserFunction $record): string => $record->is_active ? 'heroicon-m-lock-closed' : 'heroicon-m-lock-open')
                    ->color(fn (?UserFunction $record) => $record->is_active ? Color::Red : Color::Emerald)
                    ->modalDescription(fn (?UserFunction $record): string => $record->is_active ? "Êtes-vous sur de vouloir désactiver cette fonction?" : "Êtes-vous sur de vouloir activer cette fonction?")
                    ->action(function (?UserFunction $record) {
                        if ($record) {
                            $activeFunctionUsers = User::query()
                                ->where('is_active', true)
                                ->whereHas('userFunction', fn ($query) => $query->where('name', $record->name))->count();
                            if ($record->is_active && $activeFunctionUsers > 0) {
                                Notification::make()
                                    ->warning()
                                    ->title(__('Oups!'))
                                    ->body("Cette fonction ne peut pas être désactivé car il est attribué à des utilisateurs actifs.")
                                    ->persistent()
                                    ->send();
                            } else {
                                $record->is_active = !$record->is_active;
                                $record->save();

                                Notification::make()
                                    ->success()
                                    ->title(fn (?UserFunction $record) => $record->is_active ? __('Activation fonction') : __('Désactivation fonction'))
                                    ->body(fn (?UserFunction $record) => $record->is_active ? "Fonction activée avec succés!"  : "Fonction désactivée avec succés!")
                                    ->persistent()
                                    ->send();
                            }
                        }
                    })
                    ->visible(fn () => auth()->user()->can('create_security::module::user::function')),
            ])
            ->bulkActions([])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageUserFunctions::route('/'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return Utils::isResourceNavigationGroupEnabled()
            ? __('filament-shield::filament-shield.nav.group')
            : '';
    }
}

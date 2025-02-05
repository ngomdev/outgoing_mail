<?php

namespace App\Filament\Resources\SecurityModule;

use App\Models\User;
use Filament\Tables;
use App\Enums\RoleEnum;
use Filament\Forms\Get;
use Filament\Forms\Form;
use App\Models\CustomRole;
use Filament\Tables\Table;
use App\Models\UserFunction;
use Filament\Resources\Resource;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Blade;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use BezhanSalleh\FilamentShield\Support\Utils;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;
use Filament\Tables\Actions\Action as TableAction;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use App\Filament\Resources\SecurityModule\UserResource\Pages\EditUser;
use App\Filament\Resources\SecurityModule\UserResource\Pages\ViewUser;
use App\Filament\Resources\SecurityModule\UserResource\Pages\ListUsers;
use App\Filament\Resources\SecurityModule\UserResource\Pages\CreateUser;
use App\Filament\Resources\SecurityModule\UserResource\RelationManagers\TeamsRelationManager;
use App\Filament\Resources\SecurityModule\UserResource\RelationManagers\UploadsRelationManager;

class UserResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = User::class;

    protected static ?string $modelLabel = 'Utilisateur';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $pluralModelLabel = 'Utilisateurs';

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema(
                [
                    Grid::make(6)
                        ->schema(
                            [
                                Section::make()
                                    ->schema(
                                        [
                                            TextInput::make('registration_number')
                                                ->label("Matricule")
                                                ->required(fn (Get $get) => CustomRole::where('name', RoleEnum::COURSER->getLabel())?->first()?->id !== (int) $get('role'))
                                                ->disabled(fn ($context) => $context === 'edit')
                                                ->rule("string")
                                                ->unique(table: User::class, column: 'registration_number', ignoreRecord: true)
                                                ->maxLength(150)
                                                ->translateLabel(),
                                            TextInput::make('name')
                                                ->required()
                                                ->label("Nom Complet")
                                                ->rules(["string"])
                                                ->maxLength(150)
                                                ->translateLabel(),
                                            TextInput::make('email')
                                                ->required(fn (Get $get) => CustomRole::where('name', RoleEnum::COURSER->getLabel())?->first()?->id !== (int) $get('role'))
                                                ->email()
                                                ->disabled(fn ($context) => $context === 'edit')
                                                ->unique(table: User::class, column: 'email', ignoreRecord: true)
                                                ->label("Email")
                                                ->maxLength(150)
                                                ->translateLabel(),
                                            PhoneInput::make('phone')
                                                ->required(fn (Get $get) => CustomRole::where('name', RoleEnum::COURSER->getLabel())?->first()?->id === (int) $get('role'))
                                                ->initialCountry('sn')
                                                ->autoPlaceholder('xx xxx xx xx')
                                                ->placeholder('xx xxx xx xx')
                                                ->preferredCountries(['sn'])
                                                ->unique(table: User::class, column: 'phone', ignoreRecord: true)
                                                ->label("Téléphone"),
                                            Select::make('role')
                                                ->options(
                                                    function () {
                                                        $query = CustomRole::query()
                                                            ->where([
                                                                'is_role_courier' => false,
                                                                'is_active' => true,
                                                            ])
                                                            ->where('name', '!=', RoleEnum::SUPER_ADMIN->getLabel());
                                                        return $query->pluck('name', 'id');
                                                    }
                                                )
                                                ->native(false)
                                                ->afterStateHydrated(
                                                    function (Select $component, ?string $state, ?User $record) {
                                                        if ($record) {
                                                            if ($record->roles()->count() > 0) {
                                                                if (!$record->roles()->first()->is_active) {
                                                                    $component->state($record->getRoleNames()->first());
                                                                }
                                                                else{
                                                                    $component->state($record->roles()->first()->id);
                                                                }
                                                            }
                                                        }
                                                    }
                                                )
                                                ->selectablePlaceholder(false)
                                                ->required()
                                                ->live()
                                                ->label('Profil'),

                                            Select::make('user_function_id')
                                                ->relationship(name: 'userFunction', titleAttribute: 'name', modifyQueryUsing: fn (Builder $query) => $query
                                                    ->where('is_active', true))
                                                ->native(false)
                                                ->selectablePlaceholder(false)
                                                ->required(fn (Get $get) => CustomRole::where('name', RoleEnum::COURSER->getLabel())?->first()?->id !== (int) $get('role'))
                                                ->getOptionLabelFromRecordUsing(fn (UserFunction $record) => "{$record->name} ({$record->description})")
                                                ->label('Fonction')
                                                ->afterStateHydrated(
                                                    function (Select $component, ?string $state, ?User $record) {
                                                        if ($record && $record->userFunction && !$record->userFunction->is_active) {
                                                            $component->state($record->userFunction->name . " ({$record->userFunction->description})");
                                                        }
                                                    }
                                                )
                                                ->hidden(fn (Get $get) => CustomRole::where('name', RoleEnum::COURSER->getLabel())?->first()?->id === (int) $get('role')),
                                        ]
                                    )
                                    ->columns(2)
                                    ->columnSpan(4),
                                Section::make('Entités')
                                    ->description('Selectionnez les entités auquels l\'utilisateur doit être rajouté')
                                    ->schema(
                                        [
                                            Repeater::make('userTeams')
                                                ->addActionLabel('Ajouter entité')
                                                ->hiddenLabel()
                                                ->relationship()
                                                ->defaultItems(0)
                                                ->simple(
                                                    Select::make('team_id')
                                                        ->relationship(name: 'team', modifyQueryUsing: fn (Builder $query) => $query
                                                            ->where('is_active', true))
                                                        ->hiddenLabel()
                                                        ->validationAttribute("Entité")
                                                        ->allowHtml()
                                                        ->required()
                                                        ->searchable(['name'])
                                                        ->searchPrompt(__('Rechercher par nom'))
                                                        ->getOptionLabelFromRecordUsing(
                                                            fn (Model $record) => Blade::render(
                                                                "<div class='flex items-center'>
                                                        <x-filament::icon icon='heroicon-m-rectangle-group' class='h-5 w-5 text-blue-600 mr-2' />
                                                        <p>{$record->name}</p>
                                                    </div>"
                                                            )
                                                        )
                                                        ->preload()
                                                        ->dehydrateStateUsing(fn (?string $state) => $state ? strip_tags($state) : null)
                                                        ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                                        ->native(false)
                                                )
                                        ]
                                    )
                                    ->columnSpan(2)
                            ]
                        ),

                ]
            )
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->emptyStateActions(
                [
                    TableAction::make('create')
                        ->label(fn () => __('Ajouter un utilisateur'))
                        ->url(route(CreateUser::getRouteName()))
                        ->icon('heroicon-m-plus')
                        ->button()
                        ->visible(fn (): bool => auth()->user()->can('create_security::module::user')),
                ]
            )
            ->columns(
                [
                    TextColumn::make('registration_number')
                        ->label('Matricule')
                        ->searchable()
                        ->formatStateUsing(fn ($record) => new HtmlString("<p class='font-semibold'>{$record->registration_number}</p>")),
                    ViewColumn::make('name_col')
                        ->label('Nom')
                        ->searchable(['name'])
                        ->view('filament.tables.columns.user-info'),
                    TextColumn::make('roles.name')
                        ->label('Profil')
                        ->searchable()
                        ->badge()
                        ->color('primary'),
                    ViewColumn::make('contact')
                        ->label('Contact')
                        ->searchable(['email', 'phone'])
                        ->view('filament.tables.columns.contact'),
                    IconColumn::make('is_active')
                        ->label('Actif/ Inactif')
                        ->boolean(),
                    TextColumn::make('created_at')
                        ->label('Date création')
                        ->dateTime()
                        ->toggleable(isToggledHiddenByDefault: true),
                    TextColumn::make('updated_at')
                        ->label('Dernière maj')
                        ->dateTime()
                        ->toggleable(isToggledHiddenByDefault: true),
                ]
            )
            ->filters(
                [
                    Tables\Filters\TernaryFilter::make('is_active')
                        ->placeholder('Tout')
                        ->trueLabel('Actif')
                        ->falseLabel('Inactif')
                        ->queries(
                            true: fn (Builder $query) => $query->where('is_active', true),
                            false: fn (Builder $query) => $query->where('is_active', false),
                            blank: fn (Builder $query) => $query,
                        )
                        ->label('Statut'),
                    Tables\Filters\SelectFilter::make('roles')
                        ->relationship(
                            'roles',
                            'name',
                            function (Builder $query) {
                                $query = $query->where('is_role_courier', false);

                                $query->when(
                                    auth()->user()->hasRole(RoleEnum::SUPER_ADMIN->getLabel()),
                                    function ($q) {
                                        return $q->where('name', '!=', RoleEnum::SUPER_ADMIN->getLabel());
                                    }
                                );

                                $query->when(
                                    auth()->user()->hasRole(RoleEnum::ADMIN->getLabel()),
                                    function ($q) {
                                        return $q->whereNotIn('name', [RoleEnum::SUPER_ADMIN->getLabel(), RoleEnum::ADMIN->getLabel()]);
                                    }
                                );

                                return $query;
                            }
                        )
                        ->label('Profil')
                ]
            )
            ->actions(
                [
                    Tables\Actions\ViewAction::make()
                ]
            )
            ->striped()
            ->defaultSort('updated_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            TeamsRelationManager::class,
            UploadsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'view' => ViewUser::route('/{record}'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return Utils::isResourceNavigationGroupEnabled()
            ? __('filament-shield::filament-shield.nav.group')
            : '';
    }

    public static function getSlug(): string
    {
        return '/users';
    }

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'delete',
            'delete_any',
            'pass_validation_turn',
            'add_signataires',
            'export_doc_for_signing',
            'import_signed_doc'
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $userId = auth()->id();

        $query = parent::getEloquentQuery()
            ->where('id', '!=', $userId)
            ->withoutRoles([RoleEnum::SUPER_ADMIN->getLabel()]);

        return $query;
    }
}

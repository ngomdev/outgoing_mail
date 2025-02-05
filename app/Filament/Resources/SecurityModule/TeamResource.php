<?php

namespace App\Filament\Resources\SecurityModule;

use App\Models\Team;
use App\Models\User;
use Filament\Tables;
use App\Enums\RoleEnum;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Blade;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TernaryFilter;
use BezhanSalleh\FilamentShield\Support\Utils;
use Filament\Tables\Actions\Action as TableAction;
use App\Filament\Resources\SecurityModule\TeamResource\Pages;
use App\Filament\Resources\SecurityModule\TeamResource\Pages\CreateTeam;
use App\Filament\Resources\SecurityModule\TeamResource\RelationManagers\TeamMembersRelationManager;

class TeamResource extends Resource
{
    protected static ?string $model = Team::class;

    protected static ?string $modelLabel = 'Entité';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $pluralModelLabel = 'Entités';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-group';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(3)
                    ->schema([
                        Section::make("Infos entités")
                            ->schema([
                                TextInput::make("name")
                                    ->required()
                                    ->unique(table: Team::class, column: 'name', ignoreRecord: true)
                                    ->label("Nom entité")
                                    ->maxLength(150)
                                    ->columnSpan(1),
                                Textarea::make("description")
                                    ->label("Description")
                                    ->columnSpan(1),
                            ])
                            ->columnSpan(2)
                            ->columns(2),
                        Section::make("Manager")
                            ->description("Selectionnez le manager de l'entité")
                            ->columnSpan(1)
                            ->schema([
                                Select::make('user_id')
                                    ->hiddenLabel()
                                    ->allowHtml()
                                    ->validationAttribute("Manager")
                                    ->options(
                                        function (?Team $record) {
                                            $options = User::withoutRoles([RoleEnum::SUPER_ADMIN->getLabel(), RoleEnum::COURSER->getLabel()])
                                                ->where('is_active', true)
                                                ->get();

                                            if ($record && $record->manager) {
                                                unset($options[$record->manager->id]);
                                            }

                                            return $options
                                                ->map(
                                                    fn ($user) => [
                                                        'value' => $user->id,
                                                        'label' => Blade::render(
                                                            '<x-filament::avatar
                                                                src="' . filament()->getUserAvatarUrl($user) . '"
                                                            size="sm"
                                                            class="inline mr-2"
                                                            /> ' . $user?->name . ' - ' . $user->email
                                                        ),
                                                    ]
                                                )
                                                ->pluck('label', 'value');
                                        }
                                    )
                                    ->getSearchResultsUsing(fn (string $search) => User::withoutRoles([RoleEnum::SUPER_ADMIN->getLabel(), RoleEnum::COURSER->getLabel()])
                                        ->where('is_active', true)
                                        ->whereAny([
                                            'email',
                                            'name',
                                            'phone'
                                        ], 'LIKE', "%{$search}%")
                                        ->limit(50)->get()->map(
                                            fn ($user) => [
                                                'value' => $user->id,
                                                'label' => Blade::render(
                                                    '<x-filament::avatar
                                                        src="' . filament()->getUserAvatarUrl($user) . '"
                                                    size="sm"
                                                    class="inline mr-2"
                                                    /> ' . $user?->name . ' - ' . $user->email
                                                ),
                                            ]
                                        )
                                        ->pluck('label', 'value'))
                                    ->searchable(['name', 'email'])
                                    ->searchPrompt(__('Rechercher par nom ou email'))
                                    ->required()
                                    ->preload()
                                    ->reactive()
                                    ->afterStateHydrated(
                                        function (Select $component, ?string $state, ?Team $record) {
                                            if ($record && $record->manager) {
                                                $component->state($record->manager->id);
                                            }
                                        }
                                    )
                                    ->native(false)
                            ])
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->emptyStateActions([
                TableAction::make('create')
                    ->label(fn () => __('Ajouter une entité'))
                    ->url(route(CreateTeam::getRouteName()))
                    ->icon('heroicon-m-plus')
                    ->button()
                    ->visible(fn (): bool => auth()->user()->can('create_security::module::team')),
            ])
            ->columns([
                TextColumn::make('name')
                    ->label('Nom entité')
                    ->wrap()
                    ->searchable(),
                ViewColumn::make('manager')
                    ->label('Manager')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query
                            ->whereHas('manager', fn ($q) => $q->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%"));
                    })
                    ->view('filament.tables.columns.user-info'),
                TextColumn::make('description')
                    ->wrap()
                    ->label('Description')
                    ->searchable(),
                TextColumn::make('members_count')
                    ->counts('members')
                    ->badge()
                    ->sortable()
                    ->label('Membres'),
                TextColumn::make('is_active')
                    ->label('Statut')
                    ->badge()
                    ->formatStateUsing(fn ($state): string =>  $state ? 'Actif' : 'Inactif')
                    ->color(fn ($state): string => $state ? 'success' : 'danger')
            ])
            ->filters([
                TernaryFilter::make('Entités')
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
                Tables\Actions\ViewAction::make(),
            ])
            ->striped()
            ->defaultSort('updated_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            TeamMembersRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTeams::route('/'),
            'create' => Pages\CreateTeam::route('/create'),
            'view' => Pages\ViewTeam::route('/{record}'),
            'edit' => Pages\EditTeam::route('/{record}/edit'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return Utils::isResourceNavigationGroupEnabled()
            ? __('filament-shield::filament-shield.nav.group')
            : '';
    }
}

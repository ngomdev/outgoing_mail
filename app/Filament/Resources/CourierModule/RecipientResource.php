<?php

namespace App\Filament\Resources\CourierModule;


use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Recipient;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Forms\Components\Actions\Action;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;
use Filament\Tables\Actions\Action as TableAction;
use App\Filament\Resources\CourierModule\RecipientResource\Pages\EditRecipient;
use App\Filament\Resources\CourierModule\RecipientResource\Pages\ViewRecipient;
use App\Filament\Resources\CourierModule\RecipientResource\Pages\ListRecipients;
use App\Filament\Resources\CourierModule\RecipientResource\Pages\CreateRecipient;

class RecipientResource extends Resource
{
    protected static ?string $model = Recipient::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $modelLabel = 'Destinataire';
    protected static ?string $pluralModelLabel = 'Destinataires';
    protected static bool $shouldRegisterNavigation = true;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(3)
                    ->schema([
                        Section::make('Infos destinataire')
                            ->columnSpan(1)
                            ->schema([
                                TextInput::make('name')
                                    ->string()
                                    ->label('Nom entité')
                                    ->required()
                                    ->unique(ignoreRecord: true),
                                TextInput::make('email')
                                    ->email()
                                    ->label('Email')
                                    ->required()
                                    ->unique(ignoreRecord: true),
                                PhoneInput::make('phone')
                                    ->initialCountry('sn')
                                    ->preferredCountries(['sn'])
                                    ->autoPlaceholder('xx xxx xx xx')
                                    ->placeholder('xx xxx xx xx')
                                    ->unique(ignoreRecord: true)
                                    ->label('Téléphone')
                                    ->required(),
                                Textarea::make('address')
                                    ->required()
                                    ->label('Adresse')
                            ]),
                        Section::make('Contacts')
                            ->columnSpan(2)
                            ->schema([
                                Repeater::make('contacts')
                                    ->relationship()
                                    ->hiddenLabel()
                                    ->itemLabel(fn (array $state): ?string => $state['name'] ?? null)
                                    ->addActionLabel(fn () => __('Ajouter contact'))
                                    ->addAction(
                                        fn (Action $action) => $action->extraAttributes(fn ($component) => [
                                            'x-on:click' => new HtmlString('$dispatch(\'repeater-collapse\', \'' . $component->getStatePath() . '\')')
                                        ])
                                    )
                                    ->collapsible()
                                    ->collapsed(fn (?Recipient $record) => $record ? true : false)
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('name')
                                            ->string()
                                            ->label('Nom')
                                            ->required()
                                            ->live()
                                            ->unique(ignoreRecord: true),
                                        TextInput::make('email')
                                            ->email()
                                            ->label('Email')
                                            ->required()
                                            ->unique(ignoreRecord: true),
                                        PhoneInput::make('phone')
                                            ->initialCountry('sn')
                                            ->preferredCountries(['sn'])
                                            ->autoPlaceholder('xx xxx xx xx')
                                            ->placeholder('xx xxx xx xx')
                                            ->rules([
                                                function () {
                                                    return function ($attribute, $value, $fail) {
                                                        if (!preg_match('/^\+\d[\d\s]{7,}$/', $value)) {
                                                            $fail('Numéro de téléphone invalide');
                                                        }
                                                    };
                                                },
                                            ])
                                            ->unique(ignoreRecord: true)
                                            ->label('Téléphone')
                                            ->required(),
                                        TextInput::make('entity')
                                            ->label('Sous-entité')
                                    ])
                            ])
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->emptyStateActions([
                TableAction::make('create')
                    ->label(fn () => __('Ajouter un destinataire'))
                    ->url(route(CreateRecipient::getRouteName()))
                    ->icon('heroicon-m-plus')
                    ->button(),

            ])
            ->columns([
                TextColumn::make('name')
                    ->label('Nom')
                    ->searchable(),
                ViewColumn::make('contact')
                    ->label('Contact')
                    ->searchable(['email', 'phone'])
                    ->view('filament.tables.columns.contact'),
                TextColumn::make('address')
                    ->label('Adresse')
                    ->wrap()
                    ->limit(50)
                    ->default("-")
                    ->tooltip(
                        function (TextColumn $column): ?string {
                            $state = $column->getState();

                            if (strlen($state) <= $column->getCharacterLimit()) {
                                return null;
                            }
                            return $state;
                        }
                    )
                    ->searchable(),
                TextColumn::make('is_active')
                    ->label('Statut')
                    ->badge()
                    ->formatStateUsing(fn ($state): string =>  $state ? 'Actif' : 'Inactif')
                    ->color(fn ($state): string => $state ? 'success' : 'danger')

            ])
            ->filters([
                TernaryFilter::make('Destinataires')
                    ->placeholder('Tout')
                    ->trueLabel('Actifs')
                    ->falseLabel('Inactifs')
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRecipients::route('/'),
            'create' => CreateRecipient::route('/create'),
            'view' => ViewRecipient::route('/{record}'),
            'edit' => EditRecipient::route('/{record}/edit'),
        ];
    }

    public static function getSlug(): string
    {
        return '/recipients';
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Gestion Courriers');
    }
}

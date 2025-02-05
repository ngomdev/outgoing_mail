<?php

namespace App\Filament\Resources\DocumentModule;

use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Forms\Components\Grid;
use Filament\Tables\Actions\Action;
use App\Models\ExternalDocInitiator;
use Illuminate\Support\Facades\Blade;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TernaryFilter;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use App\Filament\Resources\DocumentModule\ExternalInitiatorResource\Pages\ManageExternalInitiators;

class ExternalInitiatorResource extends Resource
{
    protected static ?string $model = ExternalDocInitiator::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-top-right-on-square';
    protected static ?string $modelLabel = 'Initiateur externe';
    protected static ?string $pluralModelLabel = 'Initiateurs externes';
    protected static bool $shouldRegisterNavigation = true;
    protected static ?string $slug = 'external-doc-initiators';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(5)
                    ->schema([
                        FileUpload::make('logo_url')
                            ->disableLabel()
                            ->avatar()
                            ->validationAttribute("logo/avatar")
                            ->placeholder(fn () => Blade::render(
                                "Glissez le logo ici ou <span class='filepond--label-action'>Parcourir</span>"
                            ))
                            ->disk('public')
                            ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                                $originalName = $file->getClientOriginalName();
                                $extension = pathinfo($originalName, PATHINFO_EXTENSION);
                                $maxId = ExternalDocInitiator::max("id");
                                $newId = $maxId++;
                                return (string) "external-initiator-uploads/$newId/logo_url." . $extension;
                            })
                            ->columnSpan(1),
                        Section::make('')
                            ->compact()
                            ->columns(2)
                            ->columnSpan(4)
                            ->schema([
                                TextInput::make('name')
                                    ->required()
                                    ->label("Nom")
                                    ->rules(["string"])
                                    ->maxLength(150)
                                    ->translateLabel(),
                                TextInput::make('email')
                                    ->required()
                                    ->email()
                                    ->unique(table: ExternalDocInitiator::class, column: 'email', ignoreRecord: true)
                                    ->label("Email")
                                    ->translateLabel(),
                                PhoneInput::make('phone')
                                    ->initialCountry('sn')
                                    ->autoPlaceholder('xx xxx xx xx')
                                    ->placeholder('xx xxx xx xx')
                                    ->preferredCountries(['sn'])
                                    ->unique(table: ExternalDocInitiator::class, column: 'phone', ignoreRecord: true)
                                    ->label("Téléphone"),
                                Textarea::make('address')
                                    ->label("Adresse"),
                            ])
                    ])

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\Layout\Split::make([
                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\ImageColumn::make('logo_url')
                            ->circular(),
                        Tables\Columns\TextColumn::make('name')
                            ->searchable()
                            ->weight('medium')
                            ->alignLeft(),
                        Tables\Columns\TextColumn::make('address')
                            ->label('Adresse')
                            ->icon('heroicon-s-building-office-2')
                            ->color('gray')
                            ->wrap()
                            ->alignLeft(),
                    ])->space(),

                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\TextColumn::make('email')
                            ->label('Adresse email')
                            ->icon('heroicon-s-envelope')
                            ->searchable()
                            ->color('gray')
                            ->alignLeft(),
                        Tables\Columns\TextColumn::make('phone')
                            ->icon('heroicon-s-phone')
                            ->label('Téléphone')
                            ->color('gray')
                            ->alignLeft(),
                        Tables\Columns\TextColumn::make('is_active')
                            ->label('Statut')
                            ->badge()
                            ->formatStateUsing(fn ($state): string =>  $state ? 'Actif' : 'Inactif')
                            ->color(fn ($state): string => $state ? 'success' : 'danger')
                    ])->space(2),
                ])->from('md'),
            ])
            ->filters([
                TernaryFilter::make('Initiateurs Externes')
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
                Tables\Actions\EditAction::make()
                    ->visible(fn (?ExternalDocInitiator $record) => $record?->is_active),
                Action::make('deactivate')
                    ->requiresConfirmation()
                    ->label(fn (?ExternalDocInitiator $record): string => $record?->is_active ? 'Désactiver' : 'Activer')
                    ->icon(fn (?ExternalDocInitiator $record): string => $record?->is_active ? 'heroicon-m-lock-closed' : 'heroicon-m-lock-open')
                    ->color(fn (?ExternalDocInitiator $record) => $record?->is_active ? Color::Red : Color::Emerald)
                    ->modalDescription(fn (?ExternalDocInitiator $record): string => $record?->is_active ? "Êtes-vous sur de vouloir désactiver cet initiateur externe?" : "Êtes-vous sur de vouloir activer cet initiateur externe?")
                    ->action(function (?ExternalDocInitiator $record) {
                        if ($record) {
                            $record->is_active = !$record->is_active;
                            $record->save();

                            Notification::make()
                                ->success()
                                ->title(fn (?ExternalDocInitiator $record) => $record->is_active ? __('Activation initiateur externe') : __('Désactivation initiateur externe'))
                                ->body(fn (?ExternalDocInitiator $record) => $record->is_active ? "Initiateur externe activée avec succés!"  : "Initiateur externe désactivée avec succés!")
                                ->persistent()
                                ->send();
                        }
                    }),
            ])
            ->defaultSort('updated_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageExternalInitiators::route('/'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Production Documents');
    }
}

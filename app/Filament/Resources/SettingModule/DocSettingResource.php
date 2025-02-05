<?php

namespace App\Filament\Resources\SettingModule;

use Filament\Tables;
use App\Models\Setting;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Enums\SettingModule;
use Filament\Resources\Resource;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Forms\Components\ToggleButtons;
use App\Filament\Resources\SettingModule\DocSettingResource\Pages\ManageDocSettings;

class DocSettingResource extends Resource
{
    protected static ?string $model = Setting::class;
    protected static ?int $navigationSort = 2;
    protected static ?string $slug = 'settings/documents';
    protected static ?string $navigationLabel = 'Documents';
    protected static ?string $modelLabel = 'Paramètre documents';
    protected static ?string $pluralModelLabel = 'Paramètres documents';
    protected static bool $shouldRegisterNavigation = true;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make()
                    ->schema([
                        ToggleButtons::make('is_active')
                            ->label('Activer/ Désactiver paramètre')
                            ->boolean(trueLabel: 'Activer', falseLabel: 'Desactiver')
                            ->grouped()
                            ->helperText('Activez le paramètre pour saisir une valeur. La valeur par défaut sera utilisée si vous désactivez le paramètre.')
                            ->live()
                            ->columnspanFull(),
                        TextInput::make('value')
                            ->label(__('Valeur'))
                            ->suffix(fn(?Setting $record) => $record?->unit)
                            ->disabled(fn(Get $get) => $get('is_active') == false)
                            ->helperText(fn(?Setting $record): HtmlString => new HtmlString('<div class="inline-flex items-center justify-center space-x-1 rtl:space-x-reverse min-h-6 px-2 py-0.5 text-md font-medium tracking-tight rounded-xl text-white bg-primary-500"> par défaut: ' . ($record->default_value ? $record->default_value : 'pas de valeur par défaut') . '</div>')),
                    ]),

                TextInput::make('display_name')
                    ->label(__('Nom'))
                    ->disabled(),

                Textarea::make('description')
                    ->label('Description'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('display_name')
                    ->label(__('Nom'))
                    ->wrap()
                    ->searchable(),
                TextColumn::make('value')
                    ->label(__('Valeur'))
                    ->formatStateUsing(fn($state, Setting $record) => $record->is_active ? $state : $record->default_value)
                    ->searchable()
                    ->description(fn(Setting $record): HtmlString => new HtmlString('<div class="inline-flex items-center justify-center space-x-1 rtl:space-x-reverse min-h-6 px-2 py-0.5 text-xs font-medium tracking-tight rounded-xl text-white bg-primary-500">' . $record->unit . '</div>')),
                TextColumn::make('description')
                    ->label(__('Description'))
                    ->wrap(),
                IconColumn::make('is_active')
                    ->label(__('Actif/ Inactif'))
                    ->boolean()
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label(__('Status'))
                    ->trueLabel(__('Actifs'))
                    ->falseLabel(__('Inactifs'))
                    ->placeholder(__('Tout'))
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->defaultSort('updated_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageDocSettings::route('/'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Paramètres');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('module', SettingModule::DOCUMENT);
    }
}

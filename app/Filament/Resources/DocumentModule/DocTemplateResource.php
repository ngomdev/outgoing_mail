<?php

namespace App\Filament\Resources\DocumentModule;

use Filament\Tables;
use App\Enums\DocType;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\DocTemplate;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\DocumentModule\DocTemplateResource\Pages;

class DocTemplateResource extends Resource
{
    protected static ?string $model = DocTemplate::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document';
    protected static ?string $modelLabel = 'Modéle Document';
    protected static ?string $pluralModelLabel = 'Modéles Document';
    protected static bool $shouldRegisterNavigation = true;
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Select::make('doc_type')
                            ->label(fn() => __('Type de document'))
                            ->options(DocType::class)
                            ->selectablePlaceholder(false)
                            ->disableOptionWhen(fn (string $value): bool => DocTemplate::where("doc_type", $value)->count() > 0)
                            ->getSearchResultsUsing(fn(string $search) => DocType::search($search))
                            ->native(false),

                        TextInput::make('name')
                            ->label(fn() => __('Nom du modéle'))
                            ->unique(table: DocTemplate::class, column: 'name', ignoreRecord: true)
                            ->maxLength(255),

                        FileUpload::make('file_path')
                            ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                            ->preserveFilenames()
                            ->disk('templates')
                            ->validationAttribute("Fichier")
                            ->maxSize(1000000000)
                            ->required()
                            ->label('Fichier')
                            ->previewable()
                            ->downloadable()
                            ->openable(),

                    ])
                    ->columns(2)
                    ->columnSpan('full')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('doc_type')
                    ->label(fn() => __('Type modèle'))
                    ->badge()
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label(fn() => __('Nom modèle'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(fn() => __('Date création'))
                    ->dateTime('d M Y - H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(fn() => __('Dernière mise à jour'))
                    ->dateTime('d M Y - H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
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
            'index' => Pages\ListDocTemplates::route('/'),
            'create' => Pages\CreateDocTemplate::route('/create'),
            'view' => Pages\ViewDocTemplate::route('/{record}'),
            'edit' => Pages\EditDocTemplate::route('/{record}/edit'),
        ];
    }

    public static function getSlug(): string
    {
        return '/doc-templates';
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Production Documents');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}

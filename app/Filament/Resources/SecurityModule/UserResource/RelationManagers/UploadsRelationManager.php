<?php

namespace App\Filament\Resources\SecurityModule\UserResource\RelationManagers;


use Filament\Tables;
use App\Models\Upload;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Blade;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\RelationManagers\RelationManager;

class UploadsRelationManager extends RelationManager
{
    protected static string $relationship = 'uploads';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make('file_path')
                    ->label("Signature")
                    ->image()
                    ->imageEditorEmptyFillColor('#fff')
                    ->openable()

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('type')
            ->columns([
                Tables\Columns\TextColumn::make('type')
                    ->description(fn (Upload $record) => new HtmlString(Blade::render("
                        <x-filament::badge
                            class='w-fit'
                            color=" . ($record->is_active ? 'success' : 'danger') . "
                            icon=" . ($record->is_active ? 'heroicon-o-check' : 'heroicon-o-x-mark') . ">"
                        . ($record->is_active ? 'Actif' : 'Inactif') . "
                        </x-filament::badge>
                    "))),
                Tables\Columns\ImageColumn::make('file_path')
                    ->label('Média')
                    ->width(200)
                    ->square(),
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Actif/ Inactif')
                    ->disabled(function (Upload $record, RelationManager $livewire) {
                        $latestActive = $livewire->getOwnerRecord()->uploads()->where([
                            'type' => $record->type->value,
                            'is_active' => true
                        ])->latest()->first();

                        return $latestActive && ($record->type->value === $latestActive->type->value)  && ($record->id !== $latestActive->id);
                    }),
            ])
            ->filters([
                //
            ])
            ->headerActions([])
            ->actions([
            ])
            ->emptyStateDescription('Les paraphes/ signatures que vous ajouterez apparaitront ici.')
            ->defaultSort('created_at', 'desc');
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('Signatures & paraphes');
    }

    public function isReadOnly(): bool
    {
        return true;
    }
}

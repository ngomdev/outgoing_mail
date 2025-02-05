<?php

namespace App\Filament\Resources\CourierModule\RecipientResource\Pages;


use Filament\Actions;
use Filament\Forms\Form;
use Filament\Actions\Action;
use Filament\Support\Colors\Color;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Grid;
use Illuminate\Support\Facades\Blade;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\ViewField;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Forms\Components\Placeholder;
use App\Filament\Resources\CourierModule\RecipientResource;

class ViewRecipient extends ViewRecord
{
    protected static string $resource = RecipientResource::class;


    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->visible(fn () => $this->record->is_active),
            Action::make('deactivate')
                ->requiresConfirmation()
                ->label(fn (): string => $this->record->is_active ? 'Désactiver' : 'Activer')
                ->icon(fn (): string => $this->record->is_active ? 'heroicon-m-lock-closed' : 'heroicon-m-lock-open')
                ->color(fn () => $this->record->is_active ? Color::Red : Color::Emerald)
                ->modalDescription(fn (): string => $this->record->is_active ? "Êtes-vous sur de vouloir désactiver ce destinataire?" : "Êtes-vous sur de vouloir activer ce destinataire?")
                ->action(function () {
                    $this->record->is_active = !$this->record->is_active;
                    $this->record->save();

                    Notification::make()
                        ->success()
                        ->title(fn () => $this->record->is_active ? __('Activation destinataire') : __('Désactivation destinataire'))
                        ->body(fn () => $this->record->is_active ? "Destinataire activée avec succés!"  : "Destinataire désactivée avec succés!")
                        ->persistent()
                        ->send();
                }),
        ];
    }


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(3)
                    ->schema([
                        Section::make('Infos destinataire')
                            ->columnSpan(1)
                            ->schema([
                                Placeholder::make('is_active')
                                    ->label(fn () => __('Statut'))
                                    ->content(
                                        fn () => new HtmlString(
                                            Blade::render(
                                                "<x-filament::badge
                                class='w-fit'
                                size='lg'
                                color='danger'
                                icon='heroicon-m-x-circle'>
                                Inactif
                            </x-filament::badge>
                        "
                                            )
                                        )
                                    )
                                    ->visible(fn () => !$this->record->is_active),
                                Placeholder::make('name')
                                    ->label(fn () => new HtmlString("<p class='text-gray-500'>Nom entité</p>"))
                                    ->content(fn ($record) => new HtmlString(Blade::render("
                                            <h3 class='flex items-center mb-1 text-sm font-semibold text-gray-900 dark:text-white'>
                                                " . $record->name . "
                                            </h3>
                                        ")))
                                    ->columnSpan(1),
                                Placeholder::make('email')
                                    ->label(fn () => new HtmlString("<p class='text-gray-500'>Email</p>"))
                                    ->content(fn ($record) => new HtmlString(Blade::render("
                                            <h3 class='flex items-center mb-1 text-sm font-semibold text-gray-900 dark:text-white'>
                                                " . $record->email . "
                                            </h3>
                                        ")))
                                    ->columnSpan(1),
                                Placeholder::make('phone')
                                    ->label(fn () => new HtmlString("<p class='text-gray-500'>Téléphone</p>"))
                                    ->content(fn ($record) => new HtmlString(Blade::render("
                                            <h3 class='flex items-center mb-1 text-sm font-semibold text-gray-900 dark:text-white'>
                                                " . $record->phone . "
                                            </h3>
                                        ")))
                                    ->columnSpan(1),
                                Placeholder::make('address')
                                    ->label(fn () => new HtmlString("<p class='text-gray-500'>Adresse</p>"))
                                    ->content(fn ($record) => new HtmlString(Blade::render("
                                            <h3 class='flex items-center mb-1 text-sm font-semibold text-gray-900 dark:text-white'>
                                                " . $record->address . "
                                            </h3>
                                        ")))
                                    ->columnSpan(1),
                            ]),

                        Section::make('Contacts')
                            ->columnSpan(2)
                            ->schema([
                                Repeater::make('contacts')
                                    ->relationship()
                                    ->hiddenLabel()
                                    ->simple(
                                        ViewField::make('contacts_view')
                                            ->view('filament.forms.components.contact-view')
                                            ->columnspanFull()
                                    )
                            ])
                    ]),
            ]);
    }
}

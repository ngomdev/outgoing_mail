<?php

namespace App\Filament\Resources\SecurityModule\TeamResource\Pages;

use App\Models\Team;
use App\Models\User;
use App\Enums\RoleEnum;
use Livewire\Component as Livewire;
use Filament\Forms\Form;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\ActionGroup;
use Filament\Support\Colors\Color;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Grid;
use Filament\Support\Enums\MaxWidth;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Blade;
use Filament\Forms\Components\Section;
use Filament\Support\Enums\ActionSize;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Repeater;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\ViewField;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Forms\Components\Placeholder;
use App\Filament\Resources\SecurityModule\TeamResource;

class ViewTeam extends ViewRecord
{
    protected static string $resource = TeamResource::class;


    protected function getHeaderActions(): array
    {
        return [
            ActionGroup::make([
                Action::make('addMembers')
                    ->icon('heroicon-o-plus')
                    ->color(Color::Blue)
                    ->label('Ajouter membres')
                    ->modalDescription("Vous pouvez ajouter plusieurs utilisateurs en même temps")
                    ->modalIcon('heroicon-o-user-group')
                    ->modalWidth(MaxWidth::Large)
                    ->slideover()
                    ->form([
                        Repeater::make('teamMembers')
                            ->addActionLabel('Ajouter membre')
                            ->hiddenLabel()
                            ->defaultItems(1)
                            ->simple(
                                Select::make('user_id')
                                    ->options(
                                        function () {
                                            $options = User::where('is_active', true)
                                                ->withoutRoles([RoleEnum::SUPER_ADMIN->getLabel(), RoleEnum::ADMIN->getLabel(), RoleEnum::COURSER->getLabel()])
                                                ->whereNotIn('id', $this->record->members->pluck('id'))->get();

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
                                    ->getSearchResultsUsing(fn (string $search) => User::where('is_active', true)
                                        ->whereNotIn('id', $this->record->members->pluck('id'))
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
                                    ->hiddenLabel()
                                    ->validationAttribute("Collaborateur")
                                    ->allowHtml()
                                    ->searchable()
                                    ->searchPrompt(__('Rechercher par nom ou email'))
                                    ->getOptionLabelUsing(
                                        fn (Model $record) => Blade::render(
                                            '<x-filament::avatar
                                            src="' . filament()->getUserAvatarUrl($record) . '"
                                            size="sm"
                                            class="inline mr-2"
                                        /> ' . $record?->name . ' - ' . $record->email
                                        )
                                    )
                                    ->required()
                                    ->preload()
                                    ->dehydrateStateUsing(fn (string $state): string => strip_tags($state))
                                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                    ->native(false)
                            )
                    ])
                    ->action(function (array $data, Livewire $livewire) {
                        $this->record->members()->attach($data["teamMembers"]);

                        Notification::make()
                            ->success()
                            ->title(__('Succès !'))
                            ->body("Collaborateur(s) ajouté(s) à entité")
                            ->persistent()
                            ->send();

                        $livewire->dispatch("refresh$");
                    })
                    ->visible(fn () => $this->record->is_active),

                EditAction::make()
                    ->visible(fn () => $this->record->is_active),
                Action::make('deactivate')
                    ->requiresConfirmation()
                    ->label(fn (): string => $this->record->is_active ? 'Désactiver' : 'Activer')
                    ->icon(fn (): string => $this->record->is_active ? 'heroicon-m-lock-closed' : 'heroicon-m-lock-open')
                    ->color(fn () => $this->record->is_active ? Color::Red : Color::Emerald)
                    ->modalDescription(fn (): string => $this->record->is_active ? "Êtes-vous sur de vouloir désactiver cette entité?" : "Êtes-vous sur de vouloir activer cette entité?")
                    ->action(function (Action $action) {
                        if ($this->record->members->count() > 0) {
                            Notification::make()
                                ->warning()
                                ->title(__('Oups!'))
                                ->body("Veuillez transférer les membres de cette entité vers une autre avant de procéder à sa désactivation.")
                                ->persistent()
                                ->send();

                            $action->cancel();
                        } else {
                            $this->record->is_active = !$this->record->is_active;
                            $this->record->save();

                            Notification::make()
                                ->success()
                                ->title(fn () => $this->record->is_active ? __('Activation entité') : __('Désactivation entité'))
                                ->body(fn () => $this->record->is_active ? "Entité activée avec succés!"  : "Entité désactivée avec succés!")
                                ->persistent()
                                ->send();
                        }
                    }),
            ])
                ->label('Actions')
                ->icon('heroicon-m-ellipsis-vertical')
                ->size(ActionSize::Small)
                ->color('primary')
                ->button()

        ];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(3)
                    ->schema([
                        Section::make()
                            ->compact()
                            ->schema([
                                Fieldset::make('Infos entité')
                                    ->columns(3)
                                    ->extraAttributes(['class' => 'shadow-md'])
                                    ->schema([
                                        Placeholder::make('name')
                                            ->label(fn () => new HtmlString("<p class='text-gray-500'>Nom entité</p>"))
                                            ->content(fn ($record) => new HtmlString(Blade::render("
                                            <h3 class='flex items-center mb-1 text-sm font-medium text-gray-900 dark:text-white'>
                                                " . $record->name . "
                                            </h3>
                                        "))),

                                        Placeholder::make('description')
                                            ->label(fn () => new HtmlString("<p class='text-gray-500'>Description</p>"))
                                            ->content(fn ($record) => new HtmlString(Blade::render("
                                            <h3 class='flex items-center mb-1 text-sm font-medium text-gray-900 dark:text-white'>
                                                " . $record->description . "
                                            </h3>
                                        "))),

                                        Placeholder::make('is_active')
                                            ->label(fn () => __('Statut'))
                                            ->content(
                                                function (Team $record) {
                                                    $color = $record->is_active ? 'success' : 'danger';
                                                    $iconStatus = $record->is_active ? 'heroicon-m-check-badge' : 'heroicon-m-x-circle';
                                                    $status = $record->is_active ? 'Actif' : 'Inactif';
                                                    return new HtmlString(
                                                        Blade::render(
                                                            "
                                        <x-filament::badge
                                            class='w-fit'
                                            size='lg'
                                            color='{$color}'
                                            icon='{$iconStatus}'>
                                            {$status}
                                        </x-filament::badge>
                                    "
                                                        )
                                                    );
                                                }
                                            )
                                            ->visible(fn() => !$this->record->is_active),
                                        Placeholder::make('created_at')
                                            ->label(fn () => new HtmlString("<p class='text-gray-500'>Date de création</p>"))
                                            ->content(fn ($record) => new HtmlString(Blade::render("
                                            <h3 class='flex items-center mb-1 text-sm font-medium text-gray-900 dark:text-white'>
                                                " . $record->created_at->translatedFormat('d M Y - H:i') . "
                                            </h3>
                                        "))),
                                    ]),
                            ])
                            ->columnSpan(2),
                        Section::make()
                            ->compact()
                            ->schema([
                                ViewField::make('Manager')
                                    ->view('filament.forms.components.doc-authors-view')
                                    ->viewData([
                                        'type' => 'manager'
                                    ]),
                            ])
                            ->columnSpan(1)
                    ])
            ]);
    }
}

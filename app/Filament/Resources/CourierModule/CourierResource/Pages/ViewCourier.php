<?php

namespace App\Filament\Resources\CourierModule\CourierResource\Pages;

use Exception;
use App\Models\User;
use App\Enums\RoleEnum;
use Filament\Forms\Get;
use Filament\Forms\Form;
use App\Models\CourierUser;
use App\Enums\CourierStatus;
use App\Enums\RecipientType;
use Filament\Actions\EditAction;
use Filament\Support\Colors\Color;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Grid;
use Illuminate\Contracts\View\View;
use Filament\Support\Enums\MaxWidth;
use App\Jobs\FirebaseNotificationJob;
use Illuminate\Support\Facades\Blade;
use Filament\Forms\Components\Section;
use Filament\Support\Enums\ActionSize;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use App\Jobs\CourierUserNotificationJob;
use App\Services\DocManipulationService;
use Filament\Forms\Components\ViewField;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Placeholder;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Actions\Action as HeaderAction;
use Livewire\Component as Livewire;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use App\Filament\Resources\CourierModule\CourierResource;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;


class ViewCourier extends ViewRecord
{
    protected static string $resource = CourierResource::class;

    protected function getHeaderActions(): array
    {
        return [
            HeaderAction::make('startProcess')
                ->requiresConfirmation()
                ->modalContent(view('filament.pages.actions.start-process', ['record' => $this->record]))
                ->modalDescription('Démarrer le process et notifier les coursiers ?')
                ->modalContent(
                    fn (HeaderAction $action): View => view(
                        'filament.pages.actions.start-courier-process',
                    )
                )
                ->label('Démarrer process')
                ->icon('heroicon-m-play')
                ->color(Color::Blue)
                ->action(
                    function () {
                        DB::transaction(
                            function () {
                                try {

                                    $this->record->status = CourierStatus::INITIATED;

                                    $this->record->courier_created_at = now();

                                    // Get pdf document
                                    if ($this->record->doc_path) {
                                        $docPath = public_path("storage/{$this->record->doc_path}");
                                        // Add courier number to pdf
                                        (new DocManipulationService())->addCourierNoToPdf($docPath, $this->record->courier_number);
                                    }

                                    $this->record->save();

                                    // Update assignment date for coursers
                                    $this->record->coursers->each(
                                        function ($courser) {
                                            $courser->update(
                                                [
                                                    'assignment_date' => now(),
                                                    'status' => CourierStatus::INITIATED->value
                                                ]
                                            );
                                        }
                                    );

                                    // Send notification to coursers of this courier
                                    CourierUserNotificationJob::dispatch($this->record);

                                    // Send firebase notification to all courier users
                                    FirebaseNotificationJob::dispatch($this->record->coursers, "assigné");

                                    Notification::make()
                                        ->success()
                                        ->title(__('Success'))
                                        ->body(__('Process démarré! Les coursiers vont être notifiés!'))
                                        ->persistent()
                                        ->send();
                                } catch (Exception $e) {
                                    Notification::make()
                                        ->danger()
                                        ->title(__('Oups'))
                                        ->body($e->getMessage())
                                        ->persistent()
                                        ->send();
                                }
                            }
                        );
                    }
                )
                ->visible(fn () => $this->record->status === CourierStatus::DRAFT && $this->record->mainRecipient && $this->hasCoursersAssigned()),

            EditAction::make()
                ->visible(fn () => auth()->user()->can('create_courier::module::courier') && $this->record->status->getRank() <= 3),
            HeaderAction::make('cancelCourier')
                ->label('Annuler courrier')
                ->icon('heroicon-m-x-circle')
                ->color(fn () => Color::Red)
                ->action(function (Livewire $livewire) {

                    $this->record->status = CourierStatus::CANCELLED;
                    $this->record->save();

                    if ($this->record->coursers) {
                        $this->record->coursers->each(function ($courierUser) {
                            $courierUser->update([
                                'status' => CourierStatus::CANCELLED
                            ]);
                        });
                    }

                    $livewire->dispatch("refresh$");

                    return Notification::make()
                        ->success()
                        ->title(__('Success'))
                        ->body(__('Vous avez annulé le courrier!'))
                        ->persistent()
                        ->send();
                })
                ->requiresConfirmation()
                ->visible(fn () => auth()->user()->can('create_courier::module::courier') && $this->record->status->getRank() <= 3),
        ];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema(
                [
                    ViewField::make('overview')
                        ->view('filament.forms.components.courier-module.courier-overview')
                        ->columnspanFull(),
                    Grid::make(4)
                        ->schema(
                            [
                                Grid::make()
                                    ->columnSpan(3)
                                    ->schema(
                                        [
                                            Grid::make()
                                                ->schema(
                                                    [
                                                        Placeholder::make('main_recipient_helper')
                                                            ->label(
                                                                fn () => new HtmlString("<p class='text-gray-500'>Vous n'avez pas encore défini de destinataire. Cliquez sur le bouton <a href='/courier/" . $this->record->id . "/edit' class='text-cyan-400'>Modifier</a> pour compléter le courrier.</p>")
                                                            )
                                                            ->columnspanFull()
                                                            ->visible(fn () => !$this->record->mainRecipient),

                                                        ViewField::make('main_recipient')
                                                            ->label("Destinataire")
                                                            ->view('filament.forms.components.courier-module.recipient')
                                                            ->registerActions(
                                                                [
                                                                    Action::make('setPickupDate')
                                                                        ->icon('heroicon-m-calendar-days')
                                                                        ->color('amberLight')
                                                                        ->size(ActionSize::Small)
                                                                        ->label('Date levée')
                                                                        ->requiresConfirmation()
                                                                        ->modalIcon('heroicon-m-calendar-days')
                                                                        ->modalDescription('Cette action est irreversible!')
                                                                        ->form(
                                                                            [
                                                                                DateTimePicker::make('pickup_date')
                                                                                    ->required()
                                                                                    ->hiddenLabel()
                                                                                    ->native(false)
                                                                                    ->closeOnDateSelection()
                                                                                    ->minDate(now()->subYears(1))
                                                                                    ->afterOrEqual(
                                                                                        function () {
                                                                                            return $this->record->coursers->where('type', RecipientType::MAIN)->first()?->assignment_date;
                                                                                        }
                                                                                    )
                                                                                    ->beforeOrEqual(now())
                                                                                    ->validationMessages(
                                                                                        [
                                                                                            "after_or_equal" => "La date de levée ne peut pas être antérieure à la date d'assignation",
                                                                                            "before_or_equal" => "La date de levée ne peut pas être dans le future XD",
                                                                                        ]
                                                                                    )
                                                                                    ->default(now())
                                                                                    ->displayformat('d M Y - H:i')
                                                                            ]
                                                                        )
                                                                        ->action(
                                                                            function (array $data) {
                                                                                try {
                                                                                    DB::transaction(
                                                                                        function () use ($data) {
                                                                                            $this->record->coursers->where('type', RecipientType::MAIN)->first()?->update(
                                                                                                [
                                                                                                    'pickup_date' => $data['pickup_date'],
                                                                                                    'status' => CourierStatus::RETRIEVED->value
                                                                                                ]
                                                                                            );

                                                                                            $this->record->update(
                                                                                                [
                                                                                                    'status' => CourierStatus::RETRIEVED
                                                                                                ]
                                                                                            );

                                                                                            Notification::make()
                                                                                                ->success()
                                                                                                ->title(__('Success'))
                                                                                                ->body(__('Date de levée enregistrée!'))
                                                                                                ->persistent()
                                                                                                ->send();
                                                                                        }
                                                                                    );
                                                                                } catch (Exception $e) {
                                                                                    Notification::make()
                                                                                        ->danger()
                                                                                        ->title(__('Oups'))
                                                                                        ->body($e->getMessage())
                                                                                        ->persistent()
                                                                                        ->send();
                                                                                }
                                                                            }
                                                                        )
                                                                        ->modalWidth(MaxWidth::Medium),

                                                                    Action::make('submitReceipt')
                                                                        ->icon('heroicon-m-arrow-up-tray')
                                                                        ->color(Color::Blue)
                                                                        ->size(ActionSize::Small)
                                                                        ->label("Décharge")
                                                                        ->requiresConfirmation()
                                                                        ->modalIcon('heroicon-m-arrow-up-tray')
                                                                        ->modalDescription('Si le coursier a reçu une décharge, uploadez-là ci-dessous.')
                                                                        ->form(
                                                                            [
                                                                                FileUpload::make('receipt_path')
                                                                                    ->disk('public')
                                                                                    ->hiddenLabel()
                                                                                    ->directory('courier-attachments')
                                                                                    ->required()
                                                                                    ->acceptedFileTypes(['application/pdf'])
                                                                                    ->getUploadedFileNameForStorageUsing(
                                                                                        function (TemporaryUploadedFile $file, Get $get): string {
                                                                                            $originalName = $file->getClientOriginalName();
                                                                                            $extension = pathinfo($originalName, PATHINFO_EXTENSION);
                                                                                            $fileName = "receipt_" . uniqid();

                                                                                            return (string) "{$this->record->id}/$fileName.$extension";
                                                                                        }
                                                                                    )
                                                                                    ->maxSize(1000000000)
                                                                                    ->previewable(true)
                                                                                    ->downloadable()
                                                                                    ->openable(),
                                                                            ]
                                                                        )
                                                                        ->action(
                                                                            function (array $data) {
                                                                                $this->record->coursers->where('type', RecipientType::MAIN)->first()?->update(
                                                                                    [
                                                                                        'receipt_path' => $data['receipt_path']
                                                                                    ]
                                                                                );

                                                                                Notification::make()
                                                                                    ->success()
                                                                                    ->title(__('Success'))
                                                                                    ->body(__('Décharge enregistrée!'))
                                                                                    ->persistent()
                                                                                    ->send();
                                                                            }
                                                                        )
                                                                        ->modalWidth(MaxWidth::Medium),

                                                                    Action::make('courierNotDelivered')
                                                                        ->icon('heroicon-m-x-mark')
                                                                        ->color(Color::Red)
                                                                        ->size(ActionSize::Small)
                                                                        ->label("Non distribué")
                                                                        ->requiresConfirmation()
                                                                        ->modalIcon('heroicon-m-x-mark')
                                                                        ->modalDescription("Si le courier n'a pas été distribué, indiquez le en mentionnant le motif de non-distribution")
                                                                        ->form(
                                                                            [
                                                                                Textarea::make("motive")
                                                                                    ->string()
                                                                                    ->label("Motif")
                                                                            ]
                                                                        )
                                                                        ->action(
                                                                            function (array $data) {
                                                                                $mainCourierUser =  $this->record->coursers->where('type', RecipientType::MAIN)->first();
                                                                                $mainCourierUser?->update(
                                                                                    [
                                                                                        'rejection_motive' => $data['motive'],
                                                                                        'status' => CourierStatus::NOT_DELIVERED->value
                                                                                    ]
                                                                                );

                                                                                $this->record->update(
                                                                                    [
                                                                                        'status' => CourierStatus::NOT_DELIVERED->value
                                                                                    ]
                                                                                );

                                                                                Notification::make()
                                                                                    ->success()
                                                                                    ->title(__('Success'))
                                                                                    ->body(__('Courrier marqué comme non-distribué!'))
                                                                                    ->persistent()
                                                                                    ->send();

                                                                                $notifiables = User::where('is_active', true)->whereHas("roles", fn ($q) => $q->where("name", RoleEnum::RES_SUIVI->getLabel()))->get();

                                                                                // Send firebase notification to responsable suivi users
                                                                                FirebaseNotificationJob::dispatch($notifiables, "non_distribué", $mainCourierUser);
                                                                            }
                                                                        )
                                                                        ->modalWidth(MaxWidth::Medium),
                                                                ]
                                                            )
                                                            ->columnspanFull(),
                                                    ]
                                                ),
                                            Grid::make()
                                                ->schema(
                                                    [
                                                        Placeholder::make('main_recipient_helper')
                                                            ->label(fn () => new HtmlString("<p class='text-gray-500'>Aucun ampliataire sur ce courrier.</p>"))
                                                            ->columnspanFull()
                                                            ->visible(fn () => $this->record->coursers->count() <= 1),

                                                        Repeater::make('coursers')
                                                            ->hiddenLabel()
                                                            ->columnspanFull()
                                                            ->relationship(
                                                                name: 'coursers',
                                                                modifyQueryUsing: fn (Builder $query) => $query->where('recipient_id', '!=', $this->record->mainRecipient?->id)
                                                            )
                                                            ->simple(
                                                                ViewField::make('parapheur_view')
                                                                    ->label('Ampliataire')
                                                                    ->view('filament.forms.components.courier-module.recipient')
                                                                    ->registerActions(
                                                                        [
                                                                            Action::make('setPickupDate')
                                                                                ->icon('heroicon-m-calendar-days')
                                                                                ->color('amberLight')
                                                                                ->size(ActionSize::Small)
                                                                                ->label('Date levée')
                                                                                ->requiresConfirmation()
                                                                                ->modalIcon('heroicon-m-calendar-days')
                                                                                ->modalDescription('Cette action est irreversible!')
                                                                                ->form(
                                                                                    [
                                                                                        DateTimePicker::make('pickup_date')
                                                                                            ->required()
                                                                                            ->hiddenLabel()
                                                                                            ->native(false)
                                                                                            ->closeOnDateSelection()
                                                                                            ->afterOrEqual(fn (CourierUser $record) => $record->assignment_date)
                                                                                            ->beforeOrEqual(now())
                                                                                            ->validationMessages(
                                                                                                [
                                                                                                    "after_or_equal" => "La date de levée ne peut pas être antérieure à la date d'assignation",
                                                                                                    "before_or_equal" => "La date de levée ne peut pas être dans le future XD",
                                                                                                ]
                                                                                            )
                                                                                            ->minDate(now()->subYears(1))
                                                                                            ->default(now())
                                                                                            ->displayformat('d M Y - H:i')
                                                                                    ]
                                                                                )
                                                                                ->action(
                                                                                    function (array $data, CourierUser $record) {
                                                                                        $record->update(
                                                                                            [
                                                                                                'pickup_date' => $data['pickup_date'],
                                                                                                'status' => CourierStatus::RETRIEVED->value
                                                                                            ]
                                                                                        );

                                                                                        Notification::make()
                                                                                            ->success()
                                                                                            ->title(__('Success'))
                                                                                            ->body(__('Date de levée enregistrée!'))
                                                                                            ->persistent()
                                                                                            ->send();
                                                                                    }
                                                                                )
                                                                                ->modalWidth(MaxWidth::Medium),

                                                                            Action::make('submitReceipt')
                                                                                ->icon('heroicon-m-arrow-up-tray')
                                                                                ->color(Color::Blue)
                                                                                ->size(ActionSize::Small)
                                                                                ->label("Décharge")
                                                                                ->requiresConfirmation()
                                                                                ->modalIcon('heroicon-m-arrow-up-tray')
                                                                                ->modalDescription('Si le coursier a reçu une décharge, vous pouvez l\'uploader ici!')
                                                                                ->form(
                                                                                    [
                                                                                        FileUpload::make('receipt_path')
                                                                                            ->disk('public')
                                                                                            ->hiddenLabel()
                                                                                            ->directory('courier-attachments')
                                                                                            ->required()
                                                                                            ->acceptedFileTypes(['application/pdf'])
                                                                                            ->getUploadedFileNameForStorageUsing(
                                                                                                function (TemporaryUploadedFile $file, Get $get): string {
                                                                                                    $originalName = $file->getClientOriginalName();
                                                                                                    $extension = pathinfo($originalName, PATHINFO_EXTENSION);
                                                                                                    $fileName = "receipt_" . uniqid();

                                                                                                    return (string) "{$this->record->id}/$fileName.$extension";
                                                                                                }
                                                                                            )
                                                                                            ->maxSize(1000000000)
                                                                                            ->previewable(true)
                                                                                            ->downloadable()
                                                                                            ->openable(),
                                                                                    ]
                                                                                )
                                                                                ->action(
                                                                                    function (array $data, CourierUser $record) {
                                                                                        $record->update(
                                                                                            [
                                                                                                'receipt_path' => $data['receipt_path']
                                                                                            ]
                                                                                        );

                                                                                        Notification::make()
                                                                                            ->success()
                                                                                            ->title(__('Success'))
                                                                                            ->body(__('Décharge enregistrée!'))
                                                                                            ->persistent()
                                                                                            ->send();
                                                                                    }
                                                                                )
                                                                                ->modalWidth(MaxWidth::Medium),

                                                                            Action::make('courierNotDelivered')
                                                                                ->icon('heroicon-m-x-mark')
                                                                                ->color(Color::Red)
                                                                                ->size(ActionSize::Small)
                                                                                ->label("Non distribué")
                                                                                ->requiresConfirmation()
                                                                                ->modalIcon('heroicon-m-x-mark')
                                                                                ->modalDescription("Si le courier n'a pas été distribué, indiquez le en mentionnant le motif de non-livraison")
                                                                                ->form(
                                                                                    [
                                                                                        Textarea::make("motive")
                                                                                            ->string()
                                                                                            ->label("Motif")
                                                                                            ->required()
                                                                                    ]
                                                                                )
                                                                                ->action(
                                                                                    function (array $data, CourierUser $record) {
                                                                                        $record->update(
                                                                                            [
                                                                                                'rejection_motive' => $data['motive'],
                                                                                                'status' => CourierStatus::NOT_DELIVERED->value
                                                                                            ]
                                                                                        );

                                                                                        Notification::make()
                                                                                            ->success()
                                                                                            ->title(__('Success'))
                                                                                            ->body(__('Courrier marqué comme non-distribué!'))
                                                                                            ->persistent()
                                                                                            ->send();

                                                                                        $notifiables = User::where('is_active', true)->whereHas("roles", fn ($q) => $q->where("name", RoleEnum::RES_SUIVI->getLabel()))->get();

                                                                                        // Send firebase notification to responsable suivi users
                                                                                        FirebaseNotificationJob::dispatch($notifiables, "non_distribué", $record);
                                                                                    }
                                                                                )
                                                                                ->modalWidth(MaxWidth::Medium),
                                                                        ]
                                                                    )
                                                            )
                                                    ]
                                                ),
                                        ]
                                    ),
                                Section::make('Document & Annexes')
                                    ->columnSpan(1)
                                    ->schema(
                                        [
                                            Placeholder::make('courier_number')
                                                ->label("Numéro courrier")
                                                ->content(
                                                    fn ($record) => new HtmlString(
                                                        Blade::render(
                                                            "
                                        <x-filament::badge class='w-fit' size='lg' color='primary'>
                                            $record->courier_number
                                        </x-filament::badge>
                                    "
                                                        )
                                                    )
                                                ),

                                            Placeholder::make('status')
                                                ->label(fn () => __('Statut'))
                                                ->content(
                                                    fn ($record) => new HtmlString(
                                                        Blade::render(
                                                            "
                                            <x-filament::badge
                                                class='w-fit'
                                                size='lg'
                                                color='{$record->status->getColor()}'
                                                icon='{$record->status->getIcon()}'>
                                                {$record->status->getLabel()}
                                            </x-filament::badge>
                                        "
                                                        )
                                                    )
                                                ),

                                            Placeholder::make('doc_preview')
                                                ->label("Fichier électronique")
                                                ->content(
                                                    function () {
                                                        $docPath = $this->record->doc_path ? $this->record->doc_path : $this->record->document->doc_path;
                                                        $docUrl = asset("storage/$docPath");
                                                        return new HtmlString(
                                                            Blade::render(
                                                                "
                                                                    <x-filament::button
                                                                        icon='heroicon-m-arrow-top-right-on-square'
                                                                        href='$docUrl'
                                                                        tag='a'
                                                                        target='_blank'
                                                                    >
                                                                        Voir Document
                                                                    </x-filament::button>
                                                                "
                                                            )
                                                        );
                                                    }
                                                ),

                                            FileUpload::make('attachments')
                                                ->multiple()
                                                ->disk('public')
                                                ->label(fn () => __('Documents annexes'))
                                                ->directory('courier-attachments')
                                                ->previewable(true)
                                                ->downloadable()
                                                ->openable(),
                                        ]
                                    )
                            ]
                        )
                ]
            );
    }

    public function getTitle(): string|Htmlable
    {
        return new HtmlString($this->record->courier_number ? "Courrier nº <span class='text-cyan-600'>{$this->record->courier_number}</span>" : "Voir courrier");
    }

    public function getSubheading(): ?string
    {
        return $this->record->object;
    }

    public function hasCoursersAssigned()
    {
        $hasCoursers = $this->record->coursers()->whereHas('courser')->count() >= 1;

        return $hasCoursers;
    }
}

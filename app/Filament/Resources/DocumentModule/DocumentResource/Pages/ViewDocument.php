<?php

namespace App\Filament\Resources\DocumentModule\DocumentResource\Pages;


use Exception;
use App\Models\User;
use App\Enums\DocType;
use App\Enums\RoleEnum;
use App\Models\Courier;
use Filament\Forms\Get;
use App\Enums\DocAction;
use App\Enums\DocStatus;
use App\Models\Document;
use Filament\Forms\Form;
use App\Models\CustomRole;
use App\Enums\CourierStatus;
use App\Enums\RecipientType;
use App\Enums\SignatureType;
use App\Models\DocumentUser;
use App\Jobs\SendDocForSigning;
use Filament\Actions\EditAction;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\StaticAction;
use Filament\Support\Colors\Color;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Grid;
use Illuminate\Contracts\View\View;
use Livewire\Component as Livewire;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Support\Facades\File;
use App\Jobs\FirebaseNotificationJob;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Blade;
use Filament\Forms\Components\Section;
use Filament\Support\Enums\ActionSize;
use App\Services\DocumentNumberService;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Repeater;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Models\Activity;
use App\Notifications\DocumentValidated;
use App\Services\DocManipulationService;
use Filament\Forms\Components\ViewField;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use PhpOffice\PhpWord\TemplateProcessor;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Placeholder;
use Illuminate\Contracts\Support\Htmlable;
use App\Jobs\SendInformativeNotificationJob;
use Filament\Actions\Action as HeaderAction;
use Filament\Forms\Components\Actions\Action;
use App\Notifications\SendDocActionNotification;
use Parallax\FilamentComments\Actions\CommentsAction;
use App\Filament\Resources\CourierModule\CourierResource;
use App\Filament\Resources\DocumentModule\DocumentResource;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Filament\Notifications\Actions\Action as NotificationAction;
use Illuminate\Support\Facades\Notification as MailNotification;


class ViewDocument extends ViewRecord
{
    protected static string $resource = DocumentResource::class;

    public $activitiesQuery;

    protected function getHeaderActions(): array
    {
        return [
            ActionGroup::make(
                [
                    HeaderAction::make('exportPdfDocument')
                        ->label('Exporter pour signature')
                        ->icon('heroicon-m-arrow-up-tray')
                        ->registerModalActions(
                            [
                                HeaderAction::make('download')
                                    ->label('Télécharger')
                                    ->action(
                                        function () {}
                                    ),
                            ]
                        )
                        ->modalContent(
                            fn(HeaderAction $action): View => view(
                                'filament.pages.actions.export-doc',
                                ['action' => $action],
                            )
                        )
                        ->modalDescription("Une verison PDF du document que vous pourrez imprimer et signer sera genérée")
                        ->modalSubmitAction(fn(StaticAction $action) => $action->label('Exporter'))
                        ->action(
                            function () {
                                $docPath = $this->record->doc_path;

                                $pdfFilePath = substr($docPath, 0, strrpos($docPath, '.')) . ".pdf";

                                // Check if the PDF version exists
                                if (File::exists(public_path("storage/$pdfFilePath"))) {
                                    return $this->replaceMountedAction('downloadDoc');
                                }

                                $fullDocPath = public_path("storage/$docPath");

                                if ($this->record->should_be_expedited) {
                                    $courierNumber = (new DocumentNumberService())->generateDocumentNumber($this->record);
                                    (new DocManipulationService())->addCourierNo($fullDocPath, $courierNumber);
                                }

                                // get paraph files
                                $validatorsParaphs = $this->record->parapheurs
                                    ->map(
                                        function ($parapheur) {
                                            return $parapheur->user->uploads()->where(
                                                [
                                                    ['type', SignatureType::PARAPHE],
                                                    ['is_active', true]
                                                ]
                                            )->first()?->file_path;
                                        }
                                    )
                                    ->filter()
                                    ->all();

                                $templateProcessor = new TemplateProcessor($fullDocPath);
                                // Remove placeholder in word file
                                $templateProcessor->setValue('signature', '');
                                // Save the modified Word document
                                $fileName = pathinfo($fullDocPath, PATHINFO_FILENAME);
                                $newWordFilePath = public_path("/storage/doc-attachments/{$this->record->id}/$fileName-no-signature.docx");
                                $templateProcessor->saveAs($newWordFilePath);

                                // TODO GET GENERATED PDF FILE
                                $outputDirectory = "doc-attachments/{$this->record->id}";
                                $pdfFilePath = (new DocManipulationService())->convertDocToPdf($newWordFilePath, "$fileName-temp", $outputDirectory);

                                // TODO DELETE TEMPORARY WORD FILE WITHOUT SIGNATURE PLACEHOLDER
                                unlink($newWordFilePath);

                                // TODO ADD VALIDATORS PARAPHS TO PDF
                                (new DocManipulationService())->addParaphsToPdf($fileName, $pdfFilePath, $validatorsParaphs, $this->record->id);

                                $this->replaceMountedAction('downloadDoc');
                            }
                        )
                        ->requiresConfirmation()
                        ->disabled(fn() => !$this->canExportForSigning()),


                    HeaderAction::make('inAppSigning')
                        ->label('Signature interne')
                        ->icon('heroicon-m-pencil')
                        ->modalDescription("Vous allez signer le document. Continuer ?")
                        ->modalContent(
                            fn(HeaderAction $action): View => view(
                                'filament.pages.actions.signing-doc',
                            )
                        )
                        ->modalSubmitAction(fn(StaticAction $action) => $action->label('Signer document'))
                        ->action(
                            function (Livewire $livewire) {

                                DB::beginTransaction();

                                try {
                                    // TODO check if user has the right signature
                                    $signatureRole = $this->record->signataires()->where('user_id', auth()->id())?->first()?->role->name;

                                    $signaturePath = $this->hasRightSignature($signatureRole);

                                    if (!$signaturePath) {
                                        return Notification::make()
                                            ->warning()
                                            ->title(__('Oups!'))
                                            ->body(__("Vous n'avez pas enregistré de signature de type *$signatureRole*. Contactez un administrateur pour en enregistrer un."))
                                            ->persistent()
                                            ->send();
                                    }

                                    // TODO call add signature function on word doc
                                    $docPath = public_path("storage/{$this->record->doc_path}");

                                    (new DocManipulationService())->addSignature($docPath, public_path("storage/$signaturePath"), $this->record->doc_type);

                                    // TODO generate and add courier number to docx ONLY if it should be expedited
                                    if ($this->record->should_be_expedited) {
                                        $courierNumber = (new DocumentNumberService())->generateDocumentNumber($this->record);
                                        (new DocManipulationService())->addCourierNo($docPath, $courierNumber);
                                    }

                                    // TODO convert to PDF
                                    $outputDirectory = "doc-attachments/{$this->record->id}";
                                    $fileName = pathinfo($docPath, PATHINFO_FILENAME);
                                    $pdfFilePath = (new DocManipulationService())->convertDocToPdf($docPath, "$fileName-temp", $outputDirectory);

                                    // TODO add paraphes
                                    $validatorsParaphs = $this->record->parapheurs
                                        ->map(
                                            function ($parapheur) {
                                                return $parapheur->user->uploads()->where(
                                                    [
                                                        ['type', SignatureType::PARAPHE],
                                                        ['is_active', true]
                                                    ]
                                                )->first()?->file_path;
                                            }
                                        )
                                        ->filter()
                                        ->all();

                                    (new DocManipulationService())->addParaphsToPdf($fileName, $pdfFilePath, $validatorsParaphs, $this->record->id);

                                    // TODO replace doc path with new signed PDF
                                    // TODO doc status to signed
                                    $pdfFilePath = substr($this->record->doc_path, 0, strrpos($this->record->doc_path, '.')) . ".pdf";

                                    Document::find($this->record->id)->update(
                                        [
                                            'doc_path' => $pdfFilePath,
                                            'status' => DocStatus::SIGNED->value
                                        ]
                                    );

                                    // TODO Create the courier
                                    if ($this->record->should_be_expedited) {
                                        $courier = Courier::create(
                                            [
                                                "document_id" => $this->record->id,
                                                "courier_number" => $courierNumber,
                                                "created_by" => auth()->id()
                                            ]
                                        );

                                        if ($this->record->recipient) {
                                            $courier->coursers()->create([
                                                'recipient_id' => $this->record->recipient->id,
                                                'type' => RecipientType::MAIN->value,
                                                'status' => CourierStatus::DRAFT->value
                                            ]);
                                        }

                                        $message = "Document signé ! Le courrier a été créé. Accédez à la page de détails pour compléter les informations relatives à ce courrier.";

                                        $goToCourierAction = [
                                            NotificationAction::make('view')
                                                ->label('Vers courrier')
                                                ->button()
                                                ->url(CourierResource::getUrl('view', ['record' => $courier]), shouldOpenInNewTab: true)
                                                ->visible(fn() => $this->record->should_be_expedited),
                                        ];
                                    } else {
                                        $message = "Document signé!";
                                        $goToCourierAction = [];
                                    }

                                    DB::commit();

                                    $livewire->dispatch("refresh$");

                                    return Notification::make()
                                        ->success()
                                        ->title(__('Success!'))
                                        ->body($message)
                                        ->actions($goToCourierAction)
                                        ->persistent()
                                        ->send();
                                } catch (Exception $th) {
                                    DB::rollBack();

                                    return Notification::make()
                                        ->danger()
                                        ->title(__('Oups!'))
                                        ->body(__($th->getMessage()))
                                        ->persistent()
                                        ->send();
                                }
                            }
                        )
                        ->requiresConfirmation()
                        ->disabled(fn() => !$this->canSign()),


                    HeaderAction::make('submitFinalVersion')
                        ->icon('heroicon-m-arrow-up-on-square')
                        ->label(fn() => new HtmlString("<p class='text-wrap'>Soumettre version signée</p>"))
                        ->modalIcon('heroicon-m-arrow-up-on-square')
                        ->modalDescription("Uploader la version PDF finale, signée du document")
                        ->form(
                            [
                                FileUpload::make('doc_path')
                                    ->disk('public')
                                    ->label('Fichier')
                                    ->directory('doc-attachments')
                                    ->required()
                                    ->acceptedFileTypes(['application/pdf'])
                                    ->getUploadedFileNameForStorageUsing(
                                        function (TemporaryUploadedFile $file, Get $get): string {
                                            $docName = $this->record->name;
                                            $docType = $this->record->doc_type->value;
                                            $originalName = $file->getClientOriginalName();
                                            $extension = pathinfo($originalName, PATHINFO_EXTENSION);

                                            $latestVersion = $this->record->latestVersion++;

                                            return (string) "{$this->record->id}/$docType-$docName-v$latestVersion-final.$extension";
                                        }
                                    )
                                    ->maxSize(1000000000)
                                    ->previewable(true)
                                    ->downloadable()
                                    ->openable(),
                                RichEditor::make('comment')
                                    ->label('Ajouter un commentaire')
                                    ->placeholder(__('filament-comments::filament-comments.comments.placeholder'))
                                    ->extraInputAttributes(['style' => 'min-height: 6rem'])
                                    ->toolbarButtons(config('filament-comments.toolbar_buttons'))
                            ]
                        )
                        ->action(
                            function (array $data, Livewire $livewire) {
                                try {
                                    DB::transaction(
                                        function () use ($data, $livewire) {

                                            if ($data['comment']) {
                                                $this->record->filamentComments()->create(
                                                    [
                                                        'subject_type' => $this->record->getMorphClass(),
                                                        'comment' => $data['comment'],
                                                        'user_id' => auth()->id(),
                                                    ]
                                                );
                                            }

                                            $this->record->docHistory()->create(
                                                [
                                                    "user_id" => auth()->id(),
                                                    "action" => DocAction::EDIT,
                                                    "doc_path" => $data['doc_path'],
                                                    "version" => $this->record->latestVersion + 0.1,
                                                ]
                                            );

                                            Document::find($this->record->id)->update(
                                                [
                                                    'doc_path' => $data['doc_path'],
                                                    'status' => DocStatus::SIGNED->value
                                                ]
                                            );


                                            // Create the courier
                                            if ($this->record->should_be_expedited) {

                                                $courierNumber = $this->record->courier_number;

                                                $courier = Courier::create(
                                                    [
                                                        "document_id" => $this->record->id,
                                                        "courier_number" => $courierNumber,
                                                        "created_by" => auth()->id()
                                                    ]
                                                );

                                                if ($this->record->recipient) {
                                                    $courier->coursers()->create([
                                                        'recipient_id' => $this->record->recipient->id,
                                                        'type' => RecipientType::MAIN->value,
                                                        'status' => CourierStatus::DRAFT->value
                                                    ]);
                                                }

                                                $message = "Version finale uploadé! Le courrier a été créé. Accédez à la page de détails pour compléter les informations relatives à ce courrier.";
                                            } else {
                                                $message = "Version finale uploadé!";
                                            }

                                            $livewire->dispatch("refresh$");

                                            return Notification::make()
                                                ->success()
                                                ->title(__('Success!'))
                                                ->body($message)
                                                ->actions(
                                                    [
                                                        NotificationAction::make('view')
                                                            ->label('Vers courrier')
                                                            ->button()
                                                            ->url(CourierResource::getUrl('view', ['record' => $courier]), shouldOpenInNewTab: true)
                                                            ->visible(fn() => $this->record->should_be_expedited),
                                                    ]
                                                )
                                                ->persistent()
                                                ->send();
                                        }
                                    );
                                } catch (Exception $e) {
                                    return Notification::make()
                                        ->danger()
                                        ->title(__('Oups'))
                                        ->body($e->getMessage())
                                        ->persistent()
                                        ->send();
                                }
                            }
                        )
                        ->requiresConfirmation()
                        ->disabled(fn() => !$this->canExportForSigning()),

                    HeaderAction::make('sendForSignature')
                        ->label('Envoyer pour signature')
                        ->icon('heroicon-m-pencil')
                        ->modalDescription("Envoyez le document pour signatures?")
                        ->modalSubmitAction(fn(StaticAction $action) => $action->label('Envoyer'))
                        ->action(
                            function () {

                                SendDocForSigning::dispatch($this->record);

                                NotificationAction::make('view')
                                    ->label('Document envoyé pour signature');
                            }
                        )
                        ->requiresConfirmation()
                        ->disabled(fn() => !$this->canSign()),
                ]
            )
                ->label('Signer le document')
                ->icon('heroicon-m-ellipsis-vertical')
                ->size(ActionSize::Small)
                ->color('primary')
                ->button()
                ->visible(fn() => $this->record->status === DocStatus::VALIDATED && $this->canSign()),


            HeaderAction::make('updateSignataire')
                ->requiresConfirmation()
                ->icon('heroicon-m-pencil')
                ->label(fn() => new HtmlString("<p class='text-wrap'>Choix Signataire</p>"))
                ->modalIcon('heroicon-m-pencil')
                ->color(Color::Emerald)
                ->modalWidth(MaxWidth::ThreeExtraLarge)
                ->slideOver()
                ->modalDescription("Vous pouvez choisir un ou plusieurs signataires")
                ->fillForm(
                    fn(): array => [
                        'signataires' => $this->record->signataires,
                    ]
                )
                ->form(
                    [
                        Repeater::make('signataires')
                            ->columns(2)
                            ->relationship(name: 'signataires')
                            ->hiddenLabel()
                            ->maxItems(1)
                            ->minItems(1)
                            ->deletable(false)
                            ->schema(
                                [
                                    Select::make('user_id')
                                        ->label(fn() => __('Collaborateur'))
                                        ->allowHtml()
                                        ->relationship(
                                            name: 'user',
                                            modifyQueryUsing: fn(Builder $query) => $query->where('is_active', true)
                                                ->withoutRoles([RoleEnum::SUPER_ADMIN->getLabel(), RoleEnum::ADMIN->getLabel(), RoleEnum::COURSER->getLabel()])
                                        )
                                        ->searchable(['name', 'email'])
                                        ->searchPrompt(__('Rechercher par nom ou email'))
                                        ->getOptionLabelFromRecordUsing(
                                            fn(Model $record) => Blade::render(
                                                '<x-filament::avatar
                                                src="' . filament()->getUserAvatarUrl($record) . '"
                                                size="sm"
                                                class="inline mr-2"
                                        /> ' . $record?->name . ' - ' . $record->email
                                            )
                                        )
                                        ->required()
                                        ->preload()
                                        ->reactive()
                                        ->dehydrateStateUsing(fn(string $state): string => strip_tags($state))
                                        ->native(false)
                                        ->columnSpan(1),
                                    Select::make('role_id')
                                        ->relationship(
                                            name: 'role',
                                            titleAttribute: 'name',
                                            modifyQueryUsing: fn(Builder $query) => $query->whereIn('name', [RoleEnum::SIGN_MAIN->getLabel(), RoleEnum::SIGN_ORDER->getLabel(), RoleEnum::SIGN_INTERIM->getLabel(), RoleEnum::SIGN_DELEGATION->getLabel()])
                                        )
                                        ->disableOptionWhen(
                                            function (string $value, Get $get): bool {
                                                $itemsIdsArr = collect($get('../../signataires'));
                                                return $itemsIdsArr->where('role_id', $value)->count() > 0;
                                            }
                                        )
                                        ->preload()
                                        ->searchable()
                                        ->required()
                                        ->reactive()
                                        ->label(__('Rôle'))
                                        ->columnSpan(1),
                                ]
                            )
                            ->addActionLabel(fn() => __('Ajouter'))
                            ->itemLabel(
                                function (array $state, Get $get): ?string {
                                    $user = User::find($state['user_id']);
                                    $role = CustomRole::find($get('role_id'));
                                    return $user ? new HtmlString("$user->name - $user->email - $role?->name") : null;
                                }
                            )
                            ->collapsible()
                            ->mutateRelationshipDataBeforeSaveUsing(function (array $data, ?DocumentUser $record): array {
                                if ($record && $data['user_id'] != $record->user->id) {
                                    $data['action_request_date'] = now();
                                }
                                return $data;
                            })
                            ->addAction(
                                fn(Action $action) => $action->extraAttributes(
                                    fn($component) => [
                                        'x-on:click' => new HtmlString('$dispatch(\'repeater-collapse\', \'' . $component->getStatePath() . '\')')
                                    ]
                                )
                            )
                            ->addActionLabel('Ajouter signataire')
                    ]
                )
                ->action(
                    function (array $data, Livewire $livewire) {
                        try {
                            DB::transaction(
                                function () use ($livewire) {
                                    $livewire->dispatch("refresh$");

                                    Notification::make()
                                        ->success()
                                        ->title(__('Success'))
                                        ->body('Signataire(s) ajouté(s)!')
                                        ->persistent()
                                        ->send();

                                    // TODO Send Notification to added signataire
                                    // The boolean represent validatorAdded value which is true here
                                    SendInformativeNotificationJob::dispatch(true, $this->record);
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
                ->visible(fn() => $this->record->status === DocStatus::VALIDATED && (auth()->user()->can('add_signataires_security::module::user') || $this->record->created_by === auth()->id())),


            CommentsAction::make(),

            HeaderAction::make('startProcess')
                ->label('Démarrer process')
                ->icon('heroicon-m-play')
                ->color(Color::Blue)
                ->action(
                    function (Livewire $livewire) {
                        activity()->withoutLogs(function () use ($livewire) {
                            DB::beginTransaction();
                            try {

                                // TODO UNCOMMENT TO REACTIVE CHECKING IF DOC HAS REAUIRED VARIABLES
                                $docPath = public_path("storage/{$this->record->doc_path}");

                                if ($this->record->should_be_expedited) {
                                    if ($this->record->doc_type === DocType::LETTER) {
                                        $variables = ['signature', 'numero_courrier', 'date_signature'];
                                        $missingVariablesMessage = __('Veuillez vous assurer que le document que vous uploadez contient les éléments ${signature} , ${numero_courrier} et ${date_signature} aux endroits où ils doivent figurer.');
                                    } else {
                                        $variables = ['signature', 'numero_courrier'];
                                        $missingVariablesMessage = __('Veuillez vous assurer que le document que vous uploadez contient les éléments ${signature} et ${numero_courrier} aux endroits où ils doivent figurer.');
                                    }
                                } else {
                                    if ($this->record->doc_type === DocType::LETTER) {
                                        $variables = ['signature', 'date_signature'];
                                        $missingVariablesMessage = __('Veuillez vous assurer que le document que vous uploadez contient les éléments ${signature} , ${numero_courrier} et ${date_signature} aux endroits où ils doivent figurer.');
                                    } else {
                                        $variables = ['signature'];
                                        $missingVariablesMessage = __('Veuillez vous assurer que le document que vous uploadez contient les éléments ${signature} à l\'endroit où la signature doit figurer.');
                                    }
                                }
                                // TODO call searchVariable function on word doc to search for signature variable
                                $hasVariables = (new DocManipulationService())->searchVariables($docPath, $variables);

                                if (!$hasVariables) {
                                    return Notification::make()
                                        ->warning()
                                        ->title(__('Oups!'))
                                        ->body($missingVariablesMessage)
                                        ->persistent()
                                        ->send();
                                }

                                if ($this->record->parapheurs->count() <= 0) {
                                    Notification::make()
                                        ->warning()
                                        ->title(__('Oups!'))
                                        ->body(__('Veuillez assigner un ou des parapheurs pour pouvoir demarrer le process de validation.'))
                                        ->persistent()
                                        ->send();

                                    return;
                                }

                                $this->record->status = DocStatus::INITIATED;

                                $this->record->doc_created_at = now();

                                $this->record->save();

                                $this->record->currentValidator->update(
                                    [
                                        'action_request_date' => now()
                                    ]
                                );

                                // The boolean represent validatorAdded value which is true here
                                SendInformativeNotificationJob::dispatch(true, $this->record);

                                // Send firebase notification to user for validation action
                                if ($this->record->currentValidator->user->is_active)
                                    FirebaseNotificationJob::dispatch([$this->record->currentValidator], "validation");

                                DB::commit();

                                $livewire->dispatch("refresh$");

                                return Notification::make()
                                    ->success()
                                    ->title(__('Success'))
                                    ->body(__('Process démarré! Le premier parapheur va être notifié'))
                                    ->persistent()
                                    ->send();
                            } catch (Exception $e) {
                                DB::rollBack();
                                Notification::make()
                                    ->danger()
                                    ->title(__('Oups'))
                                    ->body($e->getMessage())
                                    ->persistent()
                                    ->send();
                            }
                        });
                    }
                )
                ->requiresConfirmation()
                ->visible(fn() => $this->record->created_by === auth()->id() && $this->record->status === DocStatus::DRAFT),

            ActionGroup::make(
                [
                    HeaderAction::make('submitNewVersion')
                        ->icon('heroicon-m-arrow-up-on-square')
                        ->label(fn() => new HtmlString("<p class='text-wrap'>Soumettre nouvelle version</p>"))
                        ->modalIcon('heroicon-m-arrow-up-on-square')
                        ->color(Color::Amber)
                        ->slideOver()
                        ->modalDescription(
                            fn() => new HtmlString(
                                "
                        <div class='text-start text-sm'>
                            <p>Vous allez soumettre une nouvelle version du document.</p>
                            <p>Le document sera automatiquement considerer comme validé pour vous.</p>
                            <p>Les validations des utilisateurs précédents seront annulées.</p>
                        </div>
                    "
                            )
                        )
                        ->form(
                            [
                                FileUpload::make('doc_path')
                                    ->disk('public')
                                    ->label('Fichier')
                                    ->directory('doc-attachments')
                                    ->required()
                                    ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                                    ->getUploadedFileNameForStorageUsing(
                                        function (TemporaryUploadedFile $file, Get $get): string {
                                            $docName = str_replace(" ", "-", $this->record->name);
                                            $docType = $this->record->doc_type->value;
                                            $originalName = $file->getClientOriginalName();
                                            $extension = pathinfo($originalName, PATHINFO_EXTENSION);

                                            $newVersion = $this->record->latestVersion++;

                                            return (string) "{$this->record->id}/$docType-$docName-v$newVersion.$extension";
                                        }
                                    )
                                    ->maxSize(1000000000)
                                    ->previewable(true)
                                    ->downloadable()
                                    ->openable(),

                                RichEditor::make('comment')
                                    ->label('Ajouter un commentaire')
                                    ->placeholder(__('filament-comments::filament-comments.comments.placeholder'))
                                    ->extraInputAttributes(['style' => 'min-height: 6rem'])
                                    ->maxLength(255)
                                    ->toolbarButtons(config('filament-comments.toolbar_buttons'))
                            ]
                        )
                        ->action(
                            function (array $data, Livewire $livewire) {
                                try {
                                    DB::transaction(
                                        function () use ($data, $livewire) {

                                            if (!$this->hasParaph()) {
                                                return Notification::make()
                                                    ->warning()
                                                    ->title(__('Oups!'))
                                                    ->body(__('Vous n\'avez pas de paraphe. Contactez un administrateur pour enregistrer un paraphe.'))
                                                    ->persistent()
                                                    ->send();
                                            }

                                            if ($data['comment']) {
                                                $this->record->filamentComments()->create(
                                                    [
                                                        'subject_type' => $this->record->getMorphClass(),
                                                        'comment' => $data['comment'],
                                                        'user_id' => auth()->id(),
                                                    ]
                                                );
                                            }

                                            $this->record->docHistory()->create(
                                                [
                                                    "user_id" => auth()->id(),
                                                    "action" => DocAction::EDIT,
                                                    "doc_path" => $data['doc_path'],
                                                    "version" => $this->record->latestVersion + 0.1,
                                                ]
                                            );

                                            Document::find($this->record->id)->update(
                                                [
                                                    'doc_path' => $data['doc_path']
                                                ]
                                            );

                                            // cancel all previous validations
                                            $this->record
                                                ->validationHistory()
                                                ->where('is_active', true)
                                                ->each(
                                                    function ($item) {
                                                        $item->is_active = false;
                                                        $item->save();
                                                    }
                                                );

                                            // The boolean represent validatorAdded value which is false here
                                            SendInformativeNotificationJob::dispatch(false, $this->record);

                                            $this->validateDocument($livewire);

                                            // TODO Activate track revisions mode

                                            $docPath = public_path('storage/' . $this->record->doc_path);
                                            (new DocManipulationService())->trackRevisions($docPath, true);

                                            return Notification::make()
                                                ->success()
                                                ->title(__('Success'))
                                                ->body("Nouvelle version soumise!")
                                                ->persistent()
                                                ->send();
                                        }
                                    );
                                } catch (Exception $e) {
                                    return Notification::make()
                                        ->danger()
                                        ->title(__('Oups'))
                                        ->body($e->getMessage())
                                        ->persistent()
                                        ->send();
                                }
                            }
                        )
                        ->requiresConfirmation()
                        ->visible(fn() => $this->record->status !== DocStatus::DRAFT && $this->isValidator() && $this->canValidate())
                        ->disabled(fn() => !$this->canValidate()),

                    HeaderAction::make('validateDoc')
                        ->label('Valider document')
                        ->icon('heroicon-m-check-circle')
                        ->color(fn() => $this->canValidate() ? Color::Amber : Color::Green)
                        ->action(fn(Livewire $livewire) => $this->validateDocument($livewire))
                        ->requiresConfirmation()
                        ->visible(fn() => $this->record->status !== DocStatus::DRAFT && $this->isValidator() && $this->canValidate())
                        ->disabled(fn() => !$this->canValidate()),

                    HeaderAction::make('passTurn')
                        ->modalContent(
                            view(
                                'filament.pages.actions.pass-turn',
                                [
                                    'currentValidator' => $this->record->currentValidator,
                                    'nextValidator' => $this->record->nextValidator
                                ]
                            )
                        )
                        ->label('Passer tour')
                        ->icon('heroicon-m-forward')
                        ->color('danger')
                        ->action(
                            function (Livewire $livewire) {

                                $this->record->currentValidator->moveToEnd();

                                $this->record->currentValidator->update(
                                    [
                                        'action_request_date' => now()
                                    ]
                                );

                                // TODO Send Notification to next validator
                                $user = $this->record->currentValidator->user;
                                if ($user->is_active) {
                                    $this->notifyUser($user, $this->record);

                                    // Send firebase notification to user for validation action
                                    FirebaseNotificationJob::dispatch([$this->record->currentValidator], "validation");
                                }

                                $livewire->dispatch("refresh$");

                                return Notification::make()
                                    ->success()
                                    ->title(__('Success'))
                                    ->body('Ordre mis à jour!')
                                    ->persistent()
                                    ->send();
                            }
                        )
                        ->requiresConfirmation()
                        ->visible(fn() => $this->canPassTurn()),

                    EditAction::make()
                        ->visible(fn() => $this->record->created_by === auth()->id()  && $this->record->status->getRank() <= 4),

                    DeleteAction::make()
                        ->before(function () {
                            if ($this->record->documentUsers) {
                                $this->record->documentUsers->each(function ($documentUser) {
                                    $documentUser->delete();
                                });
                            }

                            if ($this->record->documentTeams) {
                                $this->record->documentTeams->each(function ($documentTeam) {
                                    $documentTeam->delete();
                                });
                            }

                            if (Storage::exists($this->record->doc_path)) {
                                Storage::delete($this->record->doc_path);
                            }

                            return Notification::make()
                                ->success()
                                ->title('Document supprimé')
                                ->body('Document supprimé avec succés.')
                                ->persistent()
                                ->send();
                        })
                        ->requiresConfirmation()
                        ->visible(fn() => $this->record->created_by === auth()->id() && $this->record->status === DocStatus::DRAFT),

                    HeaderAction::make('cancelDoc')
                        ->label('Annuler document')
                        ->icon('heroicon-m-x-circle')
                        ->color(fn() => Color::Red)
                        ->action(function (Livewire $livewire) {
                            $this->record->status = DocStatus::CANCELLED;
                            $this->record->save();

                            $livewire->dispatch("refresh$");

                            return Notification::make()
                                ->success()
                                ->title(__('Success'))
                                ->body(__('Vous avez annulé le document!'))
                                ->persistent()
                                ->send();
                        })
                        ->requiresConfirmation()
                        ->visible(fn() => $this->record->created_by === auth()->id() && ($this->record->status->getRank() > 1 && $this->record->status->getRank() <= 4)),

                ]
            )
                ->label('Actions')
                ->icon('heroicon-m-ellipsis-vertical')
                ->size(ActionSize::Small)
                ->color('primary')
                ->visible(fn() => ($this->canValidate() || $this->record->created_by === auth()->id()) && $this->record->status->getRank() <= 4)
                ->button(),

        ];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema(
                [
                    ViewField::make('overview')
                        ->view('filament.forms.components.doc-overview')
                        ->columnspanFull(),
                    Grid::make(7)
                        ->schema(
                            [
                                Grid::make(5)
                                    ->columnSpan(5)
                                    ->schema(
                                        [
                                            Section::make('Informations document')
                                                ->description(fn(Document $record) => $record->should_be_expedited ? "Ce document doit être expedié en courrier. Un numéro de courrier lui sera généré à la validation." : "")
                                                ->columns(2)
                                                ->columnSpanFull()
                                                ->schema(
                                                    [
                                                        Placeholder::make('name')
                                                            ->label(fn() => new HtmlString("<p class='text-gray-500'>Nom document</p>"))
                                                            ->content(
                                                                fn($record) => new HtmlString(
                                                                    Blade::render(
                                                                        "
                                                                            <h3 class='flex items-center mb-1 text-sm font-semibold text-gray-900 dark:text-white'>
                                                                                " . $record->name . "
                                                                                <span class='bg-blue-100 text-blue-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300 ms-3'>
                                                                                    v " . number_format($record->latestVersion, 1) . "
                                                                                </span>
                                                                            </h3>
                                                                        "
                                                                    )
                                                                )
                                                            )
                                                            ->columnSpan(1),

                                                        Placeholder::make('doc_type')
                                                            ->label(fn() => new HtmlString("<p class='text-gray-500'>Type document</p>"))
                                                            ->content(
                                                                fn($record) => new HtmlString(
                                                                    Blade::render(
                                                                        "
                                                                            <h3 class='flex items-center mb-1 text-sm font-semibold text-gray-900 dark:text-white'>
                                                                                " . $record->doc_type->getLabel() . "
                                                                            </h3>
                                                                        "
                                                                    )
                                                                )
                                                            )
                                                            ->columnSpan(1),

                                                        Placeholder::make('object')
                                                            ->label(fn() => new HtmlString("<p class='text-gray-500'>Objet</p>"))
                                                            ->content(
                                                                fn($record) => new HtmlString(
                                                                    Blade::render(
                                                                        "
                                                                            <h3 class='flex items-center mb-1 text-sm text-wrap font-medium text-gray-900 dark:text-white'>
                                                                                " . $record->object . "
                                                                            </h3>
                                                                        "
                                                                    )
                                                                )
                                                            )
                                                            ->columnSpan(1),

                                                        Placeholder::make('entities')
                                                            ->label(fn() => new HtmlString("<p class='text-gray-500'>Entité(s) liée(s)</p>"))
                                                            ->content(
                                                                function ($record) {
                                                                    $teams = [];
                                                                    foreach ($record->documentTeams as $documentTeam) {
                                                                        $teamName = $documentTeam->team->name;
                                                                        $isTeamActive = $documentTeam->team->is_active ? "" : "<x-filament::badge class='w-fit' size='lg' color='danger' icon='heroicon-m-x-circle'>Inactif</x-filament::badge>";
                                                                        $teams[] = "<div class='flex items-center'>
                                                                        <x-filament::badge class='w-fit mr-2 my-1'>{$teamName}</x-filament::badge>
                                                                        {$isTeamActive}
                                                                    </div>";
                                                                    }
                                                                    return new HtmlString(
                                                                        Blade::render(
                                                                            count($teams) >= 1 ? "
                                                                                <div class='flex flex-wrap'>
                                                                                " . implode(' ', $teams) . "
                                                                                </div>
                                                                            " : "<h3 class='flex items-center mb-1 text-sm text-wrap font-medium text-gray-900 dark:text-white italic'>
                                                                                Aucune entité liée...
                                                                                </h3>"
                                                                        )
                                                                    );
                                                                }
                                                            )
                                                            ->columnSpan(1),

                                                        Fieldset::make("Destinataire")
                                                            ->schema([
                                                                Placeholder::make('')
                                                                    ->hiddenLabel()
                                                                    ->content(
                                                                        function ($record) {

                                                                            $badge = $record->recipient && $record->recipient->is_active ? "" : " <x-filament::badge class='w-fit' size='lg' color='danger' icon='heroicon-m-x-circle'>Inactif</x-filament::badge>";

                                                                            return new HtmlString(
                                                                                Blade::render(
                                                                                    " <div class='flex flex-col'>
                                                                                <div class='flex'>
                                                                                    <p class='font-semibold mr-2'>{$record->recipient->name}</p>
                                                                                    {$badge}
                                                                                </div>

                                                                                <div class='flex'>
                                                                                    <x-filament::icon icon='heroicon-m-envelope' class='h-5 w-5 text-blue-500 dark:text-gray-400 ml-1 mr-2' />
                                                                                    <p class='text-gray-500'>{$record->recipient->email}</p>
                                                                                </div>

                                                                                <div class='flex'>
                                                                                    <x-filament::icon icon='heroicon-m-phone' class='h-5 w-5 text-blue-500 dark:text-gray-400 ml-1 mr-2' />
                                                                                    <p class='text-gray-500'>{$record->recipient->phone}</p>
                                                                                </div>

                                                                                <div class='flex'>
                                                                                    <x-filament::icon icon='heroicon-m-map-pin' class='h-5 w-5 text-blue-500 dark:text-gray-400 ml-1 mr-2' />
                                                                                    <p class='text-gray-500'>{$record->recipient->address}</p>
                                                                                </div>
                                                                            </div>"
                                                                                )
                                                                            );
                                                                        }
                                                                    )
                                                                    ->columnSpanFull(),
                                                            ])
                                                            ->visible(fn() => $this->record->recipient)
                                                    ]
                                                ),
                                            Section::make(
                                                fn(Document $record) => new HtmlString(
                                                    Blade::render(
                                                        "
                                        <div class='flex'> Parapheur(s)
                                            <x-filament::badge
                                                class='w-fit ml-2'>
                                                {$record->parapheurs->count()}
                                            </x-filament::badge>
                                        </div>
                                    "
                                                    )
                                                )
                                            )
                                                ->columns(2)
                                                ->compact()
                                                ->collapsible()
                                                ->columnSpanFull()
                                                ->schema(
                                                    [
                                                        Placeholder::make('')
                                                            ->hiddenLabel()
                                                            ->content('Vous n\'avez pas encore choisis de parapheurs(s)')
                                                            ->visible(fn(Document $record) => $record->parapheurs->count() <= 0)
                                                            ->columnSpanFull(),
                                                        Repeater::make('parapheurs')
                                                            ->hiddenLabel()
                                                            ->relationship('parapheurs')
                                                            ->columnSpanFull()
                                                            ->simple(
                                                                ViewField::make('parapheur_view')
                                                                    ->view('filament.forms.components.parapheur-view')
                                                                    ->columnspanFull()
                                                            )
                                                    ]
                                                ),
                                            Section::make(
                                                fn(Document $record) => new HtmlString(
                                                    Blade::render(
                                                        "
                                        <div class='flex'> Signataire(s)
                                            <x-filament::badge
                                                class='w-fit ml-2'>
                                                {$record->signataires->count()}
                                            </x-filament::badge>
                                        </div>
                                    "
                                                    )
                                                )
                                            )
                                                ->columns(2)
                                                ->compact()
                                                ->collapsible()
                                                ->columnSpanFull()
                                                ->schema(
                                                    [
                                                        Placeholder::make('')
                                                            ->hiddenLabel()
                                                            ->content('Vous n\'avez pas encore choisis de signataire(s)')
                                                            ->visible(fn(Document $record) => $record->signataires->count() <= 0)
                                                            ->columnSpanFull(),
                                                        Repeater::make('signataires')
                                                            ->hiddenLabel()
                                                            ->relationship('signataires')
                                                            ->simple(
                                                                ViewField::make('signataire_view')
                                                                    ->view('filament.forms.components.signataire-view')
                                                                    ->columnspanFull()
                                                            )
                                                            ->visible(fn(Document $record) => $record->signataires->count() > 0)
                                                            ->columns(2)
                                                            ->columnSpanFull()
                                                    ]
                                                )
                                        ]
                                    ),
                                Grid::make()
                                    ->columnSpan(2)
                                    ->schema(
                                        [
                                            Section::make('')
                                                ->schema(
                                                    [
                                                        Placeholder::make('status')
                                                            ->label(fn() => __('Statut'))
                                                            ->content(
                                                                fn($record) => new HtmlString(
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
                                                        Placeholder::make('doc_urgency')
                                                            ->label(fn() => __('Urgence document'))
                                                            ->content(
                                                                fn($record) => new HtmlString(
                                                                    Blade::render(
                                                                        "
                                                <x-filament::badge
                                                    class='w-fit'
                                                    size='lg'
                                                    color='{$record->doc_urgency->getColor()}'
                                                    icon='{$record->doc_urgency->getIcon()}'>
                                                    {$record->doc_urgency->getLabel()}
                                                </x-filament::badge>
                                            "
                                                                    )
                                                                )
                                                            ),
                                                        FileUpload::make('doc_path')
                                                            ->disk('public')
                                                            ->label(fn() => __('Fichier électronique'))
                                                            ->directory('doc-attachments')
                                                            ->previewable(true)
                                                            ->downloadable()
                                                            ->openable(),
                                                    ]
                                                )
                                                ->columnSpan(2),
                                            Section::make('')
                                                ->schema(
                                                    [
                                                        ViewField::make('Initiateur')
                                                            ->view('filament.forms.components.doc-authors-view')
                                                            ->viewData([
                                                                'type' => 'initiator'
                                                            ]),

                                                        ViewField::make('Demandeur')
                                                            ->view('filament.forms.components.doc-authors-view')
                                                            ->viewData([
                                                                'type' => 'demandeur'
                                                            ])
                                                    ]
                                                )
                                                ->columnSpan(2),
                                        ]
                                    )
                            ]
                        )
                ]
            );
    }

    private function getActivities()
    {
        return Activity::where(
            [
                ['subject_type', 'App\Models\Document'],
                ['subject_id', $this->record->id]
            ]
        );
    }

    public function canValidate(): bool
    {
        return $this->record->currentValidator?->user_id === auth()->id() && $this->record->status !== DocStatus::DRAFT;
    }


    public function isValidator(): bool
    {
        return $this->record->parapheurs()->firstWhere('user_id', auth()->id()) ? true : false;
    }


    public function canPassTurn(): bool
    {
        return (auth()->user()->can('pass_validation_turn_security::module::user') || $this->canValidate()) && ($this->record->status !== DocStatus::DRAFT && $this->record->status !== DocStatus::VALIDATED) && $this->record->nextValidator;
    }


    public function canSign(): bool
    {
        $isAuthSignataire = $this->record->signataires()->where('user_id', auth()->id())?->first() ? true : false;
        return $isAuthSignataire && $this->record->status == DocStatus::VALIDATED;
    }

    public function canExportForSigning(): bool
    {
        $isAuthSignataire = $this->record->signataires()->where('user_id', auth()->id())?->first() ? true : false;
        return ($isAuthSignataire || auth()->user()->can("export_doc_for_signing")) && $this->record->status == DocStatus::VALIDATED;
    }

    public function validateDocument($livewire)
    {
        try {
            DB::transaction(
                function () use ($livewire) {

                    if (auth()->user()->lastDocValidationHistory($this->record)?->is_active) {
                        return Notification::make()
                            ->danger()
                            ->title(__('Oups!'))
                            ->body(__('Vous avez déjà validé ce document!'))
                            ->persistent()
                            ->send();
                    }

                    if ($this->record->currentValidator) {

                        if (!$this->hasParaph()) {
                            return Notification::make()
                                ->warning()
                                ->title(__('Oups!'))
                                ->body(__('Vous n\'avez pas de paraphe. Contactez un administrateur pour enregistrer un paraphe.'))
                                ->persistent()
                                ->send();
                        }

                        $this->record->validationHistory()->create(
                            [
                                'user_id' => auth()->id()
                            ]
                        );

                        if ($this->record->status !== DocStatus::VALIDATING) {
                            Document::find($this->record->id)->update(
                                [
                                    'status' => DocStatus::VALIDATING->value
                                ]
                            );
                        }

                        $nextValidator = $this->record->currentValidator;

                        if ($nextValidator) {
                            $nextValidator->update(
                                [
                                    'action_request_date' => now()
                                ]
                            );

                            $user = $nextValidator->user;
                            if ($user->is_active) {
                                $user->notify(new SendDocActionNotification($nextValidator->user->name, $this->record));

                                // Send firebase notification to user for validation action
                                FirebaseNotificationJob::dispatch([$nextValidator], "validation");
                            }
                        } else {
                            // no more validators so document is validated
                            Document::find($this->record->id)->update(
                                [
                                    'status' => DocStatus::VALIDATED->value
                                ]
                            );

                            // Send firebase notification to user for signature action
                            FirebaseNotificationJob::dispatch([$this->record->signataires()->first()], "signature");

                            // notify demandeur and initiateur
                            $initiateur = $this->record->initiatorUser ?? $this->record->externalInitiator;
                            $demandeur = $this->record->createdBy;

                            // Filter out inactive users
                            $recipients = collect([$initiateur, $demandeur])->filter(function ($user) {
                                return $user && $user->is_active;
                            });
                            MailNotification::send($recipients, new DocumentValidated($this->record));



                            $docPath = public_path('storage/' . $this->record->doc_path);
                            (new DocManipulationService())->trackRevisions($docPath, false);
                        }
                    }

                    $livewire->dispatch("refresh$");

                    return Notification::make()
                        ->success()
                        ->title(__('Success'))
                        ->body(__('Vous avez validé le document!'))
                        ->persistent()
                        ->send();
                }
            );
        } catch (Exception $e) {
            return Notification::make()
                ->danger()
                ->title(__('Oups'))
                ->body($e->getMessage())
                ->persistent()
                ->send();
        }
    }


    public function notifyUser($user, $document)
    {
        // TODO Send Mail Notification to next validator
        $user->notify(new SendDocActionNotification($user->name, $document));

        // TODO Send Database Notification to next validator
        Notification::make()
            ->title("Attente Validation Document")
            ->success()
            ->body("C'est a votre tour de valider le document " . $document->name)
            ->actions(
                [
                    NotificationAction::make('Voir Document')
                        ->button()
                        ->url('/documents/' . $document->id),
                    NotificationAction::make('Marquer comme non lu')
                        ->button()
                        ->markAsUnread(),
                    NotificationAction::make('Marquer comme lu')
                        ->button()
                        ->markAsRead(),
                ]
            )
            ->sendToDatabase($user);
    }

    public function downloadDocAction(): HeaderAction
    {
        return HeaderAction::make('downloadDoc')
            ->label('Télécharger document')
            ->modalIcon('heroicon-m-arrow-up-tray')
            ->modalDescription("Votre document est prêt! Cliquez sur le bouton ci-dessous pour le télécharger")
            ->modalSubmitAction(fn(StaticAction $action) => $action->label('Télécharger'))
            ->modalCancelAction(false)
            ->modalWidth(MaxWidth::Medium)
            ->action(
                function () {
                    $docPath = $this->record->doc_path;
                    $pdfFilePath = substr($docPath, 0, strrpos($docPath, '.')) . ".pdf";


                    // Check if the PDF version exists
                    if (File::exists(public_path("storage/$pdfFilePath"))) {

                        return response()->download(public_path("storage/$pdfFilePath"));
                    } else {
                        return Notification::make()
                            ->warning()
                            ->title(__('Oups!'))
                            ->body('Le document que vous tentez de télecharger est introuvable!')
                            ->persistent()
                            ->send();
                    }
                }
            );
    }

    private function hasRightSignature($signatureRole)
    {
        switch ($signatureRole) {
            case RoleEnum::SIGN_MAIN->getLabel():
                $filePath = auth()->user()->uploads()->firstWhere(
                    [
                        ['type', SignatureType::MAIN],
                        ['is_active', true]
                    ]
                )?->file_path;

                break;

            case RoleEnum::SIGN_ORDER->getLabel():
                $filePath = auth()->user()->uploads()->firstWhere(
                    [
                        ['type', SignatureType::ORDER],
                        ['is_active', true]
                    ]
                )?->file_path;

                break;

            case RoleEnum::SIGN_DELEGATION->getLabel():
                $filePath = auth()->user()->uploads()->firstWhere(
                    [
                        ['type', SignatureType::DELEGATION],
                        ['is_active', true]
                    ]
                )?->file_path;

                break;
            case RoleEnum::SIGN_INTERIM->getLabel():
                $filePath = auth()->user()->uploads()->firstWhere(
                    [
                        ['type', SignatureType::INTERIM],
                        ['is_active', true]
                    ]
                )?->file_path;

                break;
            default:
                $filePath = null;
                break;
        }
        return $filePath && file_exists(public_path("storage/$filePath")) ? $filePath : null;
    }

    private function hasParaph()
    {
        $hasParaph = $this->record->currentValidator->user->uploads()->where(
            [
                ['type', SignatureType::PARAPHE],
                ['is_active', true]
            ]
        )->first()?->file_path ? true : false;;

        return $hasParaph;
    }

    public function getTitle(): string|Htmlable
    {
        $version = $this->record->docHistory()->latest()->first()->version ?? 0.1;
        return new HtmlString($this->record->name ? ('Doc. <span class="text-cyan-600">' . $this->record->name . '</span> v:' . number_format($version, 1)) : __('Afficher Document'));
    }

    public function getSubheading(): ?string
    {
        return $this->record->object;
    }
}

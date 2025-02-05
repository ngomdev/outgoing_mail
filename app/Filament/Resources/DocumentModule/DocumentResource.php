<?php

namespace App\Filament\Resources\DocumentModule;

use Filament\Forms;
use App\Models\Team;
use App\Models\User;
use Filament\Tables;
use App\Enums\DocType;
use App\Enums\RoleEnum;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Enums\DocStatus;
use App\Models\Document;
use Filament\Forms\Form;
use App\Enums\DocUrgency;
use App\Models\Recipient;
use App\Models\CustomRole;
use Filament\Tables\Table;
use App\Models\DocTemplate;
use App\Models\DocumentTeam;
use App\Models\DocumentUser;
use Illuminate\Support\Carbon;
use Filament\Resources\Resource;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Grid;
use App\Models\ExternalDocInitiator;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Wizard;
use Illuminate\Support\Facades\Blade;
use Filament\Forms\Components\Section;
use Filament\Support\Enums\FontWeight;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\Actions\Action;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;
use Filament\Tables\Actions\Action as TableAction;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use App\Filament\Resources\DocumentModule\DocumentResource\Pages\EditDocument;
use App\Filament\Resources\DocumentModule\DocumentResource\Pages\ViewDocument;
use App\Filament\Resources\DocumentModule\DocumentResource\Pages\ListDocuments;
use App\Filament\Resources\DocumentModule\DocumentResource\Pages\CreateDocument;

class DocumentResource extends Resource
{
    protected static ?string $model = Document::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $modelLabel = 'Document';
    protected static ?string $pluralModelLabel = 'Documents';
    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $slug = 'documents';


    public static function form(Form $form): Form
    {
        return $form
            ->schema(
                [
                    Wizard::make(
                        [
                            Step::make('Infos Document')
                                ->icon('heroicon-m-information-circle')
                                ->description(('Definissez les paramètres du document'))
                                ->columns(2)
                                ->schema(
                                    [
                                        Grid::make(3)
                                            ->schema(
                                                [
                                                    FieldSet::make('Urgence')
                                                        ->schema(
                                                            [
                                                                Placeholder::make('')
                                                                    ->content('Selectionnez l\'urgence du document. Ce paramettre determinera le délais de validation pour chaque validateur.')
                                                                    ->columnSpanFull(),
                                                                ToggleButtons::make('doc_urgency')
                                                                    ->hiddenLabel()
                                                                    ->validationAttribute("Urgence document")
                                                                    ->required()
                                                                    ->options(DocUrgency::class)
                                                                    ->inline(true)
                                                                    ->columnSpanFull()
                                                            ]
                                                        )
                                                        ->columnSpan(2),
                                                    FieldSet::make('Courrier')
                                                        ->schema(
                                                            [
                                                                Placeholder::make('')
                                                                    ->content('Le document doit-il être expedié en courrier?')
                                                                    ->columnSpanFull(),
                                                                ToggleButtons::make('should_be_expedited')
                                                                    ->hiddenLabel()
                                                                    ->boolean()
                                                                    ->live()
                                                                    ->validationAttribute("Doit être expedié en courrier")
                                                                    ->helperText(fn () => str("Un numéro de courrier sera généré à la validation si vous choisissez *oui*"))
                                                                    ->columnSpanFull()
                                                                    ->required()
                                                                    ->grouped()
                                                            ]
                                                        )
                                                        ->columnSpan(1),

                                                    Fieldset::make('Selectionner le destinataire')
                                                        ->schema(
                                                            [
                                                                Select::make('recipient')
                                                                    ->hiddenLabel()
                                                                    ->required()
                                                                    ->allowHtml()
                                                                    ->relationship(name: 'recipient', modifyQueryUsing: fn (Builder $query) => $query
                                                                        ->where('is_active', true))
                                                                    ->getOptionLabelFromRecordUsing(
                                                                        fn (Recipient $record) => Blade::render(
                                                                            " <div class='flex flex-col'>
                                                                            <p class='font-semibold'>$record->name</p>

                                                                            <div class='flex'>
                                                                                <x-filament::icon icon='heroicon-m-envelope' class='h-5 w-5 text-blue-500 dark:text-gray-400 ml-1 mr-2' />
                                                                                <p class='text-gray-500'>$record->email</p>
                                                                            </div>

                                                                            <div class='flex'>
                                                                                <x-filament::icon icon='heroicon-m-phone' class='h-5 w-5 text-blue-500 dark:text-gray-400 ml-1 mr-2' />
                                                                                <p class='text-gray-500'>$record->phone</p>
                                                                            </div>

                                                                        </div>"
                                                                        )
                                                                    )
                                                                    ->searchable(["email", "name", "phone"])
                                                                    ->native(false)
                                                                    ->preload()
                                                                    ->searchPrompt(__('Rechercher destinataire'))
                                                                    ->afterStateHydrated(
                                                                        function (Select $component, ?string $state, ?Document $record) {
                                                                            if ($record) {
                                                                                if ($record->recipient) {
                                                                                    $component->state($record->recipient->id);
                                                                                }
                                                                            }
                                                                        }
                                                                    )
                                                                    ->placeholder(__('Selectionnez le destinataire')),
                                                            ]
                                                        )
                                                        ->visible(fn (Get $get) => $get("should_be_expedited")),
                                                ]
                                            )
                                            ->columnSpanFull(),
                                        FieldSet::make('')
                                            ->schema(
                                                [
                                                    TextInput::make('name')
                                                        ->label(fn () => __('Nom du document'))
                                                        ->maxLength(255)
                                                        ->disabled(fn (?Document $record) => $record && $record->status !== DocStatus::DRAFT)
                                                        ->unique(ignoreRecord: true)
                                                        ->required(),
                                                    Select::make('doc_type')
                                                        ->label(fn () => __('Type de document'))
                                                        ->options(DocType::class)
                                                        ->selectablePlaceholder(false)
                                                        ->afterStateUpdated(
                                                            function (Set $set, Get $get, ?string $state, ?string $old) {
                                                                $template = DocTemplate::where('doc_type', $state)?->first();

                                                                if (!$template) {
                                                                    return;
                                                                }
                                                                $set('doc_content', $template->content);
                                                            }
                                                        )
                                                        ->required()
                                                        ->disabled(fn (?Document $record) => $record && $record->status !== DocStatus::DRAFT)
                                                        ->live()
                                                        ->native(false),
                                                    Textarea::make('object')
                                                        ->label(fn () => __('Objet'))
                                                        ->maxLength(255)
                                                        ->disabled(fn (?Document $record) => $record && $record->status !== DocStatus::DRAFT)
                                                        ->required()
                                                        ->columnspanFull(),
                                                ]
                                            ),
                                        FieldSet::make('')
                                            ->schema(
                                                [
                                                    Select::make('initiator_id')
                                                        ->label(fn () => __('Initiateur'))
                                                        ->allowHtml()
                                                        ->disabled(fn (?Document $record) => $record && $record->status !== DocStatus::DRAFT)
                                                        ->requiredWithout('external_doc_initiator_id')
                                                        ->validationMessages(
                                                            [
                                                                'required_without' => 'Le champ :attribute est requis',
                                                            ]
                                                        )
                                                        ->helperText(fn (?Document $record) => $record ? '' : __('Selectionnez l\'initiateur dans la liste s\'il s\'agit d\'un utilisateur dans le système'))
                                                        ->options(
                                                            function (?Document $record) {
                                                                $options = User::withoutRoles([RoleEnum::SUPER_ADMIN->getLabel(), RoleEnum::COURSER->getLabel()])
                                                                    ->where('is_active', true)
                                                                    ->get();

                                                                if ($record && $record->initiatorUser) {
                                                                    unset($options[$record->initiatorUser->id]);
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
                                                                    ->prepend(
                                                                        [
                                                                            'value' => 'autre',
                                                                            'label' => Blade::render(
                                                                                '<x-filament::avatar
                                                                                    src="https://ui-avatars.com/api/?name=DemandeurSysteme&color=FFFFFF&background=#222222"
                                                                                    size="sm"
                                                                                    class="inline mr-2"
                                                                                /> Autre'
                                                                            )
                                                                        ],
                                                                        'value'
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
                                                                        "
                                                                            <div class='flex items-center'>
                                                                                <x-filament::avatar
                                                                                        src='" . filament()->getUserAvatarUrl($user) . "'
                                                                                    size='h-7 w-7'
                                                                                    class='inline mr-2' />
                                                                                <div class='flex flex-col'>
                                                                                    <p>$user->name</p>
                                                                                    <div class='flex'>
                                                                                        <x-filament::icon icon='heroicon-m-envelope' class='h-5 w-5 text-blue-500 dark:text-gray-400 ml-1 mr-2' />
                                                                                        <p class='text-gray-500'>$user->email</p>
                                                                                    </div>

                                                                                    <div class='flex'>
                                                                                        <x-filament::icon icon='heroicon-m-phone' class='h-5 w-5 text-blue-500 dark:text-gray-400 ml-1 mr-2' />
                                                                                        <p class='text-gray-500'>$user->phone</p>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            "
                                                                    ),
                                                                ]
                                                            )
                                                            ->pluck('label', 'value'))
                                                        ->searchable(['name', 'email'])
                                                        ->live()
                                                        ->searchPrompt(__('Rechercher par nom ou email'))
                                                        ->afterStateHydrated(
                                                            function (Select $component, ?string $state, ?Document $record) {
                                                                if ($record) {
                                                                    if ($record->initiatorUser) {
                                                                        $component->state($record->initiatorUser->id);
                                                                    } else {
                                                                        $component->state('autre');
                                                                    }
                                                                } else {
                                                                    $component->state(auth()->id());
                                                                }
                                                            }
                                                        )
                                                        ->placeholder(__('Selectionnez l\'initiateur')),

                                                    Select::make('external_doc_initiator_id')
                                                        ->relationship(name: 'externalInitiator', titleAttribute: 'name', modifyQueryUsing: fn (Builder $query) => $query
                                                            ->where('is_active', true))
                                                        ->searchable(['name', 'email', 'phone'])
                                                        ->disabled(fn (?Document $record) => $record && $record->status !== DocStatus::DRAFT)
                                                        ->label(fn () => __('Initiateur hors système'))
                                                        ->validationAttribute('Initiateur hors système')
                                                        ->helperText('Selectionnez l\'initiateur à partir de la liste. Cliquez sur l\'icône "+" pour ajouter un initiateur inexistant.')
                                                        ->visible(fn (Get $get) => $get('initiator_id') === 'autre')
                                                        ->requiredIf('initiator_id', 'autre')
                                                        ->getOptionLabelFromRecordUsing(fn (ExternalDocInitiator $record) => "{$record->name} - {$record->email}")
                                                        ->createOptionForm(
                                                            [
                                                                Grid::make(5)
                                                                    ->schema(
                                                                        [
                                                                            FileUpload::make('logo_url')
                                                                                ->hiddenLabel()
                                                                                ->avatar()
                                                                                ->disk('public')
                                                                                ->getUploadedFileNameForStorageUsing(
                                                                                    function (TemporaryUploadedFile $file): string {
                                                                                        $originalName = $file->getClientOriginalName();
                                                                                        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
                                                                                        $maxId = ExternalDocInitiator::max("id");
                                                                                        $newId = $maxId++;
                                                                                        return (string) "external-initiator-uploads/$newId/logo_url." . $extension;
                                                                                    }
                                                                                )
                                                                                ->columnSpan(1),
                                                                            Section::make('')
                                                                                ->compact()
                                                                                ->columns(2)
                                                                                ->columnSpan(4)
                                                                                ->schema(
                                                                                    [
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
                                                                                            ->unique(table: User::class, column: 'email', ignoreRecord: true)
                                                                                            ->label("Email")
                                                                                            ->translateLabel(),
                                                                                        PhoneInput::make('phone')
                                                                                            ->initialCountry('sn')
                                                                                            ->autoPlaceholder('xx xxx xx xx')
                                                                                            ->placeholder('xx xxx xx xx')
                                                                                            ->preferredCountries(['sn'])
                                                                                            ->unique(table: ExternalDocInitiator::class, column: 'phone', ignoreRecord: true)
                                                                                            ->unique(table: User::class, column: 'phone', ignoreRecord: true)
                                                                                            ->label("Téléphone"),
                                                                                        Textarea::make('address')
                                                                                            ->label("Adresse"),
                                                                                    ]
                                                                                )
                                                                        ]
                                                                    )
                                                            ]
                                                        )
                                                        ->columnSpan(1)
                                                ]
                                            )
                                    ]
                                ),
                            Step::make('Upload document')
                                ->icon('heroicon-m-document-text')
                                ->description('Uploadez votre document et selectionnez les entités liées')
                                ->schema(
                                    [
                                        Placeholder::make('helper')
                                            ->hiddenLabel()
                                            ->content('Uploadez votre document ici. Les formats acceptés sont .doc et .docx'),

                                        Grid::make(5)
                                            ->schema(
                                                [
                                                    Repeater::make('documentTeams')
                                                        ->addActionLabel('Ajouter entité')
                                                        ->label("Entités")
                                                        ->relationship()
                                                        ->defaultItems(0)
                                                        ->deletable(fn (?Document $record) => !$record ? true : ($record->status !== DocStatus::VALIDATED ? true : false))
                                                        ->simple(
                                                            Select::make('team_id')
                                                                ->relationship(
                                                                    name: 'team',
                                                                    modifyQueryUsing: fn (Builder $query) => $query
                                                                        ->where('is_active', true)
                                                                )
                                                                ->validationAttribute("Entité")
                                                                ->hiddenLabel()
                                                                ->required()
                                                                ->allowHtml()
                                                                ->disabled(fn (?DocumentTeam $record) => $record && $record->status === DocStatus::VALIDATED)
                                                                ->searchable(['name'])
                                                                ->searchPrompt(__('Rechercher par nom'))
                                                                ->getOptionLabelFromRecordUsing(
                                                                    fn (Model $record) => Blade::render(
                                                                        "<div class='flex items-center'>
                                                                    <x-filament::icon icon='heroicon-m-rectangle-group' class='h-5 w-5 text-blue-600 mr-2' />
                                                                    <p>{$record->name}</p>
                                                                </div>"
                                                                    )
                                                                )
                                                                ->preload()
                                                                ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                                                ->native(false)
                                                                ->createOptionForm(
                                                                    [
                                                                        Grid::make(3)
                                                                            ->schema(
                                                                                [
                                                                                    Fieldset::make("Infos entités")
                                                                                        ->schema(
                                                                                            [
                                                                                                TextInput::make("name")
                                                                                                    ->required()
                                                                                                    ->unique(table: Team::class, column: 'name', ignoreRecord: true)
                                                                                                    ->label("Nom entité")
                                                                                                    ->maxLength(150)
                                                                                                    ->columnSpanFull(),
                                                                                                Textarea::make("description")
                                                                                                    ->label("Description")
                                                                                                    ->columnSpanFull()
                                                                                            ]
                                                                                        )
                                                                                        ->columnSpan(1),
                                                                                    Fieldset::make("Manager")
                                                                                        ->schema(
                                                                                            [
                                                                                                Select::make('user_id')
                                                                                                    ->hiddenLabel()
                                                                                                    ->helperText("Selectionnez le manager de l'entité")
                                                                                                    ->allowHtml()
                                                                                                    ->relationship(
                                                                                                        name: 'manager',
                                                                                                        modifyQueryUsing: fn (Builder $query) => $query
                                                                                                            ->where('is_active', true)
                                                                                                            ->withoutRoles([RoleEnum::SUPER_ADMIN->getLabel(), RoleEnum::ADMIN->getLabel(), RoleEnum::COURSER->getLabel()])
                                                                                                    )
                                                                                                    ->searchable(['name', 'email'])
                                                                                                    ->searchPrompt(__('Rechercher par nom ou email'))
                                                                                                    ->getOptionLabelFromRecordUsing(
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
                                                                                                    ->reactive()
                                                                                                    ->columnSpanFull()
                                                                                                    ->dehydrateStateUsing(fn (string $state): string => strip_tags($state))
                                                                                                    ->native(false)
                                                                                            ]
                                                                                        )
                                                                                        ->columnSpan(2)
                                                                                ]
                                                                            ),
                                                                    ]
                                                                )
                                                        )
                                                        ->columnSpan(3),

                                                    FileUpload::make('doc_path')
                                                        ->required()
                                                        ->disk('public')
                                                        ->label("Fichier électronique")
                                                        ->disabled(fn (?Document $record) => $record && $record->status !== DocStatus::DRAFT)
                                                        ->directory('doc-attachments')
                                                        ->acceptedFileTypes(['application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                                                        ->getUploadedFileNameForStorageUsing(
                                                            function (TemporaryUploadedFile $file, Get $get, ?Document $record): string {
                                                                $docName = str_replace(' ', '-', $get('name'));
                                                                $docType = $get('doc_type');
                                                                $originalName = $file->getClientOriginalName();
                                                                $extension = pathinfo($originalName, PATHINFO_EXTENSION);

                                                                if ($record) {
                                                                    $newId = $record->id;
                                                                    $previousVersion = $record->docHistory()->latest()->first()->version;
                                                                    $newVersion = str_replace('.', '', number_format(($previousVersion + 0.1), 1));
                                                                } else {
                                                                    $lastId = Document::orderBy('id', 'desc')->value('id');
                                                                    $newId = $lastId ? ($lastId + 1) : 1;
                                                                    $newVersion = '0.10';
                                                                }


                                                                return (string) "$newId/$docType-$docName-v$newVersion.$extension";
                                                            }
                                                        )
                                                        ->maxSize(1000000000)
                                                        ->previewable(true)
                                                        ->openable()
                                                        ->downloadable()
                                                        ->columnSpan(2)


                                                ]
                                            ),

                                    ]
                                ),
                            Step::make('Parapheurs & Signataires')
                                ->icon('heroicon-m-user-group')
                                ->description('Assignez les parapheurs et signataires au document')
                                ->columns(2)
                                ->schema(
                                    [
                                        Repeater::make('parapheurs')
                                            ->relationship()
                                            ->label("Parapheurs")
                                            ->minItems(fn (?Document $record) => !$record ? 0 : ($record->status !== DocStatus::DRAFT ? 1 : 0))
                                            ->deleteAction(
                                                function ($action, ?Document $record) {
                                                    $action->before(
                                                        function (array $arguments, Repeater $component) use ($record, $action) {
                                                            if ($record) {
                                                                $itemData = $component->getRawItemState($arguments['item']);
                                                                if (array_key_exists("user_id", $itemData)) {
                                                                    $hasValidated = User::find($itemData["user_id"])?->lastDocValidationHistory($record)?->is_active;

                                                                    if ($hasValidated) {
                                                                        Notification::make()
                                                                            ->warning()
                                                                            ->title("Oups!")
                                                                            ->body("Ce parapheur a déjà validé le document. Vous ne pouvez pas le détacher du document.")
                                                                            ->persistent()
                                                                            ->send();

                                                                        $action->halt();
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    );
                                                }
                                            )
                                            ->schema(
                                                [
                                                    Select::make('user_id')
                                                        ->label(fn () => __('Collaborateur'))
                                                        ->allowHtml()
                                                        ->relationship(
                                                            name: 'user',
                                                            modifyQueryUsing: fn (Builder $query) => $query
                                                                ->where('is_active', true)
                                                                ->withoutRoles([RoleEnum::SUPER_ADMIN->getLabel(), RoleEnum::COURSER->getLabel()])
                                                        )
                                                        ->disableOptionWhen(
                                                            function (string $value, Get $get): bool {
                                                                $itemsIdsArr = collect($get('../../parapheurs'));
                                                                return $itemsIdsArr->where('user_id', $value)->count() > 0;
                                                            }
                                                        )
                                                        ->searchable(['name', 'email'])
                                                        ->searchPrompt(__('Rechercher par nom ou email'))
                                                        ->getOptionLabelFromRecordUsing(
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
                                                        ->disabled(fn (?DocumentUser $record) => $record?->user?->lastDocValidationHistory($record->document)?->is_active)
                                                        ->dehydrated()
                                                        ->reactive()
                                                        ->dehydrateStateUsing(fn (?string $state) => $state ? strip_tags($state) : '')
                                                        ->native(false),

                                                    Textarea::make('comment')
                                                        ->label(__('Commentaire'))
                                                        ->helperText(__('Ajoutez un commentaire'))
                                                        ->autosize()
                                                        ->columnSpanFull()
                                                ]
                                            )
                                            ->addActionLabel(fn () => __('Ajouter'))
                                            ->itemLabel(
                                                function (array $state): ?string {
                                                    $user = User::find($state['user_id']);
                                                    return $user ? new HtmlString("$user->name - $user->email") : null;
                                                }
                                            )
                                            ->reorderable(fn (?Document $record) => ($record && $record->status === DocStatus::DRAFT) ? true : false)
                                            ->reorderableWithButtons(fn (?Document $record) => ($record && $record->status === DocStatus::DRAFT) ? true : false)
                                            ->orderColumn('order_column')
                                            ->collapsible()
                                            ->collapsed(fn (?Document $record) => $record ? true : false)
                                            ->mutateRelationshipDataBeforeCreateUsing(
                                                function (array $data, ?Document $record): array {
                                                    $roleParapheur = CustomRole::whereName(RoleEnum::PARAPHEUR->getLabel())->first();
                                                    $data['role_id'] = $roleParapheur->id;
                                                    return $data;
                                                }
                                            )
                                            ->addAction(
                                                fn (Action $action) => $action->extraAttributes(
                                                    fn ($component) => [
                                                        'x-on:click' => new HtmlString('$dispatch(\'repeater-collapse\', \'' . $component->getStatePath() . '\')')
                                                    ]
                                                )
                                            )
                                            ->addActionLabel('Ajouter parapheur')
                                            ->columnSpan(1),

                                        Repeater::make('signataires')
                                            ->relationship(name: 'signataires')
                                            ->label("Signataires")
                                            ->maxItems(1)
                                            ->schema(
                                                [
                                                    Select::make('user_id')
                                                        ->label(fn () => __('Collaborateur'))
                                                        ->allowHtml()
                                                        ->relationship(
                                                            name: 'user',
                                                            modifyQueryUsing: fn (Builder $query) => $query->where('is_active', true)
                                                                ->withoutRoles([RoleEnum::SUPER_ADMIN->getLabel(), RoleEnum::COURSER->getLabel()])
                                                        )
                                                        ->searchable(['name', 'email'])
                                                        ->searchPrompt(__('Rechercher par nom ou email'))
                                                        ->getOptionLabelFromRecordUsing(
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
                                                        ->reactive()
                                                        ->dehydrateStateUsing(fn (string $state): string => strip_tags($state))
                                                        ->native(false),
                                                    Select::make('role_id')
                                                        ->relationship(
                                                            name: 'role',
                                                            titleAttribute: 'name',
                                                            modifyQueryUsing: fn (Builder $query) => $query->where('is_active', true)->whereIn('name', [RoleEnum::SIGN_MAIN->getLabel(), RoleEnum::SIGN_ORDER->getLabel(), RoleEnum::SIGN_INTERIM->getLabel(), RoleEnum::SIGN_DELEGATION->getLabel()])
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
                                                        ->label(__('Rôle')),
                                                    Textarea::make('comment')
                                                        ->label(__('Commentaire'))
                                                        ->helperText(__('Ajoutez un commentaire'))
                                                        ->autosize()
                                                        ->columnSpanFull()
                                                ]
                                            )
                                            ->addActionLabel(fn () => __('Ajouter'))
                                            ->itemLabel(
                                                function (array $state, Get $get): ?string {
                                                    $user = User::find($state['user_id']);
                                                    $role = CustomRole::find($get('role_id'));
                                                    return $user ? new HtmlString("$user->name - $user->email - $role?->name") : null;
                                                }
                                            )
                                            ->reorderable(fn (?Document $record) => $record ? ($record->status != DocStatus::DRAFT) : true)
                                            ->reorderableWithButtons()
                                            ->orderColumn('order_column')
                                            ->collapsible()
                                            ->collapsed(fn (?Document $record) => $record ? true : false)
                                            ->addAction(
                                                fn (Action $action) => $action->extraAttributes(
                                                    fn ($component) => [
                                                        'x-on:click' => new HtmlString('$dispatch(\'repeater-collapse\', \'' . $component->getStatePath() . '\')')
                                                    ]
                                                )
                                            )
                                            ->addActionLabel('Ajouter signataire')
                                            ->columnSpan(1)
                                    ]
                                ),
                        ]
                    )
                        ->skippable()
                        ->columnSpanFull()
                        ->submitAction(
                            new HtmlString(
                                Blade::render(
                                    <<<BLADE
                            <x-filament::button
                                type="submit"
                                size="sm">
                                Sauvegarder
                            </x-filament::button>
                        BLADE
                                )
                            )
                        )
                ]
            );
    }

    public static function table(Table $table): Table
    {
        return $table
            ->emptyStateActions(
                [
                    TableAction::make('create')
                        ->label(fn () => __('Créer un document'))
                        ->description(fn () => __('Créer un nouveau document'))
                        ->url(route(CreateDocument::getRouteName()))
                        ->icon('heroicon-m-plus')
                        ->button()
                        ->visible(fn (): bool => auth()->user()->can('create_document::module::document')),
                ]
            )
            ->columns(
                [
                    TextColumn::make('doc_type')
                        ->label(fn () => __('Type'))
                        ->badge()
                        ->searchable(),

                    TextColumn::make('name')
                        ->label(fn () => __('Nom'))
                        ->searchable()
                        ->wrap()
                        ->limit(50)
                        ->tooltip(
                            function (TextColumn $column): ?string {
                                $state = $column->getState();

                                if (strlen($state) <= $column->getCharacterLimit()) {
                                    return null;
                                }
                                return $state;
                            }
                        )
                        ->weight(FontWeight::Bold),

                    TextColumn::make('object')
                        ->label(fn () => __('Objet'))
                        ->wrap()
                        ->limit(50)
                        ->tooltip(
                            function (TextColumn $column): ?string {
                                $state = $column->getState();

                                if (strlen($state) <= $column->getCharacterLimit()) {
                                    return null;
                                }
                                return $state;
                            }
                        ),

                    TextColumn::make('status')
                        ->label(fn () => __('Statut'))
                        ->badge()
                        ->searchable(),

                    TextColumn::make('created_at')
                        ->label(fn () => __('Date création'))
                        ->toggleable(isToggledHiddenByDefault: true)
                        ->dateTime('d M Y - H:i')
                        ->sortable(),

                    TextColumn::make('updated_at')
                        ->label(fn () => __('Dernière mise à jour'))
                        ->toggleable(isToggledHiddenByDefault: true)
                        ->since()
                        ->sortable()
                ]
            )
            ->filters(
                [
                    Tables\Filters\Filter::make('doc_created_at')
                        ->form(
                            [
                                Forms\Components\DatePicker::make('created_from')
                                    ->label('Créé à partir du')
                                    ->native(false)
                                    ->placeholder(fn ($state): string => now()->subYear()->format('d M Y')),
                                Forms\Components\DatePicker::make('created_until')
                                    ->native(false)
                                    ->label('Jusqu\'au')
                                    ->placeholder(fn ($state): string => now()->format('d M Y')),
                            ]
                        )
                        ->query(
                            function (Builder $query, array $data): Builder {
                                return $query
                                    ->when(
                                        $data['created_from'] ?? null,
                                        fn (Builder $query, $date): Builder => $query->whereDate('doc_created_at', '>=', $date),
                                    )
                                    ->when(
                                        $data['created_until'] ?? null,
                                        fn (Builder $query, $date): Builder => $query->whereDate('doc_created_at', '<=', $date),
                                    );
                            }
                        )
                        ->indicateUsing(
                            function (array $data): array {
                                $indicators = [];
                                if ($data['created_from'] ?? null) {
                                    $indicators['created_from'] = 'Document du ' . Carbon::parse($data['created_from'])->toFormattedDateString();
                                }
                                if ($data['created_until'] ?? null) {
                                    $indicators['created_until'] = 'Au ' . Carbon::parse($data['created_until'])->toFormattedDateString();
                                }

                                return $indicators;
                            }
                        ),
                ]
            )
            ->actions(
                [
                    Tables\Actions\ViewAction::make(),
                ]
            )
            ->striped()
            ->defaultSort('updated_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDocuments::route('/'),
            'create' => CreateDocument::route('/create'),
            'view' => ViewDocument::route('/{record}'),
            'edit' => EditDocument::route('/{record}/edit'),
        ];
    }

    public static function getSlug(): string
    {
        return '/documents';
    }

    public static function getEloquentQuery(): Builder
    {

        $query = parent::getEloquentQuery();

        $query->when(
            !auth()->user()->hasAnyRole([
                RoleEnum::SUPER_ADMIN->getLabel(),
                RoleEnum::ADMIN->getLabel(),
                RoleEnum::RES_SUIVI->getLabel()
            ]),
            function ($q) {
                return $q->where(
                    fn ($q2) => $q2->whereHas(
                        'users',
                        function ($usersQuery) {
                            $usersQuery->where('users.id', auth()->id());
                        }
                    )
                        ->orWhereHas(
                            'teams',
                            function ($teamsQuery) {
                                $teamsQuery->whereIn('teams.id', auth()->user()->managedTeams()->pluck("id"));
                            }
                        )
                        ->orWhere('created_by', auth()->id())
                );
            }
        );

        return $query;
    }
}

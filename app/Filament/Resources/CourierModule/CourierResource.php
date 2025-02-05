<?php

namespace App\Filament\Resources\CourierModule;

use App\Models\User;
use Filament\Tables;
use App\Enums\RoleEnum;
use App\Models\Contact;
use App\Models\Courier;
use Filament\Forms\Get;
use App\Enums\DocStatus;
use App\Models\Document;
use Filament\Forms\Form;
use App\Models\Recipient;
use Filament\Tables\Table;
use App\Models\CourierUser;
use App\Enums\CourierStatus;
use App\Enums\RecipientType;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Wizard;
use Illuminate\Support\Facades\Blade;
use Filament\Support\Enums\FontWeight;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Components\Actions\Action;
use App\Filament\Resources\CourierModule\CourierResource\Pages;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class CourierResource extends Resource
{
    protected static ?string $model = Courier::class;
    protected static ?string $navigationIcon = 'heroicon-o-envelope';
    protected static ?string $modelLabel = 'Courrier';
    protected static ?string $pluralModelLabel = 'Courriers';
    protected static bool $shouldRegisterNavigation = true;

    public static function form(Form $form): Form
    {
        return $form
            ->schema(
                [
                    Wizard::make(
                        [
                            Step::make('Document et Annexes')
                                ->icon('heroicon-o-document-duplicate')
                                ->columns(2)
                                ->schema(
                                    [
                                        Placeholder::make('')
                                            ->content('Selectionnez le document principal. Vous pouvez également uploader des documents annexes')
                                            ->columnSpanFull(),

                                        TextInput::make('courier_number')
                                            ->label('Numéro courrier')
                                            ->helperText("Saisissez le numéro du courrier")
                                            ->visible(fn (Get $get) => !$get('document_id'))
                                            ->required(fn (Get $get) => !$get('document_id'))
                                            ->default(str_pad(Courier::max("id") + 1, 6, '0', STR_PAD_LEFT) . '/GAINDE2000/AG')
                                            ->columnspan(1),

                                        Textarea::make('object')
                                            ->label(fn () => __('Objet'))
                                            ->validationAttribute('Objet')
                                            ->maxLength(255)
                                            ->disabled(fn (?Courier $record) => $record && $record->status !== CourierStatus::DRAFT)
                                            ->required()
                                            ->columnspan(1),

                                        Select::make('document_id')
                                            ->label(__('Document principal'))
                                            ->allowHtml()
                                            ->disabled()
                                            ->relationship(
                                                name: 'document',
                                                titleAttribute: 'name',
                                                modifyQueryUsing: fn (Builder $query) => $query->where(fn ($q) => $q->where('status', DocStatus::SIGNED)),
                                            )
                                            ->getOptionLabelFromRecordUsing(
                                                fn (Document $record) => Blade::render(
                                                    "<div class='flex items-center'>
                                                        <x-filament::icon icon='heroicon-o-document-check' class='h-6 w-6 text-blue-600 mr-2' />
                                                        <div class='flex flex-col'>
                                                            <p>{$record->name}</p>
                                                            <p class='text-gray-400 text-wrap'>{$record->object}</p>
                                                        </div>
                                                    </div>"
                                                )
                                            )
                                            ->dehydrateStateUsing(fn (string $state): string => strip_tags($state))
                                            ->searchable(['name', 'object'])
                                            ->preload()
                                            ->native(false)
                                            ->live()
                                            ->columnspan(1)
                                            ->required()
                                            ->visible(fn (Get $get) => $get('document_id') !== null),

                                        Placeholder::make('doc_preview')
                                            ->label("Fichier électronique")
                                            ->content(
                                                function (Get $get) {
                                                    $docPath = Document::find($get('document_id'))?->doc_path;
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
                                                            Visualiser le document
                                                            </x-filament::button>
                                                        "
                                                        )
                                                    );
                                                }
                                            )
                                            ->visible(fn (Get $get) => $get('document_id') !== null),


                                        FileUpload::make('doc_path')
                                            ->required()
                                            ->disk('public')
                                            ->label("Document principal")
                                            ->directory('courier-attachments')
                                            ->acceptedFileTypes(['application/pdf'])
                                            ->getUploadedFileNameForStorageUsing(
                                                function (TemporaryUploadedFile $file, ?Courier $record): string {
                                                    $originalName = $file->getClientOriginalName();
                                                    $filename = pathinfo($originalName, PATHINFO_FILENAME);
                                                    $extension = pathinfo($originalName, PATHINFO_EXTENSION);

                                                    if ($record) {
                                                        return (string) "$record->id/$filename" . "_" . uniqid() . ".$extension";
                                                    }

                                                    return (string) Courier::max('id') . "/$filename" . "_" . uniqid() . ".$extension";
                                                }
                                            )
                                            ->maxSize(1000000000)
                                            ->previewable(true)
                                            ->openable()
                                            ->downloadable()
                                            ->columnSpan(1)
                                            ->visible(fn (Get $get) => !$get('document_id'))
                                            ->disabled(fn (?Courier $record) => $record && $record->status !== CourierStatus::DRAFT),


                                        Fieldset::make("Documents annexes")
                                            ->columns(2)
                                            ->schema([
                                                Select::make('attachment_doc_ids')
                                                    ->label(__('Choisir parmis les documents dans le système'))
                                                    ->native(false)
                                                    ->multiple()
                                                    ->validationAttribute('Choix documents annexes')
                                                    ->allowHtml()
                                                    ->disabled(fn (?Courier $record) => $record && $record->status !== CourierStatus::DRAFT)
                                                    ->helperText("Vous pouvez rechercher le nom d'un document")
                                                    ->getSearchResultsUsing(fn (string $search, ?Courier $record): array => Document::where([
                                                        ['name', 'like', "%{$search}%"],
                                                        ['status', DocStatus::SIGNED],
                                                        ['id', '!=', $record?->document?->id]
                                                    ])->limit(50)->pluck('name', 'id')->toArray())
                                                    ->getOptionLabelsUsing(function (array $values) {
                                                        $docs = Document::whereIn('id', $values)->get()
                                                            ->map(
                                                                fn ($doc) => [
                                                                    'value' => "{$doc->id}",
                                                                    'label' => Blade::render(
                                                                        "<div class='flex items-center'>
                                                                    <x-filament::icon icon='heroicon-o-document-check' class='h-6 w-6 text-emerald-600 mr-2' />
                                                                    <div class='flex flex-col'>
                                                                        <p>{$doc?->name}</p>
                                                                        <p class='text-gray-400 text-wrap'>{$doc?->object}</p>
                                                                    </div>
                                                                </div>"
                                                                    ),
                                                                ]
                                                            )
                                                            ->pluck('label', 'value');
                                                        return $docs;
                                                    })
                                                    ->searchable()
                                                    ->preload()
                                                    ->live()
                                                    ->columnSpan(1),

                                                FileUpload::make('attachments')
                                                    ->multiple()
                                                    ->label("Uploader des document")
                                                    ->validationAttribute('Upload documents annexes')
                                                    ->columnSpanFull()
                                                    ->disk('public')
                                                    ->directory('courier-attachments')
                                                    ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                                                    ->getUploadedFileNameForStorageUsing(
                                                        function (TemporaryUploadedFile $file, Get $get, ?Courier $record): string {
                                                            $originalName = $file->getClientOriginalName();
                                                            $extension = pathinfo($originalName, PATHINFO_EXTENSION);

                                                            if ($record) {
                                                                return (string) "$record->id/anx_" . uniqid() . ".$extension";
                                                            }

                                                            return (string) Courier::max('id') . "/anx_" . uniqid() . ".$extension";
                                                        }
                                                    )
                                                    ->maxSize(1000000000)
                                                    ->disabled(fn (?Courier $record) => $record && $record->status !== CourierStatus::DRAFT)
                                                    ->previewable(true)
                                                    ->columnspan(1)
                                                    ->downloadable()
                                                    ->openable(),

                                            ])
                                            ->columnSpanFull(),

                                        Textarea::make('comment')
                                            ->label('Commentaire')
                                            ->columnSpanFull()
                                    ]
                                ),

                            Step::make('Destinataire principal')
                                ->icon('heroicon-o-user')
                                ->schema(
                                    [
                                        Placeholder::make('')
                                            ->content('Selectionnez le destinataire principal du courrier puis associez-le à un coursier. Vous pouvez preciser le contact chez le destinataire qui doit recevoir le courrier')
                                            ->columnSpanFull(),

                                        Fieldset::make('')
                                            ->schema(
                                                [
                                                    Select::make('main_recipient_id')
                                                        ->label('Destinataire')
                                                        ->required()
                                                        ->disabled(fn (?Courier $record) => $record && $record->status !== CourierStatus::DRAFT)
                                                        ->allowHtml()
                                                        ->options(
                                                            function (?Courier $record) {
                                                                $options = Recipient::all();

                                                                if ($record && $record->mainRecipient) {
                                                                    unset($options[$record->mainRecipient->id]);
                                                                }

                                                                return $options
                                                                    ->map(
                                                                        fn ($recipient) => [
                                                                            'value' => $recipient->id,
                                                                            'label' => Blade::render(
                                                                                "
                                                                                    <div class='flex flex-col'>
                                                                                        <p class='font-semibold'>$recipient->name</p>

                                                                                        <div class='flex'>
                                                                                            <x-filament::icon icon='heroicon-m-envelope' class='h-5 w-5 text-blue-500 dark:text-gray-400 ml-1 mr-2' />
                                                                                            <p class='text-gray-500'>$recipient->email</p>
                                                                                        </div>

                                                                                        <div class='flex'>
                                                                                            <x-filament::icon icon='heroicon-m-phone' class='h-5 w-5 text-blue-500 dark:text-gray-400 ml-1 mr-2' />
                                                                                            <p class='text-gray-500'>$recipient->phone</p>
                                                                                        </div>

                                                                                    </div>
                                                                                    "
                                                                            ),
                                                                        ]
                                                                    )
                                                                    ->pluck('label', 'value');
                                                            }
                                                        )
                                                        ->searchable()
                                                        ->getSearchResultsUsing(fn (string $search) => Recipient::where('is_active', true)->where(function ($query) use ($search) {
                                                            $query->where('name', 'like', "%{$search}%")
                                                                ->orWhere('email', 'like', "%{$search}%")
                                                                ->orWhere('phone', 'like', "%{$search}%");
                                                        })
                                                            ->limit(10)
                                                            ->get()
                                                            ->map(
                                                                fn ($recipient) => [
                                                                    'value' => $recipient->id,
                                                                    'label' => Blade::render(
                                                                        "
                                                                        <div class='flex flex-col'>
                                                                            <p class='font-semibold'>$recipient->name</p>

                                                                            <div class='flex'>
                                                                                <x-filament::icon icon='heroicon-m-envelope' class='h-5 w-5 text-blue-500 dark:text-gray-400 ml-1 mr-2' />
                                                                                <p class='text-gray-500'>$recipient->email</p>
                                                                            </div>

                                                                            <div class='flex'>
                                                                                <x-filament::icon icon='heroicon-m-phone' class='h-5 w-5 text-blue-500 dark:text-gray-400 ml-1 mr-2' />
                                                                                <p class='text-gray-500'>$recipient->phone</p>
                                                                            </div>

                                                                        </div>
                                                                        "
                                                                    ),
                                                                ]
                                                            )
                                                            ->pluck('label', 'value'))
                                                        ->native(false)
                                                        ->preload()
                                                        ->live()
                                                        ->searchPrompt(__('Rechercher destinataire'))
                                                        ->afterStateHydrated(
                                                            function (Select $component, ?string $state, ?Courier $record) {
                                                                if ($record) {
                                                                    if ($record->mainRecipient) {
                                                                        $component->state($record->mainRecipient->id);
                                                                    }
                                                                }
                                                            }
                                                        )
                                                        ->placeholder(__('Selectionnez le destinataire principal')),

                                                    Select::make('main_contact_id')
                                                        ->label('Contact')
                                                        ->disabled(fn (?Courier $record) => $record && $record->status !== CourierStatus::DRAFT)
                                                        ->allowHtml()
                                                        ->options(
                                                            function (?Courier $record, Get $get) {
                                                                $options = Contact::where('recipient_id', $get('main_recipient_id'))->get();

                                                                if ($record && $record->mainRecipient) {
                                                                    unset($options[$record->mainRecipient->id]);
                                                                }

                                                                return $options
                                                                    ->map(
                                                                        fn ($contact) => [
                                                                            'value' => $contact->id,
                                                                            'label' => Blade::render(
                                                                                "
                                                                                    <div class='flex flex-col'>
                                                                                        <p class='font-semibold'>$contact->name</p>

                                                                                        <div class='flex'>
                                                                                            <x-filament::icon icon='heroicon-m-envelope' class='h-5 w-5 text-blue-500 dark:text-gray-400 ml-1 mr-2' />
                                                                                            <p class='text-gray-500'>$contact->email</p>
                                                                                        </div>

                                                                                        <div class='flex'>
                                                                                            <x-filament::icon icon='heroicon-m-phone' class='h-5 w-5 text-blue-500 dark:text-gray-400 ml-1 mr-2' />
                                                                                            <p class='text-gray-500'>$contact->phone</p>
                                                                                        </div>

                                                                                    </div>
                                                                                    "
                                                                            ),
                                                                        ]
                                                                    )
                                                                    ->pluck('label', 'value');
                                                            }
                                                        )
                                                        ->searchable()
                                                        ->getSearchResultsUsing(fn (string $search, Get $get) => Contact::where('recipient_id', $get('main_recipient_id'))
                                                            ->where('email', 'like', "%{$search}%")
                                                            ->orWhere('name', 'like', "%{$search}%")
                                                            ->orWhere('phone', 'like', "%{$search}%")
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
                                                        ->live()
                                                        ->searchPrompt(__('Rechercher un contact'))
                                                        ->afterStateHydrated(
                                                            function (Select $component, ?string $state, ?Courier $record) {
                                                                if ($record) {
                                                                    if ($record->mainContact) {
                                                                        $component->state($record->mainContact->id);
                                                                    }
                                                                }
                                                            }
                                                        )
                                                        ->placeholder(__('Selectionnez le contact chez le destinataire'))
                                                        ->visible(fn (Get $get) => $get('main_recipient_id') !== null),
                                                ]
                                            ),

                                        Fieldset::make('')
                                            ->visible(fn (Get $get) => $get('main_recipient_id') !== null)
                                            ->schema(
                                                [
                                                    Select::make('main_courser_id')
                                                        ->label('Coursier')
                                                        ->required()
                                                        ->disabled(
                                                            function (?Courier $record) {
                                                                if ($record) {
                                                                    $status = $record->coursers->where('type', RecipientType::MAIN)?->first()?->status;
                                                                    if ($status && $status->getRank() >= CourierStatus::RETRIEVED->getRank()) {
                                                                        return true;
                                                                    } else {
                                                                        return false;
                                                                    }
                                                                } else {
                                                                    return false;
                                                                }
                                                            }
                                                        )
                                                        ->allowHtml()
                                                        ->native(false)
                                                        ->options(
                                                            function (?Courier $record, get $get) {
                                                                $options = User::where('is_active', true)->whereHas('roles', fn ($q) => $q->where('name', RoleEnum::COURSER->getLabel()))
                                                                    ->get();

                                                                if ($record && $record->mainCourser) {
                                                                    unset($options[$record->mainCourser->id]);
                                                                }

                                                                return $options
                                                                    ->map(
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
                                                                    ->pluck('label', 'value');
                                                            }
                                                        )
                                                        ->searchable()
                                                        ->getSearchResultsUsing(fn (string $search) => User::where('is_active', true)->whereHas('roles', fn ($q) => $q->where('name', RoleEnum::COURSER->getLabel()))
                                                            ->where('email', 'like', "%{$search}%")
                                                            ->orWhere('name', 'like', "%{$search}%")
                                                            ->orWhere('phone', 'like', "%{$search}%")
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
                                                        ->live()
                                                        ->searchPrompt(__('Rechercher coursier'))
                                                        ->afterStateHydrated(
                                                            function (Select $component, ?string $state, ?Courier $record) {
                                                                if ($record) {
                                                                    if ($record->mainCourser) {
                                                                        $component->state($record->mainCourser->id);
                                                                    }
                                                                }
                                                            }
                                                        )
                                                        ->placeholder(__('Selectionnez le coursier')),

                                                    Textarea::make('main_comment')
                                                        ->formatStateUsing(
                                                            function (?Courier $record) {
                                                                $comment = null;
                                                                if ($record) {
                                                                    $comment = $record->coursers->where('type', RecipientType::MAIN)?->first()?->comment ?? null;
                                                                }

                                                                return $comment;
                                                            }
                                                        )
                                                        ->label('Commentaire'),
                                                ]
                                            )
                                    ]
                                ),

                            Step::make('Ampliataires')
                                ->icon('heroicon-o-user-group')
                                ->schema(
                                    [
                                        Placeholder::make('')
                                            ->content('Si des copies du document doivent être adressées à d\'autres destinataires, selectionnez-les ici'),

                                        Repeater::make('ampliataires')
                                            ->hiddenLabel()
                                            ->addActionLabel('Ajouter ampliataire')
                                            ->columns(2)
                                            ->defaultItems(0)
                                            ->collapsible()
                                            ->collapsed(fn (?Courier $record) => $record ? true : false)
                                            ->itemLabel(fn (array $state): ?string => Recipient::find($state['recipient_id'])?->name ?? null)
                                            ->addAction(
                                                fn (Action $action) => $action->extraAttributes(
                                                    fn ($component) => [
                                                        'x-on:click' => new HtmlString('$dispatch(\'repeater-collapse\', \'' . $component->getStatePath() . '\')')
                                                    ]
                                                )
                                            )
                                            ->relationship(
                                                name: 'coursers',
                                                modifyQueryUsing: fn (Builder $query, ?Courier $record) => $record ? $query->where('recipient_id', '!=', $record->mainRecipient?->id) : $query
                                            )
                                            ->mutateRelationshipDataBeforeSaveUsing(
                                                function (array $data, ?CourierUser $record): array {
                                                    $data['type'] = RecipientType::ADDITIONAL->value;

                                                    if ($record && $record->courier->status !== CourierStatus::DRAFT) {
                                                        $data['assignment_date'] = now();
                                                        $data['status'] = CourierStatus::INITIATED->value;
                                                    }
                                                    return $data;
                                                }
                                            )
                                            ->mutateRelationshipDataBeforeCreateUsing(
                                                function (array $data): array {
                                                    $data['type'] = RecipientType::ADDITIONAL->value;
                                                    $data['status'] = CourierStatus::DRAFT->value;
                                                    return $data;
                                                }
                                            )
                                            ->schema(
                                                [
                                                    Fieldset::make("Ampliataire")
                                                        ->schema([
                                                            Select::make('recipient_id')
                                                                ->label('Ampliataire')
                                                                ->helperText("Selectionnez un ampliataire")
                                                                ->relationship(
                                                                    name: 'recipient',
                                                                    modifyQueryUsing: fn (Builder $query, Get $get) => $query->where('id', '!=', (int) $get('../../main_recipient_id'))
                                                                )
                                                                ->required()
                                                                ->allowHtml()
                                                                ->disabled(fn (?CourierUser $record) => $record && $record->courier->status !== CourierStatus::DRAFT)
                                                                ->dehydrated()
                                                                ->getOptionLabelFromRecordUsing(
                                                                    fn (Model $record) => Blade::render(
                                                                        "
                                                                        <div class='flex flex-col'>
                                                                            <p class='font-semibold'>$record->name</p>

                                                                            <div class='flex'>
                                                                                <x-filament::icon icon='heroicon-m-envelope' class='h-5 w-5 text-blue-500 dark:text-gray-400 ml-1 mr-2' />
                                                                                <p class='text-gray-500'>$record->email</p>
                                                                            </div>

                                                                            <div class='flex'>
                                                                                <x-filament::icon icon='heroicon-m-phone' class='h-5 w-5 text-blue-500 dark:text-gray-400 ml-1 mr-2' />
                                                                                <p class='text-gray-500'>$record->phone</p>
                                                                            </div>

                                                                        </div>
                                                                    "
                                                                    )
                                                                )
                                                                ->searchable(['name', 'email', 'phone'])
                                                                ->preload()
                                                                ->live()
                                                                ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                                                ->searchPrompt(__('Rechercher ampliataire'))
                                                                ->placeholder(__('Selectionnez une option')),

                                                            Select::make('contact_id')
                                                                ->label('Contact')
                                                                ->relationship(
                                                                    name: 'contact',
                                                                    modifyQueryUsing: fn (Builder $query, Get $get) => $query->where('recipient_id', (int) $get('recipient_id'))
                                                                )
                                                                ->allowHtml()
                                                                ->disabled(fn (?CourierUser $record) => $record && $record->courier->status !== CourierStatus::DRAFT)
                                                                ->getOptionLabelFromRecordUsing(
                                                                    fn (Model $record) => Blade::render(
                                                                        "
                                                                        <div class='flex flex-col'>
                                                                            <p class='font-semibold'>$record->name</p>

                                                                            <div class='flex'>
                                                                                <x-filament::icon icon='heroicon-m-envelope' class='h-5 w-5 text-blue-500 dark:text-gray-400 ml-1 mr-2' />
                                                                                <p class='text-gray-500'>$record->email</p>
                                                                            </div>

                                                                            <div class='flex'>
                                                                                <x-filament::icon icon='heroicon-m-phone' class='h-5 w-5 text-blue-500 dark:text-gray-400 ml-1 mr-2' />
                                                                                <p class='text-gray-500'>$record->phone</p>
                                                                            </div>

                                                                        </div>
                                                                    "
                                                                    )
                                                                )
                                                                ->searchable(['name', 'email', 'phone'])
                                                                ->preload()
                                                                ->searchPrompt(__('Rechercher un contact'))
                                                                ->placeholder(__('Selectionnez le contact chez le destinataire'))
                                                                ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                                                ->visible(fn (Get $get) => $get('recipient_id') !== null),
                                                        ]),


                                                    Fieldset::make("Coursier")
                                                        ->visible(fn (Get $get) => $get('recipient_id') !== null)
                                                        ->schema([
                                                            Select::make('user_id')
                                                                ->label('Coursier')
                                                                ->relationship(
                                                                    name: 'courser',
                                                                    modifyQueryUsing: fn (Builder $query) => $query->where('is_active', true)
                                                                        ->whereHas('roles', fn ($q) => $q->where('name', RoleEnum::COURSER->getLabel()))
                                                                )
                                                                ->required()
                                                                ->allowHtml()
                                                                ->disabled(
                                                                    function (?CourierUser $record) {
                                                                        if ($record) {
                                                                            $status = $record->status;
                                                                            if ($status && $status->getRank() >= CourierStatus::RETRIEVED->getRank()) {
                                                                                return true;
                                                                            } else {
                                                                                return false;
                                                                            }
                                                                        } else {
                                                                            return false;
                                                                        }
                                                                    }
                                                                )
                                                                ->getOptionLabelFromRecordUsing(
                                                                    fn (Model $record) => Blade::render(
                                                                        "
                                                                            <div class='flex items-center'>
                                                                                <x-filament::avatar
                                                                                    src='" . filament()->getUserAvatarUrl($record) . "'
                                                                                    size='h-7 w-7'
                                                                                    class='inline mr-2' />
                                                                                <div class='flex flex-col'>
                                                                                    <p>$record->name</p>
                                                                                    <div class='flex'>
                                                                                        <x-filament::icon icon='heroicon-m-envelope' class='h-5 w-5 text-blue-500 dark:text-gray-400 ml-1 mr-2' />
                                                                                        <p class='text-gray-500'>$record->email</p>
                                                                                    </div>

                                                                                    <div class='flex'>
                                                                                        <x-filament::icon icon='heroicon-m-phone' class='h-5 w-5 text-blue-500 dark:text-gray-400 ml-1 mr-2' />
                                                                                        <p class='text-gray-500'>$record->phone</p>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        "
                                                                    )
                                                                )
                                                                ->searchable(['name', 'email', 'phone'])
                                                                ->preload()
                                                                ->searchPrompt(__('Rechercher coursier'))
                                                                ->placeholder(__('Selectionnez le coursier')),

                                                            Textarea::make('comment')
                                                                ->label('Commentaire'),
                                                        ]),

                                                ]
                                            )
                                    ]
                                )
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
                        ),
                ]
            );
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(
                [
                    TextColumn::make('courier_number')
                        ->label(fn () => __('Numéro courrier'))
                        ->default('-')
                        ->badge()
                        ->wrap()
                        ->searchable()
                        ->weight(FontWeight::Bold),

                    TextColumn::make('document.doc_type')
                        ->label(fn () => __('Type'))
                        ->badge()
                        ->color(Color::Blue)
                        ->searchable()
                        ->weight(FontWeight::Bold),

                    TextColumn::make('object')
                        ->label(fn () => __('Objet'))
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
                        ),

                    TextColumn::make('status')
                        ->label(fn () => __('Statut Courier'))
                        ->badge()
                        ->searchable(),

                    TextColumn::make('created_at')
                        ->label(fn () => __('Date création'))
                        ->dateTime('d M Y - H:i')
                        ->sortable(),

                    TextColumn::make('updated_at')
                        ->label(fn () => __('Dernière mise à jour'))
                        ->dateTime('d M Y - H:i')
                        ->toggleable(isToggledHiddenByDefault: true)
                        ->sortable()
                ]
            )
            ->filters(
                [
                    //
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
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCouriers::route('/'),
            'create' => Pages\CreateCourier::route('/create'),
            'view' => Pages\ViewCourier::route('/{record}'),
            'edit' => Pages\EditCourier::route('/{record}/edit'),
        ];
    }

    public static function getSlug(): string
    {
        return '/courier';
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Gestion Courriers');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}

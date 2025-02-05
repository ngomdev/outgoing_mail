<?php

namespace App\Filament\Resources\SecurityModule\UserResource\Pages;

use App\Models\User;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Illuminate\Support\Str;
use App\Enums\SignatureType;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\ActionGroup;
use Filament\Support\Colors\Color;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Grid;
use Livewire\Component as Livewire;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Support\Facades\File;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Blade;
use Filament\Forms\Components\Section;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Enums\IconPosition;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Wallo\FilamentSelectify\Components\ButtonGroup;
use App\Filament\Resources\SecurityModule\UserResource;
use Saade\FilamentAutograph\Forms\Components\SignaturePad;
use Jeffgreco13\FilamentBreezy\Actions\PasswordButtonAction;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [

            ActionGroup::make([
                EditAction::make()
                    ->icon('heroicon-s-pencil-square')
                    ->visible(fn() => $this->record->is_active),
                Action::make('activateUser')
                    ->label(fn() => $this->record->is_active ? __('Bloquer utilisateur') : __('Activer utilisateur'))
                    ->requiresConfirmation()
                    ->modalDescription(fn() => $this->record->is_active ? "N'oubliez pas de retirer l'utilisateur de toutes ses instances en cours" : "Etes vous sure de vouloir activer cet utilisateur?")
                    ->action(fn() => $this->updateUserStatus())
                    ->color(fn() => $this->record->is_active ? 'danger' : 'success')
                    ->icon(fn() => $this->record->is_active ? 'heroicon-s-lock-closed' : 'heroicon-s-lock-open'),

                Action::make('resendPasswordResetEmail')
                    ->action('resendPasswordResetEmail')
                    ->color(Color::Amber)
                    ->label(__('Réinitialiser mot de passe'))
                    ->icon('heroicon-s-envelope')
                    ->visible(fn() => $this->record->is_active),

                Action::make('createSignature')
                    ->form([
                        Section::make('')
                            ->schema([
                                Select::make('type')
                                    ->required()
                                    ->options(SignatureType::class)
                                    ->validationAttribute("Type")
                                    ->native(false)
                                    ->columnSpan(1),
                                ButtonGroup::make('options')
                                    ->label(__('Options'))
                                    ->options([
                                        'pad' => 'Utiliser pad',
                                        'upload' => 'Uploader',
                                    ])
                                    ->default('pad')
                                    ->onColor('primary')
                                    ->offColor('gray')
                                    ->gridDirection('row')
                                    ->icons([
                                        'pad' => 'heroicon-m-pencil',
                                        'upload' => 'heroicon-m-arrow-up-tray',
                                    ])
                                    ->iconPosition(IconPosition::Before)
                                    ->live(),

                                SignaturePad::make('signature')
                                    ->label(fn(Get $get) => Str::ucfirst($get('type')))
                                    ->helperText(fn() => __('Utiliser votre souris ou un stylet de préférence'))
                                    ->requiredWithout('file_path')
                                    ->validationMessages([
                                        'required_without' => 'Signature requise'
                                    ])
                                    ->backgroundColor('#fff')
                                    ->backgroundColorOnDark('#111827')
                                    ->penColor('#284283')
                                    ->penColorOnDark('#284283')
                                    ->dotSize(2.0)
                                    ->lineMinWidth(0.5)
                                    ->lineMaxWidth(2.5)
                                    ->throttle(16)
                                    ->minDistance(5)
                                    ->velocityFilterWeight(0.7)
                                    ->confirmable()
                                    ->visible(fn(Get $get) => $get('options') === 'pad')
                                    ->columnSpanFull(),

                                FileUpload::make('file_path')
                                    ->hiddenLabel()
                                    ->image()
                                    ->requiredWithout('signature')
                                    ->validationMessages([
                                        'required_without' => 'Signature requise'
                                    ])
                                    ->disk('public')
                                    ->label('Fichier')
                                    ->directory('signatures')
                                    ->helperText(fn() => __('Uploadez une image'))
                                    ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file, Get $get, ?User $record): string {
                                        $type = $get('type');
                                        $originalName = $file->getClientOriginalName();
                                        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
                                        $fileName = $type . '_' . uniqid() . '.' . $extension;

                                        return (string) "$record->id/$fileName";
                                    })
                                    ->maxSize(1000000000)
                                    ->previewable(true)
                                    ->openable()
                                    ->downloadable()
                                    ->visible(fn(Get $get) => $get('options') === 'upload')
                                    ->columnSpanFull(),
                            ])
                    ])
                    ->action(function (array $data, Livewire $livewire) {
                        $this->createSignature($data, $livewire);
                    })
                    ->color('primary')
                    ->label(__('Créer signature ou paraphe'))
                    ->modalWidth(MaxWidth::Medium)
                    ->icon('heroicon-s-pencil')
                    ->visible(fn() => $this->record->is_active),

                Action::make('createSigningCode')
                    ->form([
                        TextInput::make('signing_code')
                            ->label('Code de signature')
                            ->minLength(6)
                            ->password()
                            ->confirmed()
                            ->revealable(),
                        TextInput::make('signing_code_confirmation')
                            ->label('Confirmer code de signature')
                            ->same('signing_code')
                            ->password()
                            ->revealable()
                    ])
                    ->action(function (array $data) {
                        $this->record->update(['signing_code' => $data['signing_code']]);

                        Notification::make()
                            ->success()
                            ->title(__('Success'))
                            ->body("Code de signature enregistré avec succés!")
                            ->persistent()
                            ->send();
                    })
                    ->label('Définir code de signature')
                    ->icon('heroicon-s-shield-check')
                    ->modalIcon('heroicon-s-shield-check')
                    ->modalDescription('Ce code servira à vous authentifier lorsque vous devrez signer des documents.')
                    ->modalWidth(MaxWidth::Medium),
            ])
                ->button()
                ->dropdownWidth(MaxWidth::Small)
                ->label('Actions'),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function resendPasswordResetEmail(): void
    {
        User::sendPasswordResetLink($this->record);
    }


    public function updateUserStatus(): void
    {

        $this->record->is_active = !$this->record->is_active;

        $this->record->save();

        Notification::make()
            ->success()
            ->title(__('Success'))
            ->body($this->record->is_active ? __('Utilisateur activé!') : __('Utilisateur bloqué!'))
            ->persistent()
            ->send();
    }

    public function createSignature($data, $livewire)
    {
        DB::beginTransaction();
        try {
            $type = $data['type'];
            if (array_key_exists('signature', $data)) {
                $base64String = $data['signature'];

                // Remove the "data:image/png;base64," part from the base64 string
                $base64String = str_replace('data:image/png;base64,', '', $base64String);

                // Decode the base64 string
                $imageData = base64_decode($base64String);

                // Generate a unique filename
                $signatureFileName = $type . '_' . uniqid() . '.png';

                $directory = 'signatures/' . $this->record->id;

                // Create the directory if it doesn't exist
                if (!File::isDirectory($directory)) {
                    Storage::disk('public')->makeDirectory($directory);
                }

                $signatureFilePath = $directory . '/' . $signatureFileName;

                // Specify the path where you want to save the image
                $publicStoragePath = public_path('storage/' . $signatureFilePath);

                // Save the image to the storage path
                file_put_contents($publicStoragePath, $imageData);
            } else {
                $signatureFilePath = $data['file_path'];
            }

            // deactivate existing ones
            $existingSignature = $this->record->uploads()->where([
                ['type', $type],
                ['is_active', true]
            ])?->first();

            if ($existingSignature) {
                $existingSignature->update([
                    'is_active' => false
                ]);
            }

            $this->removeImageBackground($signatureFilePath);

            $this->record->uploads()->create([
                'file_path' => $signatureFilePath,
                'type' => $type
            ]);

            DB::commit();

            $typeLabel = SignatureType::getByValue($type);

            $livewire->dispatch("refresh$");

            Notification::make()
                ->success()
                ->title(__('Success'))
                ->body("$typeLabel créé avec succés!")
                ->persistent()
                ->send();
        } catch (\Exception $e) {
            DB::rollBack();
            // Handle the exception (e.g., log the error, return an error response)
            Notification::make()
                ->danger()
                ->title(__('Oups!'))
                ->body($e->getMessage())
                ->persistent()
                ->send();
        }
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(3)
                    ->schema([
                        Section::make('Infos utilisateur')
                            ->columnSpan(2)
                            ->columns(2)
                            ->schema([
                                Placeholder::make('name')
                                    ->label(fn() => new HtmlString("<p class='text-gray-500'>Nom</p>"))
                                    ->content(fn($record) => new HtmlString(Blade::render("
                                    <h3 class='flex items-center mb-1 text-sm font-semibold text-gray-900 dark:text-white'>
                                        " . $record->name . "
                                    </h3>
                                "))),
                                Placeholder::make('email')
                                    ->label(fn() => new HtmlString("<p class='text-gray-500'>Email</p>"))
                                    ->content(fn($record) => new HtmlString(Blade::render("
                                    <h3 class='flex items-center mb-1 text-sm font-semibold text-gray-900 dark:text-white'>
                                        " . $record->email . "
                                    </h3>
                                "))),
                                Placeholder::make('phone')
                                    ->label(fn() => new HtmlString("<p class='text-gray-500'>Téléphone</p>"))
                                    ->content(fn($record) => new HtmlString(Blade::render("
                                    <h3 class='flex items-center mb-1 text-sm font-semibold text-gray-900 dark:text-white'>
                                        " . ($record->phone ? $record->phone : '-') . "
                                    </h3>
                                "))),

                                Placeholder::make('role_id')
                                    ->label(fn() => new HtmlString("<p class='text-gray-500'>Role</p>"))
                                    ->content(function ($record) {
                                        $roleName = $record->getRoleNames()->first();
                                        $isRoleActive = $record->roles()->first()->is_active;
                                        $badge = $isRoleActive ? "" : "<x-filament::badge class='w-fit' size='lg' color='danger' icon='heroicon-m-x-circle'>Inactif</x-filament::badge>";
                                        return new HtmlString(Blade::render("<div class='flex'>
                                            <h3 class='flex items-center mr-2 text-sm font-semibold text-gray-900 dark:text-white'>{$roleName}</h3>
                                            {$badge}
                                        </div>"));
                                    }),

                                Placeholder::make('user_function_id')
                                    ->label(fn() => new HtmlString("<p class='text-gray-500'>Fonction</p>"))
                                    ->content(function ($record) {
                                        $userFunctionName = $record->userFunction->name;
                                        $isFunctionActive = $record->userFunction->is_active;
                                        $badge = $isFunctionActive ? "" : "<x-filament::badge class='w-fit' size='lg' color='danger' icon='heroicon-m-x-circle'>Inactif</x-filament::badge>";
                                        return new HtmlString(Blade::render("<div class='flex'>
                                            <h3 class='flex items-center mr-2 text-sm font-semibold text-gray-900 dark:text-white'>{$userFunctionName}</h3>
                                            {$badge}
                                        </div>"));
                                    })
                                    ->visible(fn() => $this->record->userFunction),
                                Placeholder::make('created_at')
                                    ->label(fn() => new HtmlString("<p class='text-gray-500'>Date d'ajout</p>"))
                                    ->content(fn($record) => new HtmlString(Blade::render("
                                    <h3 class='flex items-center mb-1 text-sm font-semibold text-gray-900 dark:text-white'>
                                        " . $record->created_at->format('d M Y - H:i') . "
                                    </h3>
                                "))),
                            ]),
                        Section::make('')
                            ->columnSpan(1)
                            ->schema([
                                Placeholder::make('phone')
                                    ->hiddenLabel()
                                    ->columnSpanFull()
                                    ->content(fn($record) => new HtmlString(Blade::render('
                                    <div class="flex flex-col items-center">
                                        <x-filament::avatar
                                            src="' . filament()->getUserAvatarUrl($record) . '"
                                            alt="' . $record->name . '"
                                            size="h-14 w-14"
                                        />

                                        <p class="text-sm my-1 font-semibold">#' . $record->registration_number . '</p>
                                        <p class="text-sm mb-1">@' . $record->getRoleNames()->first() . '</p>
                                        <x-filament::badge
                                            class="w-fit"
                                            color="' . ($record->is_active ? 'success' : 'danger') . '"
                                            icon="' . ($record->is_active ? 'heroicon-o-check' : 'heroicon-o-x-mark') . '">'
                                        . ($record->is_active ? 'Actif' : 'Inactif') . '
                                        </x-filament::badge>
                                    </div>
                                '))),
                            ])
                    ])
            ]);
    }

    private function removeImageBackground($imagePath)
    {
        // Get original image path
        $imagePath = public_path('storage/' . $imagePath);
        // Check if the file exists
        if (!file_exists($imagePath)) {
            return Notification::make()
                ->title('Oups!')
                ->danger()
                ->body("File not found")
                ->persistent()
                ->send();
        }

        // Get the image extension
        $extension = pathinfo($imagePath, PATHINFO_EXTENSION);

        // Load the image based on its extension
        if ($extension === 'png') {
            $image = imagecreatefrompng($imagePath);
        } elseif ($extension === 'jpg' || $extension === 'jpeg') {
            $image = imagecreatefromjpeg($imagePath);
        } else {
            // Handle unsupported image mimes
            return Notification::make()
                ->title('Oups!')
                ->danger()
                ->body("Image non supportee.(png,jpeg uniquement)")
                ->persistent()
                ->send();
        }

        // Get the background color (top-left pixel color)
        $bgColor = imagecolorat($image, 0, 0);
        $bgColorRgb = imagecolorsforindex($image, $bgColor);
        // Get the image dimensions
        $imageWidth = imagesx($image);
        $imageHeight = imagesy($image);
        // Create a new true color image with alpha channel
        $outputImage = imagecreatetruecolor($imageWidth, $imageHeight);
        $transparency = imagecolorallocatealpha($outputImage, 0, 0, 0, 127);
        imagefill($outputImage, 0, 0, $transparency);
        imagesavealpha($outputImage, true);
        // Loop through each pixel in the input image
        for ($x = 0; $x < $imageWidth; $x++) {
            for ($y = 0; $y < $imageHeight; $y++) {
                $color = imagecolorat($image, $x, $y);
                $colorRgb = imagecolorsforindex($image, $color);
                if ($colorRgb['red'] == $bgColorRgb['red'] && $colorRgb['green'] == $bgColorRgb['green'] && $colorRgb['blue'] == $bgColorRgb['blue']) {
                    imagesetpixel($outputImage, $x, $y, $transparency);
                } else {
                    imagesetpixel($outputImage, $x, $y, $color);
                }
            }
        }
        if ($extension === 'png') {
            imagepng($outputImage, $imagePath);
        } elseif ($extension === 'jpg' || $extension === 'jpeg') {
            imagejpeg($outputImage, $imagePath);
        }
        // Free up memory
        imagedestroy($image);
        imagedestroy($outputImage);
    }

    public function confirmPasswordAction(): Action
    {
        return PasswordButtonAction::make('confirmPassword')->action('doSecureAction');
    }
}

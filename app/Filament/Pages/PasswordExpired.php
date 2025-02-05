<?php

namespace App\Filament\Pages;

use Filament\Forms\Form;
use Filament\Facades\Filament;
use Filament\Pages\SimplePage;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Illuminate\Validation\Rules\Password;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Validation\ValidationException;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Filament\Pages\Concerns\InteractsWithFormActions;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;


class PasswordExpired extends SimplePage
{
    use InteractsWithFormActions;
    use WithRateLimiting;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static bool $shouldRegisterNavigation = false;

    protected static string $view = 'filament.pages.password-expired';

    protected ?string $heading = 'Mot de passe expiré';

    protected ?string $subheading = 'Votre mot de passe a expiré. Veuillez le changer pour continuer.';

    protected int $passwordMaxAge = 60;

    public ?array $data = [];


    public function mount(): void
    {
        if (!Filament::auth()->check()) {
            redirect()->intended(Filament::getUrl());
        }

        $this->passwordMaxAge = config('auth.password_expires_days') ?? 60;

        $this->form->fill();
    }


    public function getTitle(): string | Htmlable
    {
        return __('Mot de passe expiré');
    }

    public function form(Form $form): Form
    {
        return $form;
    }

    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        TextInput::make('currentPassword')
                            ->label(__('Mot de passe actuel'))
                            ->password()
                            ->required(),
                        TextInput::make('newPassword')
                            ->label(__('Nouveau mot de passe'))
                            ->password()
                            ->required()
                            ->rule(Password::default())
                            ->same('newPasswordConfirmation')
                            ->validationAttribute(__('filament-panels::pages/auth/register.form.password.validation_attribute')),
                        TextInput::make('newPasswordConfirmation')
                            ->label(__('Confirmer nouveau mot de passe'))
                            ->password()
                            ->required()
                    ])
                    ->statePath('data'),
            ),
        ];
    }


    public function updatePassword()
    {
        try {
            $this->rateLimit(5);
        } catch (TooManyRequestsException $exception) {
            Notification::make()
                ->title(__('filament-panels::pages/auth/register.notifications.throttled.title', [
                    'seconds' => $exception->secondsUntilAvailable,
                    'minutes' => ceil($exception->secondsUntilAvailable / 60),
                ]))
                ->body(array_key_exists('body', __('filament-panels::pages/auth/register.notifications.throttled') ?: []) ? __('filament-panels::pages/auth/register.notifications.throttled.body', [
                    'seconds' => $exception->secondsUntilAvailable,
                    'minutes' => ceil($exception->secondsUntilAvailable / 60),
                ]) : null)
                ->danger()
                ->send();

            return null;
        }

        $user = auth()->user();

        $data = $this->form->getState();

        if (!Hash::check($data['currentPassword'], $user->password)) {
            throw ValidationException::withMessages([
                'data.currentPassword' => __('Mot de passe actuel incorrect'),
            ]);
        }

        $user->password = Hash::make($data['newPassword']);
        $user->password_changed_at = now()->toDateTimeString();
        $user->save();

        session()->forget('password_hash_' . Filament::getCurrentPanel()->getAuthGuard());
        Filament::auth()->login($user);
        $this->reset(["data"]);

        redirect('/');
        return $this->getSuccessNotification();
    }

    /**
     * @return array<Action | ActionGroup>
     */
    protected function getFormActions(): array
    {
        return [
            $this->getUpdatePasswordFormAction(),
        ];
    }

    public function getUpdatePasswordFormAction(): Action
    {
        return Action::make('updatePassword')
            ->label(__('Enregistrer'))
            ->submit('updatePassword');
    }

    protected function hasFullWidthFormActions(): bool
    {
        return true;
    }

    protected function getSuccessNotification(): Notification
    {
        return Notification::make()
            ->title(__('Mot de passe mis à jour!'))
            ->body(__('Nouveau mot de passe enregistré! <br>Il est valide jusqu\'au ' . now()->addDays($this->passwordMaxAge)->format('d M. Y') . ' (' . $this->passwordMaxAge . 'jours).'))
            ->success()
            ->send();
    }
}

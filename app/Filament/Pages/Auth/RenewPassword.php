<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Components\TextInput;
use Yebor974\Filament\RenewPassword\Pages\Auth\RenewPassword as BaseRenewPassword;
use Illuminate\Validation\Rules\Password as PasswordRule;

class RenewPassword extends BaseRenewPassword
{
    /**
     * @return array<int | string, string | Form>
     */
    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema(
                        [
                        TextInput::make('currentPassword')
                            ->label(__("Mot de passe actuel"))
                            ->password()
                            ->required()
                            ->currentPassword(),
                        TextInput::make('password')
                            ->label(__('filament-renew-password::renew-password.form.password.label'))
                            ->password()
                            ->required()
                            ->rules(['different:data.currentPassword', PasswordRule::default()->mixedCase()->symbols()]),
                        TextInput::make('PasswordConfirmation')
                            ->label(__('filament-renew-password::renew-password.form.password-confirmation.label'))
                            ->password()
                            ->required()
                            ->same('password'),
                        ]
                    )
                    ->statePath('data'),
            ),
        ];
    }
}

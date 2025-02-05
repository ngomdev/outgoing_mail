<?php

namespace App\Livewire;

use Filament\Forms;
use App\Models\User;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;
use Jeffgreco13\FilamentBreezy\Livewire\PersonalInfo;

class CustomPersonalInfo extends PersonalInfo
{
    public array $only = ['registration_number', 'name', 'email'];

    protected function getRegistrationNumberComponent(): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make('registration_number')
            ->label("Matricule")
            ->required()
            ->disabled();
    }

    protected function getNameComponent(): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make('name')
            ->label("Nom complet")
            ->required();
    }

    protected function getEmailComponent(): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make('email')
            ->required()
            ->disabled();
    }

    protected function getPhoneComponent(): PhoneInput
    {
        return PhoneInput::make('phone')
            ->required()
            ->initialCountry('sn')
            ->autoPlaceholder('xx xxx xx xx')
            ->placeholder('xx xxx xx xx')
            ->unique(table: User::class, column: 'phone', ignoreRecord: true)
            ->label("Téléphone");
    }

    protected function getProfileFormSchema()
    {
        $groupFields = Forms\Components\Group::make([
            $this->getRegistrationNumberComponent(),
            $this->getEmailComponent(),
            $this->getNameComponent(),
            $this->getPhoneComponent(),
        ])->columnSpan(2);

        return ($this->hasAvatars)
            ? [filament('filament-breezy')->getAvatarUploadComponent(), $groupFields]
            : [$groupFields];
    }
}

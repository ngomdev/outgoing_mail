<?php

namespace App\Filament\Resources\SecurityModule\UserResource\Pages;

use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Validation\ValidationException;
use App\Filament\Resources\SecurityModule\UserResource;
use Filament\Notifications\Actions\Action as NotificationAction;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    private string $role;


    protected function mutateFormDataBeforeCreate(array $data): array
    {

        $data['password'] = User::generatePassword();

        $data['password_changed_at'] = now()->toDateTimeString();

        $data['email_verified_at'] = now()->toDateTimeString();

        $this->role = $data['role'];

        return $data;
    }

    protected function afterCreate(): void
    {
        $this->record->roles()->sync([$this->role]);
        User::sendSetPasswordLink($this->record);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function onValidationError(ValidationException $exception): void
    {
        Notification::make()
            ->title('Oups!')
            ->body($exception->getMessage())
            ->actions([
                NotificationAction::make('OK')
                    ->button()
                    ->close()
            ])
            ->persistent()
            ->danger()
            ->send();
    }
}

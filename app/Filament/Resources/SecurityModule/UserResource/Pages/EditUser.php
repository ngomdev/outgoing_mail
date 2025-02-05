<?php

namespace App\Filament\Resources\SecurityModule\UserResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Validation\ValidationException;
use App\Filament\Resources\SecurityModule\UserResource;
use Filament\Notifications\Actions\Action as NotificationAction;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    private string $role;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
        ];
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

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->role = $data['role'];

        return $data;
    }

    protected function afterSave(): void
    {
        $this->record->roles()->sync([$this->role]);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

}

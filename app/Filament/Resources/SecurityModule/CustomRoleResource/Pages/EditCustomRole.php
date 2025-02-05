<?php

namespace App\Filament\Resources\SecurityModule\CustomRoleResource\Pages;

use Illuminate\Support\Arr;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use BezhanSalleh\FilamentShield\Support\Utils;
use Illuminate\Validation\ValidationException;
use App\Filament\Resources\SecurityModule\CustomRoleResource;
use Filament\Notifications\Actions\Action as NotificationAction;
use BezhanSalleh\FilamentShield\Resources\RoleResource\Pages\EditRole;

class EditCustomRole extends EditRole
{
    protected static string $resource = CustomRoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {

        $this->permissions = collect($data)
            ->filter(function ($permission, $key) {
                return !in_array($key, ['name', 'is_role_courier', 'select_all']);
            })
            ->values()
            ->flatten();

        return Arr::only($data, ['display_name', 'is_role_courier']);
    }


    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterSave(): void
    {
        $permissionModels = collect();
        $this->permissions->each(function ($permission) use ($permissionModels) {
            $permissionModels->push(Utils::getPermissionModel()::firstOrCreate([
                'name' => $permission,
            ]));
        });

        $this->record->syncPermissions($permissionModels);
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

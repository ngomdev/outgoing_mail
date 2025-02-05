<?php

namespace App\Filament\Resources\SecurityModule\CustomRoleResource\Pages;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Filament\Notifications\Notification;
use BezhanSalleh\FilamentShield\Support\Utils;
use Illuminate\Validation\ValidationException;
use App\Filament\Resources\SecurityModule\CustomRoleResource;
use Filament\Notifications\Actions\Action as NotificationAction;
use BezhanSalleh\FilamentShield\Resources\RoleResource\Pages\CreateRole;

class CreateCustomRole extends CreateRole
{
    protected static string $resource = CustomRoleResource::class;

    public Collection $permissions;

    protected function mutateFormDataBeforeCreate(array $data): array
    {

        $data['name'] = $data['display_name'];

        $this->permissions = collect($data)
            ->filter(function ($permission, $key) {
                return !in_array($key, ['name', 'is_role_courier', 'select_all']);
            })
            ->values()
            ->flatten();

        return Arr::only($data, ['name', 'display_name', 'is_role_courier']);
    }

    protected function afterCreate(): void
    {
        $permissionModels = collect();
        $this->permissions->each(function ($permission) use ($permissionModels) {
            $permissionModels->push(Utils::getPermissionModel()::firstOrCreate([
                /** @phpstan-ignore-next-line */
                'name' => $permission
            ]));
        });

        $this->record->syncPermissions($permissionModels);
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

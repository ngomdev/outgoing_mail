<?php

namespace App\Filament\Resources\SecurityModule\CustomRoleResource\Pages;


use App\Models\User;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Support\Colors\Color;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\SecurityModule\CustomRoleResource;

class ViewCustomRole extends ViewRecord
{
    protected static string $resource = CustomRoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->visible(fn () => $this->record->is_active),
            Action::make('deactivate')
                ->requiresConfirmation()
                ->label(fn (): string => $this->record->is_active ? 'Désactiver' : 'Activer')
                ->icon(fn (): string => $this->record->is_active ? 'heroicon-m-lock-closed' : 'heroicon-m-lock-open')
                ->color(fn () => $this->record->is_active ? Color::Red : Color::Emerald)
                ->modalDescription(fn (): string => $this->record->is_active ? "Êtes-vous sur de vouloir désactiver ce role?" : "Êtes-vous sur de vouloir activer ce role?")
                ->action(function () {
                    $activeRoleUsers = User::query()
                        ->where('is_active', true)
                        ->whereHas('roles', fn ($query) => $query->where('name', $this->record->name))->count();
                    if ($this->record->is_active && $activeRoleUsers > 0) {
                        Notification::make()
                            ->warning()
                            ->title(__('Oups!'))
                            ->body("Le rôle ne peut pas être désactivé car il est attribué à des utilisateurs actifs.")
                            ->persistent()
                            ->send();
                    } else {
                        $this->record->is_active = !$this->record->is_active;
                        $this->record->save();

                        Notification::make()
                            ->success()
                            ->title(fn () => $this->record->is_active ? __('Activation role') : __('Désactivation role'))
                            ->body(fn () => $this->record->is_active ? "Role activée avec succés!"  : "Role désactivée avec succés!")
                            ->persistent()
                            ->send();
                    }
                }),
        ];
    }
}

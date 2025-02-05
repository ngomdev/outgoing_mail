<?php
namespace App\Filament\Resources\CourierModule\RecipientResource\Pages;


use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\CourierModule\RecipientResource;

class EditRecipient extends EditRecord
{
    protected static string $resource = RecipientResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
        ];
    }
}

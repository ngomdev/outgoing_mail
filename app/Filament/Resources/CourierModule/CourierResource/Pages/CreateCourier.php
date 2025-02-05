<?php

namespace App\Filament\Resources\CourierModule\CourierResource\Pages;

use Filament\Actions;
use App\Enums\CourierStatus;
use App\Enums\RecipientType;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\CourierModule\CourierResource;

class CreateCourier extends CreateRecord
{
    protected static string $resource = CourierResource::class;

    private $mainRecipientId;
    private $mainContactId;

    private $mainCourserId;

    private $mainComment;

    protected function getFooterActions(): array
    {
        return [];
    }

    public function getFormActions(): array
    {
        return [
            $this->getCancelFormAction()
        ];
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->mainRecipientId = $data['main_recipient_id'];
        $this->mainContactId = array_key_exists('main_contact_id', $data) ? $data['main_contact_id'] : null;
        $this->mainCourserId = $data['main_courser_id'];
        $this->mainComment = array_key_exists('main_comment', $data) ? $data['main_comment'] : null;
        $data['created_by'] = auth()->id();

        return $data;
    }

    protected function afterCreate(): void
    {
        // Add the main recipient
        $this->record->coursers()->create([
            'recipient_id' => $this->mainRecipientId,
            'type' => RecipientType::MAIN->value,
            'contact_id' => $this->mainContactId,
            'user_id' => $this->mainCourserId,
            'comment' => $this->mainComment,
            'status' => CourierStatus::DRAFT->value
        ]);
    }
}

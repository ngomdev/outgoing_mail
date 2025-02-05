<?php

namespace App\Filament\Resources\CourierModule\CourierResource\Pages;

use Filament\Actions;
use App\Models\Document;
use App\Enums\CourierStatus;
use App\Enums\RecipientType;
use App\Jobs\FirebaseNotificationJob;
use Illuminate\Support\Facades\Storage;
use App\Jobs\CourierUserNotificationJob;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\CourierModule\CourierResource;

class EditCourier extends EditRecord
{
    protected static string $resource = CourierResource::class;

    private $mainRecipientId;
    private $mainContactId;
    private $mainCourserId;
    private $mainComment;
    private $selectedDocIds;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
        ];
    }

    protected function getFooterActions(): array
    {
        return [];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }

    public function getFormActions(): array
    {
        return [
            $this->getCancelFormAction()
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->mainRecipientId = array_key_exists('main_recipient_id', $data) ? $data['main_recipient_id'] : null;
        $this->mainContactId = array_key_exists('main_contact_id', $data) ? $data['main_contact_id'] : null;
        $this->mainCourserId = $data['main_courser_id'];
        $this->mainComment = array_key_exists('main_comment', $data) ? $data['main_comment'] : null;

        $this->selectedDocIds = array_key_exists('attachment_doc_ids', $data) ? $data['attachment_doc_ids'] : [];

        return $data;
    }

    protected function afterSave(): void
    {
        $mainData = $this->record->coursers->where('type', RecipientType::MAIN)?->first();

        // save selected docs
        // Assuming $this->selectedDocIds is an array of document IDs
        $attachments = $this->record->attachments ?? [];
        foreach ($this->selectedDocIds as $docId) {
            $doc = Document::find($docId);

            if ($doc) {
                // Extract the filename from the doc_path
                $filename = pathinfo($doc->doc_path, PATHINFO_BASENAME);

                // Generate the destination path in storage/courier-attachments/{id}/
                $destinationPath = "courier-attachments/{$this->record->id}/$filename";

                // Copy the file to the new location
                Storage::disk('public')->copy("doc-attachments/{$doc->id}/$filename", $destinationPath);

                // Now, $destinationPath contains the new path in storage/courier-attachments/{id}/filename.extension
                // You can use this path as needed

                // Append the new attachment path to the attachments array

                $attachments[] = $destinationPath;
            }
        }
        // Set the updated attachments array directly
        $this->record->setAttribute('attachments', $attachments);
        $this->record->save();

        // Update the main recipient
        $this->record->coursers()->updateOrCreate(
            [
                'type' => RecipientType::MAIN->value
            ],
            [
                'recipient_id' => $this->mainRecipientId ?? $this->record->mainRecipient?->id,
                'contact_id' => $this->mainContactId ?? $this->record->mainContact?->id,
                'user_id' => $this->mainCourserId ?? $this->record->mainCourser?->id,
                'comment' => $this->mainComment ? $this->mainComment : ($mainData ? $mainData->comment : null),
                'status' => ($mainData && $mainData->status) ? $mainData->status : CourierStatus::DRAFT->value
            ]
        );


        // if new coursers, notify them and set their assignment date to now ONLY if not DRAFT
        if ($this->record->status !== CourierStatus::DRAFT) {
            $newCoursers = $this->record->coursers()->where('notified', false);

            if ($newCoursers) {
                $newCoursers->update(
                    [
                        'assignment_date' => now(),
                        'status' => CourierStatus::INITIATED->value

                    ]
                );

                // Send notification to coursers of this courier
                CourierUserNotificationJob::dispatch($this->record);
                // Send firebase notification to all courier users
                FirebaseNotificationJob::dispatch($newCoursers->get(), "assigné");
            }
        }
    }
}

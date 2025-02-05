<?php

namespace App\Filament\Resources\DocumentModule\DocumentResource\Pages;


use Filament\Actions;
use App\Enums\RoleEnum;
use App\Enums\DocStatus;
use App\Models\CustomRole;
use App\Services\DocManipulationService;
use Filament\Resources\Pages\EditRecord;
use App\Jobs\SendInformativeNotificationJob;
use App\Filament\Resources\DocumentModule\DocumentResource;

class EditDocument extends EditRecord
{
    protected static string $resource = DocumentResource::class;
    private $oldInitiator;
    private $initiatorId;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
        ];
    }

    public function getFormActions(): array
    {
        return [
            $this->getCancelFormAction()
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->oldInitiator = $this->record->initiatorUser;
        $this->initiatorId = $data['initiator_id'] ?? auth()->id();

        return $data;
    }


    protected function afterSave()
    {
        if ($this->initiatorId !== 'autre' && $this->initiatorId !== $this->oldInitiator->id) {
            // detach previous initiator then attach new one
            $this->record->users()->detach($this->record->initiatorUser);

            $initiatorRole = CustomRole::firstOrCreate(
                ['name' => RoleEnum::INITIATOR->getLabel()],
                [
                    'guard_name' => 'web',
                    'is_role_courier' => true
                ]
            );

            $this->record->users()->attach($this->initiatorId, [
                'role_id' => $initiatorRole->id
            ]);
        }

        $recipientsCount = $this->record->documentUsers()
            ->whereHas('user', function ($query) {
                $query->where('is_active', true);
            })
            ->whereHas('role', function ($query) {
                $query->where('name', '!=',  RoleEnum::INITIATOR->getLabel());
            })
            ->where('notified', false)->count();
        // Send notification to validators
        if ($this->record->status !== DocStatus::DRAFT && $recipientsCount > 0) {
            // The boolean represent validatorAdded value which is true here
            SendInformativeNotificationJob::dispatch(true, $this->record);
        }

        // TODO Activate track revisions mode on doc

        $docPath = public_path('storage/' . $this->record->doc_path);

        (new DocManipulationService())->trackRevisions($docPath, true);
    }
}

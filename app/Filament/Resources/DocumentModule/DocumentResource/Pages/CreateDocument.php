<?php

namespace App\Filament\Resources\DocumentModule\DocumentResource\Pages;


use App\Enums\RoleEnum;
use App\Enums\DocAction;
use App\Models\CustomRole;
use App\Services\DocManipulationService;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\DocumentModule\DocumentResource;

class CreateDocument extends CreateRecord
{
    protected static string $resource = DocumentResource::class;

    private $initiatorId;

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

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Document créé!')
            ->duration(100000)
            ->body('Cliquez sur Démarrer le Processus après vous être assuré(e) que tout est en ordre afin de notifier le(s) parapheur(s)');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // initiateur
        $this->initiatorId = $data['initiator_id'] ?? auth()->id();

        $data['created_by'] = auth()->id();

        return $data;
    }

    protected function afterCreate(): void
    {
        // Runs after the form fields are saved to the database.
        // demandeur
        if ($this->initiatorId !== 'autre') {
            $initiatorRole = CustomRole::firstOrCreate(
                ['name' => RoleEnum::INITIATOR->getLabel()],
                [
                    'guard_name' => 'web',
                    'is_role_courier' => true
                ]
            );

            if ($this->initiatorId) {
                $this->record->documentUsers()->create(
                    [
                    "user_id" => $this->initiatorId,
                    "role_id" => $initiatorRole->id
                    ]
                );
            }
        }

        $this->record->docHistory()->create(
            [
            "user_id" => $this->initiatorId,
            "action" => DocAction::CREATE,
            "content" => $this->record->content ?? null,
            "doc_path" => $this->record->doc_path ?? null,
            ]
        );


        // Activate track revisions mode on doc

        $docPath = public_path('storage/'. $this->record->doc_path);

        (new DocManipulationService())->trackRevisions($docPath, true);

    }


}

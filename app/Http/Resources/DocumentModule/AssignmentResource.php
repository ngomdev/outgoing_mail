<?php

namespace App\Http\Resources\DocumentModule;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\SecurityModule\UserResource;

class AssignmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            "id" => $this->id,
            "role" => $this->role->name,
            "action_request_date" => $this->action_request_date,
            "validated" => $this->user->lastDocValidationHistory($this->document)?->is_active ?? false,
            "canPassTurn" => $this->document->nextValidator && $this->document->currentValidator?->user->id === $request->user()->id,
            "document" => [
                "id" => $this->document->id,
                "name" => $this->document->name,
                "object" => $this->document->object,
                "doc_type" => $this->document->doc_type->getLabel(),
                "validation_delay" => $this->document->doc_urgency->getValue(),
                "status" => $this->document->status,
                "priority" => $this->document->doc_urgency,
                "assignees" => $this->getParapheursUser(),
                "validation_percentage" => $this->getDocValidationPercentage(),
                "current_validator" => $this->document->currentValidator?->user,
                "signataire" => $this->document->signataires()?->first()?->user
            ]
        ];
    }

    private function getParapheursUser()
    {
        $assignees = [];
        if ($this->document->parapheurs) {
            $assignees = $this->document->parapheurs
                ->map(fn ($parapheur) => $parapheur->user)
                ->filter()
                ->all();
        }

        return UserResource::collection($assignees);
    }

    private function getDocValidationPercentage()
    {
        $percentage = 0;
        $parapheurs = $this->document->parapheurs;
        $validatedCount = 0;

        foreach ($parapheurs as $parapheur) {
            $lastValidation = $parapheur->user->lastDocValidationHistory($this->document);
            if ($lastValidation && $lastValidation->is_active) {
                $validatedCount += 1;
            }
        }

        $percentage = ($validatedCount * 100) / count($parapheurs);

        // Round the percentage to 2 decimal places
        $roundedPercentage = round($percentage, 2);

        // Convert to float to remove trailing zeros
        $floatPercentage = (float)$roundedPercentage;

        return $floatPercentage;

    }
}

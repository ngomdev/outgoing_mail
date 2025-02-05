<?php

namespace App\Http\Resources\SecurityModule;

use App\Enums\RoleEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            "name" => $this->name,
            "email" => $this->email,
            "phone" => $this->phone,
            "avatar" => $this->avatar_url ? asset("storage/$this->avatar_url") : null,
            "role" => $this->getRoleNames()->first(),
            "fcm_token" => $this->fcm_token,
            "canViewCouriers" => $this->hasAnyRole([RoleEnum::RES_SUIVI->getLabel(), RoleEnum::AG->getLabel()]),
            "canViewDocs" => !$this->hasAnyRole(RoleEnum::COURSER->getLabel()),
            "canViewAllDocs" => $this->hasAnyRole([RoleEnum::RES_SUIVI->getLabel(), RoleEnum::AG->getLabel()]) || $this->initiatedDocuments()->count() > 0,
            "unread_notifications_count" => $this->firebaseNotifications()->where('read_at', null)->count(),
        ];
    }
}

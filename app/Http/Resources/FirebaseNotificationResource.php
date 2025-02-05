<?php

namespace App\Http\Resources;

use App\Http\Resources\CourierModule\CourierResource;
use App\Http\Resources\DocumentModule\AssignmentResource;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\DocumentUser;
use App\Models\CourierUser;

class FirebaseNotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $target_id = (int) $this->target_id;
        $target = DocumentUser::find($this->target_id);

        if($this->target_type === "courier"){
            $target = CourierUser::find($this->target_id);
        }

        return [
            "id" => $this->id,
            "target_type" => $this->target_type,
            "target" => $this->target_type === "courier" ? new CourierResource($target) : new AssignmentResource($target),
            "action" => $this->action,
            "title" => $this->title,
            "body" => $this->body,
            "read_at" => $this->read_at,
            "updated_at" => $this->updated_at,
            "created_at" => $this->created_at,
        ];
    }
}

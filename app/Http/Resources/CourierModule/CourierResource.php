<?php

namespace App\Http\Resources\CourierModule;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourierResource extends JsonResource
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
            "courier_id" => $this->courier_id,
            "courier_number" => $this->courier->courier_number,
            "object" => $this->courier->object,
            "type" => $this->courier?->document?->doc_type?->getLabel(),
            "recipient" => [
                "id" => $this->recipient->id,
                "name" => $this->recipient->name,
                "email" => $this->recipient->email,
                "phone" => $this->recipient->phone,
                "address" => $this->recipient->address
            ],
            "contact" => [
                "id" => $this->contact?->id,
                "name" => $this->contact?->name,
                "email" => $this->contact?->email,
                "phone" => $this->contact?->phone
            ],
            "courser" => [
                "id" => $this->courser?->id,
                "name" => $this->courser?->name,
                "email" => $this->courser?->email,
                "phone" => $this->courser?->phone,
                "avatar" => $this->avatar_url ? asset("storage/$this->avatar_url") : null
            ],
            "assignment_date" => $this->assignment_date,
            "pickup_date" => $this->pickup_date,
            "deposit_date" => $this->deposit_date,
            "status" => $this->status,
            "rejection_motive" => $this->rejection_motive,
            "created_at" => $this->created_at
        ];
    }
}

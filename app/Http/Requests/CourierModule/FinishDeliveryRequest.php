<?php

namespace App\Http\Requests\CourierModule;

use Illuminate\Foundation\Http\FormRequest;

class FinishDeliveryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "courier_id" => [
                "required",
                "exists:couriers,id"
            ],
            "delivery_id" => [
                "required",
                "exists:courier_user,id"
            ],
            "status" => [
                'required',
                'in:accepted,rejected'
            ],
            "rejection_motive" => [
                'nullable',
                'string'
            ],
            "file" => [
                'nullable',
                'file', // Ensure it's a valid file
                'mimes:png,jpeg,jpg', // Add additional file type validations if needed
                'max:2048', // Limit file size (in kilobytes)
            ],
            "lat" => [
                'nullable',
                'string'
            ],
            "lng" => [
                'nullable',
                'string'
            ]
        ];
    }
}

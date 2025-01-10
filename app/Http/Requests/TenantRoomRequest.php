<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TenantRoomRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Set to true if you want to allow all authorized users to use this request
    }

    public function rules()
    {
        return [
            'primary_tenant_id' => [
                'required',
                'integer',
                function ($attribute, $value, $fail) {
                    if (\App\Models\TenantRoom::where('secondary_tenant_id', $value)->whereNull('deleted_at')->exists()) {
                        $fail('The primary tenant is already assigned as a secondary tenant.');
                    }
                },
            ],
            'secondary_tenant_id' => [
                'nullable',
                'integer',
                function ($attribute, $value, $fail) {
                    if (\App\Models\TenantRoom::where('primary_tenant_id', $value)->whereNull('deleted_at')->exists()) {
                        $fail('The secondary tenant is already assigned as a primary tenant.');
                    }
                },
            ],
            'room_id' => 'required|exists:rooms,id',
            'status' => 'required|in:active,inactive',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'note' => 'nullable|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'primary_tenant_id.required' => 'A primary tenant is required.',
            'room_id.required' => 'The room is required.',
            'room_id.exists' => 'The selected room is invalid.',
        ];
    }
}

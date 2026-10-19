<?php

namespace App\Http\Requests\Estimate;

use Illuminate\Foundation\Http\FormRequest;

class EstimateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'service_id' => ['required', 'exists:services,id'],
            'difficulty_level' => ['nullable', 'in:light,medium,heavy'],
            'distance_km' => ['nullable', 'numeric', 'min:0'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'partner_id' => ['nullable', 'exists:users,id'],
        ];
    }
}

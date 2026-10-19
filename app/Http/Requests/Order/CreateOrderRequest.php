<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isCustomer();
    }

    public function rules(): array
    {
        return [
            'service_id' => ['required', 'exists:services,id'],
            'address_id' => ['nullable', 'exists:addresses,id'],
            'problem_description' => ['required', 'string', 'min:10'],
            'address_text' => ['nullable', 'string'],
            'landmark' => ['nullable', 'string', 'max:255'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'scheduled_at' => ['nullable', 'date'],
            'difficulty_level' => ['nullable', 'in:light,medium,heavy'],
            'assignment_method' => ['nullable', 'in:auto,manual'],
            'partner_id' => ['nullable', 'exists:users,id'],
            'photos' => ['nullable', 'array', 'max:5'],
            'photos.*' => ['file', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
        ];
    }
}

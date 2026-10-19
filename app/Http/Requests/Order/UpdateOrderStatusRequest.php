<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => [
                'required',
                'string',
                'in:accepted,on_the_way,arrived,in_progress,awaiting_customer_confirmation,completed,cancelled'
            ],
            'note' => ['nullable', 'string', 'max:500'],
            'final_price' => ['nullable', 'numeric', 'min:0'],
            'additional_fee' => ['nullable', 'numeric', 'min:0'],
            'photo' => ['nullable', 'file', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'photo_type' => ['nullable', 'in:before_work,after_work,evidence'],
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'country' => ['required', 'string', 'max:120'],
            'name' => ['required', 'string', 'max:120'],
            'company_name' => ['nullable', 'string', 'max:120'],
            'address' => ['required', 'string', 'max:255'],
            'address2' => ['nullable', 'string', 'max:255'],
            'province' => ['required', 'string', 'max:120'],
            'zip' => ['required', 'string', 'max:40'],
            'email' => ['required', 'string', 'email', 'max:120'],
            'phone' => ['required', 'string', 'max:40'],
            'notes' => ['nullable', 'string', 'max:5000'],
            'total' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'    => ['required', 'string', 'min:2', 'max:120'],
            'email'   => ['required', 'string', 'email:rfc', 'max:254'],
            'subject' => ['nullable', 'string', 'min:2', 'max:200'],
            'phone'   => ['nullable', 'string', 'min:6', 'max:40'],
            'message' => ['required', 'string', 'min:15', 'max:3000'],
        ];
    }
}

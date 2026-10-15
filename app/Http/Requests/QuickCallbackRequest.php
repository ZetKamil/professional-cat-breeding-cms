<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuickCallbackRequest extends FormRequest
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
            'phone'          => ['required', 'string', 'min:7', 'max:30', 'regex:/^[0-9\s\-+()]{7,30}$/'],
            'cat_name'       => ['nullable', 'string', 'max:100'],
            'cat_breed'      => ['nullable', 'string', 'max:100'],
            'traffic_source' => ['nullable', 'string', 'max:100'],
            '_hp_website'    => ['nullable', 'prohibited'],
        ];
    }

    /**
     * Custom validation error messages in Polish.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'phone.required'         => 'Prosimy o podanie numeru telefonu.',
            'phone.min'              => 'Numer telefonu jest zbyt krótki.',
            'phone.max'              => 'Wprowadzony numer telefonu jest zbyt długi.',
            'phone.regex'            => 'Numer telefonu może zawierać tylko cyfry, spacje, nawiasy i znak +.',
            '_hp_website.prohibited' => 'Wykryto niedozwoloną aktywność automatyczną (spam).',
        ];
    }
}

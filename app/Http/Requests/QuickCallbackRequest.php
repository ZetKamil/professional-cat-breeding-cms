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
     * Prepare inputs for validation (cast numbers to string, trim).
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('phone') && ! is_string($this->phone)) {
            $this->merge(['phone' => (string) $this->phone]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'phone' => [
                'required',
                'string',
                'min:7',
                'max:35',
                'regex:/^[0-9\s\-+().]{7,35}$/',
                function (string $attribute, mixed $value, \Closure $fail) {
                    $digitsOnly = preg_replace('/\D/', '', (string) $value);
                    if (strlen($digitsOnly) < 7) {
                        $fail('Numer telefonu musi zawierać co najmniej 7 cyfr.');
                    }
                    if (strlen($digitsOnly) > 15) {
                        $fail('Numer telefonu nie może zawierać więcej niż 15 cyfr.');
                    }
                },
            ],
            'cat_name'       => ['nullable', 'string', 'max:100'],
            'cat_breed'      => ['nullable', 'string', 'max:100'],
            'page_url'       => ['nullable', 'string', 'max:255'],
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
            'phone.regex'            => 'Numer telefonu może zawierać tylko cyfry, spacje, myślniki, kropki, nawiasy i znak +.',
            '_hp_website.prohibited' => 'Wykryto niedozwoloną aktywność automatyczną (spam).',
        ];
    }
}

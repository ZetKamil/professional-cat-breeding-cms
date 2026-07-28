<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StoreCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $name = (string) $this->input('name', '');
        $slug = (string) $this->input('slug', '');

        $this->merge([
            'name' => trim($name),
            'slug' => trim($slug) !== '' ? Str::slug($slug) : Str::slug($name),
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'min:2',
                'max:255',
                Rule::unique('categories', 'name'),
            ],
            'slug' => [
                'required',
                'string',
                'min:2',
                'max:255',
                Rule::unique('categories', 'slug'),
            ],
            'description' => ['nullable', 'string', 'max:2000'],
        ];
    }
}

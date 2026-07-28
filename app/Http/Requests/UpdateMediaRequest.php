<?php

namespace App\Http\Requests;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMediaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $parentType = $this->input('parent_type');
        $parentId = $this->input('parent_id');

        $mediableType = match ($parentType) {
            'post' => Post::class,
            'user' => User::class,
            default => null,
        };

        $this->merge([
            'mediable_type' => $mediableType,
            'mediable_id' => $parentId ?: null,
            'is_featured' => $this->boolean('is_featured'),
            'sort_order' => (int) ($this->input('sort_order', 0)),
        ]);
    }

    public function rules(): array
    {
        return [
            'parent_type' => ['nullable', Rule::in(['post', 'user'])],
            'parent_id' => ['nullable', 'integer', 'min:1'],

            'mediable_type' => ['nullable', 'string'],
            'mediable_id' => ['nullable', 'integer', 'min:1'],

            'upload' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif,svg', 'max:5120'],

            'alt_text' => ['nullable', 'string', 'max:255'],
            'caption' => ['nullable', 'string', 'max:2000'],
            'sort_order' => ['required', 'integer', 'min:0'],
            'is_featured' => ['required', 'boolean'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $parentType = $this->input('parent_type');
            $parentId = $this->input('parent_id');

            if ($parentType === 'post' && $parentId && ! Post::query()->whereKey($parentId)->exists()) {
                $validator->errors()->add('parent_id', 'De geselecteerde post bestaat niet.');
            }

            if ($parentType === 'user' && $parentId && ! User::query()->whereKey($parentId)->exists()) {
                $validator->errors()->add('parent_id', 'De geselecteerde user bestaat niet.');
            }

            if (($parentType && ! $parentId) || (! $parentType && $parentId)) {
                $validator->errors()->add('parent_id', 'Kies zowel een parent type als een parent record, of laat beide leeg.');
            }
        });
    }
}

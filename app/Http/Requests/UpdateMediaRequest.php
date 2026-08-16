<?php

namespace App\Http\Requests;

use App\Models\Animal;
use App\Models\Media;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMediaRequest extends FormRequest
{
    public function authorize(): bool
    {
        $media = $this->route('medium') ?: $this->route('media');

        return $this->user()?->can('update', $media instanceof Media ? $media : Media::findOrFail($media)) ?? true;
    }

    protected function prepareForValidation(): void
    {
        $parentType = $this->input('parent_type');
        $parentId = $this->input('parent_id');

        $mediableType = match ($parentType) {
            'post' => Post::class,
            'user' => User::class,
            'animal' => Animal::class,
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
            'parent_type' => ['nullable', 'string'],
            'parent_id' => ['nullable', 'integer', 'min:1'],

            'mediable_type' => ['nullable', 'string'],
            'mediable_id' => ['nullable', 'integer', 'min:1'],

            'upload' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif,svg', 'max:10240'],

            'title' => ['nullable', 'string', 'max:255'],
            'alt_text' => ['nullable', 'string', 'max:255'],
            'caption' => ['nullable', 'string', 'max:2000'],
            'copyright' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_featured' => ['nullable', 'boolean'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $parentType = $this->input('parent_type');
            $parentId = $this->input('parent_id');

            if ($parentType === 'post' && $parentId && ! Post::query()->whereKey($parentId)->exists()) {
                $validator->errors()->add('parent_id', 'Wybrany wpis (Post) nie istnieje.');
            }

            if ($parentType === 'user' && $parentId && ! User::query()->whereKey($parentId)->exists()) {
                $validator->errors()->add('parent_id', 'Wybrany użytkownik (User) nie istnieje.');
            }

            if ($parentType === 'animal' && $parentId && ! Animal::query()->whereKey($parentId)->exists()) {
                $validator->errors()->add('parent_id', 'Wybrane zwierzę (Animal) nie istnieje.');
            }
        });
    }
}


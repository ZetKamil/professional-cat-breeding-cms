<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StorePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Input normaliseren vóór validatie.
     *
     * Wat doen we hier?
     * - title trimmen
     * - slug automatisch genereren als die leeg is
     * - categories altijd als array doorgeven
     * - published_at automatisch invullen als post gepubliceerd is
     *   maar er nog geen datum werd meegegeven
     */
    protected function prepareForValidation(): void
    {
        $title = trim((string) $this->input('title', ''));
        $slug = trim((string) $this->input('slug', ''));
        $isPublished = $this->boolean('is_published');
        $publishedAt = $this->input('published_at');

        $this->merge([
            'title' => $title,
            'slug' => $slug !== '' ? Str::slug($slug) : Str::slug($title),
            'is_published' => $isPublished,
            'categories' => $this->input('categories', []),
            'published_at' => $isPublished
                ? ($publishedAt ?: now())
                : null,
        ]);
    }

    public function rules(): array
    {
        return [
            'user_id' => ['nullable', 'integer', Rule::exists('users', 'id')],
            'title' => ['required', 'string', 'min:3', 'max:255'],
            'slug' => ['required', 'string', 'min:3', 'max:255', Rule::unique('posts', 'slug')],
            'excerpt' => ['nullable', 'string', 'max:1000'],
            'body' => ['required', 'string', 'min:10'],

            'is_published' => ['required', 'boolean'],
            'published_at' => ['nullable', 'date'],

            'categories' => ['nullable', 'array'],
            'categories.*' => ['integer', Rule::exists('categories', 'id')],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ];
    }
}

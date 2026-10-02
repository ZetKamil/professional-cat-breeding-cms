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
     * - title trimmen
     * - slug automatisch genereren als die leeg is
     * - categories altijd als array doorgeven
     * - published_at automatisch invullen als post gepubliceerd is
     * - sections_json parsen naar PHP array
     * - body automatisch opbouwen uit sections (backward compat)
     * - featured_animal_ids doorgeven voor pivot sync
     */
    protected function prepareForValidation(): void
    {
        $title = trim((string) $this->input('title', ''));
        $slug = trim((string) $this->input('slug', ''));
        $isPublished = $this->boolean('is_published');
        $publishedAt = $this->input('published_at');
        if ($publishedAt !== null && trim((string) $publishedAt) === '') {
            $publishedAt = null;
        }

        // Parse sections JSON sent from the structured editor
        $sectionsRaw = $this->input('sections_json', '');
        $sections = null;
        if (is_string($sectionsRaw) && $sectionsRaw !== '') {
            $decoded = json_decode($sectionsRaw, true);
            $sections = is_array($decoded) ? $decoded : null;
        } elseif (is_array($this->input('sections'))) {
            $sections = $this->input('sections');
        }

        // Regenerate body from sections for backward compat (RSS, search index)
        $body = $this->input('body', '');
        if ($sections) {
            $body = $this->sectionsToBody($sections);
        }

        $this->merge([
            'title'               => $title,
            'slug'                => $slug !== '' ? Str::slug($slug) : Str::slug($title),
            'is_published'        => $isPublished,
            'categories'          => $this->input('categories', []),
            'published_at'        => $publishedAt ?: ($isPublished ? now() : null),
            'sections'            => $sections,
            'body'                => $body,
            'featured_animal_ids' => $this->input('featured_animal_ids', []),
        ]);
    }

    public function rules(): array
    {
        return [
            'user_id'               => ['nullable', 'integer', Rule::exists('users', 'id')],
            'title'                 => ['required', 'string', 'min:3', 'max:255'],
            'slug'                  => ['required', 'string', 'min:3', 'max:255', Rule::unique('posts', 'slug')],
            'excerpt'               => ['nullable', 'string', 'max:1000'],
            'body'                  => ['nullable', 'string'],
            'sections_json'         => ['nullable', 'string'],
            'is_published'          => ['required', 'boolean'],
            'published_at'          => ['nullable', 'date'],
            'categories'            => ['nullable', 'array'],
            'categories.*'          => ['integer', Rule::exists('categories', 'id')],
            'image'                 => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'featured_animal_ids'   => ['nullable', 'array'],
            'featured_animal_ids.*' => ['string', Rule::exists('animals', 'id')],
        ];
    }

    /**
     * Convert sections array to a plain-text body for backward compatibility.
     */
    private function sectionsToBody(array $sections): string
    {
        return collect($sections)->map(function (array $s) {
            $parts = [];
            if (!empty($s['heading'])) {
                $parts[] = '## ' . $s['heading'];
            }
            if (!empty($s['body'])) {
                $parts[] = $s['body'];
            }
            return implode("\n\n", $parts);
        })->implode("\n\n");
    }
}

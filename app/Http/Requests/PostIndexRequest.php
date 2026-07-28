<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PostIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Later kan dit naar policies verhuizen.
        return true;
    }

    public function rules(): array
    {
        return [
            // Vrije zoekterm
            'q' => ['nullable', 'string', 'max:100'],

            // Filters
            'author' => ['nullable', 'integer', 'min:1'],
            'category' => ['nullable', 'integer', 'min:1'],
            'status' => ['nullable', Rule::in(['published', 'draft'])],
            'trashed' => ['nullable', Rule::in(['with', 'only'])],

            // Sortering
            'sort' => ['nullable', Rule::in(['id', 'title', 'slug', 'created_at', 'published_at', 'is_published'])],
            'dir' => ['nullable', Rule::in(['asc', 'desc'])],

            // Pagination
            'per_page' => ['nullable', Rule::in([10, 25, 50, 100])],
        ];
    }

    /**
     * We geven altijd dezelfde keys terug aan controller en view.
     */
    public function defaults(): array
    {
        $v = $this->validated();

        return [
            'q' => (string) ($v['q'] ?? ''),
            'author' => $v['author'] ?? null,
            'category' => $v['category'] ?? null,
            'status' => $v['status'] ?? null,
            'trashed' => $this->input('trashed', ''),
            'sort' => (string) ($v['sort'] ?? 'created_at'),
            'dir' => (string) ($v['dir'] ?? 'desc'),
            'per_page' => (int) ($v['per_page'] ?? 10),
        ];
    }
}

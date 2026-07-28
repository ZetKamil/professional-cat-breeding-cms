<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Later kan je hier policies toepassen.
        return true;
    }

    public function rules(): array
    {
        return [
            // Search
            'q' => ['nullable', 'string', 'max:100'],

            // Filters
            'role' => ['nullable', 'integer', 'min:1'],
            'status' => ['nullable', Rule::in(['active', 'inactive'])],
            'verified' => ['nullable', Rule::in(['yes', 'no'])],
            'trashed' => ['nullable', Rule::in(['with', 'only'])],
            // Sorting
            'sort' => ['nullable', Rule::in(['id', 'name', 'email', 'created_at', 'is_active'])],
            'dir' => ['nullable', Rule::in(['asc', 'desc'])],

            // Pagination size
            'per_page' => ['nullable', Rule::in([10, 25, 50, 100])],
        ];
    }

    /**
     * Defaults + normalisatie.
     * Dit geeft een vaste array terug zodat controller/view altijd dezelfde keys hebben.
     */
    public function defaults(): array
    {
        $v = $this->validated();

        return [
            'q' => (string) ($v['q'] ?? ''),

            'role' => $v['role'] ?? null,
            'status' => $v['status'] ?? null,
            'verified' => $v['verified'] ?? null,
            'trashed' => $this->input('trashed', ''),
            'sort' => (string) ($v['sort'] ?? 'created_at'),
            'dir' => (string) ($v['dir'] ?? 'desc'),

            'per_page' => (int) ($v['per_page'] ?? 10),
        ];
    }
}

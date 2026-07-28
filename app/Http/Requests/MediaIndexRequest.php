<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MediaIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'q' => ['nullable', 'string', 'max:100'],
            'type' => ['nullable', Rule::in(['post', 'user', 'unattached'])],
            'featured' => ['nullable', Rule::in(['yes', 'no'])],
            'trashed' => ['nullable', Rule::in(['with', 'only'])],
            'sort' => ['nullable', Rule::in(['id', 'file_name', 'sort_order', 'created_at', 'is_featured'])],
            'dir' => ['nullable', Rule::in(['asc', 'desc'])],
            'per_page' => ['nullable', Rule::in([10, 25, 50, 100])],
        ];
    }

    public function defaults(): array
    {
        $v = $this->validated();

        return [
            'q' => (string) ($v['q'] ?? ''),
            'type' => $v['type'] ?? null,
            'featured' => $v['featured'] ?? null,
            'trashed' => $this->input('trashed', ''),
            'sort' => (string) ($v['sort'] ?? 'created_at'),
            'dir' => (string) ($v['dir'] ?? 'desc'),
            'per_page' => (int) ($v['per_page'] ?? 10),
        ];
    }
}

<?php

namespace App\Http\Requests;

use App\Enums\AnimalGender;
use App\Enums\AnimalStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AnimalIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'q' => ['nullable', 'string', 'max:100'],

            'status' => ['nullable', Rule::enum(AnimalStatus::class)],
            'gender' => ['nullable', Rule::enum(AnimalGender::class)],
            'trashed' => ['nullable', Rule::in(['with', 'only'])],

            'sort' => ['nullable', Rule::in(['id', 'name', 'breed', 'status', 'gender', 'date_of_birth', 'created_at', 'sort_order'])],
            'dir' => ['nullable', Rule::in(['asc', 'desc'])],

            'per_page' => ['nullable', Rule::in([10, 25, 50, 100])],
        ];
    }

    public function defaults(): array
    {
        $v = $this->validated();

        return [
            'q' => (string) ($v['q'] ?? ''),
            'status' => $v['status'] ?? null,
            'gender' => $v['gender'] ?? null,
            'trashed' => $this->input('trashed', ''),
            'sort' => (string) ($v['sort'] ?? 'sort_order'),
            'dir' => (string) ($v['dir'] ?? 'asc'),
            'per_page' => (int) ($v['per_page'] ?? 10),
        ];
    }
}

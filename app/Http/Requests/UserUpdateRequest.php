<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->route('user');

        return $user ? ($this->user()?->can('update', $user) ?? false) : false;
    }

    protected function prepareForValidation(): void
    {
        /**
         * We hergebruiken dezelfde normalisatie als bij store:
         * - checkbox verified (1/0) omzetten naar email_verified_at datetime/null
         *
         * Waarom hier?
         * - controller blijft clean
         * - view blijft simpel (checkbox)
         */
        $this->merge([
            'verified' => $this->boolean('verified'),
            'email_verified_at' => $this->boolean('verified') ? now() : null,
        ]);
    }

    public function rules(): array
    {
        $routeUser = $this->route('user');
        $userId = $routeUser instanceof \App\Models\User ? $routeUser->id : $routeUser;

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId)],
            'role_id' => ['required', 'exists:roles,id'],
            'is_active' => ['required', 'boolean'],
            'verified' => ['required', 'boolean'],
            'email_verified_at' => ['nullable', 'date'],

            'password' => ['nullable', 'confirmed', 'min:8', Password::defaults()],

            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }
}

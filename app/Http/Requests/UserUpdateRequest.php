<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        /**
         * Later vervangen we dit door policies.
         * Nu laten we update toe zolang auth/verified middleware actief is.
         */
        return true;
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
            'email_verified_at' => $this->boolean('verified') ? now() : null,
        ]);
    }

    public function rules(): array
    {
        /**
         * We halen de huidige user id uit de route.
         * Bij resource routes is de parameter meestal {user}.
         * Laravel geeft via route model binding een User instance,
         * maar hier nemen we enkel de id nodig voor unique except.
         */
        $userId = $this->route('user')?->id;

        return [
            'name' => ['required','string','max:255'],
            'email' => ['required','email','max:255','unique:users,email,'.$this->user->id],
            'role_id' => ['required','exists:roles,id'],
            'is_active' => ['required','boolean'],
            'verified' => ['required','boolean'],

            'password' => ['nullable','confirmed','min:8'],

            'image' => ['nullable','image','mimes:jpg,jpeg,png,webp','max:2048'],
        ];
    }
}

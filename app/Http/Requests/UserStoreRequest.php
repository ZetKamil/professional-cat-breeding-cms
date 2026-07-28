<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        /**
         * authorize() bepaalt toegangsrechten.
         * Later kan je dit koppelen aan policies/gates.
         * Voor nu true zodat je CRUD kan bouwen.
         */
        return true;
    }

    protected function prepareForValidation(): void
    {
        /**
         * Input normalisatie vóór validatie.
         *
         * verified checkbox:
         * - niet aangevinkt => veld ontbreekt => boolean('verified') = false
         * - aangevinkt => 'verified' = "1" => boolean('verified') = true
         *
         * We zetten dit om naar email_verified_at, zodat:
         * - controller enkel 1 veld moet opslaan
         * - view simpel blijft
         */
        $this->merge([
            'email_verified_at' => $this->boolean('verified') ? now() : null,
        ]);
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required','string','max:255'],
            'email' => ['required','email','max:255','unique:users,email'],
            'role_id' => ['required','exists:roles,id'],
            'is_active' => ['required','boolean'],
            'verified' => ['required','boolean'],

            'password' => ['required','confirmed','min:8'],

            'image' => ['nullable','image','mimes:jpg,jpeg,png,webp','max:2048'],
        ];
    }

}

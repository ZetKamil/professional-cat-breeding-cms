<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'min:2', 'max:120'],
            'email'       => ['required', 'string', 'email:rfc', 'max:254'],
            'subject'     => ['nullable', 'string', 'min:2', 'max:200'],
            'phone'       => ['nullable', 'string', 'min:6', 'max:40'],
            'message'     => ['required', 'string', 'min:15', 'max:3000'],
            '_hp_website' => ['nullable', 'prohibited'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Prosimy o podanie imienia i nazwiska.',
            'name.min'      => 'Imię i nazwisko musi zawierać co najmniej 2 znaki.',
            'name.max'      => 'Imię i nazwisko nie może przekraczać 120 znaków.',
            'email.required' => 'Prosimy o podanie adresu e-mail.',
            'email.email'   => 'Prosimy o podanie poprawnego adresu e-mail (np. jan@example.com).',
            'email.max'     => 'Adres e-mail nie może przekraczać 254 znaków.',
            'phone.min'     => 'Numer telefonu musi zawierać co najmniej 6 znaków.',
            'phone.max'     => 'Numer telefonu nie może przekraczać 40 znaków.',
            'subject.min'   => 'Temat wiadomości musi zawierać co najmniej 2 znaki.',
            'subject.max'   => 'Temat wiadomości nie może przekraczać 200 znaków.',
            'message.required' => 'Treść wiadomości jest wymagana.',
            'message.min'   => 'Treść wiadomości musi zawierać co najmniej 15 znaków.',
            'message.max'   => 'Treść wiadomości nie może przekraczać 3000 znaków.',
            '_hp_website.prohibited' => 'Wykryto niedozwoloną aktywność automatyczną (spam).',
        ];
    }
}

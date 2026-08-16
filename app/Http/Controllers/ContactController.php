<?php

namespace App\Http\Controllers;

use App\Events\ContactMessageSent;
use App\Http\Requests\ContactFormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function create(): View
    {
        return view('frontend.contact');
    }

    public function store(ContactFormRequest $request): RedirectResponse
    {
        $data = $request->validated();

        try {
            ContactMessageSent::dispatch($data);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Błąd wysyłki formularza kontaktowego: ' . $e->getMessage(), [
                'exception' => $e,
            ]);

            return redirect()
                ->route('contact')
                ->withInput()
                ->with('error', 'Wystąpił nieoczekiwany problem podczas wysyłania formularza. Prosimy o kontakt telefoniczny pod numerem +48 514 153 204.');
        }

        return redirect()
            ->route('contact')
            ->with('status', 'Dziękujemy za wiadomość! Odpowiemy tak szybko, jak to możliwe (zwykle w ciągu 24 godzin).');
    }
}

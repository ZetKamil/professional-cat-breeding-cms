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

        ContactMessageSent::dispatch($data);

        return redirect()
            ->route('contact')
            ->with('status', 'Dziękujemy za wiadomość! Odpowiemy tak szybko, jak to możliwe.');
    }
}

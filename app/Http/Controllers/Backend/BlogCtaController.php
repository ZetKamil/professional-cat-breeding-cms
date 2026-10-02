<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlogCtaController extends Controller
{
    /**
     * Show form for editing global blog closing CTA / cattery offer.
     */
    public function edit(): View
    {
        $this->authorize('create', Post::class);

        $cta = Setting::getBlogCta();

        return view('backend.settings.blog-cta', [
            'cta' => $cta,
        ]);
    }

    /**
     * Update global blog closing CTA / cattery offer.
     */
    public function update(Request $request): RedirectResponse
    {
        $this->authorize('create', Post::class);

        $validated = $request->validate([
            'is_enabled'    => ['nullable', 'boolean'],
            'badge'         => ['nullable', 'string', 'max:100'],
            'heading'       => ['required', 'string', 'max:255'],
            'body'          => ['required', 'string'],
            'image_url'     => ['nullable', 'string', 'max:500'],
            'button_text'   => ['nullable', 'string', 'max:100'],
            'button_url'    => ['nullable', 'string', 'max:255'],
            'facebook_info' => ['nullable', 'string', 'max:500'],
            'facebook_text' => ['nullable', 'string', 'max:100'],
            'facebook_url'  => ['nullable', 'string', 'max:255'],
        ]);

        $validated['is_enabled'] = $request->boolean('is_enabled');
        $validated['badge'] = $validated['badge'] ?: 'Hodowla Kotów z Mazowieckiej Szwajcarii';

        Setting::set('blog_closing_cta', $validated);

        return redirect()
            ->route('backend.blog-cta.edit')
            ->with('success', 'Oferta pod artykułami została pomyślnie zaktualizowana! Nowa treść natychmiast wyświetla się pod wszystkimi artykułami na blogu.');
    }
}

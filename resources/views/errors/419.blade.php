<x-frontend.shell
    title="419 — Sesja wygasła"
    meta-description="Sesja wygasła. Odśwież stronę i spróbuj ponownie."
>
    <section class="error-page" aria-label="Sesja wygasła">
        <div class="section-inner error-page__inner">
            <span class="error-page__code text-hero-display">419</span>
            <h1 class="error-page__headline text-display-lg">
                Sesja wygasła.
            </h1>
            <p class="error-page__desc text-lead-airy">
                Twoja sesja wygasła lub token CSRF jest nieprawidłowy.
                Odśwież stronę i spróbuj ponownie.
            </p>
            <div class="error-page__actions">
                <x-frontend.button href="javascript:history.back()" icon="refresh-cw">
                    Wróć i spróbuj ponownie
                </x-frontend.button>
                <x-frontend.button variant="secondary" href="{{ url('/') }}">
                    Strona główna
                </x-frontend.button>
            </div>
        </div>
    </section>
</x-frontend.shell>

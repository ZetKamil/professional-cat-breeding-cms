<x-frontend.shell
    title="403 — Brak uprawnień"
    meta-description="Nie masz uprawnień do wyświetlenia tej strony."
>
    <section class="error-page" aria-label="Brak uprawnień">
        <div class="section-inner error-page__inner">
            <span class="error-page__code text-hero-display">403</span>
            <h1 class="error-page__headline text-display-lg">
                Brak uprawnień.
            </h1>
            <p class="error-page__desc text-lead-airy">
                Nie masz uprawnień do wyświetlenia tej strony.
                Jeżeli uważasz, że to błąd, skontaktuj się z administratorem.
            </p>
            <div class="error-page__actions">
                <x-frontend.button href="{{ url('/') }}" icon="home">
                    Strona główna
                </x-frontend.button>
                <x-frontend.button variant="secondary" href="{{ route('contact') }}">
                    Kontakt
                </x-frontend.button>
            </div>
        </div>
    </section>
</x-frontend.shell>

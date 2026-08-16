<x-frontend.shell
    title="404 — Strona nie znaleziona"
    meta-description="Przepraszamy, strona której szukasz nie istnieje."
>
    <section class="error-page" aria-label="Strona nie znaleziona">
        <div class="section-inner error-page__inner">
            <span class="error-page__code text-hero-display">404</span>
            <h1 class="error-page__headline text-display-lg">
                Tej strony tu nie ma.
            </h1>
            <p class="error-page__desc text-lead-airy">
                Strona, której szukasz, mogła zostać przeniesiona, usunięta
                lub nigdy nie istniała. Sprawdź adres URL lub wróć na stronę główną.
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

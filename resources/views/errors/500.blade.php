<x-frontend.shell
    title="500 — Błąd serwera"
    meta-description="Przepraszamy, wystąpił nieoczekiwany błąd."
>
    <section class="error-page" aria-label="Błąd serwera">
        <div class="section-inner error-page__inner">
            <span class="error-page__code text-hero-display">500</span>
            <h1 class="error-page__headline text-display-lg">
                Coś poszło nie tak.
            </h1>
            <p class="error-page__desc text-lead-airy">
                Przepraszamy za utrudnienia. Nasz zespół został powiadomiony
                i pracuje nad rozwiązaniem problemu. Spróbuj ponownie za chwilę.
            </p>
            <div class="error-page__actions">
                <x-frontend.button href="{{ url('/') }}" icon="home">
                    Strona główna
                </x-frontend.button>
                <x-frontend.button variant="secondary" href="{{ route('contact') }}">
                    Zgłoś problem
                </x-frontend.button>
            </div>
        </div>
    </section>
</x-frontend.shell>

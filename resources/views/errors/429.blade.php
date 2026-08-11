<x-frontend.shell
    title="429 — Zbyt wiele żądań"
    meta-description="Wysłałeś zbyt wiele żądań. Poczekaj chwilę i spróbuj ponownie."
>
    <section class="error-page" aria-label="Zbyt wiele żądań">
        <div class="section-inner error-page__inner">
            <span class="error-page__code text-hero-display">429</span>
            <h1 class="error-page__headline text-display-lg">
                Zbyt wiele żądań.
            </h1>
            <p class="error-page__desc text-lead-airy">
                Wysłałeś zbyt wiele żądań w krótkim czasie.
                Poczekaj chwilę, a następnie spróbuj ponownie.
            </p>
            <div class="error-page__actions">
                <x-frontend.button href="javascript:history.back()" icon="clock">
                    Spróbuj ponownie
                </x-frontend.button>
                <x-frontend.button variant="secondary" href="{{ url('/') }}">
                    Strona główna
                </x-frontend.button>
            </div>
        </div>
    </section>
</x-frontend.shell>

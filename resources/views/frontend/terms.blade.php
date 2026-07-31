<x-frontend.shell
    title="Regulamin — {{ config('app.name') }}"
    meta-description="Regulamin adopcji i korzystania z naszej strony."
>
    <div class="section tile-canvas">
        <div class="section-inner" style="max-width: 800px; margin: 0 auto; padding-top: var(--sp-4xl); padding-bottom: var(--sp-4xl); text-align: center;">
            
            <header style="margin-bottom: var(--sp-3xl);">
                <i data-lucide="file-text" style="width: 48px; height: 48px; color: var(--color-ink-muted-48); margin-bottom: var(--sp-lg); display: inline-block;"></i>
                <h1 class="text-hero-display" style="margin-top: var(--sp-xs); margin-bottom: var(--sp-md);">Regulamin Adopcji</h1>
                <p class="text-lead-airy text-ink-muted-80">
                    Dokument jest obecnie w przygotowaniu przez nasz zespół prawny. 
                    Pełny, transparentny regulamin adopcji i korzystania z witryny pojawi się tutaj wkrótce.
                </p>
                <div style="margin-top: var(--sp-2xl);">
                    <a href="{{ route('home') }}" class="btn btn--primary">Wróć na stronę główną</a>
                </div>
            </header>

        </div>
    </div>
</x-frontend.shell>

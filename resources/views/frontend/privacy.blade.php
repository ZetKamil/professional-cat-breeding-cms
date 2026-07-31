<x-frontend.shell
    title="Polityka Prywatności — {{ config('app.name') }}"
    meta-description="Dowiedz się, w jaki sposób dbamy o Twoje dane osobowe, jak je przetwarzamy i jakie masz prawa zgodnie z RODO."
>
    <div class="section tile-canvas">
        <div class="section-inner" style="max-width: 800px; margin: 0 auto; padding-top: var(--sp-4xl); padding-bottom: var(--sp-4xl);">
            
            <header style="margin-bottom: var(--sp-3xl);">
                <span class="text-eyebrow" style="color: var(--color-primary); font-size: 12px; letter-spacing: 0.1em; text-transform: uppercase;">Wersja 1.0</span>
                <h1 class="text-hero-display" style="margin-top: var(--sp-xs); margin-bottom: var(--sp-md);">Polityka Prywatności</h1>
                <p class="text-lead-airy text-ink-muted-80">
                    Prywatność naszych gości i przyszłych opiekunów naszych kotów to dla nas priorytet. Poniższy dokument wyjaśnia, na jakich zasadach gromadzimy, przetwarzamy i chronimy Twoje dane osobowe.
                </p>
            </header>

            <article style="display: flex; flex-direction: column; gap: var(--sp-2xl); font-size: 1rem; line-height: 1.8; color: var(--color-ink-muted-80);">
                
                <section>
                    <h2 style="color: var(--color-ink); font-size: 1.5rem; margin-bottom: var(--sp-md);">1. Kto jest Administratorem Danych?</h2>
                    <p>Administratorem Twoich danych osobowych zbieranych za pośrednictwem strony internetowej jest hodowla <strong>{{ config('app.name', 'Cattery') }}</strong>. Możesz skontaktować się z nami pisząc na adres e-mail: kontakt@example.com lub dzwoniąc pod numer: +48 000 000 000.</p>
                </section>

                <section>
                    <h2 style="color: var(--color-ink); font-size: 1.5rem; margin-bottom: var(--sp-md);">2. Cel i podstawa prawna przetwarzania danych</h2>
                    <p>Twoje dane osobowe przetwarzamy w następujących celach:</p>
                    <ul style="margin-top: var(--sp-sm); padding-left: var(--sp-xl); list-style-type: disc;">
                        <li><strong>Obsługa zapytań (formularz kontaktowy):</strong> Podstawą prawną jest nasz prawnie uzasadniony interes (art. 6 ust. 1 lit. f RODO), polegający na konieczności odpowiadania na wiadomości.</li>
                        <li><strong>Proces adopcyjny:</strong> Podjęcie działań na żądanie osoby, której dane dotyczą, przed zawarciem umowy adopcyjnej (art. 6 ust. 1 lit. b RODO).</li>
                        <li><strong>Wysyłka newslettera:</strong> Podstawą jest Twoja dobrowolna, wyraźna zgoda (art. 6 ust. 1 lit. a RODO), potwierdzona poprzez mechanizm Double Opt-In.</li>
                    </ul>
                </section>

                <section>
                    <h2 style="color: var(--color-ink); font-size: 1.5rem; margin-bottom: var(--sp-md);">3. Komu udostępniamy Twoje dane?</h2>
                    <p>Twoje dane osobowe mogą być przekazywane wyłącznie zaufanym podmiotom, z którymi współpracujemy, takim jak dostawca hostingu naszej strony czy platforma do obsługi newslettera (np. MailerLite). Gwarantujemy, że <strong>nigdy nie sprzedajemy</strong> Twoich danych podmiotom trzecim.</p>
                </section>

                <section>
                    <h2 style="color: var(--color-ink); font-size: 1.5rem; margin-bottom: var(--sp-md);">4. Jak długo przechowujemy dane?</h2>
                    <p>Dane zebrane w celu obsługi zapytań przechowujemy przez okres niezbędny do udzielenia odpowiedzi i zakończenia korespondencji. W przypadku zapisu na newsletter, Twoje dane będziemy przetwarzać do momentu, w którym wycofasz swoją zgodę (klikając link wypisu na dole każdego maila).</p>
                </section>

                <section>
                    <h2 style="color: var(--color-ink); font-size: 1.5rem; margin-bottom: var(--sp-md);">5. Twoje prawa (RODO)</h2>
                    <p>Zgodnie z obowiązującymi przepisami masz pełne prawo do:</p>
                    <ul style="margin-top: var(--sp-sm); padding-left: var(--sp-xl); list-style-type: disc;">
                        <li>Dostępu do swoich danych oraz otrzymania ich kopii.</li>
                        <li>Sprostowania (poprawiania) swoich danych.</li>
                        <li>Usunięcia danych (prawo do bycia zapomnianym) lub ograniczenia ich przetwarzania.</li>
                        <li>Cofnięcia zgody na przetwarzanie danych w dowolnym momencie.</li>
                        <li>Wniesienia skargi do organu nadzorczego (Prezesa Urzędu Ochrony Danych Osobowych).</li>
                    </ul>
                </section>

                <section>
                    <h2 style="color: var(--color-ink); font-size: 1.5rem; margin-bottom: var(--sp-md);">6. Pliki Cookies (Ciasteczka)</h2>
                    <p>Nasza witryna szanuje Twój spokój. Wykorzystujemy wyłącznie niezbędne, techniczne pliki cookies, które są kluczowe dla prawidłowego funkcjonowania strony. Nie stosujemy inwazyjnych ciasteczek śledzących do celów marketingowych (np. retargetingu).</p>
                </section>
                
            </article>

            <div style="margin-top: var(--sp-4xl); padding-top: var(--sp-xl); border-top: 1px solid var(--color-hairline); text-align: center;">
                <p class="text-body text-ink-muted-48">Ostatnia aktualizacja: {{ now()->format('d.m.Y') }}</p>
            </div>

        </div>
    </div>
</x-frontend.shell>

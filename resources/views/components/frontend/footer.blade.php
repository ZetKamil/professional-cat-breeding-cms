{{--
    Premium Footer — Parchment background, editorial feel
    Calm, minimal, trust-building
--}}

<footer class="site-footer tile-parchment" role="contentinfo">
    <div class="section">
        <div class="section-inner">

            {{-- Footer Grid --}}
            <div class="footer-grid">

                {{-- Brand & Contact Column --}}
                <div class="footer-col footer-col--brand">
                    <a href="{{ route('home') }}" class="footer-brand" aria-label="{{ config('app.name') }}">
                        <span class="footer-brand__name">{{ config('app.name', 'Cattery') }}</span>
                    </a>
                    <p class="footer-brand__tagline text-body">
                        Profesjonalna hodowla kotów rasowych z pasją, troską i pełną transparentnością.
                    </p>
                    <div class="footer-contact-info">
                        <a href="mailto:kontakt@example.com" class="footer-link">kontakt@example.com</a><br>
                        <a href="tel:+48000000000" class="footer-link">+48 000 000 000</a>
                    </div>
                    {{-- Social Links --}}
                    <div class="footer-social">
                        <a href="https://facebook.com" class="footer-social__link" aria-label="Facebook" target="_blank" rel="noopener">
                            <i data-lucide="facebook" aria-hidden="true"></i>
                        </a>
                        <a href="https://instagram.com" class="footer-social__link" aria-label="Instagram" target="_blank" rel="noopener">
                            <i data-lucide="instagram" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>

                {{-- Quick Navigation --}}
                <div class="footer-col">
                    <h3 class="footer-heading">Szybka Nawigacja</h3>
                    <ul class="footer-links" role="list">
                        <li><a href="{{ route('home') }}" class="footer-link">Strona Główna</a></li>
                        <li><a href="{{ route('frontend.animals.index') }}" class="footer-link">Nasze Koty</a></li>
                        <li><a href="{{ route('home') }}#o-nas" class="footer-link">Nasza Historia</a></li>
                        <li><a href="{{ route('about') }}" class="footer-link">O Hodowli</a></li>
                        <li><a href="{{ route('frontend.blog.index') }}" class="footer-link">Baza Wiedzy</a></li>
                        <li><a href="{{ route('contact') }}" class="footer-link">Kontakt</a></li>
                    </ul>
                </div>

                {{-- Information --}}
                <div class="footer-col">
                    <h3 class="footer-heading">Informacje</h3>
                    <ul class="footer-links" role="list">
                        <li><a href="{{ route('about') }}#zdrowie" class="footer-link">Zdrowie & Certyfikaty</a></li>
                        <li><a href="{{ route('about') }}#co-otrzymujesz" class="footer-link">Wyprawka i Rodowód</a></li>
                        <li><a href="{{ route('contact') }}#faq" class="footer-link">Częste pytania (FAQ)</a></li>
                        <li><a href="{{ route('about') }}#certyfikaty" class="footer-link">Regulamin adopcji</a></li>
                    </ul>
                </div>

                {{-- Newsletter --}}
                <div class="footer-col footer-col--newsletter">
                    <h3 class="footer-heading">Bądź na bieżąco</h3>
                    <p class="footer-link" style="margin-bottom: var(--sp-sm);">
                        Zapisz się do newslettera, aby otrzymywać informacje o nowych miotach jako pierwszy.
                    </p>
                    <form action="#" method="POST" class="newsletter-form" onsubmit="event.preventDefault();">
                        <label for="newsletter-email" class="sr-only">Adres e-mail</label>
                        <input type="email" id="newsletter-email" placeholder="Twój adres e-mail" class="newsletter-input" required>
                        <button type="submit" class="newsletter-submit" aria-label="Zapisz się">
                            <i data-lucide="arrow-right" aria-hidden="true"></i>
                        </button>
                    </form>
                </div>
            </div>

            {{-- Bottom Bar --}}
            <div class="footer-bottom">
                <p class="footer-copyright">
                    &copy; {{ now()->year }} {{ config('app.name', 'Cattery') }}. Wszelkie prawa zastrzeżone.
                </p>
                <p class="footer-legal">
                    <a href="{{ route('about') }}#filozofia" class="footer-link">Polityka prywatności</a>
                    <span class="footer-legal__sep" aria-hidden="true">·</span>
                    <a href="{{ route('about') }}#standardy" class="footer-link">Regulamin strony</a>
                </p>
            </div>

        </div>
    </div>
</footer>

<style>
    /* ==========================================================================
       FOOTER — Parchment, editorial, calm
       ========================================================================== */

    .site-footer {
        border-top: 1px solid var(--color-hairline);
    }

    .site-footer .section {
        padding-top: var(--sp-3xl);
        padding-bottom: var(--sp-xl);
    }

    /* Grid */
    .footer-grid {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr 1fr;
        gap: var(--sp-2xl);
    }

    /* Brand column */
    .footer-brand {
        display: inline-block;
        text-decoration: none;
        margin-bottom: var(--sp-md);
    }

    .footer-brand__name {
        font-size: var(--text-tagline-size);
        font-weight: 600;
        letter-spacing: var(--text-tagline-ls);
        color: var(--color-ink);
    }

    .footer-brand__tagline {
        color: var(--color-ink-muted-80);
        max-width: 320px;
    }

    /* Headings */
    .footer-heading {
        font-size: var(--text-btn-util-size);
        font-weight: 600;
        letter-spacing: var(--text-btn-util-ls);
        color: var(--color-ink);
        margin-bottom: var(--sp-md);
        text-transform: uppercase;
    }

    /* Links */
    .footer-links {
        list-style: none;
        margin: 0;
        padding: 0;
        display: flex;
        flex-direction: column;
        gap: var(--sp-sm);
    }

    .footer-link {
        font-size: var(--text-btn-util-size);
        color: var(--color-ink-muted-48);
        text-decoration: none;
        transition: color var(--duration-fast) var(--ease-out);
    }

    .footer-link:hover {
        color: var(--color-ink);
    }

    /* Social */
    .footer-social {
        display: flex;
        gap: var(--sp-sm);
        margin-top: var(--sp-lg);
    }

    .footer-social__link {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: var(--r-full);
        color: var(--color-ink-muted-48);
        transition: color var(--duration-fast) var(--ease-out),
                    background-color var(--duration-fast) var(--ease-out);
    }

    .footer-social__link:hover {
        color: var(--color-ink);
        background-color: rgba(0, 0, 0, 0.05);
    }

    .footer-social__link svg {
        width: 18px;
        height: 18px;
    }

    /* Bottom Bar */
    .footer-bottom {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-top: var(--sp-xl);
        margin-top: var(--sp-2xl);
        border-top: 1px solid var(--color-hairline);
    }

    .footer-copyright {
        font-size: var(--text-nav-size);
        color: var(--color-ink-muted-48);
    }

    .footer-legal {
        font-size: var(--text-nav-size);
        color: var(--color-ink-muted-48);
        display: flex;
        gap: var(--sp-xs);
    }

    .footer-legal__sep {
        color: var(--color-hairline);
    }

    /* Newsletter Form */
    .newsletter-form {
        display: flex;
        gap: var(--sp-xs);
        margin-top: var(--sp-sm);
    }

    .newsletter-input {
        flex: 1;
        background-color: var(--color-canvas);
        border: 1px solid var(--color-hairline);
        border-radius: var(--r-sm);
        padding: 0 var(--sp-md);
        height: 44px;
        font-family: inherit;
        font-size: var(--text-btn-util-size);
        color: var(--color-ink);
        transition: border-color var(--duration-fast) var(--ease-out),
                    box-shadow var(--duration-fast) var(--ease-out);
        min-width: 0;
    }

    .newsletter-input:focus {
        outline: none;
        border-color: var(--color-primary-focus);
        box-shadow: 0 0 0 2px rgba(0, 113, 227, 0.2);
    }

    .newsletter-submit {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 44px;
        height: 44px;
        background-color: var(--color-ink);
        color: var(--color-canvas);
        border: none;
        border-radius: var(--r-sm);
        cursor: pointer;
        transition: background-color var(--duration-fast) var(--ease-out),
                    transform var(--duration-fast) var(--ease-out);
    }

    .newsletter-submit:hover {
        background-color: var(--color-ink-muted-80);
    }
    
    .newsletter-submit:active {
        transform: scale(0.95);
    }

    .newsletter-submit svg {
        width: 20px;
        height: 20px;
    }

    /* ==========================================================================
       RESPONSIVE
       ========================================================================== */

    @media (max-width: 834px) {
        .footer-grid {
            grid-template-columns: 1fr 1fr;
            gap: var(--sp-xl);
        }

        .footer-col--brand {
            grid-column: 1 / -1;
        }
    }

    @media (max-width: 640px) {
        .footer-grid {
            grid-template-columns: 1fr;
        }

        .footer-bottom {
            flex-direction: column;
            align-items: flex-start;
            gap: var(--sp-sm);
        }
    }
</style>

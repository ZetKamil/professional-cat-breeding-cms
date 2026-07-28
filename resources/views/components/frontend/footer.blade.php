{{--
    Premium Footer — Parchment background, editorial feel
    Calm, minimal, trust-building
--}}

<footer class="site-footer tile-parchment" role="contentinfo">
    <div class="section">
        <div class="section-inner">

            {{-- Footer Grid --}}
            <div class="footer-grid">

                {{-- Brand Column --}}
                <div class="footer-col footer-col--brand">
                    <a href="{{ route('home') }}" class="footer-brand" aria-label="{{ config('app.name') }}">
                        <span class="footer-brand__name">{{ config('app.name', 'Cattery') }}</span>
                    </a>
                    <p class="footer-brand__tagline text-body">
                        Profesjonalna hodowla kotów rasowych z pasją, troską i pełną transparentnością.
                    </p>
                </div>

                {{-- Navigation --}}
                <div class="footer-col">
                    <h3 class="footer-heading">Nawigacja</h3>
                    <ul class="footer-links" role="list">
                        <li><a href="{{ route('home') }}" class="footer-link">Strona Główna</a></li>
                        <li><a href="#nasze-koty" class="footer-link">Nasze Koty</a></li>
                        <li><a href="{{ route('about') }}" class="footer-link">O Nas</a></li>
                        <li><a href="{{ route('contact') }}" class="footer-link">Kontakt</a></li>
                    </ul>
                </div>

                {{-- Information --}}
                <div class="footer-col">
                    <h3 class="footer-heading">Informacje</h3>
                    <ul class="footer-links" role="list">
                        <li><a href="#zdrowie" class="footer-link">Zdrowie & Certyfikaty</a></li>
                        <li><a href="#rodowody" class="footer-link">Rodowody</a></li>
                        <li><a href="#faq" class="footer-link">FAQ</a></li>
                    </ul>
                </div>

                {{-- Contact --}}
                <div class="footer-col">
                    <h3 class="footer-heading">Kontakt</h3>
                    <ul class="footer-links" role="list">
                        <li>
                            <a href="mailto:kontakt@example.com" class="footer-link">
                                kontakt@example.com
                            </a>
                        </li>
                        <li>
                            <a href="tel:+48000000000" class="footer-link">
                                +48 000 000 000
                            </a>
                        </li>
                    </ul>

                    {{-- Social Links --}}
                    <div class="footer-social">
                        <a href="#" class="footer-social__link" aria-label="Facebook" target="_blank" rel="noopener">
                            <i data-lucide="facebook" aria-hidden="true"></i>
                        </a>
                        <a href="#" class="footer-social__link" aria-label="Instagram" target="_blank" rel="noopener">
                            <i data-lucide="instagram" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Bottom Bar --}}
            <div class="footer-bottom">
                <p class="footer-copyright">
                    &copy; {{ now()->year }} {{ config('app.name', 'Cattery') }}. Wszelkie prawa zastrzeżone.
                </p>
                <p class="footer-legal">
                    <a href="#polityka-prywatnosci" class="footer-link">Polityka prywatności</a>
                    <span class="footer-legal__sep" aria-hidden="true">·</span>
                    <a href="#regulamin" class="footer-link">Regulamin</a>
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

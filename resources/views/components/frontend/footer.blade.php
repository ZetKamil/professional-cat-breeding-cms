{{--
Premium Footer — Parchment background, editorial feel
Calm, minimal, trust-building
--}}

<footer class="site-footer tile-parchment" role="contentinfo" data-nav-theme="cream">
    <div class="section">
        <div class="section-inner">

            {{-- Footer Grid --}}
            <div class="footer-grid">

                {{-- Brand & Contact Column --}}
                <div class="footer-col footer-col--brand">
                    <a href="{{ route('home') }}" class="footer-brand"
                        aria-label="Hodowla Kotów z Mazowieckiej Szwajcarii">
                        <span class="footer-brand__name">Hodowla Kotów z Mazowieckiej Szwajcarii</span>
                    </a>
                    <p class="footer-brand__tagline text-body">
                        Profesjonalna hodowla kotów rasowych z pasją, troską i pełną transparentnością.
                    </p>



                    <div class="footer-contact-info">
                        <div class="footer-contact-item">
                            <i data-lucide="mail" aria-hidden="true" class="footer-contact-icon"></i>
                            <a href="mailto:hodowla.z.mazowieckiej.szwajcarii@gmail.com"
                                class="footer-link">hodowla.z.mazowieckiej.szwajcarii@gmail.com</a>
                        </div>
                        <div class="footer-contact-item">
                            <i data-lucide="phone" aria-hidden="true" class="footer-contact-icon"></i>
                            <a href="tel:+48514153204" class="footer-link">+48 514 153 204</a>
                        </div>
                        <div class="footer-contact-item">
                            <i data-lucide="map-pin" aria-hidden="true" class="footer-contact-icon"></i>
                            <span class="footer-link" style="color: var(--color-ink-muted-80);">Sikórz 56A, 09-413 Sikórz</span>
                        </div>
                    </div>

                    {{-- Social Links --}}
                    <div class="footer-social">
                        <a href="https://www.facebook.com/profile.php?id=61580668026948" class="footer-social__link"
                            aria-label="Facebook" target="_blank" rel="noopener">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
                            </svg>
                        </a>
                    </div>
                </div>

                {{-- Quick Navigation --}}
                <div class="footer-col">
                    <h3 class="footer-heading">Szybka Nawigacja</h3>
                    <ul class="footer-links" role="list">
                        <li><a href="{{ route('home') }}" class="footer-link">Strona Główna</a></li>
                        <li><a href="{{ route('frontend.animals.index') }}" class="footer-link">Nasze Koty</a></li>
                        <li><a href="{{ route('cattery') }}" class="footer-link">O Hodowli</a></li>
                        <li><a href="{{ route('about') }}" class="footer-link">O Nas</a></li>
                        <li><a href="{{ route('frontend.blog.index') }}" class="footer-link">Baza Wiedzy</a></li>
                        <li><a href="{{ route('contact') }}" class="footer-link">Kontakt</a></li>
                    </ul>
                </div>

                {{-- Information --}}
                <div class="footer-col">
                    <h3 class="footer-heading">Informacje</h3>
                    <ul class="footer-links" role="list">
                        <li><a href="{{ route('about') }}#zdrowie" class="footer-link">Zdrowie & Certyfikaty</a></li>
                        <li><a href="{{ route('about') }}#co-otrzymujesz" class="footer-link">Dokumentacja i standard</a>
                        </li>
                        <li><a href="{{ route('contact') }}#faq" class="footer-link">Częste pytania (FAQ)</a></li>
                        <li><a href="{{ route('terms') }}" class="footer-link">Regulamin świadczenia usług</a></li>
                    </ul>
                </div>

                {{-- Location & Visits --}}
                <div class="footer-col footer-col--newsletter">
                    <h3 class="footer-heading">Lokalizacja & Wizyty</h3>
                    <p class="footer-newsletter__desc text-body" style="line-height: 1.6;">
                        <strong>Sikórz k. Płocka</strong><br>
                        woj. mazowieckie<br><br>
                        Wizyty w hodowli po wcześniejszym uzgodnieniu terminu.<br>
                        Kontakt tel.: <strong>9:00 – 20:00</strong>
                    </p>
                    <div style="margin-top: 12px;">
                        <a href="{{ route('contact') }}" class="footer-link" style="color: var(--color-primary); font-weight: 600;">
                            Umów spotkanie w hodowli →
                        </a>
                    </div>
                </div>
            </div>

            {{-- Bottom Bar --}}
            <div class="footer-bottom">
                <p class="footer-copyright">
                    &copy; {{ now()->year }} Hodowla Kotów z Mazowieckiej Szwajcarii. Wszelkie prawa zastrzeżone.
                </p>
                <p class="footer-legal">
                    <a href="{{ route('privacy') }}" class="footer-link">Polityka prywatności</a>
                    <span class="footer-legal__sep" aria-hidden="true">·</span>
                    <a href="{{ route('terms') }}" class="footer-link">Regulamin świadczenia usług</a>
                </p>
            </div>

        </div>
    </div>
</footer>
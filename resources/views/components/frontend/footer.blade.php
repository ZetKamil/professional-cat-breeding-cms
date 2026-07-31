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
                    <a href="{{ route('home') }}" class="footer-brand" aria-label="Hodowla Kotów z Mazowieckiej Szwajcarii">
                        <span class="footer-brand__name">Hodowla Kotów z Mazowieckiej Szwajcarii</span>
                    </a>
                    <p class="footer-brand__tagline text-body">
                        Profesjonalna hodowla kotów rasowych z pasją, troską i pełną transparentnością.
                    </p>



                    <div class="footer-contact-info">
                        <div class="footer-contact-item">
                            <i data-lucide="mail" aria-hidden="true" class="footer-contact-icon"></i>
                            <a href="mailto:hodowla.z.mazowieckiej.szwajcarii@gmail.com" class="footer-link">hodowla.z.mazowieckiej.szwajcarii@gmail.com</a>
                        </div>
                        <div class="footer-contact-item">
                            <i data-lucide="phone" aria-hidden="true" class="footer-contact-icon"></i>
                            <a href="tel:+48514153204" class="footer-link">+48 514 153 204</a>
                        </div>
                    </div>

                    {{-- Social Links --}}
                    <div class="footer-social">
                        <a href="https://www.facebook.com/profile.php?id=61580668026948" class="footer-social__link" aria-label="Facebook" target="_blank" rel="noopener">
                            <i data-lucide="facebook" aria-hidden="true"></i>
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
                        <li><a href="{{ route('terms') }}" class="footer-link">Regulamin adopcji</a></li>
                    </ul>
                </div>

                {{-- Newsletter --}}
                <div class="footer-col footer-col--newsletter">
                    <h3 class="footer-heading">Bądź na bieżąco</h3>
                    <p class="footer-newsletter__desc text-body">
                        Zapisz się do newslettera, aby otrzymywać informacje o nowych miotach jako pierwszy.
                    </p>
                    <form action="#" method="POST" class="newsletter-form" onsubmit="event.preventDefault();">
                        <label for="newsletter-email" class="sr-only">Adres e-mail</label>
                        <input type="email" id="newsletter-email" placeholder="Twój adres e-mail" class="newsletter-input" required>
                        <button type="submit" class="newsletter-submit" aria-label="Zapisz się">
                            <i data-lucide="arrow-right" aria-hidden="true"></i>
                        </button>
                    </form>
                    <p class="newsletter-privacy">
                        <i data-lucide="lock" aria-hidden="true" class="newsletter-privacy__icon"></i>
                        <span>Zapisując się, akceptujesz <a href="{{ route('privacy') }}">Politykę prywatności</a>.</span>
                    </p>
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
                    <a href="{{ route('terms') }}" class="footer-link">Regulamin strony</a>
                </p>
            </div>

        </div>
    </div>
</footer>

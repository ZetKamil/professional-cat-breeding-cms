@props([
    'title' => 'Hodowla Kotów Rasowych z Rodowodem – Bengalskie, Brytyjskie i Syjamskie | Mazowiecka Szwajcaria',
    'metaDescription' => 'Domowa hodowla kotów rasowych z rodowodem SHiOZ ZOOLANDIA (Certyfikat 58/CW/2025, Rej. 58/P/2025). Koty bengalskie, brytyjskie i syjamskie w Sikorzu k. Płocka (Mazowsze).',
    'ogImage' => null,
    'ogType' => 'website',
    'canonical' => null,
    'robots' => 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1',
])

@php
    $canonicalUrl = $canonical ?? (
        app()->isProduction() || config('app.url') === 'https://kotyzmazowieckiejszwajcarii.pl'
            ? 'https://kotyzmazowieckiejszwajcarii.pl' . (request()->getPathInfo() === '/' ? '/' : request()->getPathInfo())
            : url()->current()
    );
@endphp

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="{{ $metaDescription }}">
    <meta name="robots" content="{{ $robots }}">
    <meta name="theme-color" content="#0d0d0d">

    {{-- Canonical URL --}}
    <link rel="canonical" href="{{ $canonicalUrl }}">

    {{-- Favicons & Web Manifest --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=2">
    <link rel="icon" type="image/png" sizes="48x48" href="{{ asset('favicon-48x48.png') }}?v=2">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}?v=2">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}?v=2">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}?v=2">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}?v=2">
    <link rel="manifest" href="{{ asset('manifest.webmanifest') }}?v=2">

    @php
        $effectiveOgImage = $ogImage ?? asset('logo.png');
    @endphp

    {{-- Open Graph --}}
    <meta property="og:site_name" content="Hodowla Kotów z Mazowieckiej Szwajcarii">
    <meta property="og:title" content="{{ $title }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:type" content="{{ $ogType }}">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:locale" content="pl_PL">
    <meta property="og:image" content="{{ $effectiveOgImage }}">

    {{-- Twitter Cards --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $title }}">
    <meta name="twitter:description" content="{{ $metaDescription }}">
    <meta name="twitter:image" content="{{ $effectiveOgImage }}">

    <title>{{ $title }}</title>

    {{-- Google Analytics 4 — Consent Mode v2 + gtag.js --}}
    @php
        $gaId = config('services.google.analytics_id') ?: env('GOOGLE_ANALYTICS_ID', env('GA_MEASUREMENT_ID', 'G-VB4ZCKR8WB'));
    @endphp
    @if($gaId)
        {{-- Consent Mode v2: domyślnie odmawiamy analytics do czasu wyrażenia zgody przez użytkownika --}}
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('consent', 'default', {
                'analytics_storage': 'denied',
                'ad_storage': 'denied',
                'ad_user_data': 'denied',
                'ad_personalization': 'denied',
                'wait_for_update': 500
            });
            // Przywróć zgodę jeśli użytkownik wcześniej ją wyraził
            (function() {
                try {
                    var saved = localStorage.getItem('katten_cookie_consent');
                    if (saved === 'accepted') {
                        gtag('consent', 'update', { 'analytics_storage': 'granted' });
                    }
                } catch(e) {}
            })();
        </script>
        <!-- Google tag (gtag.js) -->
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ $gaId }}"></script>
        <script>
            gtag('js', new Date());
            gtag('config', '{{ $gaId }}', { 'send_page_view': true });
        </script>
        <meta name="ga-measurement-id" content="{{ $gaId }}">
    @endif

    {{-- Google Fonts: Inter --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

    {{-- Lucide Icons --}}
    <script src="https://unpkg.com/lucide@latest" defer></script>

    {{-- Vite assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Head slot for page-specific meta --}}
    {{ $head ?? '' }}

    {{-- JSON-LD Schema.org Data --}}
    @stack('schema')
</head>
<body class="frontend-shell">

    {{-- Skip to content — accessibility --}}
    <a href="#main-content" class="skip-to-content">
        Przejdź do treści
    </a>

    {{-- Global Navigation --}}
    <x-frontend.header />

    {{-- Main Content --}}
    <main id="main-content">
        {{ $slot }}
    </main>

    {{-- Footer --}}
    <x-frontend.footer />

    {{-- Initialize Lucide Icons --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (window.lucide) {
                lucide.createIcons();
            }
        });
    </script>

    {{-- Cookie Consent Banner --}}
    <x-frontend.cookie-consent />

    {{-- Scripts slot for page-specific JS --}}
    {{ $scripts ?? '' }}
</body>
</html>

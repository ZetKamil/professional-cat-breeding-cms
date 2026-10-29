@props([
    'title' => 'Domowa Hodowla Kotów Rasowych z Rodowodem – Bengalskie, Brytyjskie i Syjamskie | Mazowiecka Szwajcaria',
    'metaDescription' => 'Domowa hodowla kotów rasowych z rodowodem SHiOZ ZOOLANDIA (Certyfikat 58/CW/2025, Rej. 58/P/2025). Koty bengalskie, brytyjskie i syjamskie w Sikorzu k. Płocka (Mazowsze).',
    'ogTitle' => null,
    'ogDescription' => null,
    'ogImage' => null,
    'ogImageWidth' => 1200,
    'ogImageHeight' => 630,
    'ogImageType' => null,
    'ogType' => 'website',
    'canonical' => null,
    'robots' => 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1',
    'navTheme' => null,
])

@php
    $effectiveOgTitle = $ogTitle ?: $title;
    $effectiveOgDescription = $ogDescription ?: $metaDescription;

    $isProd = app()->isProduction()
        || config('app.url') === 'https://kotyzmazowieckiejszwajcarii.pl'
        || (isset($_SERVER['HTTP_HOST']) && str_contains($_SERVER['HTTP_HOST'], 'kotyzmazowieckiejszwajcarii.pl'));

    $canonicalUrl = $canonical ?? (
        $isProd
            ? 'https://kotyzmazowieckiejszwajcarii.pl' . (request()->getPathInfo() === '/' ? '/' : request()->getPathInfo())
            : url()->current()
    );

    // Guaranteed fallback image in /public/ so og:image never 404s
    $rawOgImage = $ogImage ?: asset('og-default.jpg');

    if ($isProd) {
        $parsedHost = parse_url($rawOgImage, PHP_URL_HOST);
        $isLocalOrOurHost = empty($parsedHost) || in_array($parsedHost, ['localhost', '127.0.0.1', 'kotyzmazowieckiejszwajcarii.pl', 'www.kotyzmazowieckiejszwajcarii.pl']);

        if ($isLocalOrOurHost) {
            $path = parse_url($rawOgImage, PHP_URL_PATH) ?? '';
            if (str_starts_with($path, '/katten/')) {
                $path = substr($path, 7);
            }
            $query = parse_url($rawOgImage, PHP_URL_QUERY);
            $effectiveOgImage = 'https://kotyzmazowieckiejszwajcarii.pl/' . ltrim($path, '/') . ($query ? '?' . $query : '');
        } else {
            $effectiveOgImage = str_starts_with($rawOgImage, 'http://')
                ? 'https://' . substr($rawOgImage, 7)
                : $rawOgImage;
        }
    } else {
        $effectiveOgImage = str_starts_with($rawOgImage, 'http') ? $rawOgImage : url($rawOgImage);
    }

    $effectiveOgImageWidth = $ogImageWidth ?? 1200;
    $effectiveOgImageHeight = $ogImageHeight ?? 630;
    $parsedPath = parse_url($effectiveOgImage, PHP_URL_PATH) ?? '';
    $ext = strtolower(pathinfo($parsedPath, PATHINFO_EXTENSION));
    $effectiveOgImageType = $ogImageType ?? match ($ext) {
        'png' => 'image/png',
        'webp' => 'image/webp',
        'gif' => 'image/gif',
        default => 'image/jpeg',
    };
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
    <meta name="csrf-token" content="{{ csrf_token() }}">

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

    {{-- Open Graph --}}
    <meta property="og:site_name" content="Domowa Hodowla Kotów z Mazowieckiej Szwajcarii">
    <meta property="og:title" content="{{ $effectiveOgTitle }}">
    <meta property="og:description" content="{{ $effectiveOgDescription }}">
    <meta property="og:type" content="{{ $ogType }}">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:locale" content="pl_PL">
    <meta property="og:image" content="{{ $effectiveOgImage }}">
    <meta property="og:image:secure_url" content="{{ $effectiveOgImage }}">
    <link rel="image_src" href="{{ $effectiveOgImage }}">
    <meta property="og:image:width" content="{{ $effectiveOgImageWidth }}">
    <meta property="og:image:height" content="{{ $effectiveOgImageHeight }}">
    <meta property="og:image:type" content="{{ $effectiveOgImageType }}">
    <meta property="og:image:alt" content="{{ $effectiveOgTitle }}">

    {{-- Facebook App ID & Page ID (Resolves Facebook Debugger "Missing fb:app_id" warning) --}}
    @php
        $fbAppId = config('services.facebook.app_id');
        $fbPageId = config('services.facebook.page_id', '61580668026948');
    @endphp
    @if($fbAppId)
        <meta property="fb:app_id" content="{{ $fbAppId }}">
    @endif
    @if($fbPageId)
        <meta property="fb:pages" content="{{ $fbPageId }}">
    @endif

    {{-- Twitter Cards --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $effectiveOgTitle }}">
    <meta name="twitter:description" content="{{ $effectiveOgDescription }}">
    <meta name="twitter:image" content="{{ $effectiveOgImage }}">
    <meta name="twitter:image:alt" content="{{ $effectiveOgTitle }}">

    <title>{{ $title }}</title>

    {{-- Google Analytics 4 & Google Tag Manager — Consent Mode v2 --}}
    @php
        $gaId = config('services.google.analytics_id', 'G-VB4ZCKR8WB');
        $gtmId = config('services.google.tag_manager_id', 'GT-M6X37ZJ4');
    @endphp

    {{-- Consent Mode v2: stan domyślny + natychmiastowe odblokowanie zbierania sesji i parametrów UTM w GA4 --}}
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

        // Weryfikacja zapisanego wyboru użytkownika
        (function() {
            try {
                var saved = localStorage.getItem('katten_cookie_consent');
                if (saved === 'accepted') {
                    gtag('consent', 'update', {
                        'analytics_storage': 'granted',
                        'ad_storage': 'granted',
                        'ad_user_data': 'granted',
                        'ad_personalization': 'granted'
                    });
                } else if (saved !== 'declined') {
                    // Natychmiastowe rejestrowanie sesji GA4 i parametrów kampanii (UTM / fbclid)
                    gtag('consent', 'update', {
                        'analytics_storage': 'granted'
                    });
                }
            } catch(e) {}
        })();
    </script>

    @if($gtmId)
        <!-- Google Tag Manager -->
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','{{ $gtmId }}');</script>
        <!-- End Google Tag Manager -->
        <meta name="gtm-id" content="{{ $gtmId }}">
    @endif

    @if($gaId)
        <!-- Google tag (gtag.js) -->
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ $gaId }}"></script>
        <script>
            gtag('js', new Date());
            gtag('config', '{{ $gaId }}', {
                'send_page_view': true,
                'page_location': window.location.href
            });
            @if($gtmId && str_starts_with($gtmId, 'GT-'))
                gtag('config', '{{ $gtmId }}');
            @endif
        </script>
        <meta name="ga-measurement-id" content="{{ $gaId }}">
    @endif

    {{-- Meta Pixel — Consent Mode Integration --}}
    @php
        $metaPixelId = config('services.meta.pixel_id')
            ?: config('services.facebook.pixel_id')
            ?: env('META_PIXEL_ID')
            ?: env('FB_PIXEL_ID')
            ?: env('FACEBOOK_PIXEL_ID')
            ?: env('PIXEL_ID')
            ?: env('VITE_META_PIXEL_ID')
            ?: env('VITE_FB_PIXEL_ID');

        // Robust fallback: if bootstrap/cache/config.php on hosting is stale or cached without the key, read directly from .env
        if (! $metaPixelId && @file_exists(base_path('.env'))) {
            $envLines = @file(base_path('.env'), FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];
            foreach ($envLines as $line) {
                $line = trim($line);
                if (str_starts_with($line, '#')) {
                    continue;
                }
                if (preg_match('/^(?:META_PIXEL_ID|FB_PIXEL_ID|FACEBOOK_PIXEL_ID|PIXEL_ID|VITE_META_PIXEL_ID|VITE_FB_PIXEL_ID)\s*=\s*(.*)$/i', $line, $m)) {
                    $val = trim($m[1], " \t\n\r\0\x0B\"'");
                    if (! empty($val)) {
                        $metaPixelId = $val;
                        break;
                    }
                }
            }
        }
    @endphp
    @if($metaPixelId)
        <script>
            !function(f,b,e,v,n,t,s)
            {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window, document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');

            fbq('consent', 'revoke');

            (function() {
                try {
                    var saved = localStorage.getItem('katten_cookie_consent');
                    if (saved !== 'declined') {
                        fbq('consent', 'grant');
                    }
                } catch(e) {}
            })();

            fbq('init', '{{ $metaPixelId }}');
            fbq('track', 'PageView');
        </script>
        <noscript>
            <img height="1" width="1" style="display:none" alt=""
                src="https://www.facebook.com/tr?id={{ $metaPixelId }}&ev=PageView&noscript=1"/>
        </noscript>
    @endif

    {{-- Self-hosted Inter: preload critical Latin & Latin-Extended (Polish) subsets to minimise FOUT --}}
    <link rel="preload" href="/fonts/inter-latin.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="/fonts/inter-latin-ext.woff2" as="font" type="font/woff2" crossorigin>

    {{-- Vite assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Head slot for page-specific meta --}}
    {{ $head ?? '' }}

    {{-- JSON-LD Schema.org Data --}}
    @stack('schema')
</head>
<body class="frontend-shell">
    @if($gtmId)
        <!-- Google Tag Manager (noscript) -->
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ $gtmId }}"
        height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
        <!-- End Google Tag Manager (noscript) -->
    @endif

    {{-- Skip to content — accessibility --}}
    <a href="#main-content" class="skip-to-content">
        Przejdź do treści
    </a>

    {{-- Global Navigation --}}
    <x-frontend.header :theme="$navTheme" />

    {{-- Main Content --}}
    <main id="main-content">
        {{ $slot }}
    </main>

    {{-- Footer --}}
    <x-frontend.footer />


    {{-- Cookie Consent Banner --}}
    <x-frontend.cookie-consent />

    {{-- Reservation & Video-Visit Modal --}}
    <x-frontend.reservation-modal />

    {{-- Mobile Sticky Action Bar --}}
    <x-frontend.mobile-sticky-bar />

    {{-- Scripts slot and stack for page-specific JS --}}
    {{ $scripts ?? '' }}
    @stack('scripts')
</body>
</html>

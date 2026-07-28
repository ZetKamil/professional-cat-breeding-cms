@props([
    'title' => config('app.name', 'Hodowla Kotów'),
    'metaDescription' => 'Profesjonalna hodowla kotów rasowych — zdrowie, piękno, zaufanie.',
])

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="{{ $metaDescription }}">

    {{-- Open Graph --}}
    <meta property="og:title" content="{{ $title }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="pl_PL">

    {{-- Twitter Cards --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $title }}">
    <meta name="twitter:description" content="{{ $metaDescription }}">

    <title>{{ $title }}</title>

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

    {{-- Scripts slot for page-specific JS --}}
    {{ $scripts ?? '' }}
</body>
</html>

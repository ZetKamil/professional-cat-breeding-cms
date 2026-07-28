@props([
    'title' => config('app.name', 'Laravel Blog'),
    'metaDescription' => 'Welkom op onze blog',
])

    <!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="{{ $metaDescription }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>{{ $title }}</title>

    <link rel="icon" href="{{ asset('frontend/gazette/img/core-img/favicon.ico') }}">

    <link rel="stylesheet" href="{{ asset('frontend/gazette/css/core-style.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/gazette/style.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/gazette/css/responsive.css') }}">
</head>
<body>
<x-frontend.header />

{{ $slot }}

<x-frontend.footer />

<script src="{{ asset('frontend/gazette/js/jquery/jquery-2.2.4.min.js') }}"></script>
<script src="{{ asset('frontend/gazette/js/popper.min.js') }}"></script>
<script src="{{ asset('frontend/gazette/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('frontend/gazette/js/plugins.js') }}"></script>
<script src="{{ asset('frontend/gazette/js/active.js') }}"></script>
</body>
</html>

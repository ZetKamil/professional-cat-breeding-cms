<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />

    <title>{{ $title ?? 'Dashboard - SB Admin' }}</title>

    @vite(['resources/css/styles.css', 'resources/js/app.js'])

    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

    {{-- Named slot: extra <head> tags per pagina (optioneel) --}}
    {{ $head ?? '' }}
</head>

<body class="sb-nav-fixed">

<x-backend.topnav />

<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <x-backend.sidenav />
    </div>

    <div id="layoutSidenav_content">
        <main>
            {{--
                SB Admin layout: container-fluid + padding.
                Hierdoor lijnt alles (alerts, cards, tables) netjes uit.
            --}}
            <div class="container-fluid px-4 pt-4">

                {{--
                    Flash component staat hier 1 keer:
                    - validatiefouten ($errors)
                    - session messages (success/error/warning/info)
                    Elke backend pagina profiteert hiervan.
                --}}
                <x-backend.flash />

                {{-- Hier renderen we de content van de pagina (slot) --}}
                {{ $slot }}

            </div>
        </main>

        <x-backend.footer />
    </div>
</div>

{{-- Externe libs die de demo scripts verwachten --}}
{{--<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>--}}
{{--<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>--}}

{{-- Named slot: extra scripts per pagina (optioneel) --}}
{{ $scripts ?? '' }}

</body>
</html>

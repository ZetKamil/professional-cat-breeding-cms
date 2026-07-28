<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 text-slate-900">

<div class="mx-auto max-w-3xl px-6 py-12">
    <div class="overflow-hidden rounded-2xl bg-white shadow-lg ring-1 ring-slate-200">
        <div class="border-b border-slate-200 bg-slate-50 px-8 py-6">
            <h1 class="text-3xl font-bold tracking-tight">Contact</h1>
            <p class="mt-2 text-sm text-slate-600">
                Vul het formulier hieronder in. Zodra je op verzenden klikt, verwerkt Laravel de gegevens en maakt de applicatie een mail aan.
            </p>
        </div>

        <div class="px-8 py-8">
            @if(session('status'))
                <div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                    {{ session('status') }}
                </div>
            @endif

            <form action="{{ route('frontend.contact.store') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label for="name" class="mb-2 block text-sm font-medium text-slate-700">
                        Naam
                    </label>
                    <input
                        type="text"
                        name="name"
                        id="name"
                        value="{{ old('name') }}"
                        class="block w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm shadow-sm outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200"
                    >

                    @error('name')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="mb-2 block text-sm font-medium text-slate-700">
                        E-mail
                    </label>
                    <input
                        type="email"
                        name="email"
                        id="email"
                        value="{{ old('email') }}"
                        class="block w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm shadow-sm outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200"
                    >

                    @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="message" class="mb-2 block text-sm font-medium text-slate-700">
                        Bericht
                    </label>
                    <textarea
                        name="message"
                        id="message"
                        rows="8"
                        class="block w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm shadow-sm outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200"
                    >{{ old('message') }}</textarea>

                    @error('message')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end">
                    <button
                        type="submit"
                        class="inline-flex rounded-lg bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-800"
                    >
                        Verzenden
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>

@props([
    /**
     * Flash keys die we ondersteunen.
     * Controllers gebruiken:
     * ->with('success', ...)
     * ->with('error', ...)
     */
    'keys' => ['success', 'error', 'warning', 'info'],
])

@php
    /**
     * Mapping naar Bootstrap types.
     * Laravel devs gebruiken vaak 'error',
     * Bootstrap gebruikt 'danger'.
     */
    $typeMap = [
        'success' => 'success',
        'error'   => 'danger',
        'warning' => 'warning',
        'info'    => 'info',
    ];
@endphp

{{-- ================= VALIDATIE ERRORS ================= --}}
@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Please fix the following errors:</strong>
        <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- ================= SESSION FLASH ================= --}}
@foreach ($keys as $key)
    @if (session()->has($key))
        @php
            $bootstrapType = $typeMap[$key] ?? 'secondary';
            $value = session()->get($key);
            $messages = is_array($value) ? $value : [$value];

            // HARD FIX: verwijder na render zodat het zeker niet blijft hangen
            session()->forget($key);
        @endphp

        <div class="alert alert-{{ $bootstrapType }} alert-dismissible fade show" role="alert">
            @if (count($messages) === 1)
                {{ $messages[0] }}
            @else
                <ul class="mb-0">
                    @foreach ($messages as $message)
                        <li>{{ $message }}</li>
                    @endforeach
                </ul>
            @endif

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
@endforeach

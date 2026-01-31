@props(['class' => ''])

@php
    // Use company helper if available, fallback to default logo path
    $logo = null;
    try {
        $logo = \App\Helpers\CompanyHelper::logo();
    } catch (\Throwable $e) {
        $logo = null;
    }

    $src = $logo ? asset($logo) : asset('buzbox/img/logo-s.png');
@endphp

<img {{ $attributes->merge(['class' => $class ?: 'block h-9 w-auto']) }} src="{{ $src }}" alt="{{ config('app.name') }}">

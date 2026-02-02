<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Company favicon (if uploaded, fallback to default) -->
        @php
            $branding = \App\Models\CompanySetting::first();
            $favicon = \App\Helpers\CompanyHelper::favicon();
        @endphp
        <link rel="icon" type="image/png" href="{{ $favicon }}" />

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts & Styles -->
        @php
            try {
                echo vite(['resources/css/app.css', 'resources/js/app.js']);
            } catch (\Throwable $e) {
        @endphp
                <link rel="stylesheet" href="{{ asset('css/app.css') }}">
                <script src="{{ asset('js/app.js') }}" defer></script>
        @php
            }
        @endphp

        <!-- Styles -->
        @livewireStyles
    </head>
    <body>
        <div class="font-sans text-gray-900 antialiased">
            @yield('content')
        </div>

        @livewireScripts
    </body>
</html>

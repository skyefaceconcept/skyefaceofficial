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
    <body class="bg-white md:bg-gray-50">
        <div class="font-sans text-gray-900 antialiased min-h-screen flex flex-col">
            <main class="flex-1 px-3 sm:px-4 md:px-6 py-4 sm:py-6 md:py-8">
                @yield('content')
            </main>
        </div>

        @livewireScripts

        <!-- Page Impression Time Tracking -->
        <script>
            (function() {
                const pageStartTime = Date.now();
                const pageUrl = window.location.href;
                let timeSpentSeconds = 0;

                // Update time spent every 10 seconds
                const updateInterval = setInterval(() => {
                    timeSpentSeconds = Math.round((Date.now() - pageStartTime) / 1000);
                }, 10000);

                // Send time spent on page before leaving
                window.addEventListener('beforeunload', () => {
                    clearInterval(updateInterval);
                    timeSpentSeconds = Math.round((Date.now() - pageStartTime) / 1000);

                    if (timeSpentSeconds > 0) {
                        // Use sendBeacon for reliable delivery even if browser is closing
                        const formData = new FormData();
                        formData.append('page_url', pageUrl);
                        formData.append('time_spent_seconds', timeSpentSeconds);
                        formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

                        navigator.sendBeacon('/api/impression-time', formData);
                    }
                });

                // Also track periodically while on page (every 30 seconds) for long sessions
                setInterval(() => {
                    if (timeSpentSeconds > 0 && timeSpentSeconds % 30 === 0) {
                        const formData = new FormData();
                        formData.append('page_url', pageUrl);
                        formData.append('time_spent_seconds', timeSpentSeconds);
                        formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

                        fetch('/api/impression-time', {
                            method: 'POST',
                            body: formData
                        }).catch(() => {}); // Silently fail
                    }
                }, 30000);
            })();
        </script>
    </body>
</html>

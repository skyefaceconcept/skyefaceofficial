<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} - Admin</title>

    <!-- Company favicon (if uploaded, fallback to default) -->
    @php
        $branding = \App\Models\CompanySetting::first();
        $favicon = \App\Helpers\CompanyHelper::favicon();
    @endphp
    <link rel="icon" type="image/png" href="{{ $favicon }}" />

    <!-- StarAdmin CSS -->
    <link rel="stylesheet" href="{{ asset('StarAdmin/src/assets/css/demo_1/style.css') }}">
    <link rel="stylesheet" href="{{ asset('StarAdmin/src/assets/css/shared/style.css') }}">

    @stack('styles')
</head>
<body>

    @include('admin.components.sidebar')

    <div class="main-panel" style="margin-left:260px; padding:20px;">
        @include('admin.components.header', ['title' => $title ?? null])

        <div class="content">
            <div class="page-inner">
                @yield('content')
            </div>
        </div>

        @include('partials.footer-bottom')
    </div>

    <!-- StarAdmin JS -->
    <script src="{{ asset('StarAdmin/src/assets/vendors/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('StarAdmin/src/assets/vendors/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('StarAdmin/src/assets/js/demo_1/dashboard.js') }}"></script>

    @stack('scripts')
</body>
</html>

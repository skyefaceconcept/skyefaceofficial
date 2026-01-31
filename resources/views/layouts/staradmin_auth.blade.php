<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title', 'Auth') - {{ config('app.name') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Company favicon (if uploaded, fallback to default) -->
    @php
        $branding = \App\Models\CompanySetting::first();
        $favicon = \App\Helpers\CompanyHelper::favicon();
    @endphp
    <link rel="icon" type="image/png" href="{{ $favicon }}" />

    <link rel="stylesheet" href="{{ asset('StarAdmin/src/assets/vendors/iconfonts/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('StarAdmin/src/assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('StarAdmin/src/assets/css/shared/style.css') }}">
    <link rel="stylesheet" href="{{ asset('StarAdmin/src/assets/css/demo_1/style.css') }}">

    @yield('extra-css')
</head>
<body>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper d-flex align-items-center auth auth-bg-1 theme-one px-0">
                <div class="row w-100 mx-0">
                    <div class="col-lg-6 col-md-8 mx-auto">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('StarAdmin/src/assets/vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="{{ asset('StarAdmin/src/assets/js/shared/off-canvas.js') }}"></script>
    <script src="{{ asset('StarAdmin/src/assets/js/shared/misc.js') }}"></script>

    @yield('extra-js')
</body>
</html>

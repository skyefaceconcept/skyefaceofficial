<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Skyeface</title>
    <!-- Company favicon (if uploaded, fallback to default) -->
    @php
        $branding = \App\Models\CompanySetting::first();
        $favicon = \App\Helpers\CompanyHelper::favicon();
    @endphp
    <link rel="shortcut icon" href="{{ $favicon }}">

    <!-- Global Stylesheets -->
    <link href="https://fonts.googleapis.com/css?family=Roboto+Condensed" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('buzbox/admin/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('buzbox/admin/font-awesome-4.7.0/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('buzbox/admin/css/font-icon-style.css') }}">
    <link rel="stylesheet" href="{{ asset('buzbox/admin/css/style.default.css') }}" id="theme-stylesheet">
    <link rel="stylesheet" href="{{ asset('buzbox/admin/css/ui-elements/card.css') }}">
    <link rel="stylesheet" href="{{ asset('buzbox/admin/css/style.css') }}">

    @yield('additional_css')

    @livewireStyles

    <style>
        /* Page Layout Structure */
        html, body {
            height: 100%;
            width: 100%;
            margin: 0;
            padding: 0;
        }

        body {
            display: flex;
            flex-direction: column;
        }

        header.header {
            flex: 0 0 auto;
            width: 100%;
        }

        .page-content {
            flex: 1 1 auto;
            display: flex !important;
            align-items: stretch;
            width: 100%;
        }

        nav.side-navbar {
            flex: 0 0 250px;
            width: 250px !important;
            min-width: 250px !important;
            max-width: 250px !important;
            overflow-y: auto;
            background: #fff;
            color: #686a76;
            box-shadow: 1px 1px 1px rgba(0, 0, 0, 0.1);
            z-index: 999;
        }

        .content-inner {
            flex: 1 1 auto;
            width: calc(100% - 250px);
            padding: 25px;
            background: #EEF5F9;
            overflow-y: auto;
        }

        /* Sidebar Menu Styling */
        nav.side-navbar ul li a {
            padding: 10px 15px !important;
            text-decoration: none;
            display: block;
            font-weight: 300;
            border-left: 4px solid transparent;
            transition: all 0.3s ease;
            color: inherit;
        }

        nav.side-navbar ul li a:hover {
            background: linear-gradient(to left, #7c8ce4, #2196f3) !important;
            border-left: 4px solid #7b4397 !important;
            color: #fff !important;
        }

        nav.side-navbar ul li a:hover i {
            color: #fff !important;
        }

        nav.side-navbar ul li.active a {
            background: #EEF5F9 !important;
            border-left: 4px solid #2196f3 !important;
        }

        nav.side-navbar ul li.active a:hover {
            background: linear-gradient(to left, #7c8ce4, #2196f3) !important;
            color: #fff !important;
        }

        /* Submenu Styling */
        nav.side-navbar ul ul li a {
            padding-left: 50px !important;
            background: #EEF5F9 !important;
            font-size: 0.85em;
        }

        nav.side-navbar ul ul li.active a {
            background: #d7ecf9 !important;
            border-left: 4px solid #2196f3 !important;
        }

        /* Icon Styling */
        nav.side-navbar a i {
            font-size: 1.2em;
            width: 25px;
            margin-right: 10px;
            display: inline-block;
            text-align: center;
        }

        /* Menu text */
        nav.side-navbar a span {
            display: inline-block;
            vertical-align: middle;
        }

        /* Collapse icon rotation */
        nav.side-navbar a[aria-expanded="true"]::before {
            content: '\f107' !important;
        }

        nav.side-navbar a[aria-expanded="false"]::before {
            content: '\f104' !important;
        }

        /* Content Inner Margin for Sidebar */
        .content-inner {
            margin-left: 260px;
            padding: 20px;
        }

        @media (max-width: 768px) {
            .content-inner {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Include client topnav component -->
    @include('client.components.topnav')

    <!--====================================================
                            PAGE CONTENT
    ======================================================-->
    <div class="page-content d-flex align-items-stretch">

        <!-- Sidebar Navigation -->
        @include('client.components.sidenav')

        <!--***** CONTENT INNER *****-->
        <div class="content-inner">
            @yield('content')

            <!-- Footer -->
            @include('client.components.footer')
        </div>
    </div>

    <!-- Logout Form -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <!-- Scripts -->
    <script src="{{ asset('buzbox/admin/js/jquery.min.js') }}"></script>
    <script src="{{ asset('buzbox/admin/js/popper.min.js') }}"></script>
    <script src="{{ asset('buzbox/admin/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('buzbox/admin/js/front.js') }}"></script>

    <script>
        document.getElementById('toggle-btn')?.addEventListener('click', function(e) {
            e.preventDefault();
            document.querySelector('.side-navbar')?.classList.toggle('open');
        });

        function toggleFullScreen(elem) {
            elem = elem || document.documentElement;
            if (!document.fullscreenElement && !document.mozFullScreenElement &&
                !document.webkitFullscreenElement && !document.msFullscreenElement) {
                if (elem.requestFullscreen) {
                    elem.requestFullscreen();
                } else if (elem.msRequestFullscreen) {
                    elem.msRequestFullscreen();
                } else if (elem.mozRequestFullScreen) {
                    elem.mozRequestFullScreen();
                } else if (elem.webkitRequestFullscreen) {
                    elem.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
                }
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                } else if (document.msExitFullscreen) {
                    document.msExitFullscreen();
                } else if (document.mozCancelFullScreen) {
                    document.mozCancelFullScreen();
                } else if (document.webkitExitFullscreen) {
                    document.webkitExitFullscreen();
                }
            }
        }
    </script>

    @yield('additional_js')

    @livewireScripts
</body>
</html>

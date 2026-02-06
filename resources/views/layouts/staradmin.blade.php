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

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            display: flex;
            flex-direction: column;
            height: 100vh;
            background-color: #f5f5f5;
        }

        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
            height: 60px;
            background-color: #ffffff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border-bottom: 1px solid #e3e6f0;
        }

        .sidebar {
            position: fixed;
            left: 0;
            top: 60px;
            bottom: 0;
            width: 260px;
            background-color: #2c3e50;
            color: #ecf0f1;
            overflow-y: auto;
            z-index: 1020;
            box-shadow: 2px 0 8px rgba(0, 0, 0, 0.15);
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 3px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }

        .main-panel {
            margin-top: 60px;
            margin-left: 260px;
            padding: 20px;
            flex: 1;
            overflow-y: auto;
            background-color: #f5f5f5;
        }

        .content {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.08);
            padding: 20px;
            min-height: calc(100vh - 60px - 40px);
        }

        .page-inner {
            padding: 0;
        }

        /* Mobile responsive */
        @media (max-width: 992px) {
            .sidebar {
                width: 220px;
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-panel {
                margin-left: 0;
            }

            .sidebar-overlay {
                position: fixed;
                top: 60px;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 1019;
                display: none;
            }

            .sidebar-overlay.show {
                display: block;
            }
        }

        @media (max-width: 768px) {
            .navbar {
                height: 55px;
            }

            .sidebar {
                top: 55px;
                width: 180px;
            }

            .main-panel {
                margin-top: 55px;
                padding: 12px;
            }

            .content {
                padding: 15px;
                border-radius: 6px;
                min-height: calc(100vh - 55px - 24px);
            }
        }

        @media (max-width: 576px) {
            .navbar {
                height: 50px;
            }

            .sidebar {
                top: 50px;
                width: 100%;
                max-width: 250px;
            }

            .main-panel {
                margin-top: 50px;
                padding: 10px;
            }

            .content {
                padding: 12px;
                min-height: calc(100vh - 50px - 20px);
            }
        }
    </style>

    @stack('styles')
</head>
<body>

    @include('admin.components.navbar')
    @include('admin.components.sidebar')
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="main-panel">
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

    <script>
        // Sidebar toggle functionality
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar?.classList.toggle('show');
            overlay?.classList.toggle('show');
        });

        // Close sidebar when clicking overlay
        document.getElementById('sidebarOverlay')?.addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar?.classList.remove('show');
            overlay?.classList.remove('show');
        });

        // Close sidebar if resizing to larger screen
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 992) {
                const sidebar = document.getElementById('sidebar');
                const overlay = document.getElementById('sidebarOverlay');
                sidebar?.classList.remove('show');
                overlay?.classList.remove('show');
            }
        });
    </script>

    @stack('scripts')
</body>
</html>

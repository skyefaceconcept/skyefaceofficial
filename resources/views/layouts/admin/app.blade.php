<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title', 'Admin Dashboard') - Skyeface</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Company favicon (if uploaded, fallback to default) -->
    @php
        $favicon = \App\Helpers\CompanyHelper::favicon();
    @endphp
    <link rel="icon" type="image/png" href="{{ $favicon }}" />

    <!-- plugins:css -->
    <link rel="stylesheet" href="{{ asset('StarAdmin/src/assets/vendors/iconfonts/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('StarAdmin/src/assets/vendors/iconfonts/ionicons/dist/css/ionicons.css') }}">
    <link rel="stylesheet" href="{{ asset('StarAdmin/src/assets/vendors/iconfonts/flag-icon-css/css/flag-icon.min.css') }}">
    <link rel="stylesheet" href="{{ asset('StarAdmin/src/assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('StarAdmin/src/assets/vendors/css/vendor.bundle.addons.css') }}">
    <!-- endinject -->

    <!-- inject:css -->
    <link rel="stylesheet" href="{{ asset('StarAdmin/src/assets/css/shared/style.css') }}">
    <!-- endinject -->

    <!-- Layout styles -->
    <link rel="stylesheet" href="{{ asset('StarAdmin/src/assets/css/demo_1/style.css') }}">
    <!-- End Layout styles -->

    <!-- Company favicon (if uploaded, fallback to default) -->
    @php
        $favicon = \App\Helpers\CompanyHelper::favicon();
    @endphp
    <link rel="icon" type="image/png" href="{{ $favicon }}" />

    @yield('extra-css')

    <!-- Sidebar toggle CSS -->
    <style>
        /* Reset base styles */
        * {
            box-sizing: border-box;
        }

        html, body {
            width: 100% !important;
            overflow-x: hidden !important;
            padding: 0 !important;
            margin: 0 !important;
            font-size: 14px;
        }

        @media (max-width: 480px) {
            html, body {
                font-size: 13px;
            }
        }

        .container-scroller {
            width: 100% !important;
            overflow-x: hidden !important;
            display: flex !important;
            flex-direction: column !important;
            min-height: 100vh !important;
        }

        /* Navbar - responsive */
        .navbar {
            margin: 0 !important;
            padding: 10px 12px !important;
            flex-shrink: 0 !important;
            z-index: 1000 !important;
            position: fixed !important;
            top: 0 !important;
            width: 100% !important;
            left: 0 !important;
            height: auto !important;
            min-height: 50px;
            display: flex !important;
            align-items: center !important;
        }

        @media (min-width: 768px) {
            .navbar {
                padding: 15px 20px !important;
                min-height: 60px;
            }
            /* Make the page content start below the taller navbar on desktop */
            .page-body-wrapper {
                padding-top: 60px !important;
            }
        }

        /* Page Body Wrapper - responsive layout */
        .page-body-wrapper {
            margin-left: 250px !important;
            width: calc(100% - 250px) !important;
            padding-top: 50px !important;
            margin-top: 0 !important;
            display: flex !important;
            flex-direction: row !important;
            flex: 1 !important;
            overflow-x: hidden !important;
            transition: margin-left 0.3s ease, width 0.3s ease !important;
        }

        /* Make brand wrapper align and shrink with sidebar when collapsed */
        .navbar .navbar-brand-wrapper {
            height: 100%;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            transition: min-width 0.25s ease, padding 0.25s ease;
        }

        .page-body-wrapper.sidebar-collapsed .navbar-brand-wrapper {
            min-width: 70px !important;
            max-width: 70px !important;
            padding-left: 8px !important;
            padding-right: 8px !important;
        }

        /* Mobile - hide sidebar by default */
        @media (max-width: 768px) {
            .page-body-wrapper {
                margin-left: 0 !important;
                width: 100% !important;
                padding-top: 50px !important;
            }

            .navbar {
                padding: 8px 10px !important;
                min-height: 45px;
            }
        }

        /* Sidebar - responsive */
        .sidebar {
            position: fixed !important;
            left: 0 !important;
            top: 50px !important;
            width: 250px !important;
            height: calc(100vh - 50px) !important;
            display: flex !important;
            flex-direction: column !important;
            overflow: hidden !important;
            z-index: 999 !important;
            transition: all 0.3s ease !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        @media (min-width: 768px) {
            .sidebar {
                top: 60px !important;
                height: calc(100vh - 60px) !important;
            }
        }

        /* Mobile - slide out sidebar */
        @media (max-width: 768px) {
            .sidebar {
                left: -250px !important;
                top: 45px !important;
                height: calc(100vh - 45px) !important;
                box-shadow: 2px 0 5px rgba(0,0,0,0.1);
            }

            .sidebar.show {
                left: 0 !important;
            }

            /* Overlay when sidebar is open */
            body.sidebar-open::before {
                content: '';
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0,0,0,0.5);
                z-index: 998;
            }
        }

        /* Sidebar navigation list */
        .sidebar .nav {
            flex: 1 !important;
            overflow-y: auto !important;
            overflow-x: hidden !important;
            padding-right: 5px !important;
            padding: 15px 0 !important;
        }

        /* Nav items and links */
        .nav-item {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .nav-link {
            color: #fff;
            text-decoration: none;
            display: flex !important;
            align-items: center !important;
            padding: 12px 20px !important;
            font-size: 13px;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            background-color: rgba(255,255,255,0.1);
        }

        .menu-icon {
            margin-right: 12px;
            font-size: 18px;
            display: flex;
            align-items: center;
        }

        .menu-title {
            flex-grow: 1;
            white-space: nowrap;
        }

        .menu-arrow {
            margin-left: auto;
            font-size: 14px;
            transition: transform 0.3s ease;
        }

        .sub-menu {
            background-color: rgba(0,0,0,0.1);
            margin: 0 !important;
            padding: 0 !important;
            list-style: none;
            display: flex;
            flex-direction: column;
        }

        .sub-menu .nav-link {
            padding-left: 40px !important;
            font-size: 12px;
        }

        /* Collapse/Submenu styling */
        .collapse {
            display: none;
        }

        .collapse.show {
            display: flex !important;
            flex-direction: column;
        }

        /* Custom scrollbar styling */
        .sidebar .nav::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar .nav::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar .nav::-webkit-scrollbar-thumb {
            background: #ccc;
            border-radius: 4px;
            transition: background 0.3s ease;
        }

        .sidebar .nav::-webkit-scrollbar-thumb:hover {
            background: #999;
        }

        .sidebar .nav {
            scrollbar-color: #ccc transparent;
            scrollbar-width: thin;
        }

        /* Responsive adjustments */
        @media (max-width: 480px) {
            .nav-link {
                padding: 10px 15px !important;
                font-size: 12px;
            }
        }

        /* Sidebar collapsed state */
        .sidebar.sidebar-collapsed {
            width: 70px !important;
        }

        .page-body-wrapper.sidebar-collapsed {
            margin-left: 70px !important;
            width: calc(100% - 70px) !important;
        }

        @media (max-width: 768px) {
            .sidebar.sidebar-collapsed {
                left: -70px !important;
            }

            .sidebar.sidebar-collapsed.show {
                left: 0 !important;
            }

            .page-body-wrapper.sidebar-collapsed {
                margin-left: 0 !important;
                width: 100% !important;
            }
        }

        .sidebar.sidebar-collapsed .nav-item {
            text-align: center;
        }

        .sidebar.sidebar-collapsed .menu-title,
        .sidebar.sidebar-collapsed .text-wrapper,
        .sidebar.sidebar-collapsed .nav-category,
        .sidebar.sidebar-collapsed .sub-menu span {
            display: none !important;
        }

        .sidebar.sidebar-collapsed .nav-link {
            padding: 10px 0 !important;
            justify-content: center !important;
            display: flex !important;
            align-items: center !important;
        }

        .sidebar.sidebar-collapsed .menu-icon,
        .sidebar.sidebar-collapsed .mdi {
            margin-right: 0 !important;
            margin-left: 0 !important;
        }

        .sidebar.sidebar-collapsed .menu-arrow {
            display: none !important;
        }

        .sidebar.sidebar-collapsed .nav-profile {
            flex-direction: column;
            padding: 15px 0 !important;
        }

        .sidebar.sidebar-collapsed .collapse {
            display: none !important;
        }

        /* Main Panel */
        .main-panel {
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            display: flex !important;
            flex-direction: column !important;
            flex: 1 !important;
        }

        /* Content Wrapper - responsive padding */
        .content-wrapper {
            padding: 20px 15px !important;
            margin: 0 !important;
            width: 100% !important;
            flex: 1 !important;
            display: flex !important;
            flex-direction: column !important;
            margin-top: 0 !important;
            padding-top: 20px !important;
            overflow-x: auto;
        }

        @media (min-width: 480px) {
            .content-wrapper {
                padding: 25px 20px !important;
                padding-top: 25px !important;
            }
        }

        @media (min-width: 768px) {
            .content-wrapper {
                padding: 30px !important;
                padding-top: 30px !important;
            }
        }

        /* Tables - responsive */
        .table {
            width: 100%;
            margin-bottom: 20px;
            font-size: 13px;
        }

        @media (max-width: 768px) {
            .table {
                font-size: 12px;
            }

            .table th,
            .table td {
                padding: 8px 6px !important;
            }
        }

        /* Cards - responsive */
        .card {
            margin-bottom: 20px;
            border-radius: 4px;
        }

        .card-header {
            padding: 15px;
            font-size: 14px;
        }

        @media (max-width: 480px) {
            .card-header {
                padding: 12px;
                font-size: 13px;
            }
        }

        /* Forms - responsive */
        .form-group {
            margin-bottom: 15px;
        }

        .form-control, .form-select {
            width: 100%;
            padding: 8px 12px;
            font-size: 13px;
            border-radius: 4px;
        }

        @media (max-width: 480px) {
            .form-control, .form-select {
                padding: 8px 10px;
                font-size: 12px;
            }
        }

        /* Buttons - responsive */
        .btn {
            padding: 8px 16px;
            font-size: 13px;
            border-radius: 4px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        @media (max-width: 480px) {
            .btn {
                padding: 8px 12px;
                font-size: 12px;
            }

            .btn-block {
                width: 100%;
            }
        }

        /* Grid - responsive */
        .row {
            display: flex;
            flex-wrap: wrap;
            margin-right: -15px;
            margin-left: -15px;
        }

        @media (max-width: 768px) {
            .col-lg-4, .col-lg-6, .col-lg-8, .col-lg-12,
            .col-md-4, .col-md-6, .col-md-8, .col-md-12 {
                flex: 0 0 100%;
                max-width: 100%;
            }
        }

        @media (max-width: 480px) {
            .row {
                margin-right: -10px;
                margin-left: -10px;
            }

            [class*="col-"] {
                padding-right: 10px;
                padding-left: 10px;
            }
        }

        /* Menu toggle button */
        .sidebar-toggle {
            display: none;
            background: none;
            border: none;
            color: #fff;
            font-size: 20px;
            cursor: pointer;
            padding: 5px 10px;
        }

        @media (max-width: 768px) {
            .sidebar-toggle {
                display: inline-flex;
                align-items: center;
            }
        }

        /* Badge styling */
        .badge {
            display: inline-block;
            padding: 3px 6px;
            border-radius: 10px;
            font-size: 10px;
            font-weight: 600;
            white-space: nowrap;
        }

        .badge-danger {
            background-color: #dc3545;
            color: white;
        }

        .badge-warning {
            background-color: #ffc107;
            color: #333;
        }

        .badge-success {
            background-color: #28a745;
            color: white;
        }

        .badge-info {
            background-color: #17a2b8;
            color: white;
        }

        .badge-pill {
            border-radius: 10px;
        }

    </style>

</head>
<body>
    <div class="container-scroller">
        <!-- Navbar -->
        @include('admin.components.navbar')

        <div class="container-fluid page-body-wrapper">
            <!-- Sidebar -->
            @include('admin.components.sidebar')

            <!-- Main Content -->
            <div class="main-panel">
                <div class="content-wrapper">
                    @yield('content')
                </div>

                <!-- Footer -->
                @include('admin.components.footer')
            </div>
        </div>
    </div>

    <!-- plugins:js -->
    <script src="{{ asset('StarAdmin/src/assets/vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="{{ asset('StarAdmin/src/assets/vendors/js/vendor.bundle.addons.js') }}"></script>
    <!-- endinject -->

    <!-- inject:js -->
    <script src="{{ asset('StarAdmin/src/assets/js/shared/off-canvas.js') }}"></script>
    <script src="{{ asset('StarAdmin/src/assets/js/shared/misc.js') }}"></script>
    <!-- endinject -->

    <!-- Admin Responsive JS -->
    <script src="{{ asset('js/admin-responsive.js') }}"></script>

    @yield('extra-js')

    @stack('scripts')

    <!-- Sidebar toggle script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            const pageBodyWrapper = document.querySelector('.page-body-wrapper');

            // Load sidebar state from localStorage
            const sidebarCollapsed = localStorage.getItem('sidebar-collapsed') === 'true';
            if (sidebarCollapsed) {
                sidebar.classList.add('sidebar-collapsed');
                pageBodyWrapper.classList.add('sidebar-collapsed');
            }

            // Toggle sidebar on button click
            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('sidebar-collapsed');
                pageBodyWrapper.classList.toggle('sidebar-collapsed');

                // Save state to localStorage
                const isCollapsed = sidebar.classList.contains('sidebar-collapsed');
                localStorage.setItem('sidebar-collapsed', isCollapsed);
            });
        });
    </script>

</body>
</html>

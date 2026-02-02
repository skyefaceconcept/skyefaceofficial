<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?php echo $__env->yieldContent('title', 'Admin Dashboard'); ?> - Skyeface</title>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <!-- Company favicon (if uploaded, fallback to default) -->
    <?php
        $favicon = \App\Helpers\CompanyHelper::favicon();
    ?>
    <link rel="icon" type="image/png" href="<?php echo e($favicon); ?>" />

    <!-- plugins:css -->
    <link rel="stylesheet" href="<?php echo e(asset('StarAdmin/src/assets/vendors/iconfonts/mdi/css/materialdesignicons.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('StarAdmin/src/assets/vendors/iconfonts/ionicons/dist/css/ionicons.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('StarAdmin/src/assets/vendors/iconfonts/flag-icon-css/css/flag-icon.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('StarAdmin/src/assets/vendors/css/vendor.bundle.base.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('StarAdmin/src/assets/vendors/css/vendor.bundle.addons.css')); ?>">
    <!-- endinject -->

    <!-- inject:css -->
    <link rel="stylesheet" href="<?php echo e(asset('StarAdmin/src/assets/css/shared/style.css')); ?>">
    <!-- endinject -->

    <!-- Layout styles -->
    <link rel="stylesheet" href="<?php echo e(asset('StarAdmin/src/assets/css/demo_1/style.css')); ?>">
    <!-- End Layout styles -->

    <!-- Company favicon (if uploaded, fallback to default) -->
    <?php
        $favicon = \App\Helpers\CompanyHelper::favicon();
    ?>
    <link rel="icon" type="image/png" href="<?php echo e($favicon); ?>" />

    <?php echo $__env->yieldContent('extra-css'); ?>

    <!-- Sidebar toggle CSS -->
    <style>
        /* Base styles */
        html, body {
            width: 100% !important;
            overflow-x: hidden !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        .container-scroller {
            width: 100% !important;
            overflow-x: hidden !important;
            display: flex !important;
            flex-direction: column !important;
            min-height: 100vh !important;
        }

        /* Navbar */
        .navbar {
            margin: 0 !important;
            padding: 0 !important;
            flex-shrink: 0 !important;
            z-index: 1000 !important;
            position: fixed !important;
            top: 0 !important;
            width: 100% !important;
            left: 0 !important;
        }

        /* Page Body Wrapper - Main flex container */
        .page-body-wrapper {
            margin-left: 250px !important;
            width: calc(100% - 250px) !important;
            padding-top: 60px !important;
            margin-top: 0 !important;
            display: flex !important;
            flex-direction: row !important;
            flex: 1 !important;
            overflow-x: hidden !important;
            transition: margin-left 0.3s ease, width 0.3s ease !important;
        }

        /* When sidebar is collapsed */
        .page-body-wrapper.sidebar-collapsed {
            margin-left: 70px !important;
            width: calc(100% - 70px) !important;
        }

        /* Sidebar - Base styles */
        .sidebar {
            position: fixed !important;
            left: 0 !important;
            top: 60px !important;
            width: 250px !important;
            height: calc(100vh - 60px) !important;
            display: flex !important;
            flex-direction: column !important;
            overflow: hidden !important;
            z-index: 999 !important;
            transition: width 0.3s ease !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        /* Sidebar navigation list */
        .sidebar .nav {
            flex: 1 !important;
            overflow-y: auto !important;
            overflow-x: hidden !important;
            padding-right: 5px !important;
        }

        /* Custom scrollbar styling */
        .sidebar .nav::-webkit-scrollbar {
            width: 8px;
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

        /* Firefox scrollbar */
        .sidebar .nav {
            scrollbar-color: #ccc transparent;
            scrollbar-width: thin;
        }

        /* Sidebar collapsed state */
        .sidebar.sidebar-collapsed {
            width: 70px !important;
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

        /* Hide collapse menu when sidebar is collapsed */
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

        /* Content Wrapper */
        .content-wrapper {
            padding: 30px !important;
            margin: 0 !important;
            width: 100% !important;
            flex: 1 !important;
            display: flex !important;
            flex-direction: column !important;
            margin-top: 0 !important;
            padding-top: 30px !important;
        }


    </style>

</head>
<body>
    <div class="container-scroller">
        <!-- Navbar -->
        <?php echo $__env->make('admin.components.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <div class="container-fluid page-body-wrapper">
            <!-- Sidebar -->
            <?php echo $__env->make('admin.components.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <!-- Main Content -->
            <div class="main-panel">
                <div class="content-wrapper">
                    <?php echo $__env->yieldContent('content'); ?>
                </div>

                <!-- Footer -->
                <?php echo $__env->make('admin.components.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
        </div>
    </div>

    <!-- plugins:js -->
    <script src="<?php echo e(asset('StarAdmin/src/assets/vendors/js/vendor.bundle.base.js')); ?>"></script>
    <script src="<?php echo e(asset('StarAdmin/src/assets/vendors/js/vendor.bundle.addons.js')); ?>"></script>
    <!-- endinject -->

    <!-- inject:js -->
    <script src="<?php echo e(asset('StarAdmin/src/assets/js/shared/off-canvas.js')); ?>"></script>
    <script src="<?php echo e(asset('StarAdmin/src/assets/js/shared/misc.js')); ?>"></script>
    <!-- endinject -->

    <?php echo $__env->yieldContent('extra-js'); ?>

    <?php echo $__env->yieldPushContent('scripts'); ?>

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
<?php /**PATH C:\laragon\www\Skyefaceofficial\resources\views/layouts/admin/app.blade.php ENDPATH**/ ?>
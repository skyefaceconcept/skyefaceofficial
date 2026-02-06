<!-- Client Top Navigation Bar -->
<header class="header">
    <nav class="navbar navbar-expand-lg">
        
        <div class="search-box" id="top-search-box" aria-hidden="true">
            <button class="dismiss" aria-label="Close search"><i class="icon-close"></i></button>
            <form id="searchForm" action="#" role="search">
                <label for="top-search-input" class="sr-only">Search</label>
                <input id="top-search-input" type="search" placeholder="Search your tickets..." class="form-control">
            </form>
        </div>

        <div class="container-fluid">
            <div class="navbar-holder d-flex align-items-center justify-content-between w-100">
                <div class="navbar-header d-flex align-items-center">
                    <a href="<?php echo e(route('dashboard')); ?>" class="navbar-brand d-flex align-items-center" title="Skyeface Dashboard">
                        <img src="<?php echo e(asset('Buzbox/img/logo-white.png')); ?>" alt="Skyeface Logo" class="img-fluid" style="height:32px;">
                        <span class="ml-2 d-none d-sm-inline font-weight-bold text-white" style="letter-spacing: 0.5px;">Skyeface</span>
                    </a>

                    <a id="toggle-btn" href="#" class="menu-btn ml-3" aria-label="Toggle sidebar">
                        <span></span>
                        <span></span>
                        <span></span>
                    </a>
                </div>

                <ul class="nav-menu list-unstyled d-flex flex-md-row align-items-md-center mb-0 ml-auto">
                    <!-- Fullscreen -->
                    <li class="nav-item d-flex align-items-center mr-2">
                        <button class="btn btn-link nav-link p-0" type="button" onclick="toggleFullScreen(document.documentElement)" aria-label="Toggle fullscreen">
                            <img src="<?php echo e(asset('Buzbox/admin/img/expand.png')); ?>" class="img-fluid" alt="Fullscreen" style="height:20px;">
                        </button>
                    </li>

                    <!-- Search toggle -->
                    <li class="nav-item d-flex align-items-center mr-2">
                        <button id="search" class="btn btn-link nav-link p-0" type="button" aria-expanded="false" aria-controls="top-search-box" aria-label="Open search">
                            <i class="icon-search" aria-hidden="true"></i>
                            <span class="sr-only">Search</span>
                        </button>
                    </li>

                    <!-- User Profile Dropdown -->
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
                    <li class="nav-item dropdown">
                        <a id="userDropdown" class="nav-link d-flex align-items-center" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" aria-label="User menu">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->profile_photo_path && \Illuminate\Support\Facades\Storage::disk('public')->exists(auth()->user()->profile_photo_path)): ?>
                                <img src="<?php echo e(asset('storage/' . auth()->user()->profile_photo_path)); ?>" alt="Profile" class="rounded-circle" style="width: 32px; height: 32px; object-fit: cover;">
                            <?php else: ?>
                                <i class="fa fa-user-circle" style="font-size: 24px;"></i>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <span class="d-none d-md-inline ml-2"><?php echo e(auth()->user()->fname ?? auth()->user()->name); ?></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                            <h6 class="dropdown-header"><?php echo e(auth()->user()->email); ?></h6>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="<?php echo e(route('profile.show')); ?>">
                                <i class="fa fa-user mr-2"></i> My Profile
                            </a>
                            <a class="dropdown-item" href="<?php echo e(route('dashboard')); ?>">
                                <i class="fa fa-tachometer mr-2"></i> Dashboard
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#" onclick="document.getElementById('logout-form').submit();">
                                <i class="fa fa-sign-out mr-2"></i> Logout
                            </a>
                        </div>
                    </li>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->guest()): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo e(route('login')); ?>">
                            <i class="fa fa-sign-in mr-1"></i>Login
                        </a>
                    </li>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
</header>

<script>
    // Search box toggle
    document.getElementById('search')?.addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('top-search-box')?.setAttribute('aria-hidden', 'false');
        document.getElementById('top-search-input')?.focus();
    });

    document.querySelector('.search-box .dismiss')?.addEventListener('click', function() {
        document.getElementById('top-search-box')?.setAttribute('aria-hidden', 'true');
    });

    // Close search on Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.getElementById('top-search-box')?.setAttribute('aria-hidden', 'true');
        }
    });
</script>
<?php /**PATH C:\laragon\www\Skyefaceofficial\resources\views/client/components/topnav.blade.php ENDPATH**/ ?>
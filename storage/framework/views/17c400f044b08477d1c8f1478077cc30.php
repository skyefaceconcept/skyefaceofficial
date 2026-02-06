<!-- Client Sidebar Navigation (Reorganized) -->
<nav class="side-navbar" id="client-sidebar">
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
    <div class="sidebar-header d-flex align-items-center justify-content-between">
        <div class="user-pic d-flex align-items-center">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->profile_photo_path && \Illuminate\Support\Facades\Storage::disk('public')->exists(auth()->user()->profile_photo_path)): ?>
                <img src="<?php echo e(asset('storage/' . auth()->user()->profile_photo_path)); ?>" alt="Profile" class="rounded-circle" style="width: 48px; height: 48px; object-fit: cover;">
            <?php else: ?>
                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; font-size: 24px;">
                    <i class="fa fa-user"></i>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <div class="ml-3">
                <p class="mb-0"><?php echo e(auth()->user()->fname ?? auth()->user()->name); ?> <?php echo e(auth()->user()->lname); ?></p>
                <small class="text-muted">Client</small>
            </div>
        </div>
    </div>

    <ul class="list-unstyled">
        <!-- Primary -->
        <li class="sidebar-item">
            <a href="<?php echo e(route('dashboard')); ?>" class="sidebar-link <?php if(request()->routeIs('dashboard')): ?> active <?php endif; ?>">
                <i class="fa fa-tachometer mr-2"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <!-- Payments -->
        <li class="sidebar-item">
            <a href="#payments-submenu" data-toggle="collapse" aria-expanded="<?php if(request()->is('payment-history*') || request()->routeIs('payments.*')): ?> true <?php else: ?> false <?php endif; ?>" class="sidebar-link">
                <i class="fa fa-credit-card mr-2"></i>
                <span>Payments</span>
                <i class="fa fa-angle-down ml-auto"></i>
            </a>
            <ul id="payments-submenu" class="collapse list-unstyled <?php if(request()->is('payment-history*') || request()->routeIs('payments.*')): ?> show <?php endif; ?>">
                <li><a href="<?php echo e(url('/payment-history')); ?>" class="sidebar-link text-small"><i class="fa fa-history mr-2"></i>Payment History</a></li>
                <li><a href="<?php echo e(route('home')); ?>#pricing" class="sidebar-link text-small"><i class="fa fa-money mr-2"></i>Make a Payment</a></li>
            </ul>
        </li>

        <!-- Quotes -->
        <li class="sidebar-item">
            <a href="#quotes-submenu" data-toggle="collapse" aria-expanded="<?php if(request()->is('quotes*') || request()->routeIs('dashboard')): ?> true <?php else: ?> false <?php endif; ?>" class="sidebar-link">
                <i class="fa fa-file-invoice mr-2"></i>
                <span>Quotes</span>
                <i class="fa fa-angle-down ml-auto"></i>
            </a>
            <ul id="quotes-submenu" class="collapse list-unstyled <?php if(request()->is('quotes*') || request()->routeIs('dashboard')): ?> show <?php endif; ?>">
                <li><a href="<?php echo e(route('dashboard')); ?>#quotes" class="sidebar-link text-small"><i class="fa fa-list mr-2"></i>My Quotes</a></li>
                <li><a href="<?php echo e(route('home')); ?>#services" class="sidebar-link text-small"><i class="fa fa-plus mr-2"></i>Request New</a></li>
            </ul>
        </li>

        <!-- Messages -->
        <li class="sidebar-item">
            <a href="<?php echo e(url('/messages')); ?>" class="sidebar-link <?php if(request()->is('messages*')): ?> active <?php endif; ?>">
                <i class="fa fa-envelope mr-2"></i>
                <span>Messages</span>
            </a>
        </li>

        <!-- Support Tickets -->
        <li class="sidebar-item">
            <a href="#tickets-submenu" data-toggle="collapse" aria-expanded="<?php if(request()->is('tickets*') || request()->routeIs('tickets.*')): ?> true <?php else: ?> false <?php endif; ?>" class="sidebar-link">
                <i class="fa fa-ticket mr-2"></i>
                <span>Support Tickets</span>
                <i class="fa fa-angle-down ml-auto"></i>
            </a>
            <ul id="tickets-submenu" class="collapse list-unstyled <?php if(request()->is('tickets*') || request()->routeIs('tickets.*')): ?> show <?php endif; ?>">
                <li><a href="<?php echo e(url('/tickets')); ?>" class="sidebar-link text-small"><i class="fa fa-list mr-2"></i>View All</a></li>
                <li><a href="<?php echo e(route('home')); ?>#contact" class="sidebar-link text-small"><i class="fa fa-plus mr-2"></i>Create New</a></li>
            </ul>
        </li>

        <li class="sidebar-item"><hr class="my-2"></li>

        <!-- Account -->
        <li class="sidebar-item">
            <a href="#account-submenu" data-toggle="collapse" aria-expanded="<?php if(request()->routeIs('profile.*') || request()->is('settings*')): ?> true <?php else: ?> false <?php endif; ?>" class="sidebar-link">
                <i class="fa fa-cog mr-2"></i>
                <span>Account</span>
                <i class="fa fa-angle-down ml-auto"></i>
            </a>
            <ul id="account-submenu" class="collapse list-unstyled <?php if(request()->routeIs('profile.*') || request()->is('settings*')): ?> show <?php endif; ?>">
                <li><a href="<?php echo e(route('profile.show')); ?>" class="sidebar-link text-small"><i class="fa fa-user mr-2"></i>My Profile</a></li>
                <li><a href="<?php echo e(url('/settings')); ?>" class="sidebar-link text-small"><i class="fa fa-sliders mr-2"></i>Settings</a></li>
                <li><a href="<?php echo e(url('/billing')); ?>" class="sidebar-link text-small"><i class="fa fa-credit-card mr-2"></i>Billing</a></li>
            </ul>
        </li>

        <!-- Help & Logout -->
        <li class="sidebar-item">
            <a href="<?php echo e(url('/help')); ?>" class="sidebar-link <?php if(request()->is('help*')): ?> active <?php endif; ?>">
                <i class="fa fa-question-circle mr-2"></i>
                <span>Help Center</span>
            </a>
        </li>

        <li class="sidebar-item">
            <a href="#" class="sidebar-link text-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fa fa-sign-out mr-2"></i>
                <span>Logout</span>
            </a>
        </li>
    </ul>

    
    <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display:none;"><?php echo csrf_field(); ?></form>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->guest()): ?>
    <div class="sidebar-header d-flex align-items-center justify-content-between">
        <p class="mb-0 text-center w-100">
            <a href="<?php echo e(route('login')); ?>" class="btn btn-sm btn-primary">
                <i class="fa fa-sign-in mr-1"></i>Login
            </a>
        </p>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</nav>

<style>
.side-navbar {
    position: fixed;
    left: 0;
    top: 0;
    width: 260px;
    height: 100vh;
    padding: 50px 0 0;
    background: #fff;
    border-right: 1px solid #e9e9e9;
    display: flex;
    flex-direction: column;
    z-index: 1000;
}

.side-navbar .sidebar-header {
    flex-shrink: 0;
}

.side-navbar ul {
    flex: 1;
    overflow-y: auto;
    overflow-x: hidden;
    padding-right: 5px;
}

/* Custom scrollbar styling for client sidebar */
.side-navbar ul::-webkit-scrollbar {
    width: 8px;
}

.side-navbar ul::-webkit-scrollbar-track {
    background: transparent;
}

.side-navbar ul::-webkit-scrollbar-thumb {
    background: #d0d0d0;
    border-radius: 4px;
    transition: background 0.3s ease;
}

.side-navbar ul::-webkit-scrollbar-thumb:hover {
    background: #999;
}

/* Firefox scrollbar */
.side-navbar ul {
    scrollbar-color: #d0d0d0 transparent;
    scrollbar-width: thin;
}

.sidebar-header {
    padding: 20px;
    border-bottom: 1px solid #e9e9e9;
}

.sidebar-item {
    border-bottom: 1px solid #f1f1f1;
}

.sidebar-link {
    display: flex;
    align-items: center;
    padding: 12px 20px;
    color: #333;
    text-decoration: none;
    transition: all 0.2s ease;
}

.sidebar-link:hover {
    background: #f5f5f5;
    color: #2196F3;
}

.sidebar-link.active {
    background: #e3f2fd;
    color: #2196F3;
    border-left: 3px solid #2196F3;
    padding-left: 17px;
}

.sidebar-link .fa-angle-down {
    transition: transform 0.3s ease;
}

.sidebar-link[aria-expanded="true"] .fa-angle-down {
    transform: rotate(180deg);
}

.sidebar-item .collapse {
    background: #fafafa;
}

.sidebar-item .collapse .sidebar-link {
    padding-left: 40px;
    font-size: 0.9rem;
}

@media (max-width: 768px) {
    .side-navbar {
        transform: translateX(-100%);
        transition: transform 0.3s ease;
    }

    .side-navbar.open {
        transform: translateX(0);
    }
}
</style>
<?php /**PATH C:\laragon\www\Skyefaceofficial\resources\views/client/components/sidenav.blade.php ENDPATH**/ ?>
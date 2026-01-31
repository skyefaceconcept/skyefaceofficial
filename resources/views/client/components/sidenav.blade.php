<!-- Client Sidebar Navigation (Reorganized) -->
<nav class="side-navbar" id="client-sidebar">
    @auth
    <div class="sidebar-header d-flex align-items-center justify-content-between">
        <div class="user-pic d-flex align-items-center">
            @if(auth()->user()->profile_photo_path && \Illuminate\Support\Facades\Storage::disk('public')->exists(auth()->user()->profile_photo_path))
                <img src="{{ asset('storage/' . auth()->user()->profile_photo_path) }}" alt="Profile" class="rounded-circle" style="width: 48px; height: 48px; object-fit: cover;">
            @else
                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; font-size: 24px;">
                    <i class="fa fa-user"></i>
                </div>
            @endif
            <div class="ml-3">
                <p class="mb-0">{{ auth()->user()->fname ?? auth()->user()->name }} {{ auth()->user()->lname }}</p>
                <small class="text-muted">Client</small>
            </div>
        </div>
    </div>

    <ul class="list-unstyled">
        <!-- Primary -->
        <li class="sidebar-item">
            <a href="{{ route('dashboard') }}" class="sidebar-link @if(request()->routeIs('dashboard')) active @endif">
                <i class="fa fa-tachometer mr-2"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <!-- Payments -->
        <li class="sidebar-item">
            <a href="#payments-submenu" data-toggle="collapse" aria-expanded="@if(request()->is('payment-history*') || request()->routeIs('payments.*')) true @else false @endif" class="sidebar-link">
                <i class="fa fa-credit-card mr-2"></i>
                <span>Payments</span>
                <i class="fa fa-angle-down ml-auto"></i>
            </a>
            <ul id="payments-submenu" class="collapse list-unstyled @if(request()->is('payment-history*') || request()->routeIs('payments.*')) show @endif">
                <li><a href="{{ url('/payment-history') }}" class="sidebar-link text-small"><i class="fa fa-history mr-2"></i>Payment History</a></li>
                <li><a href="{{ route('home') }}#pricing" class="sidebar-link text-small"><i class="fa fa-money mr-2"></i>Make a Payment</a></li>
            </ul>
        </li>

        <!-- Quotes -->
        <li class="sidebar-item">
            <a href="#quotes-submenu" data-toggle="collapse" aria-expanded="@if(request()->is('quotes*') || request()->routeIs('dashboard')) true @else false @endif" class="sidebar-link">
                <i class="fa fa-file-invoice mr-2"></i>
                <span>Quotes</span>
                <i class="fa fa-angle-down ml-auto"></i>
            </a>
            <ul id="quotes-submenu" class="collapse list-unstyled @if(request()->is('quotes*') || request()->routeIs('dashboard')) show @endif">
                <li><a href="{{ route('dashboard') }}#quotes" class="sidebar-link text-small"><i class="fa fa-list mr-2"></i>My Quotes</a></li>
                <li><a href="{{ route('home') }}#services" class="sidebar-link text-small"><i class="fa fa-plus mr-2"></i>Request New</a></li>
            </ul>
        </li>

        <!-- Messages -->
        <li class="sidebar-item">
            <a href="{{ url('/messages') }}" class="sidebar-link @if(request()->is('messages*')) active @endif">
                <i class="fa fa-envelope mr-2"></i>
                <span>Messages</span>
            </a>
        </li>

        <!-- Support Tickets -->
        <li class="sidebar-item">
            <a href="#tickets-submenu" data-toggle="collapse" aria-expanded="@if(request()->is('tickets*') || request()->routeIs('tickets.*')) true @else false @endif" class="sidebar-link">
                <i class="fa fa-ticket mr-2"></i>
                <span>Support Tickets</span>
                <i class="fa fa-angle-down ml-auto"></i>
            </a>
            <ul id="tickets-submenu" class="collapse list-unstyled @if(request()->is('tickets*') || request()->routeIs('tickets.*')) show @endif">
                <li><a href="{{ url('/tickets') }}" class="sidebar-link text-small"><i class="fa fa-list mr-2"></i>View All</a></li>
                <li><a href="{{ route('home') }}#contact" class="sidebar-link text-small"><i class="fa fa-plus mr-2"></i>Create New</a></li>
            </ul>
        </li>

        <li class="sidebar-item"><hr class="my-2"></li>

        <!-- Account -->
        <li class="sidebar-item">
            <a href="#account-submenu" data-toggle="collapse" aria-expanded="@if(request()->routeIs('profile.*') || request()->is('settings*')) true @else false @endif" class="sidebar-link">
                <i class="fa fa-cog mr-2"></i>
                <span>Account</span>
                <i class="fa fa-angle-down ml-auto"></i>
            </a>
            <ul id="account-submenu" class="collapse list-unstyled @if(request()->routeIs('profile.*') || request()->is('settings*')) show @endif">
                <li><a href="{{ route('profile.show') }}" class="sidebar-link text-small"><i class="fa fa-user mr-2"></i>My Profile</a></li>
                <li><a href="{{ url('/settings') }}" class="sidebar-link text-small"><i class="fa fa-sliders mr-2"></i>Settings</a></li>
                <li><a href="{{ url('/billing') }}" class="sidebar-link text-small"><i class="fa fa-credit-card mr-2"></i>Billing</a></li>
            </ul>
        </li>

        <!-- Help & Logout -->
        <li class="sidebar-item">
            <a href="{{ url('/help') }}" class="sidebar-link @if(request()->is('help*')) active @endif">
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

    {{-- Hidden logout form (in case not present elsewhere) --}}
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
    @endauth

    @guest
    <div class="sidebar-header d-flex align-items-center justify-content-between">
        <p class="mb-0 text-center w-100">
            <a href="{{ route('login') }}" class="btn btn-sm btn-primary">
                <i class="fa fa-sign-in mr-1"></i>Login
            </a>
        </p>
    </div>
    @endguest
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

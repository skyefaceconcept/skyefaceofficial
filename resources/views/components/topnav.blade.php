<!-- Top Navigation Bar -->
<header class="header">
    <nav class="navbar navbar-expand-lg">
        {{-- Search overlay (toggle via #search) --}}
        <div class="search-box" id="top-search-box" aria-hidden="true">
            <button class="dismiss" aria-label="Close search"><i class="icon-close"></i></button>
            <form id="searchForm" action="#" role="search">
                <label for="top-search-input" class="sr-only">Search</label>
                <input id="top-search-input" type="search" placeholder="Search tickets, users, messages..." class="form-control">
            </form>
        </div>

        <div class="container-fluid">
            <div class="navbar-holder d-flex align-items-center justify-content-between w-100">
                <div class="navbar-header d-flex align-items-center">
                    <a href="{{ route('dashboard') }}" class="navbar-brand d-flex align-items-center" title="Skyeface Dashboard">
                        <img src="{{ asset('Buzbox/img/logo-white.png') }}" alt="Skyeface Logo" class="img-fluid" style="height:32px;">
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
                            <img src="{{ asset('Buzbox/admin/img/expand.png') }}" class="img-fluid" alt="Fullscreen" style="height:20px;">
                        </button>
                    </li>

                    <!-- Search toggle -->
                    <li class="nav-item d-flex align-items-center mr-2">
                        <button id="search" class="btn btn-link nav-link p-0" type="button" aria-expanded="false" aria-controls="top-search-box" aria-label="Open search">
                            <i class="icon-search" aria-hidden="true"></i>
                            <span class="sr-only">Search</span>
                        </button>
                    </li>

                    <!-- Notifications -->
                    <li class="nav-item dropdown mr-2">
                        <a id="notifications" class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" aria-label="Notifications">
                            <i class="fa fa-bell-o" aria-hidden="true"></i>
                            @php
                                $notificationCount = 0;
                                $notifications = collect();
                                try {
                                    $notificationCount = auth()->user()->unreadNotifications()->count() ?? 0;
                                    $notifications = auth()->user()->unreadNotifications()->latest()->take(5)->get();
                                } catch (\Exception $e) {
                                    // notifications table doesn't exist or query failed
                                }
                            @endphp
                            @if($notificationCount > 0)
                                <span class="badge badge-pill badge-danger">{{ $notificationCount }}</span>
                            @endif
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="notifications" role="menu">
                            @if($notifications->count() > 0)
                                @foreach($notifications as $notification)
                                    <a class="dropdown-item" href="#">
                                        <small class="text-muted">{{ $notification->data['message'] ?? 'New notification' }}</small>
                                    </a>
                                @endforeach
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-center text-primary" href="#">View all</a>
                            @else
                                <div class="px-3 py-2 text-muted small">No new notifications</div>
                            @endif
                        </div>
                    </li>

                    <!-- Messages -->
                    <li class="nav-item dropdown mr-2">
                        <a id="messages" class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" aria-label="Messages">
                            <i class="fa fa-envelope-o" aria-hidden="true"></i>
                            @php
                                $unreadMessageCount = 0;
                                $messages = collect();
                                try {
                                    $unreadMessageCount = \DB::table('messages')
                                        ->where('recipient_id', auth()->id())
                                        ->where('read_at', null)
                                        ->count() ?? 0;
                                    $messages = \DB::table('messages')
                                        ->where('recipient_id', auth()->id())
                                        ->where('read_at', null)
                                        ->orderBy('created_at', 'desc')
                                        ->take(5)
                                        ->get();
                                } catch (\Exception $e) {
                                    // messages table doesn't exist or query failed
                                }
                            @endphp
                            @if($unreadMessageCount > 0)
                                <span class="badge badge-pill badge-primary">{{ $unreadMessageCount }}</span>
                            @endif
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="messages" role="menu">
                            @if($messages->count() > 0)
                                @foreach($messages as $msg)
                                    <a class="dropdown-item d-flex align-items-center small" href="#">
                                        <i class="fa fa-envelope mr-2 text-primary"></i>
                                        <div>{{ Str::limit($msg->body ?? 'New message', 40) }}</div>
                                    </a>
                                @endforeach
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-center text-primary" href="#">View all messages</a>
                            @else
                                <div class="px-3 py-2 text-muted small">No new messages</div>
                            @endif
                        </div>
                    </li>

                    <!-- Profile -->
                    <li class="nav-item dropdown">
                        <a id="profile" class="nav-link d-flex align-items-center" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->fname . ' ' . auth()->user()->lname) }}&background=random&color=fff" alt="Profile" class="img-fluid rounded-circle" style="height:34px; width:34px; border: 2px solid #fff;">
                            <span class="d-none d-md-inline ml-2 text-white">{{ auth()->user()->fname }}</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right profile" aria-labelledby="profile" role="menu" style="width: 300px; box-shadow: 0 2px 8px rgba(0,0,0,0.15);">
                            <!-- Profile Header -->
                            <div class="dropdown-item d-flex align-items-center px-3 py-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 4px 4px 0 0;">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->fname . ' ' . auth()->user()->lname) }}&background=random&color=fff" alt="" class="rounded-circle mr-3" style="height:48px; width:48px; border: 2px solid #fff;">
                                <div class="text-white">
                                    <div class="font-weight-bold" style="font-size: 14px;">{{ auth()->user()->fname }} {{ auth()->user()->lname }}</div>
                                    <div class="small" style="opacity: 0.9;">{{ auth()->user()->email }}</div>
                                    @if(isset(auth()->user()->role))
                                        <div class="small badge badge-light mt-1">{{ ucfirst(auth()->user()->role ?? 'User') }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="dropdown-divider"></div>

                            <!-- Profile Menu Items -->
                            <a class="dropdown-item px-3 py-2" href="{{ route('profile.show') }}" style="font-size: 14px;">
                                <i class="fa fa-user text-primary mr-2"></i>
                                <span>My Profile</span>
                                <span class="float-right text-muted small"><i class="fa fa-chevron-right"></i></span>
                            </a>

                            <a class="dropdown-item px-3 py-2" href="{{ route('profile.show') }}" style="font-size: 14px;">
                                <i class="fa fa-cog text-warning mr-2"></i>
                                <span>Settings</span>
                                <span class="float-right text-muted small"><i class="fa fa-chevron-right"></i></span>
                            </a>

                            <a class="dropdown-item px-3 py-2" href="{{ route('profile.show') }}" style="font-size: 14px;">
                                <i class="fa fa-lock text-info mr-2"></i>
                                <span>Change Password</span>
                                <span class="float-right text-muted small"><i class="fa fa-chevron-right"></i></span>
                            </a>

                            <div class="dropdown-divider"></div>

                            <!-- Logout -->
                            <a class="dropdown-item px-3 py-2 text-danger" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" style="font-size: 14px;">
                                <i class="fa fa-power-off mr-2"></i>
                                <span>Logout</span>
                            </a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>

<script>
    // Search toggle
    document.addEventListener('click', function (e) {
        var searchToggle = document.getElementById('search');
        var searchBox = document.getElementById('top-search-box');
        if (!searchToggle) return;
        if (e.target.closest && e.target.closest('#search')) {
            searchBox.setAttribute('aria-hidden', 'false');
            searchBox.classList.add('open');
            document.getElementById('top-search-input').focus();
        }
        if (e.target.closest && e.target.closest('.dismiss')) {
            searchBox.setAttribute('aria-hidden', 'true');
            searchBox.classList.remove('open');
        }
    });

    // Dropdown menus - click to toggle
    document.addEventListener('DOMContentLoaded', function() {
        const dropdownToggles = document.querySelectorAll('[data-toggle="dropdown"]');

        dropdownToggles.forEach(toggle => {
            toggle.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                const dropdownMenu = this.parentElement.querySelector('.dropdown-menu');

                if (!dropdownMenu) return;

                // Close all other dropdowns
                document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
                    if (menu !== dropdownMenu) {
                        menu.classList.remove('show');
                    }
                });

                // Toggle current dropdown
                dropdownMenu.classList.toggle('show');
            });
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('[data-toggle="dropdown"]') && !e.target.closest('.dropdown-menu')) {
                document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
                    menu.classList.remove('show');
                });
            }
        });

        // Allow clicks on dropdown items to work
        document.querySelectorAll('.dropdown-menu .dropdown-item').forEach(item => {
            item.addEventListener('click', function(e) {
                // Don't prevent default - let links work normally
                const href = this.getAttribute('href');
                if (href && href !== '#') {
                    // Close the menu after clicking a link
                    const menu = this.closest('.dropdown-menu');
                    if (menu) {
                        menu.classList.remove('show');
                    }
                }
            });
        });
    });
</script>

<style>
    /* Ensure dropdowns are visible above other content */
    .navbar .dropdown-menu {
        position: absolute !important;
        top: 100% !important;
        z-index: 1050 !important;
        min-width: 250px !important;
        display: none !important;
        pointer-events: none !important;
    }

    .navbar .dropdown-menu.show {
        display: block !important;
        pointer-events: auto !important;
    }

    /* Ensure profile dropdown is visible and clickable */
    .nav-item.dropdown .dropdown-menu {
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 4px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.2);
        padding: 0 !important;
        margin-top: 5px;
    }

    /* Make dropdown items clickable */
    .navbar .dropdown-menu .dropdown-item {
        pointer-events: auto !important;
        cursor: pointer !important;
        padding: 10px 15px !important;
        display: block !important;
        color: #333 !important;
        text-decoration: none !important;
        transition: background-color 0.2s ease;
    }

    .navbar .dropdown-menu .dropdown-item:hover {
        background-color: #f5f5f5 !important;
        color: #000 !important;
    }

    .navbar .dropdown-menu .dropdown-divider {
        margin: 0 !important;
        pointer-events: none !important;
    }
</style>

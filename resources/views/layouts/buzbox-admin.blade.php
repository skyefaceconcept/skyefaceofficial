<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">
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
</head>
<body>
    <!-- Header -->
    <header class="header">
        <nav class="navbar navbar-expand-lg">
            <div class="search-box">
                <button class="dismiss"><i class="icon-close"></i></button>
                <form id="searchForm" action="#" role="search">
                    <input type="search" placeholder="Search Now" class="form-control">
                </form>
            </div>
            <div class="container-fluid">
                <div class="navbar-holder d-flex align-items-center justify-content-between">
                    <div class="navbar-header">
                        <a href="{{ route('dashboard') }}" class="navbar-brand">
                            <div class="brand-text brand-big hidden-lg-down">
                                <img src="{{ asset('Buzbox/img/logo-white.png') }}" alt="Logo" class="img-fluid">
                            </div>
                            <div class="brand-text brand-small">
                                <img src="{{ asset('Buzbox/img/logo-icon.png') }}" alt="Logo" class="img-fluid">
                            </div>
                        </a>
                        <a id="toggle-btn" href="#" class="menu-btn active">
                            <span></span>
                            <span></span>
                            <span></span>
                        </a>
                    </div>
                </div>
                <ul class="nav-menu list-unstyled d-flex flex-md-row align-items-md-center">
                    <!-- Expand-->
                    <li class="nav-item d-flex align-items-center full_scr_exp">
                        <a class="nav-link" href="#"><img src="{{ asset('Buzbox/admin/img/expand.png') }}" onclick="toggleFullScreen(document.body)" class="img-fluid" alt=""></a>
                    </li>
                    <!-- Search-->
                    <li class="nav-item d-flex align-items-center">
                        <a id="search" class="nav-link" href="#"><i class="icon-search"></i></a>
                    </li>
                    <!-- Notifications-->
                    <li class="nav-item dropdown">
                        <a id="notifications" class="nav-link" rel="nofollow" data-target="#" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-bell-o"></i>
                            <span class="badge">0</span>
                        </a>
                        <ul aria-labelledby="notifications" class="dropdown-menu">
                            <li>
                                <a rel="nofollow" href="#" class="dropdown-item nav-link">
                                    <div class="notification">
                                        <div class="notification-content"><i class="fa fa-envelope bg-red"></i> No new notifications</div>
                                        <div class="notification-time"><small>Just now</small></div>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <!-- Messages-->
                    <li class="nav-item dropdown">
                        <a id="messages" class="nav-link logout" rel="nofollow" data-target="#" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-envelope-o"></i>
                            <span class="badge">0</span>
                        </a>
                        <ul aria-labelledby="messages" class="dropdown-menu">
                            <li>
                                <a rel="nofollow" href="#" class="dropdown-item d-flex">
                                    <div class="msg-profile">
                                        <img src="https://ui-avatars.com/api/?name={{ auth()->user()->fname }}+{{ auth()->user()->lname }}&background=random" alt="..." class="img-fluid rounded-circle">
                                    </div>
                                    <div class="msg-body">
                                        <h3 class="h5 msg-nav-h3">{{ auth()->user()->fname }} {{ auth()->user()->lname }}</h3>
                                        <span>No new messages</span>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <!-- Profile -->
                    <li class="nav-item dropdown">
                        <a id="profile" class="nav-link logout" data-target="#" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img src="https://ui-avatars.com/api/?name={{ auth()->user()->fname }}+{{ auth()->user()->lname }}&background=random" alt="..." class="img-fluid rounded-circle" style="height: 30px; width: 30px;">
                        </a>
                        <ul aria-labelledby="profile" class="dropdown-menu profile">
                            <li>
                                <a rel="nofollow" href="#" class="dropdown-item d-flex">
                                    <div class="msg-profile">
                                        <img src="https://ui-avatars.com/api/?name={{ auth()->user()->fname }}+{{ auth()->user()->lname }}&background=random" alt="..." class="img-fluid rounded-circle">
                                    </div>
                                    <div class="msg-body">
                                        <h3 class="h5">{{ auth()->user()->fname }} {{ auth()->user()->lname }}</h3>
                                        <span>{{ auth()->user()->email }}</span>
                                    </div>
                                </a>
                                <hr>
                            </li>
                            <li>
                                <a rel="nofollow" href="{{ route('profile.show') }}" class="dropdown-item">
                                    <div class="notification">
                                        <div class="notification-content"><i class="fa fa-user"></i> My Profile</div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a rel="nofollow" href="{{ route('profile.show') }}" class="dropdown-item">
                                    <div class="notification">
                                        <div class="notification-content"><i class="fa fa-cog"></i> Settings</div>
                                    </div>
                                </a>
                                <hr>
                            </li>
                            <li>
                                <a rel="nofollow" href="#" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <div class="notification">
                                        <div class="notification-content"><i class="fa fa-power-off"></i> Logout</div>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <!--====================================================
                            PAGE CONTENT
    ======================================================-->
    <div class="page-content d-flex align-items-stretch">

        <!--***** SIDE NAVBAR *****-->
        <nav class="side-navbar">
            <div class="sidebar-header d-flex align-items-center">
                <div class="avatar">
                    <img src="https://ui-avatars.com/api/?name={{ auth()->user()->fname }}+{{ auth()->user()->lname }}&background=random" alt="User Avatar" class="img-fluid rounded-circle">
                </div>
                <div class="title">
                    <h1 class="h4">{{ auth()->user()->fname }} {{ auth()->user()->lname }}</h1>
                </div>
            </div>
            <hr>
            <!-- Sidebar Navigation Menus-->
            <ul class="list-unstyled">
                <li class="active">
                    <a href="{{ route('dashboard') }}">
                        <i class="icon-home"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="#tickets" aria-expanded="false" data-toggle="collapse">
                        <i class="fa fa-ticket"></i> Support Tickets
                    </a>
                    <ul id="tickets" class="collapse list-unstyled">
                        <li><a href="{{ route('dashboard') }}#open">Open Tickets</a></li>
                        <li><a href="{{ route('dashboard') }}#pending">Pending Reply</a></li>
                        <li><a href="{{ route('dashboard') }}#resolved">Resolved Tickets</a></li>
                    </ul>
                </li>
                <li>
                    <a href="{{ route('profile.show') }}">
                        <i class="fa fa-user-circle"></i> My Profile
                    </a>
                </li>
                <li>
                    <a href="{{ route('profile.show') }}">
                        <i class="fa fa-lock"></i> Change Password
                    </a>
                </li>
            </ul>
            <span class="heading">Account</span>
            <ul class="list-unstyled">
                <li>
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fa fa-sign-out"></i> Logout
                    </a>
                </li>
            </ul>
        </nav>

        <!--***** CONTENT INNER *****-->
        <div class="content-inner">
            @yield('content')

            <!--***** FOOTER *****-->
            <div class="row" id="footer-section">
                <div class="col-md-3">
                    <div class="card text-center social-bottom sb-fb">
                        <i class="fa fa-envelope"></i>
                        <div>{{ auth()->user()->email }}</div>
                        <p>Email</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center social-bottom sb-tw">
                        <i class="fa fa-user"></i>
                        <div>{{ auth()->user()->fname }} {{ auth()->user()->lname }}</div>
                        <p>Name</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center social-bottom sb-gp">
                        <i class="fa fa-calendar"></i>
                        <div>{{ auth()->user()->created_at->format('M d, Y') }}</div>
                        <p>Member Since</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center social-bottom sb-in">
                        <i class="fa fa-check-circle"></i>
                        <div>Active</div>
                        <p>Status</p>
                    </div>
                </div>
            </div>
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
</body>
</html>

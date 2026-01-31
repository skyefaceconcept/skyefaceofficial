<!-- Sidebar Navigation -->
<nav class="side-navbar">
    <div class="sidebar-header d-flex align-items-center">
        <div class="avatar">
            <img src="https://ui-avatars.com/api/?name={{ auth()->user()->fname }}+{{ auth()->user()->lname }}&background=random" alt="User Avatar" class="img-fluid rounded-circle">
        </div>
        <div class="title">
            <h1 class="h4">{{ auth()->user()->fname }} {{ auth()->user()->lname }}</h1>
            <p>Client</p>
        </div>
    </div>
    <hr>

    <!-- Sidebar Navigation Menus-->
    <ul class="list-unstyled">
        <!-- Dashboard Link -->
        <li class="active">
            <a href="{{ route('dashboard') }}">
                <i class="icon-home"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <!-- Support Tickets Menu -->
        <li>
            <a href="#tickets" aria-expanded="false" data-toggle="collapse">
                <i class="fa fa-ticket"></i>
                <span>Support Tickets</span>
            </a>
            <ul id="tickets" class="collapse list-unstyled">
                <li>
                    <a href="{{ route('dashboard') }}">
                        <span>View All Tickets</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('dashboard') }}#open">
                        <span>Open Tickets</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('dashboard') }}#pending">
                        <span>Pending Reply</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('dashboard') }}#resolved">
                        <span>Resolved Tickets</span>
                    </a>
                </li>
            </ul>
        </li>

        <!-- Profile Link -->
        <li>
            <a href="{{ route('profile.show') }}">
                <i class="fa fa-user-circle"></i>
                <span>My Profile</span>
            </a>
        </li>

        <!-- Change Password Link -->
        <li>
            <a href="{{ route('profile.show') }}">
                <i class="fa fa-lock"></i>
                <span>Change Password</span>
            </a>
        </li>
    </ul>

    <!-- Account Section -->
    <span class="heading">Account</span>
    <ul class="list-unstyled">
        <li>
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fa fa-sign-out"></i>
                <span>Logout</span>
            </a>
        </li>
    </ul>
</nav>

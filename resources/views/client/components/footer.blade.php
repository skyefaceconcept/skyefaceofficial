<!-- Client Footer -->
<footer class="footer">
    <div class="footer-content">
        <div class="container-fluid">
            <div class="row">
                @auth
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="fa fa-envelope" style="font-size: 24px; color: #2196F3;"></i>
                            <h6 class="mt-2 mb-1">Email</h6>
                            <p class="text-muted">{{ auth()->user()->email }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="fa fa-user" style="font-size: 24px; color: #4CAF50;"></i>
                            <h6 class="mt-2 mb-1">Full Name</h6>
                            <p class="text-muted">{{ auth()->user()->fname ?? auth()->user()->name }} {{ auth()->user()->lname }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="fa fa-calendar" style="font-size: 24px; color: #FF9800;"></i>
                            <h6 class="mt-2 mb-1">Member Since</h6>
                            <p class="text-muted">{{ auth()->user()->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="fa fa-check-circle" style="font-size: 24px; color: #4CAF50;"></i>
                            <h6 class="mt-2 mb-1">Status</h6>
                            <p class="text-muted">Active</p>
                        </div>
                    </div>
                </div>
                @endauth
            </div>
        </div>
    </div>

    <div class="footer-bottom bg-light border-top py-3 mt-4">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0 text-muted small">
                        &copy; {{ date('Y') }} {{ config('company.name', 'Skyeface') }}. All rights reserved.
                    </p>
                </div>
                <div class="col-md-6 text-right">
                    <a href="{{ route('home') }}" class="text-muted small ml-3">Home</a>
                    <a href="{{ route('home') }}#contact" class="text-muted small ml-3">Contact</a>
                    <a href="{{ route('profile.show') }}" class="text-muted small ml-3">Profile Settings</a>
                </div>
            </div>
        </div>
    </div>
</footer>

<style>
.footer {
    background: #fff;
    border-top: 1px solid #e9e9e9;
    padding: 40px 0 0;
    margin-top: 40px;
}

.footer-content {
    padding: 30px 0;
}

.footer .card {
    border: 1px solid #e9e9e9;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.footer .card:hover {
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    transform: translateY(-2px);
}

.footer-bottom {
    font-size: 0.9rem;
}

.footer-bottom a {
    text-decoration: none;
    transition: color 0.3s ease;
}

.footer-bottom a:hover {
    color: #2196F3 !important;
}

@media (max-width: 768px) {
    .footer-bottom .row {
        flex-direction: column;
        text-align: center;
    }

    .footer-bottom .col-md-6 {
        margin-bottom: 10px;
    }

    .footer-bottom .text-right {
        text-align: center !important;
    }

    .footer-bottom a {
        display: inline-block;
        margin: 5px 0;
    }
}
</style>

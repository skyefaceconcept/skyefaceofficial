@extends('layouts.app-buzbox')

@use('Illuminate\Support\Facades\Storage')

@section('title', 'My Profile')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h2 class="mb-1"><i class="fa fa-user mr-2"></i>My Profile</h2>
                    <p class="text-muted mb-0">Manage your account settings and preferences</p>
                </div>
                <div class="alert alert-info mb-0">
                    <strong>Last updated:</strong> {{ auth()->user()->updated_at->diffForHumans() }}
                </div>
            </div>
        </div>
    </div>

    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fa fa-check-circle mr-2"></i>{{ session('status') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <!-- Main Content Column -->
        <div class="col-lg-8">
            <!-- Profile Photo Section -->
            <div class="card mb-4 shadow-sm" id="profilePhotoSection">
                <div class="card-header bg-light border-bottom">
                    <h5 class="card-title mb-0"><i class="fa fa-image mr-2 text-info"></i>Profile Photo</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        @if(auth()->user()->profile_photo_path && Storage::disk('public')->exists(auth()->user()->profile_photo_path))
                            <img id="photoPreview" src="{{ asset('storage/' . auth()->user()->profile_photo_path) }}" alt="Profile" class="img-fluid rounded-3" style="max-width: 250px; max-height: 250px; object-fit: cover; border: 4px solid #f0f0f0;">
                        @else
                            <div id="photoPreview" style="width: 250px; height: 250px; margin: 0 auto; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; border: 4px solid #f0f0f0;">
                                <i class="fa fa-user" style="font-size: 100px; color: white;"></i>
                            </div>
                        @endif
                    </div>
                    <div class="alert alert-info small mb-0">
                        <strong><i class="fa fa-lightbulb-o mr-1"></i>Tip:</strong> You can upload a new profile photo in the "Profile Information" section below. The new photo will be updated immediately when you save.
                    </div>
                </div>
            </div>

            <!-- Update Profile Information -->
            @if (Laravel\Fortify\Features::canUpdateProfileInformation())
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-light border-bottom">
                        <h5 class="card-title mb-0"><i class="fa fa-edit mr-2 text-primary"></i>Profile Information</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <!-- First Name -->
                            <div class="form-group">
                                <label for="fname" class="font-weight-bold"><i class="fa fa-user mr-1 text-primary"></i>First Name</label>
                                <input type="text" id="fname" name="fname" class="form-control @error('fname') is-invalid @enderror" value="{{ old('fname', auth()->user()->fname) }}" placeholder="Enter first name">
                                @error('fname')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Middle Name -->
                            <div class="form-group">
                                <label for="mname" class="font-weight-bold"><i class="fa fa-user mr-1 text-primary"></i>Middle Name</label>
                                <input type="text" id="mname" name="mname" class="form-control @error('mname') is-invalid @enderror" value="{{ old('mname', auth()->user()->mname) }}" placeholder="Enter middle name (optional)">
                                @error('mname')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Last Name -->
                            <div class="form-group">
                                <label for="lname" class="font-weight-bold"><i class="fa fa-user mr-1 text-primary"></i>Last Name</label>
                                <input type="text" id="lname" name="lname" class="form-control @error('lname') is-invalid @enderror" value="{{ old('lname', auth()->user()->lname) }}" placeholder="Enter last name">
                                @error('lname')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="form-group">
                                <label for="email" class="font-weight-bold"><i class="fa fa-envelope mr-1 text-primary"></i>Email Address</label>
                                <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', auth()->user()->email) }}" placeholder="Enter email address">
                                @error('email')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Phone -->
                            <div class="form-group">
                                <label for="phone" class="font-weight-bold"><i class="fa fa-phone mr-1 text-primary"></i>Phone Number</label>
                                <input type="tel" id="phone" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', auth()->user()->phone) }}" placeholder="Enter phone number">
                                @error('phone')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Date of Birth -->
                            <div class="form-group">
                                <label for="dob" class="font-weight-bold"><i class="fa fa-calendar mr-1 text-primary"></i>Date of Birth</label>
                                <input type="date" id="dob" name="dob" class="form-control @error('dob') is-invalid @enderror" value="{{ old('dob', auth()->user()->dob) }}">
                                @error('dob')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Profile Photo -->
                            <div class="form-group">
                                <label for="profile_photo_path" class="font-weight-bold"><i class="fa fa-image mr-1 text-primary"></i>Profile Photo</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input @error('profile_photo_path') is-invalid @enderror" id="profile_photo_path" name="profile_photo_path" accept="image/*">
                                    <label class="custom-file-label" for="profile_photo_path">Choose file...</label>
                                </div>
                                <small class="form-text text-muted">Accepted formats: JPG, PNG, GIF. Max size: 2MB</small>
                                @error('profile_photo_path')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fa fa-save mr-2"></i>Save Profile Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif

            <!-- Update Password -->
            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-light border-bottom">
                        <h5 class="card-title mb-0"><i class="fa fa-lock mr-2 text-warning"></i>Update Password</h5>
                    </div>
                    <div class="card-body">
                        @livewire('profile.update-password-form')
                    </div>
                </div>
            @endif

            <!-- Two Factor Authentication -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0"><i class="fa fa-shield mr-2 text-success"></i>Two-Factor Authentication</h5>
                        @if(auth()->user()->two_factor_secret)
                            <span class="badge badge-success"><i class="fa fa-check-circle mr-1"></i>Enabled</span>
                        @else
                            <span class="badge badge-secondary"><i class="fa fa-times-circle mr-1"></i>Disabled</span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    @if(auth()->user()->two_factor_secret)
                        <!-- 2FA is Enabled -->
                        <div class="alert alert-success mb-3">
                            <i class="fa fa-check-circle mr-2"></i>
                            <strong>Two-Factor Authentication is Active</strong>
                            <p class="mb-0 small mt-2">Your account is protected with two-factor authentication.</p>
                        </div>
                        <form action="{{ route('profile.2fa.disable') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-block" onclick="return confirm('Are you sure you want to disable 2FA?')">
                                <i class="fa fa-times mr-2"></i>Disable Two-Factor Authentication
                            </button>
                        </form>
                    @else
                        <!-- 2FA is Disabled -->
                        <div class="alert alert-warning mb-3">
                            <i class="fa fa-exclamation-circle mr-2"></i>
                            <strong>Two-Factor Authentication is Inactive</strong>
                            <p class="mb-0 small mt-2">Enhance your account security by enabling two-factor authentication.</p>
                        </div>
                        <button type="button" id="enableTwoFactorBtn" class="btn btn-success btn-block" onclick="window.openModal();">
                            <i class="fa fa-check mr-2"></i>Enable Two-Factor Authentication
                        </button>
                    @endif
                </div>
            </div>

            <!-- Browser Sessions -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h5 class="card-title mb-0"><i class="fa fa-devices mr-2 text-info"></i>Browser Sessions</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-3"><i class="fa fa-info-circle mr-1"></i>Manage your active sessions across different devices and browsers.</p>
                    
                    @php
                        $sessions = \Illuminate\Support\Facades\DB::table('sessions')
                            ->where('user_id', auth()->id())
                            ->where('last_activity', '>=', now()->subHours(24)->timestamp)
                            ->orderBy('last_activity', 'desc')
                            ->get();
                    @endphp

                    @if($sessions->count() > 0)
                        <div class="list-group mb-3">
                            @foreach($sessions as $session)
                                @php
                                    $payload = json_decode($session->payload, true);
                                    $lastActivity = \Carbon\Carbon::createFromTimestamp($session->last_activity);
                                    $isCurrent = $session->id === request()->getSession()->getId();
                                @endphp
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">
                                                <i class="fa fa-laptop mr-2 text-primary"></i>
                                                {{ $payload['_previous']['browser'] ?? 'Unknown Browser' }}
                                            </h6>
                                            <small class="text-muted d-block">
                                                <i class="fa fa-clock-o mr-1"></i>
                                                Last active: {{ $lastActivity->diffForHumans() }}
                                            </small>
                                            <small class="text-muted d-block">
                                                <i class="fa fa-map-marker mr-1"></i>
                                                IP: {{ $session->ip_address ?? 'Unknown' }}
                                            </small>
                                            @if($isCurrent)
                                                <span class="badge badge-success mt-2">
                                                    <i class="fa fa-check-circle mr-1"></i>Current Session
                                                </span>
                                            @endif
                                        </div>
                                        @if(!$isCurrent)
                                            <form action="{{ route('profile.logout-session', $session->id) }}" method="POST" style="margin: 0;">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Logout this session">
                                                    <i class="fa fa-sign-out"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <form action="{{ route('profile.logout-other-sessions') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-warning btn-block" onclick="return confirm('Logout all other sessions?')">
                            <i class="fa fa-sign-out mr-2"></i>Logout All Other Sessions
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Account Summary Card -->
            <div class="card shadow-sm sticky-top" style="top: 20px;">
                <div class="card-header bg-primary text-white border-bottom-0">
                    <h5 class="card-title mb-0"><i class="fa fa-user-circle mr-2"></i>Account Summary</h5>
                </div>
                <div class="card-body text-center">
                    <!-- Profile Photo -->
                    <div class="mb-4">
                        @if(auth()->user()->profile_photo_path && Storage::disk('public')->exists(auth()->user()->profile_photo_path))
                            <img src="{{ asset('storage/' . auth()->user()->profile_photo_path) }}" alt="Profile" class="img-fluid rounded-circle" style="width: 120px; height: 120px; object-fit: cover; border: 4px solid #e0e0e0;">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->fname . ' ' . auth()->user()->lname) }}&background=667eea&color=fff&size=128&bold=true" alt="Profile" class="img-fluid rounded-circle" style="width: 120px; height: 120px; border: 4px solid #e0e0e0;">
                        @endif
                    </div>

                    <!-- User Name and Email -->
                    <h5 class="card-title font-weight-bold">{{ auth()->user()->fname ?? auth()->user()->name }} {{ auth()->user()->lname }}</h5>
                    <p class="text-muted small mb-4">{{ auth()->user()->email }}</p>

                    <!-- Edit Photo Button -->
                    <a href="#profilePhotoSection" class="btn btn-sm btn-outline-primary mb-3 btn-block">
                        <i class="fa fa-camera mr-1"></i>Edit Photo
                    </a>

                    <hr>

                    <!-- Account Information -->
                    <div class="text-left">
                        <div class="mb-3">
                            <small class="text-muted d-block"><i class="fa fa-badge mr-1"></i>Account Status</small>
                            <span class="badge badge-success"><i class="fa fa-check-circle mr-1"></i>Active</span>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted d-block"><i class="fa fa-shield mr-1"></i>Security</small>
                            @if(auth()->user()->two_factor_secret)
                                <span class="badge badge-success"><i class="fa fa-lock mr-1"></i>2FA Enabled</span>
                            @else
                                <span class="badge badge-warning"><i class="fa fa-unlock mr-1"></i>2FA Disabled</span>
                            @endif
                        </div>

                        <div class="mb-3">
                            <small class="text-muted d-block"><i class="fa fa-envelope mr-1"></i>Email Verified</small>
                            @if(auth()->user()->email_verified_at)
                                <span class="badge badge-success"><i class="fa fa-check-circle mr-1"></i>Yes</span>
                            @else
                                <span class="badge badge-warning"><i class="fa fa-exclamation-circle mr-1"></i>Pending</span>
                            @endif
                        </div>

                        <div class="mb-3">
                            <small class="text-muted d-block"><i class="fa fa-calendar mr-1"></i>Member Since</small>
                            <strong class="text-dark">{{ auth()->user()->created_at->format('M d, Y') }}</strong>
                        </div>

                        @if(auth()->user()->phone)
                            <div class="mb-3">
                                <small class="text-muted d-block"><i class="fa fa-phone mr-1"></i>Phone</small>
                                <strong class="text-dark">{{ auth()->user()->phone }}</strong>
                            </div>
                        @endif

                        @if(auth()->user()->dob)
                            <div class="mb-3">
                                <small class="text-muted d-block"><i class="fa fa-birthday-cake mr-1"></i>Date of Birth</small>
                                <strong class="text-dark">{{ \Carbon\Carbon::parse(auth()->user()->dob)->format('M d, Y') }}</strong>
                            </div>
                        @endif

                        <div class="mb-0">
                            <small class="text-muted d-block"><i class="fa fa-clock-o mr-1"></i>Last Updated</small>
                            <strong class="text-dark">{{ auth()->user()->updated_at->diffForHumans() }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="card shadow-sm mt-3">
                <div class="card-header bg-light border-bottom">
                    <h6 class="card-title mb-0"><i class="fa fa-bolt mr-2 text-warning"></i>Quick Actions</h6>
                </div>
                <div class="card-body">
                    <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-primary btn-block mb-2">
                        <i class="fa fa-home mr-1"></i>Back to Dashboard
                    </a>
                    <a href="{{ route('tickets.show', 'help') }}" class="btn btn-sm btn-outline-info btn-block mb-2">
                        <i class="fa fa-life-ring mr-1"></i>Get Support
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline w-100">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-danger btn-block">
                            <i class="fa fa-sign-out mr-1"></i>Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Enable 2FA Modal -->
    <div class="modal fade" id="enableTwoFactorModal" tabindex="-1" role="dialog" aria-labelledby="enableTwoFactorLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="enableTwoFactorLabel"><i class="fa fa-shield mr-2"></i>Enable Two-Factor Authentication</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" onclick="window.closeModal();">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                    <h6 class="font-weight-bold mb-3">Step 1: Scan this QR code with your authenticator app</h6>
                    <p class="text-muted small mb-3">Use an authenticator app like Google Authenticator, Microsoft Authenticator, or Authy to scan this QR code:</p>
                    
                    @php
                        $secret = $twoFactorSecret ?? \Illuminate\Support\Str::random(32);
                    @endphp

                    <div class="text-center mb-4">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data={{ urlencode('otpauth://totp/Skyeface:' . auth()->user()->email . '?secret=' . $secret . '&issuer=Skyeface') }}" alt="QR Code" class="img-fluid" style="max-width: 250px; border: 2px solid #e0e0e0; padding: 10px; border-radius: 8px;">
                    </div>

                    <div class="alert alert-info small mb-3">
                        <strong>Can't scan?</strong> Enter this code manually in your authenticator app:<br>
                        <code style="font-size: 14px; word-break: break-all;">{{ $secret }}</code>
                    </div>

                    <hr>

                    <h6 class="font-weight-bold mb-3">Step 2: Enter the 6-digit code from your authenticator app</h6>
                    <form action="{{ route('profile.2fa.enable') }}" method="POST" id="enableTwoFactorForm">
                        @csrf
                        <div class="form-group">
                            <input type="hidden" name="secret" value="{{ $secret }}">
                            <label for="twoFactorCode" class="font-weight-bold">Verification Code</label>
                            <input type="text" id="twoFactorCode" name="code" class="form-control form-control-lg text-center" placeholder="000000" maxlength="6" pattern="\d{6}" inputmode="numeric" required autofocus>
                            <small class="form-text text-muted">Enter the 6-digit code from your authenticator app</small>
                            @error('code')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>

                        <hr>

                        <h6 class="font-weight-bold mb-3">Recovery Codes</h6>
                        <p class="text-muted small mb-3">Save these recovery codes in a safe place. You can use them to access your account if you lose your authenticator device:</p>
                        
                        <div class="alert alert-light border" id="recoveryCodes" style="background-color: #f8f9fa; padding: 15px; border-radius: 4px;">
                            <div class="row" style="font-size: 14px; font-family: monospace;">
                                @foreach($recoveryCodes ?? [] as $code)
                                    <div class="col-6 mb-2">{{ $code }}</div>
                                @endforeach
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="button" class="btn btn-sm btn-outline-secondary btn-block mb-2" onclick="copyRecoveryCodes()">
                                <i class="fa fa-copy mr-1"></i>Copy Recovery Codes
                            </button>
                        </div>

                        <div class="alert alert-warning small mb-3">
                            <strong><i class="fa fa-exclamation-triangle mr-1"></i>Important:</strong> Keep your recovery codes safe. Each code can only be used once.
                        </div>

                        <div class="form-group">
                            <label class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="confirm" value="1" required>
                                <span class="custom-control-label">I have saved my recovery codes in a safe place</span>
                            </label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="window.closeModal();">Cancel</button>
                    <button type="submit" form="enableTwoFactorForm" class="btn btn-success">
                        <i class="fa fa-check mr-1"></i>Enable 2FA
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        border: 1px solid #e0e0e0;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        transition: box-shadow 0.3s ease, transform 0.3s ease;
    }

    .card:hover {
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }

    .card.shadow-sm {
        box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
    }

    .card-header {
        border-bottom: 1px solid #e0e0e0;
        background-color: #f8f9fa !important;
    }

    .card-header h5,
    .card-header h6 {
        font-size: 15px;
        font-weight: 600;
        margin-bottom: 0;
    }

    .card-header.bg-primary {
        background-color: #007bff !important;
    }

    .card-header.bg-danger {
        background-color: #dc3545 !important;
    }

    .rounded-3 {
        border-radius: 0.5rem;
    }

    .btn-outline-primary:hover,
    .btn-outline-info:hover,
    .btn-outline-danger:hover {
        opacity: 0.9;
    }

    .sticky-top {
        position: sticky;
        top: 20px;
        z-index: 10;
    }

    @media (max-width: 768px) {
        .sticky-top {
            position: static;
        }
    }

    .badge {
        padding: 0.5rem 0.75rem;
        font-size: 12px;
    }

    .list-group-item {
        padding-left: 0 !important;
        padding-right: 0 !important;
    }

    .alert-info {
        background-color: #e7f3ff;
        border-color: #b3d9ff;
        color: #004085;
    }

    .alert-success {
        background-color: #d4edda;
        border-color: #c3e6cb;
        color: #155724;
    }

    .alert-danger {
        background-color: #f8d7da;
        border-color: #f5c6cb;
        color: #721c24;
    }

    h2 {
        color: #333;
        font-weight: 600;
    }

    .text-muted {
        color: #6c757d;
    }

    .list-group-item {
        border: 1px solid #e0e0e0;
        margin-bottom: 10px;
        border-radius: 4px;
    }
</style>

<script>
// Simple global function to open the 2FA modal
window.openModal = function() {
    console.log('openModal called');
    const modal = document.getElementById('enableTwoFactorModal');
    
    if (!modal) {
        console.error('Modal not found!');
        return false;
    }
    
    console.log('Modal found, attempting to open...');
    
    // Try Bootstrap modal first
    if (typeof jQuery !== 'undefined' && jQuery.fn.modal) {
        console.log('Using jQuery.modal()');
        jQuery(modal).modal('show');
    } else if (typeof $ !== 'undefined' && $.fn.modal) {
        console.log('Using $.modal()');
        $(modal).modal('show');
    } else {
        console.log('jQuery modal not available, using CSS approach');
        // Fallback: manual CSS
        modal.classList.add('show');
        modal.style.display = 'block';
        modal.style.overflow = 'visible';
        document.body.classList.add('modal-open');
        document.body.style.overflow = 'hidden';
        document.body.style.paddingRight = '0px';
        
        // Add backdrop
        let backdrop = document.getElementById('modal-backdrop-2fa');
        if (!backdrop) {
            backdrop = document.createElement('div');
            backdrop.id = 'modal-backdrop-2fa';
            backdrop.className = 'modal-backdrop fade show';
            document.body.appendChild(backdrop);
        }
    }
    
    return false;
};

// Simple global function to close the 2FA modal
window.closeModal = function() {
    console.log('closeModal called');
    const modal = document.getElementById('enableTwoFactorModal');
    
    if (!modal) {
        console.error('Modal not found!');
        return false;
    }
    
    // Try Bootstrap modal first
    if (typeof jQuery !== 'undefined' && jQuery.fn.modal) {
        console.log('Closing modal with jQuery');
        jQuery(modal).modal('hide');
    } else if (typeof $ !== 'undefined' && $.fn.modal) {
        console.log('Closing modal with $');
        $(modal).modal('hide');
    } else {
        console.log('jQuery modal not available, using CSS approach');
        // Fallback: manual CSS
        modal.classList.remove('show');
        modal.style.display = 'none';
        document.body.classList.remove('modal-open');
        document.body.style.overflow = 'auto';
        document.body.style.paddingRight = '0px';
        
        // Remove backdrop
        const backdrop = document.getElementById('modal-backdrop-2fa');
        if (backdrop) {
            backdrop.remove();
        }
    }
    
    return false;
};

function copyRecoveryCodes() {
    const element = document.getElementById('recoveryCodes');
    if (!element) {
        alert('Recovery codes element not found');
        return;
    }
    const text = element.innerText;
    navigator.clipboard.writeText(text).then(() => {
        alert('Recovery codes copied to clipboard!');
    }).catch(() => {
        alert('Failed to copy codes. Please copy manually.');
    });
}

// Ensure functions are globally accessible
window.copyRecoveryCodes = copyRecoveryCodes;
</script>

@endsection

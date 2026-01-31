@extends('layouts.app-buzbox')

@use('Illuminate\Support\Facades\Storage')

@section('title', 'My Profile')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <h2 class="mb-0"><i class="fa fa-user mr-2"></i>My Profile</h2>
            <p class="text-muted small">Manage your account settings and preferences</p>
        </div>
    </div>

    <div class="row">
        <!-- Profile Information Column -->
        <div class="col-lg-8">
            <!-- Profile Photo Section -->
            <div class="card mb-3" id="profilePhotoSection">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0"><i class="fa fa-image mr-2 text-info"></i>Profile Photo</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        @if(auth()->user()->profile_photo_path && Storage::disk('public')->exists(auth()->user()->profile_photo_path))
                            <img src="{{ asset('storage/' . auth()->user()->profile_photo_path) }}" alt="Profile" class="img-fluid rounded" style="max-width: 250px; max-height: 250px; object-fit: cover;">
                        @else
                            <div style="width: 250px; height: 250px; margin: 0 auto; background: #f0f0f0; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                <i class="fa fa-image" style="font-size: 80px; color: #ccc;"></i>
                            </div>
                        @endif
                    </div>
                    <div class="alert alert-info small">
                        <strong>Tip:</strong> You can upload a new profile photo in the "Profile Information" section below. Photo will be updated when you save your profile.
                    </div>
                </div>
            </div>

            <!-- Update Profile Information -->
            @if (Laravel\Fortify\Features::canUpdateProfileInformation())
                <div class="card mb-3">
                    <div class="card-header bg-light">
                        <h5 class="card-title mb-0"><i class="fa fa-edit mr-2 text-primary"></i>Profile Information</h5>
                    </div>
                    <div class="card-body">
                        @livewire('profile.update-profile-information-form')
                    </div>
                </div>
            @endif

            <!-- Update Password -->
            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                <div class="card mb-3">
                    <div class="card-header bg-light">
                        <h5 class="card-title mb-0"><i class="fa fa-lock mr-2 text-warning"></i>Update Password</h5>
                    </div>
                    <div class="card-body">
                        @livewire('profile.update-password-form')
                    </div>
                </div>
            @endif

            <!-- Two Factor Authentication -->
            <div class="card mb-3">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0"><i class="fa fa-shield mr-2 text-success"></i>Two-Factor Authentication</h5>
                </div>
                <div class="card-body">
                    @livewire('profile.two-factor-authentication-form')
                </div>
            </div>

            <!-- Logout Other Browser Sessions -->
            <div class="card mb-3">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0"><i class="fa fa-sign-out mr-2 text-info"></i>Browser Sessions</h5>
                </div>
                <div class="card-body">
                    @livewire('profile.logout-other-browser-sessions-form')
                </div>
            </div>

            <!-- Delete Account -->
            <div class="card mb-3 border-danger">
                <div class="card-header bg-light border-danger">
                    <h5 class="card-title mb-0 text-danger"><i class="fa fa-trash mr-2"></i>Delete Account</h5>
                </div>
                <div class="card-body">
                    @livewire('profile.delete-user-form')
                </div>
            </div>
        </div>

        <!-- Account Summary Sidebar -->
        <div class="col-lg-4">
            <div class="card sticky-top" style="top: 20px;">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0"><i class="fa fa-user-circle mr-2"></i>Account Summary</h5>
                </div>
                <div class="card-body text-center">
                    <div class="position-relative d-inline-block mb-3">
                        @if(auth()->user()->profile_photo_path && Storage::disk('public')->exists(auth()->user()->profile_photo_path))
                            <img src="{{ asset('storage/' . auth()->user()->profile_photo_path) }}" alt="Profile" class="img-fluid rounded-circle" style="width: 120px; height: 120px; object-fit: cover;">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->fname . ' ' . auth()->user()->lname) }}&background=random&size=128" alt="Profile" class="img-fluid rounded-circle" style="width: 120px; height: 120px;">
                        @endif
                        <a href="#profilePhotoSection" class="badge badge-primary position-absolute" style="bottom: 0; right: 0; cursor: pointer;" title="Click to edit photo">
                            <i class="fa fa-camera"></i>
                        </a>
                    </div>
                    <p class="text-muted small"><a href="#profilePhotoSection" class="text-primary">Edit Photo</a></p>
                    <h5 class="card-title">{{ auth()->user()->fname ?? auth()->user()->name }} {{ auth()->user()->lname }}</h5>
                    <p class="text-muted small mb-3">{{ auth()->user()->email }}</p>

                    <div class="list-group list-group-flush">
                        <div class="list-group-item px-0 py-2 border-0">
                            <small class="text-muted">Account Status</small>
                            <p class="mb-0"><span class="badge badge-success">Active</span></p>
                        </div>
                        <div class="list-group-item px-0 py-2 border-0">
                            <small class="text-muted">Role</small>
                            <p class="mb-0">
                                @if(auth()->user()->roles && auth()->user()->roles->count() > 0)
                                    <strong>{{ auth()->user()->roles->first()->name }}</strong>
                                @else
                                    <strong>User</strong>
                                @endif
                            </p>
                        </div>
                        <div class="list-group-item px-0 py-2 border-0">
                            <small class="text-muted">Member Since</small>
                            <p class="mb-0"><strong>{{ auth()->user()->created_at->format('M d, Y') }}</strong></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        border: 1px solid #e0e0e0;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    .card-header {
        border-bottom: 1px solid #e0e0e0;
    }

    .card-header.bg-light {
        background-color: #f8f9fa !important;
    }

    .card-header h5 {
        font-size: 16px;
        font-weight: 600;
    }
</style>
@endsection

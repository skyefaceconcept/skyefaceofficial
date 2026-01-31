@extends('layouts.admin.app')

@section('title', 'User Details')

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="page-header">
      <div class="page-title">
        <h1>User Details</h1>
        <p class="text-muted">View and manage user information</p>
      </div>
    </div>
  </div>
</div>

@if (session('success'))
<div class="row mt-3">
  <div class="col-md-12">
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="mdi mdi-check-circle mr-2"></i>
      <strong>Success!</strong> {{ session('success') }}
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
  </div>
</div>
@endif

@if ($errors->any())
<div class="row mt-3">
  <div class="col-md-12">
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <i class="mdi mdi-alert-circle mr-2"></i>
      <strong>Error!</strong>
      @foreach ($errors->all() as $error)
        <div>{{ $error }}</div>
      @endforeach
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
  </div>
</div>
@endif

<div class="row mt-4">
  <div class="col-md-8">
    <div class="card shadow-sm">
      <div class="card-header bg-light border-bottom">
        <div class="d-flex justify-content-between align-items-center">
          <h5 class="card-title mb-0">
            <i class="mdi mdi-account mr-2"></i>
            {{ $user->fname ?? '' }} {{ $user->lname ?? '' }}
          </h5>
          <small class="text-muted">ID: {{ $user->id }}</small>
        </div>
      </div>
      <div class="card-body">
        <div class="row mb-4">
          <div class="col-md-6">
            <div class="form-group">
              <label class="font-weight-semibold text-muted small">First Name</label>
              <p class="form-control-static">{{ $user->fname ?? 'N/A' }}</p>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label class="font-weight-semibold text-muted small">Last Name</label>
              <p class="form-control-static">{{ $user->lname ?? 'N/A' }}</p>
            </div>
          </div>
        </div>

        <div class="row mb-4">
          <div class="col-md-6">
            <div class="form-group">
              <label class="font-weight-semibold text-muted small">Email Address</label>
              <p class="form-control-static">
                <a href="mailto:{{ $user->email }}">{{ $user->email ?? 'N/A' }}</a>
              </p>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label class="font-weight-semibold text-muted small">Username</label>
              <p class="form-control-static">{{ $user->username ?? 'N/A' }}</p>
            </div>
          </div>
        </div>

        <div class="row mb-4">
          <div class="col-md-6">
            <div class="form-group">
              <label class="font-weight-semibold text-muted small">Phone Number</label>
              <p class="form-control-static">{{ $user->phone ?? 'N/A' }}</p>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label class="font-weight-semibold text-muted small">Role</label>
              <p class="form-control-static">
                @if($user->role)
                  <span class="badge badge-primary">{{ $user->role->name }}</span>
                @else
                  <span class="text-muted">N/A</span>
                @endif
              </p>
            </div>
          </div>
        </div>

        <hr class="my-4">

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label class="font-weight-semibold text-muted small">Account Created</label>
              <p class="form-control-static">{{ $user->created_at->format('M d, Y - g:i A') }}</p>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label class="font-weight-semibold text-muted small">Last Updated</label>
              <p class="form-control-static">{{ $user->updated_at->format('M d, Y - g:i A') }}</p>
            </div>
          </div>
        </div>

        <div class="mt-4 pt-3 border-top">
          <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary btn-sm">
            <i class="mdi mdi-pencil mr-1"></i> Edit User
          </a>
          <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm">
            <i class="mdi mdi-arrow-left mr-1"></i> Back to List
          </a>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-4">
    <!-- User Status Card -->
    <div class="card shadow-sm mb-4">
      <div class="card-header bg-light border-bottom">
        <h5 class="card-title mb-0">
          <i class="mdi mdi-information mr-2"></i>
          Account Status
        </h5>
      </div>
      <div class="card-body">
        <div class="mb-3">
          <label class="font-weight-semibold text-muted small">Email Verification</label>
          <p class="mt-2">
            @if($user->email_verified_at)
              <span class="badge badge-success">
                <i class="mdi mdi-check-circle mr-1"></i> Verified
              </span>
              <small class="text-muted d-block mt-1">on {{ $user->email_verified_at->format('M d, Y') }}</small>
            @else
              <span class="badge badge-danger">
                <i class="mdi mdi-alert-circle mr-1"></i> Not Verified
              </span>
            @endif
          </p>
        </div>

        <hr class="my-3">

        <div class="mb-3">
          <label class="font-weight-semibold text-muted small">Current Role</label>
          <p class="mt-2">
            @if($user->role)
              <span class="badge badge-info badge-lg" style="font-size: 0.95rem; padding: 8px 12px;">
                {{ $user->role->name }}
              </span>
            @else
              <span class="text-muted">No role assigned</span>
            @endif
          </p>
        </div>

        <hr class="my-3">

        <div class="mb-3">
          <label class="font-weight-semibold text-muted small">Account Activity</label>
          <p class="mt-2">
            <small class="text-muted">
              <i class="mdi mdi-history mr-1"></i>
              Created {{ $user->created_at->diffForHumans() }}
            </small>
          </p>
        </div>
      </div>
    </div>

    <!-- Quick Actions Card -->
    <div class="card shadow-sm">
      <div class="card-header bg-light border-bottom">
        <h5 class="card-title mb-0">
          <i class="mdi mdi-lightning-bolt mr-2"></i>
          Quick Actions
        </h5>
      </div>
      <div class="card-body">
        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-outline-primary btn-block btn-sm mb-2">
          <i class="mdi mdi-pencil mr-2"></i> Edit Details
        </a>
        <form method="POST" action="{{ route('admin.users.resendVerification', $user->id) }}" style="display: inline-block; width: 100%;">
          @csrf
          <button type="submit" class="btn btn-outline-warning btn-block btn-sm">
            <i class="mdi mdi-email-send mr-2"></i> Resend Verification Email
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

<style>
.form-control-static {
  padding: 0.5rem 0;
  color: #333;
  font-weight: 500;
}

.card {
  border: 1px solid #e0e0e0;
  transition: box-shadow 0.3s ease;
}

.card:hover {
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1) !important;
}

.card-header {
  background-color: #f8f9fa !important;
}

.badge-lg {
  font-size: 0.9rem !important;
  padding: 0.5rem 0.75rem !important;
}
</style>
@endsection


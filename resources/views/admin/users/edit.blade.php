@extends('layouts.admin.app')

@section('title', 'Edit User')

@php
  $roles = $roles ?? \App\Models\Role::all();
@endphp

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="page-header">
      <div class="page-title">
        <h1><i class="mdi mdi-account-edit mr-2"></i>Edit User</h1>
        <p class="text-muted">Update user information and settings</p>
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

@if ($errors->has('error'))
<div class="row mt-3">
  <div class="col-md-12">
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <i class="mdi mdi-alert-circle mr-2"></i>
      <strong>Error!</strong> {{ $errors->first('error') }}
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
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
      <i class="mdi mdi-alert mr-2"></i>
      <strong>Validation Errors:</strong>
      <ul class="mb-0 mt-2">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
  </div>
</div>
@endif

<div class="row mt-4">
  <div class="col-md-8">
    @if(auth()->user()->isSuperAdmin() || auth()->user()->can('edit_user'))
    <div class="card shadow-sm">
      <div class="card-header bg-light border-bottom">
        <h5 class="card-title mb-0">
          <i class="mdi mdi-account-details mr-2"></i>
          Personal Information
        </h5>
      </div>
      <div class="card-body">
        <form action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
          @csrf
          @method('PUT')

          <!-- Name Section -->
          <div class="form-section mb-4">
            <h6 class="font-weight-semibold text-uppercase text-muted small mb-3">
              <i class="mdi mdi-account mr-2"></i> Name Information
            </h6>
            <div class="form-row">
              <div class="form-group col-md-4">
                <label for="fname" class="font-weight-semibold">First Name <span class="text-danger">*</span></label>
                <input
                  type="text"
                  class="form-control @error('fname') is-invalid @enderror"
                  id="fname"
                  name="fname"
                  value="{{ old('fname', $user->fname) }}"
                  placeholder="John"
                  required>
                @error('fname')
                  <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
              </div>

              <div class="form-group col-md-4">
                <label for="mname" class="font-weight-semibold">Middle Name</label>
                <input
                  type="text"
                  class="form-control @error('mname') is-invalid @enderror"
                  id="mname"
                  name="mname"
                  value="{{ old('mname', $user->mname) }}"
                  placeholder="Michael">
                @error('mname')
                  <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
              </div>

              <div class="form-group col-md-4">
                <label for="lname" class="font-weight-semibold">Last Name <span class="text-danger">*</span></label>
                <input
                  type="text"
                  class="form-control @error('lname') is-invalid @enderror"
                  id="lname"
                  name="lname"
                  value="{{ old('lname', $user->lname) }}"
                  placeholder="Doe"
                  required>
                @error('lname')
                  <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div>

          <!-- Contact Section -->
          <div class="form-section mb-4">
            <h6 class="font-weight-semibold text-uppercase text-muted small mb-3">
              <i class="mdi mdi-email mr-2"></i> Contact Information
            </h6>
            <div class="form-row">
              <div class="form-group col-md-6">
                <label for="email" class="font-weight-semibold">Email Address <span class="text-danger">*</span></label>
                <input
                  type="email"
                  class="form-control @error('email') is-invalid @enderror"
                  id="email"
                  name="email"
                  value="{{ old('email', $user->email) }}"
                  placeholder="john@example.com"
                  required>
                @error('email')
                  <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
              </div>

              <div class="form-group col-md-6">
                <label for="phone" class="font-weight-semibold">Phone Number</label>
                <input
                  type="text"
                  class="form-control @error('phone') is-invalid @enderror"
                  id="phone"
                  name="phone"
                  value="{{ old('phone', $user->phone) }}"
                  placeholder="+1 (555) 123-4567">
                @error('phone')
                  <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div>

          <!-- Account Section -->
          <div class="form-section mb-4">
            <h6 class="font-weight-semibold text-uppercase text-muted small mb-3">
              <i class="mdi mdi-account-key mr-2"></i> Account Settings
            </h6>
            <div class="form-row">
              <div class="form-group col-md-6">
                <label for="username" class="font-weight-semibold">Username <span class="text-danger">*</span></label>
                <input
                  type="text"
                  class="form-control @error('username') is-invalid @enderror"
                  id="username"
                  name="username"
                  value="{{ old('username', $user->username) }}"
                  placeholder="johndoe"
                  required>
                @error('username')
                  <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
              </div>

              <div class="form-group col-md-6">
                <label for="role_id" class="font-weight-semibold">Role <span class="text-danger">*</span></label>
                <select
                  class="form-control @error('role_id') is-invalid @enderror"
                  id="role_id"
                  name="role_id"
                  required>
                  <option value="">-- Select Role --</option>
                  @foreach($roles as $role)
                    <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                      {{ $role->name }}
                    </option>
                  @endforeach
                </select>
                @error('role_id')
                  <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div>

          <!-- Password Section -->
          <div class="form-section mb-4">
            <div class="d-flex align-items-center mb-3">
              <h6 class="font-weight-semibold text-uppercase text-muted small mb-0">
                <i class="mdi mdi-lock mr-2"></i> Change Password
              </h6>
              <span class="badge badge-secondary ml-2">Optional</span>
            </div>
            <p class="text-muted small mb-3">Leave blank to keep the current password</p>
            <div class="form-row">
              <div class="form-group col-md-6">
                <label for="password" class="font-weight-semibold">New Password</label>
                <input
                  type="password"
                  class="form-control @error('password') is-invalid @enderror"
                  id="password"
                  name="password"
                  placeholder="Enter new password">
                @error('password')
                  <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
                <small class="form-text text-muted mt-2">
                  <i class="mdi mdi-information-outline mr-1"></i>
                  Minimum 8 characters recommended
                </small>
              </div>

              <div class="form-group col-md-6">
                <label for="password_confirmation" class="font-weight-semibold">Confirm Password</label>
                <input
                  type="password"
                  class="form-control @error('password_confirmation') is-invalid @enderror"
                  id="password_confirmation"
                  name="password_confirmation"
                  placeholder="Confirm new password">
                @error('password_confirmation')
                  <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div>

          <!-- Form Actions -->
          <div class="form-section border-top pt-4">
            <div class="d-flex gap-2">
              <button type="submit" class="btn btn-primary btn-lg">
                <i class="mdi mdi-content-save mr-2"></i> Save Changes
              </button>
              <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-lg">
                <i class="mdi mdi-close mr-2"></i> Cancel
              </a>
              @permission('delete_user')
              <button type="button" class="btn btn-danger btn-lg ml-auto" onclick="confirmDelete()">
                <i class="mdi mdi-trash-can mr-2"></i> Delete User
              </button>
              @endpermission
            </div>
          </div>
        </form>
      </div>
    </div>
    @else
    <div class="alert alert-danger" role="alert">
      <i class="mdi mdi-alert-circle mr-2"></i>
      <strong>Access Denied</strong> You do not have permission to edit users.
    </div>
    @endif
  </div>

  <!-- Sidebar Info -->
  <div class="col-md-4">
    <!-- Account Info Card -->
    <div class="card shadow-sm mb-4">
      <div class="card-header bg-light border-bottom">
        <h5 class="card-title mb-0">
          <i class="mdi mdi-information mr-2"></i>
          Account Information
        </h5>
      </div>
      <div class="card-body">
        <div class="mb-3">
          <label class="font-weight-semibold text-muted small">User ID</label>
          <p class="mb-0 mt-1">{{ $user->id }}</p>
        </div>
        <hr>
        <div class="mb-3">
          <label class="font-weight-semibold text-muted small">Created Date</label>
          <p class="mb-0 mt-1">{{ $user->created_at->format('M d, Y') }}</p>
          <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
        </div>
        <hr>
        <div class="mb-3">
          <label class="font-weight-semibold text-muted small">Last Updated</label>
          <p class="mb-0 mt-1">{{ $user->updated_at->format('M d, Y') }}</p>
          <small class="text-muted">{{ $user->updated_at->diffForHumans() }}</small>
        </div>
      </div>
    </div>

    <!-- Verification Status Card -->
    <div class="card shadow-sm mb-4">
      <div class="card-header bg-light border-bottom">
        <h5 class="card-title mb-0">
          <i class="mdi mdi-shield-check mr-2"></i>
          Verification Status
        </h5>
      </div>
      <div class="card-body">
        <div class="mb-3">
          <label class="font-weight-semibold text-muted small">Email Verification</label>
          <p class="mt-2">
            @if($user->email_verified_at)
              <span class="badge badge-success badge-lg">
                <i class="mdi mdi-check-circle mr-1"></i> Verified
              </span>
              <small class="text-muted d-block mt-1">{{ $user->email_verified_at->format('M d, Y') }}</small>
            @else
              <span class="badge badge-danger badge-lg">
                <i class="mdi mdi-alert-circle mr-1"></i> Not Verified
              </span>
            @endif
          </p>
        </div>
        @if(!$user->email_verified_at)
        <form method="POST" action="{{ route('admin.users.resendVerification', $user->id) }}">
          @csrf
          <button type="submit" class="btn btn-warning btn-sm btn-block">
            <i class="mdi mdi-email-send mr-1"></i> Resend Verification Email
          </button>
        </form>
        @endif
      </div>
    </div>

    <!-- Role Card -->
    <div class="card shadow-sm">
      <div class="card-header bg-light border-bottom">
        <h5 class="card-title mb-0">
          <i class="mdi mdi-account-multiple mr-2"></i>
          Role
        </h5>
      </div>
      <div class="card-body">
        <p class="mb-0">
          @if($user->role)
            <span class="badge badge-primary badge-lg">{{ $user->role->name }}</span>
          @else
            <span class="text-muted">No role assigned</span>
          @endif
        </p>
      </div>
    </div>
  </div>
</div>

<style>
.form-section {
  border-bottom: none;
}

.badge-lg {
  font-size: 0.9rem;
  padding: 0.5rem 0.75rem;
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

.form-control:focus,
.form-control:active {
  border-color: #007bff;
  box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.btn {
  font-weight: 500;
  border-radius: 0.375rem;
}

.btn-lg {
  padding: 0.75rem 1.5rem;
}

.gap-2 {
  gap: 0.5rem;
}
</style>

@push('extra-js')
<script>
function confirmDelete() {
  if (confirm('Are you absolutely sure you want to delete this user? This action cannot be undone.')) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("admin.users.destroy", $user->id) }}';

    const tokenInput = document.createElement('input');
    tokenInput.type = 'hidden';
    tokenInput.name = '_token';
    tokenInput.value = '{{ csrf_token() }}';

    const methodInput = document.createElement('input');
    methodInput.type = 'hidden';
    methodInput.name = '_method';
    methodInput.value = 'DELETE';

    form.appendChild(tokenInput);
    form.appendChild(methodInput);
    document.body.appendChild(form);
    form.submit();
  }
}
</script>
@endpush
@endsection

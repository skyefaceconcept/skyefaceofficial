@extends('layouts.admin.app')

@section('title', 'Create User')

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="page-header">
      <div class="page-title">
        <h1>Create New User</h1>
      </div>
    </div>
  </div>
</div>

<div class="row mt-3">
  <div class="col-md-8">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title">User Details</h5>
      </div>
      <div class="card-body">
        @permission('create_user')
        <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
          @csrf

          <div class="form-row">
            <div class="form-group col-md-4">
              <label for="fname">First Name</label>
              <input
                type="text"
                class="form-control @error('fname') is-invalid @enderror"
                id="fname"
                name="fname"
                value="{{ old('fname') }}"
                placeholder="John"
                required>
              @error('fname')
                <span class="invalid-feedback">{{ $message }}</span>
              @enderror
            </div>

            <div class="form-group col-md-4">
              <label for="mname">Middle Name</label>
              <input
                type="text"
                class="form-control @error('mname') is-invalid @enderror"
                id="mname"
                name="mname"
                value="{{ old('mname') }}"
                placeholder="Michael">
              @error('mname')
                <span class="invalid-feedback">{{ $message }}</span>
              @enderror
            </div>

            <div class="form-group col-md-4">
              <label for="lname">Last Name</label>
              <input
                type="text"
                class="form-control @error('lname') is-invalid @enderror"
                id="lname"
                name="lname"
                value="{{ old('lname') }}"
                placeholder="Doe"
                required>
              @error('lname')
                <span class="invalid-feedback">{{ $message }}</span>
              @enderror
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="email">Email Address</label>
              <input
                type="email"
                class="form-control @error('email') is-invalid @enderror"
                id="email"
                name="email"
                value="{{ old('email') }}"
                placeholder="john@example.com"
                required>
              @error('email')
                <span class="invalid-feedback">{{ $message }}</span>
              @enderror
            </div>

            <div class="form-group col-md-6">
              <label for="phone">Phone Number</label>
              <input
                type="text"
                class="form-control @error('phone') is-invalid @enderror"
                id="phone"
                name="phone"
                value="{{ old('phone') }}"
                placeholder="+1 (555) 123-4567">
              @error('phone')
                <span class="invalid-feedback">{{ $message }}</span>
              @enderror
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="username">Username</label>
              <input
                type="text"
                class="form-control @error('username') is-invalid @enderror"
                id="username"
                name="username"
                value="{{ old('username') }}"
                placeholder="johndoe"
                required>
              @error('username')
                <span class="invalid-feedback">{{ $message }}</span>
              @enderror
            </div>

            <div class="form-group col-md-6">
              <label for="role_id">Role</label>
              <select
                class="form-control @error('role_id') is-invalid @enderror"
                id="role_id"
                name="role_id"
                required>
                <option value="">-- Select Role --</option>
                @foreach($roles as $role)
                  <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                    {{ $role->name }}
                  </option>
                @endforeach
              </select>
              @error('role_id')
                <span class="invalid-feedback">{{ $message }}</span>
              @enderror
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="password">Password</label>
              <input
                type="password"
                class="form-control @error('password') is-invalid @enderror"
                id="password"
                name="password"
                placeholder="Enter a strong password"
                required>
              @error('password')
                <span class="invalid-feedback">{{ $message }}</span>
              @enderror
              <small class="form-text text-muted">At least 8 characters, with uppercase, lowercase, numbers, and symbols.</small>
            </div>

            <div class="form-group col-md-6">
              <label for="password_confirmation">Confirm Password</label>
              <input
                type="password"
                class="form-control @error('password_confirmation') is-invalid @enderror"
                id="password_confirmation"
                name="password_confirmation"
                placeholder="Confirm password"
                required>
              @error('password_confirmation')
                <span class="invalid-feedback">{{ $message }}</span>
              @enderror
            </div>
          </div>

          <div class="form-group mt-4">
            <button type="submit" class="btn btn-primary">Create User</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>
          </div>
        </form>
        @else
        <div class="alert alert-danger">You do not have permission to create users.</div>
        @endpermission
      </div>
    </div>
  </div>

  <div class="col-md-4">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title">Help</h5>
      </div>
      <div class="card-body">
        <p><strong>First Name:</strong> User's first name.</p>
        <p><strong>Middle Name:</strong> Optional middle name.</p>
        <p><strong>Last Name:</strong> User's last name.</p>
        <p><strong>Email:</strong> Must be unique and used for login.</p>
        <p><strong>Phone:</strong> Contact phone number.</p>
        <p><strong>Username:</strong> Unique username identifier.</p>
        <p><strong>Role:</strong> Assign user permissions via role.</p>
        <p><strong>Password:</strong> Must be strong and confirmed.</p>
      </div>
    </div>
  </div>
</div>
@endsection

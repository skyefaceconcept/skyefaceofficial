@extends('layouts.admin.app')

@section('title', 'Users')

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="page-header d-flex justify-content-between align-items-center">
      <div class="page-title">
        <h1>User Management</h1>
      </div>
      <div>
        @permission('create_user')
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">Add New User</a>
        @endpermission
      </div>
    </div>
  </div>
</div>

<div class="row mt-3">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title">All Users</h5>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Email Verified</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($users as $user)
              <tr>
                <td>{{ $user->id }}</td>
                <td><strong>{{ $user->fname ?? '' }} {{ $user->lname ?? '' }}</strong></td>
                <td>{{ $user->email }}</td>
                <td><span class="badge badge-warning">{{ $user->role->name ?? 'N/A' }}</span></td>
                <td>
                  @if($user->email_verified_at)
                    <span class="badge badge-success">Yes</span>
                  @else
                    <span class="badge badge-danger">No</span>
                  @endif
                </td>
                <td>
                  @permission('edit_user')
                  <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-info">Edit</a>
                  @endpermission
                  @permission('delete_user')
                  <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this user?')">Delete</button>
                  </form>
                  @endpermission
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="6" class="text-center text-muted">No users found.</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
        @if($users->hasPages())
        <div class="mt-3">
          {{ $users->links() }}
        </div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection

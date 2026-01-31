@extends('layouts.admin.app')

@section('title', 'Roles')

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="page-header d-flex justify-content-between align-items-center">
      <div class="page-title">
        <h1>Role Management</h1>
      </div>
      <div>
        @permission('create_role')
        <a href="{{ route('admin.roles.create') }}" class="btn btn-primary btn-sm">Add New Role</a>
        @endpermission
      </div>
    </div>
  </div>
</div>

<div class="row mt-3">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title">All Roles</h5>
      </div>
      <div class="card-body">
        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Slug</th>
                <th>Description</th>
                <th>Users Count</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($roles as $role)
              <tr>
                <td>{{ $role->id }}</td>
                <td><strong>{{ $role->name }}</strong></td>
                <td><span class="badge badge-info">{{ $role->slug }}</span></td>
                <td>{{ $role->description ?? 'N/A' }}</td>
                <td><span class="badge badge-primary">{{ $role->users->count() }}</span></td>
                  <td>
                    @permission('edit_role')
                    <a href="{{ route('admin.roles.edit', $role->slug) }}" class="btn btn-sm btn-info">Edit</a>
                    @endpermission
                    @permission('delete_role')
                    <form action="{{ route('admin.roles.destroy', $role->slug) }}" method="POST" class="d-inline">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this role?')">Delete</button>
                    </form>
                    @endpermission
                  </td>
              </tr>
              @empty
              <tr>
                <td colspan="6" class="text-center text-muted">No roles found.</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

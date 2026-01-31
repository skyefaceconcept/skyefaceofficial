@extends('layouts.admin.app')

@section('title', 'Permissions')

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="page-header d-flex justify-content-between align-items-center">
      <div class="page-title">
        <h1>Permission Management</h1>
      </div>
      <div>
        @permission('create_permission')
        <a href="{{ route('admin.permissions.create') }}" class="btn btn-primary btn-sm">Add New Permission</a>
        @endpermission
      </div>
    </div>
  </div>
</div>

<div class="row mt-3">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title">All Permissions</h5>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Slug</th>
                <th>Description</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($permissions as $permission)
              <tr>
                <td>{{ $permission->id }}</td>
                <td><strong>{{ $permission->name }}</strong></td>
                <td><span class="badge badge-success">{{ $permission->slug }}</span></td>
                <td>{{ $permission->description ?? 'N/A' }}</td>
                <td>
                  @permission('edit_permission')
                  <a href="{{ route('admin.permissions.edit', $permission->id) }}" class="btn btn-sm btn-info">Edit</a>
                  @endpermission
                  @permission('delete_permission')
                  <form action="{{ route('admin.permissions.destroy', $permission->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this permission?')">Delete</button>
                  </form>
                  @endpermission
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="5" class="text-center text-muted">No permissions found.</td>
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

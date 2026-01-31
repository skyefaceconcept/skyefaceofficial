@extends('layouts.admin.app')

@section('title', 'Create Role')

@section('content')
<div class="row">
  <div class="col-md-8 offset-md-2">
    <div class="page-header">
      <h1>Create Role</h1>
    </div>

    <div class="card mt-3">
      <div class="card-body">
        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if($errors->any())
        <div class="alert alert-danger">
          <ul class="mb-0">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
        @endif
        @permission('create_role')
        <form action="{{ route('admin.roles.store') }}" method="POST">
          @csrf

          <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
          </div>

          <div class="form-group mt-2">
            <label for="slug">Slug</label>
            <input type="text" name="slug" id="slug" class="form-control" value="{{ old('slug') }}">
            <small class="form-text text-muted">Leave empty to auto-generate from the name; must be unique.</small>
          </div>

          <div class="form-group mt-2">
            <label for="description">Description</label>
            <textarea name="description" id="description" class="form-control" rows="3">{{ old('description') }}</textarea>
          </div>

          @if(isset($permissions) && $permissions->count())
          <div class="form-group mt-3">
            <label>Permissions</label>
            <div class="row">
              @foreach($permissions as $permission)
              <div class="col-md-6">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->id }}" id="perm_{{ $permission->id }}" {{ (is_array(old('permissions')) && in_array($permission->id, old('permissions'))) ? 'checked' : '' }}>
                  <label class="form-check-label" for="perm_{{ $permission->id }}">{{ $permission->name }} <small class="text-muted">({{ $permission->slug }})</small></label>
                </div>
              </div>
              @endforeach
            </div>
          </div>
          @endif

          <div class="mt-4 d-flex justify-content-between">
            <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Create Role</button>
          </div>
        </form>
        @else
        <div class="alert alert-danger">You do not have permission to create roles.</div>
        @endpermission
      </div>
    </div>
  </div>
</div>
@push('extra-js')
<script>
function slugify(text) {
  return text.toString().toLowerCase()
    .replace(/\s+/g, '-')
    .replace(/[^a-z0-9\-]/g, '')
    .replace(/--+/g, '-')
    .replace(/^-+/, '')
    .replace(/-+$/, '');
}

document.addEventListener('DOMContentLoaded', function () {
  const nameInput = document.getElementById('name');
  const slugInput = document.getElementById('slug');
  if (!nameInput || !slugInput) return;

  nameInput.addEventListener('input', function () {
    if (!slugInput.dataset.touched) {
      slugInput.value = slugify(nameInput.value);
    }
  });

  slugInput.addEventListener('input', function () {
    slugInput.dataset.touched = true;
  });
});
</script>
@endpush

@endsection

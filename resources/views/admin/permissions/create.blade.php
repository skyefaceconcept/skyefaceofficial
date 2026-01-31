@extends('layouts.admin.app')

@section('title', 'Create Permission')

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="page-header">
      <div class="page-title">
        <h1>Create Permission</h1>
      </div>
    </div>
  </div>
</div>

<div class="row mt-3">
  <div class="col-md-8">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title">Permission Details</h5>
      </div>
      <div class="card-body">
        @permission('create_permission')
        <form action="{{ route('admin.permissions.store') }}" method="POST">
          @csrf

          <div class="form-group">
            <label for="name">Permission Name</label>
            <input
              type="text"
              class="form-control @error('name') is-invalid @enderror"
              id="name"
              name="name"
              value="{{ old('name') }}"
              placeholder="e.g., Create User"
              required>
            @error('name')
              <span class="invalid-feedback">{{ $message }}</span>
            @enderror
          </div>

          <div class="form-group">
            <label for="slug">Permission Slug</label>
            <input
              type="text"
              class="form-control @error('slug') is-invalid @enderror"
              id="slug"
              name="slug"
              value="{{ old('slug') }}"
              placeholder="e.g., create-user"
              required>
            @error('slug')
              <span class="invalid-feedback">{{ $message }}</span>
            @enderror
            <small class="form-text text-muted">Use lowercase letters, numbers, and hyphens only.</small>
          </div>

          <div class="form-group">
            <label for="description">Description</label>
            <textarea
              class="form-control @error('description') is-invalid @enderror"
              id="description"
              name="description"
              rows="4"
              placeholder="Brief description of what this permission does...">{{ old('description') }}</textarea>
            @error('description')
              <span class="invalid-feedback">{{ $message }}</span>
            @enderror
          </div>

          @if(isset($routes) && $routes->count())
          <div class="form-group">
            <label for="route_select">Link To Route <span class="text-danger">*</span></label>
            <select id="route_select" class="form-control @error('route') is-invalid @enderror" required>
              <option value="">-- Select a named route (required) --</option>
              @php
                // Group routes by prefix
                $groupedRoutes = [];
                foreach($routes as $r) {
                  $parts = explode('.', $r['name']);
                  $group = count($parts) > 1 ? $parts[0] : 'Other';
                  if(!isset($groupedRoutes[$group])) {
                    $groupedRoutes[$group] = [];
                  }
                  $groupedRoutes[$group][] = $r;
                }
                ksort($groupedRoutes);
              @endphp
              @foreach($groupedRoutes as $group => $groupRoutes)
                <optgroup label="{{ ucfirst(str_replace('-', ' ', $group)) }}">
                  @foreach($groupRoutes as $r)
                    <option value="{{ $r['name'] }}" data-uri="{{ $r['uri'] }}">{{ $r['name'] }} &mdash; {{ $r['uri'] }}</option>
                  @endforeach
                </optgroup>
              @endforeach
            </select>
            <input type="hidden" id="route" name="route" value="">
            @error('route')
              <span class="invalid-feedback">{{ $message }}</span>
            @enderror
            <small class="form-text text-muted">Selecting a route will auto-fill the fields below, which you can then customize.</small>
          </div>
          @endif

          <!-- Preview removed per request -->

          <div class="form-group mt-4">
            <button type="submit" class="btn btn-primary">Create Permission</button>
            <a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary">Cancel</a>
          </div>
        </form>
        @else
        <div class="alert alert-danger">You do not have permission to create permissions.</div>
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
        <p><strong>Permission Name:</strong> A human-readable name for the permission.</p>
        <p><strong>Slug:</strong> A unique identifier in lowercase. Used in code to check permissions.</p>
        <p><strong>Description:</strong> Optional details explaining what this permission grants.</p>
        <hr>
        <p class="text-muted small"><strong>Example:</strong></p>
        <p class="text-muted small">
          Name: "Create User"<br>
          Slug: "create-user"
        </p>
      </div>
    </div>
  </div>
</div>
@endsection

@section('extra-js')
<script>
document.addEventListener('DOMContentLoaded', function(){
  console.log('DOM loaded - initializing permission form');

  var sel = document.getElementById('route_select');
  var nameInput = document.getElementById('name');
  var slugInput = document.getElementById('slug');
  var descInput = document.getElementById('description');

  console.log('Elements:', { sel: !!sel, nameInput: !!nameInput, slugInput: !!slugInput, descInput: !!descInput });

  // Auto-generate slug from name
  if(nameInput && slugInput){
    if(slugInput.value) slugInput.dataset.touched = true;
    else slugInput.dataset.touched = false;

    nameInput.addEventListener('input', function(){
      if(slugInput.dataset.touched === 'true' || slugInput.dataset.touched === true) return;
      var s = nameInput.value.toString().toLowerCase()
        .replace(/\s+/g, '-')
        .replace(/[^a-z0-9\-]/g, '')
        .replace(/--+/g, '-')
        .replace(/^-+/, '')
        .replace(/-+$/, '');
      slugInput.value = s;
    });

    slugInput.addEventListener('input', function(){
      slugInput.dataset.touched = true;
      updatePreview();
    });
  }

  if(descInput){
    descInput.addEventListener('input', function(){
      // description edited by user
    });
  }

  // Route selector AJAX
  if(!sel) {
    console.log('No route selector found');
    return;
  }

  console.log('Attaching change listener to route selector');

  sel.addEventListener('change', function(){
    var route = sel.value;
    console.log('Route selected:', route);

    if(!route){
      if(nameInput) nameInput.value = '';
      if(slugInput) slugInput.value = '';
      if(descInput) descInput.value = '';
      if(document.getElementById('route')) document.getElementById('route').value = '';
      updatePreview();
      return;
    }

    // Show loading state directly in inputs
    console.log('Setting loading state in inputs');
    if(nameInput) nameInput.value = 'Loading...';
    if(slugInput) slugInput.value = 'Loading...';
    if(descInput) descInput.value = 'Loading...';

    // AJAX call
    setTimeout(function(){
      console.log('Sending AJAX request to {{ route("admin.permissions.generateFromRoute") }}');

      fetch('{{ route("admin.permissions.generateFromRoute") }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ route: route })
      })
      .then(response => {
        console.log('Response received:', response.status);
        return response.json();
      })
      .then(data => {
        console.log('Data received:', data);

        if(data.error){
          console.error('Server error:', data.error);
          if(nameInput) nameInput.value = '';
          return;
        }

        // Fill fields
        if(nameInput) nameInput.value = data.name;
        if(slugInput) slugInput.value = data.slug;
        if(descInput) descInput.value = data.description;
        if(document.getElementById('route')) document.getElementById('route').value = route;
      })
      .catch(error => {
        console.error('AJAX Error:', error);
        if(nameEl) nameEl.textContent = 'Error: ' + error.message;
      });
    }, 300);
  });

  // Initial preview
  updatePreview();
});
</script>
@endsection

@extends('layouts.admin.app')

@section('title', 'Backup & Restore')

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="page-header">
      <div class="page-title">
        <h1>Backup & Restore</h1>
      </div>
    </div>
  </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
  {{ session('success') }}
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
@endif

<div class="row mt-3">
  <div class="col-md-6">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title">Create Database Backup</h5>
      </div>
      <div class="card-body">
        <p>Create a backup of your database to ensure data safety.</p>
        @permission('backup_system')
        <form action="{{ route('admin.settings.performBackup') }}" method="POST">
          @csrf
          <button type="submit" class="btn btn-primary">Create Backup Now</button>
        </form>
        @else
        <div class="alert alert-danger">You do not have permission to create backups.</div>
        @endpermission
      </div>
    </div>
  </div>

  <div class="col-md-6">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title">Recent Backups</h5>
      </div>
      <div class="card-body">
        @if(!empty($backups) && count($backups) > 0)
          <ul class="list-group">
            @foreach($backups as $b)
              <li class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                  <strong>{{ $b['name'] }}</strong>
                  <div class="text-muted small">{{ date('Y-m-d H:i:s', $b['modified']) }} — {{ round($b['size'] / 1024, 2) }} KB</div>
                </div>
                <div>
                  @permission('backup_system')
                  <a href="{{ route('admin.settings.downloadBackup', ['path' => urlencode($b['path'])]) }}" class="btn btn-sm btn-outline-primary">Download</a>
                  @endpermission
                </div>
              </li>
            @endforeach
          </ul>
        @else
          <p class="text-muted">No backups found yet.</p>
          <small>Backups will be listed here once you create one.</small>
        @endif
      </div>
    </div>
  </div>
</div>

<div class="row mt-3">
  <div class="col-md-6">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title">System Information</h5>
      </div>
      <div class="card-body">
        <ul class="list-unstyled">
          <li><strong>Disk Space Used:</strong> {{ disk_free_space('/') ? round(disk_free_space('/') / 1024 / 1024 / 1024, 2) . ' GB' : 'N/A' }}</li>
          <li><strong>Last Backup:</strong> Never</li>
          <li><strong>Database Size:</strong> N/A</li>
        </ul>
      </div>
    </div>
  </div>

  <div class="col-md-6">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title">Restore from Backup</h5>
      </div>
      <div class="card-body">
        <p>Select a backup file to restore your database.</p>
        <form enctype="multipart/form-data">
          <div class="form-group">
            <input type="file" class="form-control" accept=".sql,.zip" disabled>
          </div>
          <button type="submit" class="btn btn-warning" disabled>Restore Backup</button>
        </form>
        <small class="text-muted d-block mt-3">Restore functionality coming soon.</small>
      </div>
    </div>
  </div>
</div>

<div class="row mt-3">
  <div class="col-md-12">
    <a href="{{ route('admin.settings.index') }}" class="btn btn-secondary">Back to Settings</a>
  </div>
</div>
@endsection

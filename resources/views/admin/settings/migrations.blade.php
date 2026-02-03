@extends('layouts.admin.app')

@section('title', 'Migrations')

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="page-header">
      <div class="page-title">
        <h1>Migrations</h1>
      </div>
    </div>
  </div>
</div>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="card">
  <div class="card-body">
    <p class="small text-muted">Manage migration files: see which have run, install pending ones, refresh changed migrations, or rollback recent batches. Be careful — running migrations via the UI affects your database.</p>

    <div class="mb-3">
      <form action="{{ route('admin.settings.migrations.run') }}" method="POST" class="d-inline">
        @csrf
        <input type="hidden" name="action" value="run-all" />
        <button class="btn btn-success" onclick="return confirm('Run all pending migrations? This will modify your database.')">Run All Pending</button>
      </form>

      <form action="{{ route('admin.settings.migrations.rollback') }}" method="POST" class="d-inline">
        @csrf
        <input type="hidden" name="steps" value="1" />
        <button class="btn btn-warning" onclick="return confirm('Rollback the last migration batch?')">Rollback Last Batch</button>
      </form>
    </div>

    <table class="table table-striped table-sm">
      <thead>
        <tr>
          <th>Filename</th>
          <th>Status</th>
          <th>Batch</th>
          <th class="text-right">Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($migrations as $m)
        <tr>
          <td>{{ $m['filename'] }}</td>
          <td>
            @if($m['status'] === 'ran')
              <span class="badge badge-success">Ran</span>
            @else
              <span class="badge badge-secondary">Pending</span>
            @endif
          </td>
          <td>{{ $m['batch'] ?? '—' }}</td>
          <td class="text-right">
            @if($m['status'] === 'pending')
              <form action="{{ route('admin.settings.migrations.run') }}" method="POST" class="d-inline">
                @csrf
                <input type="hidden" name="action" value="run-file" />
                <input type="hidden" name="file" value="{{ $m['filename'] }}" />
                <button class="btn btn-sm btn-success">Run</button>
              </form>
            @else
              <form action="{{ route('admin.settings.migrations.refresh') }}" method="POST" class="d-inline">
                @csrf
                <input type="hidden" name="file" value="{{ $m['filename'] }}" />
                <button class="btn btn-sm btn-info" onclick="return confirm('Refresh (rollback & re-run) this migration file?')">Refresh</button>
              </form>
            @endif
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>

    @if(session('migration_output'))
      <hr>
      <h6>Command Output</h6>
      <pre style="white-space: pre-wrap; background: #0b1220; color: #cfe8ff; padding: 12px; border-radius: 6px;">{{ session('migration_output') }}</pre>
    @endif

  </div>
</div>
@endsection
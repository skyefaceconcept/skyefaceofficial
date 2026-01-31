@extends('layouts.admin.app')

@section('content')
<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1><i class="fa fa-quote-left mr-2"></i>Quote Requests</h1>
        </div>
        <div class="text-right">
            <span class="badge badge-pill badge-info mr-2" title="Total Quotes">Total: {{ $stats['total'] ?? 0 }}</span>
            <span class="badge badge-pill badge-primary mr-2" title="New Quotes">New: {{ $stats['new'] ?? 0 }}</span>
            <span class="badge badge-pill badge-warning mr-2" title="Under Review">Reviewed: {{ $stats['reviewed'] ?? 0 }}</span>
            <span class="badge badge-pill badge-success mr-2" title="Quoted">Quoted: {{ $stats['quoted'] ?? 0 }}</span>
            <span class="badge badge-pill badge-danger mr-2" title="Rejected">Rejected: {{ $stats['rejected'] ?? 0 }}</span>
            <span class="badge badge-pill badge-info" title="Accepted">Accepted: {{ $stats['accepted'] ?? 0 }}</span>
        </div>
    </div>

    @if(session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if($quotes->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Package</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Submitted</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($quotes as $q)
                    <tr>
                        <td><strong>{{ $q->id }}</strong></td>
                        <td>{{ $q->package ?? 'General' }}</td>
                        <td>{{ $q->name }}</td>
                        <td><a href="mailto:{{ $q->email }}">{{ $q->email }}</a></td>
                        <td>{{ $q->phone ?? '—' }}</td>
                        <td>
                            @php
                                $statusColors = [
                                    'new' => 'primary',
                                    'reviewed' => 'warning',
                                    'quoted' => 'success',
                                    'rejected' => 'danger',
                                    'accepted' => 'info'
                                ];
                                $statusLabel = ucfirst($q->status);
                                $color = $statusColors[$q->status] ?? 'secondary';
                            @endphp
                            <span class="badge badge-{{ $color }}">{{ $statusLabel }}</span>
                            @if($q->responded_at)
                                <br><small class="text-muted">Responded: {{ $q->responded_at->format('M d, H:i') }}</small>
                            @endif
                        </td>
                        <td><small>{{ $q->created_at->format('M d, Y H:i') }}</small></td>
                        <td>
                            <a href="{{ route('admin.quotes.show', $q->id) }}" class="btn btn-sm btn-primary" title="View details"><i class="fa fa-eye"></i> View</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center">
            {{ $quotes->links() }}
        </div>
    @else
        <div class="alert alert-info">
            <strong>No quotes yet.</strong> Quote requests will appear here when customers submit them through the Services page.
        </div>
    @endif
</div>
@endsection

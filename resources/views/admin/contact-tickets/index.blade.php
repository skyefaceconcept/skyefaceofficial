@extends('layouts.admin.app')

@section('content')
@php $title = 'Contact Tickets'; @endphp

<div class="page-header d-flex align-items-start justify-content-between mb-4">
    <div>
        <h4 class="page-title">Contact Tickets</h4>
        <p class="text-muted small mb-0">Manage and respond to customer inquiries</p>
    </div>
    <div class="d-flex gap-2">
        <div class="input-group">
            <input id="ticketSearch" type="search" class="form-control" placeholder="Search tickets by number, subject or client...">
            <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="button" onclick="document.getElementById('ticketSearch').value=''; filterTickets();">Clear</button>
            </div>
        </div>
    </div>
</div>

{{-- Status Filters --}}
<div class="mb-3">
    <a href="{{ route('admin.contact-tickets.index') }}" class="btn btn-sm btn-primary mr-1">All</a>
    <a href="{{ route('admin.contact-tickets.index', ['status' => 'open']) }}" class="btn btn-sm btn-danger mr-1">Open</a>
    <a href="{{ route('admin.contact-tickets.index', ['status' => 'pending_reply']) }}" class="btn btn-sm btn-warning mr-1">Pending Reply</a>
    <a href="{{ route('admin.contact-tickets.index', ['status' => 'closed']) }}" class="btn btn-sm btn-secondary mr-1">Closed</a>
</div>

{{-- Statistics --}}
<div class="row mb-4">
    <div class="col-md-3 col-sm-6">
        <div class="card card-stats">
            <div class="card-body">
                <h6 class="text-muted">Open Tickets</h6>
                <h3 class="m-0 text-danger">{{ \App\Models\ContactTicket::where('status', 'open')->count() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card card-stats">
            <div class="card-body">
                <h6 class="text-muted">Pending Reply</h6>
                <h3 class="m-0 text-warning">{{ \App\Models\ContactTicket::where('status', 'pending_reply')->count() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card card-stats">
            <div class="card-body">
                <h6 class="text-muted">Closed Tickets</h6>
                <h3 class="m-0 text-muted">{{ \App\Models\ContactTicket::where('status', 'closed')->count() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card card-stats">
            <div class="card-body">
                <h6 class="text-muted">Assigned to Me</h6>
                <h3 class="m-0 text-primary">{{ \App\Models\ContactTicket::where('assigned_to', auth()->id())->whereIn('status', ['open', 'pending_reply'])->count() }}</h3>
            </div>
        </div>
    </div>
</div>

{{-- Tickets Table --}}
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="ticketsTable">
                <thead>
                    <tr>
                        <th>Ticket</th>
                        <th>Client</th>
                        <th>Subject</th>
                        <th>Status</th>
                        <th>Assigned To</th>
                        <th>Last Reply</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets as $ticket)
                    <tr>
                        <td>
                            <a href="{{ route('admin.contact-tickets.show', $ticket) }}" class="font-weight-bold">{{ $ticket->ticket_number }}</a>
                        </td>
                        <td>
                            <div class="small">
                                <strong>{{ $ticket->user_name }}</strong><br>
                                <span class="text-muted">{{ $ticket->user_email }}</span>
                            </div>
                        </td>
                        <td>{{ $ticket->subject }}</td>
                        <td>
                            @if($ticket->status === 'open')
                                <span class="badge badge-danger">Open</span>
                            @elseif($ticket->status === 'pending_reply')
                                <span class="badge badge-warning">Pending Reply</span>
                            @else
                                <span class="badge badge-secondary">Closed</span>
                            @endif
                        </td>
                        <td>
                            @if($ticket->assigned_to)
                                {{ $ticket->assignedAdmin->fname ?? 'N/A' }}
                            @else
                                <em class="text-muted">Unassigned</em>
                            @endif
                        </td>
                        <td>{{ $ticket->last_reply_date?->diffForHumans() ?? 'N/A' }}</td>
                        <td class="text-center">
                            <a href="{{ route('admin.contact-tickets.show', $ticket) }}" class="btn btn-sm btn-outline-primary">View</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">No tickets found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-3 d-flex justify-content-end">
            {{ $tickets->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Simple client-side filtering for quick searches
    function filterTickets() {
        const q = document.getElementById('ticketSearch').value.toLowerCase().trim();
        const rows = document.querySelectorAll('#ticketsTable tbody tr');

        rows.forEach(function(row) {
            const text = row.textContent.toLowerCase();
            row.style.display = q === '' || text.indexOf(q) !== -1 ? '' : 'none';
        });
    }

    document.getElementById('ticketSearch').addEventListener('input', filterTickets);
</script>
@endpush

@endsection

@extends('layouts.app-buzbox')

@section('title', 'Client Dashboard')

@section('content')
<div class="container-fluid">
    <!--***** REPORT-1 *****-->
    <div class="row" id="report1" style="margin-top: 20px; margin-bottom: 30px;">
        <div class="col-sm-3">
            <div class="card">
                <div class="card-block">
                    <div class="text-left report1-cont">
                        <h2 class="font-light m-b-0">
                            <i class="fa fa-ticket text-info"></i> {{ $tickets->total() }}
                        </h2>
                        <span class="text-muted">Total Tickets</span>
                    </div>
                    <div class="progress mt-3" style="height: 6px;">
                        <div class="progress-bar bg-info" style="width: 100%"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="card">
                <div class="card-block">
                    <div class="text-left report1-cont">
                        <h2 class="font-light m-b-0">
                            <i class="fa fa-envelope-open text-success"></i> {{ $openTickets }}
                        </h2>
                        <span class="text-muted">Open Tickets</span>
                    </div>
                    <div class="progress mt-3" style="height: 6px;">
                        <div class="progress-bar bg-success" style="width: {{ ($openTickets / max($openTickets + $pendingReplyTickets + $resolvedTickets, 1)) * 100 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="card">
                <div class="card-block">
                    <div class="text-left report1-cont">
                        <h2 class="font-light m-b-0">
                            <i class="fa fa-clock-o text-warning"></i> {{ $pendingReplyTickets }}
                        </h2>
                        <span class="text-muted">Pending Reply</span>
                    </div>
                    <div class="progress mt-3" style="height: 6px;">
                        <div class="progress-bar bg-warning" style="width: {{ ($pendingReplyTickets / max($openTickets + $pendingReplyTickets + $resolvedTickets, 1)) * 100 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="card">
                <div class="card-block">
                    <div class="text-left report1-cont">
                        <h2 class="font-light m-b-0">
                            <i class="fa fa-check-circle text-success"></i> {{ $resolvedTickets }}
                        </h2>
                        <span class="text-muted">Resolved Tickets</span>
                    </div>
                    <div class="progress mt-3" style="height: 6px;">
                        <div class="progress-bar bg-success" style="width: {{ ($resolvedTickets / max($openTickets + $pendingReplyTickets + $resolvedTickets, 1)) * 100 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Quick Actions</h3>
                </div>
                <div class="card-block">
                    <div class="btn-group" role="group">
                        <a href="{{ route('home') }}#services" class="btn btn-primary">
                            <i class="fa fa-plus"></i> Request Quote
                        </a>
                        <a href="{{ route('home') }}#contact" class="btn btn-success">
                            <i class="fa fa-envelope"></i> Contact Support
                        </a>
                        <a href="{{ route('tickets.show', $tickets->first()->ticket_number ?? '#') }}" class="btn btn-info" @if($tickets->total() == 0) style="opacity: 0.5; pointer-events: none;" @endif>
                            <i class="fa fa-eye"></i> View Latest Ticket
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tickets Table -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">My Support Tickets</h3>
                    <p class="card-category">View and manage your support tickets</p>
                </div>
                <div class="card-block">
                    @if($tickets->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Ticket #</th>
                                    <th>Subject</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Messages</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tickets as $ticket)
                                <tr>
                                    <td><strong>#{{ $ticket->ticket_number }}</strong></td>
                                    <td>{{ Str::limit($ticket->subject, 40) }}</td>
                                    <td>
                                        @if($ticket->status === 'open')
                                            <span class="badge badge-success">Open</span>
                                        @elseif($ticket->status === 'pending_reply')
                                            <span class="badge badge-warning">Pending Reply</span>
                                        @else
                                            <span class="badge badge-secondary">Resolved</span>
                                        @endif
                                    </td>
                                    <td>{{ $ticket->created_at->format('M d, Y') }}</td>
                                    <td>{{ $ticket->messages->count() }}</td>
                                    <td>
                                        <a href="{{ route('ticket.view', $ticket->ticket_number) }}" class="btn btn-sm btn-primary">
                                            <i class="fa fa-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $tickets->links() }}
                    </div>
                    @else
                    <div class="alert alert-info" role="alert">
                        <i class="fa fa-info-circle"></i> No tickets found. Create one to get started!
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

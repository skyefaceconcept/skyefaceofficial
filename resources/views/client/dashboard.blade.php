@extends('layouts.app-buzbox')

@section('title', 'Client Dashboard')

@section('additional_css')
<style>
    /* Dashboard Tabs Styling */
    .nav-tabs {
        border-bottom: 2px solid #e3e3e3;
        gap: 0;
    }

    .nav-tabs .nav-item {
        margin-bottom: -2px;
    }

    .nav-tabs .nav-link {
        border: none;
        border-bottom: 3px solid transparent;
        color: #6c757d;
        font-weight: 500;
        padding: 0.75rem 1.5rem;
        transition: all 0.3s ease;
    }

    .nav-tabs .nav-link:hover {
        border-bottom-color: #e3e3e3;
        color: #495057;
    }

    .nav-tabs .nav-link.active {
        border-bottom-color: #007bff;
        color: #007bff;
        background-color: transparent;
    }

    .tab-pane {
        padding-top: 1.5rem;
    }

    .card-header-tabs {
        margin-bottom: -1px;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Dashboard</h1>
    </div>

    <!--***** STATISTICS CARDS *****-->
    <div class="row" id="report1" style="margin-top: 20px; margin-bottom: 30px;">
        <!-- Total Tickets -->
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

        <!-- Open Tickets -->
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

        <!-- Pending Reply -->
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

        <!-- Resolved Tickets -->
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

        <!-- Total Quotes -->
        <div class="col-sm-3">
            <div class="card">
                <div class="card-block">
                    <div class="text-left report1-cont">
                        <h2 class="font-light m-b-0">
                            <i class="fa fa-quote-left text-primary"></i> {{ $totalQuotes }}
                        </h2>
                        <span class="text-muted">Total Quotes</span>
                    </div>
                    <div class="progress mt-3" style="height: 6px;">
                        <div class="progress-bar bg-primary" style="width: 100%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quoted -->
        <div class="col-sm-3">
            <div class="card">
                <div class="card-block">
                    <div class="text-left report1-cont">
                        <h2 class="font-light m-b-0">
                            <i class="fa fa-check text-success"></i> {{ $quotedQuotes }}
                        </h2>
                        <span class="text-muted">Quoted</span>
                    </div>
                    <div class="progress mt-3" style="height: 6px;">
                        <div class="progress-bar bg-success" style="width: {{ ($quotedQuotes / max($totalQuotes, 1)) * 100 }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rejected -->
        <div class="col-sm-3">
            <div class="card">
                <div class="card-block">
                    <div class="text-left report1-cont">
                        <h2 class="font-light m-b-0">
                            <i class="fa fa-times text-danger"></i> {{ $rejectedQuotes }}
                        </h2>
                        <span class="text-muted">Rejected</span>
                    </div>
                    <div class="progress mt-3" style="height: 6px;">
                        <div class="progress-bar bg-danger" style="width: {{ ($rejectedQuotes / max($totalQuotes, 1)) * 100 }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Accepted -->
        <div class="col-sm-3">
            <div class="card">
                <div class="card-block">
                    <div class="text-left report1-cont">
                        <h2 class="font-light m-b-0">
                            <i class="fa fa-thumbs-up text-info"></i> {{ $acceptedQuotes }}
                        </h2>
                        <span class="text-muted">Accepted</span>
                    </div>
                    <div class="progress mt-3" style="height: 6px;">
                        <div class="progress-bar bg-info" style="width: {{ ($acceptedQuotes / max($totalQuotes, 1)) * 100 }}%"></div>
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

    <!-- Quotes & Tickets Tabs -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" id="dashboardTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="quotes-tab" data-toggle="tab" href="#quotes-content" role="tab" aria-controls="quotes-content" aria-selected="true">
                                <i class="fa fa-quote-left"></i> My Quotes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="tickets-tab" data-toggle="tab" href="#tickets-content" role="tab" aria-controls="tickets-content" aria-selected="false">
                                <i class="fa fa-ticket"></i> My Support Tickets
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="tab-content" id="dashboardTabContent">
                    <!-- Quotes Tab -->
                    <div class="tab-pane fade show active" id="quotes-content" role="tabpanel" aria-labelledby="quotes-tab">
                        <div class="card-block">
                            <p class="card-category">View and track your quote requests</p>
                            @if($quotes->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Quote ID</th>
                                            <th>Package</th>
                                            <th>Status</th>
                                            <th>Price</th>
                                            <th>Submitted</th>
                                            <th>Responded</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($quotes as $quote)
                                <tr>
                                    <td><strong>#{{ $quote->id }}</strong></td>
                                    <td>{{ $quote->package ?? 'General' }}</td>
                                    <td>
                                        @if($quote->status === 'new')
                                            <span class="badge badge-primary">New</span>
                                        @elseif($quote->status === 'reviewed')
                                            <span class="badge badge-warning">Under Review</span>
                                        @elseif($quote->status === 'quoted')
                                            <span class="badge badge-success">Quoted</span>
                                        @elseif($quote->status === 'rejected')
                                            <span class="badge badge-danger">Rejected</span>
                                        @elseif($quote->status === 'accepted')
                                            <span class="badge badge-info">Accepted</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($quote->quoted_price)
                                            <strong>${{ number_format($quote->quoted_price, 2) }}</strong>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>{{ $quote->created_at->format('M d, Y') }}</td>
                                    <td>
                                        @if($quote->responded_at)
                                            {{ $quote->responded_at->format('M d, Y') }}
                                        @else
                                            <span class="text-muted">Pending</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('quotes.track') }}?email={{ $quote->email }}&id={{ $quote->id }}" class="btn btn-sm btn-primary" target="_blank">
                                            <i class="fa fa-eye"></i> Track
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                            </div>
                            @else
                            <div class="alert alert-info" role="alert">
                                <i class="fa fa-info-circle"></i> No quotes found. <a href="{{ route('home') }}#services">Request a quote</a> to get started!
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Tickets Tab -->
                    <div class="tab-pane fade" id="tickets-content" role="tabpanel" aria-labelledby="tickets-tab">
                        <div class="card-block">
                            <p class="card-category">View and manage your support tickets</p>
                            @if($tickets->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Ticket #</th>
                                            <th>Subject</th>
                                            <th>Status</th>
                                            <th>Created</th>
                                            <th>Updated</th>
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
                                        @elseif($ticket->status === 'resolved')
                                            <span class="badge badge-secondary">Resolved</span>
                                        @else
                                            <span class="badge badge-danger">Closed</span>
                                        @endif
                                    </td>
                                    <td>{{ $ticket->created_at->format('M d, Y') }}</td>
                                    <td>{{ $ticket->updated_at->format('M d, Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('tickets.show', $ticket->ticket_number) }}" class="btn btn-sm btn-primary">
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
    </div>
</div>
@endsection

@extends('layouts.app-buzbox')

@section('title', 'Ticket: ' . $ticket->ticket_number)

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">Support Ticket: {{ $ticket->ticket_number }}</h1>
                <p class="text-muted">{{ $ticket->subject }}</p>
            </div>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                <i class="fa fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Ticket Information Card -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-block">
                    <div class="row">
                        <div class="col-md-3">
                            <p class="text-muted text-sm">Ticket Number</p>
                            <p class="font-mono font-weight-bold">{{ $ticket->ticket_number }}</p>
                        </div>
                        <div class="col-md-3">
                            <p class="text-muted text-sm">Status</p>
                            <div>
                                @if($ticket->status === 'open')
                                    <span class="badge badge-success">
                                        <i class="fa fa-envelope"></i> Open
                                    </span>
                                @elseif($ticket->status === 'pending_reply')
                                    <span class="badge badge-warning">
                                        <i class="fa fa-clock-o"></i> Awaiting Response
                                    </span>
                                @elseif($ticket->status === 'resolved')
                                    <span class="badge badge-info">
                                        <i class="fa fa-check-circle"></i> Resolved
                                    </span>
                                @else
                                    <span class="badge badge-secondary">
                                        <i class="fa fa-times-circle"></i> Closed
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-3">
                            <p class="text-muted text-sm">Created</p>
                            <p>{{ $ticket->created_at->format('M d, Y H:i') }}</p>
                        </div>
                        <div class="col-md-3">
                            <p class="text-muted text-sm">Last Update</p>
                            <p>{{ $ticket->updated_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
    <div class="alert alert-success alert-with-icon" role="alert">
        <span class="alert-icon"><i class="fa fa-check-circle"></i></span>
        <span class="alert-text"><strong>Success!</strong> {{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-with-icon" role="alert">
        <span class="alert-icon"><i class="fa fa-exclamation-circle"></i></span>
        <span class="alert-text"><strong>Error!</strong> {{ session('error') }}</span>
    </div>
    @endif

    <!-- Messages Section -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Conversation</h3>
                </div>
                <div class="card-block" style="max-height: 400px; overflow-y: auto; background: #f5f5f5;">
                    @if($messages->count() > 0)
                        @foreach($messages as $message)
                        <div class="message-item mb-3 p-3 rounded" style="background: {{ $message->sender_type === 'client' ? '#e3f2fd' : '#fff' }}; border-left: 4px solid {{ $message->sender_type === 'client' ? '#2196f3' : '#999' }};">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <strong class="{{ $message->sender_type === 'client' ? 'text-info' : 'text-muted' }}">
                                        {{ $message->sender_type === 'client' ? 'You' : 'Support Team' }}
                                    </strong>
                                    <small class="text-muted d-block">{{ $message->created_at->format('M d, Y H:i') }}</small>
                                </div>
                            </div>
                            <p class="mt-2 mb-0">{{ $message->message }}</p>
                        </div>
                        @endforeach
                    @else
                    <div class="text-center py-5">
                        <p class="text-muted"><i class="fa fa-inbox"></i> No messages yet in this ticket.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Reply Form -->
    @if($ticket->status !== 'resolved')
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Send a Reply</h3>
                </div>
                <div class="card-block">
                    <form action="{{ route('tickets.reply', $ticket->ticket_number) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="message" class="form-control-label">Your Message</label>
                            <textarea id="message" name="message" rows="5" class="form-control" placeholder="Type your message here..." required></textarea>
                            @error('message')
                            <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-paper-plane"></i> Send Reply
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="alert alert-info" role="alert">
                <i class="fa fa-info-circle"></i> This ticket is closed. You can still view the conversation history above.
            </div>
        </div>
    </div>
    @endif

    <!-- Close Ticket Button -->
    @if($ticket->status !== 'resolved')
    <div class="row mt-4">
        <div class="col-md-12">
            <form action="{{ route('tickets.close', $ticket->ticket_number) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-secondary" onclick="return confirm('Are you sure you want to close this ticket?');">
                    <i class="fa fa-times"></i> Close Ticket
                </button>
            </form>
        </div>
    </div>
    @endif
</div>
@endsection

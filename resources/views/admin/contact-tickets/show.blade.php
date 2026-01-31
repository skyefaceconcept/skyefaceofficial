@extends('layouts.admin.app')

@section('content')
@php $title = 'Ticket: ' . ($ticket->ticket_number ?? 'Ticket'); @endphp

<div class="mb-3">
    <a href="{{ route('admin.contact-tickets.index') }}" class="text-decoration-none">&larr; Back to Tickets</a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-body d-flex justify-content-between align-items-start">
                <div>
                    <h5 class="mb-1">{{ $ticket->ticket_number }}</h5>
                    <p class="mb-0 text-muted">{{ $ticket->subject }}</p>
                </div>
                <div>
                    @if($ticket->status === 'open')
                        <span class="badge badge-danger">Open</span>
                    @elseif($ticket->status === 'pending_reply')
                        <span class="badge badge-warning">Pending Reply</span>
                    @else
                        <span class="badge badge-secondary">Closed</span>
                    @endif
                </div>
            </div>
            <div class="card-body border-top">
                <div class="row text-sm">
                    <div class="col-6">
                        <strong>From</strong>
                        <div>{{ $ticket->user_name }}<br><small class="text-muted">{{ $ticket->user_email }}</small>
                            @if($ticket->phone)<div class="text-muted small">{{ $ticket->phone }}</div>@endif
                        </div>
                    </div>
                    <div class="col-6 text-right">
                        <strong>Created</strong>
                        <div>{{ $ticket->created_at->format('M d, Y H:i') }}
                            @if($ticket->last_reply_date)<div class="text-muted small">Last Reply: {{ $ticket->last_reply_date->format('M d, Y H:i') }}</div>@endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Messages Thread (loaded via AJAX) --}}
        <div id="messagesContainer" class="card mb-3" style="max-height:520px; overflow:auto;">
            <div id="messagesBody" class="card-body">
                <p id="messagesLoading" class="text-center text-muted mb-0">Loading messages...</p>
            </div>
        </div>

        {{-- Reply Form --}}
        @if($ticket->status !== 'closed')
            <div class="card">
                <div class="card-body">
                    <h6 class="mb-3">Send Reply</h6>
                    <form id="replyForm">
                        @csrf
                        <div class="form-group">
                            <textarea name="message" id="replyMessage" rows="4" class="form-control" placeholder="Type your message here..." required></textarea>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button id="sendBtn" type="submit" class="btn btn-primary">Send Reply</button>
                        </div>
                    </form>
                </div>
            </div>
        @else
            <div class="alert alert-secondary">This ticket is closed. No further replies can be sent.</div>
        @endif
    </div>

    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-body">
                <h6 class="mb-3">Actions</h6>
                <div class="d-grid gap-2">
                    @if(!$ticket->assigned_to)
                        <form action="{{ route('admin.contact-tickets.assign', $ticket) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success btn-block">Assign to Me</button>
                        </form>
                    @endif

                    @if($ticket->status !== 'closed')
                        <form id="closeForm" action="{{ route('admin.contact-tickets.close', $ticket) }}" method="POST" onsubmit="return confirm('Are you sure you want to close this ticket?');">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-danger btn-block">Close Ticket</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h6 class="mb-3">Information</h6>
                <p class="mb-1"><strong>Status:</strong> {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}</p>
                <p class="mb-1"><strong>Assigned To:</strong>
                    @if($ticket->assigned_to)
                        {{ $ticket->assignedAdmin->fname ?? 'N/A' }}
                    @else
                        <em class="text-muted">Unassigned</em>
                    @endif
                </p>
                <p class="mb-1"><strong>Messages:</strong> {{ $ticket->messages->count() }}</p>
                <p class="mb-0"><strong>Created:</strong> {{ $ticket->created_at->format('M d, Y') }}</p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.11.3/dist/echo.iife.js"></script>
<script>
    // Global polling flag
    let isPolling = false;
    let lastMessageId = 0;

    // Initialize Echo/Pusher (requires PUSHER_* env configured). Falls back to polling.
    (function() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        const csrfToken = meta ? meta.getAttribute('content') : '{{ csrf_token() }}';
        const pusherKey = '{{ env("PUSHER_APP_KEY") }}';
        const pusherCluster = '{{ env("PUSHER_APP_CLUSTER") }}';

        // Only attempt Echo if Pusher credentials exist
        if (pusherKey && pusherKey.trim() && pusherCluster && pusherCluster.trim()) {
            try {
                Pusher.logToConsole = false;
                window.Echo = new Echo({
                    broadcaster: 'pusher',
                    key: pusherKey,
                    cluster: pusherCluster,
                    forceTLS: true,
                    auth: { headers: { 'X-CSRF-TOKEN': csrfToken } }
                });

                // Subscribe to private channel for this ticket
                window.Echo.private('contact-ticket.{{ $ticket->id }}').listen('.ContactMessageCreated', function(e) {
                    console.log('ContactMessageCreated broadcast received', e);
                    try { fetchMessages(); } catch (err) { console.error('Failed to refresh messages on broadcast', err); }
                });
                console.log('Echo/Pusher initialized successfully');
            } catch (e) {
                console.warn('Echo/Pusher failed, falling back to polling:', e.message);
                startPolling();
            }
        } else {
            console.log('Pusher credentials not configured, using polling fallback');
            startPolling();
        }
    })();

    // Polling fallback for real-time updates
    function startPolling() {
        if (isPolling) return;
        isPolling = true;
        console.log('Starting message polling (2 sec interval)');
        setInterval(function() {
            try {
                fetchMessages();
            } catch (e) {
                console.error('Polling error:', e);
            }
        }, 2000); // Poll every 2 seconds
    }

    console.log('Admin reply script loaded');

    const replyForm = document.getElementById('replyForm');
    console.log('Reply form element:', replyForm);

    if (replyForm) {
        replyForm.addEventListener('submit', sendReply);
        console.log('Event listener attached to replyForm');
    } else {
        console.error('Reply form not found in DOM');
    }

    // Scroll messages to bottom on load
    // Fetch and render messages on load
    $(document).ready(function() {
        fetchMessages();
    });

    function fetchMessages() {
        const body = document.getElementById('messagesBody');
        const loading = document.getElementById('messagesLoading');
        if (loading) loading.style.display = 'block';

        $.ajax({
            url: `/admin/contact-tickets/{{ $ticket->id }}/messages`,
            method: 'GET',
            dataType: 'json',
            success: function(res) {
                if (!res || !res.success) {
                    console.error('Failed to fetch messages', res);
                    if (loading) loading.textContent = 'Failed to load messages';
                    return;
                }

                renderMessages(res.data || []);
            },
            error: function(xhr, status, err) {
                console.error('Error fetching messages:', status, err, xhr && xhr.responseText);
                if (loading) loading.textContent = 'Failed to load messages';
            }
        });
    }

    function renderMessages(messages) {
        const container = document.getElementById('messagesBody');
        if (!container) return;
        container.innerHTML = '';

        if (!messages || messages.length === 0) {
            container.innerHTML = '<p class="text-center text-muted mb-0">No messages yet</p>';
            return;
        }

        messages.forEach(function(message) {
            try {
                const wrapper = document.createElement('div');
                wrapper.className = message.sender_type === 'admin' ? 'd-flex justify-content-end mb-3' : 'd-flex justify-content-start mb-3';

                const displayTime = message.created_at || '';
                const displayMessage = message.message || '';

                const inner = message.sender_type === 'admin'
                    ? `<div class="bg-primary text-white p-3 rounded" style="max-width:80%;"><div class="small font-weight-bold">You (Admin) <span class="text-light small">&middot; ${escapeHtml(displayTime)}</span></div><div class="mt-1">${escapeHtml(displayMessage).replace(/\n/g, '<br>')}</div></div>`
                    : `<div class="bg-light p-3 rounded" style="max-width:80%;"><div class="small font-weight-bold">Client <span class="text-muted small">&middot; ${escapeHtml(displayTime)}</span></div><div class="mt-1">${escapeHtml(displayMessage).replace(/\n/g, '<br>')}</div></div>`;

                wrapper.innerHTML = inner;
                container.appendChild(wrapper);
            } catch (e) {
                console.error('Failed to render message', e, message);
            }
        });

        // scroll to bottom
        const outer = document.getElementById('messagesContainer');
        if (outer) outer.scrollTop = outer.scrollHeight;
    }

    async function sendReply(event) {
        event.preventDefault();
        const btn = document.getElementById('sendBtn');
        const textarea = document.getElementById('replyMessage');
        const form = document.getElementById('replyForm');
        const message = textarea.value.trim();
        if (!message) return;

        btn.disabled = true;

        // Extract CSRF token from the form's hidden input
        const csrfInput = form.querySelector('input[name="_token"]');
        const csrfToken = csrfInput ? csrfInput.value : '';

        console.log('CSRF Token:', csrfToken || 'NOT FOUND');
        console.log('Sending reply to ticket {{ $ticket->id }}: ', message);

        try {
            // Use jQuery AJAX for compatibility
            $.ajax({
                url: `/admin/contact-tickets/{{ $ticket->id }}/reply`,
                method: 'POST',
                dataType: 'json',
                contentType: 'application/json; charset=utf-8',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                data: JSON.stringify({ message }),
                success: function(data) {
                    console.log('AJAX success data:', data);
                        if (data && data.success) {
                        // refresh full message list to ensure order & consistency
                        try { fetchMessages(); } catch (e) { console.error('Failed to refresh messages after send', e); }
                        try { textarea.value = ''; } catch (e) { /* ignore */ }
                    } else {
                        console.error('Server returned success:false', data);
                        alert('Error: ' + (data && data.message ? data.message : 'Unable to send reply'));
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error:', status, error, xhr && xhr.responseText);
                    alert('An error occurred: ' + (xhr && xhr.responseText ? xhr.responseText : error));
                },
                complete: function() {
                    btn.disabled = false;
                }
            });
        } catch (err) {
            console.error('Unexpected error:', err);
            alert('An error occurred: ' + err.message);
            btn.disabled = false;
        }
    }

    function escapeHtml(unsafe) {
        return unsafe
             .replace(/&/g, "&amp;")
             .replace(/</g, "&lt;")
             .replace(/>/g, "&gt;")
             .replace(/\"/g, "&quot;")
             .replace(/'/g, "&#039;");
    }
</script>
@endpush

@endsection

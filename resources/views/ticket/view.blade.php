<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Ticket View - {{ config('app.name') }}</title>
    <link href="{{ asset('buzbox/css/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('buzbox/font-awesome-4.7.0/css/font-awesome.min.css') }}">
    <style>
        body { background-color: #f5f5f5; padding: 20px 0; }
        .ticket-container { max-width: 800px; margin: 0 auto; background: white; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); padding: 30px; }
        .ticket-header { border-bottom: 2px solid #ddd; padding-bottom: 20px; margin-bottom: 20px; }
        .ticket-number { color: #2c3e50; font-size: 24px; font-weight: bold; }
        .ticket-status { display: inline-block; padding: 5px 15px; border-radius: 20px; font-size: 12px; font-weight: bold; margin-left: 10px; }
        .status-open { background-color: #e74c3c; color: white; }
        .status-pending { background-color: #f39c12; color: white; }
        .status-closed { background-color: #95a5a6; color: white; }
        .messages-container { background: #f9f9f9; border: 1px solid #ddd; border-radius: 6px; padding: 20px; min-height: 300px; max-height: 500px; overflow-y: auto; margin-bottom: 20px; }
        .message { margin-bottom: 20px; }
        .message.client { text-align: left; }
        .message.admin { text-align: right; }
        .message-bubble { display: inline-block; max-width: 70%; padding: 12px 15px; border-radius: 8px; word-wrap: break-word; }
        .message.client .message-bubble { background: #e8f4f8; color: #333; border-left: 4px solid #3498db; }
        .message.admin .message-bubble { background: #3498db; color: white; border-right: 4px solid #2980b9; }
        .message-time { font-size: 12px; color: #999; margin-top: 5px; }
        .message.admin .message-time { text-align: right; }
        .reply-form { margin-top: 20px; }
        .form-group label { font-weight: bold; color: #2c3e50; }
        .form-group input, .form-group textarea { border: 1px solid #ddd; }
        .alert { margin-bottom: 20px; }
        .btn-send { background-color: #3498db; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; }
        .btn-send:hover { background-color: #2980b9; }
        .error-message { color: #e74c3c; text-align: center; padding: 40px 20px; }
        .back-link { margin-bottom: 20px; }
        .back-link a { color: #3498db; text-decoration: none; }
        .back-link a:hover { text-decoration: underline; }
        .ticket-info { background: #f0f0f0; padding: 15px; border-radius: 4px; margin-bottom: 20px; }
        .info-row { margin-bottom: 10px; }
        .info-row strong { color: #2c3e50; }
    </style>
</head>
<body>
    <div class="ticket-container">
        <div class="back-link">
            <a href="{{ route('home') }}"><i class="fa fa-arrow-left"></i> Back to Home</a>
        </div>

        @if($ticket)
            <div class="ticket-header">
                <div class="ticket-number">
                    {{ $ticket->ticket_number }}
                    @if($ticket->status === 'open')
                        <span class="ticket-status status-open">Open</span>
                    @elseif($ticket->status === 'pending_reply')
                        <span class="ticket-status status-pending">Pending Reply</span>
                    @else
                        <span class="ticket-status status-closed">Closed</span>
                    @endif
                </div>
                <p style="margin-top: 10px; color: #666;">{{ $ticket->subject }}</p>
            </div>

            <div class="ticket-info">
                <div class="info-row">
                    <strong>From:</strong> {{ $ticket->user_name }} ({{ $ticket->user_email }})
                    @if($ticket->phone)
                        <br><strong>Phone:</strong> {{ $ticket->phone }}
                    @endif
                </div>
                <div class="info-row">
                    <strong>Created:</strong> {{ $ticket->created_at->format('M d, Y H:i') }}
                </div>
                @if($ticket->last_reply_date)
                    <div class="info-row">
                        <strong>Last Update:</strong> {{ $ticket->last_reply_date->format('M d, Y H:i') }}
                    </div>
                @endif
            </div>

            {{-- Messages Thread --}}
            <div id="messagesContainer" class="messages-container">
                <p id="messagesLoading" style="text-align: center; color: #999;">Loading messages...</p>
            </div>

            {{-- Reply Form --}}
            @if($ticket->status !== 'closed')
                <div class="reply-form">
                    <h5>Send a Reply</h5>
                    <form id="clientReplyForm" onsubmit="sendClientReply(event)">
                        <div class="form-group">
                            <label for="clientEmail">Your Email (for verification)</label>
                            <input type="email" class="form-control" id="clientEmail" name="email" value="{{ $ticket->user_email }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="clientMessage">Your Message</label>
                            <textarea class="form-control" id="clientMessage" name="message" rows="4" placeholder="Type your message..." required></textarea>
                        </div>
                        <button type="submit" class="btn-send" id="sendClientBtn">Send Reply</button>
                        <div id="clientReplyMessage" style="margin-top: 15px; display: none;"></div>
                    </form>
                </div>
            @else
                <div class="alert alert-secondary" style="margin-top: 20px;">
                    <strong>Ticket Closed:</strong> This ticket has been closed. If you need further assistance, please submit a new contact form.
                </div>
            @endif
        @else
            <div class="error-message">
                <h4>{{ $error }}</h4>
                <p>Please check your ticket number and try again.</p>
                <p style="margin-top: 20px;"><a href="{{ route('home') }}"><i class="fa fa-arrow-left"></i> Return to Home</a></p>
            </div>
        @endif
    </div>

    <script src="{{ asset('buzbox/js/jquery/jquery.min.js') }}"></script>
    <script>
        // Global polling flag
        let isPolling = false;

        // Initialize message polling on page load
        $(document).ready(function() {
            console.log('Client ticket view loaded, starting message polling');
            fetchMessages();
            startPolling();
        });

        // Polling for real-time message updates
        function startPolling() {
            if (isPolling) return;
            isPolling = true;
            console.log('Starting client message polling (2 sec interval)');
            setInterval(function() {
                try {
                    fetchMessages();
                } catch (e) {
                    console.error('Polling error:', e);
                }
            }, 2000); // Poll every 2 seconds
        }

        // Fetch messages from server
        function fetchMessages() {
            $.ajax({
                url: `/ticket/{{ $ticket->ticket_number }}/messages`,
                method: 'GET',
                dataType: 'json',
                success: function(res) {
                    if (!res || !res.success) {
                        console.error('Failed to fetch messages', res);
                        return;
                    }
                    renderMessages(res.data || []);
                },
                error: function(xhr, status, err) {
                    console.error('Error fetching messages:', status, err);
                }
            });
        }

        // Render all messages in the container
        function renderMessages(messages) {
            const container = document.getElementById('messagesContainer');
            if (!container) return;

            // Store previous scroll position to check if user scrolled up
            const wasScrolledToBottom = container.scrollHeight - container.clientHeight <= container.scrollTop + 1;

            // Clear container
            container.innerHTML = '';

            if (!messages || messages.length === 0) {
                container.innerHTML = '<p style="text-align: center; color: #999;">No messages yet</p>';
                return;
            }

            // Render each message
            messages.forEach(function(msg) {
                try {
                    const messageDiv = document.createElement('div');
                    messageDiv.className = `message ${msg.sender_type === 'admin' ? 'admin' : 'client'}`;

                    const bubble = document.createElement('div');
                    bubble.className = 'message-bubble';
                    bubble.innerHTML = escapeHtml(msg.message).replace(/\n/g, '<br>');

                    const time = document.createElement('div');
                    time.className = 'message-time';
                    time.textContent = (msg.sender_type === 'admin' ? 'Support Team' : 'You') + ' • ' + msg.created_at;

                    messageDiv.appendChild(bubble);
                    messageDiv.appendChild(time);
                    container.appendChild(messageDiv);
                } catch (e) {
                    console.error('Failed to render message', e, msg);
                }
            });

            // Auto-scroll only if user was at the bottom
            if (wasScrolledToBottom) {
                container.scrollTop = container.scrollHeight;
            }
        }

        // Send client reply
        async function sendClientReply(event) {
            event.preventDefault();
            const btn = document.getElementById('sendClientBtn');
            const textarea = document.getElementById('clientMessage');
            const emailInput = document.getElementById('clientEmail');
            const messageDiv = document.getElementById('clientReplyMessage');
            const message = textarea.value.trim();

            if (!message) return;

            btn.disabled = true;
            btn.textContent = 'Sending...';

            try {
                const res = await fetch(`/ticket/{{ $ticket->ticket_number }}/reply`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        message: message,
                        email: emailInput.value
                    })
                });

                const data = await res.json();
                messageDiv.style.display = 'block';

                if (data.success) {
                    messageDiv.className = 'alert alert-success';
                    messageDiv.innerHTML = '<strong>Success!</strong> Your reply has been sent.';
                    textarea.value = '';

                    // Messages will update automatically via polling, no need to manually add
                    console.log('Client reply sent, messages will refresh via polling');
                } else {
                    messageDiv.className = 'alert alert-danger';
                    messageDiv.innerHTML = `<strong>Error!</strong> ${data.message}`;
                }
            } catch (err) {
                messageDiv.style.display = 'block';
                messageDiv.className = 'alert alert-danger';
                messageDiv.innerHTML = `<strong>Error!</strong> An error occurred: ${err.message}`;
            } finally {
                btn.disabled = false;
                btn.textContent = 'Send Reply';
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
</body>
</html>

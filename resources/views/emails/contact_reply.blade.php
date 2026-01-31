<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Reply to Your Support Ticket</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background-color: #f5f5f5; }
        .email-container { max-width: 600px; margin: 20px auto; background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); overflow: hidden; }
        .email-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px 20px; text-align: center; }
        .email-header h1 { margin: 0; font-size: 24px; font-weight: 600; }
        .email-body { padding: 30px 20px; }
        .ticket-info { background: #f9f9f9; border-left: 4px solid #667eea; padding: 15px; margin-bottom: 20px; border-radius: 4px; }
        .ticket-info p { margin: 5px 0; font-size: 14px; }
        .ticket-info strong { color: #667eea; }
        .message-block { background: #f0f4ff; border: 1px solid #e0e7ff; border-radius: 6px; padding: 20px; margin: 20px 0; }
        .message-block p { margin: 0; color: #333; line-height: 1.8; }
        .cta-button { display: inline-block; background: #667eea; color: white; padding: 12px 30px; text-decoration: none; border-radius: 6px; margin: 20px 0; font-weight: 600; transition: background 0.3s; }
        .cta-button:hover { background: #5568d3; }
        .email-footer { background: #f9f9f9; padding: 20px; text-align: center; border-top: 1px solid #eee; font-size: 12px; color: #666; }
        .email-footer p { margin: 5px 0; }
        .divider { height: 1px; background: #eee; margin: 20px 0; }
        .action-box { text-align: center; margin: 30px 0; }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <h1>✉️ New Reply to Your Ticket</h1>
        </div>

        <!-- Body -->
        <div class="email-body">
            <p>Hi <strong>{{ $ticket->user_name ?? 'Valued Customer' }}</strong>,</p>

            <p>Great news! Our support team has replied to your support ticket. See the response below:</p>

            <!-- Ticket Info Box -->
            <div class="ticket-info">
                <p><strong>Ticket Number:</strong> {{ $ticket->ticket_number }}</p>
                <p><strong>Subject:</strong> {{ $ticket->subject }}</p>
                <p><strong>Status:</strong> <span style="color: #f39c12; font-weight: 600;">Pending Your Response</span></p>
            </div>

            <div class="divider"></div>

            <!-- Support Team Reply -->
            <p style="font-size: 14px; color: #666;"><strong>Support Team Reply:</strong></p>
            <div class="message-block">
                {!! nl2br(e($messageModel->message)) !!}
            </div>

            <!-- Call to Action -->
            <div class="action-box">
                <a href="{{ url('/ticket/' . $ticket->ticket_number) }}" class="cta-button">View Full Conversation</a>
                <p style="font-size: 12px; color: #999; margin-top: 10px;">Or copy this link: <br><span style="word-break: break-all;">{{ url('/ticket/' . $ticket->ticket_number) }}</span></p>
            </div>

            <div class="divider"></div>

            <p style="font-size: 13px; color: #666; margin-bottom: 0;">
                You can reply to this ticket anytime by visiting the link above. We're here to help!
            </p>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p><strong>{{ config('app.name') }}</strong> Support Team</p>
            <p>{{ config('app.url') }}</p>
            <p style="margin-top: 15px; font-size: 11px; color: #999;">
                This is an automated message. Please do not reply directly to this email.
            </p>
        </div>
    </div>
</body>
</html>

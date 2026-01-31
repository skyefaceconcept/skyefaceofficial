<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Support Ticket Has Been Closed</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background-color: #f5f5f5; }
        .email-container { max-width: 600px; margin: 20px auto; background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); overflow: hidden; }
        .email-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px 20px; text-align: center; }
        .email-header h1 { margin: 0; font-size: 24px; font-weight: 600; }
        .email-body { padding: 30px 20px; }
        .ticket-info { background: #f9f9f9; border-left: 4px solid #667eea; padding: 15px; margin-bottom: 20px; border-radius: 4px; }
        .ticket-info p { margin: 5px 0; font-size: 14px; }
        .ticket-info strong { color: #667eea; }
        .status-badge { display: inline-block; background: #95a5a6; color: white; padding: 5px 15px; border-radius: 20px; font-size: 13px; font-weight: 600; margin-top: 5px; }
        .cta-button { display: inline-block; background: #667eea; color: white; padding: 12px 30px; text-decoration: none; border-radius: 6px; margin: 20px 0; font-weight: 600; transition: background 0.3s; }
        .cta-button:hover { background: #5568d3; }
        .secondary-button { display: inline-block; background: white; color: #667eea; padding: 12px 30px; text-decoration: none; border: 2px solid #667eea; border-radius: 6px; margin: 20px 0; font-weight: 600; transition: all 0.3s; }
        .secondary-button:hover { background: #f5f7ff; }
        .email-footer { background: #f9f9f9; padding: 20px; text-align: center; border-top: 1px solid #eee; font-size: 12px; color: #666; }
        .email-footer p { margin: 5px 0; }
        .divider { height: 1px; background: #eee; margin: 20px 0; }
        .action-box { text-align: center; margin: 30px 0; }
        .note-box { background: #fff3cd; border: 1px solid #ffeaa7; border-radius: 6px; padding: 15px; margin: 20px 0; font-size: 14px; color: #856404; }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <h1>✓ Ticket Closed</h1>
        </div>

        <!-- Body -->
        <div class="email-body">
            <p>Hi <strong>{{ $ticket->user_name ?? 'Valued Customer' }}</strong>,</p>

            <p>Thank you for reaching out to us! Your support ticket has been closed.</p>

            <!-- Ticket Info Box -->
            <div class="ticket-info">
                <p><strong>Ticket Number:</strong> {{ $ticket->ticket_number }}</p>
                <p><strong>Subject:</strong> {{ $ticket->subject }}</p>
                <p><strong>Status:</strong> <span class="status-badge">Closed</span></p>
            </div>

            <div class="divider"></div>

            <!-- Note -->
            <div class="note-box">
                <strong>📌 Note:</strong> This ticket has been marked as resolved. If you have additional questions or concerns related to this issue, you can reopen it by replying to your ticket.
            </div>

            <!-- Call to Action -->
            <div class="action-box">
                <p style="font-size: 14px; color: #666; margin-bottom: 15px;">Need to provide more information?</p>
                <a href="{{ url('/ticket/' . $ticket->ticket_number) }}" class="cta-button">View Your Ticket</a>
            </div>

            <div class="divider"></div>

            <!-- Additional Help -->
            <div style="background: #f0f4ff; border: 1px solid #e0e7ff; border-radius: 6px; padding: 15px; margin: 20px 0;">
                <p style="margin: 0 0 10px 0; font-weight: 600; color: #667eea;">Need Help With Something Else?</p>
                <p style="margin: 0; font-size: 14px;">If you need further assistance on a different matter, please feel free to submit a new support ticket anytime.</p>
            </div>

            <p style="font-size: 13px; color: #666; margin-bottom: 0; margin-top: 20px;">
                We appreciate your business and look forward to helping you again in the future!
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

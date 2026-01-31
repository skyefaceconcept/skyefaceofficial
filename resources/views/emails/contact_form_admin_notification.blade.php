<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Contact Form Submission</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background-color: #f5f5f5; }
        .email-container { max-width: 600px; margin: 20px auto; background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); overflow: hidden; }
        .email-header { background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%); color: white; padding: 30px 20px; text-align: center; }
        .email-header h1 { margin: 0; font-size: 24px; font-weight: 600; }
        .email-body { padding: 30px 20px; }
        .alert-box { background: #d4edda; border-left: 4px solid #28a745; padding: 15px; margin-bottom: 20px; border-radius: 4px; color: #155724; }
        .alert-box p { margin: 0; }
        .info-box { background: #f9f9f9; border: 1px solid #e0e0e0; padding: 15px; margin: 15px 0; border-radius: 4px; }
        .info-box .label { font-weight: 600; color: #28a745; }
        .message-block { background: #f0f8f0; border: 1px solid #d4edda; border-radius: 6px; padding: 20px; margin: 20px 0; }
        .message-block p { margin: 0; color: #333; line-height: 1.8; white-space: pre-wrap; word-wrap: break-word; }
        .cta-button { display: inline-block; background: #28a745; color: white; padding: 12px 30px; text-decoration: none; border-radius: 6px; margin: 20px 0; font-weight: 600; transition: background 0.3s; }
        .cta-button:hover { background: #218838; }
        .email-footer { background: #f9f9f9; padding: 20px; text-align: center; border-top: 1px solid #eee; font-size: 12px; color: #666; }
        .email-footer p { margin: 5px 0; }
        .divider { height: 1px; background: #eee; margin: 20px 0; }
        .badge { display: inline-block; background: #28a745; color: white; padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; margin: 5px 0; }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <h1>🎯 New Contact Form Submission</h1>
        </div>

        <!-- Body -->
        <div class="email-body">
            <p>A new contact form submission has been received.</p>

            <!-- Alert Box -->
            <div class="alert-box">
                <p><strong>✓ Ticket Created:</strong> Ticket #{{ $ticket->ticket_number }} has been automatically created and the customer has been notified.</p>
            </div>

            <!-- Contact Details -->
            <h3 style="color: #28a745; margin-top: 25px; margin-bottom: 15px;">Customer Information</h3>

            <div class="info-box">
                <p><span class="label">Name:</span> {{ $ticket->user_name }}</p>
            </div>

            <div class="info-box">
                <p><span class="label">Email:</span> <a href="mailto:{{ $ticket->user_email }}" style="color: #28a745; text-decoration: none;">{{ $ticket->user_email }}</a></p>
            </div>

            @if($ticket->phone)
            <div class="info-box">
                <p><span class="label">Phone:</span> <a href="tel:{{ str_replace([' ', '(', ')', '-'], '', $ticket->phone) }}" style="color: #28a745; text-decoration: none;">{{ $ticket->phone }}</a></p>
            </div>
            @endif

            <div class="info-box">
                <p><span class="label">Subject:</span> {{ $ticket->subject }}</p>
            </div>

            <div class="info-box">
                <p><span class="label">Ticket Number:</span> <span style="color: #28a745; font-weight: bold; font-size: 14px;">{{ $ticket->ticket_number }}</span></p>
            </div>

            <div class="info-box">
                <p><span class="label">Submitted:</span> {{ $ticket->created_at->format('F d, Y at h:i A') }}</p>
            </div>

            <div class="divider"></div>

            <!-- Message -->
            <h3 style="color: #28a745; margin-top: 25px; margin-bottom: 15px;">Message</h3>
            <div class="message-block">
                <p>{{ $ticket->messages->first()->message ?? 'No message content' }}</p>
            </div>

            <div class="divider"></div>

            <!-- Action Items -->
            <h3 style="color: #28a745; margin-top: 25px; margin-bottom: 15px;">Next Steps</h3>
            <ol style="color: #555; line-height: 2;">
                <li>Review the customer's message above</li>
                <li>Log in to your admin panel to respond</li>
                <li>The customer will be notified of your reply via email</li>
                <li>Track all communications under ticket #{{ $ticket->ticket_number }}</li>
            </ol>

            <!-- CTA Button -->
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ route('admin.contact.index') ?? '#' }}" class="cta-button">View in Admin Panel</a>
            </div>

            <p style="color: #666; font-size: 14px; background: #f9f9f9; padding: 15px; border-radius: 6px; margin-top: 20px;">
                <strong>Note:</strong> Make sure to respond to this ticket promptly to maintain good customer relations. If this requires urgent attention, contact the customer directly using the information above.
            </p>

        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p><strong>{{ config('company.name', 'Skyeface Digital') }}</strong> - Support Team</p>
            <p style="margin-top: 15px; color: #999; font-size: 11px;">
                This is an automated notification. Do not reply to this email.
            </p>
        </div>
    </div>
</body>
</html>

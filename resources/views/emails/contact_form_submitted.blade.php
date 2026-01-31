<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form Confirmation</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background-color: #f5f5f5; }
        .email-container { max-width: 600px; margin: 20px auto; background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); overflow: hidden; }
        .email-header { background: linear-gradient(135deg, #007bff 0%, #0056b3 100%); color: white; padding: 30px 20px; text-align: center; }
        .email-header h1 { margin: 0; font-size: 24px; font-weight: 600; }
        .email-body { padding: 30px 20px; }
        .ticket-info { background: #f9f9f9; border-left: 4px solid #007bff; padding: 15px; margin-bottom: 20px; border-radius: 4px; }
        .ticket-info p { margin: 8px 0; font-size: 14px; }
        .ticket-info strong { color: #007bff; }
        .message-block { background: #f0f4ff; border: 1px solid #e0e7ff; border-radius: 6px; padding: 20px; margin: 20px 0; }
        .message-block p { margin: 0; color: #333; line-height: 1.8; }
        .cta-button { display: inline-block; background: #007bff; color: white; padding: 12px 30px; text-decoration: none; border-radius: 6px; margin: 20px 0; font-weight: 600; transition: background 0.3s; }
        .cta-button:hover { background: #0056b3; }
        .email-footer { background: #f9f9f9; padding: 20px; text-align: center; border-top: 1px solid #eee; font-size: 12px; color: #666; }
        .email-footer p { margin: 5px 0; }
        .divider { height: 1px; background: #eee; margin: 20px 0; }
        .action-box { text-align: center; margin: 30px 0; }
        .highlight { background: #fff3cd; padding: 15px; border-radius: 6px; border-left: 4px solid #ffc107; margin: 20px 0; }
        .highlight p { margin: 0; color: #856404; }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <h1>✓ Message Received</h1>
        </div>

        <!-- Body -->
        <div class="email-body">
            <p>Hi <strong>{{ $ticket->user_name ?? 'Valued Customer' }}</strong>,</p>

            <p>Thank you for reaching out to us! We have successfully received your contact form submission and appreciate you getting in touch.</p>

            <!-- Ticket Info Box -->
            <div class="ticket-info">
                <p><strong>Your Ticket Number:</strong> <span style="color: #007bff; font-weight: bold; font-size: 16px;">{{ $ticket->ticket_number }}</span></p>
                <p><strong>Subject:</strong> {{ $ticket->subject }}</p>
                <p><strong>Submitted:</strong> {{ $ticket->created_at->format('F d, Y at h:i A') }}</p>
                <p><strong>Status:</strong> <span style="color: #28a745; font-weight: 600;">Open</span></p>
            </div>

            <div class="divider"></div>

            <!-- Important Info -->
            <div class="highlight">
                <p>📌 <strong>Save Your Ticket Number:</strong> Please save the ticket number above to track your support request. You'll need it to check the status of your inquiry.</p>
            </div>

            <!-- Message Summary -->
            <p><strong>Your Message Summary:</strong></p>
            <div class="message-block">
                <p>{{ \Illuminate\Support\Str::limit($ticket->messages->first()->message ?? 'Your message', 300) }}</p>
            </div>

            <div class="divider"></div>

            <!-- What Happens Next -->
            <h3 style="color: #007bff; margin-top: 25px;">What Happens Next?</h3>
            <ol style="color: #555; line-height: 2;">
                <li>Our support team will review your message</li>
                <li>We'll respond to you as soon as possible</li>
                <li>You'll receive email notifications for any replies</li>
                <li>You can track your ticket status anytime using your ticket number</li>
            </ol>

            <!-- CTA Button -->
            <div class="action-box">
                <a href="{{ url('/contact') }}" class="cta-button">View Your Ticket</a>
            </div>

            <p style="color: #666; font-size: 14px;">If you have any additional information to add or urgent concerns, please reply to this email or contact us directly using the information below.</p>

        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p><strong>{{ config('company.name', 'Skyeface Digital') }}</strong></p>
            <p>{{ config('company.address', 'Our Address') }}</p>
            <p>
                <strong>Phone:</strong> <a href="tel:{{ str_replace([' ', '(', ')', '-'], '', config('company.phone', '')) }}" style="color: #007bff; text-decoration: none;">{{ config('company.phone', 'Contact Number') }}</a>
            </p>
            <p>
                <strong>Email:</strong> <a href="mailto:{{ config('company.email', config('mail.from.address')) }}" style="color: #007bff; text-decoration: none;">{{ config('company.email', config('mail.from.address')) }}</a>
            </p>
            <div style="margin-top: 15px; color: #999; font-size: 11px;">
                <p>This is an automated message. Please do not reply directly to this email.</p>
            </div>
        </div>
    </div>
</body>
</html>

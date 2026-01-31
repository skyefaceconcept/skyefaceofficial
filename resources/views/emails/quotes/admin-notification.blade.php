<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Quote Request</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background-color: #f5f5f5; }
        .email-container { max-width: 600px; margin: 20px auto; background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); overflow: hidden; }
        .email-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px 20px; text-align: center; }
        .email-header h1 { margin: 0; font-size: 24px; font-weight: 600; }
        .email-body { padding: 30px 20px; }
        .info-box { background: #f9f9f9; border-left: 4px solid #667eea; padding: 15px; margin-bottom: 20px; border-radius: 4px; }
        .info-box p { margin: 5px 0; font-size: 14px; }
        .info-box strong { color: #667eea; display: block; margin-bottom: 5px; }
        .details-block { background: #f0f4ff; border: 1px solid #e0e7ff; border-radius: 6px; padding: 20px; margin: 20px 0; }
        .details-block p { margin: 0; color: #333; line-height: 1.8; white-space: pre-wrap; word-wrap: break-word; }
        .cta-button { display: inline-block; background: #667eea; color: white; padding: 12px 30px; text-decoration: none; border-radius: 6px; margin: 20px 0; font-weight: 600; transition: background 0.3s; }
        .cta-button:hover { background: #5568d3; }
        .email-footer { background: #f9f9f9; padding: 20px; text-align: center; border-top: 1px solid #eee; font-size: 12px; color: #666; }
        .email-footer p { margin: 5px 0; }
        .divider { height: 1px; background: #eee; margin: 20px 0; }
        .action-box { text-align: center; margin: 30px 0; }
        .quote-header { background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin-bottom: 20px; border-radius: 4px; }
        .quote-header strong { color: #856404; }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <h1>📋 New Quote Request</h1>
        </div>

        <!-- Body -->
        <div class="email-body">
            <h2>A new quote request requires your attention</h2>

            <div class="quote-header">
                <strong>Quote ID:</strong> #{{ $quote->id }}<br>
                <strong>Submitted:</strong> {{ $quote->created_at->format('M d, Y \a\t H:i') }}
            </div>

            <h3>Customer Information</h3>
            <div class="info-box">
                <strong>Name:</strong> {{ $quote->name }}<br>
                <strong>Email:</strong> {{ $quote->email }}<br>
                <strong>Phone:</strong> {{ $quote->phone ?? 'Not provided' }}<br>
                <strong>Package:</strong> {{ $quote->package ?? 'General' }}<br>
                <strong>IP Address:</strong> {{ $quote->ip_address }}
            </div>

            <h3>Project Details</h3>
            <div class="details-block">
                {{ $quote->details }}
            </div>

            <div class="action-box">
                <a href="{{ route('admin.quotes.show', $quote->id) }}" class="cta-button">View Quote Details</a>
            </div>

            <div class="divider"></div>

            <p style="color: #999; font-size: 12px;">This is an automated notification. Please respond to the customer within 24 hours.</p>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p><strong>{{ config('app.name') }}</strong></p>
            <p>Professional Digital Solutions</p>
            <p>{{ config('app.url') }}</p>
        </div>
    </div>
</body>
</html>

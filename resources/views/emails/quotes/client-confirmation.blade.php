<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quote Request Confirmation</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background-color: #f5f5f5; }
        .email-container { max-width: 600px; margin: 20px auto; background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); overflow: hidden; }
        .email-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px 20px; text-align: center; }
        .email-header h1 { margin: 0; font-size: 24px; font-weight: 600; }
        .email-body { padding: 30px 20px; }
        .info-box { background: #f9f9f9; border-left: 4px solid #667eea; padding: 15px; margin-bottom: 20px; border-radius: 4px; }
        .info-box p { margin: 5px 0; font-size: 14px; }
        .info-box strong { color: #667eea; display: block; margin-bottom: 5px; }
        .quote-header { background: #e8f4f8; border-left: 4px solid #17a2b8; padding: 15px; margin-bottom: 20px; border-radius: 4px; }
        .quote-header strong { color: #155e6f; }
        .steps-box { background: #f0fff4; border-left: 4px solid #28a745; padding: 20px; margin: 20px 0; border-radius: 4px; }
        .steps-box h3 { margin-top: 0; color: #155f35; }
        .steps-box ol { margin: 10px 0; padding-left: 20px; }
        .steps-box li { margin-bottom: 10px; }
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
            <h1>✓ Thank You for Your Quote Request</h1>
        </div>

        <!-- Body -->
        <div class="email-body">
            <h2>Dear {{ $quote->name }},</h2>

            <p>Thank you for submitting a quote request! We have received your inquiry and our team will review it shortly.</p>

            <div class="quote-header">
                <strong>Quote ID:</strong> #{{ $quote->id }}<br>
                <strong>Package:</strong> {{ $quote->package ?? 'General Service' }}<br>
                <strong>Submitted:</strong> {{ $quote->created_at->format('M d, Y \a\t H:i') }}
            </div>

            <div class="steps-box">
                <h3>Next Steps</h3>
                <ol>
                    <li>Our team will review your project details</li>
                    <li>We'll respond with a detailed quote within 1-2 business days</li>
                    <li>You'll receive an email with our proposal and any questions we might have</li>
                </ol>
            </div>

            <h3>Track Your Quote</h3>
            <p>You can track the status of your quote anytime using:</p>
            <div class="info-box">
                <strong>Quote ID:</strong> {{ $quote->id }}<br>
                <strong>Email:</strong> {{ $quote->email }}
            </div>

            <div class="action-box">
                <a href="{{ route('quotes.track', ['email' => $quote->email, 'id' => $quote->id]) }}" class="cta-button">Track Your Quote</a>
            </div>

            <p>If you have any questions in the meantime, feel free to reply to this email.</p>

            <div class="divider"></div>

            <p style="color: #999; font-size: 12px;">This is an automated confirmation. Our team will contact you shortly with more information.</p>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p><strong>{{ config('company.name') ?? config('app.name') }}</strong></p>
            <p>Professional Digital Solutions</p>
            <p>{{ config('app.url') }}</p>
        </div>
    </div>
</body>
</html>

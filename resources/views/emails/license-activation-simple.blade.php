<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; color: #333; line-height: 1.6; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #f8f9fa; padding: 20px; border-radius: 6px; margin-bottom: 20px; }
        .section { margin-bottom: 20px; }
        .section-title { font-weight: bold; font-size: 16px; margin-bottom: 10px; }
        .panel { background: #f8fafc; padding: 12px 16px; border-radius: 6px; margin: 12px 0; }
        .panel-content { font-size: 18px; font-weight: bold; word-break: break-all; }
        .table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        .table td { border: 1px solid #ddd; padding: 8px; }
        .footer { font-size: 12px; color: #666; margin-top: 20px; padding-top: 20px; border-top: 1px solid #ddd; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Your License Code & Activation Instructions</h1>
        </div>

        @if($order)
        <p>Hello {{ $order->customer_name }},</p>
        @else
        <p>Hello,</p>
        @endif

        <p>Thank you for choosing <strong>{{ $license->application_name ?? 'our service' }}</strong>! Your license has been generated and is ready for activation.</p>

        <div class="section">
            <div class="section-title">Your License Code</div>
            <p>Please keep this code safe and secure:</p>
            <div class="panel">
                <div class="panel-content">{{ $license->license_code }}</div>
            </div>
            <p><strong>Valid Until:</strong> {{ $license->expiry_date->format('F d, Y') }}</p>
        </div>

        <hr>

        <div class="section">
            <div class="section-title">{{ $activationInstructions['title'] }}</div>
            <p>Follow these steps to activate your license:</p>
            <ol>
                @foreach($activationInstructions['steps'] as $step)
                <li>{!! $step !!}</li>
                @endforeach
            </ol>
        </div>

        <hr>

        @if($order && $order->portfolio)
        <div class="section">
            <div class="section-title">License Details</div>
            <table class="table">
                <tr>
                    <td><strong>Product</strong></td>
                    <td>{{ $order->portfolio->title }}</td>
                </tr>
                <tr>
                    <td><strong>License Code</strong></td>
                    <td>{{ $license->license_code }}</td>
                </tr>
                <tr>
                    <td><strong>Expiry Date</strong></td>
                    <td>{{ $license->expiry_date->format('F d, Y') }}</td>
                </tr>
                <tr>
                    <td><strong>Order ID</strong></td>
                    <td>#{{ $order->id }}</td>
                </tr>
                <tr>
                    <td><strong>Status</strong></td>
                    <td>Active</td>
                </tr>
            </table>
        </div>
        @endif

        <div class="section">
            <div class="section-title">Important Reminders</div>
            <ul>
                <li>✓ <strong>Do not share</strong> your license code publicly</li>
                <li>✓ <strong>Keep it secure</strong> - treat it like a password</li>
                <li>✓ <strong>One code per person</strong> - each license is unique</li>
                <li>✓ <strong>Not transferable</strong> - tied to your order only</li>
            </ul>
        </div>

        <div class="section">
            <div class="section-title">Activation API (For Developers)</div>
            <p>If you're integrating license verification programmatically, use the API endpoint:</p>
            <pre>POST /api/license/activate
Content-Type: application/json

{
  "license_code": "{{ $license->license_code }}"
}</pre>
        </div>

        <div class="section">
            <div class="section-title">Troubleshooting</div>
            <ul>
                <li><strong>License not recognized?</strong> Double-check that you've entered the code exactly as shown (case-sensitive)</li>
                <li><strong>Activation failed?</strong> Ensure you have internet connectivity during activation</li>
                <li><strong>Still having issues?</strong> Contact our support team immediately</li>
            </ul>
        </div>

        <div class="section">
            <div class="section-title">Need Support?</div>
            <p>Our support team is here to help! Contact us at:</p>
            <ul>
                <li><strong>Email:</strong> {{ config('mail.from.address') }}</li>
                <li><strong>Response Time:</strong> Within 24 hours</li>
            </ul>
        </div>

        <p>We're glad to have you as a customer!<br>
        {{ config('app.name') }} Team</p>

        <div class="footer">
            <p>This is an automated email. Please do not reply to this message. For support inquiries, use the contact information above.</p>
        </div>
    </div>
</body>
</html>

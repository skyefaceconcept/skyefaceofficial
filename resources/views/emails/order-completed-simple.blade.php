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
        .footer { font-size: 12px; color: #666; margin-top: 20px; padding-top: 20px; border-top: 1px solid #ddd; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Order Completed Successfully!</h1>
        </div>

        <p>Hello {{ $order->customer_name }},</p>

        <p>Great news! Your payment has been processed successfully and your order is complete.</p>

        <div class="section">
            <div class="section-title">Order Summary</div>
            <p><strong>Invoice Number:</strong> {{ $order->invoice_number }}</p>
            @if($portfolio)
            <p><strong>Product:</strong> {{ $portfolio->title }}</p>
            @endif
            <p><strong>Amount Paid:</strong> {{ config('app.currency', '$') }}{{ number_format($order->amount, 2) }}</p>
            <p><strong>Order ID:</strong> #{{ $order->id }}</p>
            @if($order->transaction_reference)
            <p><strong>Transaction Reference:</strong> {{ $order->transaction_reference }}</p>
            @endif
            @if($order->completed_at)
            <p><strong>Completed At:</strong> {{ $order->completed_at->format('F d, Y H:i A') }}</p>
            @endif
        </div>

        @if($license)
        <div class="section">
            <div class="section-title">Your License Code</div>
            <p>Your license code is ready to use:</p>
            <div class="panel">
                <div class="panel-content">{{ $license->license_code }}</div>
            </div>
            <p><strong>License Information:</strong></p>
            <ul>
                <li><strong>Application:</strong> {{ $license->application_name }}</li>
                <li><strong>Valid Until:</strong> {{ $license->expiry_date->format('F d, Y') }}</li>
                <li><strong>Status:</strong> Active</li>
            </ul>
        </div>

        <div class="section">
            <div class="section-title">Activation Instructions</div>
            <p>Please refer to the separate email titled "Your License Code - {{ $license->application_name }}" for detailed activation instructions based on your product type.</p>
        </div>

        <div class="section">
            <div class="section-title">Important Notes</div>
            <ul>
                <li><strong>Do not share</strong> this license code with anyone else</li>
                <li>Keep this code <strong>safe and secure</strong> for future reference</li>
                <li>You can use this code to <strong>activate the application</strong> on your device/server</li>
            </ul>
        </div>
        @endif

        <div class="section">
            <p>If you encounter any issues during activation or need technical support, please don't hesitate to contact us at <strong>{{ config('mail.from.address') }}</strong>.</p>
        </div>

        <p>We appreciate your business!<br>
        {{ config('app.name') }} Team</p>

        <div class="footer">
            <p>This is an automated email. Please do not reply to this message. For inquiries, contact our support team.</p>
        </div>
    </div>
</body>
</html>

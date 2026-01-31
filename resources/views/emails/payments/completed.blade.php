<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; background: #f5f5f5; }
        .container { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; text-align: center; }
        .header h1 { margin: 0; font-size: 28px; }
        .details-table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        .details-table td { padding: 12px; border-bottom: 1px solid #e9ecef; }
        .label { font-weight: 600; color: #555; }
        .value { color: #333; }
        .amount { font-size: 20px; font-weight: bold; color: #28a745; }
        .footer { text-align: center; margin-top: 20px; padding-top: 20px; border-top: 1px solid #e9ecef; font-size: 12px; color: #999; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header"><h1>✓ Payment Received</h1></div>
        <p>Hello {{ $customerName }},</p>
        <p>Thank you for your payment! We've successfully received your payment and your invoice has been generated.</p>
        <h2 style="color: #333;">Payment Details</h2>
        <table class="details-table">
            <tr><td class="label">Invoice Number:</td><td class="value">{{ $invoiceNumber ?? 'N/A' }}</td></tr>
            @if($quote)
            <tr><td class="label">Quote Number:</td><td class="value">#{{ $quote->id }}</td></tr>
            <tr><td class="label">Service:</td><td class="value">{{ $quote->package ?? 'Professional Service' }}</td></tr>
            @endif
            <tr><td class="label">Amount Paid:</td><td class="value amount">{{ $currency }} {{ number_format($amount, 2) }}</td></tr>
            <tr><td class="label">Reference:</td><td class="value">{{ $reference }}</td></tr>
            <tr><td class="label">Transaction ID:</td><td class="value">{{ $transactionId }}</td></tr>
            <tr><td class="label">Date:</td><td class="value">{{ $payment->created_at->format('F d, Y \a\t g:i A') }}</td></tr>
        </table>
        <p>Our team will begin work on your project shortly. You'll receive regular updates on your progress.</p>
        <p>If you have questions, contact our support at {{ config('company.support_email', 'support@skyeface.com') }}.</p>
        <p>We appreciate your business!</p>
        <div class="footer">© {{ date('Y') }} {{ config('app.name', 'Skyeface') }}. All rights reserved.</div>
    </div>
</body>
</html>

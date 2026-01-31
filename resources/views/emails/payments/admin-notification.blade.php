<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; background: #f5f5f5; }
        .container { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #007bff 0%, #0056b3 100%); color: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; text-align: center; }
        .header h1 { margin: 0; font-size: 28px; }
        .details-table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        .details-table td { padding: 12px; border-bottom: 1px solid #e9ecef; }
        .label { font-weight: 600; color: #555; }
        .value { color: #333; }
        .amount { font-size: 18px; font-weight: bold; color: #28a745; }
        .alert { background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 20px 0; border-radius: 4px; }
        .button { display: inline-block; background: #007bff; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; margin: 20px 0; font-weight: 600; }
        .footer { text-align: center; margin-top: 20px; padding-top: 20px; border-top: 1px solid #e9ecef; font-size: 12px; color: #999; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header"><h1>💳 New Payment Received</h1></div>
        <p>A payment has been successfully processed and is ready for action.</p>
        <h2 style="color: #333;">Payment Information</h2>
        <table class="details-table">
            <tr><td class="label">Invoice Number:</td><td class="value">{{ $invoiceNumber ?? 'N/A' }}</td></tr>
            <tr><td class="label">Amount:</td><td class="value amount">{{ $currency }} {{ number_format($amount, 2) }}</td></tr>
            <tr><td class="label">Reference:</td><td class="value">{{ $reference }}</td></tr>
            <tr><td class="label">Transaction ID:</td><td class="value">{{ $transactionId }}</td></tr>
            <tr><td class="label">Date:</td><td class="value">{{ $payment->created_at->format('F d, Y \a\t g:i A') }}</td></tr>
        </table>
        <h2 style="color: #333;">Client Details</h2>
        <table class="details-table">
            <tr><td class="label">Name:</td><td class="value">{{ $customerName }}</td></tr>
            <tr><td class="label">Email:</td><td class="value">{{ $customerEmail }}</td></tr>
            @if($quote)
            <tr><td class="label">Quote ID:</td><td class="value">#{{ $quote->id }}</td></tr>
            <tr><td class="label">Service:</td><td class="value">{{ $quote->package ?? 'Professional Service' }}</td></tr>
            <tr><td class="label">Status:</td><td class="value">{{ $quote->status }}</td></tr>
            @endif
        </table>
        <div class="alert"><strong>⚠️ Action Required:</strong> Please begin work on the client's project and keep them updated on progress.</div>
        <div style="text-align: center;"><a href="{{ route('quotes.show', $quote->id) }}" class="button">View Quote Details</a></div>
        <div class="footer">Payment Notification ID: <strong>{{ $reference }}</strong><br>© {{ date('Y') }} {{ config('app.name', 'Skyeface') }}. All rights reserved.</div>
    </div>
</body>
</html>

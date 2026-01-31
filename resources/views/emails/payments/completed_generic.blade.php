<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .details-table th {
            background: #f8f9fa;
            padding: 12px;
            text-align: left;
            font-weight: 600;
            border-bottom: 2px solid #e9ecef;
        }
        .details-table td {
            padding: 12px;
            border-bottom: 1px solid #e9ecef;
        }
        .details-table tr:last-child td {
            border-bottom: none;
        }
        .label {
            font-weight: 600;
            color: #555;
        }
        .value {
            color: #333;
        }
        .status-badge {
            display: inline-block;
            background: #28a745;
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
            font-size: 12px;
            color: #999;
        }
        .amount {
            font-size: 20px;
            font-weight: bold;
            color: #28a745;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✓ Payment Received</h1>
        </div>

        <p>Hello {{ $customerName }},</p>

        <p>Thank you for your payment! We've successfully received your payment and your transaction has been recorded in our system.</p>

        <h2 style="color: #333; margin-top: 25px;">Payment Details</h2>

        <table class="details-table">
            <tr>
                <td class="label">Invoice Number:</td>
                <td class="value">{{ $invoiceNumber ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Payment ID:</td>
                <td class="value">#{{ $payment->id }}</td>
            </tr>
            <tr>
                <td class="label">Amount Paid:</td>
                <td class="value amount">{{ $currency }} {{ number_format($amount, 2) }}</td>
            </tr>
            <tr>
                <td class="label">Reference:</td>
                <td class="value">{{ $reference }}</td>
            </tr>
            <tr>
                <td class="label">Transaction ID:</td>
                <td class="value">{{ $transactionId }}</td>
            </tr>
            <tr>
                <td class="label">Date & Time:</td>
                <td class="value">{{ $payment->created_at->format('F d, Y \a\t g:i A') }}</td>
            </tr>
            <tr>
                <td class="label">Status:</td>
                <td class="value">
                    <span class="status-badge">✓ Completed</span>
                </td>
            </tr>
            <tr>
                <td class="label">Customer:</td>
                <td class="value">{{ $customerName }}<br>{{ $customerEmail }}</td>
            </tr>
        </table>

        <div style="background: #f0f7ff; padding: 15px; border-left: 4px solid #0066cc; margin: 20px 0; border-radius: 4px;">
            <p style="margin: 0; color: #0c3475;">
                <strong>📧 You will receive additional order confirmation and license activation emails shortly.</strong>
            </p>
        </div>

        <p>If you have any questions about your payment or need assistance, please don't hesitate to contact our support team at <strong>{{ config('company.support_email', 'support@skyeface.com') }}</strong>.</p>

        <p>Thank you for your business!</p>

        <p style="color: #999; font-size: 13px;">
            Best regards,<br>
            <strong>{{ config('app.name', 'Skyeface') }} Support Team</strong>
        </p>

        <div class="footer">
            © {{ date('Y') }} {{ config('app.name', 'Skyeface') }}. All rights reserved.
        </div>
    </div>
</body>
</html>

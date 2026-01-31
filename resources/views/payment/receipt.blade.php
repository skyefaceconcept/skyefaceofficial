<!-- Payment Receipt Template (Print-Friendly) -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt - {{ $payment->reference }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        html, body {
            width: 100%;
            height: 100%;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background: #f5f5f5;
            padding: 20px;
        }
        .receipt-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 40px;
            background: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .receipt-header {
            text-align: center;
            border-bottom: 2px solid #2196F3;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .company-logo {
            font-size: 24px;
            font-weight: bold;
            color: #2196F3;
            margin-bottom: 10px;
        }
        .company-logo img {
            max-width: 150px;
            height: auto;
            margin-bottom: 10px;
        }
        .receipt-title {
            font-size: 28px;
            font-weight: bold;
            color: #333;
            margin: 15px 0 5px 0;
        }
        .receipt-number {
            color: #666;
            font-size: 14px;
        }
        .receipt-meta {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }
        .meta-column {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding: 0 15px;
        }
        .meta-column:first-child {
            padding-left: 0;
        }
        .meta-column:last-child {
            padding-right: 0;
        }
        .meta-label {
            font-weight: bold;
            color: #2196F3;
            font-size: 12px;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .meta-value {
            color: #333;
            font-size: 14px;
            margin-bottom: 15px;
            word-break: break-word;
        }
        .section {
            margin-bottom: 30px;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #2196F3;
            text-transform: uppercase;
            border-bottom: 1px solid #e0e0e0;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }
        .detail-row {
            display: table;
            width: 100%;
            margin-bottom: 10px;
        }
        .detail-label {
            display: table-cell;
            font-weight: 600;
            color: #333;
            width: 40%;
        }
        .detail-value {
            display: table-cell;
            text-align: right;
            color: #555;
        }
        .amount-section {
            background: #f5f5f5;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .total-row {
            display: table;
            width: 100%;
            font-size: 18px;
            font-weight: bold;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 2px solid #2196F3;
        }
        .total-label {
            display: table-cell;
            color: #333;
        }
        .total-amount {
            display: table-cell;
            text-align: right;
            color: #2196F3;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-completed {
            background: #e8f5e9;
            color: #2e7d32;
        }
        .status-pending {
            background: #fff3e0;
            color: #f57c00;
        }
        .status-failed {
            background: #ffebee;
            color: #c62828;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
            text-align: center;
            font-size: 12px;
            color: #999;
        }
        .notice {
            background: #e3f2fd;
            border-left: 4px solid #2196F3;
            padding: 15px;
            margin: 20px 0;
            font-size: 13px;
            color: #1565c0;
        }
        .print-controls {
            text-align: center;
            margin-bottom: 30px;
            padding: 15px;
            background: #e3f2fd;
            border-radius: 5px;
        }
        .print-controls button {
            background: #2196F3;
            color: white;
            border: none;
            padding: 10px 20px;
            margin: 0 10px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }
        .print-controls button:hover {
            background: #1976D2;
        }

        @page {
            size: A4;
            margin: 12mm;
        }

        @media print {
            /* Ask the browser to preserve colors where possible */
            * { -webkit-print-color-adjust: exact; print-color-adjust: exact; }

            body {
                background: white;
                padding: 0;
            }

            /* Keep a subtle shadow so the printed page area is visible in print preview */
            /* Force A4 single page: width and height matching A4 with margins from @page */
            .receipt-container {
                width: 210mm;
                height: 297mm;
                /* account for @page margins visually by using same padding inside container */
                padding: 12mm;
                margin: 0 auto;
                background: #fff;
                box-shadow: 0 8px 24px rgba(0,0,0,0.25);
                filter: drop-shadow(0 8px 24px rgba(0,0,0,0.25));
                overflow: hidden;
                page-break-after: avoid;
                page-break-inside: avoid;
                break-inside: avoid;
            }

            /* Prevent internal elements from forcing page breaks */
            .receipt-container, .receipt-container * {
                page-break-inside: avoid;
                break-inside: avoid;
            }

            /* Slightly reduce font size for print to help fit content on one page */
            body, .receipt-container {
                font-size: 12px;
                line-height: 1.4;
            }

            /* Limit image heights so logos don't overflow */
            .company-logo img { max-height: 40px; max-width: 140px; height: auto; }

            .print-controls {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="print-controls">
        <button onclick="window.print()"><i class="fa fa-print"></i> Print / Save as PDF</button>
        <button onclick="window.close()">Close</button>
    </div>

    <div class="receipt-container">
        <!-- Header -->
        <div class="receipt-header">
            <div class="company-logo" style="background-color: #333">
                @if($settings && $settings->logo)
                    <img src="{{ url('storage/' . $settings->logo) }}" alt="Company Logo" style="max-width: 150px; height: 45px;">
                    <img src="{{ url('storage/' . $settings->name_logo) }}" alt="Company Logo" style="max-width: 150px; height: auto;">
                @elseif($settings && $settings->name_logo)
                    <img src="{{ url('storage/' . $settings->name_logo) }}" alt="Company Logo" style="max-width: 150px; height: auto;">
                @else
                    <div style="font-size: 24px; font-weight: bold; color: #2196F3;">📋</div>
                @endif
            </div>
            <div class="receipt-title">Payment Receipt</div>
            <div class="receipt-number">{{ $payment->reference }}</div>
        </div>

        <!-- Payment Info -->
        <div class="receipt-meta">
            <div class="meta-column">
                <div class="meta-label">Customer Name</div>
                <div class="meta-value">{{ $payment->customer_name }}</div>

                <div class="meta-label">Email</div>
                <div class="meta-value">{{ $payment->customer_email }}</div>
            </div>
            <div class="meta-column">
                <div class="meta-label">Payment Date</div>
                <div class="meta-value">{{ $payment->paid_at ? $payment->paid_at->format('M d, Y g:i A') : 'Pending' }}</div>

                <div class="meta-label">Reference</div>
                <div class="meta-value">{{ $payment->reference }}</div>
            </div>
        </div>

        <!-- Amount Section -->
        <div class="amount-section">
            <div class="detail-row">
                <div class="detail-label">Amount</div>
                <div class="detail-value">{{ $payment->currency }} {{ number_format($payment->amount, 2) }}</div>
            </div>
            @if($quote)
            <div class="detail-row">
                <div class="detail-label">Quote #</div>
                <div class="detail-value">#{{ $quote->id }}</div>
            </div>
            @endif
            <div class="detail-row">
                <div class="detail-label">Payment Method</div>
                <div class="detail-value">{{ $payment->payment_method ?? 'N/A' }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Status</div>
                <div class="detail-value">
                    <span class="status-badge status-{{ strtolower($payment->status) }}">
                        {{ ucfirst($payment->status) }}
                    </span>
                </div>
            </div>
            <div class="total-row">
                <div class="total-label">Total Amount</div>
                <div class="total-amount">{{ $payment->currency }} {{ number_format($payment->amount, 2) }}</div>
            </div>
        </div>

        <!-- Payment Details -->
        <div class="section">
            <div class="section-title">Payment Details</div>
            <div class="detail-row">
                <div class="detail-label">Transaction ID</div>
                <div class="detail-value">{{ $payment->transaction_id ?? 'N/A' }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Payment Source</div>
                <div class="detail-value">{{ $payment->payment_source ?? 'Flutterwave' }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Currency</div>
                <div class="detail-value">{{ $payment->currency }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Created</div>
                <div class="detail-value">{{ $payment->created_at->format('M d, Y g:i A') }}</div>
            </div>
            @if($payment->paid_at)
            <div class="detail-row">
                <div class="detail-label">Paid</div>
                <div class="detail-value">{{ $payment->paid_at->format('M d, Y g:i A') }}</div>
            </div>
            @endif
        </div>

        @if($payment->status === 'completed')
        <div class="notice">
            ✓ Thank you! Your payment has been successfully received and processed.
        </div>
        @elseif($payment->status === 'pending')
        <div class="notice">
            ⏳ Your payment is pending. Please check back shortly for updates.
        </div>
        @else
        <div class="notice">
            ⚠ Your payment could not be processed. Please contact support.
        </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <p>This is an automated receipt generated on {{ now()->format('M d, Y') }}.</p>
            <p>If you have any questions, please contact our support team.</p>
        </div>
    </div>
</body>
</html>

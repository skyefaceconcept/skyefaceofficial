<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Re: Your Quote Request</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background-color: #f5f5f5; }
        .email-container { max-width: 600px; margin: 20px auto; background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); overflow: hidden; }
        .email-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px 20px; text-align: center; }
        .email-header h1 { margin: 0; font-size: 24px; font-weight: 600; }
        .email-body { padding: 30px 20px; }
        .quote-box { background: #e8f5e9; border-left: 4px solid #28a745; padding: 20px; margin: 20px 0; border-radius: 4px; }
        .quote-box h3 { color: #155f35; margin-top: 0; }
        .amount { font-size: 32px; font-weight: bold; color: #28a745; margin: 20px 0; }
        .rejection-box { background: #ffebee; border-left: 4px solid #dc3545; padding: 20px; margin: 20px 0; border-radius: 4px; }
        .rejection-box h3 { color: #c82333; margin-top: 0; }
        .message-block { background: #f0f4ff; border: 1px solid #e0e7ff; border-radius: 6px; padding: 20px; margin: 20px 0; }
        .message-block p { margin: 0; color: #333; line-height: 1.8; white-space: pre-wrap; word-wrap: break-word; }
        .info-box { background: #f9f9f9; border-left: 4px solid #667eea; padding: 15px; margin-bottom: 20px; border-radius: 4px; }
        .info-box p { margin: 5px 0; font-size: 14px; }
        .cta-button { display: inline-block; background: #667eea; color: white; padding: 12px 30px; text-decoration: none; border-radius: 6px; margin: 20px 0; font-weight: 600; transition: background 0.3s; }
        .cta-button:hover { background: #5568d3; }
        .cta-button.success { background: #28a745; }
        .cta-button.success:hover { background: #218838; }
        .email-footer { background: #f9f9f9; padding: 20px; text-align: center; border-top: 1px solid #eee; font-size: 12px; color: #666; }
        .email-footer p { margin: 5px 0; }
        .divider { height: 1px; background: #eee; margin: 20px 0; }
        .action-box { text-align: center; margin: 30px 0; }
        /* Receipt Styles */
        .receipt-section { background: #fafafa; border: 1px solid #ddd; border-radius: 6px; padding: 20px; margin: 20px 0; }
        .receipt-header { border-bottom: 2px solid #667eea; padding-bottom: 15px; margin-bottom: 15px; }
        .receipt-header h3 { margin: 0; color: #667eea; font-size: 18px; }
        .receipt-row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #eee; }
        .receipt-row.total { border-bottom: 2px solid #667eea; font-weight: bold; padding-top: 15px; }
        .receipt-label { color: #666; font-size: 14px; }
        .receipt-value { color: #333; font-size: 14px; font-weight: 500; }
        .receipt-row.total .receipt-label { color: #333; }
        .receipt-row.total .receipt-value { color: #28a745; font-size: 18px; }
        .quote-ref { background: #f0f4ff; padding: 10px; border-radius: 4px; margin: 10px 0; }
        .quote-ref-label { color: #667eea; font-size: 12px; font-weight: 600; text-transform: uppercase; }
        .quote-ref-value { color: #333; font-size: 14px; font-weight: 600; }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <h1>Re: Your Quote Request #{{ $quote->id }}</h1>
        </div>

        <!-- Body -->
        <div class="email-body">
            <h2>Dear {{ $quote->name }},</h2>

            <p>Thank you for choosing us! We've reviewed your project details and have a response for you.</p>

            @if($quote->status === 'quoted')
                <div class="quote-box">
                    <h3>✓ Your Quote</h3>
                    <p>We're pleased to provide the following quote for your project:</p>
                    <div class="amount">₦{{ number_format($quote->quoted_price ?? 0, 2) }}</div>
                </div>

                <h3>Project Details</h3>
                <div class="message-block">
                    {{ $quote->response }}
                </div>

                <!-- Receipt Section -->
                <div class="receipt-section">
                    <div class="receipt-header">
                        <h3>📋 Quote Receipt</h3>
                    </div>
                    
                    <div class="quote-ref">
                        <div class="quote-ref-label">Quote Reference Number</div>
                        <div class="quote-ref-value">QT-{{ str_pad($quote->id, 5, '0', STR_PAD_LEFT) }}</div>
                    </div>

                    <div class="receipt-row">
                        <span class="receipt-label">Date Issued</span>
                        <span class="receipt-value">{{ $quote->responded_at ? $quote->responded_at->format('M d, Y') : now()->format('M d, Y') }}</span>
                    </div>

                    <div class="receipt-row">
                        <span class="receipt-label">Client Name</span>
                        <span class="receipt-value">{{ $quote->name }}</span>
                    </div>

                    <div class="receipt-row">
                        <span class="receipt-label">Client Email</span>
                        <span class="receipt-value">{{ $quote->email }}</span>
                    </div>

                    <div class="receipt-row">
                        <span class="receipt-label">Project Type</span>
                        <span class="receipt-value">{{ ucfirst($quote->service_type ?? 'General Service') }}</span>
                    </div>

                    <div class="receipt-row">
                        <span class="receipt-label">Quote Amount</span>
                        <span class="receipt-value">₦{{ number_format($quote->quoted_price ?? 0, 2) }}</span>
                    </div>

                    <div class="receipt-row total">
                        <span class="receipt-label">Total Amount Due</span>
                        <span class="receipt-value">₦{{ number_format($quote->quoted_price ?? 0, 2) }}</span>
                    </div>
                </div>

                <h3>Next Steps</h3>
                <div class="info-box">
                    <p>To move forward with this quote, simply click the button below to proceed to payment. If you have any questions or would like to discuss adjustments, please reply to this email or contact us directly.</p>
                </div>

                <div class="action-box">
                    <a href="{{ route('payment.form', $quote->id) }}" class="cta-button success" style="display: inline-block; padding: 15px 40px; font-size: 16px;">
                        💳 Proceed to Payment
                    </a>
                </div>

                <p style="text-align: center; color: #666; font-size: 12px;">
                    Or view full details: <a href="{{ route('quotes.track', ['email' => $quote->email, 'id' => $quote->id]) }}">Track Your Quote</a>
                </p>

            @elseif($quote->status === 'rejected')
                <div class="rejection-box">
                    <h3>Update on Your Request</h3>
                    <p>Unfortunately, after reviewing your project details, we're unable to provide a quote at this time.</p>
                </div>

                <div class="message-block">
                    {{ $quote->response }}
                </div>

                <p>We appreciate you considering us, and we hope to work with you in the future on a project that better fits our expertise.</p>

            @else
                <h3>Status Update</h3>
                <div class="message-block">
                    {{ $quote->response }}
                </div>

                <div class="action-box">
                    <a href="{{ route('quotes.track', ['email' => $quote->email, 'id' => $quote->id]) }}" class="cta-button">Check Status</a>
                </div>
            @endif

            <div class="divider"></div>

            <p>If you have any questions, please don't hesitate to reach out.</p>
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

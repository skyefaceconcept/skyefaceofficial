<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #f4f4f4; padding: 20px; text-align: center; }
        .content { background-color: #fff; padding: 20px; }
        .footer { background-color: #f4f4f4; padding: 20px; text-align: center; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        .button { display: inline-block; padding: 10px 20px; background-color: #007bff; color: #fff; text-decoration: none; border-radius: 4px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Device Received</h1>
        </div>
        
        <div class="content">
            <p>Hi {{ $repair->customer_name }},</p>
            
            <p>Thank you for trusting us with your device! We've successfully received it and added it to our repair queue. Your device is now safe with us.</p>
            
            <h2>Repair Details</h2>
            <table>
                <tr><th>Item</th><th>Details</th></tr>
                <tr><td><strong>Tracking Number</strong></td><td>{{ $repair->invoice_number }}</td></tr>
                <tr><td><strong>Device</strong></td><td>{{ $repair->device_brand }} {{ $repair->device_model }} ({{ $repair->device_type }})</td></tr>
                <tr><td><strong>Issue</strong></td><td>{{ $repair->issue_description }}</td></tr>
                <tr><td><strong>Received</strong></td><td>{{ $repair->created_at->format('M d, Y') }}</td></tr>
                <tr><td><strong>Consultation Fee</strong></td><td>₦{{ number_format($repair->cost_estimate, 2) }}</td></tr>
                <tr><td><strong>Total Repair Cost</strong></td><td>₦{{ number_format($repair->cost_estimate + ($repair->cost_actual ?? 0), 2) }}</td></tr>
            </table>
            
            <h2>What Happens Next</h2>
            <p>Our experienced technician will:</p>
            <ol>
                <li><strong>Inspect</strong> your device thoroughly (24 hours)</li>
                <li><strong>Diagnose</strong> the exact problem (12-24 hours)</li>
                <li><strong>Report</strong> findings and repair cost (within 48 hours)</li>
            </ol>
            
            <p>You'll receive a detailed diagnosis report with:</p>
            <ul>
                <li>What's causing the problem</li>
                <li>Exact repair cost estimate</li>
                <li>Timeline for completion</li>
            </ul>
            
            @if($notes)
                <p><strong>Notes from our team:</strong><br>{{ $notes }}</p>
            @endif
            
            <p style="text-align: center; margin: 20px 0;">
                <a href="{{ route('repairs.track.page') }}" class="button">View Tracking Dashboard</a>
            </p>
            
            <p>Save your tracking number: <strong>{{ $repair->invoice_number }}</strong></p>
            <p>You can check your repair status anytime without logging in.</p>
            
            <hr>
            
            <p>Questions? Reply to this email and we'll get back to you within 2 hours.</p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} Skyeface. All rights reserved.</p>
        </div>
    </div>
</body>
</html>

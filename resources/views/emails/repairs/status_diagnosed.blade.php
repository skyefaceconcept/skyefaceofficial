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
        .button { display: inline-block; padding: 10px 20px; background-color: #28a745; color: #fff; text-decoration: none; border-radius: 4px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Diagnosis Complete</h1>
        </div>
        
        <div class="content">
            <p>Hi {{ $repair->customer_name }},</p>
            
            <p>Great news! We've completed the diagnosis of your device. Our technician has identified the issue and prepared a repair plan for you.</p>
            
            <h2>Diagnosis Report</h2>
            <table>
                <tr><th>Item</th><th>Details</th></tr>
                <tr><td><strong>Tracking Number</strong></td><td>{{ $repair->invoice_number }}</td></tr>
                <tr><td><strong>Device</strong></td><td>{{ $repair->device_brand }} {{ $repair->device_model }}</td></tr>
                <tr><td><strong>Status</strong></td><td>Diagnosis Complete</td></tr>
                <tr><td><strong>Consultation Fee</strong></td><td>₦{{ number_format($repair->cost_estimate, 2) }}</td></tr>
                <tr><td><strong>Estimated Repair Cost</strong></td><td>₦{{ number_format($repair->cost_actual ?? $repair->cost_estimate, 2) }}</td></tr>
                <tr><td><strong>Total Cost</strong></td><td>₦{{ number_format($repair->cost_estimate + ($repair->cost_actual ?? $repair->cost_estimate), 2) }}</td></tr>
            </table>
            
            <h2>What We Found</h2>
            @if($notes)
                <p>{{ $notes }}</p>
            @else
                <p>Our technician has identified the main issues affecting your device's performance and reliability. The repair will address all identified problems and restore your device to optimal working condition.</p>
            @endif
            
            <h2>Next Steps</h2>
            <p>Please review the diagnosis and costs above. If you'd like to proceed with the repair, simply click the button below or visit your tracking page.</p>
            
            <p style="text-align: center; margin: 20px 0;">
                <a href="{{ route('repairs.payment-form', $repair->id) }}" class="button" style="background-color: #28a745; display: inline-block; padding: 15px 40px; font-size: 16px; font-weight: bold;">
                    💳 Pay Now - ₦{{ number_format($repair->cost_actual ?? $repair->cost_estimate, 2) }}
                </a>
            </p>

            <p style="text-align: center; margin-top: 15px;">
                <a href="{{ route('repairs.track.page') }}" class="button" style="background-color: #6c757d;">View Repair Details</a>
            </p>
            
            <h2>What Happens After Approval</h2>
            <ol>
                <li>Repair Starts - Immediately after your approval</li>
                <li>Progress Updates - You'll receive status emails</li>
                <li>Quality Check - We inspect before handover</li>
                <li>Ready Notification - When your device is ready</li>
            </ol>
            
            <p>Your Tracking Number: <strong>{{ $repair->invoice_number }}</strong></p>
            
            <hr>
            
            <p>Have questions about the diagnosis or cost? Reply to this email and we'll help you understand everything.</p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} Skyeface. All rights reserved.</p>
        </div>
    </div>
</body>
</html>

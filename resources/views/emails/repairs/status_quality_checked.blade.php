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
        ul { margin: 15px 0; padding-left: 20px; }
        ol { margin: 15px 0; padding-left: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Quality Checked - Ready for Cost Approval</h1>
        </div>
        
        <div class="content">
            <p>Hi {{ $repair->customer_name }},</p>
            
            <p>Excellent news! Your device has successfully passed all quality checks and is in perfect condition. We're now finalizing the repair cost for your approval.</p>
            
            <h2>Quality Check Results</h2>
            <table>
                <tr><th>Item</th><th>Details</th></tr>
                <tr><td><strong>Tracking Number</strong></td><td>{{ $repair->invoice_number }}</td></tr>
                <tr><td><strong>Device</strong></td><td>{{ $repair->device_brand }} {{ $repair->device_model }}</td></tr>
                <tr><td><strong>Status</strong></td><td>Quality Checked - All Tests Passed</td></tr>
                <tr><td><strong>Condition</strong></td><td>Perfect - Ready for Delivery</td></tr>
            </table>
            
            <h2>Final Repair Cost</h2>
            <table>
                <tr><th>Item</th><th>Amount</th></tr>
                <tr><td><strong>Total Repair Cost</strong></td><td><strong>₦{{ number_format($repair->cost_actual ?? $repair->cost_estimate, 2) }}</strong></td></tr>
            </table>
            <small style="color: #666; margin-top: 10px; display: block;">Note: Consultation fee was charged during initial device receipt</small>
            
            @if($notes)
                <p><strong>Final Notes:</strong><br>{{ $notes }}</p>
            @else
                <p>Your device has been thoroughly tested and is functioning perfectly. All repairs have been completed to our high quality standards.</p>
            @endif
            
            <h2>What Passed Quality Check</h2>
            <ul>
                <li>Functionality - All features and functions working perfectly</li>
                <li>Performance - Device running at optimal speed</li>
                <li>Hardware - All components secure and functional</li>
                <li>Software - System optimized and updated</li>
                <li>Aesthetics - Clean and visually perfect</li>
                <li>Durability - Stress tested and verified</li>
            </ul>
            
            <h2>Your Repair Progress</h2>
            <table>
                <tr><th>Stage</th><th>Status</th></tr>
                <tr><td>Received</td><td>Completed</td></tr>
                <tr><td>Diagnosis</td><td>Completed</td></tr>
                <tr><td>In Progress</td><td>Completed</td></tr>
                <tr><td>Quality Check</td><td>Completed</td></tr>
                <tr><td>Quality Checked</td><td><strong>Current</strong></td></tr>
                <tr><td>Ready for Pickup</td><td>Next</td></tr>
            </table>
            
            <h2>Next Steps</h2>
            <ol>
                <li>Review Cost - Check if the repair cost is acceptable</li>
                <li>Approve Cost - Confirm you agree to the final amount</li>
                <li>Ready for Pickup - Device will be marked ready</li>
                <li>Pick Up Device - Come collect your repaired device</li>
            </ol>
            
            <p style="text-align: center; margin: 20px 0;">
                <a href="{{ route('repairs.track.page') }}" class="button">Review & Approve Cost</a>
            </p>
            
            <p>Your Tracking Number: <strong>{{ $repair->invoice_number }}</strong></p>
            
            <hr>
            
            <p><em>Your device is fully repaired and ready! Please approve the final cost so we can prepare it for pickup.</em></p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} Skyeface. All rights reserved.</p>
        </div>
    </div>
</body>
</html>

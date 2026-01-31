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
            <h1>Quality Check - Final Inspection</h1>
        </div>
        
        <div class="content">
            <p>Hi {{ $repair->customer_name }},</p>
            
            <p>The repair work is complete! Your device is now undergoing our rigorous quality assurance testing to ensure everything works perfectly.</p>
            
            <h2>Quality Assurance Status</h2>
            <table>
                <tr><th>Item</th><th>Details</th></tr>
                <tr><td><strong>Tracking Number</strong></td><td>{{ $repair->invoice_number }}</td></tr>
                <tr><td><strong>Device</strong></td><td>{{ $repair->device_brand }} {{ $repair->device_model }}</td></tr>
                <tr><td><strong>Status</strong></td><td>Quality Check In Progress</td></tr>
                <tr><td><strong>Expected Completion</strong></td><td>Within 24 hours</td></tr>
                <tr><td><strong>Consultation Fee</strong></td><td>₦{{ number_format($repair->cost_estimate, 2) }}</td></tr>
                <tr><td><strong>Total Repair Cost</strong></td><td>₦{{ number_format($repair->cost_estimate + ($repair->cost_actual ?? 0), 2) }}</td></tr>
            </table>
            
            <h2>What We're Testing</h2>
            <p>Every device must pass our strict quality standards:</p>
            <ul>
                <li>Functionality Tests - All features working correctly</li>
                <li>Performance Diagnostics - Speed and responsiveness</li>
                <li>Hardware Verification - All components secure and functional</li>
                <li>Software Check - System optimization and updates</li>
                <li>Visual Inspection - Clean and cosmetically perfect</li>
                <li>Final Trial Run - Extended use testing</li>
            </ul>
            
            @if($notes)
                <p><strong>Quality Check Details:</strong><br>{{ $notes }}</p>
            @else
                <p>Your device is being tested thoroughly to ensure it meets our high quality standards before being released to you.</p>
            @endif
            
            <h2>Your Repair Progress</h2>
            <table>
                <tr><th>Stage</th><th>Status</th></tr>
                <tr><td>Received</td><td>Completed</td></tr>
                <tr><td>Diagnosis</td><td>Completed</td></tr>
                <tr><td>In Progress</td><td>Completed</td></tr>
                <tr><td>Quality Check</td><td><strong>Current</strong></td></tr>
                <tr><td>Quality Checked</td><td>Next</td></tr>
                <tr><td>Ready for Pickup</td><td>Coming Soon</td></tr>
            </table>
            
            <h2>What Happens Next</h2>
            <p>Once quality checks pass:</p>
            <ol>
                <li>Device moves to "Quality Checked" status</li>
                <li>Final cost review (if needed)</li>
                <li>Marked "Ready for Pickup"</li>
                <li>You'll receive pickup instructions</li>
            </ol>
            <p>Expected Timeline: 24 hours from now</p>
            
            <p style="text-align: center; margin: 20px 0;">
                <a href="{{ route('repairs.track.page') }}" class="button">View Detailed Progress</a>
            </p>
            
            <p>Your Tracking Number: <strong>{{ $repair->invoice_number }}</strong></p>
            
            <hr>
            
            <p><em>Your device is almost ready! We take quality seriously and ensure everything is perfect before handover.</em></p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} Skyeface. All rights reserved.</p>
        </div>
    </div>
</body>
</html>

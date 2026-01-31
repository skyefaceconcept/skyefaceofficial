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
            <h1>Repair In Progress</h1>
        </div>
        
        <div class="content">
            <p>Hi {{ $repair->customer_name }},</p>
            
            <p>Your device repair has started! Our skilled technician is working on it right now.</p>
            
            <h2>Repair Status</h2>
            <table>
                <tr>
                    <th>Item</th>
                    <th>Details</th>
                </tr>
                <tr>
                    <td><strong>Tracking Number</strong></td>
                    <td>{{ $repair->invoice_number }}</td>
                </tr>
                <tr>
                    <td><strong>Device</strong></td>
                    <td>{{ $repair->device_brand }} {{ $repair->device_model }}</td>
                </tr>
                <tr>
                    <td><strong>Status</strong></td>
                    <td>In Progress</td>
                </tr>
                <tr>
                    <td><strong>Estimated Completion</strong></td>
                    <td>{{ $repair->created_at->addDays(3)->format('M d, Y') }}</td>
                </tr>
                <tr>
                    <td><strong>Consultation Fee</strong></td>
                    <td>₦{{ number_format($repair->cost_estimate, 2) }}</td>
                </tr>
                <tr>
                    <td><strong>Total Repair Cost</strong></td>
                    <td>₦{{ number_format($repair->cost_estimate + ($repair->cost_actual ?? 0), 2) }}</td>
                </tr>
            </table>
            
            <h2>What We're Doing</h2>
            <p>Our technician is currently:</p>
            <ul>
                <li>Inspecting all components thoroughly</li>
                <li>Replacing or repairing faulty parts</li>
                <li>Testing functionality during repair</li>
                <li>Cleaning and preparing for quality check</li>
            </ul>
            
            @if($notes)
                <p><strong>Progress Update:</strong><br>{{ $notes }}</p>
            @else
                <p>Your device is progressing well through our repair process. Each step is carefully executed to ensure quality and longevity.</p>
            @endif
            
            <h2>What Comes Next</h2>
            <p>After the repair work is complete, your device enters our Quality Check phase where we:</p>
            <ol>
                <li>Test all features and functions</li>
                <li>Run performance diagnostics</li>
                <li>Final cleaning and polish</li>
                <li>Prepare for pickup</li>
            </ol>
            <p>This usually takes 24-48 hours.</p>
            
            <p style="text-align: center; margin: 20px 0;">
                <a href="{{ route('repairs.track.page') }}" class="button">View Live Repair Progress</a>
            </p>
            
            <p>Your Tracking Number: <strong>{{ $repair->invoice_number }}</strong></p>
            
            <hr>
            
            <p><em>Your device is in good hands. We'll notify you as soon as the repair is complete and quality check begins.</em></p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} Skyeface. All rights reserved.</p>
        </div>
    </div>
</body>
</html>

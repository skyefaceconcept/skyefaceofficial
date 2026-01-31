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
            <h1>Ready for Pickup!</h1>
        </div>
        
        <div class="content">
            <p>Hi {{ $repair->customer_name }},</p>
            
            <p>Excellent news! Your device repair is complete and has passed all quality checks. It's ready for you to pick up!</p>
            
            <h2>Pickup Information</h2>
            <table>
                <tr><th>Item</th><th>Details</th></tr>
                <tr><td><strong>Tracking Number</strong></td><td>{{ $repair->invoice_number }}</td></tr>
                <tr><td><strong>Device</strong></td><td>{{ $repair->device_brand }} {{ $repair->device_model }}</td></tr>
                <tr><td><strong>Status</strong></td><td>Ready for Pickup</td></tr>
                <tr><td><strong>Total Repair Cost</strong></td><td>₦{{ number_format($repair->cost_actual ?? $repair->cost_estimate, 2) }}</td></tr>
            </table>
            
            <h2>How to Pick Up</h2>
            <p><strong>Visit Us In Person:</strong></p>
            <p>
                Address: {{ config('company.address') ?? 'Our Service Center' }}<br>
                Hours: Monday - Friday, 9:00 AM - 6:00 PM<br>
                Saturday: 10:00 AM - 4:00 PM<br>
                Closed: Sundays & Public Holidays<br>
                <br>
                Bring your tracking number or any valid ID
            </p>
            
            <p><strong>Alternative: Request Delivery</strong><br>
            We offer safe delivery service for an additional fee. Contact us to arrange!</p>
            
            <h2>What You'll Receive</h2>
            <p>When you pick up your device:</p>
            <ul>
                <li>Your fully repaired and tested device</li>
                <li>30-day warranty certificate</li>
                <li>Final invoice and receipt</li>
                <li>Device care and maintenance guide</li>
                <li>Parts warranty information</li>
            </ul>
            
            <h2>Before Pickup</h2>
            <p>Please check:</p>
            <ol>
                <li>Test Your Device - We've tested it, but verify it works for you</li>
                <li>Inspect for Cleanliness - Should look as good as new</li>
                <li>Take Photos/Video - For your records if needed</li>
                <li>Keep Your Receipt - Important for warranty claims</li>
            </ol>
            
            <p style="text-align: center; margin: 20px 0;">
                <a href="{{ route('repairs.track.page') }}" class="button">Schedule Pickup/Delivery</a>
            </p>
            
            <p>Your Tracking Number: <strong>{{ $repair->invoice_number }}</strong></p>
            
            <hr>
            
            <p><em>Thank you for choosing Skyeface! We hope your device serves you well.</em></p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} Skyeface. All rights reserved.</p>
        </div>
    </div>
</body>
</html>

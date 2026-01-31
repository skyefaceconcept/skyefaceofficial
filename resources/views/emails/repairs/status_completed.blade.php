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
            <h1>Repair Complete!</h1>
        </div>
        
        <div class="content">
            <p>Hi {{ $repair->customer_name }},</p>
            
            <p>Your device repair is complete and has been successfully delivered to you. Thank you for your business.</p>
            
            <h2>Repair Summary</h2>
            <table>
                <tr><th>Item</th><th>Details</th></tr>
                <tr><td><strong>Tracking Number</strong></td><td>{{ $repair->invoice_number }}</td></tr>
                <tr><td><strong>Device</strong></td><td>{{ $repair->device_brand }} {{ $repair->device_model }}</td></tr>
                <tr><td><strong>Status</strong></td><td>Complete</td></tr>
                <tr><td><strong>Total Repair Cost</strong></td><td>₦{{ number_format($repair->cost_actual ?? 0, 2) }}</td></tr>
                <tr><td><strong>Warranty</strong></td><td>30 Days</td></tr>
            </table>
            
            @if($notes)
                <p><strong>Completion Notes:</strong><br>{{ $notes }}</p>
            @endif
            
            <h2>Your Warranty (30 Days)</h2>
            <p>Your device includes a 30-day warranty from pickup date covering:</p>
            <ul>
                <li>Replaced parts</li>
                <li>Repair work</li>
                <li>Original issues only</li>
            </ul>
            
            <p>If you experience any issues:</p>
            <ol>
                <li>Contact us right away</li>
                <li>Bring your device and receipt</li>
                <li>We will repair at no charge</li>
            </ol>
            
            <p><strong>Warranty Support:</strong><br>
                Email: {{ config('company.email') }}<br>
                Phone: {{ config('company.phone') }}<br>
                Mon-Sat: 9:00 AM - 6:00 PM
            </p>
            
            <h2>Care Instructions</h2>
            <p>To keep your device working well:</p>
            <ul>
                <li>Keep it clean with a soft cloth</li>
                <li>Avoid heat and moisture</li>
                <li>Use original chargers</li>
                <li>Keep software updated</li>
                <li>Avoid drops and impacts</li>
                <li>Store in a cool, dry place</li>
            </ul>
            
            <h2>Questions?</h2>
            <p>Contact us:</p>
            <ul>
                <li>Email: {{ config('company.email') }}</li>
                <li>Phone: {{ config('company.phone') }}</li>
                <li>Visit: {{ config('company.address') ?? 'Our Service Center' }}</li>
            </ul>
            
            <hr>
            
            <p>Thank you for choosing Skyeface. We appreciate your business.</p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} Skyeface. All rights reserved.</p>
        </div>
    </div>
</body>
</html>

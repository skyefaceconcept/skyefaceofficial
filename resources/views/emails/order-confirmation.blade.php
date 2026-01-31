@component('mail::message')
# Order Confirmation

Hello {{ $order->customer_name }},

Thank you for your order! We've received your request and your payment is pending.

## Order Details
- **Product:** {{ $portfolio->title }}
- **Amount:** {{ config('app.currency', '$') }}{{ number_format($order->amount, 2) }}
- **Order ID:** #{{ $order->id }}
- **Status:** Pending Payment

## Next Steps
Please proceed with payment to confirm your order. Click the button below to complete payment:

@component('mail::button', ['url' => $paymentLink])
Complete Payment
@endcomponent

Once payment is confirmed, you'll receive your license code and activation instructions via email.

@if($license)
## Your License Information
Great news! Your license is already generated and ready to use:

@component('mail::panel')
# {{ $license->license_code }}
@endcomponent

**License Details:**
- **Product:** {{ $license->application_name }}
- **Valid Until:** {{ $license->expiry_date->format('F d, Y') }}
- **Status:** Active
- **License ID:** {{ $license->id }}

Once your payment is confirmed, check your email for detailed activation instructions on how to activate and use this license.

@endif

## Need Help?
If you have any questions or need assistance, please contact our support team at {{ config('mail.from.address') }}.

Thanks,<br>
{{ config('app.name') }}

@slot('subcopy')
If you're having trouble clicking the "Complete Payment" button, copy and paste this URL into your web browser: [{{ $paymentLink }}]({{ $paymentLink }})
@endslot
@endcomponent

# Order Completed Successfully!

Hello {{ $order->customer_name }},

Great news! Your payment has been processed successfully and your order is complete.

## Order Summary
@if($portfolio)
- **Product:** {{ $portfolio->title }}
@endif
- **Amount Paid:** {{ config('app.currency', '$') }}{{ number_format($order->amount, 2) }}
- **Order ID:** #{{ $order->id }}
@if($order->transaction_reference)
- **Transaction Reference:** {{ $order->transaction_reference }}
@endif
@if($order->completed_at)
- **Completed At:** {{ $order->completed_at->format('F d, Y H:i A') }}
@endif

@if($license)
## Your License Code
Your license code is ready to use:

<x-mail-panel>
# {{ $license->license_code }}
</x-mail-panel>

**License Information:**
- **Application:** {{ $license->application_name }}
- **Valid Until:** {{ $license->expiry_date->format('F d, Y') }}
- **Status:** Active

## Activation Instructions
Please refer to the separate email titled "Your License Code - {{ $license->application_name }}" for detailed activation instructions based on your product type.

## Important Notes
- **Do not share** this license code with anyone else
- Keep this code **safe and secure** for future reference
- You can use this code to **activate the application** on your device/server
@endif

## Need Help?
If you encounter any issues during activation or need technical support, please don't hesitate to contact us at {{ config('mail.from.address') }}.

We appreciate your business!

{{ config('app.name') }} Team

---

This is an automated email. Please do not reply to this message. For inquiries, contact our support team.

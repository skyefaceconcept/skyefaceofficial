# Your License Code & Activation Instructions

@if($order)
Hello {{ $order->customer_name }},
@else
Hello,
@endif

Thank you for choosing **{{ $license->application_name ?? 'our service' }}**! Your license has been generated and is ready for activation.

## Your License Code
Please keep this code safe and secure:

<x-mail-panel>
# {{ $license->license_code }}
</x-mail-panel>

**Valid Until:** {{ $license->expiry_date->format('F d, Y') }}

---

## {{ $activationInstructions['title'] }}

Follow these steps to activate your license:

@foreach($activationInstructions['steps'] as $step)
{!! $step !!}
@endforeach

---

@if($order && $order->portfolio)
## License Details
| Field | Value |
|-------|-------|
| **Product** | {{ $order->portfolio->title }} |
| **License Code** | {{ $license->license_code }} |
| **Expiry Date** | {{ $license->expiry_date->format('F d, Y') }} |
| **Order ID** | #{{ $order->id }} |
| **Status** | Active |
@endif

## Important Reminders
✓ **Do not share** your license code publicly  
✓ **Keep it secure** - treat it like a password  
✓ **One code per person** - each license is unique  
✓ **Not transferable** - tied to your order only

## Activation API (For Developers)
If you're integrating license verification programmatically, use the API endpoint:

```
POST /api/license/activate
Content-Type: application/json

{
  "license_code": "{{ $license->license_code }}"
}
```

## Troubleshooting
- **License not recognized?** Double-check that you've entered the code exactly as shown (case-sensitive)
- **Activation failed?** Ensure you have internet connectivity during activation
- **Still having issues?** Contact our support team immediately

## Need Support?
Our support team is here to help! Contact us at:
- **Email:** {{ config('mail.from.address') }}
- **Response Time:** Within 24 hours

We're glad to have you as a customer!

{{ config('app.name') }} Team

---

This is an automated email. Please do not reply to this message. For support inquiries, use the contact information above.

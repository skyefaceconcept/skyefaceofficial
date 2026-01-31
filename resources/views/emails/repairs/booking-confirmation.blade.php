@component('mail::message')
# Device Repair Booking Confirmed! ✓

Hi {{ $repair->customer_name }},

Thank you for booking with us! Your device repair has been registered in our system.

## Booking Details

| Field | Details |
|-------|---------|
| **Tracking Number** | {{ $repair->invoice_number }} |
| **Device** | {{ $repair->device_brand }} {{ $repair->device_model }} ({{ $repair->device_type }}) |
| **Issue** | {{ $repair->issue_description }} |
| **Priority** | {{ $repair->urgency }} |
| **Consultation Fee** | ₦{{ number_format($repair->cost_estimate, 2) }} (Non-Refundable) |

## Next Steps

**Please proceed with payment** to complete your booking. Your device will only be taken for repair after payment is confirmed.

@component('mail::button', ['url' => route('repairs.track.page')])
View & Pay Online
@endcomponent

## Track Your Repair

You can track your repair status anytime using your tracking number: **{{ $repair->invoice_number }}**

Visit: [{{ config('app.url') }}/repairs/track]({{ route('repairs.track.page') }})

## Support

If you have any questions, please contact us:
- **Email:** {{ config('company.email') }}
- **Phone:** {{ config('company.phone') }}

---

**Important:** Your consultation fee is non-refundable. If you proceed with the repair after diagnosis, the consultation fee will be deducted from the final repair cost.

@endcomponent

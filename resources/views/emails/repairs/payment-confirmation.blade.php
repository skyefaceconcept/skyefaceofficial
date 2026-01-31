@component('mail::message')
# Payment Received ✓

Hi {{ $repair->customer_name }},

We've received your payment! Your device repair is now confirmed and will be processed shortly.

## Payment Summary

| Field | Details |
|-------|---------|
| **Tracking Number** | {{ $repair->invoice_number }} |
| **Diagnosis Fee** | ₦{{ number_format($repair->cost_estimate, 2) }} |
| **Repair Cost** | ₦{{ number_format($repair->cost_actual ?? 0, 2) }} |
| **Total Amount Paid** | ₦{{ number_format(($repair->cost_estimate ?? 0) + ($repair->cost_actual ?? 0), 2) }} |
| **Device** | {{ $repair->device_brand }} {{ $repair->device_model }} |
| **Status** | ✓ Confirmed & Queued |

## What Happens Next?

1. **Device Received** - We'll log your device into our system
2. **Diagnosis** - Our technician will diagnose the problem and provide a full report
3. **Approval** - We'll send you the diagnosis and estimated repair cost
4. **Repair** - Once approved, we'll proceed with the repair
5. **Quality Check** - Every device goes through quality assurance
6. **Ready for Pickup** - You'll be notified when it's ready

## Track Your Repair

Monitor your repair progress in real-time:

@component('mail::button', ['url' => route('repairs.track.page')])
Check Repair Status
@endcomponent

**Your Tracking Number:** {{ $repair->invoice_number }}

## Repair Timeline

- **Estimated Time:** 3-5 business days (may vary based on issue complexity)
- **Updates:** You'll receive email notifications at each milestone
- **Contact:** We're here to help - reply to this email with any questions

---

Thank you for choosing {{ config('company.name' )}}.

@endcomponent

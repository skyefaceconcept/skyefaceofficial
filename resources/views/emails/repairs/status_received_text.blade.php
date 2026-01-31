DEVICE RECEIVED
===============

Hi {{ $repair->customer_name }},

Thank you for trusting us with your device! We've successfully received it and added it to our repair queue. Your device is now safe with us.

REPAIR DETAILS
==============

Tracking Number: {{ $repair->invoice_number }}
Device: {{ $repair->device_brand }} {{ $repair->device_model }} ({{ $repair->device_type }})
Issue: {{ $repair->issue_description }}
Received: {{ $repair->created_at->format('M d, Y') }}
Consultation Fee: ₦{{ number_format($repair->cost_estimate, 2) }}
Total Repair Cost: ₦{{ number_format($repair->cost_estimate + ($repair->cost_actual ?? 0), 2) }}

WHAT HAPPENS NEXT
=================

Our experienced technician will:

1. Inspect your device thoroughly (24 hours)
2. Diagnose the exact problem (12-24 hours)
3. Report findings and repair cost (within 48 hours)

You'll receive a detailed diagnosis report with:
- What's causing the problem
- Exact repair cost estimate
- Timeline for completion

@if($notes)
Notes from our team:
{{ $notes }}
@endif

TRACK YOUR REPAIR
=================

Monitor every step in real-time:
View: {{ route('repairs.track.page') }}

Save your tracking number: {{ $repair->invoice_number }}

You can check your repair status anytime without logging in.

---

Questions? Reply to this email and we'll get back to you within 2 hours.

Skyeface Repair System
Copyright © {{ date('Y') }} Skyeface. All rights reserved.

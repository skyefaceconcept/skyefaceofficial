DIAGNOSIS COMPLETE
==================

Hi {{ $repair->customer_name }},

Great news! We've completed the diagnosis of your device. Our technician has identified the issue and prepared a repair plan for you.

DIAGNOSIS REPORT
================

Tracking Number: {{ $repair->invoice_number }}
Device: {{ $repair->device_brand }} {{ $repair->device_model }}
Status: Diagnosis Complete
Consultation Fee: ₦{{ number_format($repair->cost_estimate, 2) }}
Estimated Repair Cost: ₦{{ number_format($repair->cost_actual ?? $repair->cost_estimate, 2) }}
Total Cost: ₦{{ number_format($repair->cost_estimate + ($repair->cost_actual ?? $repair->cost_estimate), 2) }}

WHAT WE FOUND
=============

@if($notes)
{{ $notes }}
@else
Our technician has identified the main issues affecting your device's performance and reliability. The repair will address all identified problems and restore your device to optimal working condition.
@endif

NEXT STEPS
==========

Please review the diagnosis and costs above. If you'd like to proceed with the repair, simply reply to this email or visit your tracking page.

View: {{ route('repairs.track.page') }}

WHAT HAPPENS AFTER APPROVAL
============================

1. Repair Starts - Immediately after your approval
2. Progress Updates - You'll receive status emails
3. Quality Check - We inspect before handover
4. Ready Notification - When your device is ready

Your Tracking Number: {{ $repair->invoice_number }}

---

Have questions about the diagnosis or cost? Reply to this email and we'll help you understand everything.

Skyeface Repair System
Copyright © {{ date('Y') }} Skyeface. All rights reserved.

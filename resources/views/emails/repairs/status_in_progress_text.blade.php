REPAIR IN PROGRESS
==================

Hi {{ $repair->customer_name }},

Your device repair has started! Our skilled technician is working on it right now.

REPAIR STATUS
=============

Tracking Number: {{ $repair->invoice_number }}
Device: {{ $repair->device_brand }} {{ $repair->device_model }}
Status: In Progress
Estimated Completion: {{ $repair->created_at->addDays(3)->format('M d, Y') }}
Consultation Fee: ₦{{ number_format($repair->cost_estimate, 2) }}
Total Repair Cost: ₦{{ number_format($repair->cost_estimate + ($repair->cost_actual ?? 0), 2) }}

WHAT WE'RE DOING
================

Our technician is currently:
- Inspecting all components thoroughly
- Replacing or repairing faulty parts
- Testing functionality during repair
- Cleaning and preparing for quality check

@if($notes)
Progress Update:
{{ $notes }}
@else
Your device is progressing well through our repair process. Each step is carefully executed to ensure quality and longevity.
@endif

WHAT COMES NEXT
===============

After the repair work is complete, your device enters our Quality Check phase where we:

1. Test all features and functions
2. Run performance diagnostics
3. Final cleaning and polish
4. Prepare for pickup

This usually takes 24-48 hours.

TRACK YOUR REPAIR
=================

View: {{ route('repairs.track.page') }}

Your Tracking Number: {{ $repair->invoice_number }}

---

Your device is in good hands. We'll notify you as soon as the repair is complete and quality check begins.

Skyeface Repair System
Copyright © {{ date('Y') }} Skyeface. All rights reserved.

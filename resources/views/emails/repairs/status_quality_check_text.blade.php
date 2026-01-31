QUALITY CHECK - FINAL INSPECTION
=================================

Hi {{ $repair->customer_name }},

The repair work is complete! Your device is now undergoing our rigorous quality assurance testing to ensure everything works perfectly.

QUALITY ASSURANCE STATUS
=========================

Tracking Number: {{ $repair->invoice_number }}
Device: {{ $repair->device_brand }} {{ $repair->device_model }}
Status: Quality Check In Progress
Expected Completion: Within 24 hours
Consultation Fee: ₦{{ number_format($repair->cost_estimate, 2) }}
Total Repair Cost: ₦{{ number_format($repair->cost_estimate + ($repair->cost_actual ?? 0), 2) }}

WHAT WE'RE TESTING
==================

Every device must pass our strict quality standards:
- Functionality Tests - All features working correctly
- Performance Diagnostics - Speed and responsiveness
- Hardware Verification - All components secure and functional
- Software Check - System optimization and updates
- Visual Inspection - Clean and cosmetically perfect
- Final Trial Run - Extended use testing

@if($notes)
Quality Check Details:
{{ $notes }}
@else
Your device is being tested thoroughly to ensure it meets our high quality standards before being released to you.
@endif

YOUR REPAIR PROGRESS
====================

Received - Completed
Diagnosis - Completed
In Progress - Completed
Quality Check - Current
Quality Checked - Next
Ready for Pickup - Coming Soon

WHAT HAPPENS NEXT
=================

Once quality checks pass:

1. Device moves to "Quality Checked" status
2. Final cost review (if needed)
3. Marked "Ready for Pickup"
4. You'll receive pickup instructions

Expected Timeline: 24 hours from now

TRACK YOUR REPAIR
=================

View: {{ route('repairs.track.page') }}

Your Tracking Number: {{ $repair->invoice_number }}

---

Your device is almost ready! We take quality seriously and ensure everything is perfect before handover.

Skyeface Repair System
Copyright © {{ date('Y') }} Skyeface. All rights reserved.

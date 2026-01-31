QUALITY CHECKED - READY FOR COST APPROVAL
==========================================

Hi {{ $repair->customer_name }},

Excellent news! Your device has successfully passed all quality checks and is in perfect condition. We're now finalizing the repair cost for your approval.

QUALITY CHECK RESULTS
=====================

Tracking Number: {{ $repair->invoice_number }}
Device: {{ $repair->device_brand }} {{ $repair->device_model }}
Status: Quality Checked - All Tests Passed
Condition: Perfect - Ready for Delivery

FINAL REPAIR COST
=================

Total Repair Cost: ₦{{ number_format($repair->cost_actual ?? $repair->cost_estimate, 2) }}

Note: Consultation fee was charged during initial device receipt

@if($notes)
Final Notes:
{{ $notes }}
@else
Your device has been thoroughly tested and is functioning perfectly. All repairs have been completed to our high quality standards.
@endif

WHAT PASSED QUALITY CHECK
==========================

- Functionality - All features and functions working perfectly
- Performance - Device running at optimal speed
- Hardware - All components secure and functional
- Software - System optimized and updated
- Aesthetics - Clean and visually perfect
- Durability - Stress tested and verified

YOUR REPAIR PROGRESS
====================

Received - Completed
Diagnosis - Completed
In Progress - Completed
Quality Check - Completed
Quality Checked - Current
Ready for Pickup - Next

NEXT STEPS
==========

1. Review Cost - Check if the repair cost is acceptable
2. Approve Cost - Confirm you agree to the final amount
3. Ready for Pickup - Device will be marked ready
4. Pick Up Device - Come collect your repaired device

Please review and approve the cost:
{{ route('repairs.track.page') }}

Your Tracking Number: {{ $repair->invoice_number }}

---

Your device is fully repaired and ready! Please approve the final cost so we can prepare it for pickup.

Skyeface Repair System
Copyright © {{ date('Y') }} Skyeface. All rights reserved.

READY FOR PICKUP
=================

Hi {{ $repair->customer_name }},

Excellent news! Your device repair is complete and has passed all quality checks. It's ready for you to pick up!

PICKUP INFORMATION
==================

Tracking Number: {{ $repair->invoice_number }}
Device: {{ $repair->device_brand }} {{ $repair->device_model }}
Status: Ready for Pickup
Total Repair Cost: ₦{{ number_format($repair->cost_actual ?? $repair->cost_estimate, 2) }}

HOW TO PICK UP
==============

Visit Us In Person:
Address: {{ config('company.address') ?? 'Our Service Center' }}
Hours: Monday - Friday, 9:00 AM - 6:00 PM
Saturday: 10:00 AM - 4:00 PM
Closed: Sundays & Public Holidays

Bring your tracking number or any valid ID

Alternative: Request Delivery
We offer safe delivery service for an additional fee. Contact us to arrange!

WHAT YOU'LL RECEIVE
===================

When you pick up your device:
- Your fully repaired and tested device
- 30-day warranty certificate
- Final invoice and receipt
- Device care and maintenance guide
- Parts warranty information

BEFORE PICKUP
=============

Please check:
1. Test Your Device - We've tested it, but verify it works for you
2. Inspect for Cleanliness - Should look as good as new
3. Take Photos/Video - For your records if needed
4. Keep Your Receipt - Important for warranty claims

Schedule Pickup or Delivery:
{{ route('repairs.track.page') }}

Your Tracking Number: {{ $repair->invoice_number }}

---

Thank you for choosing Skyeface! We hope your device serves you well.

Skyeface Repair System
Copyright © {{ date('Y') }} Skyeface. All rights reserved.

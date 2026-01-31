REPAIR COMPLETE
================

Hi {{ $repair->customer_name }},

Your device repair is complete and has been successfully delivered to you. Thank you for your business.

REPAIR SUMMARY
==============

Tracking Number: {{ $repair->invoice_number }}
Device: {{ $repair->device_brand }} {{ $repair->device_model }}
Status: Complete
Total Repair Cost: ₦{{ number_format($repair->cost_actual ?? 0, 2) }}
Warranty: 30 Days

@if($notes)
Completion Notes:
{{ $notes }}
@endif

YOUR WARRANTY (30 DAYS)
=======================

Your device includes a 30-day warranty from pickup date covering:
- Replaced parts
- Repair work
- Original issues only

If you experience any issues:
1. Contact us right away
2. Bring your device and receipt
3. We will repair at no charge

Warranty Support:
- Email: {{ config('company.email') }}
- Phone: {{ config('company.phone') }}
- Mon-Sat: 9:00 AM - 6:00 PM

CARE INSTRUCTIONS
=================

To keep your device working well:
- Keep it clean with a soft cloth
- Avoid heat and moisture
- Use original chargers when possible
- Regular software updates
- Avoid physical impact or drops
- Store in a cool, dry place

SHARE YOUR EXPERIENCE
=====================

We'd love to hear how we did! Rate your experience and help other customers choose us:
{{ route('home') }}

NEED MORE HELP?
===============

Have questions about your repaired device or need more information? We're here to help:
- WhatsApp: {{ config('company.phone') }}
- Email: {{ config('company.email') }}
- Visit us: {{ config('company.address') ?? 'Our Service Center' }}

---

Thank you for choosing Skyeface! We appreciate your business and hope your device serves you well for years to come.

Skyeface Repair System
Copyright © {{ date('Y') }} Skyeface. All rights reserved.

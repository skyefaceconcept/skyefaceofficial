# Device Repair Pricing Structure

## Overview
When customers fill out the device repair booking form, they automatically see the estimated diagnosis fee based on the device type they select. This pricing is displayed dynamically as they choose their device.

## Device Type Pricing

| Device Type | Diagnosis Fee |
|-------------|--------------|
| Laptop | $35.00 |
| Desktop Computer | $30.00 |
| Mobile Phone | $25.00 |
| Tablet | $28.00 |
| Printer | $40.00 |
| Other Electronic Device | $50.00 |

## How It Works

### 1. On Booking Form (services page)
- Customer selects device type from dropdown
- Price automatically displays in a highlighted box
- Shows: "Estimated Diagnosis Fee: $XX.XX"
- Hidden note explains prices may vary after diagnosis
- Price is stored as `cost_estimate` in the database

### 2. On Repair Tracking Page
- Customers search for their repair by tracking number
- Estimated cost displays in repair info section
- Shows either the diagnosis fee or "TBD - After Diagnosis"
- Can be updated by admin as work progresses

## Implementation Details

### Files Modified
1. **resources/views/services.blade.php**
   - Added pricing display box (lines 834-844)
   - Added JavaScript pricing logic (lines 1217-1242)
   - Pricing updates on device type change

2. **database/migrations/2026_01_20_create_repairs_table.php**
   - Added `cost_estimate` column (decimal 10,2)
   - Added `cost_actual` column for final cost
   - Both nullable for flexibility

3. **app/Http/Controllers/RepairController.php**
   - Accepts `cost_estimate` in validation
   - Stores estimate with initial repair record





























- Customers can see both estimate and final costs on tracking page- Admin can update actual cost in repair status updates- Final cost may change after technician examines device- Initial diagnosis fees are estimates only## Notes```7. Tech performs diagnosis and updates actual cost if needed   ↓6. Uses tracking number to see current status + estimated cost   ↓5. Receives tracking number via email   ↓4. Customer submits booking   ↓3. Customer sees note: "may vary after diagnosis"   ↓2. Diagnosis fee appears automatically ($25-$50 depending on device)   ↓1. Customer selects device type```## Customer Experience Flow   - Shows "TBD - After Diagnosis" if no estimate   - Displays estimated cost in customer tracking view4. **resources/views/repairs/track.blade.php**

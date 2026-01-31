# Payment Tracking System - Complete Verification ✅

## Overview
All payment transactions processed on the Skyeface portal are tracked and stored in the **Payment** table and displayed in the **Admin Payments** dashboard at `/admin/payments`.

## Payment Types Being Tracked

### 1. **Quote Payments** ✅
- **Triggered by:** Clients requesting quotes on services
- **How tracked:** Created in `PaymentController::initiateQuotePayment()`
- **Amount:** Quote total
- **Payment Methods:** Flutterwave, Paystack
- **Status:** Pending → Completed/Failed/Cancelled
- **Callback:** `PaymentController::callback()` updates Payment & Quote status

**Payment Record Fields:**
```
quote_id     → Links to quotes table
amount       → Quote total
currency     → NGN/USD
status       → pending/completed/failed/cancelled
transaction_id → Gateway transaction ID
reference    → SKYEFACE-{quoteId}-{random}
payment_source → Flutterwave or Paystack
paid_at      → Timestamp when payment completed
response_data → Gateway response (JSON)
```

### 2. **Repair Payments** ✅
- **Triggered by:** Device repair bookings
- **How tracked:** Created in `RepairController::initiateFlutterwavePayment()` or `initiatePaystackPayment()`
- **Amount:** 
  - Initially: `cost_estimate` (diagnosis fee)
  - Later: `cost_actual` (final repair cost) after admin inspection
- **Payment Methods:** Flutterwave, Paystack
- **Status:** Pending → Completed/Failed/Cancelled
- **Callback:** `RepairController::flutterwaveCallback()` or `paystackCallback()` updates Payment & Repair status

**Payment Record Fields:**
```
repair_id    → Links to repairs table
amount       → Repair cost (diagnosis fee or final cost)
currency     → NGN
status       → pending/completed/failed/cancelled
transaction_id → Gateway transaction ID
reference    → REPAIR-{repairId}-{timestamp}
payment_source → Flutterwave or Paystack
paid_at      → Timestamp when payment completed
response_data → Gateway response (JSON)
```

### 3. **Direct Payments** ✅
- **Triggered by:** Manual payment creation by admin
- **How tracked:** Can be created with neither quote_id nor repair_id
- **Amount:** Custom amount
- **Status:** Can be manually updated in admin panel

**Payment Record Fields:**
```
quote_id     → NULL (not linked to quote)
repair_id    → NULL (not linked to repair)
amount       → Custom amount
currency     → NGN/USD
status       → Manually managed
```

## Payment Tracking Flow

### Quote Payment Flow
```
1. Client requests quote
   ↓
2. Customer initiates payment via /payment/{quote}
   ↓
3. PaymentController::initiateQuotePayment() creates Payment record
   ↓
4. Payment record: status = 'pending'
   ↓
5. Customer completes payment at gateway (Paystack/Flutterwave)
   ↓
6. Gateway redirects to PaymentController::callback()
   ↓
7. Callback verifies payment with gateway
   ↓
8. Callback updates Payment: status = 'completed', paid_at = now()
   ↓
9. Callback updates Quote: status = 'accepted'
   ↓
10. Payment appears in Admin Dashboard ✅
```

### Repair Payment Flow
```
1. Customer books device repair
   ↓
2. Repair record created: status = 'Pending'
   ↓
3. Customer pays diagnosis fee
   ↓
4. RepairController::initiateFlutterwavePayment() creates Payment record
   ↓
5. Payment record: status = 'pending'
   ↓
6. Customer completes payment at gateway
   ↓
7. Gateway redirects to RepairController::flutterwaveCallback()
   ↓
8. Callback verifies payment with gateway
   ↓
9. Callback creates/updates Payment: status = 'completed'
   ↓
10. Callback updates Repair: payment_status = 'completed'
    ↓
11. Payment appears in Admin Dashboard ✅
    ↓
12. [LATER] Admin inspects device, sets final cost via /admin/repairs/{id}
    ↓
13. Repair updated: cost_actual = final amount
    ↓
14. If customer requests final payment:
    - Another Payment record created
    - Amount = cost_actual (final repair cost)
    - Same callback flow
```

## Admin Payments Dashboard Features

**Location:** `/admin/payments`

**What's Displayed:**
- Payment Reference
- Customer Name & Email
- Amount & Currency
- Payment Type (Quote/Repair/Direct)
- Processor (Flutterwave/Paystack)
- Status (Completed/Pending/Failed/Cancelled)
- Related Quote/Repair Link
- Paid Date/Created Date
- Action Buttons

**Filters Available:**
- Search by: Reference, Email, Name, Transaction ID
- Filter by: Status, Payment Type (Quote/Repair), Processor
- Date Range: From/To dates

**Statistics Displayed:**
```
Total Payments       → Count of all payments
Total Revenue        → Sum of completed payments
Completed Count      → Number of completed payments
Pending Payments     → Number of pending payments
Failed Payments      → Number of failed payments
Cancelled Payments   → Number of cancelled payments

By Type:
├─ Quote Payments    → Count of quote-related payments
└─ Repair Payments   → Count of repair-related payments

By Processor:
├─ Flutterwave       → Count of Flutterwave payments
└─ Paystack          → Count of Paystack payments
```

**Actions Available:**
- Mark as Completed
- Mark as Pending
- Mark as Failed
- Mark as Cancelled
- Refresh Status (re-verify with gateway)
- View Associated Quote/Repair

## Database Schema

```sql
CREATE TABLE payments (
    id                  BIGINT PRIMARY KEY AUTO_INCREMENT
    quote_id            BIGINT NULLABLE (FK → quotes)
    repair_id           BIGINT NULLABLE (FK → repairs)
    amount              DECIMAL(12,2)
    currency            VARCHAR(3) DEFAULT 'USD'
    status              VARCHAR(20) DEFAULT 'pending'
    transaction_id      VARCHAR(255) NULLABLE UNIQUE
    reference           VARCHAR(255) UNIQUE
    customer_email      VARCHAR(255)
    customer_name       VARCHAR(255)
    payment_method      VARCHAR(100) NULLABLE
    payment_source      VARCHAR(100) -- 'Flutterwave', 'Paystack'
    response_data       JSON NULLABLE -- Full gateway response
    paid_at             TIMESTAMP NULLABLE
    created_at          TIMESTAMP
    updated_at          TIMESTAMP
    
    INDEXES:
    - INDEX (quote_id, status)
    - INDEX (repair_id)
    - INDEX (reference)
    - INDEX (payment_source)
    - INDEX (transaction_id)
);
```

## API Endpoints for Payment Tracking

| Endpoint | Method | Purpose |
|----------|--------|---------|
| `/admin/payments` | GET | View all payments with filters |
| `/payments/{quote}` | GET | Initiate quote payment |
| `/repairs/{repair}/payment` | GET | Initiate repair payment |
| `/payment/callback` | GET/POST | Quote payment callback |
| `/repairs/flutterwave-callback` | GET | Repair payment callback (Flutterwave) |
| `/repairs/paystack-callback` | GET | Repair payment callback (Paystack) |
| `/api/payments` | GET | API list of payments (with filters) |
| `/admin/payments/{payment}/update-status` | POST | Manually update payment status |
| `/admin/payments/{payment}/refresh` | POST | Refresh payment status from gateway |

## Transaction Tracking Completeness

✅ **All payment transactions created via Flutterwave** are tracked
✅ **All payment transactions created via Paystack** are tracked
✅ **All quote payments** are created in Payment table
✅ **All repair payments** are created in Payment table
✅ **All payment statuses** are updated in Payment table
✅ **All payments visible** in admin dashboard
✅ **Payment reconciliation** possible via reference + transaction_id
✅ **Revenue reporting** available with statistics

## Verification Checklist

To verify all payments are tracked:

```bash
# Check total payments in database
php artisan tinker
> Payment::count()

# Check payments by type
> Payment::whereNotNull('quote_id')->count()     # Quote payments
> Payment::whereNotNull('repair_id')->count()    # Repair payments

# Check payments by processor
> Payment::where('payment_source', 'Flutterwave')->count()
> Payment::where('payment_source', 'Paystack')->count()

# Check payments by status
> Payment::where('status', 'completed')->count()
> Payment::where('status', 'pending')->count()

# Calculate total revenue
> Payment::where('status', 'completed')->sum('amount')

# Find specific payment
> Payment::where('reference', 'SKYEFACE-1-abc123')->first()
> Payment::where('reference', 'REPAIR-23-1234567890')->first()
```

## Reconciliation Process

**To reconcile payments with actual transactions:**

1. Go to `/admin/payments`
2. Search by customer email or reference
3. Click "Refresh Status" to verify with gateway
4. Compare:
   - Admin Payment Status → Database Payment Status
   - Gateway Response → Stored response_data (JSON)
   - Transaction ID from gateway → Stored transaction_id

**To identify missing payments:**

1. Check gateway transaction history manually
2. Search in `/admin/payments` by date range
3. If missing: 
   - Payment may have failed callback
   - Check logs: `storage/logs/laravel.log`
   - Manually create Payment record if needed

## Audit Trail

**Payment lifecycle is logged:**
- Payment creation → Logged at INFO level
- Payment verification → Logged at INFO level
- Payment status updates → Logged at INFO level
- Payment failures → Logged at WARNING/ERROR level
- Gateway responses → Stored in `response_data` JSON field

**View audit logs:**
```bash
tail -f storage/logs/laravel.log | grep -i payment
```

## Troubleshooting

**If payments not appearing in admin list:**

1. ✅ Check if Payment record created
   ```bash
   php artisan tinker
   > Payment::latest()->limit(5)->get()
   ```

2. ✅ Check callback logs
   ```bash
   tail storage/logs/laravel.log | grep -i callback
   ```

3. ✅ Verify relations
   ```bash
   > $payment = Payment::find(1)
   > $payment->quote
   > $payment->repair
   ```

4. ✅ Check payment status
   ```bash
   > Payment::where('status', 'pending')->count()
   ```

5. ✅ Refresh from gateway
   - Use "Refresh Status" button in admin panel
   - Re-verifies with Paystack/Flutterwave

## Summary

✅ **ALL payment transactions are tracked**
✅ **Quote payments → Stored in Payment table**
✅ **Repair payments → Stored in Payment table**
✅ **All payments visible in /admin/payments**
✅ **Full audit trail available**
✅ **Revenue reporting built-in**
✅ **Status reconciliation available**

**Status: COMPLETE AND VERIFIED**

---
**Last Updated:** January 24, 2026  
**Verified By:** System Administrator  
**Next Review:** Monitor for 7 days of transactions

# Admin/Payments Management via Tinker

## Quick Summary

✅ **Total Payments in System:** 6
✅ **Quote Payments:** 5 (₦2,600,000.00)
✅ **Repair Payments:** 1 (₦2,000.00)
✅ **Total Revenue:** ₦2,702,000.00
✅ **All Completed:** 100% success rate

---

## Running Scripts

We've created two useful PHP scripts that can be executed via Laravel Tinker:

### 1. **Check Payment Statistics** 
Check the status of all payments in your system.

```bash
php artisan tinker --execute="include('check-payments.php')"
```

**Output Includes:**
- Total payment count by type (Quote/Repair/Direct)
- Payments by status (Completed/Pending/Failed/Cancelled)
- Payments by processor (Flutterwave/Paystack)
- Total revenue statistics
- Latest 10 payments with details
- Detailed repair payment list
- Detailed quote payment list

### 2. **Create Test Repair Payment**
Create a test repair payment record to verify tracking works.

```bash
php artisan tinker --execute="include('create-test-repair-payment.php')"
```

**What it does:**
- Finds the latest repair in the system
- Creates a Payment record linked to that repair
- Shows payment details
- Updates and displays statistics
- Confirms payment appears in admin dashboard

---

## Manual Tinker Commands

You can also open Tinker interactively and run commands:

```bash
php artisan tinker
```

Then execute commands:

### Get Payment Counts
```php
Payment::count()                           // Total payments
Payment::whereNotNull('quote_id')->count() // Quote payments only
Payment::whereNotNull('repair_id')->count() // Repair payments only
```

### Get Payments by Status
```php
Payment::where('status', 'completed')->count()
Payment::where('status', 'pending')->count()
Payment::where('status', 'failed')->count()
Payment::where('status', 'cancelled')->count()
```

### Get Payments by Processor
```php
Payment::where('payment_source', 'Flutterwave')->count()
Payment::where('payment_source', 'Paystack')->count()
```

### Calculate Revenue
```php
Payment::where('status', 'completed')->sum('amount')  // Total completed
Payment::where('status', 'pending')->sum('amount')    // Total pending
```

### View Specific Payments
```php
// Get all payments
Payment::all()

// Get latest 5 payments
Payment::latest()->limit(5)->get()

// Get payment by ID
Payment::find(1)

// Get payment by reference
Payment::where('reference', 'SKYEFACE-1-abc123')->first()
```

### Get Payment with Relations
```php
// Load quote relationship
Payment::with('quote')->find(1)

// Load repair relationship  
Payment::with('repair')->find(9)

// Get repair payments with repair details
Payment::whereNotNull('repair_id')->with('repair')->get()

// Get quote payments with quote details
Payment::whereNotNull('quote_id')->with('quote')->get()
```

### Create a Payment Manually
```php
// Create a quote payment
Payment::create([
    'quote_id' => 1,
    'amount' => 5000,
    'currency' => 'NGN',
    'status' => 'completed',
    'reference' => 'SKYEFACE-1-manual',
    'customer_email' => 'customer@example.com',
    'customer_name' => 'John Doe',
    'payment_source' => 'Paystack',
    'transaction_id' => 'PST-123456789',
    'paid_at' => now(),
])

// Create a repair payment
Payment::create([
    'repair_id' => 23,
    'amount' => 2000,
    'currency' => 'NGN',
    'status' => 'completed',
    'reference' => 'REPAIR-23-1234567890',
    'customer_email' => 'customer@example.com',
    'customer_name' => 'John Doe',
    'payment_source' => 'Paystack',
    'transaction_id' => 'PST-987654321',
    'paid_at' => now(),
])
```

### Update a Payment
```php
$payment = Payment::find(1);
$payment->update(['status' => 'completed', 'paid_at' => now()]);

// Or
Payment::where('reference', 'SKYEFACE-1-abc123')->update(['status' => 'completed']);
```

### Delete a Payment
```php
Payment::find(1)->delete()
```

### Filter Payments
```php
// Get payments in date range
Payment::whereBetween('created_at', [
    '2026-01-20',
    '2026-01-25'
])->get()

// Get pending payments
Payment::where('status', 'pending')->get()

// Get payments over ₦100,000
Payment::where('amount', '>', 100000)->get()

// Get payments for specific customer
Payment::where('customer_email', 'customer@example.com')->get()
```

---

## Current System Status

### Payment Statistics (Updated Jan 25, 2026)

```
Total Payments:           6
├─ Quote Payments:        5
├─ Repair Payments:       1
└─ Direct Payments:       0

By Status:
├─ Completed:            6 (100%)
├─ Pending:              0
├─ Failed:               0
└─ Cancelled:            0

By Processor:
├─ Paystack:             2
└─ Flutterwave:          0

Revenue:
├─ Total Completed:      ₦2,702,000.00
└─ Total Pending:        ₦0.00
```

### Latest Payments

| ID | Type | Amount | Status | Processor | Date |
|----|------|--------|--------|-----------|------|
| 9 | Repair | ₦2,000.00 | Completed | Paystack | 2026-01-25 00:04:42 |
| 7 | Quote | ₦100,000.00 | Completed | Paystack | 2026-01-14 19:44:58 |
| 6 | Quote | ₦1,000,000.00 | Completed | - | 2026-01-13 16:58:40 |
| 5 | Quote | ₦1,000,000.00 | Completed | - | 2026-01-12 22:24:18 |
| 4 | Quote | ₦200,000.00 | Completed | - | 2026-01-12 21:26:28 |
| 3 | Quote | ₦400,000.00 | Completed | - | 2026-01-12 21:10:17 |

---

## Useful Queries

### Export Payment Data
```php
// Get all payment data for reporting
$payments = Payment::with(['quote', 'repair'])->get();
$payments->each(function($p) {
    echo $p->id . "|" . $p->reference . "|" . $p->amount . "|" . $p->status . "\n";
});
```

### Find Orphaned Payments
```php
// Payments with no quote or repair
Payment::whereNull('quote_id')->whereNull('repair_id')->get()
```

### Get Payment Summary by Date
```php
$payments = Payment::where('status', 'completed')
    ->whereBetween('created_at', ['2026-01-20', '2026-01-31'])
    ->get();
echo $payments->sum('amount');
```

### Find Payments by Customer
```php
Payment::where('customer_email', 'skyefacecon@gmail.com')->get()
```

---

## Exit Tinker

```php
exit
```

or press `Ctrl+C`

---

## Notes

✅ All payments are tracked in the Payment model
✅ Each payment links to either a quote or repair (or both/neither for direct)
✅ Payment status can be: pending, completed, failed, cancelled
✅ All payment callbacks update the Payment record automatically
✅ Admin panel at `/admin/payments` shows all tracked payments
✅ Revenue reporting available via sum() queries
✅ Full transaction history preserved in response_data JSON field

---

**Last Updated:** January 25, 2026
**Tinker Version:** Laravel 11.x
**Database:** MySQL

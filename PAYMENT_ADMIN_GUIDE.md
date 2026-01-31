# Payment Admin Guide - Tinker Commands

## Quick Overview

**Current System Status (as of Jan 25, 2026):**
- ✅ **6 Total Payments** tracked
- ✅ **100% Completion Rate** (all payments completed)
- ✅ **₦2,702,000** verified revenue
- ✅ **5 Quote Payments** + **1 Repair Payment**
- ✅ **Admin Dashboard** fully functional at `/admin/payments`

---

## Viewing Payment Data

### 1. View Dashboard via Tinker
```bash
php artisan tinker --execute="include('payment-dashboard.php')"
```
Shows comprehensive statistics, breakdown by type/processor, and recent payments.

### 2. View All Payments
```bash
php artisan tinker
Payment::all();
```

### 3. View Specific Payment
```bash
Payment::find(9);  # Replace 9 with payment ID
```

### 4. View Payment With Relations
```bash
Payment::with(['quote', 'repair'])->find(9);
```

---

## Filtering Payments

### By Status
```bash
Payment::where('status', 'completed')->get();
Payment::where('status', 'pending')->get();
Payment::where('status', 'failed')->get();
```

### By Type
```bash
# Quote Payments Only
Payment::whereNotNull('quote_id')->with('quote')->get();

# Repair Payments Only
Payment::whereNotNull('repair_id')->with('repair')->get();

# Direct Payments (no quote or repair)
Payment::whereNull('quote_id')->whereNull('repair_id')->get();
```

### By Processor
```bash
Payment::where('payment_source', 'like', '%Paystack%')->get();
Payment::where('payment_source', 'like', '%Flutterwave%')->get();
```

### By Customer
```bash
Payment::where('customer_email', 'email@example.com')->get();
Payment::where('customer_name', 'like', '%John%')->get();
```

### By Reference
```bash
Payment::where('reference', 'REPAIR-23-1769295882')->first();
```

### By Date Range
```bash
# Payments from today
Payment::where('created_at', '>=', today())->get();

# Payments from last 7 days
Payment::where('created_at', '>=', now()->subDays(7))->get();

# Payments in January 2026
Payment::whereBetween('created_at', [
    '2026-01-01 00:00:00',
    '2026-01-31 23:59:59'
])->get();
```

---

## Revenue Reports

### Total Completed Revenue
```bash
Payment::where('status', 'completed')->sum('amount');
```

### Revenue by Type
```bash
Payment::whereNotNull('quote_id')->sum('amount');  # Quote revenue
Payment::whereNotNull('repair_id')->sum('amount');  # Repair revenue
```

### Revenue by Processor
```bash
Payment::where('payment_source', 'like', '%Paystack%')->sum('amount');
Payment::where('payment_source', 'like', '%Flutterwave%')->sum('amount');
```

### Revenue by Date
```bash
# Today's revenue
Payment::where('created_at', '>=', today())->where('status', 'completed')->sum('amount');

# This month's revenue
Payment::whereBetween('created_at', [
    now()->startOfMonth(),
    now()->endOfMonth()
])->where('status', 'completed')->sum('amount');
```

---

## Payment Management

### Create a Payment (Manual Entry)
```bash
Payment::create([
    'quote_id' => null,           # Set to quote ID if quote payment
    'repair_id' => 23,             # Set to repair ID if repair payment
    'customer_name' => 'John Doe',
    'customer_email' => 'john@example.com',
    'amount' => 50000,
    'currency' => 'NGN',
    'status' => 'completed',
    'transaction_id' => 'MANUAL-123456',
    'reference' => 'MANUAL-23-' . time(),
    'payment_source' => 'Paystack',
    'paid_at' => now()
]);
```

### Update Payment Status
```bash
$payment = Payment::find(9);
$payment->update(['status' => 'completed']);
$payment->save();
```

### Mark as Pending
```bash
Payment::find(9)->update(['status' => 'pending']);
```

### Mark as Failed
```bash
Payment::find(9)->update(['status' => 'failed']);
```

### Update Payment Amount
```bash
Payment::find(9)->update(['amount' => 75000]);
```

### Delete a Payment (Use with caution!)
```bash
Payment::find(9)->delete();
```

---

## Verification Commands

### Count Payments by Status
```bash
Payment::groupBy('status')->selectRaw('status, count(*) as total')->get();
```

### List All Payment References
```bash
Payment::pluck('reference');
```

### Find Duplicate Transactions
```bash
Payment::selectRaw('transaction_id, count(*) as total')
    ->groupBy('transaction_id')
    ->having('total', '>', 1)
    ->get();
```

### Payment Success Rate
```bash
$total = Payment::count();
$completed = Payment::where('status', 'completed')->count();
echo "Success Rate: " . round(($completed / $total) * 100, 2) . "%";
```

### Average Transaction Amount
```bash
Payment::where('status', 'completed')->avg('amount');
```

---

## Admin Dashboard Access

**URL:** `http://skyeface.local/admin/payments`

**Features:**
- View all payments with details
- Filter by status, type, processor, or date
- Search by reference, email, or name
- View payment statistics and charts
- Update payment status
- Refresh status from payment gateway
- View associated quote or repair

---

## Testing & Creating Test Data

### Create Test Repair Payment
```bash
php artisan tinker --execute="include('create-test-repair-payment.php')"
```

### View Current Statistics
```bash
php artisan tinker --execute="include('check-payments.php')"
```

---

## Troubleshooting

### Payment Not Appearing in Admin Dashboard
1. Run: `php artisan tinker`
2. Check: `Payment::where('reference', 'YOUR_REFERENCE')->first();`
3. If payment exists but not visible, check your filters on `/admin/payments`
4. Clear browser cache and refresh

### Check Payment Logs
```bash
# View payment creation logs
tail -f storage/logs/laravel.log | grep -i payment

# View callback logs
tail -f storage/logs/laravel.log | grep -i "callback\|webhook"
```

### Verify Payment Gateway Connection
```bash
php artisan tinker
$gateway = new PaystackService();  # or FlutterwaveService
$gateway->verifyTransaction('TRANSACTION_REF');
```

### Check Database
```bash
# View tables
php artisan tinker
DB::table('payments')->count();
DB::table('payments')->latest()->first();
```

---

## Common Scenarios

### Scenario 1: Customer Paid but Payment Shows Pending
```bash
$payment = Payment::where('reference', 'YOUR_REFERENCE')->first();
$payment->update(['status' => 'completed']);
```

### Scenario 2: Find All Payments for a Customer
```bash
Payment::where('customer_email', 'customer@example.com')->get();
```

### Scenario 3: Get Yesterday's Revenue
```bash
Payment::where('created_at', '>=', now()->subDay()->startOfDay())
        ->where('created_at', '<', now()->startOfDay())
        ->where('status', 'completed')
        ->sum('amount');
```

### Scenario 4: Export Payment List to CSV
```bash
$payments = Payment::with(['quote', 'repair'])->get();
// Then use your preferred CSV export method
```

---

## Key Payment Fields

| Field | Description | Example |
|-------|-------------|---------|
| `id` | Payment ID | 9 |
| `quote_id` | Associated quote (if any) | 1 |
| `repair_id` | Associated repair (if any) | 23 |
| `amount` | Payment amount | 2000 |
| `currency` | Currency code | NGN |
| `status` | Payment status | completed |
| `transaction_id` | Gateway transaction ID | PAY-XXXXX |
| `reference` | Custom reference | REPAIR-23-1769295882 |
| `payment_source` | Payment processor | Paystack |
| `customer_name` | Customer name | John Doe |
| `customer_email` | Customer email | john@example.com |
| `paid_at` | Payment confirmation time | 2026-01-25 00:04:42 |
| `created_at` | Record creation time | 2026-01-25 00:04:42 |

---

## Quick Start

1. **View Dashboard:**
   ```bash
   php artisan tinker --execute="include('payment-dashboard.php')"
   ```

2. **Enter Tinker Shell:**
   ```bash
   php artisan tinker
   ```

3. **Find a Payment:**
   ```bash
   Payment::find(9);
   ```

4. **Exit Tinker:**
   ```bash
   exit
   ```

---

## Support

All payment data is stored in the `payments` table and synchronized with:
- **Payment Gateways:** Paystack, Flutterwave
- **Quote System:** Quote payments linked to `quotes` table
- **Repair System:** Repair payments linked to `repairs` table
- **Admin Dashboard:** `/admin/payments` for visual management

**Callbacks automatically create Payment records - manual entry should be rare!**

---

*Last Updated: 2026-01-25 00:06:42*
*System Status: ✅ Fully Operational*
*Current Balance: ₦2,702,000.00*

# Web Application Shop - Payment Flow Testing & Verification Guide

## Overview
This document explains the complete payment flow for web application orders and how to test and verify it.

## System Architecture

### Flow Diagram:
```
1. Customer fills checkout form
   ↓
2. Order created (status: pending)
   ↓
3. Payment page shown
   ↓
4. Customer completes payment via Paystack/Flutterwave
   ↓
5. Payment gateway notifies server via callback
   ↓
6. Payment verified with payment processor
   ↓
7. Order status updated to "completed"
   ↓
8. License generated automatically
   ↓
9. Emails sent:
   - Order Completion Email (customer)
   - License Activation Email (customer)
   - Payment Notification Email (admin)
   ↓
10. Customer sees success page
```

## Components Involved

### 1. Checkout Controller (`app/Http/Controllers/CheckoutController.php`)
- **Method**: `store()`
- **Purpose**: Creates order and redirects to payment
- **Key Actions**:
  - Validates form input
  - Creates Order model with status = "pending"
  - Redirects to payment processor

### 2. Payment Controller (`app/Http/Controllers/PaymentController.php`)
- **Methods**:
  - `callback()`: Handles payment gateway callbacks
  - `handlePaystackCallback()`: Processes Paystack payments
  - `handleFlutterwaveCallback()`: Processes Flutterwave payments
  - `sendPaymentEmails()`: Sends confirmation emails
  
- **KEY FIX APPLIED**: Now calls `Order::markAsCompleted()` instead of just updating status
  - This ensures license generation happens
  - This ensures proper emails are sent

### 3. Order Model (`app/Models/Order.php`)
- **Key Method**: `markAsCompleted($transactionRef)`
  - Updates order status to "completed"
  - Generates license via `LicenseService::generateLicense()`
  - Sends OrderCompletedMail
  - Sends LicenseActivationMail

### 4. License Service (`app/Services/LicenseService.php`)
- **Key Method**: `generateLicense(Order $order)`
  - Generates unique license code: `SKYEFACE-XXXXX-XXXXX-XXXXX-XXXXX`
  - Calculates expiry date based on license duration:
    - 6months = 180 days
    - 1year = 365 days
    - 2years = 730 days
  - Creates License record in database
  - Sets status = "active"

### 5. Email Templates
- **order-completed.blade.php**: Summary of order
- **license-activation.blade.php**: License code and activation instructions
- **PaymentCompletedMail.php**: Generic payment confirmation

## Testing Checklist

### Pre-Testing Setup
- [ ] Mail configuration is correct (check `.env` MAIL_* settings)
- [ ] Database migrations are run (`php artisan migrate:status`)
- [ ] At least one Portfolio with pricing is created
- [ ] Company email is configured in `company.email` setting

### Test 1: Create a Test Web Application Portfolio
```sql
INSERT INTO portfolios (
  title, 
  description, 
  price, 
  price_6months, 
  price_1year, 
  price_2years, 
  category, 
  slug, 
  status, 
  created_at, 
  updated_at
) VALUES (
  'Test Web App',
  'Test Application',
  15000,
  5000,
  10000,
  18000,
  'web-apps',
  'test-web-app',
  'published',
  NOW(),
  NOW()
);
```

### Test 2: Complete Order Flow
1. Go to shop and select the test product
2. Fill checkout form with test data:
   - Name: Test User
   - Email: test@example.com
   - Phone: +2348012345678
   - Address: 123 Test Street
   - City: Lagos
   - State: Lagos
   - Zip: 100001
   - Country: Nigeria
3. Select payment method (Paystack or Flutterwave)
4. Accept terms and proceed to payment
5. Complete payment with test card:
   - Card: 4123450131001381 (Flutterwave)
   - Card: 4111111111111111 (Paystack)
   - Expiry: Any future date
   - CVV: Any 3 digits

### Test 3: Verify Order Status
```php
// In Tinker or console
$order = Order::latest()->first();
echo $order->status; // Should be "completed"
echo $order->customer_email; // Should be test email
```

### Test 4: Verify License Generated
```php
$license = License::where('order_id', $order->id)->first();
echo $license->license_code; // Should print license code
echo $license->status; // Should be "active"
echo $license->expiry_date; // Should be future date
```

### Test 5: Check Emails Sent
```php
// Check mail logs
$logs = MailLog::where('recipient', 'test@example.com')->get();
foreach ($logs as $log) {
    echo $log->subject . "\n"; // Should see multiple emails
}
```

## What Should Happen

### On Successful Payment:
1. **Database Changes**:
   - Order status changes from "pending" to "completed"
   - License created with:
     - Unique license code
     - Status = "active"
     - Expiry date set
   - Payment marked as completed

2. **Emails Sent** (3 total):
   - **To Customer** - Order Completed Email
     - Subject: "Your Order is Complete - [Application Name]"
     - Contains license code
     - Shows order summary
   
   - **To Customer** - License Activation Email
     - Subject: "Your License Code - [Application Name]"
     - Contains detailed activation instructions
     - License code and expiry date
   
   - **To Admin** - Payment Notification
     - Subject: "Payment Completed"
     - Contains order and customer details

3. **User Experience**:
   - Redirected to payment success page
   - Shows message: "Payment completed successfully!"
   - Displays order confirmation

## Troubleshooting

### Issue: No Emails Sent
**Check**:
1. Mail configuration in `.env`:
   ```bash
   MAIL_MAILER=smtp
   MAIL_HOST=mail.skyeface.com.ng
   MAIL_PORT=465
   MAIL_USERNAME=info@skyeface.com.ng
   MAIL_ENCRYPTION=ssl
   ```

2. Check logs:
   ```bash
   tail -f storage/logs/laravel.log
   ```

3. Test mail sending:
   ```php
   Mail::raw('Test', function($message) {
       $message->to('your@email.com')->subject('Test');
   });
   ```

### Issue: License Not Generated
**Check**:
1. Verify `markAsCompleted()` is being called:
   ```bash
   grep -n "markAsCompleted" storage/logs/laravel.log
   ```

2. Check license table:
   ```sql
   SELECT * FROM licenses WHERE order_id = YOUR_ORDER_ID;
   ```

3. Check for errors in logs:
   ```bash
   grep -i "error" storage/logs/laravel.log | tail -20
   ```

### Issue: Payment Verification Fails
**Check**:
1. API keys are correct for payment processor
2. Payment processor is in "test" mode
3. Check payment logs:
   ```sql
   SELECT * FROM payments WHERE order_id = YOUR_ORDER_ID;
   ```

## Key Files to Review

| File | Purpose |
|------|---------|
| `app/Http/Controllers/CheckoutController.php` | Order creation |
| `app/Http/Controllers/PaymentController.php` | Payment handling **[FIXED]** |
| `app/Models/Order.php` | Order model with license generation |
| `app/Services/LicenseService.php` | License generation logic |
| `app/Mail/OrderCompletedMail.php` | Order completion email |
| `app/Mail/LicenseActivationMail.php` | License activation email |
| `resources/views/emails/order-completed.blade.php` | Order email template |
| `resources/views/emails/license-activation.blade.php` | License email template |

## Changes Made (January 28, 2026)

### Fixed Issues:
1. **Payment Controller Callback** - Added calls to `Order::markAsCompleted()`:
   - Paystack callback (line ~300)
   - Flutterwave callback (line ~440)

2. **Impact**: Now when a payment is completed:
   - ✅ License is automatically generated
   - ✅ License code is created and stored
   - ✅ Emails are properly sent to customer and admin
   - ✅ Order status is properly updated

## Quick Verification Steps

### Step 1: Check if fix is applied
```bash
grep -A 5 "markAsCompleted" app/Http/Controllers/PaymentController.php
```
Should see the method call in both Paystack and Flutterwave handlers.

### Step 2: Verify license service works
```php
// Create test order first
$order = Order::create([...]);

// Generate license
$license = \App\Services\LicenseService::generateLicense($order);

// Check it was created
echo $license->license_code; // Should print code
```

### Step 3: Test complete flow
Use test payment processor to simulate payment and verify all steps complete.

## Support

For issues or questions about the shop payment flow, refer to:
- Payment processor documentation (Paystack/Flutterwave)
- Laravel Mail documentation
- Database structure: `php artisan migrate:status`

---
**Last Updated**: January 28, 2026
**Status**: ✅ Fixed and Tested

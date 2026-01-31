# Payment Processors - Quick Reference

## Access Payment Settings

**URL**: `/admin/settings/payment-processors`

Or from Admin Dashboard → Settings → Payment Processors

---

## Quick Setup Checklist

### ✅ Flutterwave
- [ ] Get Public Key from Flutterwave Dashboard (Settings → API Keys)
- [ ] Get Secret Key from Flutterwave Dashboard
- [ ] Enter keys in Flutterwave Configuration section
- [ ] Choose Environment: Sandbox (testing) or Live (production)
- [ ] Check "Enable Flutterwave"
- [ ] Click "Save Flutterwave"
- [ ] Set as Active Processor if needed

### ✅ Paystack
- [ ] Get Public Key from Paystack Dashboard (Settings → API Keys)
- [ ] Get Secret Key from Paystack Dashboard
- [ ] Enter keys in Paystack Configuration section
- [ ] Choose Environment: Test (testing) or Live (production)
- [ ] Select Currency (NGN, USD, etc.)
- [ ] Check "Enable Paystack"
- [ ] Click "Save Paystack"

---

## 🚀 Quick Start (Updated)

### 1. Switch Payment Processor (Admin)
Go to: **Admin Dashboard → Settings → Payment Processors**
- Select processor from dropdown
- Click "Switch Processor"
- All future payments use the selected processor

### 2. Check Active Processor (Code)
```php
use App\Services\PaymentProcessorService;

$processor = PaymentProcessorService::getActiveProcessor();  // 'flutterwave' or 'paystack'
```

### 3. Display Processor Info (Blade)
```blade
<x-payment-processor-info />
<!-- or -->
<x-payment-processor-badge :processor="$processor" size="md" />
<!-- or -->
<x-payment-processor-display position="top" :showWarning="true" />
```

---

## 📋 Payment Pages Using Active Processor

| Feature | Route | Controller | Auto-Switches |
|---------|-------|-----------|---------------|
| Quote Payment | `/payment/{quote}` | PaymentController | ✅ Yes |
| Repair Payment | `/repairs/{repair}/initiate-payment` | RepairController | ✅ Yes |
| Payment History | `/payment-history` | PaymentController | ✅ Yes |
| Admin Payments | `/admin/payments` | PaymentController | ✅ Yes |

---

## 🔧 Key Classes & Methods

### PaymentProcessorService
```php
PaymentProcessorService::getActiveProcessor()           // Get current processor
PaymentProcessorService::getActiveConfig()             // Get processor config
PaymentProcessorService::isConfigured()                // Check if ready
PaymentProcessorService::getSupportedCurrencies()      // Get supported currencies
PaymentProcessorService::getCurrencyDetails($code)     // Get currency info
PaymentProcessorService::formatAmount($amount)         // Format for payment
PaymentProcessorService::getProcessorName()            // Get friendly name
PaymentProcessorService::getProcessorDescription()     // Get description
PaymentProcessorService::getProcessorIcon()            // Get icon class
PaymentProcessorService::getProcessorBadgeColor()      // Get badge color
```

### PaymentProcessorHelper
```php
use App\Helpers\PaymentProcessorHelper;

PaymentProcessorHelper::canProcessPayments()            // Check if payments enabled
PaymentProcessorHelper::getProcessorStatus()            // Get full status
PaymentProcessorHelper::formatPaymentAmount($amount)    // Format for display
PaymentProcessorHelper::hasMultipleProcessors()         // Multiple configured?
PaymentProcessorHelper::getProcessorClass()             // Get CSS class
PaymentProcessorHelper::getProcessorIconClass()         // Get icon class
```

---

## 💾 Environment Variables

```env
PAYMENT_ACTIVE_PROCESSOR=flutterwave          # Active processor
FLUTTERWAVE_PUBLIC_KEY=your_key
FLUTTERWAVE_SECRET_KEY=your_key
FLUTTERWAVE_ENVIRONMENT=live                   # or sandbox
PAYSTACK_PUBLIC_KEY=your_key
PAYSTACK_SECRET_KEY=your_key
PAYSTACK_ENVIRONMENT=live                      # or test
PAYSTACK_CURRENCY=NGN
PAYMENT_CURRENCY=NGN
PAYMENT_TEST_MODE=false
```

---

## ✅ Supported Currencies

**Flutterwave:** NGN, USD, GHS, KES, UGX, ZAR, RWF  
**Paystack:** NGN, USD, GHS, ZAR

---

## 🧪 Test Credentials

### Flutterwave Test Card
```
Number: 5531 8866 5214 2950
Expiry: 09/32
CVV: 564
PIN: 1234
OTP: 12345
```

### Paystack Test Card
```
Number: 4084 0343 1234 5010
Expiry: 12/25
CVV: 408
```

---

## 🎨 New Blade Components

### Payment Processor Badge Component
```blade
<x-payment-processor-badge :processor="$processor" size="md" />
<!-- Sizes: sm (small badge), md (alert), lg (card with description) -->
```

### Payment Processor Info Component
```blade
<x-payment-processor-info :show-description="true" />
<!-- Displays processor info with supported currencies -->
```

### Payment Processor Display Component
```blade
<x-payment-processor-display position="top" :showWarning="true" />
<!-- Positions: top, sidebar, modal -->
```

---

## 📍 Key Files Updated

| File | Changes |
|------|---------|
| `app/Services/PaymentProcessorService.php` | Enhanced with 20+ new helper methods |
| `app/Helpers/PaymentProcessorHelper.php` | NEW - Convenient helper functions |
| `app/Http/Controllers/PaymentController.php` | Now uses active processor dynamically |
| `app/Http/Controllers/RepairController.php` | Now uses active processor automatically |
| `resources/views/payment/form.blade.php` | Updated to use active processor info |
| `resources/views/components/payment-processor-badge.blade.php` | NEW - Badge component |
| `resources/views/components/payment-processor-info.blade.php` | NEW - Info component |
| `resources/views/components/payment-processor-display.blade.php` | NEW - Display component |

---

## 🐛 Common Issues & Fixes

| Problem | Solution |
|---------|----------|
| Processor not switching | `php artisan config:clear` |
| Keys not recognized | Verify in Admin Settings |
| Currency not supported | Check `getSupportedCurrencies()` |
| Webhook not firing | Configure in processor dashboard |

---

## 📚 Full Documentation

**Complete Guide:** See `PAYMENT_PROCESSOR_SWITCHING_GUIDE.md`  
**Admin Settings:** `/admin/settings/payment-processors`

---

**Last Updated:** January 24, 2026  
**Status:** ✅ Professional & Production-Ready


**Quick Tip**: Always test with sandbox/test environment before switching to live!---- **Settings Page**: /admin/settings/payment-processors- **Full Documentation**: See PAYMENT_PROCESSOR_SETUP.md- **Paystack Dashboard**: https://dashboard.paystack.com- **Flutterwave Dashboard**: https://dashboard.flutterwave.com## Useful Links---| Webhook errors | Verify webhook secret and webhook URL || Transactions failing | Check active processor, verify test/live mode || "Connection Failed" | Verify processor is online, check firewall || "Invalid API Keys" | Check keys are copied correctly, no extra spaces ||-------|----------|| Issue | Solution |## Common Issues---| Active (green badge) | Currently active processor || ⚠ Not Configured | Missing API keys || ✓ Configured | Processor has valid API keys ||--------|---------|| Status | Meaning |## Status Indicators---4. Done! All new payments will use the selected processor3. Click "Switch Processor"2. Under "Active Processor", select the processor1. Go to Payment Processors Settings## Switching Processors---```PAYMENT_TEST_MODE=falsePAYMENT_TIMEOUT=30PAYMENT_CURRENCY=NGNPAYSTACK_ENABLED=truePAYSTACK_CURRENCY=NGNPAYSTACK_ENVIRONMENT=testPAYSTACK_SECRET_KEY=sk_test_xxxxxPAYSTACK_PUBLIC_KEY=pk_test_xxxxxFLUTTERWAVE_ENABLED=trueFLUTTERWAVE_ENVIRONMENT=sandboxFLUTTERWAVE_SECRET_KEY=sk_test_xxxxxFLUTTERWAVE_PUBLIC_KEY=pk_test_xxxxxPAYMENT_ACTIVE_PROCESSOR=flutterwave```envSave these in your `.env` file:## Environment Variables---```Exp: 01/25, CVV: 123Failed: 4000000000000002
Successful: 4111111111111111```### Paystack (Test)```Exp: Any future date, CVV: Any 3 digitsFailed: 4007410000000005Successful: 4242424242424242```### Flutterwave (Sandbox)## Test Cards---- [ ] Enable Test Mode only if developing- [ ] Configure Webhook Secret- [ ] Set Payment Timeout (5-120 minutes)- [ ] Set Default Currency### ✅ Global Settings- [ ] Set as Active Processor if needed

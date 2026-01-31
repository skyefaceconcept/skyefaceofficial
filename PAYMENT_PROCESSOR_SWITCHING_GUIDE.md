# Payment Processor Switching System - Complete Guide

## Overview
The Skyeface payment system supports seamless switching between **Flutterwave** and **Paystack** payment processors. The system intelligently manages processor configuration, switching, and fallback scenarios.

---

## System Architecture

### Key Components

1. **PaymentProcessorService** - Core service handling all processor operations
2. **PaymentProcessorHelper** - Convenient helper functions for common operations
3. **PaymentController** - Handles quote payments
4. **RepairController** - Handles repair booking payments
5. **Blade Components** - UI components for processor display

---

## Configuration

### Environment Variables (.env)

```env
# Active Payment Processor (flutterwave or paystack)
PAYMENT_ACTIVE_PROCESSOR=flutterwave

# Flutterwave Configuration
FLUTTERWAVE_PUBLIC_KEY=your_flutterwave_public_key
FLUTTERWAVE_SECRET_KEY=your_flutterwave_secret_key
FLUTTERWAVE_ENCRYPT_KEY=your_flutterwave_encrypt_key
FLUTTERWAVE_ENVIRONMENT=live  # live or sandbox
FLUTTERWAVE_ENABLED=true

# Paystack Configuration
PAYSTACK_PUBLIC_KEY=your_paystack_public_key
PAYSTACK_SECRET_KEY=your_paystack_secret_key
PAYSTACK_ENVIRONMENT=live  # live or test
PAYSTACK_CURRENCY=NGN
PAYSTACK_ENABLED=true

# Global Payment Settings
PAYMENT_CURRENCY=NGN
PAYMENT_TIMEOUT=30
PAYMENT_WEBHOOK_SECRET=your_webhook_secret
PAYMENT_TEST_MODE=false
```

---

## API & Usage Guide

### PaymentProcessorService Methods

#### Getting the Active Processor
```php
use App\Services\PaymentProcessorService;

// Get active processor name
$processor = PaymentProcessorService::getActiveProcessor();  // Returns: 'flutterwave' or 'paystack'

// Get processor configuration
$config = PaymentProcessorService::getActiveConfig();

// Get public key
$publicKey = PaymentProcessorService::getPublicKey();

// Get secret key
$secretKey = PaymentProcessorService::getSecretKey();
```

#### Checking Processor Status
```php
// Check if configured and ready
if (PaymentProcessorService::isConfigured()) {
    // Safe to process payments
}

// Check if test mode is enabled
if (PaymentProcessorService::isTestMode()) {
    // In test mode
}

// Get processor name for display
$name = PaymentProcessorService::getProcessorName();  // Returns: 'Flutterwave' or 'Paystack'
```

#### Processor Utilities
```php
// Get base URL for API calls
$baseUrl = PaymentProcessorService::getBaseUrl();

// Get all configured processors
$configured = PaymentProcessorService::getConfiguredProcessors();
// Returns: ['flutterwave' => 'Flutterwave', 'paystack' => 'Paystack']

// Get fallback processor if current one fails
$fallback = PaymentProcessorService::getFallbackProcessor();

// Get processor description
$description = PaymentProcessorService::getProcessorDescription();
```

#### Currency Handling
```php
// Get default currency
$currency = PaymentProcessorService::getCurrency();  // Returns: 'NGN'

// Get currency symbol
$symbol = PaymentProcessorService::getCurrencySymbol('NGN');  // Returns: '₦'

// Check if processor supports a currency
$supports = PaymentProcessorService::supportsCurrency('USD');  // Returns: true/false

// Get all supported currencies
$currencies = PaymentProcessorService::getSupportedCurrencies();
// Flutterwave: ['NGN', 'USD', 'GHS', 'KES', 'UGX', 'ZAR', 'RWF']
// Paystack: ['NGN', 'USD', 'GHS', 'ZAR']

// Get currency details
$details = PaymentProcessorService::getCurrencyDetails('NGN');
// Returns: ['name' => 'Nigerian Naira', 'symbol' => '₦', 'flag' => '🇳🇬']
```

#### Payment Operations
```php
// Format amount for processor (usually multiply by 100)
$formatted = PaymentProcessorService::formatAmount(1000);  // Returns: 100000

// Get payment metadata
$metadata = PaymentProcessorService::getPaymentMetadata($quote);

// Validate payment data
$validation = PaymentProcessorService::validatePaymentData([
    'email' => 'user@example.com',
    'amount' => 1000,
    'currency' => 'NGN',
]);

// Get callback/webhook URLs
$callbackUrl = PaymentProcessorService::getCallbackUrl();
$webhookUrl = PaymentProcessorService::getWebhookUrl();
```

#### UI/Display Methods
```php
// Get badge color for styling
$color = PaymentProcessorService::getProcessorBadgeColor();  // 'primary' or 'success'

// Get icon class
$icon = PaymentProcessorService::getProcessorIcon();  // 'fa-credit-card' or 'fa-money-check'

// Get processor description
$desc = PaymentProcessorService::getProcessorDescription();

// Get success/error messages
$success = PaymentProcessorService::getSuccessMessage();
$error = PaymentProcessorService::getErrorMessage();
```

### PaymentProcessorHelper Methods

```php
use App\Helpers\PaymentProcessorHelper;

// Get processor with fallback support
$processor = PaymentProcessorHelper::getActiveProcessorWithFallback();

// Check if we can process payments
if (PaymentProcessorHelper::canProcessPayments()) {
    // Safe to show payment options
}

// Get processor status for display
$status = PaymentProcessorHelper::getProcessorStatus();
// Returns: [
//     'processor' => 'flutterwave',
//     'name' => 'Flutterwave',
//     'configured' => true,
//     'test_mode' => false,
//     'status' => 'Ready',
//     'status_class' => 'success'
// ]

// Format payment amount for display
$formatted = PaymentProcessorHelper::formatPaymentAmount(1000, 'NGN');  // Returns: '₦1000.00'

// Validate payment configuration
$validation = PaymentProcessorHelper::validatePaymentConfig();

// Get CSS class for styling
$class = PaymentProcessorHelper::getProcessorClass();  // 'badge-primary' or 'badge-success'

// Get icon class
$icon = PaymentProcessorHelper::getProcessorIconClass();

// Check if multiple processors are configured
if (PaymentProcessorHelper::hasMultipleProcessors()) {
    // Show processor selection option
}

// Get list of configured processors
$processors = PaymentProcessorHelper::getConfiguredProcessorsList();

// Get payment metadata for logging
$metadata = PaymentProcessorHelper::getPaymentMetadata($quote);

// Log processor action
PaymentProcessorHelper::logProcessorAction('payment_created', ['amount' => 1000]);

// Switch processor
$result = PaymentProcessorHelper::switchProcessor('paystack');
// Returns: ['success' => true, 'processor' => 'paystack', 'message' => '...']
```

---

## Usage in Controllers

### Quote Payment Example (PaymentController)
```php
public function showPaymentForm($quoteId)
{
    $quote = Quote::findOrFail($quoteId);
    $processor = PaymentProcessorService::getActiveProcessor();
    $service = $this->getPaymentService();

    return view('payment.form', [
        'quote' => $quote,
        'publicKey' => $service->getPublicKey(),
        'processor' => $processor,
    ]);
}
```

### Repair Payment Example (RepairController)
```php
public function initiateRepairPayment(Request $request, Repair $repair)
{
    // Use active processor if not specified
    $processor = $request->input('processor') 
        ?? PaymentProcessorService::getActiveProcessor();
    
    // Process payment using the determined processor
    if ($processor === 'flutterwave') {
        return $this->initiateFlutterwavePayment($repair, ...);
    } else {
        return $this->initiatePaystackPayment($repair, ...);
    }
}
```

---

## Usage in Blade Views

### Display Processor Information
```blade
@php
    use App\Services\PaymentProcessorService;
    $processor = PaymentProcessorService::getActiveProcessor();
@endphp

<!-- Simple Display -->
<p>Processing with {{ ucfirst($processor) }}</p>

<!-- With Badge -->
<span class="badge badge-{{ PaymentProcessorService::getProcessorBadgeColor() }}">
    <i class="fa {{ PaymentProcessorService::getProcessorIcon() }}"></i>
    {{ ucfirst($processor) }}
</span>
```

### Using Components
```blade
<!-- Payment Processor Badge Component -->
<x-payment-processor-badge :processor="$processor" size="md" />

<!-- Payment Processor Info Component -->
<x-payment-processor-info :show-description="true" />

<!-- Payment Processor Display Component -->
<x-payment-processor-display position="top" :showWarning="true" />
```

### Conditional Rendering Based on Processor
```blade
@php
    $processor = PaymentProcessorService::getActiveProcessor();
    $isFW = $processor === 'flutterwave';
@endphp

@if($isFW)
    <!-- Flutterwave specific UI -->
    <script src="https://checkout.flutterwave.com/v3.js"></script>
@else
    <!-- Paystack specific UI -->
    <script src="https://js.paystack.co/v1/inline.js"></script>
@endif
```

---

## Admin Settings Page

The admin can switch between processors and configure them at:
**Admin → Settings → Payment Processors**

### Switching Processors
1. Navigate to Payment Processors settings
2. Select desired processor from "Active Processor" dropdown
3. Click "Switch Processor"
4. All future payments will use the selected processor

### Configuring Processors
Each processor has its own configuration section where admin can:
- Enter/Update Public Key
- Enter/Update Secret Key
- Set environment (live/sandbox for Flutterwave, live/test for Paystack)
- Enable/Disable the processor

---

## Pages & Routes Using Payment Processors

### Quote Payments
- **Route**: `/payment/{quote}`
- **View**: `resources/views/payment/form.blade.php`
- **Controller**: `PaymentController@showPaymentForm`

### Repair Payments
- **Route**: `/repairs/{repair}/initiate-payment`
- **View**: Device repair booking modal
- **Controller**: `RepairController@initiateRepairPayment`

### Payment Success/Failure
- **Routes**: `/payment/success/{payment}`, `/payment/failed`
- **Views**: `resources/views/payment/success.blade.php`, `resources/views/payment/failed.blade.php`

### Payment History
- **Route**: `/payment-history`
- **View**: `resources/views/payment/client-history.blade.php`

### Admin Payment Management
- **Route**: `/admin/payments`
- **View**: `resources/views/admin/payment/admin-list.blade.php`

---

## Webhook Configuration

### Flutterwave Webhook
- **URL**: `{{ config('app.url') }}/payment/webhook`
- **Event**: Payment Verified
- **Configure in**: Flutterwave Dashboard → Settings → Webhooks

### Paystack Webhook
- **URL**: `{{ config('app.url') }}/payment/webhook`
- **Setup in**: Paystack Dashboard → Settings → API Keys & Webhooks
- **Test with**: `POST /payment/webhook` to test endpoint

---

## Testing

### Test Mode Configuration
```env
PAYMENT_TEST_MODE=true
FLUTTERWAVE_ENVIRONMENT=sandbox
PAYSTACK_ENVIRONMENT=test
```

### Test Cards

**Flutterwave Test Card:**
```
Number: 5531 8866 5214 2950
Expiry: 09/32
CVV: 564
PIN: 1234
OTP: 12345
```

**Paystack Test Card:**
```
Number: 4084 0343 1234 5010
Expiry: 12/25
CVV: 408
```

---

## Best Practices

1. **Always Validate Configuration** - Check if processor is configured before showing payment options
2. **Use Services** - Use PaymentProcessorService for all processor-related operations
3. **Log Actions** - Log payment processor switches and critical operations
4. **Handle Failures** - Implement fallback logic in case of processor failures
5. **Test Thoroughly** - Test payment flow with both processors
6. **Monitor Webhooks** - Ensure webhooks are properly configured and monitored
7. **Secure Keys** - Never commit API keys to version control
8. **Use HTTPS** - Ensure all payment communication is over HTTPS

---

## Troubleshooting

### Payment Processor Not Switching
- Check that both processors are configured in `.env`
- Verify `PAYMENT_ACTIVE_PROCESSOR` is set correctly
- Clear config cache: `php artisan config:clear`

### Payments Not Processing
- Check if processor is configured: `PaymentProcessorService::isConfigured()`
- Verify API keys in `.env` are correct
- Check webhook configuration
- Review logs in `storage/logs/laravel.log`

### Currency Not Supported
- Use `supportsCurrency()` to check if processor supports the currency
- Flutterwave supports more currencies than Paystack
- See supported currencies list in API documentation

### Webhook Issues
- Verify webhook URL in processor dashboard
- Check logs for webhook errors
- Test webhook delivery in processor dashboard settings

---

## Migration Guide (from Single Processor)

If migrating from a single processor:

1. Add new processor credentials to `.env`
2. Configure new processor in Admin Settings
3. Test payment flow with new processor
4. When ready, switch `PAYMENT_ACTIVE_PROCESSOR` to new processor
5. Monitor logs for any issues
6. Optionally keep old processor configured as fallback

---

## Support & Additional Resources

- **Flutterwave Docs**: https://developer.flutterwave.com
- **Paystack Docs**: https://paystack.com/docs
- **GitHub Issues**: Report any payment processor issues here
- **Admin Settings**: Configure and monitor processors at `/admin/settings/payment-processors`


# Payment Processor Switching Fix

## Problem
The payment form was hardcoded to display Flutterwave information and functionality, even when the active payment processor was switched to Paystack in the `.env` file.

## Root Cause
The `PaymentController` and `payment/form.blade.php` view were hardcoded to use only the Flutterwave service, ignoring the `PAYMENT_ACTIVE_PROCESSOR` environment variable.

## Solution Implemented

### 1. Created PaystackService (`app/Services/PaystackService.php`)
A new service class that mirrors the functionality of `FlutterwaveService` but works with Paystack's API:
- `initializePayment()` - Initializes a payment transaction
- `verifyPayment()` - Verifies payment status
- `getPublicKey()` - Returns the public key for frontend
- `validateWebhook()` - Validates webhook signatures

### 2. Updated PaymentController (`app/Http/Controllers/PaymentController.php`)
Made the controller processor-agnostic:
- Added PaystackService injection alongside FlutterwaveService
- Created `getPaymentService()` method that returns the appropriate service based on active processor
- Created `getActiveProcessor()` method to check the config
- Modified `showPaymentForm()` to pass the active processor to the view
- Modified `createPayment()` to use the active processor's service
- Split callback handling into:
  - `callback()` - Routes to the appropriate processor handler
  - `handlePaystackCallback()` - Handles Paystack-specific logic
  - `handleFlutterwaveCallback()` - Handles Flutterwave-specific logic
- Added `sendPaymentEmails()` helper method for shared email logic

### 3. Updated Payment Form View (`resources/views/payment/form.blade.php`)
Made the view dynamic and processor-aware:
- Added processor detection at the top of the view
- Updated security alert text to show the correct processor name
- Updated FAQ section to reference the correct processor
- Updated payment modal info text to show the correct processor
- Conditionally load the correct payment processor script:
  - Paystack: `https://js.paystack.co/v1/inline.js`
  - Flutterwave: `https://checkout.flutterwave.com/v3.js`
- Modified JavaScript to use the dynamic processor variable
- Updated payment method in request payload to use the active processor

## Configuration

The active payment processor is controlled by the `PAYMENT_ACTIVE_PROCESSOR` environment variable:

```bash
# In .env file
PAYMENT_ACTIVE_PROCESSOR=paystack  # or 'flutterwave'
```

Both processors require their respective API keys configured:

### Paystack Configuration
```bash
PAYSTACK_PUBLIC_KEY=pk_test_...
PAYSTACK_SECRET_KEY=sk_test_...
PAYSTACK_ENVIRONMENT=test  # or 'live'
PAYSTACK_CURRENCY=NGN






















































































- Active processor is determined at request time, allowing dynamic switching without redeployment- Payment processor is stored in the `payment_source` field in the database- Both services handle webhook verification with processor-specific signatures- Paystack API expects amounts in kobo (multiply by 100) for NGN currency## Notes- No database migrations needed- Payments created with either processor can be verified- Callbacks from both processors are handled correctly- Existing Flutterwave integrations continue to workThe changes are fully backward compatible:## Backward Compatibility3. `app/Services/PaystackService.php` - NEW Paystack service (created)2. `resources/views/payment/form.blade.php` - Payment form UI1. `app/Http/Controllers/PaymentController.php` - Controller logic## Files Modified- ✅ Callback is routed to correct handler- ✅ Payment source in database shows correct processor- ✅ Payment script loaded is for correct processor- ✅ Modal text mentions correct processor- ✅ FAQ answers reference correct processor- ✅ Security alert message shows correct processor### Key Items to Verify:3. Visit payment page - it should show "Flutterwave" in all references2. Clear Laravel config cache (if applicable)   ```   PAYMENT_ACTIVE_PROCESSOR=flutterwave   ```1. Update `.env`:### To Switch Back to Flutterwave:4. Try making a test payment3. Visit payment page - it should show "Paystack" in all references2. Clear Laravel config cache (if applicable)   ```   PAYMENT_ACTIVE_PROCESSOR=paystack   ```1. Update `.env`:### To Switch to Paystack:## Testing   - UI automatically reflects the change   - Next payment will use the new processor   - No code changes needed   - Simply change `PAYMENT_ACTIVE_PROCESSOR` in `.env`4. **Switching Processors**   - Payment record and quote status are updated   - Payment is verified using correct service   - Routes to processor-specific callback handler   - Callback handler detects active processor   - User redirects back from payment processor3. **Payment Callback**   - Correct payment gateway URL is returned   - Controller uses active processor's service to initialize payment   - Form sends request to `/payment/create/{quoteId}`   - User selects currency and clicks "Pay Securely"2. **Payment Initialization**   - Correct payment script is loaded based on processor   - UI displays correct processor name and information   - View receives processor information   - Controller checks `PAYMENT_ACTIVE_PROCESSOR` config1. **Payment Form Display**## How It Works```FLUTTERWAVE_ENABLED=1FLUTTERWAVE_ENVIRONMENT=sandbox  # or 'live'FLUTTERWAVE_SECRET_KEY=sk_...FLUTTERWAVE_PUBLIC_KEY=pk_...```bash### Flutterwave Configuration  ```PAYSTACK_ENABLED=1

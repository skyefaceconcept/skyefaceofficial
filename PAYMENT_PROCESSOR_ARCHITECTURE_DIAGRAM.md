# Payment Processor Architecture Diagram

## System Components Flow

```
┌─────────────────────────────────────────────────────────────────┐
│                       ADMIN SETTINGS                             │
│          /admin/settings/payment-processors                      │
│  ┌─────────────────────────────────────────────────────────┐    │
│  │  Select Active Processor: [Flutterwave v] or [Paystack] │    │
│  │  Switch Processor → Updates PAYMENT_ACTIVE_PROCESSOR    │    │
│  └─────────────────────────────────────────────────────────┘    │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ↓
┌─────────────────────────────────────────────────────────────────┐
│                   ENVIRONMENT VARIABLES                          │
│  PAYMENT_ACTIVE_PROCESSOR=flutterwave                            │
│  FLUTTERWAVE_PUBLIC_KEY=xxx    PAYSTACK_PUBLIC_KEY=xxx          │
│  FLUTTERWAVE_SECRET_KEY=xxx    PAYSTACK_SECRET_KEY=xxx          │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ↓
┌─────────────────────────────────────────────────────────────────┐
│            PaymentProcessorService (Core)                        │
│  ┌─────────────────────────────────────────────────────────┐    │
│  │ getActiveProcessor()        → 'flutterwave' or 'paystack' │  │
│  │ isConfigured()              → Check if ready to process  │  │
│  │ getSupportedCurrencies()    → Get available currencies   │  │
│  │ getProcessorIcon()          → Get UI icon              │  │
│  │ getProcessorBadgeColor()    → Get badge color          │  │
│  │ validatePaymentData()       → Validate payment request │  │
│  │ ... 20+ more helper methods ...                         │  │
│  └─────────────────────────────────────────────────────────┘    │
└─────────────────────────────────────────────────────────────────┘
                              │
                ┌─────────────┴──────────────┐
                ↓                            ↓
    ┌──────────────────────┐    ┌──────────────────────┐
    │  PaymentController   │    │  RepairController    │
    ├──────────────────────┤    ├──────────────────────┤
    │ showPaymentForm()    │    │ initiateRepayment()  │
    │ createPayment()      │    │ paymentCallback()    │
    │ callback()           │    │ flutterwaveCallback()│
    │ success()            │    │ paystackCallback()   │
    │ failed()             │    └──────────────────────┘
    └──────────────────────┘
         ↓
    ┌──────────────────────┐
    │  PaymentProcessorHelper
    ├──────────────────────┤
    │ canProcessPayments() │
    │ getProcessorStatus() │
    │ formatPaymentAmount()│
    │ switchProcessor()    │
    │ ... helpers ...      │
    └──────────────────────┘
```

---

## Payment Pages Integration Flow

```
QUOTE PAYMENT FLOW:
├─ Client visits: /payment/{quote}
├─ PaymentController::showPaymentForm()
│  ├─ Get active processor
│  ├─ Get processor's public key
│  └─ Render payment/form.blade.php with processor info
├─ Client selects currency & clicks "Pay"
├─ PaymentController::createPayment()
│  ├─ Determine active processor
│  ├─ Use PaymentProcessorService::getPaymentService()
│  └─ Initialize payment via active service
├─ Client redirected to processor's payment page
├─ Callback: /payment/callback
│  ├─ Determine which processor sent callback
│  ├─ Verify payment status
│  └─ Update payment record
└─ Redirect to /payment/success/{payment}

REPAIR PAYMENT FLOW:
├─ Client submits repair booking
├─ RepairController::initiateRepairPayment()
│  ├─ Use active processor (auto or specified)
│  ├─ Validate processor configuration
│  └─ Initialize payment
├─ Payment modal appears in booking form
├─ Client completes payment
├─ Callback: /repairs/payment-callback
│  ├─ Verify payment
│  └─ Update repair status
└─ Confirmation email sent with processor info
```

---

## Component Usage in Views

```
Payment Form View (payment/form.blade.php):
└─ Display Current Processor Info
   ├─ <x-payment-processor-badge />
   ├─ <x-payment-processor-info />
   └─ Show processor description & supported currencies

Payment Success View:
└─ <x-payment-processor-display position="top" />
   └─ Show "Processed by Flutterwave" or "Processed by Paystack"

Admin Payment List:
└─ Show processor used for each payment
   ├─ Badge color coded by processor
   └─ Icon indicating processor type

Repair Booking Modal:
└─ Show active processor before payment
   └─ Display processor's logo/branding
```

---

## Processor Configuration & Selection

```
┌────────────────────────────────────────┐
│   Admin Changes Active Processor        │
│   From Flutterwave to Paystack          │
└────────────────────────────────────────┘
           │
           ↓
┌────────────────────────────────────────┐
│  SettingController::                   │
│  setActivePaymentProcessor()            │
│  ├─ Validate processor selection        │
│  ├─ Update .env file                    │
│  ├─ Clear config cache                  │
│  └─ Redirect with success message       │
└────────────────────────────────────────┘
           │
           ↓
┌────────────────────────────────────────┐
│  .env Updated                           │
│  PAYMENT_ACTIVE_PROCESSOR=paystack      │
│  (was: flutterwave)                     │
└────────────────────────────────────────┘
           │
           ↓
┌────────────────────────────────────────┐
│  config('payment.active_processor')     │
│  Now returns: 'paystack'                │
└────────────────────────────────────────┘
           │
           ↓
┌────────────────────────────────────────┐
│  Next Payment Automatically Uses         │
│  Paystack Service & Configuration       │
└────────────────────────────────────────┘
```

---

## Currency Support Matrix

```
            │ Flutterwave │ Paystack │
────────────┼─────────────┼──────────┤
NGN (₦)     │      ✅     │    ✅    │
USD ($)     │      ✅     │    ✅    │
GHS (GH₵)   │      ✅     │    ✅    │
KES (KSh)   │      ✅     │    ❌    │
UGX (USh)   │      ✅     │    ❌    │
ZAR (R)     │      ✅     │    ✅    │
RWF (FRw)   │      ✅     │    ❌    │
```

---

## Data Flow Example

```
User Initiates Quote Payment:
│
├─ POST /payment/quote/123
│  └─ PaymentController::createPayment('quote', 123)
│
├─ Get active processor: 'paystack'
│
├─ Validate payment data:
│  └─ PaymentProcessorService::validatePaymentData()
│
├─ Create payment record:
│  ├─ amount: 50000
│  ├─ currency: NGN
│  ├─ processor: paystack
│  ├─ reference: SKYEFACE-123-xyz123
│  └─ status: pending
│
├─ Initialize with Paystack:
│  └─ paystackService->initializePayment()
│
├─ Return payment link:
│  └─ https://checkout.paystack.com/...
│
├─ Client completes payment on Paystack
│
├─ Paystack POSTs to webhook: /payment/webhook
│
├─ Verify signature & process:
│  ├─ PaymentProcessorService::verifyWebhookSignature()
│  └─ Update payment status: completed
│
├─ Send confirmation email:
│  └─ Include "Powered by Paystack"
│
└─ Redirect to success page
   └─ Show processor badge
```

---

## Helper Classes Relationship

```
PaymentProcessorService (Static)
├─ Core processor operations
├─ Configuration access
├─ Currency handling
├─ Validation
├─ UI helpers (icons, colors, descriptions)
└─ Webhook management

        ↓ Uses

PaymentProcessorHelper (Static)
├─ Higher-level convenience functions
├─ Processor status checking
├─ Formatting for display
├─ Configuration validation
├─ Multi-processor handling
└─ Logging and switching

        ↓ Uses

PaymentController / RepairController
├─ Call helper methods
├─ Get active processor
├─ Process payments
└─ Handle callbacks

        ↓ Uses in Views

Blade Components
├─ payment-processor-badge
├─ payment-processor-info
├─ payment-processor-display
└─ Display processor info to users
```

---

## Error Handling & Fallback Flow

```
Payment Processing Initiated:
│
├─ Check if active processor configured
│  └─ If NO → Error: "Processor not configured"
│
├─ Try to initialize payment with active processor
│  │
│  ├─ Success → Continue to processor's page
│  │
│  └─ Failure → Check for fallback processor
│      │
│      ├─ Fallback exists → Try fallback processor
│      │   ├─ Success → Continue to fallback page
│      │   └─ Failure → Error: Both processors failed
│      │
│      └─ No fallback → Error: Processor failed, no fallback
│
└─ Log all actions & errors for debugging
```

---

**Architecture Diagram Generated:** January 24, 2026  
**Status:** Complete - Professional Implementation


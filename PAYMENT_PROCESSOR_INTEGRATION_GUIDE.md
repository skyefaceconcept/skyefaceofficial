# Payment Processor Integration Guide

## Overview

This document provides a complete guide to integrating payment processors into new booking flows or features. The system supports **Flutterwave** and **Paystack** with dynamic switching between processors.

---

## 1. Available Payment Processors

### Flutterwave
- **Public Key**: Stored in `FLUTTERWAVE_PUBLIC_KEY` (.env)
- **Secret Key**: Stored in `FLUTTERWAVE_SECRET_KEY` (.env)
- **Encrypt Key**: Stored in `FLUTTERWAVE_ENCRYPT_KEY` (.env)
- **Environments**: `sandbox` or `live`
- **Base URL**: 
  - Sandbox: `https://staging-api.flutterwave.com`
  - Live: `https://api.flutterwave.com`
- **Payment Methods**: Card, USSD, Bank Transfer, Mobile Money (Ghana, Rwanda, Uganda, Kenya, Comoros)

### Paystack
- **Public Key**: Stored in `PAYSTACK_PUBLIC_KEY` (.env)
- **Secret Key**: Stored in `PAYSTACK_SECRET_KEY` (.env)
- **Environments**: `live` or `test`
- **Base URL**: `https://api.paystack.co`
- **Supported Currencies**: NGN, USD, GHS, KES, ZAR (configurable)

---

## 2. Configuration Files

### [config/payment.php](config/payment.php)

Central payment configuration file with the following structure:

```php
[
    'active_processor' => env('PAYMENT_ACTIVE_PROCESSOR', 'flutterwave'),
    
    'flutterwave' => [
        'public_key' => env('FLUTTERWAVE_PUBLIC_KEY'),
        'secret_key' => env('FLUTTERWAVE_SECRET_KEY'),
        'encrypt_key' => env('FLUTTERWAVE_ENCRYPT_KEY'),
        'environment' => env('FLUTTERWAVE_ENVIRONMENT', 'live'),
        'enabled' => env('FLUTTERWAVE_ENABLED', false),
        'base_url' => 'https://api.flutterwave.com' | 'https://staging-api.flutterwave.com',
    ],
    
    'paystack' => [
        'public_key' => env('PAYSTACK_PUBLIC_KEY'),
        'secret_key' => env('PAYSTACK_SECRET_KEY'),
        'environment' => env('PAYSTACK_ENVIRONMENT', 'live'),
        'currency' => env('PAYSTACK_CURRENCY', 'NGN'),
        'enabled' => env('PAYSTACK_ENABLED', false),
        'base_url' => 'https://api.paystack.co',
    ],
    
    'currency' => env('PAYMENT_CURRENCY', 'NGN'),
    'timeout' => env('PAYMENT_TIMEOUT', 30), // minutes
    'webhook_secret' => env('PAYMENT_WEBHOOK_SECRET'),
    'test_mode' => env('PAYMENT_TEST_MODE', false),
    
    'statuses' => [
        'pending' => 'Pending',
        'completed' => 'Completed',
        'failed' => 'Failed',
        'cancelled' => 'Cancelled',
        'refunded' => 'Refunded',
    ],
]
```

---

## 3. How to Determine the Active Payment Processor

### Method 1: Using PaymentProcessorService

```php
use App\Services\PaymentProcessorService;

// Get the active processor name
$activeProcessor = PaymentProcessorService::getActiveProcessor(); // 'flutterwave' or 'paystack'

// Get full configuration for active processor
$config = PaymentProcessorService::getActiveConfig();

// Get public key
$publicKey = PaymentProcessorService::getPublicKey();

// Get secret key
$secretKey = PaymentProcessorService::getSecretKey();

// Check if processor is configured
if (PaymentProcessorService::isConfigured()) {
    // Processor has valid API keys
}

// Check if processor is enabled
if (PaymentProcessorService::isProcessorEnabled('flutterwave')) {
    // Flutterwave is enabled
}

// Get processor name (formatted)
$name = PaymentProcessorService::getProcessorName(); // 'Flutterwave' or 'Paystack'

// Get base URL for API calls
$baseUrl = PaymentProcessorService::getBaseUrl();

// Get all configured processors
$configured = PaymentProcessorService::getConfiguredProcessors(); // ['flutterwave' => 'Flutterwave', 'paystack' => 'Paystack']

// Get fallback processor (if primary fails)
$fallback = PaymentProcessorService::getFallbackProcessor();
```

### Method 2: Using config() Helper

```php
// Get active processor directly from config
$activeProcessor = config('payment.active_processor'); // 'flutterwave' or 'paystack'

// Get processor-specific configuration
$flutterwaveConfig = config('payment.flutterwave');
$paystackConfig = config('payment.paystack');

// Get global settings
$currency = config('payment.currency');
$timeout = config('payment.timeout');
$testMode = config('payment.test_mode');
```

---

## 4. Payment Integration Code

### Step 1: Initialize Payment in Controller

```php
<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use App\Models\Payment;
use App\Services\FlutterwaveService;
use App\Services\PaystackService;
use App\Services\PaymentProcessorService;
use Illuminate\Http\Request;

class BookingPaymentController extends Controller
{
    protected $flutterwaveService;
    protected $paystackService;

    public function __construct(FlutterwaveService $flutterwaveService, PaystackService $paystackService)
    {
        $this->flutterwaveService = $flutterwaveService;
        $this->paystackService = $paystackService;
    }

    /**
     * Get the appropriate payment service based on active processor
     */
    protected function getPaymentService()
    {
        $processor = PaymentProcessorService::getActiveProcessor();

        if ($processor === 'paystack') {
            return $this->paystackService;
        }

        return $this->flutterwaveService;
    }

    /**
     * Show payment form for a booking
     */
    public function showPaymentForm($bookingId)
    {
        $booking = Booking::findOrFail($bookingId);

        // Validate booking can be paid
        if (empty($booking->total_amount)) {
            return back()->with('error', 'Booking must have a total amount before payment.');
        }

        // Check if payment already completed
        $existingPayment = Payment::where('booking_id', $bookingId)
            ->whereIn('status', ['pending', 'completed'])
            ->first();

        if ($existingPayment && $existingPayment->status === 'completed') {
            return back()->with('info', 'This booking has already been paid.');
        }

        $processor = PaymentProcessorService::getActiveProcessor();
        $service = $this->getPaymentService();

        return view('booking.payment-form', [
            'booking' => $booking,
            'publicKey' => $service->getPublicKey(),
            'processor' => $processor,
            'currency' => PaymentProcessorService::getCurrency(),
            'currencySymbol' => PaymentProcessorService::getCurrencySymbol(),
        ]);
    }

    /**
     * Process payment
     */
    public function createPayment(Request $request, $bookingId)
    {
        $booking = Booking::findOrFail($bookingId);
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'email' => 'required|email',
            'name' => 'required|string',
        ]);

        try {
            // Create payment record
            $payment = Payment::create([
                'booking_id' => $bookingId,
                'amount' => $request->amount,
                'currency' => PaymentProcessorService::getCurrency(),
                'status' => 'pending',
                'customer_email' => $request->email,
                'customer_name' => $request->name,
                'payment_method' => PaymentProcessorService::getActiveProcessor(),
                'reference' => 'PAY-' . time() . '-' . uniqid(),
            ]);

            // Initialize payment with service
            $service = $this->getPaymentService();
            $result = $service->initializePayment(
                $request->amount,
                $request->email,
                $request->name,
                $payment->reference,
                'Booking Payment: ' . $booking->id,
                PaymentProcessorService::getCurrency()
            );

            if (!$result['success']) {
                $payment->update(['status' => 'failed']);
                return back()->with('error', $result['error']);
            }

            // Store response data
            $payment->update([
                'response_data' => $result['data'] ?? [],
            ]);

            // Redirect to processor payment page
            return redirect($result['payment_link']);

        } catch (\Exception $e) {
            \Log::error('Payment creation error: ' . $e->getMessage());
            return back()->with('error', 'Failed to process payment. Please try again.');
        }
    }

    /**
     * Handle payment callback from processor
     */
    public function handleCallback(Request $request)
    {
        $processor = PaymentProcessorService::getActiveProcessor();

        try {
            if ($processor === 'paystack') {
                return $this->handlePaystackCallback($request);
            } else {
                return $this->handleFlutterwaveCallback($request);
            }
        } catch (\Exception $e) {
            \Log::error("Payment callback error: " . $e->getMessage());
            return redirect('/')->with('error', 'Payment processing error.');
        }
    }

    /**
     * Handle Paystack callback
     */
    protected function handlePaystackCallback(Request $request)
    {
        $reference = $request->get('reference');

        if (!$reference) {
            return back()->with('error', 'Invalid payment reference.');
        }

        // Verify payment with service
        $result = $this->paystackService->verifyPayment($reference);

        if (!$result['success']) {
            return back()->with('error', 'Payment verification failed.');
        }

        // Update payment record
        $payment = Payment::where('reference', $reference)->firstOrFail();
        $payment->update([
            'status' => 'completed',
            'transaction_id' => $result['data']['id'] ?? null,
            'response_data' => $result['data'] ?? [],
            'paid_at' => now(),
        ]);

        // Update booking as paid
        Booking::findOrFail($payment->booking_id)->update(['paid' => true]);

        // Send confirmation email
        $this->sendPaymentEmails($payment);

        return redirect('/bookings/' . $payment->booking_id)->with('success', 'Payment completed successfully!');
    }

    /**
     * Handle Flutterwave callback
     */
    protected function handleFlutterwaveCallback(Request $request)
    {
        $transactionId = $request->get('transaction_id');

        if (!$transactionId) {
            return back()->with('error', 'Invalid transaction ID.');
        }

        // Verify payment with service
        $result = $this->flutterwaveService->verifyPayment($transactionId);

        if (!$result['success'] || $result['status'] !== 'successful') {
            return back()->with('error', 'Payment verification failed.');
        }

        // Find payment by transaction
        $payment = Payment::where('transaction_id', $transactionId)->first();

        if (!$payment) {
            // Get reference from response
            $reference = $result['data']['tx_ref'] ?? null;
            $payment = Payment::where('reference', $reference)->firstOrFail();
        }

        // Update payment record
        $payment->update([
            'status' => 'completed',
            'transaction_id' => $transactionId,
            'response_data' => $result['data'] ?? [],
            'paid_at' => now(),
        ]);

        // Update booking as paid
        Booking::findOrFail($payment->booking_id)->update(['paid' => true]);

        // Send confirmation email
        $this->sendPaymentEmails($payment);

        return redirect('/bookings/' . $payment->booking_id)->with('success', 'Payment completed successfully!');
    }

    /**
     * Send payment confirmation emails
     */
    protected function sendPaymentEmails($payment)
    {
        // Send confirmation to customer
        \Mail::to($payment->customer_email)->send(new \App\Mail\PaymentCompletedMail($payment));

        // Send notification to admin
        // \Mail::to(config('app.admin_email'))->send(new \App\Mail\PaymentReceivedAdminMail($payment));
    }
}
```

### Step 2: Create Payment Model (if not exists)

File: [app/Models/Payment.php](app/Models/Payment.php)

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id', // or 'quote_id' depending on your system
        'amount',
        'currency',
        'status',
        'transaction_id',
        'reference',
        'customer_email',
        'customer_name',
        'payment_method',
        'payment_source',
        'response_data',
        'paid_at',
    ];

    protected $casts = [
        'response_data' => 'json',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    // Status constants
    public const STATUS_PENDING = 'pending';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_FAILED = 'failed';
    public const STATUS_CANCELLED = 'cancelled';

    /**
     * Get the booking associated with this payment
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Check if payment is completed
     */
    public function isCompleted()
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    /**
     * Check if payment is pending
     */
    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Mark payment as completed
     */
    public function markAsCompleted()
    {
        $this->update([
            'status' => self::STATUS_COMPLETED,
            'paid_at' => now(),
        ]);
    }
}
```

### Step 3: Create Blade Template for Payment Form

File: `resources/views/booking/payment-form.blade.php`

```blade
@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        💳 Complete Payment
                        <small class="d-block text-light">{{ ucfirst($processor) }}</small>
                    </h5>
                </div>

                <div class="card-body">
                    <!-- Booking Details -->
                    <div class="alert alert-info">
                        <h6 class="mb-3">Booking Details</h6>
                        <table class="table table-sm mb-0">
                            <tr>
                                <td><strong>Booking ID:</strong></td>
                                <td>#{{ $booking->id }}</td>
                            </tr>
                            <tr>
                                <td><strong>Amount:</strong></td>
                                <td><strong>{{ $currencySymbol }}{{ number_format($booking->total_amount, 2) }}</strong></td>
                            </tr>
                            <tr>
                                <td><strong>Currency:</strong></td>
                                <td>{{ $currency }}</td>
                            </tr>
                        </table>
                    </div>

                    <!-- Security Notice -->
                    <div class="alert alert-warning">
                        <small>
                            ✅ This page is secured with SSL encryption. Your payment information is safe.
                            <br>
                            🔒 Powered by <strong>{{ ucfirst($processor) }}</strong>
                        </small>
                    </div>

                    <!-- Payment Form -->
                    <form id="paymentForm" method="POST" action="{{ route('booking.create-payment', $booking->id) }}">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="name">Full Name *</label>
                            <input 
                                type="text" 
                                id="name" 
                                name="name" 
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ auth()->user()->name ?? '' }}"
                                required
                            >
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="email">Email Address *</label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ auth()->user()->email ?? '' }}"
                                required
                            >
                            @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="amount">Amount to Pay *</label>
                            <div class="input-group">
                                <span class="input-group-text">{{ $currencySymbol }}</span>
                                <input 
                                    type="number" 
                                    id="amount" 
                                    name="amount" 
                                    class="form-control @error('amount') is-invalid @enderror"
                                    value="{{ $booking->total_amount }}"
                                    step="0.01"
                                    min="0"
                                    required
                                >
                            </div>
                            @error('amount')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <button 
                            type="submit" 
                            class="btn btn-primary btn-lg w-100 mb-3"
                            id="payBtn"
                        >
                            <i class="fas fa-lock"></i> Proceed to Payment
                        </button>
                    </form>

                    <!-- Help Text -->
                    @if($processor === 'flutterwave')
                        <small class="text-muted d-block mt-3">
                            <strong>Test Card (Sandbox):</strong> 4242 4242 4242 4242 | 
                            Exp: 12/30 | CVV: 123
                        </small>
                    @else
                        <small class="text-muted d-block mt-3">
                            <strong>Test Card (Sandbox):</strong> 4111 1111 1111 1111 | 
                            Exp: 12/30 | CVV: 123
                        </small>
                    @endif

                    <a href="{{ route('booking.show', $booking->id) }}" class="btn btn-link mt-3">
                        ← Back to Booking
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
```

---

## 5. Payment Models and Services

### PaymentProcessorService
**File**: [app/Services/PaymentProcessorService.php](app/Services/PaymentProcessorService.php)

**Key Methods**:
- `getActiveProcessor()` - Get 'flutterwave' or 'paystack'
- `getActiveConfig()` - Get full configuration for active processor
- `getPublicKey()` - Get public key for frontend
- `getSecretKey()` - Get secret key for backend
- `isConfigured()` - Check if processor has valid API keys
- `isProcessorEnabled($processor)` - Check if processor is enabled
- `getProcessorName()` - Get formatted name
- `getBaseUrl()` - Get API base URL
- `getCurrency()` - Get default currency
- `getCurrencySymbol()` - Get currency symbol
- `getTimeout()` - Get payment timeout in minutes
- `getConfiguredProcessors()` - Get all configured processors
- `getFallbackProcessor()` - Get fallback if primary fails
- `verifyWebhookSignature()` - Verify webhook signature
- `formatAmount()` - Format amount for processor
- `getPaymentMetadata()` - Get payment metadata

### FlutterwaveService
**File**: [app/Services/FlutterwaveService.php](app/Services/FlutterwaveService.php)

**Key Methods**:
```php
// Initialize payment
$result = $flutterwaveService->initializePayment(
    $amount,           // numeric amount
    $email,            // customer email
    $name,             // customer name
    $reference,        // unique reference (e.g., 'QUOTE-123')
    $description,      // optional description
    $currency          // optional currency (defaults to config)
);
// Returns: ['success' => bool, 'data' => array, 'payment_link' => string]

// Verify payment
$result = $flutterwaveService->verifyPayment($transactionId);
// Returns: ['success' => bool, 'status' => string, 'data' => array]

// Get public key
$publicKey = $flutterwaveService->getPublicKey();

// Validate webhook
$isValid = $flutterwaveService->validateWebhook($payload, $signature);
```

### PaystackService
**File**: [app/Services/PaystackService.php](app/Services/PaystackService.php)

**Key Methods**:
```php
// Initialize payment
$result = $paystackService->initializePayment(
    $amount,           // numeric amount
    $email,            // customer email
    $name,             // customer name
    $reference,        // unique reference
    $description,      // optional description
    $currency          // optional currency (defaults to config)
);
// Returns: ['success' => bool, 'data' => array, 'payment_link' => string]

// Verify payment
$result = $paystackService->verifyPayment($reference);
// Returns: ['success' => bool, 'status' => string, 'data' => array]

// Get public key
$publicKey = $paystackService->getPublicKey();
```

### Payment Model
**File**: [app/Models/Payment.php](app/Models/Payment.php)

**Attributes**:
- `booking_id` - Reference to booking
- `amount` - Payment amount
- `currency` - Currency code (NGN, USD, etc.)
- `status` - pending, completed, failed, cancelled, refunded
- `transaction_id` - Processor transaction ID
- `reference` - Unique reference code
- `customer_email` - Payer email
- `customer_name` - Payer name
- `payment_method` - flutterwave or paystack
- `response_data` - Full processor response (JSON)
- `paid_at` - Timestamp when payment completed

**Methods**:
- `isCompleted()` - Check if payment succeeded
- `isPending()` - Check if payment pending
- `markAsCompleted()` - Mark payment as completed

---

## 6. Routes and Endpoints

**File**: [routes/web.php](routes/web.php)

```php
// Payment Routes
Route::get('/payment/{quoteId}', [PaymentController::class, 'showPaymentForm'])->name('payment.form');
Route::post('/payment/{quoteId}', [PaymentController::class, 'createPayment'])->name('payment.create');
Route::get('/payment/callback', [PaymentController::class, 'handleCallback'])->name('payment.callback');

// Admin Settings
Route::get('/settings/payment-processors', [SettingController::class, 'paymentProcessors'])->name('settings.payment_processors');
Route::put('/settings/payment-processors/set-active', [SettingController::class, 'setActivePaymentProcessor'])->name('settings.setActivePaymentProcessor');
Route::put('/settings/payment-processors/{processor}', [SettingController::class, 'savePaymentProcessor'])->name('settings.savePaymentProcessor');
Route::put('/settings/payment-processors/global/settings', [SettingController::class, 'savePaymentGlobalSettings'])->name('settings.savePaymentGlobalSettings');
```

---

## 7. Admin Settings Controller

**File**: [app/Http/Controllers/Admin/SettingController.php](app/Http/Controllers/Admin/SettingController.php)

**Key Methods**:

```php
// Display payment settings page
public function paymentProcessors()
{
    $activeProcessor = config('payment.active_processor', 'flutterwave');
    $paymentSettings = [...];  // Flutterwave & Paystack config
    $globalSettings = [...];   // Global payment settings
    
    return view('admin.settings.payment_processors', [
        'activeProcessor' => $activeProcessor,
        'paymentSettings' => $paymentSettings,
        'globalSettings' => $globalSettings,
    ]);
}

// Switch active processor
public function setActivePaymentProcessor(Request $request)
{
    $request->validate(['active_processor' => 'required|in:flutterwave,paystack']);
    $this->updateEnv('PAYMENT_ACTIVE_PROCESSOR', $request->active_processor);
    return redirect()->route('admin.settings.payment_processors')->with('success', 'Processor switched!');
}

// Save processor configuration
public function savePaymentProcessor(Request $request, $processor)
{
    // Validates and saves API keys for specific processor
    // Updates .env file with FLUTTERWAVE_* or PAYSTACK_* keys
    // Clears config cache
}

// Save global payment settings
public function savePaymentGlobalSettings(Request $request)
{
    // Saves PAYMENT_CURRENCY, PAYMENT_TIMEOUT, PAYMENT_WEBHOOK_SECRET, PAYMENT_TEST_MODE
}
```

---

## 8. Integration Checklist for New Booking Flow

- [ ] Create `BookingPayment` model with proper relationships
- [ ] Create payment form blade template
- [ ] Create controller with `getPaymentService()` method
- [ ] Inject `FlutterwaveService` and `PaystackService` in controller
- [ ] Implement `showPaymentForm()` method
- [ ] Implement `createPayment()` method
- [ ] Implement `handleCallback()` method with processor routing
- [ ] Implement `handleFlutterwaveCallback()` method
- [ ] Implement `handlePaystackCallback()` method
- [ ] Create database migration for payments table
- [ ] Add payment routes to `routes/web.php`
- [ ] Test with both Flutterwave and Paystack in admin settings
- [ ] Configure webhook endpoints (if needed)
- [ ] Test with test cards:
  - Flutterwave: 4242 4242 4242 4242
  - Paystack: 4111 1111 1111 1111

---

## 9. Environment Variables

Add these to your `.env` file:

```bash
# Active Processor (flutterwave or paystack)
PAYMENT_ACTIVE_PROCESSOR=flutterwave

# Flutterwave Configuration
FLUTTERWAVE_PUBLIC_KEY=your_flutterwave_public_key
FLUTTERWAVE_SECRET_KEY=your_flutterwave_secret_key
FLUTTERWAVE_ENCRYPT_KEY=your_flutterwave_encrypt_key
FLUTTERWAVE_ENVIRONMENT=live  # or 'sandbox'
FLUTTERWAVE_ENABLED=true

# Paystack Configuration
PAYSTACK_PUBLIC_KEY=your_paystack_public_key
PAYSTACK_SECRET_KEY=your_paystack_secret_key
PAYSTACK_ENVIRONMENT=live  # or 'test'
PAYSTACK_CURRENCY=NGN
PAYSTACK_ENABLED=true

# Global Payment Settings
PAYMENT_CURRENCY=NGN
PAYMENT_TIMEOUT=30
PAYMENT_WEBHOOK_SECRET=your_webhook_secret
PAYMENT_TEST_MODE=false
```

---

## 10. Quick Reference

### Get Active Processor
```php
$processor = PaymentProcessorService::getActiveProcessor(); // 'flutterwave' or 'paystack'
```

### Initialize Payment
```php
$service = $processor === 'paystack' ? $paystackService : $flutterwaveService;
$result = $service->initializePayment($amount, $email, $name, $reference);
// Redirect to: $result['payment_link']
```

### Verify Payment
```php
$result = $service->verifyPayment($transactionId); // For Flutterwave
$result = $service->verifyPayment($reference);     // For Paystack
if ($result['success'] && $result['status'] === 'completed') {
    // Payment successful
}
```

### Get Currency Symbol
```php
$symbol = PaymentProcessorService::getCurrencySymbol(); // '₦', '$', '£', etc.
```

### Check Configuration
```php
if (PaymentProcessorService::isConfigured()) {
    // Payment processor is ready
}
```

---

## 11. Error Handling

All service methods return a consistent format:

```php
[
    'success' => bool,
    'data' => array,           // Response data from processor
    'error' => string,         // Error message if failed
    'payment_link' => string,  // Link to processor payment page
    'status' => string,        // Payment status ('successful', 'pending', etc.)
]
```

Always check `$result['success']` before proceeding.

---

## 12. Admin Settings Page

Access at: `/admin/settings/payment-processors`

Features:
- Switch active processor with dropdown
- Configure Flutterwave API keys (Public, Secret, Encrypt)
- Configure Paystack API keys (Public, Secret)
- Set environment (sandbox/test vs live)
- Global settings (currency, timeout, webhook secret)
- Processor status indicators
- Test card information
- Enable/disable individual processors

---

## Summary

The payment processor system is fully implemented and ready to integrate into new booking flows. Simply:

1. Use `PaymentProcessorService::getActiveProcessor()` to determine which processor to use
2. Inject the appropriate service (`FlutterwaveService` or `PaystackService`)
3. Call `initializePayment()` to start the payment process
4. Handle the callback to verify and process the payment
5. Update your booking/payment record with the status

All configuration is managed from the admin settings panel at `/admin/settings/payment-processors`.

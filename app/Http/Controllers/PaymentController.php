<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use App\Models\Order;
use App\Models\Payment;
use App\Services\FlutterwaveService;
use App\Services\PaystackService;
use App\Services\PaymentProcessorService;
use App\Mail\PaymentCompletedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PaymentController extends Controller
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
     * Get the active payment processor
     */
    protected function getActiveProcessor()
    {
        return PaymentProcessorService::getActiveProcessor();
    }

    /**
     * Show payment page for an order (shop checkout)
     */
    public function showOrder($orderId)
    {
        try {
            \Log::info('PaymentController.showOrder() called', ['order_id' => $orderId]);

            $order = Order::findOrFail($orderId);
            \Log::info('Order found', ['order_id' => $order->id, 'amount' => $order->amount, 'status' => $order->status]);

            // Check if order has an amount
            if (empty($order->amount)) {
                \Log::error('Order has no amount', ['order_id' => $order->id]);
                return back()->with('error', 'Order must have an amount before payment.');
            }

            // Get the active payment processor
            $processor = $this->getActiveProcessor();
            \Log::info('Processor retrieved', ['processor' => $processor]);

            $service = $this->getPaymentService();
            \Log::info('Payment service instantiated');

            \Log::info('Loading payment.order-form view', ['order_id' => $order->id, 'processor' => $processor]);

            return view('payment.order-form', [
                'order' => $order,
                'publicKey' => $service->getPublicKey(),
                'processor' => $processor,
            ]);
        } catch (\Exception $e) {
            \Log::error('PaymentController.showOrder() error', [
                'order_id' => $orderId,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->view('errors.404', [], 404);
        }
    }

    /**
     * Show payment page for a quote
     */
    public function showPaymentForm($quoteId)
    {
        $quote = Quote::findOrFail($quoteId);

        // Check if quote has a price
        if (empty($quote->quoted_price)) {
            return back()->with('error', 'Quote must have a price before payment.');
        }

        // Check if payment already exists
        $existingPayment = Payment::where('quote_id', $quoteId)
            ->whereIn('status', ['pending', 'completed'])
            ->first();

        if ($existingPayment && $existingPayment->isCompleted()) {
            return back()->with('info', 'This quote has already been paid.');
        }

        $processor = $this->getActiveProcessor();
        $service = $this->getPaymentService();

        return view('payment.form', [
            'quote' => $quote,
            'publicKey' => $service->getPublicKey(),
            'processor' => $processor,
        ]);
    }

    /**
     * Create payment record and get payment link
     */
    public function createPayment(Request $request, $quoteId)
    {
        $quote = Quote::findOrFail($quoteId);

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'nullable|string|in:USD,NGN,GHS,KES,UGX,ZAR,RWF',
            'payment_method' => 'nullable|string',
        ]);

        // Get the active processor
        $processor = $this->getActiveProcessor();
        $service = $this->getPaymentService();

        // Get currency from request or use default
        $currencyConfig = $processor === 'paystack'
            ? config('payment.paystack.currency', 'NGN')
            : config('payment.flutterwave.currency', 'NGN');
        $currency = $validated['currency'] ?? $currencyConfig;

        // Create or update payment record
        $reference = 'SKYEFACE-' . $quoteId . '-' . Str::random(8);

        $payment = Payment::where('quote_id', $quoteId)
            ->where('status', 'pending')
            ->first();

        $processorName = ucfirst($processor);

        if (!$payment) {
            $payment = Payment::create([
                'quote_id' => $quoteId,
                'amount' => $validated['amount'],
                'currency' => $currency,
                'status' => Payment::STATUS_PENDING,
                'reference' => $reference,
                'customer_email' => $quote->email,
                'customer_name' => $quote->name,
                'payment_source' => $processorName,
            ]);
        } else {
            $payment->update([
                'amount' => $validated['amount'],
                'currency' => $currency,
                'reference' => $reference,
                'payment_source' => $processorName,
            ]);
        }

        // Initialize payment with the active processor service
        $result = $service->initializePayment(
            $validated['amount'],
            $quote->email,
            $quote->name,
            $reference,
            'Quote #' . $quoteId . ' Payment',
            $currency

        );

        if (!$result['success']) {
            $payment->update(['status' => Payment::STATUS_FAILED]);
            return response()->json(['error' => $result['error']], 400);
        }

        // Extract gateway transaction ID based on processor
        $processor = $this->getActiveProcessor();
        if ($processor === 'paystack') {
            // For Paystack, use reference as transaction ID
            $gatewayTxId = $result['data']['data']['reference'] ?? null;
        } else {
            // For Flutterwave, try different possible ID fields
            $gatewayTxId = $result['data']['data']['id'] ?? $result['data']['data']['tx_ref'] ?? $result['data']['data']['flw_ref'] ?? null;
        }

        $transactionId = $gatewayTxId ?: (string) Str::orderedUuid();

        $payment->update([
            'transaction_id' => $transactionId,
            'response_data' => $result['data']['data'],
        ]);

        return response()->json([
            'success' => true,
            'payment_link' => $result['payment_link'],
            'reference' => $reference,
        ]);
    }

    /**
     * Handle payment callback from payment processor
     */
    public function callback(Request $request)
    {
        try {
            $processor = $this->getActiveProcessor();
            $service = $this->getPaymentService();

            Log::info('Payment callback received', [
                'processor' => $processor,
                'query_params' => $request->query(),
                'request_method' => $request->method(),
            ]);

            if ($processor === 'paystack') {
                return $this->handlePaystackCallback($request, $service);
            } else {
                return $this->handleFlutterwaveCallback($request, $service);
            }
        } catch (\Exception $e) {
            Log::error('Payment callback error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->route('payment.failed')->with('error', 'An error occurred processing the payment callback.');
        }
    }

    /**
     * Handle Paystack payment callback
     */
    protected function handlePaystackCallback(Request $request, $service)
    {
        $reference = $request->input('reference');
        $status = $request->input('status');

        Log::info('Paystack callback', [
            'reference' => $reference,
            'status' => $status,
        ]);

        if ($reference && $status === 'success') {
            // Verify payment with Paystack
            $verification = $service->verifyPayment($reference);

            if ($verification['success'] && ($verification['status'] ?? null) === 'success') {
                // Find payment by reference
                $payment = Payment::where('reference', $reference)->first();

                // Check if this is a repair booking payment
                $repair = null;
                if (!$payment) {
                    $repair = \App\Models\Repair::where('payment_reference', $reference)->first();
                    Log::info('Looking for repair by payment_reference (Paystack)', [
                        'reference' => $reference,
                        'repair_found' => $repair ? true : false,
                        'repair_id' => $repair->id ?? null,
                    ]);
                }

                if ($payment) {
                    // Update payment
                    Log::info('Updating payment from Paystack callback', [
                        'payment_id' => $payment->id,
                        'order_id' => $payment->order_id,
                        'quote_id' => $payment->quote_id,
                        'reference' => $reference,
                    ]);

                    $payment->update([
                        'transaction_id' => $verification['data']['reference'] ?? $reference,
                        'status' => Payment::STATUS_COMPLETED,
                        'paid_at' => now(),
                        'response_data' => $verification['data'],
                    ]);

                    // Update related record based on payment type
                    if ($payment->quote) {
                        $payment->quote->update(['status' => Quote::STATUS_ACCEPTED]);
                    } elseif ($payment->order) {
                        // Use the Order model's markAsCompleted() method which handles license generation and emails
                        $payment->order->markAsCompleted($reference);
                    }

                    Log::info('Paystack payment verified and completed', [
                        'payment_id' => $payment->id,
                        'quote_id' => $payment->quote_id,
                        'order_id' => $payment->order_id,
                    ]);

                    // Send payment confirmation email
                    $this->sendPaymentEmails($payment);

                    return redirect()->route('payment.success', $payment->id)
                        ->with('success', 'Payment completed successfully!');
                } elseif ($repair) {
                    // Handle repair booking payment
                    try {
                        $repair->update([
                            'payment_status' => 'completed',
                            'payment_verified_at' => now(),
                            'payment_reference' => $reference,
                            'payment_processor' => 'paystack',
                        ]);

                        // Create Payment record for tracking
                        $payment = Payment::updateOrCreate(
                            ['reference' => $reference],
                            [
                                'repair_id' => $repair->id,
                                'transaction_id' => $verification['data']['reference'] ?? $reference,
                                'amount' => $verification['data']['amount'] ?? 0,
                                'currency' => $verification['data']['currency'] ?? 'NGN',
                                'status' => Payment::STATUS_COMPLETED,
                                'customer_email' => $repair->customer_email,
                                'customer_name' => $repair->customer_name,
                                'payment_source' => 'paystack',
                                'paid_at' => now(),
                                'response_data' => $verification['data'],
                            ]
                        );

                        Log::info('Paystack repair payment verified and completed', [
                            'payment_id' => $payment->id,
                            'repair_id' => $repair->id,
                        ]);

                        // Send confirmation email
                        try {
                            Mail::to($repair->customer_email)->send(new \App\Mail\RepairPaymentConfirmation($repair));
                        } catch (\Exception $e) {
                            Log::error('Failed to send repair payment confirmation: ' . $e->getMessage());
                        }

                        return redirect()->route('repairs.track.page')
                            ->with('success', 'Payment confirmed! Your repair booking is now active.');
                    } catch (\Exception $e) {
                        Log::error('Failed to process repair payment (Paystack)', [
                            'repair_id' => $repair->id,
                            'reference' => $reference,
                            'error' => $e->getMessage(),
                            'trace' => $e->getTraceAsString(),
                        ]);

                        return redirect()->route('payment.failed')
                            ->with('error', 'Failed to confirm payment. Please contact support.');
                    }
                }
            }
        }

        Log::warning('Paystack payment verification failed', [
            'reference' => $reference,
        ]);

        return redirect()->route('payment.failed')->with('error', 'Payment verification failed.');
    }

    /**
     * Handle Flutterwave payment callback
     */
    protected function handleFlutterwaveCallback(Request $request, $service)
    {
        $status = $request->input('status');
        $transactionId = $request->input('transaction_id');
        $txRef = $request->input('tx_ref');

        Log::info('Flutterwave callback', [
            'status' => $status,
            'transaction_id' => $transactionId,
            'tx_ref' => $txRef,
        ]);

        // Handle both 'successful' and 'completed' status values
        if (in_array($status, ['successful', 'completed']) && $transactionId) {
            // Verify payment
            $verification = $service->verifyPayment($transactionId);

            if ($verification['success'] && in_array($verification['status'] ?? null, ['successful', 'completed'])) {
                // Try to find payment by tx_ref first (our internal reference)
                $payment = null;
                if ($txRef) {
                    $payment = Payment::where('reference', $txRef)
                        ->orWhere('transaction_reference', $txRef)
                        ->first();
                }

                // If not found by reference, try by transaction ID
                if (!$payment) {
                    $payment = Payment::where('transaction_id', $transactionId)->first();
                }

                // Check if this is a repair booking payment
                $repair = null;
                if (!$payment && $txRef) {
                    $repair = \App\Models\Repair::where('payment_reference', $txRef)->first();
                    Log::info('Looking for repair by payment_reference', [
                        'tx_ref' => $txRef,
                        'repair_found' => $repair ? true : false,
                        'repair_id' => $repair->id ?? null,
                    ]);
                }

                if ($payment) {
                    // Ensure transaction_id is set (use gateway ID or generate UUID)
                    $txIdToSave = $transactionId ?: (string) Str::orderedUuid();

                    Log::info('Updating payment from Flutterwave callback', [
                        'payment_id' => $payment->id,
                        'order_id' => $payment->order_id,
                        'quote_id' => $payment->quote_id,
                        'old_transaction_id' => $payment->transaction_id,
                        'new_transaction_id' => $txIdToSave,
                    ]);

                    $payment->update([
                        'transaction_id' => $txIdToSave,
                        'status' => Payment::STATUS_COMPLETED,
                        'paid_at' => now(),
                        'response_data' => $verification['data'],
                    ]);

                    // Update related record based on payment type
                    if ($payment->quote) {
                        $payment->quote->update(['status' => Quote::STATUS_ACCEPTED]);
                    } elseif ($payment->order) {
                        // Use the Order model's markAsCompleted() method which handles license generation and emails
                        $payment->order->markAsCompleted($txRef);
                    }

                    Log::info('Flutterwave payment verified and completed', [
                        'payment_id' => $payment->id,
                        'quote_id' => $payment->quote_id,
                        'order_id' => $payment->order_id,
                    ]);

                    // Send payment confirmation email
                    $this->sendPaymentEmails($payment);

                    return redirect()->route('payment.success', $payment->id)
                        ->with('success', 'Payment completed successfully!');
                } elseif ($repair) {
                    // Handle repair booking payment
                    try {
                        $repair->update([
                            'payment_status' => 'completed',
                            'payment_verified_at' => now(),
                            'payment_reference' => $txRef,
                            'payment_processor' => 'flutterwave',
                        ]);

                        // Create Payment record for tracking
                        // Use transaction_id if available, otherwise use reference
                        $identifierKey = $transactionId ? 'transaction_id' : 'reference';
                        $identifierValue = $transactionId ?: $txRef;

                        $payment = Payment::updateOrCreate(
                            [$identifierKey => $identifierValue],
                            [
                                'repair_id' => $repair->id,
                                'transaction_id' => $transactionId,
                                'amount' => $verification['data']['amount'] ?? 0,
                                'currency' => $verification['data']['currency'] ?? 'NGN',
                                'status' => Payment::STATUS_COMPLETED,
                                'reference' => $txRef,
                                'customer_email' => $repair->customer_email,
                                'customer_name' => $repair->customer_name,
                                'payment_source' => 'flutterwave',
                                'paid_at' => now(),
                                'response_data' => $verification['data'],
                            ]
                        );

                        Log::info('Flutterwave repair payment verified and completed', [
                            'payment_id' => $payment->id,
                            'repair_id' => $repair->id,
                        ]);

                        // Send confirmation email
                        try {
                            Mail::to($repair->customer_email)->send(new \App\Mail\RepairPaymentConfirmation($repair));
                        } catch (\Exception $e) {
                            Log::error('Failed to send repair payment confirmation: ' . $e->getMessage());
                        }

                        return redirect()->route('repairs.track.page')
                            ->with('success', 'Payment confirmed! Your repair booking is now active.');
                    } catch (\Exception $e) {
                        Log::error('Failed to process repair payment (Flutterwave)', [
                            'repair_id' => $repair->id,
                            'tx_ref' => $txRef,
                            'error' => $e->getMessage(),
                            'trace' => $e->getTraceAsString(),
                        ]);

                        return redirect()->route('payment.failed')
                            ->with('error', 'Failed to confirm payment. Please contact support.');
                    }
                } else {
                    Log::warning('Payment record not found', [
                        'transaction_id' => $transactionId,
                        'tx_ref' => $txRef,
                    ]);
                    return redirect()->route('payment.failed')
                        ->with('error', 'Payment record not found.');
                }
            } else {
                Log::warning('Flutterwave payment verification failed', [
                    'verification' => $verification,
                    'transaction_id' => $transactionId,
                ]);
            }
        }

        Log::warning('Payment callback failed or invalid status', [
            'status' => $status,
            'transaction_id' => $transactionId,
        ]);

        return redirect()->route('payment.failed')
            ->with('error', 'Payment verification failed. Please contact support if you believe this is an error.');
    }

    /**
     * Send payment completion emails to customer and admin
     */
    protected function sendPaymentEmails(Payment $payment)
    {
        // Send payment confirmation email to client
        try {
            Mail::to($payment->customer_email)->send(new PaymentCompletedMail($payment));
            Log::info('Payment confirmation email sent to client', [
                'email' => $payment->customer_email,
                'payment_id' => $payment->id,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send payment confirmation email', [
                'email' => $payment->customer_email,
                'error' => $e->getMessage(),
            ]);
        }

        // Send admin notification
        try {
            $adminEmail = config('company.admin_email', config('mail.from.address'));
            Mail::to($adminEmail)->send(new PaymentCompletedMail($payment));
            Log::info('Payment notification email sent to admin', [
                'email' => $adminEmail,
                'payment_id' => $payment->id,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send admin notification email', [
                'error' => $e->getMessage(),
            ]);
        }

    }

    /**
     * Show payment success page
     */
    public function success($paymentId)
    {
        $payment = Payment::findOrFail($paymentId);
        return view('payment.success', ['payment' => $payment]);
    }

    /**
     * Show payment failed page
     */
    public function failed(Request $request)
    {
        // Try to get the most recent failed payment from the session or query
        $payment = null;

        // If there's a quote ID in the query string, get that payment
        if ($request->has('quote_id')) {
            $payment = Payment::where('quote_id', $request->input('quote_id'))
                ->orderBy('created_at', 'desc')
                ->first();
        }

        // If still no payment, try to get from session
        if (!$payment && session('last_payment_id')) {
            $payment = Payment::find(session('last_payment_id'));
        }

        // If still no payment, create a dummy one for display
        if (!$payment) {
            $payment = new Payment([
                'reference' => 'N/A',
                'transaction_id' => 'N/A',
                'currency' => 'NGN',
                'amount' => 0,
                'customer_email' => 'customer@example.com',
                'quote_id' => 0
            ]);
        }

        return view('payment.failed', ['payment' => $payment]);
    }

    /**
     * Check payment status - allows users to query if their payment was successful
     * even if they received an error due to network issues
     * Supports both Flutterwave and Paystack
     */
    public function checkStatus(Request $request, $quoteId)
    {
        $quote = Quote::findOrFail($quoteId);

        // Find the most recent payment for this quote
        $payment = Payment::where('quote_id', $quoteId)
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$payment) {
            return response()->json([
                'success' => false,
                'found' => false,
                'message' => 'No payment found for this quote. Please initiate a new payment.',
            ], 404);
        }

        // If payment is pending, verify with the payment processor
        if ($payment->status === Payment::STATUS_PENDING && ($payment->transaction_id || $payment->reference)) {
            try {
                // Determine which processor to use
                $paymentSource = $payment->payment_source ? strtolower($payment->payment_source) : null;

                // Auto-detect from response_data if not explicitly set
                if (!$paymentSource || $paymentSource === 'null') {
                    if ($payment->response_data) {
                        if (isset($payment->response_data['data']['authorization'])) {
                            $paymentSource = 'paystack';
                        } elseif (isset($payment->response_data['data']['link'])) {
                            $paymentSource = 'flutterwave';
                        }
                    }
                    // Fallback: check reference pattern
                    if (!$paymentSource && $payment->reference && str_contains($payment->reference, 'SKYEFACE')) {
                        $paymentSource = 'paystack';
                    }
                }

                // Select the appropriate service
                if ($paymentSource === 'paystack' || str_contains($paymentSource ?? '', 'paystack')) {
                    $service = $this->paystackService;
                    $identifierToVerify = $payment->reference ?? $payment->transaction_id;
                    Log::info('Checking payment status with Paystack', [
                        'payment_id' => $payment->id,
                        'reference' => $identifierToVerify,
                    ]);
                } else {
                    $service = $this->flutterwaveService;
                    $identifierToVerify = $payment->transaction_id ?? $payment->reference;
                    Log::info('Checking payment status with Flutterwave', [
                        'payment_id' => $payment->id,
                        'transaction_id' => $identifierToVerify,
                    ]);
                }

                // Verify payment with the appropriate processor
                $verification = $service->verifyPayment($identifierToVerify);

                if ($verification['success'] && in_array($verification['status'] ?? null, ['successful', 'completed', 'success'])) {
                    // Payment was successful! Update the record
                    $payment->update([
                        'status' => Payment::STATUS_COMPLETED,
                        'paid_at' => now(),
                        'response_data' => $verification['data'] ?? $payment->response_data,
                    ]);

                    // Update quote status
                    $payment->quote->update(['status' => Quote::STATUS_ACCEPTED]);

                    Log::info('Payment confirmed via status check', [
                        'payment_id' => $payment->id,
                        'quote_id' => $quoteId,
                        'processor' => $paymentSource,
                    ]);

                    // Send payment confirmation email
                    try {
                        Mail::to($payment->customer_email)->send(new PaymentCompletedMail($payment));
                        Log::info('Payment confirmation email sent after status check', [
                            'email' => $payment->customer_email,
                        ]);
                    } catch (\Exception $e) {
                        Log::error('Failed to send payment confirmation email', [
                            'email' => $payment->customer_email,
                            'error' => $e->getMessage(),
                        ]);
                    }

                    return response()->json([
                        'success' => true,
                        'status' => Payment::STATUS_COMPLETED,
                        'message' => 'Great news! Your payment was successful. Confirmation email sent.',
                        'payment' => [
                            'id' => $payment->id,
                            'status' => $payment->status,
                            'amount' => $payment->amount,
                            'currency' => $payment->currency,
                            'paid_at' => $payment->paid_at,
                            'reference' => $payment->reference,
                        ],
                    ]);
                } else {
                    // Payment still pending or failed
                    $currentStatus = $verification['status'] ?? $payment->status;
                    return response()->json([
                        'success' => true,
                        'found' => true,
                        'status' => $currentStatus,
                        'message' => $this->getStatusMessage($currentStatus),
                        'payment' => [
                            'id' => $payment->id,
                            'status' => $currentStatus,
                            'amount' => $payment->amount,
                            'currency' => $payment->currency,
                            'paid_at' => $payment->paid_at,
                            'reference' => $payment->reference,
                            'created_at' => $payment->created_at,
                        ],
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Error verifying payment status', [
                    'payment_id' => $payment->id,
                    'error' => $e->getMessage(),
                ]);

                // Return current status if verification fails
                return response()->json([
                    'success' => true,
                    'found' => true,
                    'status' => $payment->status,
                    'message' => $this->getStatusMessage($payment->status) . ' (Unable to verify with processor)',
                    'payment' => [
                        'id' => $payment->id,
                        'status' => $payment->status,
                        'amount' => $payment->amount,
                        'currency' => $payment->currency,
                        'paid_at' => $payment->paid_at,
                        'reference' => $payment->reference,
                        'created_at' => $payment->created_at,
                    ],
                ]);
            }
        }

        // Return current payment status if not pending or no transaction ID
        return response()->json([
            'success' => true,
            'found' => true,
            'status' => $payment->status,
            'message' => $this->getStatusMessage($payment->status),
            'payment' => [
                'id' => $payment->id,
                'status' => $payment->status,
                'amount' => $payment->amount,
                'currency' => $payment->currency,
                'paid_at' => $payment->paid_at,
                'reference' => $payment->reference,
                'created_at' => $payment->created_at,
            ],
        ]);
    }

    /**
     * Get user-friendly status message
     */
    private function getStatusMessage($status)
    {
        return match($status) {
            Payment::STATUS_COMPLETED => 'Payment completed successfully!',
            Payment::STATUS_PENDING => 'Payment is still pending. Please complete the payment or check again shortly.',
            Payment::STATUS_FAILED => 'Payment failed. Please try again or contact support.',
            Payment::STATUS_CANCELLED => 'Payment was cancelled.',
            default => 'Unknown payment status.',
        };
    }

    /**
     * Handle webhook from Flutterwave
     */
    public function webhook(Request $request)
    {
        $signature = $request->header('Verifi-Hash');

        if (!$this->flutterwaveService->validateWebhook($request->all(), $signature)) {
            return response()->json(['error' => 'Invalid signature'], 403);
        }

        $data = $request->all();
        $transactionId = $data['data']['id'] ?? null;

        if ($transactionId && $data['data']['status'] === 'successful') {
            $payment = Payment::where('transaction_id', $transactionId)->first();

            if ($payment && !$payment->isCompleted()) {
                $payment->update([
                    'status' => Payment::STATUS_COMPLETED,
                    'paid_at' => now(),
                ]);

                $payment->quote->update(['status' => Quote::STATUS_ACCEPTED]);

                Log::info('Payment completed via webhook', ['payment_id' => $payment->id]);
            }
        }

        return response()->json(['success' => true]);
    }

    /**
     * Show payment history for logged-in client
     */
    public function clientHistory(Request $request)
    {
        $user = auth()->user();

        // Get all quotes for this user based on email
        $quotes = Quote::where('email', $user->email)
            ->with('payments')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Get all payments for this user
        $payments = Payment::whereHas('quote', function ($q) use ($user) {
            $q->where('email', $user->email);
        })
        ->with('quote')
        ->orderBy('created_at', 'desc')
        ->paginate(15);

        // Get all shop orders for this customer (by email)
        $orders = Order::where('customer_email', $user->email)
            ->with('payment')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('payment.client-history', [
            'payments' => $payments,
            'quotes' => $quotes,
            'orders' => $orders,
        ]);
    }

    /**
     * Show all payments admin page
     */
    public function adminList(Request $request)
    {
        // Admin only - check authorization
        if (!auth()->user() || !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access');
        }

        // Get ALL payments without any quote_id or repair_id requirements
        $query = Payment::with(['quote', 'repair', 'order'])
            ->orderBy('created_at', 'desc');

        // Filter by status if provided
        if ($request->has('status') && $request->input('status') !== '') {
            $query->where('status', $request->input('status'));
        }

        // Filter by payment source (processor or type)
        if ($request->has('source') && $request->input('source') !== '') {
            $source = $request->input('source');
            if ($source === 'quote') {
                $query->whereNotNull('quote_id')->whereNull('repair_id')->whereNull('order_id');
            } elseif ($source === 'repair') {
                $query->whereNotNull('repair_id')->whereNull('quote_id')->whereNull('order_id');
            } elseif ($source === 'order') {
                $query->whereNotNull('order_id')->whereNull('quote_id')->whereNull('repair_id');
            } else {
                // Filter by payment processor (flutterwave, paystack)
                $query->where('payment_source', 'like', "%{$source}%");
            }
        }

        // Search by reference or customer email
        if ($request->has('search') && $request->input('search') !== '') {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('reference', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('transaction_id', 'like', "%{$search}%");
            });
        }

        // Filter by date range
        $fromDate = $request->input('from_date');
        if (!empty($fromDate)) {
            $query->whereDate('created_at', '>=', $fromDate);
        }

        $toDate = $request->input('to_date');
        if (!empty($toDate)) {
            $query->whereDate('created_at', '<=', $toDate);
        }

        $payments = $query->paginate(20);

        // Get summary statistics - from ALL payments
        $stats = [
            'total_payments' => Payment::count(),
            'total_revenue' => Payment::where('status', Payment::STATUS_COMPLETED)->sum('amount'),
            'completed_count' => Payment::where('status', Payment::STATUS_COMPLETED)->count(),
            'pending_payments' => Payment::where('status', Payment::STATUS_PENDING)->count(),
            'failed_payments' => Payment::where('status', Payment::STATUS_FAILED)->count(),
            'cancelled_payments' => Payment::where('status', Payment::STATUS_CANCELLED)->count(),
            // Additional stats by source
            'quote_payments' => Payment::whereNotNull('quote_id')->whereNull('repair_id')->whereNull('order_id')->count(),
            'repair_payments' => Payment::whereNotNull('repair_id')->whereNull('quote_id')->whereNull('order_id')->count(),
            'order_payments' => Payment::whereNotNull('order_id')->whereNull('quote_id')->whereNull('repair_id')->count(),
            'flutterwave_payments' => Payment::where('payment_source', 'like', '%flutterwave%')->count(),
            'paystack_payments' => Payment::where('payment_source', 'like', '%paystack%')->count(),
        ];

        return view('payment.admin-list', [
            'payments' => $payments,
            'stats' => $stats,
        ]);
    }

    /**
     * Refresh/recheck payment status from the payment's original processor
     */
    public function adminRefreshPayment(Request $request, Payment $payment)
    {
        // Set JSON response header
        header('Content-Type: application/json');

        // Admin only - check authorization
        if (!auth()->user() || !auth()->user()->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            Log::info('Starting payment refresh', [
                'payment_id' => $payment->id,
                'reference' => $payment->reference,
                'transaction_id' => $payment->transaction_id,
                'payment_source' => $payment->payment_source,
            ]);

            if (!$payment->transaction_id && !$payment->reference) {
                return response()->json([
                    'error' => 'No transaction ID or reference found for this payment',
                    'payment' => $payment
                ], 400);
            }

            // Get the processor that was used for THIS payment (from payment_source)
            $paymentSource = $payment->payment_source ? strtolower($payment->payment_source) : null;

            // If payment_source is not set, try to detect from reference pattern or response_data
            if (!$paymentSource || $paymentSource === 'null') {
                // Check if response_data contains Paystack or Flutterwave specific fields
                if ($payment->response_data) {
                    if (isset($payment->response_data['data']['authorization'])) {
                        // Paystack specific field
                        $paymentSource = 'paystack';
                    } elseif (isset($payment->response_data['data']['link'])) {
                        // Flutterwave specific field
                        $paymentSource = 'flutterwave';
                    }
                }

                // Fallback: check reference pattern (SKYEFACE pattern usually indicates Paystack)
                if (!$paymentSource && $payment->reference && str_contains($payment->reference, 'SKYEFACE')) {
                    $paymentSource = 'paystack';
                }
            }

            Log::info('Detected payment source', [
                'payment_id' => $payment->id,
                'detected_source' => $paymentSource,
            ]);

            // Determine which service to use based on payment_source
            if ($paymentSource === 'paystack' || str_contains($paymentSource ?? '', 'paystack')) {
                $service = $this->paystackService;
                $identifierToVerify = $payment->reference ?? $payment->transaction_id;
            } else {
                // Default to Flutterwave for 'flutterwave' or unknown sources
                $service = $this->flutterwaveService;
                $identifierToVerify = $payment->transaction_id ?? $payment->reference;
            }

            Log::info('Calling service to verify payment', [
                'payment_id' => $payment->id,
                'service' => $service::class,
                'identifier' => $identifierToVerify,
            ]);

            // Verify payment with the original processor
            $verification = $service->verifyPayment($identifierToVerify);

            Log::info('Verification result', [
                'payment_id' => $payment->id,
                'verification' => $verification,
            ]);

            if ($verification['success']) {
                $status = $verification['status'] ?? null;

                Log::info('Payment verification successful', [
                    'payment_id' => $payment->id,
                    'status' => $status,
                    'is_completed' => in_array($status, ['successful', 'completed', 'success']),
                ]);

                // Handle both Paystack status ('success') and Flutterwave status ('successful', 'completed')
                if (in_array($status, ['successful', 'completed', 'success'])) {
                    // Update payment to completed
                    $payment->update([
                        'status' => Payment::STATUS_COMPLETED,
                        'paid_at' => now(),
                    ]);

                    // Update quote status
                    if ($payment->quote) {
                        $payment->quote->update(['status' => Quote::STATUS_ACCEPTED]);
                    }

                    // Update repair status if this is a repair payment
                    if ($payment->repair) {
                        $payment->repair->update([
                            'payment_status' => 'completed',
                            'payment_verified_at' => now(),
                        ]);
                    }

                    Log::info('Payment updated to completed', [
                        'payment_id' => $payment->id,
                        'repair_id' => $payment->repair_id,
                    ]);

                    $response = response()->json([
                        'success' => true,
                        'message' => 'Payment status updated to COMPLETED',
                        'payment' => $payment->fresh(),
                    ]);

                    Log::info('Returning success response', [
                        'payment_id' => $payment->id,
                        'response' => $response->getData(),
                    ]);

                    return $response;
                } else {
                    // Update to current status
                    $payment->update(['status' => $status]);

                    // Update repair status if this is a repair payment
                    if ($payment->repair) {
                        $payment->repair->update(['payment_status' => $status]);
                    }

                    $response = response()->json([
                        'success' => true,
                        'message' => "Payment status updated to " . strtoupper($status ?? 'UNKNOWN'),
                        'payment' => $payment->fresh(),
                    ]);

                    Log::info('Returning status update response', [
                        'payment_id' => $payment->id,
                        'repair_id' => $payment->repair_id,
                        'status' => $status,
                    ]);

                    return $response;
                }
            } else {
                Log::warning('Payment verification failed', [
                    'payment_id' => $payment->id,
                    'verification' => $verification,
                ]);

                return response()->json([
                    'error' => $verification['message'] ?? 'Could not verify payment',
                    'payment' => $payment
                ], 400);
            }
        } catch (\Exception $e) {
            Log::error('Error refreshing payment', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'error' => 'An error occurred while refreshing payment: ' . $e->getMessage(),
                'payment' => $payment,
                'debug' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Download payment receipt (as printable HTML)
     */
    public function downloadReceipt(Payment $payment)
    {
        // Verify user owns this payment
        if ($payment->customer_email !== auth()->user()->email) {
            abort(403, 'Unauthorized to access this receipt');
        }

        // Get company settings
        $settings = \App\Models\CompanySetting::first();

        // Return receipt as view (not rendered) so asset paths work
        return view('payment.receipt', [
            'payment' => $payment,
            'quote' => $payment->quote,
            'settings' => $settings,
        ]);
    }

    /**
     * Get payment details as JSON
     */
    public function getPayment(Payment $payment)
    {
        // Verify user owns this payment
        if ($payment->customer_email !== auth()->user()->email) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json([
            'data' => $payment->toArray()
        ]);
    }

    /**
     * Manually update payment status (Admin only)
     */
    public function adminUpdatePaymentStatus(Request $request, Payment $payment)
    {
        // Set JSON response header
        header('Content-Type: application/json');

        // Admin only - check authorization
        if (!auth()->user() || !auth()->user()->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            $validated = $request->validate([
                'status' => 'required|in:pending,completed,failed,cancelled',
                'notes' => 'nullable|string|max:500',
            ]);

            // If marking as completed, set paid_at timestamp
            $updateData = ['status' => $validated['status']];
            if ($validated['status'] === Payment::STATUS_COMPLETED) {
                $updateData['paid_at'] = now();
            }

            $payment->update($updateData);

            // Update related records if payment is completed
            if ($validated['status'] === Payment::STATUS_COMPLETED) {
                if ($payment->quote) {
                    $payment->quote->update(['status' => Quote::STATUS_ACCEPTED]);
                }

                if ($payment->repair) {
                    $payment->repair->update([
                        'payment_status' => 'completed',
                        'payment_verified_at' => now(),
                    ]);
                }
            }

            Log::info('Admin manually updated payment status', [
                'admin_id' => auth()->id(),
                'payment_id' => $payment->id,
                'new_status' => $validated['status'],
                'notes' => $validated['notes'] ?? null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Payment status updated successfully',
                'payment' => $payment->fresh(['quote', 'repair']),
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating payment status', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'error' => 'Failed to update payment status: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Handle Flutterwave payment for orders
     */
    public function payWithFlutterwave(Request $request)
    {
        // If it's a GET request (refresh), redirect back to order
        if ($request->isMethod('GET')) {
            return redirect()->route('payment.show', $request->input('order_id'))->with('info', 'Please use the payment button to proceed.');
        }

        try {
            $validated = $request->validate([
                'order_id' => 'required|exists:orders,id',
                'amount' => 'required|numeric|min:1',
                'currency' => 'required|string|in:NGN,USD,GHS,KES,UGX,ZAR,RWF',
                'email' => 'required|email',
                'phone_number' => 'nullable|string',
                'customer_name' => 'nullable|string',
            ]);

            $order = Order::findOrFail($validated['order_id']);

            // Create payment record with reference for callback lookup
            $reference = 'FW-' . $order->id . '-' . time();
            $payment = Payment::create([
                'order_id' => $order->id,
                'amount' => $validated['amount'],
                'currency' => $validated['currency'],
                'processor' => 'flutterwave',
                'status' => Payment::STATUS_PENDING,
                'reference' => $reference,
                'transaction_reference' => $reference,
                'customer_email' => $validated['email'],
                'customer_name' => $validated['customer_name'] ?? $order->customer_name ?? 'Customer',
                'transaction_id' => \Illuminate\Support\Str::uuid(),
            ]);

            Log::info('Flutterwave payment initiated for order', [
                'order_id' => $order->id,
                'payment_id' => $payment->id,
                'amount' => $validated['amount'],
                'currency' => $validated['currency'],
            ]);

            // Return HTML with Flutterwave payment initialization
            return view('payment.flutterwave-checkout', [
                'payment' => $payment,
                'order' => $order,
                'publicKey' => config('payment.flutterwave.public_key'),
            ]);

        } catch (\Exception $e) {
            Log::error('Flutterwave payment error', [
                'error' => $e->getMessage(),
                'order_id' => $request->input('order_id'),
            ]);

            return back()->with('error', 'Payment initialization failed: ' . $e->getMessage());
        }
    }

    /**
     * Handle Paystack payment for orders
     */
    public function payWithPaystack(Request $request)
    {
        // If it's a GET request (refresh), redirect back to order
        if ($request->isMethod('GET')) {
            return redirect()->route('payment.show', $request->input('order_id'))->with('info', 'Please use the payment button to proceed.');
        }

        try {
            $validated = $request->validate([
                'order_id' => 'required|exists:orders,id',
                'amount' => 'required|numeric|min:50', // Paystack minimum
                'email' => 'required|email',
            ]);

            $order = Order::findOrFail($validated['order_id']);

            // Amount is in kobo (cents), convert to naira for storage
            $amountInNaira = $validated['amount'] / 100;

            // Create payment record with reference for callback lookup
            $reference = 'PS-' . $order->id . '-' . time();
            $payment = Payment::create([
                'order_id' => $order->id,
                'amount' => $amountInNaira,
                'currency' => 'NGN',
                'processor' => 'paystack',
                'status' => Payment::STATUS_PENDING,
                'reference' => $reference,
                'transaction_reference' => $reference,
                'customer_email' => $validated['email'],
                'customer_name' => $order->customer_name ?? 'Customer',
                'transaction_id' => \Illuminate\Support\Str::uuid(),
            ]);

            Log::info('Paystack payment initiated for order', [
                'order_id' => $order->id,
                'payment_id' => $payment->id,
                'amount' => $amountInNaira,
            ]);

            // Return HTML with Paystack payment initialization
            return view('payment.paystack-checkout', [
                'payment' => $payment,
                'order' => $order,
                'publicKey' => config('payment.paystack.public_key'),
            ]);

        } catch (\Exception $e) {
            Log::error('Paystack payment error', [
                'error' => $e->getMessage(),
                'order_id' => $request->input('order_id'),
            ]);

            return back()->with('error', 'Payment initialization failed: ' . $e->getMessage());
        }
    }
}




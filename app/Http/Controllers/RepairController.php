<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Repair;
use App\Models\Payment;
use App\Models\RepairStatus;
use App\Models\RepairPricing;
use App\Mail\RepairBookingConfirmation;
use App\Mail\RepairPaymentConfirmation;
use App\Mail\RepairStatusUpdate;
use Illuminate\Support\Str;
use Mail;
use Illuminate\Support\Facades\DB;

class RepairController extends Controller
{
    /**
     * Store a new repair booking
     */
    public function store(Request $request)
    {
        try {
            // Validate the request
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:20',
                'device_type' => 'required|string|max:100',
                'brand' => 'required|string|max:100',
                'model' => 'required|string|max:100',
                'issue_description' => 'required|string|min:10|max:1000',
                'urgency' => 'nullable|in:Normal,Express,Urgent',
                'cost_estimate' => 'nullable|numeric|min:0',
            ]);

            // Generate unique invoice/tracking number
            $invoiceNumber = $this->generateInvoiceNumber();

            // Create the repair record
            $repair = Repair::create([
                'invoice_number' => $invoiceNumber,
                'customer_name' => $validated['name'],
                'customer_email' => $validated['email'],
                'customer_phone' => $validated['phone'],
                'device_type' => $validated['device_type'],
                'device_brand' => $validated['brand'],
                'device_model' => $validated['model'],
                'issue_description' => $validated['issue_description'],
                'urgency' => $validated['urgency'] ?? 'Normal',
                'status' => 'Pending',
                'cost_estimate' => $validated['cost_estimate'] ?? 0,
            ]);

            // Create initial status record
            RepairStatus::create([
                'repair_id' => $repair->id,
                'status' => 'Pending',
                'description' => 'Your device repair booking has been received. Please bring your device to our office for inspection.',
                'stage' => 1,
            ]);

            // Send booking confirmation email
            try {
                Mail::to($repair->customer_email)->send(new RepairBookingConfirmation($repair));
                
                // Log email to database
                DB::table('mail_logs')->insert([
                    'to' => $repair->customer_email,
                    'subject' => 'Repair Booking Confirmed - ' . $repair->invoice_number,
                    'body' => 'Your device repair booking has been received. Invoice: ' . $repair->invoice_number . ' Device: ' . $repair->device_brand . ' ' . $repair->device_model,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                
                \Log::info('✓ Booking confirmation email sent and logged for: ' . $repair->invoice_number);
            } catch (\Exception $e) {
                \Log::error('Failed to send booking confirmation email: ' . $e->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'Repair booking submitted successfully',
                'invoice_number' => $invoiceNumber,
                'tracking_number' => $invoiceNumber,
                'repair_id' => $repair->id,
                'repair' => [
                    'id' => $repair->id,
                    'invoice_number' => $repair->invoice_number,
                    'customer_name' => $repair->customer_name,
                    'customer_email' => $repair->customer_email,
                    'customer_phone' => $repair->customer_phone,
                    'cost_estimate' => $repair->cost_estimate,
                    'device_type' => $repair->device_type,
                    'device_brand' => $repair->device_brand,
                    'device_model' => $repair->device_model,
                    'status' => $repair->status,
                ],
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Repair booking error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing your request.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get repair status by invoice number
     */
    public function getStatus($invoiceNumber)
    {
        try {
            $repair = Repair::where('invoice_number', $invoiceNumber)->firstOrFail();

            $statuses = RepairStatus::where('repair_id', $repair->id)
                ->orderBy('created_at', 'asc')
                ->get();

            $progressPercentage = $this->calculateProgress($repair->status);

            return response()->json([
                'success' => true,
                'repair' => $repair,
                'statuses' => $statuses,
                'current_status' => $repair->status,
                'progress_percentage' => $progressPercentage,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Repair not found',
            ], 404);
        }
    }

    /**
     * Search and display repair status (from POST form)
     */
    public function searchStatus(Request $request)
    {
        $invoiceNumber = $request->input('invoice_number');

        if (!$invoiceNumber) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Please enter an invoice number'], 400);
            }
            return redirect()->back()->with('error', 'Please enter an invoice number');
        }

        try {
            $repair = Repair::where('invoice_number', $invoiceNumber)->firstOrFail();

            $statuses = RepairStatus::where('repair_id', $repair->id)
                ->orderBy('created_at', 'asc')
                ->get();

            $progressPercentage = $this->calculateProgress($repair->status);

            // If AJAX request, return JSON
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'repair' => $repair,
                    'statuses' => $statuses,
                    'progressPercentage' => $progressPercentage,
                ]);
            }

            // Otherwise return view
            return view('repairs.search-result', compact('repair', 'statuses', 'progressPercentage'));
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Repair invoice not found. Please check and try again.'], 404);
            }
            return redirect()->back()->with('error', 'Repair invoice not found. Please check the invoice number and try again.');
        }
    }

    /**
     * Update repair status (for admin)
     */
    public function updateStatus(Request $request, $repairId)
    {
        try {
            // Check if user is authorized (should be admin)
            // TODO: Add authorization check

            $validated = $request->validate([
                'status' => 'required|string|max:100',
                'description' => 'nullable|string|max:1000',
                'estimated_completion' => 'nullable|date',
            ]);

            $repair = Repair::findOrFail($repairId);

            // Determine stage
            $stages = [
                'Pending' => 0,
                'Received' => 1,
                'Diagnosed' => 2,
                'In Progress' => 3,
                'Quality Check' => 4,
                'Quality Checked' => 5,
                'Cost Approval' => 6,
                'Ready for Pickup' => 7,
                'Completed' => 8,
            ];

            $stage = $stages[$validated['status']] ?? 1;

            // Create status record
            RepairStatus::create([
                'repair_id' => $repair->id,
                'status' => $validated['status'],
                'description' => $validated['description'] ?? '',
                'stage' => $stage,
                'estimated_completion' => $validated['estimated_completion'] ?? null,
            ]);

            // Update repair status
            $repair->update([
                'status' => $validated['status'],
                'estimated_completion' => $validated['estimated_completion'] ?? $repair->estimated_completion,
            ]);

            // Charge consultation fee when device is received
            if ($validated['status'] === 'Received' && $repair->payment_status !== 'completed') {
                try {
                    // Create payment record for consultation fee
                    $reference = 'REPAIR-CONSULTATION-' . $repair->id . '-' . time();

                    Payment::create([
                        'repair_id' => $repair->id,
                        'quote_id' => null,
                        'transaction_id' => $reference,
                        'reference' => $reference,
                        'amount' => $repair->cost_estimate,
                        'currency' => 'NGN',
                        'status' => Payment::STATUS_COMPLETED,
                        'customer_email' => $repair->customer_email,
                        'customer_name' => $repair->customer_name,
                        'payment_source' => 'admin_manual',
                        'paid_at' => now(),
                        'response_data' => json_encode([
                            'type' => 'consultation_fee',
                            'repair_id' => $repair->id,
                            'invoice_number' => $repair->invoice_number,
                            'charged_at' => now()->toDateTimeString(),
                        ]),
                    ]);

                    // Update repair payment status
                    $repair->update([
                        'payment_status' => 'completed',
                        'payment_verified_at' => now(),
                        'payment_reference' => $reference,
                        'payment_processor' => 'admin_manual',
                    ]);

                    \Log::info('Consultation fee recorded', [
                        'repair_id' => $repair->id,
                        'amount' => $repair->cost_estimate,
                        'reference' => $reference,
                    ]);

                    // Send payment confirmation email
                    try {
                        Mail::to($repair->customer_email)->send(new RepairPaymentConfirmation($repair));
                        \Log::info('Consultation fee confirmation email sent', ['repair_id' => $repair->id]);
                    } catch (\Exception $e) {
                        \Log::error('Failed to send consultation fee confirmation: ' . $e->getMessage());
                    }
                } catch (\Exception $e) {
                    \Log::error('Failed to record consultation fee: ' . $e->getMessage());
                    // Continue even if payment recording fails - status is already updated
                }
            }

            // TODO: Send status update email to customer

            return response()->json([
                'success' => true,
                'message' => 'Repair status updated successfully',
                'repair' => $repair,
            ]);

        } catch (\Exception $e) {
            \Log::error('Repair status update error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Generate unique invoice number
     */
    private function generateInvoiceNumber()
    {
        do {
            $invoiceNumber = 'REP-' . strtoupper(Str::random(3)) . '-' . date('Ymd') . '-' . str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
        } while (Repair::where('invoice_number', $invoiceNumber)->exists());

        return $invoiceNumber;
    }

    /**
     * Calculate progress percentage based on status
     */
    private function calculateProgress($status)
    {
        $progress = [
            'Received' => 20,
            'Diagnosed' => 40,
            'In Progress' => 60,
            'Quality Check' => 80,
            'Ready for Pickup' => 90,
            'Completed' => 100,
        ];

        return $progress[$status] ?? 0;
    }

    /**
     * Admin - List all repairs
     */
    public function adminIndex()
    {
        $repairs = Repair::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.repairs.index', compact('repairs'));
    }

    /**
     * Admin - Show repair details
     */
    public function adminShow(Repair $repair)
    {
        $statuses = $repair->statusUpdates()->orderBy('created_at', 'desc')->get();
        return view('admin.repairs.show', compact('repair', 'statuses'));
    }

    /**
     * Admin - Update repair status
     */
    public function adminUpdateStatus(Request $request, Repair $repair)
    {
        try {
            $validated = $request->validate([
                'status' => 'required|string|in:Pending,Received,Diagnosed,In Progress,Quality Check,Quality Checked,Cost Approval,Ready for Pickup,Completed',
                'notes' => 'nullable|string',
            ]);

            $repair->update([
                'status' => $validated['status'],
            ]);

            // Add status record
            $statusRecord = RepairStatus::create([
                'repair_id' => $repair->id,
                'status' => $validated['status'],
                'notes' => $validated['notes'] ?? null,
            ]);

            // Send status update email to customer
            try {
                \Log::info('Starting email send for repair: ' . $repair->invoice_number . ' to: ' . $repair->customer_email);
                
                Mail::to($repair->customer_email)->send(new RepairStatusUpdate($repair, $validated['status'], $validated['notes'] ?? null));
                
                \Log::info('✓ Email sent successfully for: ' . $repair->invoice_number);
                
                // Log email to database
                try {
                    DB::table('mail_logs')->insert([
                        'to' => $repair->customer_email,
                        'subject' => 'Repair Status Update - ' . $repair->invoice_number,
                        'body' => 'Status: ' . $validated['status'] . (isset($validated['notes']) ? ' - ' . substr($validated['notes'], 0, 100) : ''),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    \Log::info('✓ Email logged to database for: ' . $repair->invoice_number);
                } catch (\Exception $dbEx) {
                    \Log::error('Failed to log email to database: ' . $dbEx->getMessage());
                }
                
            } catch (\Exception $e) {
                \Log::error('Failed to send status update email', [
                    'invoice' => $repair->invoice_number,
                    'customer_email' => $repair->customer_email,
                    'error' => $e->getMessage(),
                    'exception_class' => get_class($e),
                    'trace' => $e->getTraceAsString()
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully',
                'status_record' => $statusRecord,
            ]);
        } catch (\Exception $e) {
            \Log::error('Admin repair status update error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating status',
            ], 500);
        }
    }

    /**
     * Admin - Update repair total cost (after quality check)
     */
    public function adminUpdateCost(Request $request, Repair $repair)
    {
        try {
            $validated = $request->validate([
                'cost_actual' => 'required|numeric|min:0',
            ]);

            $repair->update([
                'cost_actual' => $validated['cost_actual'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Repair cost updated successfully',
            ]);
        } catch (\Exception $e) {
            \Log::error('Admin repair cost update error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating repair cost',
            ], 500);
        }
    }

    /**
     * Admin - Add notes to repair
     */
    public function adminAddNotes(Request $request, Repair $repair)
    {
        try {
            $validated = $request->validate([
                'notes' => 'required|string|max:1000',
            ]);

            RepairStatus::create([
                'repair_id' => $repair->id,
                'status' => $repair->status,
                'notes' => $validated['notes'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Notes added successfully',
            ]);
        } catch (\Exception $e) {
            \Log::error('Admin add notes error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error adding notes',
            ], 500);
        }
    }

    /**
     * Get repair pricing for frontend
     */
    public function getPricing()
    {
        $pricing = RepairPricing::all()->keyBy('device_type')->map(function ($item) {
            return (float) $item->price;
        })->toArray();

        return response()->json([
            'success' => true,
            'pricing' => $pricing,
        ]);
    }

    /**
     * Admin - Show repair pricing management page
     */
    public function adminPricingIndex()
    {
        $pricing = RepairPricing::all();
        return view('admin.repairs.pricing', compact('pricing'));
    }

    /**
     * Admin - Update repair pricing
     */
    public function adminUpdatePricing(Request $request, RepairPricing $repairPricing)
    {
        try {
            $validated = $request->validate([
                'price' => 'required|numeric|min:0',
                'description' => 'nullable|string|max:500',
            ]);

            $repairPricing->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Price updated successfully',
                'pricing' => $repairPricing,
            ]);
        } catch (\Exception $e) {
            \Log::error('Repair pricing update error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating price',
            ], 500);
        }
    }

    /**
     * Get active payment processor
     */
    public function getActivePaymentProcessor()
    {
        try {
            $processor = config('payment.active_processor', 'flutterwave');

            return response()->json([
                'success' => true,
                'processor' => $processor, // 'flutterwave' or 'paystack'
            ]);
        } catch (\Exception $e) {
            \Log::error('Get payment processor error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'processor' => 'flutterwave', // Default fallback
            ]);
        }
    }

    /**
     * Show repair payment form
     */
    public function showRepairPaymentForm(Repair $repair)
    {
        try {
            // Get active payment processor
            $processor = \App\Services\PaymentProcessorService::getActiveProcessor();
            
            // Get payment service based on processor
            if ($processor === 'paystack') {
                $service = app(\App\Services\PaystackService::class);
            } else {
                $service = app(\App\Services\FlutterwaveService::class);
            }

            // Use cost_actual (final repair cost) if set and > 0, otherwise use cost_estimate (diagnosis fee)
            $amount = ($repair->cost_actual && $repair->cost_actual > 0) ? $repair->cost_actual : $repair->cost_estimate;

            return view('repairs.payment-form', [
                'repair' => $repair,
                'processor' => $processor,
                'publicKey' => $service->getPublicKey(),
                'amount' => $amount,
                'currency' => config('payment.' . $processor . '.currency', 'NGN'),
            ]);
        } catch (\Exception $e) {
            \Log::error('Repair payment form error: ' . $e->getMessage());
            return back()->with('error', 'Unable to process payment at this time. Please try again later.');
        }
    }

    /**
     * Initiate payment for repair booking
     */
    public function initiateRepairPayment(Request $request, Repair $repair)
    {
        try {
            \Log::info('Payment initiation started', [
                'repair_id' => $repair->id,
                'repair_invoice' => $repair->invoice_number,
            ]);

            $validated = $request->validate([
                'processor' => 'nullable|in:flutterwave,paystack',
                'amount' => 'required|numeric|min:0',
                'email' => 'required|email',
                'name' => 'required|string',
                'currency' => 'nullable|string',
            ]);

            // Use the active processor if not specified
            $processor = $validated['processor'] ?? \App\Services\PaymentProcessorService::getActiveProcessor();
            $amount = $validated['amount'];
            $currency = $validated['currency'] ?? 'NGN';

            \Log::info('Payment validation passed', [
                'processor' => $processor,
                'amount' => $amount,
                'currency' => $currency,
                'repair_id' => $repair->id,
            ]);

            // Validate that the processor is properly configured
            if (!in_array($processor, ['flutterwave', 'paystack'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid payment processor',
                ], 400);
            }

            if ($processor === 'flutterwave') {
                return $this->initiateFlutterwavePayment($repair, $amount, $validated['email'], $validated['name'], $currency);
            } elseif ($processor === 'paystack') {
                return $this->initiatePaystackPayment($repair, $amount, $validated['email'], $validated['name'], $currency);
            }

            return response()->json([
                'success' => false,
                'message' => 'Invalid payment processor',
            ], 400);
        } catch (\Exception $e) {
            \Log::error('Payment initiation error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'repair_id' => $repair->id ?? 'unknown',
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error initiating payment: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Initiate Flutterwave payment
     */
    private function initiateFlutterwavePayment(Repair $repair, $amount, $email, $name)
    {
        try {
            \Log::info('Initiating Flutterwave payment', ['repair_id' => $repair->id, 'amount' => $amount]);

            $flutterwaveService = app(\App\Services\FlutterwaveService::class);

            \Log::info('Flutterwave service resolved');

            $reference = 'REPAIR-' . $repair->id . '-' . time();
            $description = 'Device Repair - ' . $repair->invoice_number;

            \Log::info('Calling Flutterwave service', [
                'amount' => $amount,
                'email' => $email,
                'name' => $name,
                'reference' => $reference,
            ]);

            $response = $flutterwaveService->initializePayment(
                $amount,
                $email,
                $name,
                $reference,
                $description,
                'NGN'
            );

            \Log::info('Flutterwave response received', $response);

            if ($response['success']) {
                // Create Payment record for repair
                $payment = \App\Models\Payment::create([
                    'repair_id' => $repair->id,
                    'amount' => $amount,
                    'currency' => 'NGN',
                    'status' => \App\Models\Payment::STATUS_PENDING,
                    'reference' => $reference,
                    'customer_email' => $email,
                    'customer_name' => $name,
                    'payment_source' => 'Flutterwave',
                    'payment_method' => 'card',
                ]);

                // Store payment reference in repair
                $repair->update([
                    'payment_reference' => $reference,
                    'payment_processor' => 'flutterwave',
                    'payment_id' => $payment->id,
                ]);

                return response()->json([
                    'success' => true,
                    'payment_url' => $response['payment_link'] ?? $response['data']['link'] ?? null,
                    'reference' => $reference,
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => $response['error'] ?? 'Failed to initialize Flutterwave payment',
            ], 400);
        } catch (\Exception $e) {
            \Log::error('Flutterwave payment error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error with Flutterwave payment: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Initiate Paystack payment
     */
    private function initiatePaystackPayment(Repair $repair, $amount, $email, $name)
    {
        try {
            \Log::info('Initiating Paystack payment', ['repair_id' => $repair->id, 'amount' => $amount]);

            $paystackService = app(\App\Services\PaystackService::class);

            $reference = 'REPAIR-' . $repair->id . '-' . time();

            $paymentData = [
                'amount' => $amount * 100, // Paystack expects amount in kobo
                'email' => $email,
                'reference' => $reference,
                'metadata' => [
                    'repair_id' => $repair->id,
                    'invoice_number' => $repair->invoice_number,
                    'customer_name' => $name,
                ],
            ];

            \Log::info('Paystack payment data prepared', $paymentData);

            $response = $paystackService->initializePayment($paymentData);

            \Log::info('Paystack response received', $response);

            if ($response['success']) {
                // Create Payment record for repair
                $payment = \App\Models\Payment::create([
                    'repair_id' => $repair->id,
                    'amount' => $amount,
                    'currency' => 'NGN',
                    'status' => \App\Models\Payment::STATUS_PENDING,
                    'reference' => $reference,
                    'customer_email' => $email,
                    'customer_name' => $name,
                    'payment_source' => 'Paystack',
                    'payment_method' => 'card',
                ]);

                // Store payment reference and payment_id in repair
                $repair->update([
                    'payment_reference' => $reference,
                    'payment_processor' => 'paystack',
                    'payment_id' => $payment->id,
                ]);

                return response()->json([
                    'success' => true,
                    'public_key' => config('services.paystack.public_key'),
                    'email' => $email,
                    'amount' => $amount,
                    'reference' => $reference,
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => $response['error'] ?? $response['message'] ?? 'Failed to initialize Paystack payment',
            ], 400);
        } catch (\Exception $e) {
            \Log::error('Paystack payment error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error with Paystack payment: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Handle payment callback and send confirmation email
     */
    public function paymentCallback(Request $request)
    {
        try {
            $reference = $request->get('reference');

            if (!$reference) {
                return redirect()->route('repairs.track.page')->with('error', 'No payment reference provided');
            }

            // Find repair by payment reference
            $repair = Repair::where('payment_reference', $reference)->firstOrFail();

            // Update payment status
            $repair->update([
                'payment_status' => 'completed',
                'payment_verified_at' => now(),
            ]);

            // Update related Payment record status
            if ($repair->payment_id) {
                \App\Models\Payment::where('id', $repair->payment_id)->update([
                    'status' => \App\Models\Payment::STATUS_COMPLETED,
                    'paid_at' => now(),
                ]);
            }

            // Send payment confirmation email
            try {
                Mail::to($repair->customer_email)->send(new RepairPaymentConfirmation($repair));
            } catch (\Exception $e) {
                \Log::error('Failed to send payment confirmation email: ' . $e->getMessage());
            }

            return redirect()->route('repairs.track.page')->with('success', 'Payment confirmed! Your repair booking is now active.');
        } catch (\Exception $e) {
            \Log::error('Payment callback error: ' . $e->getMessage());
            return redirect()->route('repairs.track.page')->with('error', 'Error processing payment callback');
        }
    }

    /**
     * Flutterwave callback handler
     */
    public function flutterwaveCallback(Request $request)
    {
        try {
            $transactionId = $request->get('transaction_id');
            $txRefParam = $request->get('tx_ref');

            \Log::info('Flutterwave callback received', [
                'transaction_id' => $transactionId,
                'tx_ref_param' => $txRefParam,
            ]);

            if (!$transactionId) {
                \Log::warning('Flutterwave callback: No transaction_id provided');
                return redirect()->route('repairs.track.page')->with('error', 'Payment verification failed: No transaction ID provided');
            }

            // Verify with Flutterwave service
            $flutterwaveService = app(\App\Services\FlutterwaveService::class);
            $result = $flutterwaveService->verifyPayment($transactionId);

            \Log::info('Flutterwave verification result', [
                'success' => $result['success'] ?? false,
                'tx_ref_response' => $result['tx_ref'] ?? null,
                'tx_ref_param' => $txRefParam,
            ]);

            if ($result['success']) {
                // Get data from nested response structure
                $data = $result['data'] ?? [];
                $txRef = $txRefParam ?? $data['tx_ref'] ?? null;
                $amount = $data['amount'] ?? 0;
                $currency = $data['currency'] ?? 'NGN';

                \Log::info('Flutterwave verifying payment', [
                    'tx_ref' => $txRef,
                    'amount' => $amount,
                    'currency' => $currency,
                ]);

                // Update repair payment status
                $repair = Repair::where('payment_reference', $txRef)->first();

                // Fallback: Try to extract repair ID from tx_ref string (format: REPAIR-{id}-{timestamp})
                if (!$repair && $txRef && preg_match('/^REPAIR-(\d+)-/', $txRef, $matches)) {
                    $repairId = $matches[1];
                    $repair = Repair::find($repairId);
                    if ($repair) {
                        \Log::info('Flutterwave repair found via fallback ID lookup', [
                            'tx_ref' => $txRef,
                            'repair_id' => $repairId,
                        ]);
                    }
                }

                \Log::info('Flutterwave repair lookup', [
                    'tx_ref' => $txRef,
                    'repair_found' => $repair ? true : false,
                    'repair_id' => $repair->id ?? null,
                ]);

                if ($repair) {
                    try {
                        $repair->update([
                            'payment_status' => 'completed',
                            'payment_verified_at' => now(),
                        ]);

                        \Log::info('Flutterwave repair updated', [
                            'repair_id' => $repair->id,
                            'payment_status' => 'completed',
                        ]);

                        // Update Payment record if it exists
                        if ($repair->payment_id) {
                            Payment::where('id', $repair->payment_id)->update([
                                'status' => Payment::STATUS_COMPLETED,
                                'transaction_id' => $transactionId,
                                'paid_at' => now(),
                                'response_data' => json_encode($result),
                            ]);
                        } else {
                            // Create Payment record for tracking if it doesn't exist
                            $payment = Payment::create([
                                'repair_id' => $repair->id,
                                'transaction_id' => $transactionId,
                                'amount' => $amount ?? $repair->cost_estimate ?? 0,
                                'currency' => $currency ?? 'NGN',
                                'status' => Payment::STATUS_COMPLETED,
                                'reference' => $txRef,
                                'customer_email' => $repair->customer_email,
                                'customer_name' => $repair->customer_name,
                                'payment_source' => 'Flutterwave',
                                'paid_at' => now(),
                                'response_data' => json_encode($result),
                            ]);

                            $repair->update(['payment_id' => $payment->id]);
                        }

                        \Log::info('Flutterwave payment record updated', [
                            'repair_id' => $repair->id,
                        ]);

                        // Send confirmation email
                        try {
                            Mail::to($repair->customer_email)->send(new RepairPaymentConfirmation($repair));
                            \Log::info('Flutterwave confirmation email sent', ['repair_id' => $repair->id]);
                        } catch (\Exception $e) {
                            \Log::error('Failed to send Flutterwave payment confirmation: ' . $e->getMessage());
                        }

                        return redirect()->route('repairs.track.page')->with('success', 'Payment confirmed successfully! Your repair booking is now active.');
                    } catch (\Exception $e) {
                        \Log::error('Flutterwave repair update failed', [
                            'repair_id' => $repair->id ?? null,
                            'error' => $e->getMessage(),
                            'trace' => $e->getTraceAsString(),
                        ]);
                        return redirect()->route('repairs.track.page')->with('error', 'Error processing payment: ' . $e->getMessage());
                    }
                } else {
                    \Log::warning('Flutterwave repair not found', [
                        'tx_ref' => $result['tx_ref'],
                    ]);
                    return redirect()->route('repairs.track.page')->with('error', 'Payment verified but repair record not found');
                }
            }

            \Log::warning('Flutterwave verification failed', $result);
            return redirect()->route('repairs.track.page')->with('error', 'Payment verification failed: ' . ($result['error'] ?? 'Unknown error'));
        } catch (\Exception $e) {
            \Log::error('Flutterwave callback error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->route('repairs.track.page')->with('error', 'Error processing payment callback: ' . $e->getMessage());
        }
    }

    /**
     * Paystack callback handler
     */
    public function paystackCallback(Request $request)
    {
        try {
            $reference = $request->get('reference');

            \Log::info('Paystack callback received', ['reference' => $reference]);

            if (!$reference) {
                \Log::warning('Paystack callback: No reference provided');
                return redirect()->route('repairs.track.page')->with('error', 'Payment verification failed: No reference provided');
            }

            // Verify with Paystack service
            $paystackService = app(\App\Services\PaystackService::class);
            $result = $paystackService->verifyPayment($reference);

            \Log::info('Paystack verification result', [
                'success' => $result['success'] ?? false,
                'reference' => $reference,
            ]);

            if ($result['success']) {
                // Update repair payment status
                $repair = Repair::where('payment_reference', $reference)->first();

                // Fallback: Try to extract repair ID from reference string (format: REPAIR-{id}-{timestamp})
                if (!$repair && preg_match('/^REPAIR-(\d+)-/', $reference, $matches)) {
                    $repairId = $matches[1];
                    $repair = Repair::find($repairId);
                    if ($repair) {
                        \Log::info('Paystack repair found via fallback ID lookup', [
                            'reference' => $reference,
                            'repair_id' => $repairId,
                        ]);
                    }
                }

                \Log::info('Paystack repair lookup', [
                    'reference' => $reference,
                    'repair_found' => $repair ? true : false,
                    'repair_id' => $repair->id ?? null,
                ]);

                if ($repair) {
                    try {
                        $repair->update([
                            'payment_status' => 'completed',
                            'payment_verified_at' => now(),
                        ]);

                        \Log::info('Paystack repair updated', [
                            'repair_id' => $repair->id,
                            'payment_status' => 'completed',
                        ]);

                        // Update Payment record if it exists
                        if ($repair->payment_id) {
                            Payment::where('id', $repair->payment_id)->update([
                                'status' => Payment::STATUS_COMPLETED,
                                'transaction_id' => $result['transaction_id'] ?? $reference,
                                'paid_at' => now(),
                                'response_data' => json_encode($result),
                            ]);
                        } else {
                            // Create Payment record for tracking if it doesn't exist
                            $payment = Payment::create([
                                'repair_id' => $repair->id,
                                'reference' => $reference,
                                'transaction_id' => $result['transaction_id'] ?? $reference,
                                'amount' => ($result['amount'] ?? 0) / 100, // Paystack returns in kobo
                                'currency' => $result['currency'] ?? 'NGN',
                                'status' => Payment::STATUS_COMPLETED,
                                'customer_email' => $repair->customer_email,
                                'customer_name' => $repair->customer_name,
                                'payment_source' => 'Paystack',
                                'paid_at' => now(),
                                'response_data' => json_encode($result),
                            ]);

                            $repair->update(['payment_id' => $payment->id]);
                        }

                        \Log::info('Paystack payment record updated', [
                            'repair_id' => $repair->id,
                        ]);

                        // Send confirmation email
                        try {
                            Mail::to($repair->customer_email)->send(new RepairPaymentConfirmation($repair));
                            \Log::info('Paystack confirmation email sent', ['repair_id' => $repair->id]);
                        } catch (\Exception $e) {
                            \Log::error('Failed to send Paystack payment confirmation: ' . $e->getMessage());
                        }

                        return redirect()->route('repairs.track.page')->with('success', 'Payment confirmed successfully! Your repair booking is now active.');
                    } catch (\Exception $e) {
                        \Log::error('Paystack repair update failed', [
                            'repair_id' => $repair->id ?? null,
                            'error' => $e->getMessage(),
                            'trace' => $e->getTraceAsString(),
                        ]);
                        return redirect()->route('repairs.track.page')->with('error', 'Error processing payment: ' . $e->getMessage());
                    }
                } else {
                    \Log::warning('Paystack repair not found', [
                        'reference' => $reference,
                    ]);
                    return redirect()->route('repairs.track.page')->with('error', 'Payment verified but repair record not found');
                }
            }

            \Log::warning('Paystack verification failed', $result);
            return redirect()->route('repairs.track.page')->with('error', 'Payment verification failed: ' . ($result['error'] ?? 'Unknown error'));
        } catch (\Exception $e) {
            \Log::error('Paystack callback error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->route('repairs.track.page')->with('error', 'Error processing payment callback: ' . $e->getMessage());
        }
    }
}

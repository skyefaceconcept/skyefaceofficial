<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use App\Mail\NewQuoteNotification;
use App\Mail\QuoteReceivedConfirmation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class QuoteController extends Controller
{
    /**
     * Check quota limits to prevent spam
     * Limit: 3 quotes per IP per day, 10 quotes per email per day
     */
    private function checkQuotaLimits(Request $request)
    {
        $ip = $request->ip();
        $email = $request->input('email');
        $today = now()->startOfDay();

        // Check IP-based limit
        $ipQuoteCount = Quote::where('ip_address', $ip)
            ->where('created_at', '>=', $today)
            ->count();

        if ($ipQuoteCount >= 3) {
            return [
                'valid' => false,
                'message' => 'You have reached the maximum number of quote requests for today. Please try again tomorrow.',
            ];
        }

        // Check email-based limit
        $emailQuoteCount = Quote::where('email', $email)
            ->where('created_at', '>=', $today)
            ->count();

        if ($emailQuoteCount >= 10) {
            return [
                'valid' => false,
                'message' => 'This email has reached the maximum number of quote requests for today. Please try again tomorrow.',
            ];
        }

        return ['valid' => true];
    }

    public function store(Request $request)
    {
        try {
            // Check quota limits first
            $quotaCheck = $this->checkQuotaLimits($request);
            if (!$quotaCheck['valid']) {
                return response()->json([
                    'success' => false,
                    'message' => $quotaCheck['message'],
                ], 429); // 429 Too Many Requests
            }

            // Validate input
            $validated = $request->validate([
                'package' => 'nullable|string|max:255',
                'name' => 'required|string|max:255|regex:/^[a-zA-Z\s\'-]+$/',
                'email' => 'required|email:rfc|max:255',
                'phone' => 'nullable|regex:/^[0-9\+\-\(\)\s]+$/',
                'details' => 'required|string|min:10|max:5000',
            ], [
                'name.regex' => 'Name can only contain letters, spaces, apostrophes, and hyphens.',
                'phone.regex' => 'Please enter a valid phone number.',
                'details.min' => 'Project details must be at least 10 characters.',
                'email.email' => 'Please enter a valid email address.',
            ]);

            // Create quote with IP address
            $quote = Quote::create([
                'package' => $validated['package'] ?? null,
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'details' => $validated['details'],
                'status' => Quote::STATUS_NEW,
                'notified' => false,
                'ip_address' => $request->ip(),
            ]);

            // Send notification emails
            try {
                // Send to admin (synchronously to ensure logging)
                $adminEmail = config('mail.from.address') ?? 'admin@example.com';
                \Log::info('Attempting to send admin notification to: ' . $adminEmail);

                $adminMailable = new NewQuoteNotification($quote);
                Mail::to($adminEmail)->send($adminMailable);

                // Log to database manually
                \Log::info('DEBUG: About to insert admin email log');
                try {
                    DB::table('mail_logs')->insert([
                        'to' => $adminEmail,
                        'subject' => 'New Quote Request - ' . $quote->id,
                        'body' => 'New quote request from ' . $quote->name . ' (' . $quote->email . ')',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    \Log::info('SUCCESS: Admin email logged to database');
                } catch (\Exception $dbEx) {
                    \Log::error('ERROR logging admin email: ' . $dbEx->getMessage(), ['trace' => $dbEx->getTraceAsString()]);
                    throw $dbEx;
                }

                \Log::info('✓ Admin notification email sent successfully for quote: ' . $quote->id);
                $quote->update(['notified' => true]);

                // Send confirmation to customer (synchronously to ensure logging)
                \Log::info('Attempting to send customer confirmation to: ' . $quote->email);

                $customerMailable = new QuoteReceivedConfirmation($quote);
                Mail::to($quote->email)->send($customerMailable);

                // Log to database manually
                \Log::info('DEBUG: About to insert customer email log');
                try {
                    DB::table('mail_logs')->insert([
                        'to' => $quote->email,
                        'subject' => 'Your Quote Request Has Been Received',
                        'body' => 'Thank you for your quote request. We will review it and get back to you soon.',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    \Log::info('SUCCESS: Customer email logged to database');
                } catch (\Exception $dbEx) {
                    \Log::error('ERROR logging customer email: ' . $dbEx->getMessage(), ['trace' => $dbEx->getTraceAsString()]);
                    throw $dbEx;
                }

                \Log::info('✓ Customer confirmation email sent successfully for quote: ' . $quote->id);
            } catch (\Exception $e) {
                \Log::error('✗ Quote notification emails failed - Exception: ' . $e->getMessage(), [
                    'quote_id' => $quote->id,
                    'exception' => get_class($e),
                    'trace' => $e->getTraceAsString()
                ]);
                // Don't fail the quote creation if emails fail
            }

            return response()->json([
                'success' => true,
                'message' => 'Quote request submitted successfully! You will receive an email confirmation shortly.',
                'quote_id' => $quote->id,
                'tracking_url' => route('quotes.track', ['email' => $quote->email, 'id' => $quote->id]),
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Quote submission error: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit quote. Please try again later.',
            ], 500);
        }
    }

    /**
     * Track quote status by email and ID
     */
    public function track(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'id' => 'required|integer',
        ]);

        $quote = Quote::where('email', $request->email)
            ->where('id', $request->id)
            ->first();

        if (!$quote) {
            return response()->json([
                'success' => false,
                'message' => 'Quote not found. Please check your email and quote ID.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'quote' => [
                'id' => $quote->id,
                'package' => $quote->package,
                'status' => $quote->status,
                'status_label' => Quote::getStatuses()[$quote->status] ?? 'Unknown',
                'created_at' => $quote->created_at->format('M d, Y H:i'),
                'responded_at' => $quote->responded_at ? $quote->responded_at->format('M d, Y H:i') : null,
                'quoted_price' => $quote->quoted_price,
                'response' => $quote->response,
            ],
        ]);
    }
}

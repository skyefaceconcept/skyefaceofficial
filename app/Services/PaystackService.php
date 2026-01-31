<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaystackService
{
    protected $baseUrl = 'https://api.paystack.co';
    protected $publicKey;
    protected $secretKey;

    public function __construct()
    {
        // Get keys from payment config
        $this->publicKey = config('payment.paystack.public_key');
        $this->secretKey = config('payment.paystack.secret_key');

        // Check if keys are loaded correctly
        if (empty($this->publicKey) || empty($this->secretKey)) {
            Log::error('Paystack keys not configured', [
                'public_key' => $this->publicKey ? 'set' : 'empty',
                'secret_key' => $this->secretKey ? 'set' : 'empty',
            ]);
        }
    }

    /**
     * Initialize a payment transaction
     */
    public function initializePayment($amount, $email, $name, $reference, $description = '', $currency = null)
    {
        try {
            // Use provided currency or fall back to config
            $currency = $currency ?? config('payment.paystack.currency', 'NGN');

            // Paystack expects amount in kobo (multiply by 100) for NGN
            // But for other currencies, the amount might be different - check Paystack docs
            $paystackAmount = intval($amount * 100);

            $payload = [
                'email' => $email,
                'amount' => $paystackAmount, // Amount in kobo
                'currency' => $currency,
                'reference' => $reference,
                'metadata' => [
                    'customer_name' => $name,
                    'description' => $description,
                    'reference' => $reference,
                ],
                'callback_url' => route('payment.callback'),
            ];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->secretKey,
            ])->post("{$this->baseUrl}/transaction/initialize", $payload);

            Log::info('Paystack Initialize Payment Response:', [
                'status_code' => $response->status(),
                'response' => $response->json(),
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if ($data['status'] ?? false) {
                    return [
                        'success' => true,
                        'data' => $data,
                        'payment_link' => $data['data']['authorization_url'] ?? null,
                    ];
                }
            }

            // Log the error response details
            Log::error('Paystack API Error Response:', [
                'status' => $response->status(),
                'body' => $response->json(),
            ]);

            return [
                'success' => false,
                'error' => $response->json()['message'] ?? 'Payment initialization failed',
            ];
        } catch (\Exception $e) {
            Log::error('Paystack Initialize Payment Error:', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Verify a payment transaction
     */
    public function verifyPayment($reference)
    {
        try {
            Log::info('Paystack: Starting verification', [
                'reference' => $reference,
                'secret_key_set' => !empty($this->secretKey),
            ]);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->secretKey,
            ])->get("{$this->baseUrl}/transaction/verify/{$reference}");

            Log::info('Paystack Verify Payment Response:', [
                'status_code' => $response->status(),
                'response' => $response->json(),
            ]);

            if ($response->successful()) {
                $data = $response->json();
                Log::info('Paystack response successful', [
                    'status_key_exists' => isset($data['status']),
                    'status_value' => $data['status'] ?? null,
                    'data_status' => $data['data']['status'] ?? null,
                ]);

                if ($data['status'] ?? false) {
                    $returnData = [
                        'success' => true,
                        'status' => $data['data']['status'] ?? null,
                        'data' => $data['data'] ?? [],
                    ];
                    Log::info('Returning success from Paystack verify', $returnData);
                    return $returnData;
                }
            } else {
                Log::warning('Paystack response not successful', [
                    'status_code' => $response->status(),
                    'body' => $response->json(),
                ]);
            }

            $errorReturn = [
                'success' => false,
                'error' => $response->json()['message'] ?? 'Payment verification failed',
            ];
            Log::warning('Returning error from Paystack verify', $errorReturn);
            return $errorReturn;
        } catch (\Exception $e) {
            Log::error('Paystack Verify Payment Error:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get public key for frontend
     */
    public function getPublicKey()
    {
        return $this->publicKey;
    }

    /**
     * Validate webhook signature
     */
    public function validateWebhook($payload, $signature)
    {
        $hash = hash_hmac('sha512', json_encode($payload), $this->secretKey);
        return hash_equals($hash, $signature);
    }
}

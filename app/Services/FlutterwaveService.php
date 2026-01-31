<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Payment;

class FlutterwaveService
{
    protected $baseUrl = 'https://api.flutterwave.com/v3';
    protected $publicKey;
    protected $secretKey;

    public function __construct()
    {
        // Get keys from config/services.php (which reads from .env)
        $this->publicKey = config('services.flutterwave.public_key');
        $this->secretKey = config('services.flutterwave.secret_key');

        // Check if keys are loaded correctly
        if (empty($this->publicKey) || empty($this->secretKey)) {
            \Log::error('Flutterwave keys not configured', [
                'public_key' => $this->publicKey,
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
            $currency = $currency ?? config('services.flutterwave.currency', 'NGN');

            $payload = [
                'tx_ref' => $reference,
                'amount' => $amount,
                'currency' => $currency,
                'payment_options' => 'card,ussd,bank_transfer,mobilemoneyghana,mobilemoneyrwanda,mobilemoneyuganda,mobilemoneykenya,mobilemoneycomoros',
                'redirect_url' => route('payment.callback'),
                'customer' => [
                    'email' => $email,
                    'name' => $name,
                ],
                'customizations' => [
                    'title' => config('app.name', 'Skyeface'),
                    'logo' => asset(\App\Helpers\CompanyHelper::logo()),
                ],
                'meta' => [
                    'reference' => $reference,
                ]
            ];

            if (!empty($description)) {
                $payload['meta']['description'] = $description;
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->secretKey,
            ])->post("{$this->baseUrl}/payments", $payload);

            Log::info('Flutterwave Initialize Payment Response:', [
                'status_code' => $response->status(),
                'response' => $response->json(),
                'headers' => $response->headers(),
            ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json(),
                    'payment_link' => $response->json()['data']['link'] ?? null,
                ];
            }

            // Log the error response details
            Log::error('Flutterwave API Error Response:', [
                'status' => $response->status(),
                'body' => $response->json(),
                'secret_key_prefix' => substr($this->secretKey, 0, 20) . '...',
            ]);

            return [
                'success' => false,
                'error' => $response->json()['message'] ?? 'Payment initialization failed',
            ];
        } catch (\Exception $e) {
            Log::error('Flutterwave Initialize Payment Error:', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Verify a payment transaction
     */
    public function verifyPayment($transactionId)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->secretKey,
            ])->get("{$this->baseUrl}/transactions/{$transactionId}/verify");

            Log::info('Flutterwave Verify Payment Response:', $response->json());

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => true,
                    'status' => $data['data']['status'] ?? null,
                    'data' => $data['data'] ?? [],
                ];
            }

            return [
                'success' => false,
                'error' => $response->json()['message'] ?? 'Payment verification failed',
            ];
        } catch (\Exception $e) {
            Log::error('Flutterwave Verify Payment Error:', ['error' => $e->getMessage()]);
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
        $hash = hash_hmac('sha256', json_encode($payload), $this->secretKey);
        return hash_equals($hash, $signature);
    }
}

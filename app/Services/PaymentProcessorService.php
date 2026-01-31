<?php

namespace App\Services;

class PaymentProcessorService
{
    /**
     * Get the active payment processor name
     */
    public static function getActiveProcessor()
    {
        return config('payment.active_processor', 'flutterwave');
    }

    /**
     * Get the configuration for the active processor
     */
    public static function getActiveConfig()
    {
        $processor = self::getActiveProcessor();
        return config("payment.{$processor}");
    }

    /**
     * Get public key for active processor
     */
    public static function getPublicKey()
    {
        $config = self::getActiveConfig();
        return $config['public_key'] ?? null;
    }

    /**
     * Get secret key for active processor
     */
    public static function getSecretKey()
    {
        $config = self::getActiveConfig();
        return $config['secret_key'] ?? null;
    }

    /**
     * Check if active processor is configured
     */
    public static function isConfigured()
    {
        $processor = self::getActiveProcessor();

        if ($processor === 'flutterwave') {
            return !empty(config('payment.flutterwave.public_key'))
                && !empty(config('payment.flutterwave.secret_key'));
        } elseif ($processor === 'paystack') {
            return !empty(config('payment.paystack.public_key'))
                && !empty(config('payment.paystack.secret_key'));
        }

        return false;
    }

    /**
     * Get processor-specific configuration
     */
    public static function getProcessorConfig($processor)
    {
        return config("payment.{$processor}");
    }

    /**
     * Check if a specific processor is enabled
     */
    public static function isProcessorEnabled($processor)
    {
        return config("payment.{$processor}.enabled", false);
    }

    /**
     * Get the base URL for the active processor
     */
    public static function getBaseUrl()
    {
        $processor = self::getActiveProcessor();

        if ($processor === 'flutterwave') {
            $env = config('payment.flutterwave.environment', 'live');
            return $env === 'sandbox'
                ? 'https://staging-api.flutterwave.com'
                : 'https://api.flutterwave.com';
        } elseif ($processor === 'paystack') {
            return 'https://api.paystack.co';
        }

        return null;
    }

    /**
     * Get processor name in friendly format
     */
    public static function getProcessorName()
    {
        return ucfirst(self::getActiveProcessor());
    }

    /**
     * Check if test mode is enabled
     */
    public static function isTestMode()
    {
        return config('payment.test_mode', false);
    }

    /**
     * Get default currency
     */
    public static function getCurrency()
    {
        return config('payment.currency', 'NGN');
    }

    /**
     * Get currency symbol
     */
    public static function getCurrencySymbol($currency = null)
    {
        if (!$currency) {
            $currency = self::getCurrency();
        }

        return config("payment.currency_symbols.{$currency}", $currency);
    }

    /**
     * Get payment timeout in minutes
     */
    public static function getTimeout()
    {
        return config('payment.timeout', 30);
    }

    /**
     * Verify webhook signature
     *
     * @param string $signature Signature from header
     * @param string $body Request body
     * @return bool
     */
    public static function verifyWebhookSignature($signature, $body)
    {
        $processor = self::getActiveProcessor();
        $secret = config('payment.webhook_secret');

        if (!$secret) {
            return false;
        }

        if ($processor === 'flutterwave') {
            return self::verifyFlutterwaveSignature($signature, $body, $secret);
        } elseif ($processor === 'paystack') {
            return self::verifyPaystackSignature($signature, $body, $secret);
        }

        return false;
    }

    /**
     * Verify Flutterwave webhook signature
     */
    private static function verifyFlutterwaveSignature($signature, $body, $secret)
    {
        $hash = hash_hmac('sha256', $body, $secret);
        return hash_equals($hash, $signature);
    }

    /**
     * Verify Paystack webhook signature
     */
    private static function verifyPaystackSignature($signature, $body, $secret)
    {
        $hash = hash_hmac('sha512', $body, $secret);
        return hash_equals($hash, $signature);
    }

    /**
     * Get all configured processors
     */
    public static function getConfiguredProcessors()
    {
        $processors = [];

        $flutterwave = config('payment.flutterwave');
        if (!empty($flutterwave['public_key']) && !empty($flutterwave['secret_key'])) {
            $processors['flutterwave'] = 'Flutterwave';
        }

        $paystack = config('payment.paystack');
        if (!empty($paystack['public_key']) && !empty($paystack['secret_key'])) {
            $processors['paystack'] = 'Paystack';
        }

        return $processors;
    }

    /**
     * Get fallback processor (if current one fails)
     */
    public static function getFallbackProcessor()
    {
        $active = self::getActiveProcessor();
        $configured = self::getConfiguredProcessors();

        foreach ($configured as $processor => $name) {
            if ($processor !== $active) {
                return $processor;
            }
        }

        return null;
    }

    /**
     * Get payment provider for a transaction
     * Returns the processor that should handle this transaction
     */
    public static function getPaymentProvider()
    {
        if (!self::isConfigured()) {
            return null;
        }

        return self::getActiveProcessor();
    }

    /**
     * Format amount for payment processor
     */
    public static function formatAmount($amount, $processor = null)
    {
        if (!$processor) {
            $processor = self::getActiveProcessor();
        }

        // Flutterwave expects amount in kobo (multiply by 100) for NGN
        if ($processor === 'flutterwave') {
            return (int)($amount * 100);
        }

        // Paystack expects amount in kobo (multiply by 100)
        if ($processor === 'paystack') {
            return (int)($amount * 100);
        }

        return $amount;
    }

    /**
     * Get metadata for a payment
     */
    public static function getPaymentMetadata($quote)
    {
        return [
            'quote_id' => $quote->id ?? null,
            'customer_email' => $quote->email ?? null,
            'customer_name' => $quote->name ?? null,
            'processor' => self::getActiveProcessor(),
            'timestamp' => now()->toIso8601String(),
        ];
    }

    /**
     * Get processor description for UI display
     */
    public static function getProcessorDescription()
    {
        $processor = self::getActiveProcessor();

        $descriptions = [
            'flutterwave' => 'Flutterwave is a comprehensive payment infrastructure for businesses in Africa. Supports cards, mobile money, and bank transfers.',
            'paystack' => 'Paystack is a payment infrastructure platform that enables merchants accept payments online. Supports cards, bank transfers, and mobile money.',
        ];

        return $descriptions[$processor] ?? 'Payment processor';
    }

    /**
     * Get processor icon/logo class
     */
    public static function getProcessorIcon()
    {
        $processor = self::getActiveProcessor();

        return $processor === 'flutterwave' ? 'fa-credit-card' : 'fa-money-check';
    }

    /**
     * Get processor badge color for UI display
     */
    public static function getProcessorBadgeColor()
    {
        $processor = self::getActiveProcessor();

        return $processor === 'flutterwave' ? 'primary' : 'success';
    }

    /**
     * Get callback URL for the active processor
     */
    public static function getCallbackUrl()
    {
        $processor = self::getActiveProcessor();

        if ($processor === 'flutterwave') {
            return route('payment.callback');
        }

        return route('payment.callback');
    }

    /**
     * Get webhook URL for the active processor
     */
    public static function getWebhookUrl()
    {
        $processor = self::getActiveProcessor();

        if ($processor === 'flutterwave') {
            return route('payment.webhook');
        }

        return route('payment.webhook');
    }

    /**
     * Check if processor supports a specific currency
     */
    public static function supportsCurrency($currency, $processor = null)
    {
        if (!$processor) {
            $processor = self::getActiveProcessor();
        }

        $supported = [
            'flutterwave' => ['NGN', 'USD', 'GHS', 'KES', 'UGX', 'ZAR', 'RWF'],
            'paystack' => ['NGN', 'USD', 'GHS', 'ZAR'],
        ];

        return in_array($currency, $supported[$processor] ?? []);
    }

    /**
     * Get supported currencies for active processor
     */
    public static function getSupportedCurrencies()
    {
        $processor = self::getActiveProcessor();

        $currencies = [
            'flutterwave' => ['NGN', 'USD', 'GHS', 'KES', 'UGX', 'ZAR', 'RWF'],
            'paystack' => ['NGN', 'USD', 'GHS', 'ZAR'],
        ];

        return $currencies[$processor] ?? ['NGN'];
    }

    /**
     * Get currency details for display
     */
    public static function getCurrencyDetails($currency = null)
    {
        if (!$currency) {
            $currency = self::getCurrency();
        }

        $details = [
            'NGN' => ['name' => 'Nigerian Naira', 'symbol' => '₦', 'flag' => '🇳🇬'],
            'USD' => ['name' => 'US Dollar', 'symbol' => '$', 'flag' => '🇺🇸'],
            'GHS' => ['name' => 'Ghana Cedis', 'symbol' => 'GH₵', 'flag' => '🇬🇭'],
            'KES' => ['name' => 'Kenyan Shilling', 'symbol' => 'KSh', 'flag' => '🇰🇪'],
            'UGX' => ['name' => 'Ugandan Shilling', 'symbol' => 'USh', 'flag' => '🇺🇬'],
            'ZAR' => ['name' => 'South African Rand', 'symbol' => 'R', 'flag' => '🇿🇦'],
            'RWF' => ['name' => 'Rwanda Franc', 'symbol' => 'FRw', 'flag' => '🇷🇼'],
        ];

        return $details[$currency] ?? ['name' => $currency, 'symbol' => $currency, 'flag' => ''];
    }

    /**
     * Validate payment data before sending to processor
     */
    public static function validatePaymentData($data)
    {
        $processor = self::getActiveProcessor();

        // Common validations
        if (empty($data['email'])) {
            return ['valid' => false, 'message' => 'Customer email is required'];
        }

        if (empty($data['amount']) || $data['amount'] <= 0) {
            return ['valid' => false, 'message' => 'Valid amount is required'];
        }

        if (empty($data['currency'])) {
            return ['valid' => false, 'message' => 'Currency is required'];
        }

        // Check currency support
        if (!self::supportsCurrency($data['currency'], $processor)) {
            return ['valid' => false, 'message' => ucfirst($processor) . ' does not support ' . $data['currency']];
        }

        return ['valid' => true];
    }

    /**
     * Get payment link from processor
     * (This is a helper to determine which service method to call)
     */
    public static function getPaymentLinkMethod()
    {
        $processor = self::getActiveProcessor();

        if ($processor === 'flutterwave') {
            return 'getPaymentLink'; // FlutterwaveService method
        }

        return 'getPaymentLink'; // PaystackService method
    }

    /**
     * Get success redirect message
     */
    public static function getSuccessMessage()
    {
        $processor = self::getActiveProcessor();

        if ($processor === 'flutterwave') {
            return 'Payment successful! Your transaction has been processed by Flutterwave.';
        }

        return 'Payment successful! Your transaction has been processed by Paystack.';
    }

    /**
     * Get error redirect message
     */
    public static function getErrorMessage()
    {
        $processor = self::getActiveProcessor();

        if ($processor === 'flutterwave') {
            return 'Payment failed! Please try again or contact support. (Flutterwave)';
        }

        return 'Payment failed! Please try again or contact support. (Paystack)';
    }
}

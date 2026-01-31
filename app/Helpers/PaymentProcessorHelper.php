<?php

namespace App\Helpers;

use App\Services\PaymentProcessorService;

/**
 * Payment Processor Helper Functions
 * Provides convenient functions for payment processor operations throughout the application
 */
class PaymentProcessorHelper
{
    /**
     * Get the active processor with fallback
     * Returns the active processor or a fallback if the primary is not configured
     */
    public static function getActiveProcessorWithFallback()
    {
        $active = PaymentProcessorService::getActiveProcessor();

        // Check if active processor is configured
        if (PaymentProcessorService::isConfigured()) {
            return $active;
        }

        // Try to fallback to another processor
        $fallback = PaymentProcessorService::getFallbackProcessor();
        if ($fallback) {
            return $fallback;
        }

        return $active; // Return primary even if not fully configured
    }

    /**
     * Check if we can process payments
     */
    public static function canProcessPayments()
    {
        return PaymentProcessorService::isConfigured();
    }

    /**
     * Get processor status for display
     */
    public static function getProcessorStatus()
    {
        $processor = PaymentProcessorService::getActiveProcessor();
        $isConfigured = PaymentProcessorService::isConfigured();
        $testMode = PaymentProcessorService::isTestMode();

        return [
            'processor' => $processor,
            'name' => ucfirst($processor),
            'configured' => $isConfigured,
            'test_mode' => $testMode,
            'status' => $isConfigured ? 'Ready' : 'Not Configured',
            'status_class' => $isConfigured ? 'success' : 'warning',
        ];
    }

    /**
     * Format payment amount for display
     */
    public static function formatPaymentAmount($amount, $currency = null, $includeSymbol = true)
    {
        if (!$currency) {
            $currency = PaymentProcessorService::getCurrency();
        }

        $details = PaymentProcessorService::getCurrencyDetails($currency);
        $symbol = $details['symbol'];
        $formatted = number_format($amount, 2);

        if ($includeSymbol) {
            return $symbol . $formatted;
        }

        return $formatted;
    }

    /**
     * Validate payment configuration before processing
     */
    public static function validatePaymentConfig()
    {
        $processor = PaymentProcessorService::getActiveProcessor();
        $publicKey = PaymentProcessorService::getPublicKey();
        $secretKey = PaymentProcessorService::getSecretKey();

        if (empty($publicKey) || empty($secretKey)) {
            return [
                'valid' => false,
                'error' => ucfirst($processor) . ' is not properly configured. Please contact support.'
            ];
        }

        return ['valid' => true];
    }

    /**
     * Get payment processor CSS class for styling
     */
    public static function getProcessorClass()
    {
        $processor = PaymentProcessorService::getActiveProcessor();
        $badgeColor = PaymentProcessorService::getProcessorBadgeColor();

        return "badge-{$badgeColor}";
    }

    /**
     * Get payment processor icon class
     */
    public static function getProcessorIconClass()
    {
        return 'fa ' . PaymentProcessorService::getProcessorIcon();
    }

    /**
     * Check if multiple processors are configured
     */
    public static function hasMultipleProcessors()
    {
        $configured = PaymentProcessorService::getConfiguredProcessors();
        return count($configured) > 1;
    }

    /**
     * Get list of configured processors
     */
    public static function getConfiguredProcessorsList()
    {
        return PaymentProcessorService::getConfiguredProcessors();
    }

    /**
     * Get payment processor metadata for logging/tracking
     */
    public static function getPaymentMetadata($quote = null)
    {
        return [
            'processor' => PaymentProcessorService::getActiveProcessor(),
            'processor_name' => ucfirst(PaymentProcessorService::getActiveProcessor()),
            'test_mode' => PaymentProcessorService::isTestMode(),
            'configured' => PaymentProcessorService::isConfigured(),
            'quote_id' => $quote?->id,
            'timestamp' => now(),
        ];
    }

    /**
     * Log payment processor action
     */
    public static function logProcessorAction($action, $data = [])
    {
        \Log::info("Payment Processor Action: {$action}", array_merge([
            'processor' => PaymentProcessorService::getActiveProcessor(),
        ], $data));
    }

    /**
     * Handle processor switching with validation
     */
    public static function switchProcessor($newProcessor)
    {
        if (!in_array($newProcessor, ['flutterwave', 'paystack'])) {
            return [
                'success' => false,
                'error' => 'Invalid processor specified.'
            ];
        }

        $configured = PaymentProcessorService::getConfiguredProcessors();
        if (!isset($configured[$newProcessor])) {
            return [
                'success' => false,
                'error' => ucfirst($newProcessor) . ' is not configured.'
            ];
        }

        // Update the active processor in the configuration
        config(['payment.active_processor' => $newProcessor]);

        self::logProcessorAction('switched', ['new_processor' => $newProcessor]);

        return [
            'success' => true,
            'processor' => $newProcessor,
            'message' => 'Switched to ' . ucfirst($newProcessor) . ' successfully.'
        ];
    }
}

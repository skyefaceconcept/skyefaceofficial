<?php

namespace App\Services;

use App\Models\Order;
use App\Models\License;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class LicenseService
{
    /**
     * Generate a license code for an order
     */
    public static function generateLicense(Order $order, $expiryDays = null)
    {
        try {
            $existingLicense = License::where('order_id', $order->id)->first();
            if ($existingLicense) {
                Log::info('License already exists for order', [
                    'order_id' => $order->id,
                    'license_code' => $existingLicense->license_code,
                ]);
                return $existingLicense;
            }

            // Calculate expiry based on license duration if not provided
            if ($expiryDays === null) {
                $expiryDays = self::getDaysForDuration($order->license_duration);
            }

            $licenseCode = self::generateUniqueLicenseCode();
            $expiryDate = now()->addDays($expiryDays);

            $license = License::create([
                'order_id' => $order->id,
                'license_code' => $licenseCode,
                'application_name' => $order->portfolio->title,
                'expiry_date' => $expiryDate,
                'status' => License::STATUS_ACTIVE,
                'metadata' => [
                    'generated_at' => now()->toIso8601String(),
                    'portfolio_title' => $order->portfolio->title,
                    'customer_email' => $order->customer_email,
                    'license_duration' => $order->license_duration,
                ],
            ]);

            Log::info('License generated successfully', [
                'license_id' => $license->id,
                'order_id' => $order->id,
                'license_code' => $licenseCode,
            ]);

            return $license;
        } catch (\Exception $e) {
            Log::error('Error generating license', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Get number of days for a license duration
     */
    private static function getDaysForDuration($duration)
    {
        return match($duration) {
            '6months' => 180,
            '1year' => 365,
            '2years' => 730,
            default => 365,
        };
    }

    /**
     * Generate a unique license code
     */
    public static function generateUniqueLicenseCode()
    {
        do {
            $code = self::createLicenseCode();
            $exists = License::where('license_code', $code)->exists();
        } while ($exists);

        return $code;
    }

    /**
     * Create a formatted license code: SKYEFACE-XXXXX-XXXXX-XXXXX-XXXXX
     */
    private static function createLicenseCode()
    {
        $parts = [];
        for ($i = 0; $i < 5; $i++) {
            $parts[] = self::generateRandomSegment(5);
        }
        return 'SKYEFACE-' . implode('-', $parts);
    }

    /**
     * Generate a random alphanumeric segment
     */
    private static function generateRandomSegment($length = 5)
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $segment = '';
        for ($i = 0; $i < $length; $i++) {
            $segment .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $segment;
    }

    /**
     * Verify a license code
     */
    public static function verifyLicense($licenseCode)
    {
        $license = License::where('license_code', $licenseCode)->first();

        if (!$license) {
            return [
                'valid' => false,
                'message' => 'License code not found',
            ];
        }

        if (!$license->isValid()) {
            return [
                'valid' => false,
                'message' => 'License is ' . $license->status,
            ];
        }

        return [
            'valid' => true,
            'license' => $license,
            'application' => $license->application_name,
            'expiry_date' => $license->expiry_date,
            'activations' => $license->activation_count,
        ];
    }

    /**
     * Activate a license
     */
    public static function activateLicense($licenseCode, $ip = null)
    {
        $license = License::where('license_code', $licenseCode)->first();

        if (!$license) {
            return [
                'success' => false,
                'message' => 'License not found',
            ];
        }

        if (!$license->recordActivation($ip)) {
            return [
                'success' => false,
                'message' => 'License activation failed: ' . $license->status,
            ];
        }

        return [
            'success' => true,
            'message' => 'License activated successfully',
            'activation_count' => $license->activation_count,
        ];
    }
}

<?php

namespace App\Helpers;

use App\Models\CompanySetting;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class CompanyHelper
{
    /**
     * Get company logo from settings or fallback
     * Returns full URL-ready path
     */
    public static function logo()
    {
        try {
            $setting = CompanySetting::first();
            Log::debug('CompanyHelper::logo() - Setting found: ' . ($setting ? 'yes' : 'no'));
            
            if ($setting) {
                Log::debug('CompanyHelper::logo() - name_logo value: ' . ($setting->name_logo ?? 'null'));
            }
            
            if ($setting && $setting->name_logo) {
                $path = $setting->name_logo;
                Log::debug('CompanyHelper::logo() - Using path: ' . $path);
                
                // Check if it's a stored file in storage/app/public
                if (self::isStoragePath($path)) {
                    // Prefer a root-relative URL so the asset is requested on the current
                    // host and scheme (avoids mixed content and host mismatch issues).
                    if (function_exists('request') && request() && request()->getHttpHost()) {
                        return '/branding/' . ltrim($path, '/');
                    }

                    // Use a Laravel route to serve storage assets safely (works around symlink/403 issues on some hosts)
                    try {
                        return route('branding.asset', ['path' => $path]);
                    } catch (\Exception $e) {
                        // Fallback to Storage::url() if route generation fails
                        $url = Storage::url($path);
                        Log::debug('CompanyHelper::logo() - Storage URL fallback: ' . $url);
                        return $url;
                    }
                }
                // If the path looks like a full URL or an absolute path, return as-is; otherwise return an absolute asset URL
                if (preg_match('/^(https?:)?\\/\\//', $path) || strpos($path, '/') === 0) {
                    return $path;
                }
                return asset($path);
            }
        } catch (\Exception $e) {
            Log::error('CompanyHelper::logo() - Error: ' . $e->getMessage());
        }
        
        Log::debug('CompanyHelper::logo() - Using fallback: buzbox/img/logo-s.png');
        return asset('buzbox/img/logo-s.png');
    }

    /**
     * Get company white/footer logo from settings or fallback
     * Returns full URL-ready path
     */
    public static function logoWhite()
    {
        try {
            $setting = CompanySetting::first();
            Log::debug('CompanyHelper::logoWhite() - Setting found: ' . ($setting ? 'yes' : 'no'));
            
            if ($setting) {
                Log::debug('CompanyHelper::logoWhite() - logo value: ' . ($setting->logo ?? 'null'));
            }
            
            if ($setting && $setting->logo) {
                $path = $setting->logo;
                Log::debug('CompanyHelper::logoWhite() - Using path: ' . $path);
                
                // Check if it's a stored file in storage/app/public
                if (self::isStoragePath($path)) {
                    if (function_exists('request') && request() && request()->getHttpHost()) {
                        return '/branding/' . ltrim($path, '/');
                    }

                    try {
                        return route('branding.asset', ['path' => $path]);
                    } catch (\Exception $e) {
                        $url = Storage::url($path);
                        Log::debug('CompanyHelper::logoWhite() - Storage URL fallback: ' . $url);
                        return $url;
                    }
                }
                // If the path looks like a full URL or an absolute path, return as-is; otherwise return an absolute asset URL
                if (preg_match('/^(https?:)?\\/\\//', $path) || strpos($path, '/') === 0) {
                    return $path;
                }
                return asset($path);
            }
        } catch (\Exception $e) {
            Log::error('CompanyHelper::logoWhite() - Error: ' . $e->getMessage());
        }
        
        Log::debug('CompanyHelper::logoWhite() - Using fallback: buzbox/img/logo-w.png');
        return asset('buzbox/img/logo-w.png');
    }

    /**
     * Get company favicon from settings or fallback
     * Returns full URL-ready path
     */
    public static function favicon()
    {
        try {
            $setting = CompanySetting::first();
            Log::debug('CompanyHelper::favicon() - Setting found: ' . ($setting ? 'yes' : 'no'));
            
            if ($setting) {
                Log::debug('CompanyHelper::favicon() - favicon value: ' . ($setting->favicon ?? 'null'));
            }
            
            if ($setting && $setting->favicon) {
                $path = $setting->favicon;
                Log::debug('CompanyHelper::favicon() - Using path: ' . $path);
                
                // Check if it's a stored file in storage/app/public
                if (self::isStoragePath($path)) {
                    if (function_exists('request') && request() && request()->getHttpHost()) {
                        return '/branding/' . ltrim($path, '/');
                    }

                    try {
                        return route('branding.asset', ['path' => $path]);
                    } catch (\Exception $e) {
                        $url = Storage::url($path);
                        Log::debug('CompanyHelper::favicon() - Storage URL fallback: ' . $url);
                        return $url;
                    }
                }
                // If the path looks like a full URL or an absolute path, return as-is; otherwise return an absolute asset URL
                if (preg_match('/^(https?:)?\\/\\//', $path) || strpos($path, '/') === 0) {
                    return $path;
                }
                return asset($path);
            }
        } catch (\Exception $e) {
            Log::error('CompanyHelper::favicon() - Error: ' . $e->getMessage());
        }
        
        Log::debug('CompanyHelper::favicon() - Using fallback: buzbox/img/favicon.ico');
        return asset('buzbox/img/favicon.ico');
    }

    /**
     * Check if path is likely from storage
     */
    private static function isStoragePath($path)
    {
        if (empty($path)) {
            return false;
        }
        
        // Check if path starts with storage indicators
        return strpos($path, 'storage/') === 0 || 
               strpos($path, '/storage/') !== false || 
               strpos($path, 'uploads/') === 0 ||
               strpos($path, 'assets/') === 0;  // Add this to catch assets/branding paths
    }

    /**
     * Format price with Naira currency symbol
     */
    public static function formatCurrency($amount, $decimals = 2)
    {
        return '₦' . number_format($amount, $decimals);
    }

    /**
     * Get currency symbol
     */
    public static function currencySymbol()
    {
        return '₦';
    }

    /**
     * Get currency code
     */
    public static function currencyCode()
    {
        return 'NGN';
    }
}

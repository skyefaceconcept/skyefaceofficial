<?php

use App\Helpers\CompanyHelper;

if (!function_exists('formatCurrency')) {
    /**
     * Format amount as Naira currency
     */
    function formatCurrency($amount, $decimals = 2)
    {
        return CompanyHelper::formatCurrency($amount, $decimals);
    }
}

if (!function_exists('naira')) {
    /**
     * Shorthand for formatting currency as Naira
     */
    function naira($amount, $decimals = 2)
    {
        return CompanyHelper::formatCurrency($amount, $decimals);
    }
}

if (!function_exists('currencySymbol')) {
    /**
     * Get currency symbol
     */
    function currencySymbol()
    {
        return CompanyHelper::currencySymbol();
    }
}

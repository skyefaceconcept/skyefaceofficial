<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InvoiceNumberService
{
    /**
     * Generate a unique professional invoice number
     * Format: INV-YYYY-MM-XXXXXX
     * Example: INV-2026-01-000001
     */
    public static function generate(): string
    {
        return DB::transaction(function () {
            $year = now()->year;
            $month = now()->month;
            $monthPadded = str_pad($month, 2, '0', STR_PAD_LEFT);
            
            // Count orders created this month using a lock for consistency
            $count = Order::whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->lockForUpdate()
                ->count();
            
            // Next sequence number (1-indexed for user-friendly numbering)
            $sequence = str_pad($count + 1, 6, '0', STR_PAD_LEFT);
            $invoiceNumber = "INV-{$year}-{$monthPadded}-{$sequence}";
            
            // Final safety check for uniqueness
            while (Order::where('invoice_number', $invoiceNumber)->exists()) {
                $count++;
                $sequence = str_pad($count + 1, 6, '0', STR_PAD_LEFT);
                $invoiceNumber = "INV-{$year}-{$monthPadded}-{$sequence}";
            }
            
            return $invoiceNumber;
        });
    }

    /**
     * Get the next invoice number without generating it
     */
    public static function getNext(): string
    {
        $year = now()->year;
        $month = now()->month;
        $monthPadded = str_pad($month, 2, '0', STR_PAD_LEFT);
        
        $count = Order::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->count();
        
        $sequence = str_pad($count + 1, 6, '0', STR_PAD_LEFT);
        
        return "INV-{$year}-{$monthPadded}-{$sequence}";
    }

    /**
     * Format invoice number for display with company prefix
     */
    public static function format(string $invoiceNumber): string
    {
        $companyName = config('app.name', 'SKYEFACE');
        return "{$companyName}-{$invoiceNumber}";
    }
}

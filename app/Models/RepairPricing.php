<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RepairPricing extends Model
{
    use HasFactory;

    protected $table = 'repair_pricing';

    protected $fillable = [
        'device_type',
        'price',
        'description',
    ];

    protected $casts = [
        'price' => 'float',
    ];

    /**
     * Get all pricing as key-value pairs for easy access
     */
    public static function getPricingMap()
    {
        $pricing = self::all();
        $map = [];
        
        foreach ($pricing as $item) {
            $map[$item->device_type] = $item->price;
        }
        
        return $map;
    }

    /**
     * Get all pricing formatted for frontend
     */
    public static function getPricingFormatted()
    {
        return self::all()->map(function ($item) {
            return [
                'device_type' => $item->device_type,
                'price' => (float) $item->price,
                'price_formatted' => '₦' . number_format($item->price, 2),
                'description' => $item->description,
            ];
        })->toArray();
    }
}

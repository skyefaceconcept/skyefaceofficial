<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'detailed_description',
        'price',
        'price_6months',
        'price_1year',
        'price_2years',
        'category',
        'slug',
        'thumbnail',
        'view_count',
        'status',
        'festive_discount_enabled',
        'festive_discount_percentage',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'price_6months' => 'decimal:2',
        'price_1year' => 'decimal:2',
        'price_2years' => 'decimal:2',
        'festive_discount_enabled' => 'boolean',
        'festive_discount_percentage' => 'decimal:2',
    ];

    // Status constants
    public const STATUS_DRAFT = 'draft';
    public const STATUS_PUBLISHED = 'published';
    public const STATUS_ARCHIVED = 'archived';

    /**
     * Get all footages (photos/videos) for this portfolio
     */
    public function footages()
    {
        return $this->hasMany(PortfolioFootage::class)->orderBy('display_order');
    }

    /**
     * Get all orders for this portfolio
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get completed orders count
     */
    public function completedOrdersCount()
    {
        return $this->orders()->where('status', 'completed')->count();
    }

    /**
     * Get total revenue from this portfolio
     */
    public function totalRevenue()
    {
        return $this->orders()
            ->where('status', 'completed')
            ->sum('amount');
    }

    /**
     * Scope to get only published portfolios
     */
    public function scopePublished($query)
    {
        return $query->where('status', self::STATUS_PUBLISHED);
    }

    /**
     * Scope to filter by category
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Boot method for model events
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-generate slug from title
        static::creating(function ($model) {
            if (!$model->slug) {
                $model->slug = \Illuminate\Support\Str::slug($model->title);
            }
        });
    }

    /**
     * Get the route key for model binding
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}

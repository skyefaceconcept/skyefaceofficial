<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PageImpression extends Model
{
    use HasFactory;

    protected $fillable = [
        'route_name',
        'page_url',
        'page_title',
        'ip_address',
        'user_agent',
        'referrer',
        'user_id',
        'device_type',
        'browser',
        'os',
        'time_spent_seconds',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user associated with the impression.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to get impressions for a specific page
     */
    public function scopeForPage($query, $pageRoute)
    {
        return $query->where('route_name', $pageRoute);
    }

    /**
     * Scope to get impressions for a date range
     */
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Scope to get impressions for today
     */
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    /**
     * Scope to get impressions for this week
     */
    public function scopeThisWeek($query)
    {
        return $query->whereBetween('created_at', [
            now()->startOfWeek(),
            now()->endOfWeek(),
        ]);
    }

    /**
     * Scope to get impressions for this month
     */
    public function scopeThisMonth($query)
    {
        return $query->whereYear('created_at', now()->year)
                     ->whereMonth('created_at', now()->month);
    }

    /**
     * Get unique visitor count for a page
     */
    public static function getUniqueVisitors($pageRoute = null, $startDate = null, $endDate = null)
    {
        $query = self::query();

        if ($pageRoute) {
            $query->where('route_name', $pageRoute);
        }

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        return $query->distinct('ip_address')->count('ip_address');
    }

    /**
     * Get impression statistics by date
     */
    public static function getStatsByDate($pageRoute = null)
    {
        $query = self::query();

        if ($pageRoute) {
            $query->where('route_name', $pageRoute);
        }

        return $query->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                     ->groupBy('date')
                     ->orderBy('date', 'desc')
                     ->get();
    }

    /**
     * Get page statistics by route
     */
    public static function getPageStats($startDate = null, $endDate = null)
    {
        $query = self::query();

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        return $query->groupBy('page_url', 'route_name', 'page_title')
            ->selectRaw('page_url, route_name, page_title, COUNT(*) as impressions, COUNT(DISTINCT ip_address) as unique_visitors')
            ->orderByDesc('impressions')
            ->get();
    }

    /**
     * Get traffic by device type
     */
    public static function getTrafficByDevice($startDate = null, $endDate = null)
    {
        $query = self::query();

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        return $query->groupBy('device_type')
            ->selectRaw('device_type, COUNT(*) as total')
            ->get();
    }

    /**
     * Get daily impressions for chart
     */
    public static function getDailyImpressions($days = 30)
    {
        $startDate = now()->subDays($days);

        return self::whereBetween('created_at', [$startDate, now()])
            ->groupByRaw('DATE(created_at)')
            ->selectRaw('DATE(created_at) as date, COUNT(*) as impressions, COUNT(DISTINCT ip_address) as unique_visitors')
            ->orderBy('date')
            ->get();
    }

    /**
     * Get top pages
     */
    public static function getTopPages($limit = 10, $startDate = null, $endDate = null)
    {
        $query = self::query();

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        return $query->select('page_url', 'page_title', 'route_name')
            ->groupBy('page_url', 'page_title', 'route_name')
            ->selectRaw('page_url, page_title, route_name, COUNT(*) as impressions, COUNT(DISTINCT ip_address) as unique_visitors')
            ->orderByDesc('impressions')
            ->limit($limit)
            ->get();
    }

    /**
     * Get referrer statistics
     */
    public static function getReferrerStats($startDate = null, $endDate = null)
    {
        $query = self::query();

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        return $query->whereNotNull('referrer')
            ->groupBy('referrer')
            ->selectRaw('referrer, COUNT(*) as total')
            ->orderByDesc('total')
            ->limit(20)
            ->get();
    }
}

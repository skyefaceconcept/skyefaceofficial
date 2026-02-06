<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageImpression;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PageImpressionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show analytics dashboard
     */
    public function index(Request $request)
    {
        $days = $request->query('days', 30);
        $startDate = Carbon::now()->subDays($days)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        $totalImpressions = PageImpression::whereBetween('created_at', [$startDate, $endDate])->count();
        $uniqueVisitors = PageImpression::whereBetween('created_at', [$startDate, $endDate])->distinct('ip_address')->count('ip_address');
        $avgSessionTime = PageImpression::whereBetween('created_at', [$startDate, $endDate])->avg('time_spent_seconds') ?? 0;

        $topPages = PageImpression::whereBetween('created_at', [$startDate, $endDate])
            ->select('page_url', 'page_title', 'route_name', DB::raw('COUNT(*) as impressions'), DB::raw('COUNT(DISTINCT ip_address) as unique_visitors'))
            ->groupBy('page_url', 'page_title', 'route_name')
            ->orderByDesc('impressions')
            ->limit(10)
            ->get();

        $dailyImpressions = PageImpression::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as impressions, COUNT(DISTINCT ip_address) as unique_visitors')
            ->groupByRaw('DATE(created_at)')
            ->orderBy('date')
            ->get();

        $deviceStats = PageImpression::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('device_type, COUNT(*) as total')
            ->groupBy('device_type')
            ->get();

        // Top referrers
        $referrerStats = PageImpression::whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('referrer')
            ->selectRaw('referrer, COUNT(*) as total')
            ->groupBy('referrer')
            ->orderByDesc('total')
            ->limit(15)
            ->get();

        return view('admin.analytics.page-impressions.index', [
            'totalImpressions' => $totalImpressions,
            'uniqueVisitors' => $uniqueVisitors,
            'avgSessionTime' => round($avgSessionTime, 2),
            'topPages' => $topPages,
            'dailyImpressions' => $dailyImpressions,
            'deviceStats' => $deviceStats,
            'referrerStats' => $referrerStats,
            'days' => $days,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }

    /**
     * Show page statistics
     */
    public function statistics()
    {
        $days = request('days', 30);
        $startDate = Carbon::now()->subDays($days)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        $totalImpressions = PageImpression::whereBetween('created_at', [$startDate, $endDate])->count();
        $uniqueVisitors = PageImpression::whereBetween('created_at', [$startDate, $endDate])->distinct('ip_address')->count('ip_address');
        $avgTime = PageImpression::whereBetween('created_at', [$startDate, $endDate])->avg('time_spent_seconds') ?? 0;

        return view('admin.analytics.page-impressions.statistics', [
            'totalImpressions' => $totalImpressions,
            'uniqueVisitors' => $uniqueVisitors,
            'avgTime' => round($avgTime, 2),
            'days' => $days,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }

    /**
     * Show specific page details
     */
    public function show($pageUrl = null)
    {
        $pageUrl = request('page', $pageUrl);
        $days = request('days', 30);
        $startDate = Carbon::now()->subDays($days)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        $pageImpressions = PageImpression::where('page_url', $pageUrl)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderByDesc('created_at')
            ->paginate(50);

        // Aggregate page-level stats for header cards
        $pageStats = PageImpression::where('page_url', $pageUrl)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select('page_title', 'route_name', DB::raw('COUNT(*) as total'), DB::raw('COUNT(DISTINCT ip_address) as unique_visitors'))
            ->groupBy('page_title', 'route_name')
            ->first();

        if (! $pageStats) {
            $pageStats = (object) [
                'page_title' => null,
                'route_name' => null,
                'total' => 0,
                'unique_visitors' => 0,
            ];
        }

        // Daily breakdown for chart
        $dailyStats = PageImpression::where('page_url', $pageUrl)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as impressions, COUNT(DISTINCT ip_address) as visitors')
            ->groupByRaw('DATE(created_at)')
            ->orderBy('date')
            ->get();

        $pageRoute = $pageStats->route_name ?? $pageUrl;

        return view('admin.analytics.page-impressions.show', [
            'pageUrl' => $pageUrl,
            'pageRoute' => $pageRoute,
            'pageStats' => $pageStats,
            'dailyStats' => $dailyStats,
            'pageImpressions' => $pageImpressions,
            'days' => $days,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }

    /**
     * Device analytics
     */
    public function deviceAnalytics()
    {
        $days = request('days', 30);
        $startDate = Carbon::now()->subDays($days)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        $deviceStats = PageImpression::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('device_type, COUNT(*) as impressions, COUNT(DISTINCT ip_address) as unique_visitors')
            ->groupBy('device_type')
            ->orderByDesc('impressions')
            ->get();

        return view('admin.analytics.page-impressions.devices', [
            'deviceStats' => $deviceStats,
            'days' => $days,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }

    /**
     * Browser analytics
     */
    public function browserAnalytics()
    {
        $days = request('days', 30);
        $startDate = Carbon::now()->subDays($days)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        $browserStats = PageImpression::whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('browser')
            ->selectRaw('browser, COUNT(*) as impressions, COUNT(DISTINCT ip_address) as unique_visitors')
            ->groupBy('browser')
            ->orderByDesc('impressions')
            ->limit(15)
            ->get();

        return view('admin.analytics.page-impressions.browsers', [
            'browserStats' => $browserStats,
            'days' => $days,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }

    /**
     * OS analytics
     */
    public function osAnalytics()
    {
        $days = request('days', 30);
        $startDate = Carbon::now()->subDays($days)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        $osStats = PageImpression::whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('os')
            ->selectRaw('os, COUNT(*) as impressions, COUNT(DISTINCT ip_address) as unique_visitors')
            ->groupBy('os')
            ->orderByDesc('impressions')
            ->limit(15)
            ->get();

        return view('admin.analytics.page-impressions.os', [
            'osStats' => $osStats,
            'days' => $days,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }

    /**
     * Visitor analytics
     */
    public function visitorAnalytics()
    {
        $days = request('days', 30);
        $startDate = Carbon::now()->subDays($days)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        $topVisitors = PageImpression::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('ip_address, device_type, COUNT(*) as impressions, MAX(created_at) as last_seen')
            ->groupBy('ip_address', 'device_type')
            ->orderByDesc('impressions')
            ->limit(50)
            ->get();

        return view('admin.analytics.page-impressions.visitors', [
            'topVisitors' => $topVisitors,
            'days' => $days,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }

    /**
     * Export analytics data
     */
    public function export(Request $request)
    {
        $days = $request->query('days', 30);
        $startDate = Carbon::now()->subDays($days)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        $impressions = PageImpression::whereBetween('created_at', [$startDate, $endDate])
            ->orderByDesc('created_at')
            ->get();

        $filename = 'page-impressions-' . date('Y-m-d-H-i-s') . '.csv';
        $headers = ["Content-type" => "text/csv", "Content-Disposition" => "attachment; filename=$filename"];

        $callback = function() use ($impressions) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Date', 'Page URL', 'Page Title', 'Route', 'IP Address', 'Device Type', 'Browser', 'OS', 'Referrer', 'User ID']);

            foreach ($impressions as $impression) {
                fputcsv($file, [
                    $impression->created_at->format('Y-m-d H:i:s'),
                    $impression->page_url,
                    $impression->page_title,
                    $impression->route_name,
                    $impression->ip_address,
                    $impression->device_type,
                    $impression->browser,
                    $impression->os,
                    $impression->referrer,
                    $impression->user_id,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get chart data
     */
    public function getChartData(Request $request)
    {
        $days = $request->query('days', 30);
        $startDate = Carbon::now()->subDays($days)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        $dailyData = PageImpression::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as impressions, COUNT(DISTINCT ip_address) as unique_visitors')
            ->groupByRaw('DATE(created_at)')
            ->orderBy('date')
            ->get();

        return response()->json([
            'labels' => $dailyData->pluck('date'),
            'datasets' => [
                ['label' => 'Impressions', 'data' => $dailyData->pluck('impressions'), 'borderColor' => '#3182ce'],
                ['label' => 'Visitors', 'data' => $dailyData->pluck('unique_visitors'), 'borderColor' => '#38a169']
            ]
        ]);
    }

    /**
     * Cleanup old data
     */
    public function cleanup(Request $request)
    {
        $days = $request->input('days', 90);
        $deleted = PageImpression::where('created_at', '<', Carbon::now()->subDays($days))->delete();
        return back()->with('success', "Deleted $deleted old records.");
    }
}

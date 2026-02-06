<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageImpression;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    /**
     * Display the page impressions dashboard
     */
    public function index(Request $request)
    {
        // Get date range from request or default to last 30 days
        $startDate = $request->get('start_date')
            ? Carbon::createFromFormat('Y-m-d', $request->get('start_date'))->startOfDay()
            : now()->subDays(30)->startOfDay();

        $endDate = $request->get('end_date')
            ? Carbon::createFromFormat('Y-m-d', $request->get('end_date'))->endOfDay()
            : now()->endOfDay();

        // Overall statistics
        $totalImpressions = PageImpression::whereBetween('created_at', [$startDate, $endDate])->count();
        $uniqueVisitors = PageImpression::whereBetween('created_at', [$startDate, $endDate])
            ->distinct('ip_address')
            ->count('ip_address');
        $uniquePages = PageImpression::whereBetween('created_at', [$startDate, $endDate])
            ->distinct('route_name')
            ->count('route_name');

        // Get impressions by page
        $impressionsByPage = PageImpression::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('route_name, page_title, COUNT(*) as total_impressions')
            ->selectRaw('COUNT(DISTINCT ip_address) as unique_visitors')
            ->groupBy('route_name', 'page_title')
            ->orderByDesc('total_impressions')
            ->get();

        // Get impressions by date (for chart)
        $impressionsByDate = PageImpression::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as impressions, COUNT(DISTINCT ip_address) as visitors')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // Get top referrers
        $topReferrers = PageImpression::whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('referrer')
            ->selectRaw('referrer, COUNT(*) as count')
            ->groupBy('referrer')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        // Get hourly distribution for today
        $hourlyDistribution = PageImpression::whereDate('created_at', today())
            ->selectRaw('HOUR(created_at) as hour, COUNT(*) as count')
            ->groupBy('hour')
            ->orderBy('hour', 'asc')
            ->get()
            ->keyBy('hour');

        // Format hourly data for all 24 hours
        $hourlyData = collect();
        for ($hour = 0; $hour < 24; $hour++) {
            $hourlyData->push([
                'hour' => str_pad($hour, 2, '0', STR_PAD_LEFT) . ':00',
                'count' => $hourlyDistribution->get($hour)?->count ?? 0,
            ]);
        }

        return view('admin.analytics.index', [
            'totalImpressions' => $totalImpressions,
            'uniqueVisitors' => $uniqueVisitors,
            'uniquePages' => $uniquePages,
            'impressionsByPage' => $impressionsByPage,
            'impressionsByDate' => $impressionsByDate,
            'topReferrers' => $topReferrers,
            'hourlyData' => $hourlyData,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }

    /**
     * Show details for a specific page
     */
    public function showPage(Request $request, $pageRoute)
    {
        $startDate = $request->get('start_date')
            ? Carbon::createFromFormat('Y-m-d', $request->get('start_date'))->startOfDay()
            : now()->subDays(30)->startOfDay();

        $endDate = $request->get('end_date')
            ? Carbon::createFromFormat('Y-m-d', $request->get('end_date'))->endOfDay()
            : now()->endOfDay();

        $pageImpressions = PageImpression::where('route_name', $pageRoute)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderByDesc('created_at')
            ->paginate(100);

        $pageStats = PageImpression::where('route_name', $pageRoute)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('page_title, COUNT(*) as total, COUNT(DISTINCT ip_address) as unique_visitors')
            ->groupBy('page_title')
            ->first();

        $dailyStats = PageImpression::where('route_name', $pageRoute)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as impressions, COUNT(DISTINCT ip_address) as visitors')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        return view('admin.analytics.page-detail', [
            'pageRoute' => $pageRoute,
            'pageImpressions' => $pageImpressions,
            'pageStats' => $pageStats,
            'dailyStats' => $dailyStats,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }

    /**
     * Export impressions data
     */
    public function export(Request $request)
    {
        $startDate = $request->get('start_date')
            ? Carbon::createFromFormat('Y-m-d', $request->get('start_date'))->startOfDay()
            : now()->subDays(30)->startOfDay();

        $endDate = $request->get('end_date')
            ? Carbon::createFromFormat('Y-m-d', $request->get('end_date'))->endOfDay()
            : now()->endOfDay();

        $impressions = PageImpression::whereBetween('created_at', [$startDate, $endDate])
            ->orderByDesc('created_at')
            ->get();

        $filename = 'page-impressions-' . $startDate->format('Y-m-d') . '-to-' . $endDate->format('Y-m-d') . '.csv';

        $headers = [
            "Content-type" => "text/csv;charset=UTF-8",
            "Content-Disposition" => "attachment;filename=" . $filename,
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0",
        ];

        $columns = ['Date', 'Time', 'Page Route', 'Page Title', 'IP Address', 'Referrer', 'User'];

        $callback = function () use ($impressions, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($impressions as $impression) {
                fputcsv($file, [
                    $impression->created_at->format('Y-m-d'),
                    $impression->created_at->format('H:i:s'),
                    $impression->route_name,
                    $impression->page_title,
                    $impression->ip_address,
                    $impression->referrer,
                    $impression->user?->name ?? 'Guest',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Clear old impressions data
     */
    public function clearOldData(Request $request)
    {
        $days = $request->input('days', 90);
        $deleted = PageImpression::where('created_at', '<', now()->subDays($days))->delete();

        return back()->with('success', "Deleted $deleted records older than $days days.");
    }
}

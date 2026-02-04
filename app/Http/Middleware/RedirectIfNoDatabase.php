<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RedirectIfNoDatabase
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Allow installer routes and public assets to be fetched without DB
        $path = $request->path();
        $whitelist = [
            'install',
            'install/*',
            'api/*',
            'build/*',
            'css/*',
            'js/*',
            'favicon.ico',
            'robots.txt',
            'sitemap.xml',
            'storage/*'
        ];

        // If the request is whitelisted, allow it through without DB checks


        foreach ($whitelist as $p) {
            $p = rtrim($p, '/');
            if ($p === $path || str_starts_with($path, rtrim($p, '*'))) {
                \Log::debug('RedirectIfNoDatabase: whitelist matched, allowing request', ['path' => $path, 'whitelist_item' => $p]);
                return $next($request);
            }
        }

        // Try a lightweight DB connection check; if it fails, return JSON 503 for API clients or redirect to installer for web
        try {
            DB::connection()->getPdo();
            \Log::debug('RedirectIfNoDatabase: DB connection OK', ['path' => $path]);
            return $next($request);
        } catch (\Throwable $e) {
            \Log::warning('RedirectIfNoDatabase: DB connection failed', ['path' => $path, 'error' => $e->getMessage()]);
            // API/JSON clients should get a 503 so AJAX callers can handle it gracefully
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Application not installed or database unavailable.'], 503);
            }
            return redirect()->route('install.show');
        }
    }
}

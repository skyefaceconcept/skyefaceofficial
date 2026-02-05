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

        // For API/JSON clients, return a JSON service unavailable message
        if ($request->expectsJson()) {
            \Log::debug('RedirectIfNoDatabase: expectsJson -> returning 503', ['path' => $path]);
            return response()->json(['message' => 'Application not installed or database unavailable.'], 503);
        }

        // Try a lightweight DB connection check; if it fails, redirect to installer
        try {
            DB::connection()->getPdo();
            \Log::debug('RedirectIfNoDatabase: DB connection OK', ['path' => $path]);
            return $next($request);
        } catch (\Throwable $e) {
            \Log::warning('RedirectIfNoDatabase: DB connection failed, redirecting to installer', ['path' => $path, 'error' => $e->getMessage()]);
            return redirect()->route('install.show');
        }
    }
}

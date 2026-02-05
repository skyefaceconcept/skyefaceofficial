<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class InjectSeoDescription
{
    public function handle(Request $request, Closure $next)
    {
        // Resolve description and share with all views
        try {
            $description = app(\App\Services\SeoService::class)->getDescriptionForRequest($request);
            view()->share('pageDescription', $description);
        } catch (\Throwable $e) {
            // Fail-safe: share empty string so templates don't break
            view()->share('pageDescription', config('app.description', ''));
            \Log::warning('InjectSeoDescription: failed to resolve description', ['error' => $e->getMessage()]);
        }

        return $next($request);
    }
}

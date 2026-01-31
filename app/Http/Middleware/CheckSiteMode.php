<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckSiteMode
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Read site mode from environment (managed via admin settings)
        $mode = env('APP_MODE', 'live');

        // If live, continue
        if ($mode === 'live') {
            return $next($request);
        }

        // Allow admin area and API/webhooks to function when site is in maintenance
        if ($request->is('admin/*') || $request->is('api/*') || $request->is('payment/*')) {
            return $next($request);
        }

        // Allow authenticated super-admins to bypass (if user model has is_admin or role check)
        if (Auth::check()) {
            $user = Auth::user();
            if (property_exists($user, 'is_admin') && $user->is_admin) {
                return $next($request);
            }
            // common projects use role relationship; try basic check if available
            if (method_exists($user, 'hasRole') && $user->hasRole('superadmin')) {
                return $next($request);
            }
        }

        // Choose view based on mode
        if ($mode === 'maintenance') {
            return response()->view('maintenance', [
                'title' => env('SITE_TITLE', config('app.name')),
                'message' => env('SITE_MESSAGE', 'We are performing scheduled maintenance. We\'ll be back shortly.'),
            ], 503);
        }

        // fallback: under construction
        return response()->view('underconstruction', [
            'title' => env('SITE_TITLE', config('app.name')),
            'message' => env('SITE_MESSAGE', 'Our site is currently under construction. Check back soon!'),
        ], 503);
    }
}

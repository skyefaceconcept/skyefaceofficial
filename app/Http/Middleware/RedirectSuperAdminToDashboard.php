<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectSuperAdminToDashboard
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();

            // Determine if user should be considered a SuperAdmin
            $isSuper = false;
            if (method_exists($user, 'isSuperAdmin')) {
                $isSuper = $user->isSuperAdmin();
            } elseif (method_exists($user, 'hasRole')) {
                $isSuper = $user->hasRole('superadmin');
            } elseif ($user->role && isset($user->role->slug)) {
                $isSuper = strcasecmp($user->role->slug, 'superadmin') === 0;
            }

            // If user is SuperAdmin and trying to access the regular dashboard route, redirect to admin dashboard
            if ($isSuper && ($request->routeIs('dashboard') || $request->is('dashboard') || trim($request->path(), '/') === 'dashboard')) {
                return redirect()->route('admin.dashboard');
            }
        }

        return $next($request);
    }
}

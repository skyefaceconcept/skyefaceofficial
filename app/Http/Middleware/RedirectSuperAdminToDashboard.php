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
            
            // If user is SuperAdmin and trying to access regular dashboard, redirect to admin
            if ($user->role && $user->role->slug === 'superadmin' && $request->path() === 'dashboard') {
                return redirect()->route('admin.dashboard');
            }
        }

        return $next($request);
    }
}

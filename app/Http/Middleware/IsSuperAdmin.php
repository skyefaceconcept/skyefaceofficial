<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsSuperAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Check if user has role_id and if that role is SuperAdmin
        if (!$user->role_id) {
            abort(403, 'Unauthorized. Only SuperAdmin can access this area.');
        }

        $role = $user->role;
        
        if (!$role || $role->slug !== 'superadmin') {
            abort(403, 'Unauthorized. Only SuperAdmin can access this area.');
        }

        return $next($request);
    }
}

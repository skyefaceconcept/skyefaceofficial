<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Illuminate\Routing\Router;
use App\Models\Role;
use App\Http\Middleware\IsSuperAdmin;
use App\HTTP\Middleware\RedirectSuperAdminToDashboard;
use App\Http\Middleware\CheckSiteMode;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Auth;
use App\Mail\WelcomeMail;
use App\Mail\VerifyEmail;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(Router $router): void
    {
        // Register mail view namespace
        View::addNamespace('mail', resource_path('views/emails'));

        // Register the SuperAdmin middleware
        $router->aliasMiddleware('is.superadmin', IsSuperAdmin::class);
        $router->aliasMiddleware('redirect.superadmin', RedirectSuperAdminToDashboard::class);

        // Push site mode middleware into the web group so it runs for all web requests
        try {
            $router->pushMiddlewareToGroup('web', CheckSiteMode::class);
        } catch (\Throwable $e) {
            // If the router doesn't support pushMiddlewareToGroup in this environment, ignore silently
        }

        // Assign a default role on user registration (if a role with slug 'user' exists)
        Event::listen(Registered::class, function (Registered $event) {
            $user = $event->user;
            $role = Role::where('slug', 'user')->first();
            if ($role && $user) {
                $user->role_id = $role->id;
                $user->save();
            }

            // Send welcome email
            try {
                if ($user && $user->email) {
                    Log::info('Sending welcome email to: ' . $user->email);
                    Mail::to($user->email)->send(new WelcomeMail($user));
                    Log::info('Welcome email sent successfully to: ' . $user->email);
                }
            } catch (\Throwable $e) {
                Log::error('Failed to send welcome email', ['error' => $e->getMessage()]);
            }

            // Send email verification link
            try {
                if ($user && $user->email) {
                    Log::info('Sending verification email to: ' . $user->email);
                    Mail::to($user->email)->send(new VerifyEmail($user));
                    Log::info('Verification email sent successfully to: ' . $user->email);
                }
            } catch (\Throwable $e) {
                Log::error('Failed to send verification email', ['error' => $e->getMessage()]);
            }
        });

        // Log all sent messages to the mail_logs table
        Event::listen(MessageSent::class, function (MessageSent $event) {
            // Use a listener class for more complex handling (kept inline here for simplicity)
            try {
                Log::debug('MessageSent event received', ['time' => now()]);
                app(\App\Listeners\LogSentMessage::class)->handle($event);
            } catch (\Throwable $e) {
                Log::error('Failed to log email - Listener error: ' . $e->getMessage(), [
                    'exception' => get_class($e),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });

        // Blade conditional for permission checks: @permission('slug') ... @endpermission
        Blade::if('permission', function ($permission) {
            if (! Auth::check()) {
                return false;
            }

            // Accept array or single slug
            $perms = is_array($permission) ? $permission : [$permission];
            return Auth::user()->hasAnyPermission($perms);
        });
    }
}

<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable;
use Laravel\Fortify\Fortify;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class FortifyServiceProvider extends ServiceProvider
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
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        Fortify::redirectUserForTwoFactorAuthenticationUsing(RedirectIfTwoFactorAuthenticatable::class);

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        // Allow login via either username or email from the same input field
        Fortify::authenticateUsing(function (Request $request) {
            $login = $request->input(Fortify::username());
            $password = $request->input('password');

            if (! $login || ! $password) {
                return null;
            }

            $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

            $user = User::where($field, $login)->first();

            if ($user && Hash::check($password, $user->password)) {
                // If the site is in maintenance/under construction, only allow SuperAdmin users to authenticate
                $mode = env('APP_MODE', 'live');

                if ($mode !== 'live') {
                    // Log details to aid troubleshooting (do NOT log passwords)
                    try {
                        \Illuminate\Support\Facades\Log::debug('Auth attempt during non-live mode', [
                            'mode' => $mode,
                            'login' => $login,
                            'user_id' => $user->id ?? null,
                            'user_role_slug' => $user->role->slug ?? null,
                            'is_superadmin_helper' => method_exists($user, 'isSuperAdmin'),
                            'is_superadmin' => method_exists($user, 'isSuperAdmin') ? $user->isSuperAdmin() : (property_exists($user, 'role') && $user->role && $user->role->slug === 'superadmin'),
                        ]);
                    } catch (\Throwable $e) {
                        // swallow logging errors
                    }

                    if (method_exists($user, 'isSuperAdmin')) {
                        if (! $user->isSuperAdmin()) {
                            return null;
                        }
                    } else {
                        // If no helper method exists, attempt a role slug check if available
                        if (property_exists($user, 'role') && $user->role && $user->role->slug !== 'superadmin') {
                            return null;
                        }
                    }
                }

                // Log successful superadmin authentication when site is in maintenance
                try {
                    if ($mode !== 'live') {
                        \Illuminate\Support\Facades\Log::info('SuperAdmin authenticated during maintenance', ['user_id' => $user->id ?? null, 'login' => $login]);
                    }
                } catch (\Throwable $e) {
                    // ignore
                }

                return $user;
            }

            return null;
        });
    }
}

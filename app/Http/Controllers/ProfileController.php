<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProfileController extends Controller
{
    /**
     * Show the user profile view.
     */
    public function show(Request $request): View
    {
        $twoFactorSecret = null;
        $recoveryCodes = [];

        // Generate fresh 2FA secret and recovery codes for the form
        if (!$request->user()->two_factor_secret) {
            $twoFactorSecret = $this->generateTwoFactorSecret();
            $recoveryCodes = $this->generateRecoveryCodes();
        }

        return view('client.profile.show', [
            'user' => $request->user(),
            'twoFactorSecret' => $twoFactorSecret,
            'recoveryCodes' => $recoveryCodes,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function updateProfileInformation(Request $request)
    {
        $request->validate([
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $request->user()->id,
        ]);

        $request->user()->update([
            'fname' => $request->fname,
            'lname' => $request->lname,
            'email' => $request->email,
        ]);

        return Redirect::route('profile.show')->with('status', 'Profile information updated successfully.');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|min:8|confirmed',
        ]);

        $request->user()->update([
            'password' => bcrypt($request->password),
        ]);

        return Redirect::route('profile.show')->with('status', 'Password updated successfully.');
    }

    /**
     * Update user profile (generic update method).
     */
    public function update(Request $request)
    {
        $request->validate([
            'fname' => 'required|string|max:255',
            'mname' => 'nullable|string|max:255',
            'lname' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $request->user()->id,
            'phone' => 'nullable|string|max:20',
            'dob' => 'nullable|date',
            'profile_photo_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [];

        // Handle profile photo upload
        if ($request->hasFile('profile_photo_path')) {
            $file = $request->file('profile_photo_path');
            $path = $file->store('profile-photos', 'public');
            $data['profile_photo_path'] = $path;
        }

        // Handle text field updates
        if ($request->filled('fname')) {
            $data['fname'] = $request->fname;
        }
        if ($request->filled('mname')) {
            $data['mname'] = $request->mname;
        }
        if ($request->filled('lname')) {
            $data['lname'] = $request->lname;
        }
        if ($request->filled('email')) {
            $data['email'] = $request->email;
        }
        if ($request->filled('phone')) {
            $data['phone'] = $request->phone;
        }
        if ($request->filled('dob')) {
            $data['dob'] = $request->dob;
        }

        // Update user if there's data to update
        if (!empty($data)) {
            $request->user()->update($data);
        }

        return Redirect::route('profile.show')->with('status', 'Profile updated successfully.');
    }

    /**
     * Show the admin profile view (simplified for admins).
     */
    public function adminShow(Request $request): View
    {
        $sessions = $this->getActiveSessions($request);
        $twoFactorSecret = $this->generateTwoFactorSecret();
        $recoveryCodes = $this->generateRecoveryCodes();

        return view('admin.profile.show', [
            'user' => $request->user(),
            'sessions' => $sessions,
            'twoFactorSecret' => $twoFactorSecret,
            'recoveryCodes' => $recoveryCodes,
        ]);
    }

    /**
     * Update the admin's profile information.
     */
    public function adminUpdate(Request $request)
    {
        $request->validate([
            'fname' => 'required|string|max:255',
            'mname' => 'nullable|string|max:255',
            'lname' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $request->user()->id,
            'username' => 'nullable|string|max:255|unique:users,username,' . $request->user()->id,
            'phone' => 'nullable|string|max:20',
            'dob' => 'nullable|date',
            'profile_photo_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [];

        // Handle profile photo upload
        if ($request->hasFile('profile_photo_path')) {
            $file = $request->file('profile_photo_path');
            $path = $file->store('profile-photos', 'public');
            $data['profile_photo_path'] = $path;
        }

        // Handle text field updates
        if ($request->filled('fname')) {
            $data['fname'] = $request->fname;
        }
        if ($request->filled('mname')) {
            $data['mname'] = $request->mname;
        }
        if ($request->filled('lname')) {
            $data['lname'] = $request->lname;
        }
        if ($request->filled('email')) {
            $data['email'] = $request->email;
        }
        if ($request->filled('username')) {
            $data['username'] = $request->username;
        }
        if ($request->filled('phone')) {
            $data['phone'] = $request->phone;
        }
        if ($request->filled('dob')) {
            $data['dob'] = $request->dob;
        }

        // Update user if there's data to update
        if (!empty($data)) {
            $request->user()->update($data);
        }

        return Redirect::route('admin.profile.show')->with('status', 'Admin profile updated successfully.');
    }

    /**
     * Enable two-factor authentication.
     */
    public function enableTwoFactor(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
            'secret' => 'required|string',
            'confirm' => 'required|accepted',
        ]);

        $user = $request->user();
        $secret = $request->secret;
        $code = $request->code;

        // Verify the code matches the secret (TOTP verification)
        $verifier = new \OTPHP\TOTP($secret);
        
        if (!$verifier->verify($code)) {
            return back()->withErrors(['code' => 'The verification code is incorrect.']);
        }

        // Store the secret encrypted
        $user->update([
            'two_factor_secret' => encrypt($secret),
        ]);

        // Determine which profile route to redirect to
        $route = $request->user()->hasRole('superadmin') 
            ? 'admin.profile.show' 
            : 'profile.show';

        return Redirect::route($route)->with('status', 'Two-factor authentication enabled successfully.');
    }

    /**
     * Disable two-factor authentication.
     */
    public function disableTwoFactor(Request $request)
    {
        $user = $request->user();

        $user->update([
            'two_factor_secret' => null,
        ]);

        // Determine which profile route to redirect to
        $route = $request->user()->hasRole('superadmin') 
            ? 'admin.profile.show' 
            : 'profile.show';

        return Redirect::route($route)->with('status', 'Two-factor authentication disabled.');
    }

    /**
     * Logout a specific session.
     */
    public function logoutSession(Request $request, $sessionId = null)
    {
        if (config('session.driver') === 'database' && $sessionId) {
            DB::table(config('session.table', 'sessions'))
                ->where('id', $sessionId)
                ->where('user_id', $request->user()->id)
                ->delete();
        }

        // Determine which profile route to redirect to
        $route = $request->user()->hasRole('superadmin') 
            ? 'admin.profile.show' 
            : 'profile.show';
        
        return Redirect::route($route)->with('status', 'Session logged out successfully.');
    }

    /**
     * Logout all other sessions.
     */
    public function logoutOtherSessions(Request $request)
    {
        $currentSessionId = $request->session()->getId();
        
        if (config('session.driver') === 'database') {
            DB::table(config('session.table', 'sessions'))
                ->where('user_id', $request->user()->id)
                ->where('id', '!=', $currentSessionId)
                ->delete();
        }

        // Determine which profile route to redirect to
        $route = $request->user()->hasRole('superadmin') 
            ? 'admin.profile.show' 
            : 'profile.show';
        
        return Redirect::route($route)->with('status', 'All other sessions have been logged out.');
    }

    /**
     * Get active sessions for the current user.
     */
    private function getActiveSessions(Request $request)
    {
        $currentSessionId = $request->session()->getId();
        $sessions = [];

        // Get sessions from database
        if (config('session.driver') === 'database') {
            $dbSessions = DB::table(config('session.table', 'sessions'))
                ->where('user_id', $request->user()->id)
                ->orderBy('last_activity', 'desc')
                ->get();

            foreach ($dbSessions as $session) {
                $payload = json_decode($session->payload, true);
                $userAgent = $payload['user_agent'] ?? '';

                $lastActivityTime = Carbon::createFromTimestamp($session->last_activity);
                $lastActivityDiff = $lastActivityTime->diffForHumans();

                $deviceInfo = $this->parseUserAgent($userAgent);

                $sessions[] = [
                    'id' => $session->id,
                    'device' => $deviceInfo['device'],
                    'browser' => $deviceInfo['browser'],
                    'platform' => $deviceInfo['platform'],
                    'ip_address' => $payload['_ip'] ?? $session->ip_address ?? 'Unknown',
                    'last_activity' => $lastActivityDiff,
                    'is_current' => $session->id === $currentSessionId,
                ];
            }
        } else {
            // Fallback for file-based sessions - get current session info
            $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
            $deviceInfo = $this->parseUserAgent($userAgent);
            
            $sessions[] = [
                'id' => $currentSessionId,
                'device' => $deviceInfo['device'],
                'browser' => $deviceInfo['browser'],
                'platform' => $deviceInfo['platform'],
                'ip_address' => $request->ip(),
                'last_activity' => 'Just now',
                'is_current' => true,
            ];
        }

        return $sessions;
    }

    /**
     * Parse user agent string to extract browser, device, and platform info.
     */
    private function parseUserAgent($userAgent)
    {
        $browser = 'Unknown Browser';
        $platform = 'Unknown OS';
        $device = 'Device';

        // Detect Browser
        if (preg_match('/Chrome\/([0-9.]+)/', $userAgent)) {
            preg_match('/Chrome\/([0-9.]+)/', $userAgent, $matches);
            $browser = 'Chrome ' . $matches[1];
        } elseif (preg_match('/Firefox\/([0-9.]+)/', $userAgent)) {
            preg_match('/Firefox\/([0-9.]+)/', $userAgent, $matches);
            $browser = 'Firefox ' . $matches[1];
        } elseif (preg_match('/Safari\/([0-9.]+)/', $userAgent) && !preg_match('/Chrome/', $userAgent)) {
            preg_match('/Safari\/([0-9.]+)/', $userAgent, $matches);
            $browser = 'Safari ' . $matches[1];
        } elseif (preg_match('/Edge\/([0-9.]+)/', $userAgent)) {
            preg_match('/Edge\/([0-9.]+)/', $userAgent, $matches);
            $browser = 'Edge ' . $matches[1];
        } elseif (preg_match('/MSIE ([0-9.]+)/', $userAgent)) {
            preg_match('/MSIE ([0-9.]+)/', $userAgent, $matches);
            $browser = 'IE ' . $matches[1];
        }

        // Detect Platform/OS
        if (preg_match('/Windows NT 10.0/', $userAgent)) {
            $platform = 'Windows 10';
        } elseif (preg_match('/Windows NT 6.3/', $userAgent)) {
            $platform = 'Windows 8.1';
        } elseif (preg_match('/Windows NT 6.2/', $userAgent)) {
            $platform = 'Windows 8';
        } elseif (preg_match('/Windows NT 6.1/', $userAgent)) {
            $platform = 'Windows 7';
        } elseif (preg_match('/Mac OS X 10_15/', $userAgent)) {
            $platform = 'macOS Catalina';
        } elseif (preg_match('/Mac OS X 10_14/', $userAgent)) {
            $platform = 'macOS Mojave';
        } elseif (preg_match('/Mac OS X/', $userAgent)) {
            $platform = 'macOS';
        } elseif (preg_match('/Linux/', $userAgent)) {
            $platform = 'Linux';
        } elseif (preg_match('/iPhone/', $userAgent)) {
            $platform = 'iOS';
        } elseif (preg_match('/iPad/', $userAgent)) {
            $platform = 'iPadOS';
        } elseif (preg_match('/Android/', $userAgent)) {
            $platform = 'Android';
        }

        // Detect Device Type
        if (preg_match('/Mobile|Android|iPhone/', $userAgent)) {
            $device = 'Mobile Phone';
        } elseif (preg_match('/iPad|Tablet/', $userAgent)) {
            $device = 'Tablet';
        } else {
            $device = 'Desktop';
        }

        return [
            'browser' => $browser,
            'platform' => $platform,
            'device' => $device,
        ];
    }

    /**
     * Generate a TOTP (Time-based One-Time Password) secret.
     * Generates a proper base32 encoded 32-byte secret (256 bits).
     */
    private function generateTwoFactorSecret()
    {
        // Use Laravel's random bytes and encode to base32
        $randomBytes = random_bytes(32);
        $secret = $this->base32Encode($randomBytes);
        return $secret;
    }

    /**
     * Base32 encode bytes (RFC 4648).
     */
    private function base32Encode($data)
    {
        $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        
        $bits = '';
        foreach (str_split($data) as $char) {
            $bits .= str_pad(decbin(ord($char)), 8, '0', STR_PAD_LEFT);
        }
        
        // Pad with zeros if needed
        $bits = str_pad($bits, ceil(strlen($bits) / 5) * 5, '0', STR_PAD_RIGHT);
        
        $encoded = '';
        foreach (str_split($bits, 5) as $chunk) {
            $encoded .= $alphabet[bindec($chunk)];
        }
        
        return $encoded;
    }

    /**
     * Generate OTPAuth URI for QR code.
     */
    private function getOtpAuthUri($user, $secret)
    {
        $appName = config('app.name', 'Skyeface');
        $email = $user->email;
        return "otpauth://totp/{$appName}:{$email}?issuer={$appName}&secret={$secret}";
    }

    /**
     * Generate recovery codes for 2FA backup.
     * Generates 10 recovery codes in the format XXXX-XXXX-XXXX (12 digits each).
     */
    private function generateRecoveryCodes()
    {
        $codes = [];
        for ($i = 0; $i < 10; $i++) {
            // Generate 12 random digits
            $code = '';
            for ($j = 0; $j < 12; $j++) {
                $code .= mt_rand(0, 9);
            }
            // Format as XXXX-XXXX-XXXX
            $codes[] = substr($code, 0, 4) . '-' . substr($code, 4, 4) . '-' . substr($code, 8, 4);
        }
        return $codes;
    }
}
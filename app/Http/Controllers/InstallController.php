<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Symfony\Component\Process\Process;

class InstallController extends Controller
{
    public function show(\Illuminate\Http\Request $request)
    {
        abort(410, 'Installer removed');
    }

    /**
     * Create the database on the host (does NOT attempt to connect to the DB itself).
     */
    public function dbCreate(\Illuminate\Http\Request $request)
    {
        abort(410, 'Installer removed');
    }

    /**
     * Run migrations using currently configured database env values.
     */
    public function dbMigrate(Request $request)
    {
        abort(410, 'Installer removed');
    }

    public function dbTest(\Illuminate\Http\Request $request)
    {
        abort(410, 'Installer removed');
    }

    public function dbMigrateStart(Request $request)
    {
        abort(410, 'Installer removed');
    }

    public function dbMigrateStatus(Request $request)
    {
        abort(410, 'Installer removed');
    }

    /**
     * Return a list of migration files for the installer UI
     */
    public function listMigrations(Request $request)
    {
        abort(410, 'Installer removed');
    }

    public function queueWorkOnce(Request $request)
    {
        abort(410, 'Installer removed');
    }

    public function install(Request $request)
    {
        $data = $request->validate([
            'site_title' => 'nullable|string|max:255',
            'admin_name' => 'required|string|max:255',
            'admin_username' => 'required|string|max:50',
            'admin_email' => 'required|email|max:255',
            'admin_password' => 'required|string|min:8|confirmed',
            // optionally accept DB details if users want to set it here
            'db_connection' => 'nullable|string',
            'db_host' => 'nullable|string',
            'db_port' => 'nullable|string',
            'db_database' => 'nullable|string',
            'db_username' => 'nullable|string',
            'db_password' => 'nullable|string',
        ]);

        // Set site title if provided.
        // NOTE: Don't write site title to .env during install to avoid crashes caused by unescaped input.
        // Apply a sanitized value to runtime config only (admin settings page can persist safely later).
        if (!empty($data['site_title'])) {
            $cleanTitle = trim(preg_replace('/[\r\n]+/', ' ', $data['site_title']));
            $cleanTitle = mb_substr($cleanTitle, 0, 255);
            $cleanTitle = str_replace('"', '"', $cleanTitle); // ensure quotes are not raw
            // Remove control characters
            $cleanTitle = preg_replace('/[\x00-\x1F\x7F]/u', '', $cleanTitle);
            config(['site.title' => $cleanTitle]);
        }

        // If APP_KEY missing, generate
        if (empty(env('APP_KEY'))) {
            Artisan::call('key:generate');
        }

        // Optionally write DB connection settings to .env (if provided)
        if (!empty($data['db_database'])) {
            $this->setEnvValue('DB_CONNECTION', $data['db_connection'] ?? env('DB_CONNECTION', 'mysql'));
            $this->setEnvValue('DB_HOST', $data['db_host'] ?? env('DB_HOST', '127.0.0.1'));
            $this->setEnvValue('DB_PORT', $data['db_port'] ?? env('DB_PORT', '3306'));
            $this->setEnvValue('DB_DATABASE', $data['db_database']);
            $this->setEnvValue('DB_USERNAME', $data['db_username'] ?? 'root');
            $this->setEnvValue('DB_PASSWORD', $data['db_password'] ?? '');
        }

        // Try running migrations (best-effort)
        try {
            Artisan::call('migrate', ['--force' => true]);
        } catch (\Exception $e) {
            try { \Illuminate\Support\Facades\Log::warning('install: migrate failed', ['error' => $e->getMessage()]); } catch (\Exception $__e) {}
        }

        // Create admin user if users table exists; attempt migration again if missing
        try {
            if (! Schema::hasTable('users')) {
                try {
                    Artisan::call('migrate', ['--force' => true]);
                } catch (\Exception $e) {
                    try { \Illuminate\Support\Facades\Log::warning('install: second migrate attempt failed', ['error' => $e->getMessage()]); } catch (\Exception $__e) {}
                }
            }

            if (! Schema::hasTable('users')) {
                return redirect()->back()->withErrors(['admin' => 'Users table not found after attempting migrations. Run migrations and retry.'])->withInput();
            }

            // Avoid creating duplicates by email or username
            $existing = DB::table('users')
                ->where('email', $data['admin_email'])
                ->orWhere('username', $data['admin_username'])
                ->first();

            if (! $existing) {
                // Split full name into fname, mname, lname
                $parts = preg_split('/\s+/', trim($data['admin_name']));
                $fname = array_shift($parts) ?? $data['admin_name'];
                $lname = count($parts) ? array_pop($parts) : '';
                $mname = count($parts) ? implode(' ', $parts) : null;

                // Ensure SuperAdmin role exists and get its id
                $roleId = \App\Models\Role::where('slug', 'superadmin')->value('id');
                if (! $roleId) {
                    try {
                        $role = \App\Models\Role::create(['name' => 'SuperAdmin', 'slug' => 'superadmin', 'description' => 'System Super Administrator']);
                        $roleId = $role->id;
                    } catch (\Exception $__e) {
                        $roleId = null; // proceed without role if creation fails
                    }
                }

                DB::table('users')->insert([
                    'fname' => $fname,
                    'mname' => $mname,
                    'lname' => $lname,
                    'username' => $data['admin_username'],
                    'email' => $data['admin_email'],
                    'password' => Hash::make($data['admin_password']),
                    'role_id' => $roleId,
                    'email_verified_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                session()->flash('status', 'Admin user created successfully.');
            } else {
                session()->flash('status', 'Admin user already exists.');
            }
        } catch (\Exception $e) {
            try { \Illuminate\Support\Facades\Log::error('install: user creation failed', ['error' => $e->getMessage()]); } catch (\Exception $__e) {}
            return redirect()->back()->withErrors(['admin' => 'Failed to create admin user: ' . $e->getMessage()])->withInput();
        }

        // Create installed lock file
        try {
            $path = storage_path('app/installed');
            file_put_contents($path, 'installed_at:' . now());
        } catch (\Exception $e) {
            // ignore
        }

        return redirect('/')->with('status', 'Installation completed. You can now continue.');
    }

    /**
     * Write or update a key in the .env file.
     */
    protected function setEnvValue($key, $value)
    {
        $envPath = base_path('.env');
        if (!file_exists($envPath)) {
            // create a minimal .env file from example
            if (file_exists(base_path('.env.example'))) {
                copy(base_path('.env.example'), $envPath);
            } else {
                file_put_contents($envPath, "APP_ENV=local\n");
            }
        }

        $escaped = preg_quote('=' . env($key, ''), '/');
        $content = file_get_contents($envPath);
        if (strpos($content, $key . '=') !== false) {
            $content = preg_replace('/^' . $key . '=.*/m', $key . '="' . addslashes($value) . '"', $content);
        } else {
            $content .= "\n" . $key . '="' . addslashes($value) . '"';
        }
        file_put_contents($envPath, $content);
    }
}

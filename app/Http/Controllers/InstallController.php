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
        // Serve static installer pages (safe, simple fallback to repo-provided HTML)
        $step = (int) $request->query('step', 1);

        // Prefer Blade view for step 2 and 3 so the migrations list and admin form are always shown to the user
        if ($step === 2 || $step === 3) {
            return response()->view('install')->header('Content-Type', 'text/html');
        }

        $candidates = [
            base_path("install_step{$step}.html"),
            base_path('install_page.html'),
        ];

        $path = null;
        foreach ($candidates as $p) {
            if (file_exists($p)) { $path = $p; break; }
        }

        if (! $path) {
            return response()->view('install')->header('Content-Type', 'text/html');
        }

        $html = file_get_contents($path);
        // Inject a fresh CSRF token and make URLs relative so it works regardless of host
        $html = str_replace('hHYuFuaFU7dOfkNFCe2ZRJuyRjcBWVX138iHXUsk', csrf_token(), $html);
        $html = str_replace('http://127.0.0.1:8000', '', $html);
        return response($html)->header('Content-Type', 'text/html');
    }

    /**
     * Create the database on the host (does NOT attempt to connect to the DB itself).
     */
    public function dbCreate(\Illuminate\Http\Request $request)
    {
        $data = $request->validate([
            'db_host' => 'required|string',
            'db_port' => 'nullable|numeric',
            'db_database' => 'required|string',
            'db_username' => 'nullable|string',
            'db_password' => 'nullable|string',
            'persist' => 'nullable',
        ]);

        $host = $data['db_host'];
        $port = $data['db_port'] ?: 3306;
        $db = $data['db_database'];
        $user = $data['db_username'] ?: 'root';
        $pass = $data['db_password'] ?: '';

        try {
            $dsn = "mysql:host={$host};port={$port};charset=utf8mb4";
            $pdo = new \PDO($dsn, $user, $pass, [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$db}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

            if ($request->input('persist')) {
                $this->setEnvValue('DB_CONNECTION', env('DB_CONNECTION', 'mysql'));
                $this->setEnvValue('DB_HOST', $host);
                $this->setEnvValue('DB_PORT', $port);
                $this->setEnvValue('DB_DATABASE', $db);
                $this->setEnvValue('DB_USERNAME', $user);
                $this->setEnvValue('DB_PASSWORD', $pass);
            }

            return response()->json(['success' => true, 'message' => 'Database created or already exists.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error creating database: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Run migrations using currently configured database env values.
     */
    public function dbMigrate(Request $request)
    {
        try {
            Artisan::call('migrate', ['--force' => true]);
            $output = Artisan::output();
            return response()->json(['success' => true, 'message' => 'Migrations run successfully.', 'output' => $output]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Migrate failed: ' . $e->getMessage()], 500);
        }
    }

    public function dbTest(\Illuminate\Http\Request $request)
    {
        $data = $request->validate([
            'db_host' => 'required|string',
            'db_port' => 'nullable|numeric',
            'db_database' => 'required|string',
            'db_username' => 'nullable|string',
            'db_password' => 'nullable|string',
        ]);

        $host = $data['db_host'];
        $port = $data['db_port'] ?: 3306;
        $db = $data['db_database'];
        $user = $data['db_username'] ?: 'root';
        $pass = $data['db_password'] ?: '';

        try {
            $dsn = "mysql:host={$host};port={$port};dbname={$db};charset=utf8mb4";
            $pdo = new \PDO($dsn, $user, $pass, [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);
            return response()->json(['success' => true, 'message' => 'Connection successful.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Connection failed: ' . $e->getMessage()], 422);
        }
    }

    public function dbMigrateStart(Request $request)
    {
        try {
            $proc = new Process(['php', base_path('artisan'), 'install:migrate-start']);
            $proc->setTimeout(0);
            $proc->start();
            return response()->json(['success' => true, 'message' => 'Background migration started.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to start migration: ' . $e->getMessage()], 500);
        }
    }

    public function dbMigrateStatus(Request $request)
    {
        $statusPath = storage_path('app/install_migrate_status.json');
        if (! file_exists($statusPath)) {
            return response()->json(['status' => 'none']);
        }
        try {
            $raw = file_get_contents($statusPath);
            $json = json_decode($raw, true);
            return response()->json($json);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Return a list of migration files for the installer UI
     */
    public function listMigrations(Request $request)
    {
        $files = glob(database_path('migrations') . DIRECTORY_SEPARATOR . '*.php');
        natsort($files);
        $m = [];
        foreach ($files as $f) {
            $m[] = basename($f);
        }
        return response()->json(['migrations' => array_values($m)]);
    }

    /**
     * Run specific migration files by basename (from installer UI).
     * Accepts JSON { files: ["2026_01_01_000000_create_users_table.php", ...] }
     */
    public function dbMigrateFiles(Request $request)
    {
        \Log::info('InstallController::dbMigrateFiles called', ['path' => $request->path(), 'headers' => $request->headers->all(), 'body' => $request->all()]);
        // Validate manually so API callers receive JSON errors instead of redirects
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'files' => 'required|array|min:1',
            'files.*' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()->all()], 422);
        }

        $data = $validator->validated();

        $available = glob(database_path('migrations') . DIRECTORY_SEPARATOR . '*.php');
        $availableBasenames = array_map('basename', $available);

        $results = [];
        foreach ($data['files'] as $file) {
            // Security: only allow running migrations that exist in the migrations folder
            if (! in_array($file, $availableBasenames, true)) {
                $results[$file] = ['success' => false, 'message' => 'Migration file not found or not allowed.'];
                continue;
            }

            try {
                // Use artisan migrate --path option (relative path from base)
                $relative = 'database/migrations/' . $file;
                Artisan::call('migrate', ['--path' => $relative, '--force' => true]);
                $out = Artisan::output();
                $results[$file] = ['success' => true, 'output' => $out];
            } catch (\Exception $e) {
                $results[$file] = ['success' => false, 'message' => $e->getMessage()];
            }
        }

        return response()->json(['results' => $results]);
    }

    public function queueWorkOnce(Request $request)
    {
        try {
            $proc = new Process(['php', base_path('artisan'), 'queue:work', '--once']);
            $proc->setTimeout(0);
            $proc->run();
            $out = $proc->getOutput() . $proc->getErrorOutput();
            return response()->json(['success' => true, 'output' => $out]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
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

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
        // Show installer and accept optional step query parameter (1 = DB, 2 = Admin)
        $step = (int) $request->query('step', 1);
        return view('install', ['step' => $step]);
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
            'persist' => 'sometimes|boolean',
        ]);

        $host = $data['db_host'];
        $port = $data['db_port'] ?? env('DB_PORT', 3306);
        $dbname = $data['db_database'];
        $user = $data['db_username'] ?? env('DB_USERNAME', 'root');
        $pass = $data['db_password'] ?? '';

        try {
            $dsn = "mysql:host={$host};port={$port}";
            $pdo = new \PDO($dsn, $user, $pass, [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);

            // Create database if absent
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `" . str_replace('`', '``', $dbname) . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

            // Persist to .env if requested (store host & credentials now)
            if ($request->boolean('persist', true)) {
                $this->setEnvValue('DB_CONNECTION', 'mysql');
                $this->setEnvValue('DB_HOST', $host);
                $this->setEnvValue('DB_PORT', $port);
                $this->setEnvValue('DB_DATABASE', $dbname);
                $this->setEnvValue('DB_USERNAME', $user);
                $this->setEnvValue('DB_PASSWORD', $pass);
            }

            return response()->json(['success' => true, 'message' => 'Database created or already exists']);
        } catch (\PDOException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    /**
     * Run migrations using currently configured database env values.
     */
    public function dbMigrate(Request $request)
    {
        // Ensure runtime DB config is using latest env values
        $connection = config('database.default');
        config([
            "database.connections.{$connection}.host" => env('DB_HOST'),
            "database.connections.{$connection}.port" => env('DB_PORT'),
            "database.connections.{$connection}.database" => env('DB_DATABASE'),
            "database.connections.{$connection}.username" => env('DB_USERNAME'),
            "database.connections.{$connection}.password" => env('DB_PASSWORD'),
        ]);

        // Purge and reconnect so the new config is used
        try {
            // Purge and reconnect the DB connection
            DB::purge($connection);
            DB::reconnect($connection);

            // Clear config cache to be safe
            Artisan::call('config:clear');

            // Run migrations (synchronous fallback)
            Artisan::call('migrate', ['--force' => true]);
            $output = Artisan::output();

            return response()->json(['success' => true, 'message' => 'Migrations ran successfully', 'output' => $output]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage(), 'output' => $e->getTraceAsString() ?? ''], 422);
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
            'persist' => 'sometimes|boolean',
        ]);

        $host = $data['db_host'];
        $port = $data['db_port'] ?? env('DB_PORT', 3306);
        $dbname = $data['db_database'];
        $user = $data['db_username'] ?? env('DB_USERNAME', 'root');
        $pass = $data['db_password'] ?? '';

        try {
            // Log attempt
            try { \Illuminate\Support\Facades\Log::info('dbTest called', ['host'=>$host,'db'=>$dbname,'user'=>$user,'ip'=>$request->ip()]); } catch (\Exception $__e) {}

            $dsn = "mysql:host={$host};port={$port}";
            $pdo = new \PDO($dsn, $user, $pass, [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);

            // Create database if absent
            if (!empty($dbname)) {
                $pdo->exec("CREATE DATABASE IF NOT EXISTS `" . str_replace('`', '``', $dbname) . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            }

            // Persist to .env if requested
            if ($request->boolean('persist', true)) {
                $this->setEnvValue('DB_CONNECTION', 'mysql');
                $this->setEnvValue('DB_HOST', $host);
                $this->setEnvValue('DB_PORT', $port);
                $this->setEnvValue('DB_DATABASE', $dbname);
                $this->setEnvValue('DB_USERNAME', $user);
                $this->setEnvValue('DB_PASSWORD', $pass);
            }

            return response()->json(['success' => true, 'message' => 'Database is reachable and was created/verified']);
        } catch (\PDOException $e) {
            try { \Illuminate\Support\Facades\Log::warning('dbTest failed', ['error'=>$e->getMessage()]); } catch (\Exception $__e) {}
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    public function dbMigrateStart(Request $request)
    {
        // Debug log of incoming request
        try {
            \Illuminate\Support\Facades\Log::info('dbMigrateStart called', ['ip' => $request->ip(), 'env_db' => env('DB_DATABASE')]);
        } catch (\Exception $__e) {
            // ignore logging errors
        }

        // Update runtime DB config as in dbMigrate
        $connection = config('database.default');
        config([
            "database.connections.{$connection}.host" => env('DB_HOST'),
            "database.connections.{$connection}.port" => env('DB_PORT'),
            "database.connections.{$connection}.database" => env('DB_DATABASE'),
            "database.connections.{$connection}.username" => env('DB_USERNAME'),
            "database.connections.{$connection}.password" => env('DB_PASSWORD'),
        ]);

        try {
            DB::purge($connection);
            DB::reconnect($connection);
            Artisan::call('config:clear');
        } catch (\Exception $e) {
            // ignore connectivity errors here — status endpoint will report
            try { \Illuminate\Support\Facades\Log::warning('dbMigrateStart connection reset failed', ['error' => $e->getMessage()]); } catch (\Exception $__) {}
        }

        // Start the migration job via Laravel queue (more reliable cross-platform approach)
        $statusPath = storage_path('app/install_migrate_status.json');
        // Guard: if a migration is already running, refuse to start another
        if (file_exists($statusPath)) {
            $raw = @file_get_contents($statusPath);
            $decoded = json_decode($raw, true) ?: [];
            if (isset($decoded['status']) && $decoded['status'] === 'running') {
                try { \Illuminate\Support\Facades\Log::warning('dbMigrateStart refused: already running'); } catch (\Exception $__) {}
                return response()->json(['success' => false, 'message' => 'Migration already in progress'], 409);
            }
        }

        // Write initial running status with list of migrations
        $files = glob(database_path('migrations') . DIRECTORY_SEPARATOR . '*.php');
        natsort($files);
        $migs = [];
        foreach ($files as $f) {
            $migs[] = ['name' => basename($f), 'status' => 'pending'];
        }

        $initial = ['status' => 'running', 'started_at' => now()->toDateTimeString(), 'migrations' => $migs];
        @file_put_contents($statusPath, json_encode($initial, JSON_PRETTY_PRINT));
        @file_put_contents(storage_path('logs/install-migrate.log'), "[".now()->toDateTimeString()."] Queued migrations\n", FILE_APPEND);

        try {
            \App\Jobs\RunMigrationsJob::dispatch();
            try { \Illuminate\Support\Facades\Log::info('dbMigrateStart dispatched RunMigrationsJob'); } catch (\Exception $__) {}
            return response()->json(['success' => true, 'message' => 'Migration started (queued)', 'queue_driver' => config('queue.default')]);
        } catch (\Exception $ex) {
            try { \Illuminate\Support\Facades\Log::error('dbMigrateStart dispatch failed', ['error' => $ex->getMessage()]); } catch (\Exception $__) {}
            return response()->json(['success' => false, 'message' => 'Failed to dispatch migration job: ' . $ex->getMessage()], 500);
        }
    }

    public function dbMigrateStatus(Request $request)
    {
        $statusPath = storage_path('app/install_migrate_status.json');
        $logPath = storage_path('logs/install-migrate.log');
        $status = ['status' => 'unknown'];

        if (file_exists($statusPath)) {
            $raw = @file_get_contents($statusPath);
            if ($raw) {
                $decoded = json_decode($raw, true);
                if (is_array($decoded)) {
                    $status = $decoded;
                }
            }
        }

        // Read tail of log
        $logTail = null;
        if (file_exists($logPath)) {
            $contents = file_get_contents($logPath);
            $logTail = substr($contents, -4000);
        }

        return response()->json(['status' => $status, 'log' => $logTail]);
    }

    /**
     * Return a list of migration files for the installer UI
     */
    public function listMigrations(Request $request)
    {
        try {
            $files = glob(database_path('migrations') . DIRECTORY_SEPARATOR . '*.php');
            natsort($files);
            $migs = [];
            foreach ($files as $f) {
                $migs[] = ['name' => basename($f), 'status' => 'pending'];
            }

            // If there is an existing status file, merge statuses
            $statusPath = storage_path('app/install_migrate_status.json');
            if (file_exists($statusPath)) {
                $raw = @file_get_contents($statusPath);
                $decoded = json_decode($raw, true) ?: [];
                if (isset($decoded['migrations']) && is_array($decoded['migrations'])) {
                    $map = [];
                    foreach ($decoded['migrations'] as $m) { $map[$m['name']] = $m['status'] ?? 'pending'; }
                    foreach ($migs as $idx => $m) {
                        if (isset($map[$m['name']])) $migs[$idx]['status'] = $map[$m['name']];
                    }
                }
            }

            return response()->json(['success' => true, 'migrations' => $migs]);
        } catch (\Exception $e) {
            try { \Illuminate\Support\Facades\Log::error('listMigrations failed', ['error' => $e->getMessage()]); } catch (\Exception $__e) {}
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function queueWorkOnce(Request $request)
    {
        try {
            \Illuminate\Support\Facades\Log::info('queueWorkOnce called', ['ip' => $request->ip()]);
        } catch (\Exception $__e) {}

        try {
            // Increase time limit for the request while the worker runs
            @set_time_limit(300);
            Artisan::call('queue:work', ['--once' => true, '--tries' => 1]);
            $output = Artisan::output();
            try { \Illuminate\Support\Facades\Log::info('queueWorkOnce output', ['output' => substr($output,0,2000)]); } catch (\Exception $__e) {}
            return response()->json(['success' => true, 'message' => 'Worker run once', 'output' => $output]);
        } catch (\Exception $e) {
            try { \Illuminate\Support\Facades\Log::error('queueWorkOnce failed', ['error' => $e->getMessage()]); } catch (\Exception $__e) {}
            return response()->json(['success' => false, 'message' => 'Failed to run worker: ' . $e->getMessage()], 500);
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

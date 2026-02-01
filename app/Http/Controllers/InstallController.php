<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

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

            // Run migrations
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
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    public function install(Request $request)
    {
        $data = $request->validate([
            'site_title' => 'nullable|string|max:255',
            'admin_name' => 'required|string|max:255',
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

        // Set site title if provided
        if (!empty($data['site_title'])) {
            $this->setEnvValue('SITE_TITLE', $data['site_title']);
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
            // ignore migration errors for now; we still want to mark installed so developer can fix DB
        }

        // Create admin user if users table exists
        try {
            if (Schema::hasTable('users')) {
                $existing = DB::table('users')->where('email', $data['admin_email'])->first();
                if (! $existing) {
                    DB::table('users')->insert([
                        'name' => $data['admin_name'],
                        'email' => $data['admin_email'],
                        'password' => Hash::make($data['admin_password']),
                        'email_verified_at' => now(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        } catch (\Exception $e) {
            // ignore user creation errors
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

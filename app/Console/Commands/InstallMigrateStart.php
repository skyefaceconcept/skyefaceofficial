<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class InstallMigrateStart extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'install:migrate-start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run migrations as a background step for the installer and write status to storage';

    public function handle()
    {
        $statusPath = storage_path('app/install_migrate_status.json');
        $logPath = storage_path('logs/install-migrate.log');

        // Mark as running
        file_put_contents($statusPath, json_encode([
            'status' => 'running',
            'started_at' => now()->toDateTimeString(),
            'output' => ''
        ]));

        try {
            // Ensure log directory exists and is writable
            if (! file_exists(dirname($logPath))) {
                @mkdir(dirname($logPath), 0755, true);
            }

            // Run migrations and capture output, append to log
            $exit = Artisan::call('migrate', ['--force' => true]);
            $output = Artisan::output();

            // Append to log file
            file_put_contents($logPath, "\n----- Migrated at: " . now() . " -----\n", FILE_APPEND);
            file_put_contents($logPath, $output . "\n", FILE_APPEND);

            // Mark as done
            file_put_contents($statusPath, json_encode([
                'status' => 'done',
                'completed_at' => now()->toDateTimeString(),
                'output' => substr($output, 0, 8192),
                'exit' => $exit,
            ]));
        } catch (\Exception $e) {
            // Log error and mark status as error
            file_put_contents($logPath, "\n----- Migration ERROR at: " . now() . " -----\n" . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n", FILE_APPEND);
            file_put_contents($statusPath, json_encode([
                'status' => 'error',
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]));
        }

        return 0;
    }
}

<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class RunMigrationsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Maximum number of attempts
     *
     * @var int
     */
    public $tries = 1;

    public function __construct()
    {
        // no constructor args for now
    }

    public function handle()
    {
        $statusPath = storage_path('app/install_migrate_status.json');
        $logPath = storage_path('logs/install-migrate.log');

        $this->writeLog($logPath, "[RunMigrationsJob] started at " . now()->toDateTimeString() . PHP_EOL);

        // Discover migrations
        $files = glob(database_path('migrations') . DIRECTORY_SEPARATOR . '*.php');
        natsort($files);
        $migs = [];
        foreach ($files as $f) {
            $migs[] = ['name' => basename($f), 'status' => 'pending'];
        }

        // Initialize status file
        $status = [
            'status' => 'running',
            'started_at' => now()->toDateTimeString(),
            'migrations' => $migs
        ];

        file_put_contents($statusPath, json_encode($status, JSON_PRETTY_PRINT));

        $anyFailure = false;

        foreach ($status['migrations'] as $idx => $m) {
            $name = $m['name'];
            // mark running
            $status['migrations'][$idx]['status'] = 'running';
            $status['migrations'][$idx]['started_at'] = now()->toDateTimeString();
            file_put_contents($statusPath, json_encode($status, JSON_PRETTY_PRINT));

            $this->writeLog($logPath, "[RunMigrationsJob] Running migration: {$name}\n");

            try {
                // run single migration file
                // Use --path with relative path under project root
                Artisan::call('migrate', ['--path' => 'database/migrations/' . $name, '--force' => true]);
                $out = Artisan::output();

                $status['migrations'][$idx]['status'] = 'done';
                $status['migrations'][$idx]['completed_at'] = now()->toDateTimeString();
                $status['migrations'][$idx]['output'] = substr($out, 0, 10000);

                $this->writeLog($logPath, "[RunMigrationsJob] Done: {$name}\n" . $out . PHP_EOL);
            } catch (\Exception $e) {
                $anyFailure = true;
                $status['migrations'][$idx]['status'] = 'failed';
                $status['migrations'][$idx]['completed_at'] = now()->toDateTimeString();
                $status['migrations'][$idx]['error'] = $e->getMessage();

                $this->writeLog($logPath, "[RunMigrationsJob] Failed: {$name}\n" . $e->getMessage() . PHP_EOL);

                // continue with next migration
            }

            // persist status after each migration
            file_put_contents($statusPath, json_encode($status, JSON_PRETTY_PRINT));
        }

        $status['status'] = $anyFailure ? 'error' : 'done';
        $status['completed_at'] = now()->toDateTimeString();
        file_put_contents($statusPath, json_encode($status, JSON_PRETTY_PRINT));

        $this->writeLog($logPath, "[RunMigrationsJob] finished at " . now()->toDateTimeString() . " status=" . $status['status'] . PHP_EOL);

        // Final logging
        try { Log::info('RunMigrationsJob completed', ['status' => $status['status']]); } catch (\Exception $e) {}
    }

    protected function writeLog($path, $text)
    {
        try {
            @file_put_contents($path, "[" . now()->toDateTimeString() . "] " . $text, FILE_APPEND);
        } catch (\Exception $e) {
            try { Log::warning('RunMigrationsJob writeLog failed', ['error' => $e->getMessage()]); } catch (\Exception $__e) {}
        }
    }
}

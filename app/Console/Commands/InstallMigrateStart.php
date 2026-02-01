<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Process\Process;

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

        // Ensure log directory exists
        if (! file_exists(dirname($logPath))) {
            @mkdir(dirname($logPath), 0755, true);
        }

        // Prepare migrator and list pending migrations
        try {
            $migrator = $this->laravel->make('migrator');
            $repo = $migrator->getRepository();
            if (! $repo->repositoryExists()) {
                $repo->createRepository();
            }

            // Get migration files and pending names
            $files = $migrator->getMigrationFiles(database_path('migrations'));
            $ran = $repo->getRan();
            $pending = array_values(array_diff(array_keys($files), $ran));
        } catch (\Throwable $e) {
            $pending = [];
        }

        // Initialize status file with migrations list (pending)
        $migrations = [];
        foreach ($pending as $p) {
            $migrations[$p] = ['name' => $p, 'status' => 'pending', 'message' => ''];
        }
        file_put_contents($statusPath, json_encode([
            'status' => 'running',
            'started_at' => now()->toDateTimeString(),
            'migrations' => array_values($migrations),
            'output' => ''
        ]));

        // Run migrations one-by-one so a failure in one file does not stop others
        try {
            $anyFailed = false;
            foreach ($pending as $p) {
                $path = $files[$p] ?? database_path('migrations/' . $p . '.php');
                $rel = 'database/migrations/' . basename($path);

                // Mark as running
                $raw = @file_get_contents($statusPath);
                $json = $raw ? json_decode($raw, true) : [];
                if (! isset($json['migrations'])) {
                    $json['migrations'] = [];
                }
                foreach ($json['migrations'] as &$mig) {
                    if ($mig['name'] === $p) {
                        $mig['status'] = 'running';
                        $mig['started_at'] = now()->toDateTimeString();
                        $mig['message'] = "Running migration: {$p}";
                        break;
                    }
                }
                file_put_contents($statusPath, json_encode($json));

                // Run this single migration via artisan --path
                $proc = new Process(['php', base_path('artisan'), 'migrate', '--path=' . $rel, '--force', '--no-interaction']);
                $proc->setTimeout(null);
                $proc->start();

                while ($proc->isRunning()) {
                    $out = $proc->getIncrementalOutput();
                    $err = $proc->getIncrementalErrorOutput();
                    if ($out !== '') file_put_contents($logPath, $out, FILE_APPEND);
                    if ($err !== '') file_put_contents($logPath, $err, FILE_APPEND);

                    // Update running message for this migration with latest output line
                    $combined = $out . "\n" . $err;
                    $lines = preg_split('/\r?\n/', $combined);
                    $lastLine = '';
                    foreach ($lines as $line) {
                        $line = trim($line);
                        if ($line === '') continue;
                        $lastLine = $line;
                    }
                    if ($lastLine !== '') {
                        $raw = @file_get_contents($statusPath);
                        $json = $raw ? json_decode($raw, true) : [];
                        foreach ($json['migrations'] as &$mig) {
                            if ($mig['name'] === $p) {
                                $mig['message'] = $lastLine;
                                break;
                            }
                        }
                        file_put_contents($statusPath, json_encode($json));
                    }

                    usleep(200000);
                }

                // Capture final output/error for this migration
                $o = $proc->getIncrementalOutput();
                $e = $proc->getIncrementalErrorOutput();
                if ($o !== '') file_put_contents($logPath, $o, FILE_APPEND);
                if ($e !== '') file_put_contents($logPath, $e, FILE_APPEND);

                $exit = $proc->getExitCode();
                $finalLines = '';
                $tail = '';
                try { $tail = substr(file_get_contents($logPath), -4096); } catch (\Exception $ex) { $tail = ''; }

                // Update migration status entry
                $raw = @file_get_contents($statusPath);
                $json = $raw ? json_decode($raw, true) : [];
                foreach ($json['migrations'] as &$mig) {
                    if ($mig['name'] === $p) {
                        if ($exit === 0) {
                            $mig['status'] = 'done';
                            $mig['message'] = $tail ?: 'Done';
                            $mig['completed_at'] = now()->toDateTimeString();
                        } else {
                            $mig['status'] = 'failed';
                            $mig['message'] = $tail ?: ('Failed with exit ' . $exit);
                            $mig['completed_at'] = now()->toDateTimeString();
                            $anyFailed = true;
                        }
                        break;
                    }
                }
                file_put_contents($statusPath, json_encode($json));
            }

            // Final overall status
            $finalOutput = file_get_contents($logPath);
            $finalStatus = $anyFailed ? 'error' : 'done';
            $statusDoc = [
                'status' => $finalStatus,
                'completed_at' => now()->toDateTimeString(),
                'exit' => $anyFailed ? 1 : 0,
                'output' => substr($finalOutput, -8192),
            ];

            $raw = @file_get_contents($statusPath);
            $json = $raw ? json_decode($raw, true) : [];
            if (isset($json['migrations'])) {
                $statusDoc['migrations'] = $json['migrations'];
            }

            file_put_contents($statusPath, json_encode($statusDoc));
        } catch (\Exception $e) {
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

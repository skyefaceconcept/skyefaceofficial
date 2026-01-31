<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;

echo "\n=== PROCESSING QUEUE JOBS ===\n";

// Get pending jobs
$jobs = DB::table('jobs')->limit(5)->get();
echo "Found " . count($jobs) . " pending jobs\n\n";

foreach($jobs as $job) {
    echo "Processing Job #{$job->id}...\n";
    $payload = json_decode($job->payload, true);
    echo "  Queue: {$job->queue}\n";
    echo "  Attempts: {$job->attempts}\n";
    
    // Show job details if it's a Mail job
    if (isset($payload['data']['commandName'])) {
        echo "  Command: {$payload['data']['commandName']}\n";
    }
    
    try {
        // Process the job using Laravel's queue handler
        $worker = app(\Illuminate\Queue\Worker::class);
        $connection = Queue::connection();
        
        // Manually process this specific job
        $job = $connection->get();
        if ($job) {
            $job->fire();
            $connection->delete($job);
            echo "  ✓ Job processed successfully\n";
        }
    } catch (\Exception $e) {
        echo "  ✗ Error: " . $e->getMessage() . "\n";
    }
    echo "\n";
}

// Show updated queue status
echo "\n=== FINAL QUEUE STATUS ===\n";
$remainingJobs = DB::table('jobs')->count();
$failedJobs = DB::table('failed_jobs')->count();
echo "Remaining jobs: $remainingJobs\n";
echo "Failed jobs: $failedJobs\n";

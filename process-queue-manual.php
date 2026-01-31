<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Queue\Jobs\DatabaseJob;

echo "\n=== PROCESSING QUEUE JOBS MANUALLY ===\n\n";

$startTime = time();
$maxDuration = 30; // 30 seconds max
$jobsProcessed = 0;
$jobsFailed = 0;

while ((time() - $startTime) < $maxDuration) {
    // Get one job from the queue
    $jobRecord = DB::table('jobs')->where('queue', 'default')->first();
    
    if (!$jobRecord) {
        echo "✓ No more jobs to process\n";
        break;
    }
    
    $jobsProcessed++;
    echo "Processing Job #{$jobRecord->id}...\n";
    
    try {
        // Create a DatabaseJob instance and process it
        $connection = app(\Illuminate\Queue\QueueManager::class)->connection();
        $job = new DatabaseJob(app(), $connection, $jobRecord);
        
        // Process the job
        $job->fire();
        
        echo "  ✓ Success\n";
    } catch (\Exception $e) {
        $jobsFailed++;
        echo "  ✗ Failed: " . $e->getMessage() . "\n";
        
        // Move to failed jobs
        DB::table('failed_jobs')->insert([
            'uuid' => \Illuminate\Support\Str::uuid(),
            'connection' => 'database',
            'queue' => 'default',
            'payload' => $jobRecord->payload,
            'exception' => (string)$e,
            'failed_at' => \Carbon\Carbon::now(),
        ]);
        
        // Delete from jobs table
        DB::table('jobs')->where('id', $jobRecord->id)->delete();
    }
    
    // Add a small delay to prevent CPU spinning
    usleep(100000); // 0.1 second
}

echo "\n=== PROCESSING COMPLETE ===\n";
echo "Jobs Processed: $jobsProcessed\n";
echo "Jobs Failed: $jobsFailed\n";
echo "Time Elapsed: " . (time() - $startTime) . " seconds\n\n";

// Show final queue status
$remaining = DB::table('jobs')->count();
$failedTotal = DB::table('failed_jobs')->count();
echo "Remaining jobs: $remaining\n";
echo "Total failed jobs: $failedTotal\n\n";

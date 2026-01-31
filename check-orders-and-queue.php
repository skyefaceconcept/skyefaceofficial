<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Order;
use Illuminate\Support\Facades\DB;

echo "\n=== LATEST 5 ORDERS ===\n";
$orders = Order::latest('id')->limit(5)->get();
foreach($orders as $order) {
    echo "Order #{$order->id}: {$order->customer_email} | Amount: {$order->amount} | Status: {$order->status} | Created: {$order->created_at}\n";
}

echo "\n=== JOBS QUEUE STATUS ===\n";
$jobs = DB::table('jobs')->latest('id')->limit(10)->get();
echo "Total pending jobs: " . count($jobs) . "\n";
foreach($jobs as $job) {
    echo "Job #{$job->id}: Queue={$job->queue}, Attempts={$job->attempts}\n";
}

echo "\n=== FAILED JOBS ===\n";
$failed = DB::table('failed_jobs')->latest('id')->limit(5)->get();
echo "Total failed jobs: " . count($failed) . "\n";
foreach($failed as $job) {
    echo "Failed Job #{$job->id}: Exception: " . substr($job->exception, 0, 100) . "...\n";
}

echo "\n";

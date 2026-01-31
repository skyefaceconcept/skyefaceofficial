<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

$count = DB::table('mail_logs')->where('to', 'skyefacecon@gmail.com')->count();
echo "Total emails in mail_logs from skyefacecon@gmail.com: $count\n\n";

$latest = DB::table('mail_logs')->latest()->limit(15)->get();
echo "Latest 15 emails in database:\n";
foreach($latest as $log) {
    echo "  [{$log->id}] " . substr($log->subject, 0, 60) . "\n";
}

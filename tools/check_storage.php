<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
// Boot the framework to ensure Storage facade works
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$path = 'assets/branding/YigZL9PZyeds2yPcB0VXWtBNf2kGBG7zq6Oqts5U.png';
$exists = Illuminate\Support\Facades\Storage::disk('public')->exists($path);
$fullPath = Illuminate\Support\Facades\Storage::disk('public')->path($path);
echo "exists: " . ($exists ? 'yes' : 'no') . PHP_EOL;
echo "fullPath: $fullPath" . PHP_EOL;
echo "file_exists(fullPath): " . (file_exists($fullPath) ? 'yes' : 'no') . PHP_EOL;

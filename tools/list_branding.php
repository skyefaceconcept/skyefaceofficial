<?php
$files = glob(__DIR__ . '/../storage/app/public/assets/branding/*');
if (count($files) === 0) { echo "No branding assets found\n"; exit(0); }
foreach ($files as $f) { echo basename($f) . PHP_EOL; }

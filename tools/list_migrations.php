<?php
$files = glob(__DIR__ . '/../database/migrations/*.php');
natsort($files);
$migs = [];
foreach ($files as $f) {
    $migs[] = ['name' => basename($f)];
}
echo json_encode(['migrations' => $migs], JSON_PRETTY_PRINT);

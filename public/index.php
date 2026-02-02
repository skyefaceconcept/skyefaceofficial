<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Early redirect to installer when visiting root and DB is missing or unreachable.
// This prevents 500s when the homepage queries the DB before installation is done.
$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
$path = parse_url($requestUri, PHP_URL_PATH) ?: '/';
$rootPaths = ['/', '', '/index.php'];
if (in_array($path, $rootPaths, true) && ! file_exists(__DIR__.'/../storage/app/installed')) {
    // If DB credentials are not present, try to read them from the environment or the .env file and redirect to installer if still missing.
    $dbDatabase = getenv('DB_DATABASE');
    $dbUsername = getenv('DB_USERNAME');

    // If getenv() returns empty (common before Laravel bootstrap), attempt to read the project's .env as a fallback.
    if (empty($dbDatabase) || empty($dbUsername)) {
        $envFile = __DIR__ . '/../.env';
        if (file_exists($envFile)) {
            $envContents = file_get_contents($envFile);
            if (preg_match('/^DB_DATABASE\s*=\s*"?([^\r\n"]*)"?/m', $envContents, $m)) {
                $dbDatabase = $m[1];
            }
            if (preg_match('/^DB_USERNAME\s*=\s*"?([^\r\n"]*)"?/m', $envContents, $n)) {
                $dbUsername = $n[1];
            }
        }
    }

    // Debug log to help track early redirect behavior
    @file_put_contents(__DIR__.'/../storage/logs/early_install_check.log', date('c') . " path={$path} uri={$requestUri} db=" . ($dbDatabase ?: '[empty]') . " user=" . ($dbUsername ?: '[empty]') . PHP_EOL, FILE_APPEND);

    if (empty($dbDatabase) || empty($dbUsername)) {
        @file_put_contents(__DIR__.'/../storage/logs/early_install_check.log', date('c') . " -> redirect (missing creds)" . PHP_EOL, FILE_APPEND);
        header('Location: /install', true, 302);
        exit;
    }

    // Otherwise, attempt a short PDO connection check. If it fails, redirect to installer.
    try {
        $host = getenv('DB_HOST') ?: '127.0.0.1';
        $port = getenv('DB_PORT') ?: '3306';
        $dsn = "mysql:host={$host};port={$port};dbname={$dbDatabase}";
        $pdo = new PDO($dsn, $dbUsername, getenv('DB_PASSWORD') ?: '', [PDO::ATTR_TIMEOUT => 2, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    } catch (\Throwable $e) {
        @file_put_contents(__DIR__.'/../storage/logs/early_install_check.log', date('c') . " -> redirect (pdo failed): " . $e->getMessage() . PHP_EOL, FILE_APPEND);
        header('Location: /install', true, 302);
        exit;
    }
}

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());

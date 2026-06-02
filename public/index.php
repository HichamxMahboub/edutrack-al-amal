<?php

$uriPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

if ($uriPath === '/health') {
    header('Content-Type: application/json');

    echo json_encode([
        'status' => 'ok',
        'app' => getenv('APP_NAME') ?: 'EduTrack Al Amal',
        'environment' => getenv('APP_ENV') ?: 'production',
        'view_compiled_path' => getenv('VIEW_COMPILED_PATH') ?: '/tmp/views',
        'tmp_writable' => is_writable('/tmp'),
        'time' => gmdate('c'),
    ]);

    return;
}

if (getenv('VERCEL') || getenv('VIEW_COMPILED_PATH')) {
    $paths = [
        '/tmp/views',
        '/tmp/cache',
        '/tmp/sessions',
        '/tmp/framework',
        '/tmp/framework/views',
        '/tmp/framework/cache',
        '/tmp/framework/sessions',
    ];

    foreach ($paths as $path) {
        if (! is_dir($path)) {
            mkdir($path, 0777, true);
        }
    }
}

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
try {
    $app = require_once __DIR__.'/../bootstrap/app.php';

    $app->handleRequest(Request::capture());
} catch (Throwable $exception) {
    error_log('EDUTRACK_BOOTSTRAP_ERROR: '.$exception);

    throw $exception;
}

<?php

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);

// Set error handler to log errors
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    $log_file = __DIR__ . '/core/storage/logs/php_errors.log';
    $log_dir = dirname($log_file);
    if (!is_dir($log_dir)) {
        @mkdir($log_dir, 0755, true);
    }
    @file_put_contents($log_file, date('Y-m-d H:i:s') . " - Error [$errno]: $errstr in $errfile:$errline\n", FILE_APPEND);
    return false; // Let PHP handle it normally
});

// Set exception handler
set_exception_handler(function($exception) {
    $log_file = __DIR__ . '/core/storage/logs/php_errors.log';
    $log_dir = dirname($log_file);
    if (!is_dir($log_dir)) {
        @mkdir($log_dir, 0755, true);
    }
    $message = date('Y-m-d H:i:s') . " - Exception: " . $exception->getMessage() . " in " . $exception->getFile() . ":" . $exception->getLine() . "\n";
    $message .= "Stack trace:\n" . $exception->getTraceAsString() . "\n\n";
    @file_put_contents($log_file, $message, FILE_APPEND);
    
    // Show error if debug is enabled
    if (getenv('APP_DEBUG') === 'true' || (file_exists(__DIR__.'/core/.env') && strpos(file_get_contents(__DIR__.'/core/.env'), 'APP_DEBUG=true') !== false)) {
        http_response_code(500);
        echo "<h1>Application Error</h1>";
        echo "<p><strong>Error:</strong> " . htmlspecialchars($exception->getMessage()) . "</p>";
        echo "<p><strong>File:</strong> " . htmlspecialchars($exception->getFile()) . "</p>";
        echo "<p><strong>Line:</strong> " . $exception->getLine() . "</p>";
        echo "<pre>" . htmlspecialchars($exception->getTraceAsString()) . "</pre>";
    } else {
        http_response_code(500);
        echo "Application Error. Please check logs.";
    }
    exit(1);
});

// Register shutdown function to catch fatal errors
register_shutdown_function(function() {
    $error = error_get_last();
    if ($error !== NULL && in_array($error['type'], [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_PARSE])) {
        $log_file = __DIR__ . '/core/storage/logs/php_errors.log';
        $log_dir = dirname($log_file);
        if (!is_dir($log_dir)) {
            @mkdir($log_dir, 0755, true);
        }
        $message = date('Y-m-d H:i:s') . " - Fatal Error: " . $error['message'] . " in " . $error['file'] . ":" . $error['line'] . "\n";
        @file_put_contents($log_file, $message, FILE_APPEND);
    }
});

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Check If Application Is Under Maintenance
|--------------------------------------------------------------------------
|
| If the application is maintenance / demo mode via the "down" command we
| will require this file so that any prerendered template can be shown
| instead of starting the framework, which could cause an exception.
|
*/

if (file_exists(__DIR__.'/core/storage/framework/maintenance.php')) {
    require __DIR__.'/core/storage/framework/maintenance.php';
}

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| this application. We just need to utilize it! We'll simply require it
| into the script here so we don't need to manually load our classes.
|
*/

$autoload_path = __DIR__.'/core/vendor/autoload.php';
$vendor_dir = __DIR__.'/core/vendor';

// Check if vendor directory exists
if (!is_dir($vendor_dir)) {
    http_response_code(500);
    $error_msg = "Error: Composer dependencies not installed.\n\n";
    $error_msg .= "The vendor directory is missing. Please run 'composer install' in the core directory.\n\n";
    $error_msg .= "To fix this:\n";
    $error_msg .= "1. Connect to your server via SSH\n";
    $error_msg .= "2. Navigate to: " . __DIR__ . "/core\n";
    $error_msg .= "3. Run: composer install --no-dev --optimize-autoloader\n\n";
    $error_msg .= "If you don't have SSH access, contact your hosting provider.";
    die($error_msg);
}

// Check if autoload.php exists
if (!file_exists($autoload_path)) {
    http_response_code(500);
    $error_msg = "Error: Composer autoload file not found.\n\n";
    $error_msg .= "The vendor directory exists but autoload.php is missing.\n";
    $error_msg .= "This usually means composer install didn't complete successfully.\n\n";
    $error_msg .= "To fix this:\n";
    $error_msg .= "1. Connect to your server via SSH\n";
    $error_msg .= "2. Navigate to: " . __DIR__ . "/core\n";
    $error_msg .= "3. Run: composer install --no-dev --optimize-autoloader\n";
    die($error_msg);
}

require $autoload_path;

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request using
| the application's HTTP kernel. Then, we will send the response back
| to this client's browser, allowing them to enjoy our application.
|
*/

$bootstrap_path = __DIR__.'/core/bootstrap/app.php';
if (!file_exists($bootstrap_path)) {
    http_response_code(500);
    die("Error: Bootstrap file not found at: $bootstrap_path");
}

$app = require_once $bootstrap_path;

$kernel = $app->make(Kernel::class);

$response = tap($kernel->handle(
    $request = Request::capture()
))->send();

$kernel->terminate($request, $response);

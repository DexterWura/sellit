<?php

// Only apply enhanced error handling for the subdomain
$is_subdomain = isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], 'sellit.zimadsense.com') !== false;

if ($is_subdomain) {
    // Enable error reporting for debugging (subdomain only)
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('log_errors', 1);

    // Set error handler to log errors (subdomain only)
    set_error_handler(function($errno, $errstr, $errfile, $errline) {
        $log_file = __DIR__ . '/core/storage/logs/php_errors.log';
        $log_dir = dirname($log_file);
        if (!is_dir($log_dir)) {
            @mkdir($log_dir, 0755, true);
        }
        @file_put_contents($log_file, date('Y-m-d H:i:s') . " - Error [$errno]: $errstr in $errfile:$errline\n", FILE_APPEND);
        return false; // Let PHP handle it normally
    });

    // Set exception handler (subdomain only)
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

    // Register shutdown function to catch fatal errors (subdomain only)
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
}

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

// Only show detailed error messages for subdomain, simple check for main site
if (!is_dir($vendor_dir) || !file_exists($autoload_path)) {
    if ($is_subdomain) {
        // Detailed check with helpful error messages (subdomain only)
        if (!is_dir($vendor_dir)) {
            http_response_code(500);
            $error_msg = "<!DOCTYPE html><html><head><title>Error</title><style>body{font-family:Arial;padding:20px;background:#f5f5f5;}";
            $error_msg .= ".container{max-width:800px;margin:0 auto;background:white;padding:30px;border-radius:8px;border-left:4px solid #dc3545;}";
            $error_msg .= "code{background:#f4f4f4;padding:2px 6px;border-radius:3px;}</style></head><body><div class='container'>";
            $error_msg .= "<h1 style='color:#dc3545;'>❌ Composer Dependencies Not Installed</h1>";
            $error_msg .= "<p><strong>The vendor directory is missing.</strong></p>";
            $error_msg .= "<p>Path checked: <code>" . htmlspecialchars($vendor_dir) . "</code></p>";
            $error_msg .= "<h3>To fix this:</h3><ol>";
            $error_msg .= "<li>Connect to your server via SSH</li>";
            $error_msg .= "<li>Navigate to: <code>" . htmlspecialchars(__DIR__ . "/core") . "</code></li>";
            $error_msg .= "<li>Run: <code>composer install --no-dev --optimize-autoloader</code></li>";
            $error_msg .= "</ol><p><a href='check-vendor.php' style='color:#007bff;'>Run diagnostic check</a></p></div></body></html>";
            die($error_msg);
        }

        // Check if autoload.php exists
        if (!file_exists($autoload_path)) {
            http_response_code(500);
            $error_msg = "<!DOCTYPE html><html><head><title>Error</title><style>body{font-family:Arial;padding:20px;background:#f5f5f5;}";
            $error_msg .= ".container{max-width:800px;margin:0 auto;background:white;padding:30px;border-radius:8px;border-left:4px solid #ffc107;}";
            $error_msg .= "code{background:#f4f4f4;padding:2px 6px;border-radius:3px;}</style></head><body><div class='container'>";
            $error_msg .= "<h1 style='color:#ffc107;'>⚠️ Autoload File Missing</h1>";
            $error_msg .= "<p><strong>The vendor directory exists but autoload.php is missing.</strong></p>";
            $error_msg .= "<p>This usually means composer install didn't complete successfully or the vendor directory is incomplete.</p>";
            $error_msg .= "<p>Vendor directory: <code>" . htmlspecialchars($vendor_dir) . "</code></p>";
            $error_msg .= "<p>Autoload path: <code>" . htmlspecialchars($autoload_path) . "</code></p>";
            
            // Check if vendor directory has any contents
            $vendor_contents = @scandir($vendor_dir);
            $item_count = $vendor_contents ? count($vendor_contents) - 2 : 0;
            $error_msg .= "<p>Items in vendor directory: <strong>" . $item_count . "</strong> " . ($item_count < 10 ? "(incomplete - should be 100+)" : "") . "</p>";
            
            $error_msg .= "<h3>To fix this:</h3><ol>";
            $error_msg .= "<li>Delete the vendor directory: <code>rm -rf " . htmlspecialchars($vendor_dir) . "</code></li>";
            $error_msg .= "<li>Navigate to: <code>" . htmlspecialchars(__DIR__ . "/core") . "</code></li>";
            $error_msg .= "<li>Run: <code>composer install --no-dev --optimize-autoloader</code></li>";
            $error_msg .= "</ol><p><a href='check-vendor.php' style='color:#007bff;'>Run detailed diagnostic</a></p></div></body></html>";
            die($error_msg);
        }

        // Check if autoload.php is readable
        if (!is_readable($autoload_path)) {
            http_response_code(500);
            $error_msg = "<!DOCTYPE html><html><head><title>Error</title><style>body{font-family:Arial;padding:20px;background:#f5f5f5;}";
            $error_msg .= ".container{max-width:800px;margin:0 auto;background:white;padding:30px;border-radius:8px;border-left:4px solid #dc3545;}";
            $error_msg .= "code{background:#f4f4f4;padding:2px 6px;border-radius:3px;}</style></head><body><div class='container'>";
            $error_msg .= "<h1 style='color:#dc3545;'>❌ Permission Error</h1>";
            $error_msg .= "<p><strong>The autoload.php file exists but is not readable.</strong></p>";
            $error_msg .= "<p>This is a file permissions issue.</p>";
            $error_msg .= "<h3>To fix this:</h3><ol>";
            $error_msg .= "<li>Run: <code>chmod -R 755 " . htmlspecialchars($vendor_dir) . "</code></li>";
            $error_msg .= "<li>Or: <code>chmod 644 " . htmlspecialchars($autoload_path) . "</code></li>";
            $error_msg .= "</ol></div></body></html>";
            die($error_msg);
        }
    } else {
        // Simple error for main site (don't break it)
        if (!file_exists($autoload_path)) {
            http_response_code(500);
            die("Application Error: Required files missing.");
        }
    }
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

<?php
// Enable all error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// Try to catch any fatal errors
register_shutdown_function(function() {
    $error = error_get_last();
    if ($error !== NULL) {
        echo "<h1>Fatal Error Detected:</h1>";
        echo "<pre>";
        print_r($error);
        echo "</pre>";
    }
});

// Simple output
echo "<h1>Error Check Page</h1>";
echo "<p>PHP Version: " . PHP_VERSION . "</p>";
echo "<p>If you see this, basic PHP is working.</p>";

// Try phpinfo
echo "<h2>Attempting phpinfo():</h2>";
try {
    ob_start();
    phpinfo();
    $info = ob_get_clean();
    echo $info;
} catch (Throwable $e) {
    echo "<p style='color:red;'>Error: " . $e->getMessage() . "</p>";
    echo "<p>File: " . $e->getFile() . "</p>";
    echo "<p>Line: " . $e->getLine() . "</p>";
}
?>


<?php
/**
 * PHP Info Page - Shows detailed PHP configuration
 * Access: https://sellit.zimadsense.com/phpinfo.php
 * 
 * WARNING: Remove this file after debugging for security reasons!
 */

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Try to show PHP info
try {
    phpinfo();
} catch (Exception $e) {
    echo "Error calling phpinfo(): " . $e->getMessage();
    echo "<br><br>";
    echo "PHP Version: " . PHP_VERSION;
    echo "<br>";
    echo "Server: " . ($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown');
}


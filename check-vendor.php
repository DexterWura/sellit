<?php
/**
 * Check Vendor Directory
 * This will show exactly what's in the vendor folder and what's missing
 * Access: https://sellit.zimadsense.com/check-vendor.php
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

$core_path = __DIR__ . '/core';
$vendor_path = $core_path . '/vendor';
$autoload_path = $vendor_path . '/autoload.php';
$composer_json = $core_path . '/composer.json';

?>
<!DOCTYPE html>
<html>
<head>
    <title>Vendor Directory Check</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f5f5f5; }
        .container { max-width: 1000px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; }
        .check-item { padding: 15px; margin: 10px 0; border-left: 4px solid #ddd; background: #f8f9fa; }
        .check-item.ok { border-left-color: #28a745; }
        .check-item.error { border-left-color: #dc3545; }
        .check-item.warning { border-left-color: #ffc107; }
        .status { font-weight: bold; font-size: 1.1em; }
        .ok .status { color: #28a745; }
        .error .status { color: #dc3545; }
        .warning .status { color: #ffc107; }
        code { background: #f4f4f4; padding: 2px 6px; border-radius: 3px; font-family: monospace; }
        pre { background: #f4f4f4; padding: 15px; border-radius: 5px; overflow-x: auto; }
        .path { color: #666; font-size: 0.9em; margin-top: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Vendor Directory Diagnostic</h1>
        
        <?php
        $checks = [];
        
        // Check 1: Core directory
        $checks[] = [
            'name' => 'Core Directory',
            'status' => is_dir($core_path) ? 'ok' : 'error',
            'message' => is_dir($core_path) ? 'Exists' : 'Missing',
            'path' => $core_path
        ];
        
        // Check 2: Vendor directory
        $vendor_exists = is_dir($vendor_path);
        $checks[] = [
            'name' => 'Vendor Directory',
            'status' => $vendor_exists ? 'ok' : 'error',
            'message' => $vendor_exists ? 'Exists' : 'Missing',
            'path' => $vendor_path
        ];
        
        // Check 3: Autoload file
        $autoload_exists = file_exists($autoload_path);
        $checks[] = [
            'name' => 'Autoload File',
            'status' => $autoload_exists ? 'ok' : 'error',
            'message' => $autoload_exists ? 'Exists' : 'Missing',
            'path' => $autoload_path
        ];
        
        // Check 4: Composer.json
        $composer_json_exists = file_exists($composer_json);
        $checks[] = [
            'name' => 'Composer.json',
            'status' => $composer_json_exists ? 'ok' : 'error',
            'message' => $composer_json_exists ? 'Exists' : 'Missing',
            'path' => $composer_json
        ];
        
        // Check 5: Vendor directory contents
        if ($vendor_exists) {
            $vendor_contents = @scandir($vendor_path);
            $vendor_count = $vendor_contents ? count($vendor_contents) - 2 : 0; // -2 for . and ..
            $checks[] = [
                'name' => 'Vendor Directory Contents',
                'status' => $vendor_count > 10 ? 'ok' : 'warning',
                'message' => $vendor_count > 10 ? "$vendor_count items found" : "Only $vendor_count items found (should be 100+)",
                'details' => $vendor_count < 10 ? 'Vendor directory appears incomplete. Run: composer install' : null
            ];
        }
        
        // Check 6: Try to require autoload
        if ($autoload_exists) {
            try {
                require_once $autoload_path;
                $checks[] = [
                    'name' => 'Load Autoload',
                    'status' => 'ok',
                    'message' => 'Successfully loaded autoload.php'
                ];
            } catch (Exception $e) {
                $checks[] = [
                    'name' => 'Load Autoload',
                    'status' => 'error',
                    'message' => 'Failed to load: ' . $e->getMessage()
                ];
            } catch (Error $e) {
                $checks[] = [
                    'name' => 'Load Autoload',
                    'status' => 'error',
                    'message' => 'Fatal error: ' . $e->getMessage()
                ];
            }
        }
        
        // Check 7: Check for key Laravel files
        if ($vendor_exists) {
            $key_files = [
                'composer/autoload_real.php',
                'illuminate/support/helpers.php',
                'illuminate/foundation/Application.php'
            ];
            $missing_key_files = [];
            foreach ($key_files as $file) {
                if (!file_exists($vendor_path . '/' . $file)) {
                    $missing_key_files[] = $file;
                }
            }
            $checks[] = [
                'name' => 'Key Laravel Files',
                'status' => empty($missing_key_files) ? 'ok' : 'error',
                'message' => empty($missing_key_files) ? 'All key files present' : 'Missing: ' . implode(', ', $missing_key_files),
                'details' => !empty($missing_key_files) ? 'Vendor directory is incomplete. Run: composer install' : null
            ];
        }
        
        // Check 8: File permissions
        if ($vendor_exists) {
            $vendor_readable = is_readable($vendor_path);
            $autoload_readable = $autoload_exists ? is_readable($autoload_path) : false;
            $checks[] = [
                'name' => 'File Permissions',
                'status' => ($vendor_readable && $autoload_readable) ? 'ok' : 'error',
                'message' => $vendor_readable && $autoload_readable 
                    ? 'Readable' 
                    : 'Not readable - Check permissions',
                'details' => !$vendor_readable ? 'Vendor directory not readable' : (!$autoload_readable ? 'Autoload file not readable' : null)
            ];
        }
        
        // Display all checks
        foreach ($checks as $check) {
            $status_class = $check['status'];
            echo '<div class="check-item ' . $status_class . '">';
            echo '<div class="status">';
            if ($check['status'] === 'ok') echo '✅';
            elseif ($check['status'] === 'warning') echo '⚠️';
            else echo '❌';
            echo ' ' . htmlspecialchars($check['name']) . '</div>';
            echo '<div>' . htmlspecialchars($check['message']) . '</div>';
            if (isset($check['path'])) {
                echo '<div class="path"><code>' . htmlspecialchars($check['path']) . '</code></div>';
            }
            if (isset($check['details'])) {
                echo '<div style="margin-top: 10px; color: #dc3545;"><strong>Details:</strong> ' . htmlspecialchars($check['details']) . '</div>';
            }
            echo '</div>';
        }
        
        // Show vendor directory listing if it exists
        if ($vendor_exists && is_readable($vendor_path)) {
            echo '<div class="check-item">';
            echo '<h3>Vendor Directory Contents (first 50 items):</h3>';
            $items = @scandir($vendor_path);
            if ($items) {
                $items = array_filter($items, function($item) {
                    return $item !== '.' && $item !== '..';
                });
                $items = array_slice($items, 0, 50);
                echo '<pre>' . implode("\n", $items) . '</pre>';
                if (count($items) >= 50) {
                    echo '<p><em>... and more (showing first 50)</em></p>';
                }
            } else {
                echo '<p class="error">Could not read vendor directory</p>';
            }
            echo '</div>';
        }
        ?>
        
        <div style="margin-top: 30px; padding: 15px; background: #e7f3ff; border-radius: 5px;">
            <h3>Next Steps:</h3>
            <ul>
                <li>If <strong>autoload.php is missing</strong>: Run <code>composer install</code> in the core directory</li>
                <li>If <strong>vendor directory is incomplete</strong>: Delete it and run <code>composer install</code> again</li>
                <li>If <strong>permissions are wrong</strong>: Run <code>chmod -R 755 core/vendor</code></li>
                <li>If <strong>autoload loads but site still fails</strong>: Check <code>core/storage/logs/laravel.log</code> for errors</li>
            </ul>
        </div>
    </div>
</body>
</html>


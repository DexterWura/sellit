<?php
/**
 * Root-level diagnostic - bypasses Laravel completely
 * Access: https://sellit.zimadsense.com/diagnostic-root.php
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Root Diagnostic</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f5f5f5; }
        .container { max-width: 1000px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; }
        .success { color: green; }
        .error { color: red; }
        .warning { color: orange; }
        .box { background: #f8f9fa; padding: 15px; margin: 10px 0; border-left: 4px solid #ddd; }
        .box.ok { border-left-color: green; }
        .box.error { border-left-color: red; }
        code { background: #f4f4f4; padding: 2px 6px; border-radius: 3px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Root-Level Diagnostic</h1>
        <p>This page bypasses Laravel completely to test basic PHP and server configuration.</p>
        
        <div class="box ok">
            <h2 class="success">✅ PHP is Working!</h2>
            <p>If you can see this page, PHP is executing.</p>
        </div>
        
        <div class="box">
            <h3>Server Information:</h3>
            <ul>
                <li><strong>PHP Version:</strong> <?php echo PHP_VERSION; ?></li>
                <li><strong>Server Software:</strong> <?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown'; ?></li>
                <li><strong>Document Root:</strong> <code><?php echo $_SERVER['DOCUMENT_ROOT'] ?? 'Not set'; ?></code></li>
                <li><strong>Script Path:</strong> <code><?php echo __FILE__; ?></code></li>
                <li><strong>Current Directory:</strong> <code><?php echo getcwd(); ?></code></li>
                <li><strong>PHP SAPI:</strong> <?php echo php_sapi_name(); ?></li>
            </ul>
        </div>
        
        <div class="box">
            <h3>File System Checks:</h3>
            <ul>
                <li><strong>Root index.php exists:</strong> <?php echo file_exists(__DIR__ . '/index.php') ? '✅ Yes' : '❌ No'; ?></li>
                <li><strong>Core directory exists:</strong> <?php echo is_dir(__DIR__ . '/core') ? '✅ Yes' : '❌ No'; ?></li>
                <li><strong>Core/public exists:</strong> <?php echo is_dir(__DIR__ . '/core/public') ? '✅ Yes' : '❌ No'; ?></li>
                <li><strong>Core/vendor exists:</strong> <?php echo is_dir(__DIR__ . '/core/vendor') ? '✅ Yes' : '❌ No'; ?></li>
                <li><strong>Core/.env exists:</strong> <?php echo file_exists(__DIR__ . '/core/.env') ? '✅ Yes' : '❌ No'; ?></li>
                <li><strong>Core/storage exists:</strong> <?php echo is_dir(__DIR__ . '/core/storage') ? '✅ Yes' : '❌ No'; ?></li>
                <li><strong>Core/storage/logs exists:</strong> <?php echo is_dir(__DIR__ . '/core/storage/logs') ? '✅ Yes' : '❌ No'; ?></li>
                <li><strong>Core/storage/logs writable:</strong> <?php 
                    $logs = __DIR__ . '/core/storage/logs';
                    echo is_dir($logs) && is_writable($logs) ? '✅ Yes' : '❌ No';
                ?></li>
            </ul>
        </div>
        
        <div class="box">
            <h3>PHP Extensions:</h3>
            <ul>
                <?php
                $exts = ['pdo', 'pdo_mysql', 'mbstring', 'openssl', 'json', 'curl', 'fileinfo', 'gd', 'zip'];
                foreach ($exts as $ext) {
                    $loaded = extension_loaded($ext);
                    echo '<li><strong>' . $ext . ':</strong> ' . ($loaded ? '<span class="success">✅ Loaded</span>' : '<span class="error">❌ Missing</span>') . '</li>';
                }
                ?>
            </ul>
        </div>
        
        <div class="box">
            <h3>Try Loading Laravel Files:</h3>
            <ul>
                <?php
                $autoload = __DIR__ . '/core/vendor/autoload.php';
                if (file_exists($autoload)) {
                    echo '<li><strong>Autoload file:</strong> <span class="success">✅ Exists</span></li>';
                    try {
                        require_once $autoload;
                        echo '<li><strong>Autoload loading:</strong> <span class="success">✅ Success</span></li>';
                    } catch (Exception $e) {
                        echo '<li><strong>Autoload loading:</strong> <span class="error">❌ Failed: ' . htmlspecialchars($e->getMessage()) . '</span></li>';
                    }
                } else {
                    echo '<li><strong>Autoload file:</strong> <span class="error">❌ Missing</span></li>';
                }
                
                $bootstrap = __DIR__ . '/core/bootstrap/app.php';
                if (file_exists($bootstrap)) {
                    echo '<li><strong>Bootstrap file:</strong> <span class="success">✅ Exists</span></li>';
                    try {
                        $app = require_once $bootstrap;
                        echo '<li><strong>Bootstrap loading:</strong> <span class="success">✅ Success</span></li>';
                    } catch (Exception $e) {
                        echo '<li><strong>Bootstrap loading:</strong> <span class="error">❌ Failed: ' . htmlspecialchars($e->getMessage()) . '</span></li>';
                        echo '<li><strong>Error details:</strong> <code>' . htmlspecialchars($e->getFile()) . ':' . $e->getLine() . '</code></li>';
                    }
                } else {
                    echo '<li><strong>Bootstrap file:</strong> <span class="error">❌ Missing</span></li>';
                }
                ?>
            </ul>
        </div>
        
        <div class="box">
            <h3>Error Logging:</h3>
            <ul>
                <li><strong>Error Reporting:</strong> <?php echo error_reporting(); ?></li>
                <li><strong>Display Errors:</strong> <?php echo ini_get('display_errors') ? 'On' : 'Off'; ?></li>
                <li><strong>Error Log Path:</strong> <code><?php echo ini_get('error_log') ?: 'Not set'; ?></code></li>
                <li><strong>Log Errors:</strong> <?php echo ini_get('log_errors') ? 'On' : 'Off'; ?></li>
            </ul>
        </div>
        
        <div class="box">
            <h3>Test Writing to Log:</h3>
            <?php
            $test_log = __DIR__ . '/core/storage/logs/test_' . date('Y-m-d') . '.log';
            $write_test = @file_put_contents($test_log, "Test log entry at " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);
            if ($write_test !== false) {
                echo '<p class="success">✅ Successfully wrote to: <code>' . $test_log . '</code></p>';
                echo '<p>File size: ' . filesize($test_log) . ' bytes</p>';
            } else {
                echo '<p class="error">❌ Failed to write to log file</p>';
                echo '<p>Path: <code>' . $test_log . '</code></p>';
                echo '<p>Directory exists: ' . (is_dir(dirname($test_log)) ? 'Yes' : 'No') . '</p>';
                echo '<p>Directory writable: ' . (is_writable(dirname($test_log)) ? 'Yes' : 'No') . '</p>';
            }
            ?>
        </div>
        
        <div class="box">
            <h3>Next Steps:</h3>
            <ol>
                <li>If Laravel bootstrap fails, check the error message above</li>
                <li>Check if <code>core/vendor/autoload.php</code> exists (run <code>composer install</code> if missing)</li>
                <li>Check if <code>core/.env</code> exists and has correct database settings</li>
                <li>Check file permissions on <code>core/storage/</code> directory (should be writable)</li>
                <li>Check server error logs (usually in cPanel or server logs directory)</li>
            </ol>
        </div>
        
        <p style="margin-top: 30px; color: #999; font-size: 0.9em;">
            Generated: <?php echo date('Y-m-d H:i:s T'); ?>
        </p>
    </div>
</body>
</html>


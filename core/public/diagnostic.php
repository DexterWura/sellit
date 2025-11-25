<?php
/**
 * Diagnostic Page - Check system status and configuration
 * Access this file directly: https://sellit.zimadsense.com/diagnostic.php
 */

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);

// Set timezone
date_default_timezone_set('UTC');

$diagnostics = [];
$errors = [];
$warnings = [];

// 1. PHP Version Check
$diagnostics['php_version'] = [
    'label' => 'PHP Version',
    'value' => phpversion(),
    'status' => version_compare(phpversion(), '7.4.0', '>=') ? 'ok' : 'error',
    'message' => version_compare(phpversion(), '7.4.0', '>=') ? 'PHP version is compatible' : 'PHP 7.4+ required'
];

// 2. Required PHP Extensions
$required_extensions = ['pdo', 'pdo_mysql', 'mbstring', 'openssl', 'json', 'curl', 'fileinfo', 'gd', 'zip'];
$missing_extensions = [];
foreach ($required_extensions as $ext) {
    if (!extension_loaded($ext)) {
        $missing_extensions[] = $ext;
    }
}
$diagnostics['php_extensions'] = [
    'label' => 'PHP Extensions',
    'value' => empty($missing_extensions) ? 'All required extensions loaded' : 'Missing: ' . implode(', ', $missing_extensions),
    'status' => empty($missing_extensions) ? 'ok' : 'error',
    'message' => empty($missing_extensions) ? 'All extensions available' : 'Some extensions are missing'
];

// 3. Check if Laravel bootstrap exists
$bootstrap_path = __DIR__ . '/../bootstrap/app.php';
$diagnostics['laravel_bootstrap'] = [
    'label' => 'Laravel Bootstrap',
    'value' => file_exists($bootstrap_path) ? 'Found' : 'Not Found',
    'status' => file_exists($bootstrap_path) ? 'ok' : 'error',
    'path' => $bootstrap_path
];

// 4. Check .env file
$env_path = __DIR__ . '/../.env';
$diagnostics['env_file'] = [
    'label' => '.env File',
    'value' => file_exists($env_path) ? 'Found' : 'Not Found',
    'status' => file_exists($env_path) ? 'ok' : 'error',
    'path' => $env_path
];

// 5. Check storage/logs directory
$logs_path = __DIR__ . '/../storage/logs';
$logs_writable = is_dir($logs_path) && is_writable($logs_path);
$diagnostics['logs_directory'] = [
    'label' => 'Logs Directory',
    'value' => $logs_writable ? 'Writable' : 'Not Writable or Missing',
    'status' => $logs_writable ? 'ok' : 'error',
    'path' => $logs_path,
    'permissions' => is_dir($logs_path) ? substr(sprintf('%o', fileperms($logs_path)), -4) : 'N/A'
];

// 6. Check storage directory permissions
$storage_path = __DIR__ . '/../storage';
$storage_writable = is_dir($storage_path) && is_writable($storage_path);
$diagnostics['storage_directory'] = [
    'label' => 'Storage Directory',
    'value' => $storage_writable ? 'Writable' : 'Not Writable',
    'status' => $storage_writable ? 'ok' : 'error',
    'path' => $storage_path,
    'permissions' => is_dir($storage_path) ? substr(sprintf('%o', fileperms($storage_path)), -4) : 'N/A'
];

// 7. Try to load Laravel and check database
$database_status = 'Not Tested';
$database_error = null;
if (file_exists($bootstrap_path)) {
    try {
        // Try to bootstrap Laravel
        require_once $bootstrap_path;
        $app = require_once __DIR__ . '/../bootstrap/app.php';
        
        // Try database connection
        try {
            $db = $app->make('db');
            $db->connection()->getPdo();
            $database_status = 'Connected';
            $db_error = null;
        } catch (\Exception $e) {
            $database_status = 'Connection Failed';
            $database_error = $e->getMessage();
        }
    } catch (\Exception $e) {
        $database_status = 'Laravel Bootstrap Failed';
        $database_error = $e->getMessage();
    }
}

$diagnostics['database'] = [
    'label' => 'Database Connection',
    'value' => $database_status,
    'status' => $database_status === 'Connected' ? 'ok' : 'error',
    'error' => $database_error
];

// 8. Check if vendor directory exists (Composer dependencies)
$vendor_path = __DIR__ . '/../vendor';
$diagnostics['vendor_directory'] = [
    'label' => 'Composer Dependencies',
    'value' => is_dir($vendor_path) ? 'Installed' : 'Not Installed',
    'status' => is_dir($vendor_path) ? 'ok' : 'error',
    'path' => $vendor_path
];

// 9. Check if .env has APP_KEY
if (file_exists($env_path)) {
    $env_content = file_get_contents($env_path);
    $has_app_key = strpos($env_content, 'APP_KEY=') !== false && strpos($env_content, 'APP_KEY=base64:') !== false;
    $diagnostics['app_key'] = [
        'label' => 'APP_KEY in .env',
        'value' => $has_app_key ? 'Set' : 'Not Set',
        'status' => $has_app_key ? 'ok' : 'warning',
        'message' => $has_app_key ? 'Application key is set' : 'Run: php artisan key:generate'
    ];
}

// 10. Check recent log file
$log_file = $logs_path . '/laravel.log';
$diagnostics['log_file'] = [
    'label' => 'Log File',
    'value' => file_exists($log_file) ? 'Exists (' . number_format(filesize($log_file)) . ' bytes)' : 'Not Found',
    'status' => file_exists($log_file) ? 'ok' : 'warning',
    'path' => $log_file,
    'readable' => file_exists($log_file) && is_readable($log_file) ? 'Yes' : 'No'
];

// 11. Test writing to log
$test_log_write = false;
if ($logs_writable) {
    $test_file = $logs_path . '/test_write_' . time() . '.txt';
    if (@file_put_contents($test_file, 'test') !== false) {
        $test_log_write = true;
        @unlink($test_file);
    }
}
$diagnostics['log_write_test'] = [
    'label' => 'Log Write Test',
    'value' => $test_log_write ? 'Success' : 'Failed',
    'status' => $test_log_write ? 'ok' : 'error',
    'message' => $test_log_write ? 'Can write to logs directory' : 'Cannot write to logs directory'
];

// 12. Check if migrations have been run (check for listing_type column)
$migration_status = 'Not Tested';
if (file_exists($bootstrap_path)) {
    try {
        require_once $bootstrap_path;
        $app = require_once __DIR__ . '/../bootstrap/app.php';
        try {
            $db = $app->make('db');
            $schema = $db->getDoctrineSchemaManager();
            $columns = $schema->listTableColumns('domain_posts');
            $has_listing_type = isset($columns['listing_type']);
            $migration_status = $has_listing_type ? 'Migrations Run (listing_type column exists)' : 'Migrations Not Run (listing_type column missing)';
        } catch (\Exception $e) {
            $migration_status = 'Could not check: ' . $e->getMessage();
        }
    } catch (\Exception $e) {
        $migration_status = 'Laravel not bootstrapped';
    }
}
$diagnostics['migrations'] = [
    'label' => 'Database Migrations',
    'value' => $migration_status,
    'status' => strpos($migration_status, 'exists') !== false ? 'ok' : 'warning'
];

// 13. Server Information
$diagnostics['server_info'] = [
    'label' => 'Server Information',
    'value' => [
        'Server Software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
        'Document Root' => $_SERVER['DOCUMENT_ROOT'] ?? 'Unknown',
        'Script Path' => __FILE__,
        'Current User' => get_current_user(),
        'PHP SAPI' => php_sapi_name(),
        'Memory Limit' => ini_get('memory_limit'),
        'Max Execution Time' => ini_get('max_execution_time'),
        'Upload Max Filesize' => ini_get('upload_max_filesize'),
        'Post Max Size' => ini_get('post_max_size'),
    ]
];

// 14. Recent PHP Errors
$error_log_path = ini_get('error_log');
$diagnostics['php_error_log'] = [
    'label' => 'PHP Error Log',
    'value' => $error_log_path ?: 'Not configured',
    'path' => $error_log_path
];

// Count statuses
$ok_count = 0;
$error_count = 0;
$warning_count = 0;
foreach ($diagnostics as $diag) {
    if (isset($diag['status'])) {
        if ($diag['status'] === 'ok') $ok_count++;
        elseif ($diag['status'] === 'error') $error_count++;
        elseif ($diag['status'] === 'warning') $warning_count++;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Diagnostic - SellIt</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: #f5f5f5;
            padding: 20px;
            line-height: 1.6;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 30px;
        }
        h1 {
            color: #333;
            margin-bottom: 10px;
            border-bottom: 3px solid #007bff;
            padding-bottom: 10px;
        }
        .summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin: 20px 0;
        }
        .summary-box {
            padding: 20px;
            border-radius: 6px;
            text-align: center;
        }
        .summary-box.ok { background: #d4edda; color: #155724; }
        .summary-box.error { background: #f8d7da; color: #721c24; }
        .summary-box.warning { background: #fff3cd; color: #856404; }
        .summary-box h3 { font-size: 2em; margin-bottom: 5px; }
        .diagnostic-item {
            background: #f8f9fa;
            border-left: 4px solid #ddd;
            padding: 15px;
            margin: 15px 0;
            border-radius: 4px;
        }
        .diagnostic-item.ok { border-left-color: #28a745; }
        .diagnostic-item.error { border-left-color: #dc3545; }
        .diagnostic-item.warning { border-left-color: #ffc107; }
        .diagnostic-item h3 {
            color: #333;
            margin-bottom: 8px;
            font-size: 1.1em;
        }
        .diagnostic-item .value {
            color: #666;
            margin: 5px 0;
            font-family: 'Courier New', monospace;
            background: white;
            padding: 8px;
            border-radius: 4px;
        }
        .diagnostic-item .message {
            color: #888;
            font-size: 0.9em;
            margin-top: 5px;
        }
        .diagnostic-item .error-msg {
            color: #dc3545;
            background: #f8d7da;
            padding: 10px;
            border-radius: 4px;
            margin-top: 10px;
            font-family: 'Courier New', monospace;
            font-size: 0.85em;
        }
        .timestamp {
            color: #999;
            font-size: 0.9em;
            margin-top: 20px;
            text-align: center;
        }
        code {
            background: #f4f4f4;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 0.9em;
        }
        ul {
            margin-left: 20px;
            margin-top: 10px;
        }
        li {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 System Diagnostic Report</h1>
        <p style="color: #666; margin-bottom: 20px;">This page checks your system configuration and identifies potential issues.</p>
        
        <div class="summary">
            <div class="summary-box ok">
                <h3><?php echo $ok_count; ?></h3>
                <p>OK</p>
            </div>
            <div class="summary-box warning">
                <h3><?php echo $warning_count; ?></h3>
                <p>Warnings</p>
            </div>
            <div class="summary-box error">
                <h3><?php echo $error_count; ?></h3>
                <p>Errors</p>
            </div>
        </div>

        <?php foreach ($diagnostics as $key => $diag): ?>
            <div class="diagnostic-item <?php echo $diag['status'] ?? 'ok'; ?>">
                <h3><?php echo htmlspecialchars($diag['label']); ?></h3>
                
                <?php if (is_array($diag['value'])): ?>
                    <ul>
                        <?php foreach ($diag['value'] as $k => $v): ?>
                            <li><strong><?php echo htmlspecialchars($k); ?>:</strong> <?php echo htmlspecialchars($v); ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <div class="value"><?php echo htmlspecialchars($diag['value']); ?></div>
                <?php endif; ?>
                
                <?php if (isset($diag['message'])): ?>
                    <div class="message"><?php echo htmlspecialchars($diag['message']); ?></div>
                <?php endif; ?>
                
                <?php if (isset($diag['path'])): ?>
                    <div class="message"><strong>Path:</strong> <code><?php echo htmlspecialchars($diag['path']); ?></code></div>
                <?php endif; ?>
                
                <?php if (isset($diag['permissions'])): ?>
                    <div class="message"><strong>Permissions:</strong> <code><?php echo htmlspecialchars($diag['permissions']); ?></code></div>
                <?php endif; ?>
                
                <?php if (isset($diag['error'])): ?>
                    <div class="error-msg"><?php echo htmlspecialchars($diag['error']); ?></div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>

        <div class="timestamp">
            Generated: <?php echo date('Y-m-d H:i:s T'); ?>
        </div>
    </div>
</body>
</html>


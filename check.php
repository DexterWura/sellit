<?php
/**
 * Comprehensive Site Check
 * This will identify exactly what's wrong
 * Access: https://sellit.zimadsense.com/check.php
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

$checks = [];
$has_errors = false;

// Check 1: PHP Version
$php_version = PHP_VERSION;
$php_ok = version_compare($php_version, '7.4.0', '>=');
$checks[] = [
    'name' => 'PHP Version',
    'status' => $php_ok ? 'ok' : 'error',
    'message' => $php_version . ($php_ok ? ' (OK)' : ' (Need 7.4+)')
];
if (!$php_ok) $has_errors = true;

// Check 2: Core directory
$core_exists = is_dir(__DIR__ . '/core');
$checks[] = [
    'name' => 'Core Directory',
    'status' => $core_exists ? 'ok' : 'error',
    'message' => $core_exists ? 'Exists' : 'Missing'
];
if (!$core_exists) $has_errors = true;

// Check 3: Composer autoload
$autoload = __DIR__ . '/core/vendor/autoload.php';
$autoload_exists = file_exists($autoload);
$checks[] = [
    'name' => 'Composer Autoload',
    'status' => $autoload_exists ? 'ok' : 'error',
    'message' => $autoload_exists ? 'Found' : 'Missing - Run: composer install',
    'path' => $autoload
];
if (!$autoload_exists) $has_errors = true;

// Check 4: Bootstrap file
$bootstrap = __DIR__ . '/core/bootstrap/app.php';
$bootstrap_exists = file_exists($bootstrap);
$checks[] = [
    'name' => 'Bootstrap File',
    'status' => $bootstrap_exists ? 'ok' : 'error',
    'message' => $bootstrap_exists ? 'Found' : 'Missing',
    'path' => $bootstrap
];
if (!$bootstrap_exists) $has_errors = true;

// Check 5: .env file
$env = __DIR__ . '/core/.env';
$env_exists = file_exists($env);
$checks[] = [
    'name' => '.env File',
    'status' => $env_exists ? 'ok' : 'error',
    'message' => $env_exists ? 'Found' : 'Missing - Create from .env.example',
    'path' => $env
];
if (!$env_exists) $has_errors = true;

// Check 6: Storage directory
$storage = __DIR__ . '/core/storage';
$storage_exists = is_dir($storage);
$storage_writable = $storage_exists && is_writable($storage);
$checks[] = [
    'name' => 'Storage Directory',
    'status' => $storage_writable ? 'ok' : ($storage_exists ? 'warning' : 'error'),
    'message' => $storage_exists ? ($storage_writable ? 'Exists & Writable' : 'Exists but NOT Writable') : 'Missing',
    'path' => $storage
];
if (!$storage_writable) $has_errors = true;

// Check 7: Storage/logs
$logs = __DIR__ . '/core/storage/logs';
$logs_exists = is_dir($logs);
$logs_writable = $logs_exists && is_writable($logs);
$checks[] = [
    'name' => 'Logs Directory',
    'status' => $logs_writable ? 'ok' : ($logs_exists ? 'warning' : 'error'),
    'message' => $logs_exists ? ($logs_writable ? 'Exists & Writable' : 'Exists but NOT Writable') : 'Missing',
    'path' => $logs
];

// Check 8: Try loading autoload
if ($autoload_exists) {
    try {
        require_once $autoload;
        $checks[] = [
            'name' => 'Load Autoload',
            'status' => 'ok',
            'message' => 'Successfully loaded'
        ];
    } catch (Exception $e) {
        $checks[] = [
            'name' => 'Load Autoload',
            'status' => 'error',
            'message' => 'Failed: ' . $e->getMessage()
        ];
        $has_errors = true;
    }
}

// Check 9: Try loading bootstrap
if ($autoload_exists && $bootstrap_exists) {
    try {
        $app = require_once $bootstrap;
        $checks[] = [
            'name' => 'Load Bootstrap',
            'status' => 'ok',
            'message' => 'Successfully loaded Laravel app'
        ];
    } catch (Exception $e) {
        $checks[] = [
            'name' => 'Load Bootstrap',
            'status' => 'error',
            'message' => 'Failed: ' . $e->getMessage(),
            'details' => 'File: ' . $e->getFile() . ' Line: ' . $e->getLine()
        ];
        $has_errors = true;
    } catch (Error $e) {
        $checks[] = [
            'name' => 'Load Bootstrap',
            'status' => 'error',
            'message' => 'Fatal Error: ' . $e->getMessage(),
            'details' => 'File: ' . $e->getFile() . ' Line: ' . $e->getLine()
        ];
        $has_errors = true;
    }
}

// Check 10: Database config (if .env exists)
if ($env_exists) {
    $env_content = file_get_contents($env);
    $has_db = strpos($env_content, 'DB_CONNECTION') !== false;
    $checks[] = [
        'name' => 'Database Config in .env',
        'status' => $has_db ? 'ok' : 'warning',
        'message' => $has_db ? 'Found' : 'Missing DB configuration'
    ];
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Site Check</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f5f5f5;
            padding: 20px;
        }
        .container {
            max-width: 900px;
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
        .check-item {
            padding: 15px;
            margin: 10px 0;
            border-left: 4px solid #ddd;
            background: #f8f9fa;
            border-radius: 4px;
        }
        .check-item.ok { border-left-color: #28a745; }
        .check-item.warning { border-left-color: #ffc107; }
        .check-item.error { border-left-color: #dc3545; }
        .status {
            font-weight: bold;
            font-size: 1.1em;
            margin-bottom: 5px;
        }
        .ok .status { color: #28a745; }
        .warning .status { color: #ffc107; }
        .error .status { color: #dc3545; }
        .message { color: #666; margin: 5px 0; }
        .path { color: #999; font-size: 0.9em; font-family: monospace; margin-top: 5px; }
        .details { color: #dc3545; margin-top: 5px; font-size: 0.9em; }
        .summary {
            background: <?php echo $has_errors ? '#f8d7da' : '#d4edda'; ?>;
            color: <?php echo $has_errors ? '#721c24' : '#155724'; ?>;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
            font-size: 1.1em;
        }
        code {
            background: #f4f4f4;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Site Diagnostic Check</h1>
        
        <div class="summary">
            <?php if ($has_errors): ?>
                <strong>❌ Issues Found!</strong> Please fix the errors below.
            <?php else: ?>
                <strong>✅ All Checks Passed!</strong> Your site should be working.
            <?php endif; ?>
        </div>
        
        <?php foreach ($checks as $check): ?>
            <div class="check-item <?php echo $check['status']; ?>">
                <div class="status">
                    <?php
                    if ($check['status'] === 'ok') echo '✅';
                    elseif ($check['status'] === 'warning') echo '⚠️';
                    else echo '❌';
                    ?>
                    <?php echo htmlspecialchars($check['name']); ?>
                </div>
                <div class="message"><?php echo htmlspecialchars($check['message']); ?></div>
                <?php if (isset($check['path'])): ?>
                    <div class="path"><?php echo htmlspecialchars($check['path']); ?></div>
                <?php endif; ?>
                <?php if (isset($check['details'])): ?>
                    <div class="details"><?php echo htmlspecialchars($check['details']); ?></div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
        
        <div style="margin-top: 30px; padding: 15px; background: #e7f3ff; border-radius: 5px;">
            <h3>Common Fixes:</h3>
            <ul style="margin-left: 20px; margin-top: 10px;">
                <li>If <strong>Composer Autoload</strong> is missing: Run <code>cd core && composer install</code></li>
                <li>If <strong>.env</strong> is missing: Copy <code>core/.env.example</code> to <code>core/.env</code> and configure it</li>
                <li>If <strong>Storage</strong> is not writable: Run <code>chmod -R 775 core/storage</code></li>
                <li>If <strong>Bootstrap fails</strong>: Check the error message above and fix the specific issue</li>
            </ul>
        </div>
        
        <p style="margin-top: 20px; color: #999; font-size: 0.9em;">
            Check completed: <?php echo date('Y-m-d H:i:s'); ?>
        </p>
    </div>
</body>
</html>


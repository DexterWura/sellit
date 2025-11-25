<?php
/**
 * Simple Log Test - Test if logging works
 * Access: https://sellit.zimadsense.com/test-log.php
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);

$logs_path = __DIR__ . '/../storage/logs';
$log_file = $logs_path . '/laravel.log';
$test_log = $logs_path . '/test_' . date('Y-m-d') . '.log';

$results = [];

// Test 1: Check if logs directory exists
$results[] = [
    'test' => 'Logs Directory Exists',
    'status' => is_dir($logs_path) ? 'PASS' : 'FAIL',
    'path' => $logs_path
];

// Test 2: Check if logs directory is writable
$results[] = [
    'test' => 'Logs Directory Writable',
    'status' => is_writable($logs_path) ? 'PASS' : 'FAIL',
    'path' => $logs_path
];

// Test 3: Try to write to test log file
$test_message = "Test log entry at " . date('Y-m-d H:i:s') . "\n";
$write_success = @file_put_contents($test_log, $test_message, FILE_APPEND);
$results[] = [
    'test' => 'Write to Test Log File',
    'status' => $write_success !== false ? 'PASS' : 'FAIL',
    'message' => $write_success !== false ? "Wrote to: $test_log" : "Failed to write to: $test_log"
];

// Test 4: Try to write to Laravel log file
$laravel_message = "[" . date('Y-m-d H:i:s') . "] Testing Laravel log file\n";
$laravel_write = @file_put_contents($log_file, $laravel_message, FILE_APPEND);
$results[] = [
    'test' => 'Write to Laravel Log File',
    'status' => $laravel_write !== false ? 'PASS' : 'FAIL',
    'message' => $laravel_write !== false ? "Wrote to: $log_file" : "Failed to write to: $log_file"
];

// Test 5: Try error_log()
$error_log_test = @error_log("Test error_log() at " . date('Y-m-d H:i:s'));
$results[] = [
    'test' => 'PHP error_log() Function',
    'status' => $error_log_test ? 'PASS' : 'FAIL',
    'message' => $error_log_test ? 'error_log() works' : 'error_log() failed'
];

// Test 6: Check Laravel logging (if Laravel is available)
$laravel_log_test = 'NOT TESTED';
if (file_exists(__DIR__ . '/../bootstrap/app.php')) {
    try {
        require_once __DIR__ . '/../bootstrap/app.php';
        $app = require_once __DIR__ . '/../bootstrap/app.php';
        \Illuminate\Support\Facades\Log::info('Test log from diagnostic page at ' . date('Y-m-d H:i:s'));
        $laravel_log_test = 'PASS - Check laravel.log file';
    } catch (\Exception $e) {
        $laravel_log_test = 'FAIL - ' . $e->getMessage();
    }
}
$results[] = [
    'test' => 'Laravel Log Facade',
    'status' => strpos($laravel_log_test, 'PASS') !== false ? 'PASS' : 'FAIL',
    'message' => $laravel_log_test
];

?>
<!DOCTYPE html>
<html>
<head>
    <title>Log Test</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; }
        h1 { color: #333; }
        .test-item { padding: 15px; margin: 10px 0; border-left: 4px solid #ddd; background: #f8f9fa; }
        .test-item.pass { border-left-color: #28a745; }
        .test-item.fail { border-left-color: #dc3545; }
        .status { font-weight: bold; font-size: 1.2em; }
        .pass .status { color: #28a745; }
        .fail .status { color: #dc3545; }
        code { background: #f4f4f4; padding: 2px 6px; border-radius: 3px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>📝 Log Test Results</h1>
        <p>Testing various logging methods to identify why logs aren't being written.</p>
        
        <?php foreach ($results as $result): ?>
            <div class="test-item <?php echo strtolower($result['status']); ?>">
                <div class="status"><?php echo $result['status']; ?></div>
                <div><strong><?php echo htmlspecialchars($result['test']); ?></strong></div>
                <?php if (isset($result['path'])): ?>
                    <div><code><?php echo htmlspecialchars($result['path']); ?></code></div>
                <?php endif; ?>
                <?php if (isset($result['message'])): ?>
                    <div style="margin-top: 5px; color: #666;"><?php echo htmlspecialchars($result['message']); ?></div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
        
        <div style="margin-top: 30px; padding: 15px; background: #e7f3ff; border-radius: 4px;">
            <h3>Next Steps:</h3>
            <ul>
                <li>Check <code>storage/logs/laravel.log</code> for Laravel logs</li>
                <li>Check <code>storage/logs/test_<?php echo date('Y-m-d'); ?>.log</code> for test logs</li>
                <li>Check PHP error log (usually in server logs or php.ini error_log setting)</li>
                <li>If all tests fail, check file permissions on <code>storage/logs/</code> directory</li>
            </ul>
        </div>
        
        <div style="margin-top: 20px; color: #999; font-size: 0.9em;">
            Test completed at: <?php echo date('Y-m-d H:i:s'); ?>
        </div>
    </div>
</body>
</html>


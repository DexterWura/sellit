<?php
/**
 * Run Composer Install
 * This script attempts to run composer install via PHP
 * Access: https://sellit.zimadsense.com/run-composer.php
 * 
 * WARNING: This may not work on all servers. SSH is preferred.
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);
set_time_limit(300); // 5 minutes
ini_set('memory_limit', '512M');

$core_path = __DIR__ . '/core';
$vendor_path = $core_path . '/vendor';
$autoload_path = $vendor_path . '/autoload.php';

?>
<!DOCTYPE html>
<html>
<head>
    <title>Run Composer Install</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f5f5f5; }
        .container { max-width: 900px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; }
        .success { color: #28a745; }
        .error { color: #dc3545; }
        .warning { color: #ffc107; }
        .info { background: #e7f3ff; padding: 15px; border-radius: 5px; margin: 15px 0; }
        pre { background: #f4f4f4; padding: 15px; border-radius: 5px; overflow-x: auto; max-height: 400px; overflow-y: auto; }
        code { background: #f4f4f4; padding: 2px 6px; border-radius: 3px; }
        .btn { display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; margin: 10px 5px 0 0; }
        .btn:hover { background: #0056b3; }
        .btn-danger { background: #dc3545; }
        .btn-danger:hover { background: #c82333; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 Run Composer Install</h1>
        
        <?php
        // Check if already installed
        if (file_exists($autoload_path)) {
            echo '<div class="info"><strong class="success">✅ Composer dependencies are already installed!</strong></div>';
            echo '<p>The autoload.php file exists at: <code>' . htmlspecialchars($autoload_path) . '</code></p>';
            echo '<p><a href="check.php" class="btn">Run Diagnostic Again</a></p>';
            exit;
        }
        
        // Check if vendor directory exists (might be incomplete)
        if (is_dir($vendor_path)) {
            echo '<div class="info"><strong class="warning">⚠️ Vendor directory exists but autoload.php is missing.</strong></div>';
            echo '<p>This usually means composer install didn\'t complete. We\'ll try to run it again.</p>';
        }
        
        // Check for composer
        $composer_found = false;
        $composer_cmd = '';
        $composer_locations = [
            'composer',
            '/usr/local/bin/composer',
            '/usr/bin/composer',
            '/opt/cpanel/ea-php82/root/usr/bin/composer',
            '/opt/cpanel/ea-php81/root/usr/bin/composer',
            '/opt/cpanel/ea-php80/root/usr/bin/composer',
        ];
        
        echo '<h3>Step 1: Finding Composer</h3>';
        foreach ($composer_locations as $cmd) {
            exec($cmd . ' --version 2>&1', $output, $return_var);
            if ($return_var === 0) {
                $composer_found = true;
                $composer_cmd = $cmd;
                echo '<p class="success">✅ Found composer at: <code>' . htmlspecialchars($cmd) . '</code></p>';
                echo '<p>Version: ' . htmlspecialchars(implode("\n", $output)) . '</p>';
                break;
            }
        }
        
        if (!$composer_found) {
            // Try composer.phar in core directory
            if (file_exists($core_path . '/composer.phar')) {
                $composer_found = true;
                $composer_cmd = 'php ' . escapeshellarg($core_path . '/composer.phar');
                echo '<p class="success">✅ Found composer.phar in core directory</p>';
            } else {
                echo '<div class="info"><strong class="error">❌ Composer not found on server.</strong></div>';
                echo '<h3>Manual Installation Required</h3>';
                echo '<p>You need to install composer dependencies manually via SSH:</p>';
                echo '<pre>cd ' . htmlspecialchars($core_path) . '
composer install --no-dev --optimize-autoloader</pre>';
                echo '<p><strong>Or install composer first:</strong></p>';
                echo '<pre>cd ' . htmlspecialchars($core_path) . '
php -r "copy(\'https://getcomposer.org/installer\', \'composer-setup.php\');"
php composer-setup.php
php composer.phar install --no-dev --optimize-autoloader</pre>';
                exit;
            }
        }
        
        // Run composer install
        echo '<h3>Step 2: Running Composer Install</h3>';
        echo '<p>This may take a few minutes. Please wait...</p>';
        echo '<pre id="output">Running: ' . htmlspecialchars($composer_cmd) . ' install --no-dev --optimize-autoloader --no-interaction
';
        
        // Flush output
        if (ob_get_level()) {
            ob_end_flush();
        }
        flush();
        
        $full_cmd = 'cd ' . escapeshellarg($core_path) . ' && ' . $composer_cmd . ' install --no-dev --optimize-autoloader --no-interaction 2>&1';
        
        // Execute and capture output in real-time
        $descriptorspec = array(
            0 => array("pipe", "r"),
            1 => array("pipe", "w"),
            2 => array("pipe", "w")
        );
        
        $process = proc_open($full_cmd, $descriptorspec, $pipes, $core_path);
        
        if (is_resource($process)) {
            fclose($pipes[0]);
            
            $output = '';
            $error_output = '';
            
            // Read output
            while (!feof($pipes[1])) {
                $line = fgets($pipes[1]);
                if ($line !== false) {
                    echo htmlspecialchars($line);
                    $output .= $line;
                    flush();
                }
            }
            
            // Read errors
            while (!feof($pipes[2])) {
                $line = fgets($pipes[2]);
                if ($line !== false) {
                    $error_output .= $line;
                }
            }
            
            fclose($pipes[1]);
            fclose($pipes[2]);
            
            $return_value = proc_close($process);
            
            echo '</pre>';
            
            if ($return_value === 0) {
                // Check if autoload.php was created
                if (file_exists($autoload_path)) {
                    echo '<div class="info"><strong class="success">✅ SUCCESS! Composer install completed successfully!</strong></div>';
                    echo '<p>The autoload.php file has been created at: <code>' . htmlspecialchars($autoload_path) . '</code></p>';
                    echo '<p><a href="check.php" class="btn">Verify Installation</a> <a href="../" class="btn">Go to Website</a></p>';
                } else {
                    echo '<div class="info"><strong class="warning">⚠️ Composer completed but autoload.php is still missing.</strong></div>';
                    echo '<p>This might be a permissions issue. Check the output above for errors.</p>';
                    if (!empty($error_output)) {
                        echo '<h4>Error Output:</h4><pre>' . htmlspecialchars($error_output) . '</pre>';
                    }
                }
            } else {
                echo '<div class="info"><strong class="error">❌ Composer install failed with exit code: ' . $return_value . '</strong></div>';
                if (!empty($error_output)) {
                    echo '<h4>Error Output:</h4><pre>' . htmlspecialchars($error_output) . '</pre>';
                }
                echo '<p><strong>Try running manually via SSH:</strong></p>';
                echo '<pre>cd ' . htmlspecialchars($core_path) . '
' . htmlspecialchars($composer_cmd) . ' install --no-dev --optimize-autoloader</pre>';
            }
        } else {
            echo '</pre>';
            echo '<div class="info"><strong class="error">❌ Failed to start composer process.</strong></div>';
            echo '<p>This might be a server configuration issue. Please run composer install manually via SSH.</p>';
        }
        ?>
        
        <div class="info" style="margin-top: 30px;">
            <h3>Alternative: Manual Installation via SSH</h3>
            <p>If the automatic installation doesn't work, connect to your server via SSH and run:</p>
            <pre>cd <?php echo htmlspecialchars($core_path); ?>
composer install --no-dev --optimize-autoloader</pre>
            <p><strong>If composer is not installed:</strong></p>
            <pre>cd <?php echo htmlspecialchars($core_path); ?>
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php
php composer.phar install --no-dev --optimize-autoloader</pre>
        </div>
    </div>
</body>
</html>


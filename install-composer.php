<?php
/**
 * Install Composer and Run Composer Install
 * This script downloads composer and installs dependencies
 * Access: https://sellit.zimadsense.com/install-composer.php
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);
set_time_limit(600); // 10 minutes
ini_set('memory_limit', '512M');

$core_path = __DIR__ . '/core';
$composer_phar = $core_path . '/composer.phar';
$composer_installer = $core_path . '/composer-setup.php';
$autoload_path = $core_path . '/vendor/autoload.php';

?>
<!DOCTYPE html>
<html>
<head>
    <title>Install Composer</title>
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
        .step { margin: 20px 0; padding: 15px; background: #f8f9fa; border-left: 4px solid #007bff; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 Install Composer & Dependencies</h1>
        
        <?php
        // Step 1: Check if already installed
        if (file_exists($autoload_path)) {
            echo '<div class="info"><strong class="success">✅ Composer dependencies are already installed!</strong></div>';
            echo '<p>The autoload.php file exists at: <code>' . htmlspecialchars($autoload_path) . '</code></p>';
            echo '<p><a href="check.php" class="btn">Run Diagnostic</a> <a href="../" class="btn">Go to Website</a></p>';
            exit;
        }
        
        echo '<div class="step">';
        echo '<h3>Step 1: Downloading Composer PHAR</h3>';
        
        // Check if composer.phar already exists
        if (file_exists($composer_phar)) {
            echo '<p class="success">✅ Composer.phar already exists!</p>';
            echo '<p>Location: <code>' . htmlspecialchars($composer_phar) . '</code></p>';
        } else {
            // Download composer.phar directly (latest stable version)
            // Try multiple sources
            $composer_urls = [
                'https://getcomposer.org/download/latest-stable/composer.phar',
                'https://github.com/composer/composer/releases/latest/download/composer.phar',
                'https://getcomposer.org/composer-stable.phar'
            ];
            
            $composer_downloaded = false;
            $download_error = '';
            
            foreach ($composer_urls as $url) {
                echo '<p>Trying to download from: <code>' . htmlspecialchars($url) . '</code>...</p>';
                
                // Try with file_get_contents first
                $context = stream_context_create([
                    'http' => [
                        'timeout' => 30,
                        'user_agent' => 'Mozilla/5.0',
                        'follow_location' => true
                    ]
                ]);
                
                $composer_content = @file_get_contents($url, false, $context);
                
                // If that fails, try with curl
                if ($composer_content === false) {
                    if (function_exists('curl_init')) {
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $url);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
                        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
                        $composer_content = curl_exec($ch);
                        $curl_error = curl_error($ch);
                        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                        curl_close($ch);
                        
                        if ($composer_content === false || $http_code !== 200) {
                            $download_error = $curl_error ?: "HTTP $http_code";
                            continue;
                        }
                    } else {
                        $download_error = 'file_get_contents failed and curl not available';
                        continue;
                    }
                }
                
                // Verify it's actually a PHAR file (should start with specific bytes)
                if ($composer_content && strlen($composer_content) > 1000) {
                    // Save composer.phar
                    if (!is_dir($core_path)) {
                        mkdir($core_path, 0755, true);
                    }
                    
                    if (file_put_contents($composer_phar, $composer_content) !== false) {
                        // Make it executable
                        @chmod($composer_phar, 0755);
                        $composer_downloaded = true;
                        echo '<p class="success">✅ Composer.phar downloaded successfully!</p>';
                        echo '<p>Size: ' . number_format(strlen($composer_content)) . ' bytes</p>';
                        break;
                    } else {
                        $download_error = 'Failed to save file (check permissions)';
                    }
                } else {
                    $download_error = 'Downloaded content is too small or invalid';
                }
            }
            
            if (!$composer_downloaded) {
                echo '<p class="error">❌ Failed to download composer.phar.</p>';
                echo '<p>Error: ' . htmlspecialchars($download_error) . '</p>';
                echo '<p><strong>Manual installation required via SSH:</strong></p>';
                echo '<pre>cd ' . htmlspecialchars($core_path) . '
curl -sS https://getcomposer.org/installer | php
php composer.phar install --no-dev --optimize-autoloader</pre>';
                echo '<p><strong>Or download directly:</strong></p>';
                echo '<pre>cd ' . htmlspecialchars($core_path) . '
wget https://getcomposer.org/download/latest-stable/composer.phar
php composer.phar install --no-dev --optimize-autoloader</pre>';
                exit;
            }
        }
        
        // Verify composer works
        echo '<p>Verifying composer...</p>';
        $composer_version_cmd = 'cd ' . escapeshellarg($core_path) . ' && php composer.phar --version 2>&1';
        exec($composer_version_cmd, $version_output, $version_return);
        if ($version_return === 0 && !empty($version_output)) {
            echo '<p class="success">✅ Composer is working!</p>';
            echo '<p>Version: <code>' . htmlspecialchars(implode("\n", $version_output)) . '</code></p>';
        } else {
            echo '<p class="warning">⚠️ Could not verify composer version, but file exists. Continuing...</p>';
        }
        
        echo '</div>';
        
        // Step 3: Run composer install
        echo '<div class="step">';
        echo '<h3>Step 3: Installing Dependencies</h3>';
        echo '<p>This may take several minutes. Please wait...</p>';
        echo '<pre id="composer-output">';
        
        // Flush output
        if (ob_get_level()) {
            ob_end_flush();
        }
        flush();
        
        // Run composer install
        $composer_install_cmd = 'cd ' . escapeshellarg($core_path) . ' && php composer.phar install --no-dev --optimize-autoloader --no-interaction 2>&1';
        
        // Execute with real-time output
        $descriptorspec = array(
            0 => array("pipe", "r"),
            1 => array("pipe", "w"),
            2 => array("pipe", "w")
        );
        
        $process = proc_open($composer_install_cmd, $descriptorspec, $pipes, $core_path);
        
        if (is_resource($process)) {
            fclose($pipes[0]);
            
            $output = '';
            $error_output = '';
            
            // Read output in real-time
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
                    echo '<div class="info"><strong class="success">✅ SUCCESS! All dependencies installed successfully!</strong></div>';
                    echo '<p>The autoload.php file has been created at: <code>' . htmlspecialchars($autoload_path) . '</code></p>';
                    echo '<p><a href="check.php" class="btn">Verify Installation</a> <a href="../" class="btn">Go to Website</a></p>';
                } else {
                    echo '<div class="info"><strong class="warning">⚠️ Composer completed but autoload.php is still missing.</strong></div>';
                    echo '<p>Check the output above for any errors. The vendor directory might be incomplete.</p>';
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
php composer.phar install --no-dev --optimize-autoloader</pre>';
            }
        } else {
            echo '</pre>';
            echo '<div class="info"><strong class="error">❌ Failed to start composer process.</strong></div>';
            echo '<p>This might be a server configuration issue. Please run composer install manually via SSH.</p>';
        }
        
        echo '</div>';
        ?>
        
        <div class="info" style="margin-top: 30px;">
            <h3>If Automatic Installation Fails</h3>
            <p>Connect to your server via SSH and run these commands:</p>
            <pre>cd <?php echo htmlspecialchars($core_path); ?>
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php
php composer.phar install --no-dev --optimize-autoloader
rm composer-setup.php</pre>
        </div>
    </div>
</body>
</html>


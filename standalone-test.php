<?php
/**
 * Standalone Test - No dependencies, no Laravel, no includes
 * This should work even if everything else is broken
 * Access: https://sellit.zimadsense.com/standalone-test.php
 */
?>
<!DOCTYPE html>
<html>
<head>
    <title>Standalone Test</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f0f0f0; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; }
        .success { color: green; font-size: 24px; }
        .info { background: #e7f3ff; padding: 15px; border-radius: 5px; margin: 10px 0; }
        code { background: #f4f4f4; padding: 2px 6px; border-radius: 3px; }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="success">✅ Server is Working!</h1>
        <p>If you can see this page, your web server and PHP are functioning correctly.</p>
        
        <div class="info">
            <h3>Basic Information:</h3>
            <ul>
                <li><strong>PHP Version:</strong> <?php echo PHP_VERSION; ?></li>
                <li><strong>Server Time:</strong> <?php echo date('Y-m-d H:i:s'); ?></li>
                <li><strong>Document Root:</strong> <code><?php echo $_SERVER['DOCUMENT_ROOT'] ?? 'Not set'; ?></code></li>
                <li><strong>Script Location:</strong> <code><?php echo __FILE__; ?></code></li>
            </ul>
        </div>
        
        <div class="info">
            <h3>File System Check:</h3>
            <ul>
                <li><strong>Current Directory:</strong> <code><?php echo getcwd(); ?></code></li>
                <li><strong>index.php exists:</strong> <?php echo file_exists(__DIR__ . '/index.php') ? '✅ Yes' : '❌ No'; ?></li>
                <li><strong>core/ directory exists:</strong> <?php echo is_dir(__DIR__ . '/core') ? '✅ Yes' : '❌ No'; ?></li>
            </ul>
        </div>
        
        <div class="info">
            <h3>Next Steps:</h3>
            <p>If you can see this page but other pages don't work, the issue is likely:</p>
            <ol>
                <li>Laravel files are missing or corrupted</li>
                <li>Composer dependencies not installed (run <code>composer install</code> in core directory)</li>
                <li>Database connection issues</li>
                <li>Missing .env file in core directory</li>
            </ol>
        </div>
    </div>
</body>
</html>


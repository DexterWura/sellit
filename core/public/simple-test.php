<?php
// Ultra-simple test - no functions, no includes
?>
<!DOCTYPE html>
<html>
<head>
    <title>Simple PHP Test</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f0f0f0; }
        .box { background: white; padding: 20px; border-radius: 5px; margin: 10px 0; }
        .success { border-left: 4px solid green; }
        .error { border-left: 4px solid red; }
    </style>
</head>
<body>
    <h1>Simple PHP Test</h1>
    
    <div class="box success">
        <h2>✅ PHP is Working!</h2>
        <p>If you can see this page, PHP is executing.</p>
    </div>
    
    <div class="box">
        <h3>Basic PHP Info:</h3>
        <ul>
            <li><strong>PHP Version:</strong> <?php echo PHP_VERSION; ?></li>
            <li><strong>Server Time:</strong> <?php echo date('Y-m-d H:i:s'); ?></li>
            <li><strong>Document Root:</strong> <?php echo $_SERVER['DOCUMENT_ROOT'] ?? 'Not set'; ?></li>
            <li><strong>Script Path:</strong> <?php echo __FILE__; ?></li>
        </ul>
    </div>
    
    <div class="box">
        <h3>File System Checks:</h3>
        <ul>
            <li><strong>Current Directory:</strong> <?php echo getcwd(); ?></li>
            <li><strong>Script Directory:</strong> <?php echo __DIR__; ?></li>
            <li><strong>Parent Directory Exists:</strong> <?php echo is_dir(__DIR__ . '/..') ? 'Yes' : 'No'; ?></li>
            <li><strong>Storage Directory:</strong> <?php 
                $storage = __DIR__ . '/../storage';
                echo is_dir($storage) ? 'Exists' : 'Missing';
                echo ' (';
                echo is_writable($storage) ? 'Writable' : 'Not Writable';
                echo ')';
            ?></li>
        </ul>
    </div>
    
    <div class="box">
        <h3>PHP Extensions Check:</h3>
        <ul>
            <?php
            $exts = ['pdo', 'pdo_mysql', 'mbstring', 'openssl', 'json', 'curl'];
            foreach ($exts as $ext) {
                $loaded = extension_loaded($ext);
                echo '<li><strong>' . $ext . ':</strong> ' . ($loaded ? '✅ Loaded' : '❌ Missing') . '</li>';
            }
            ?>
        </ul>
    </div>
    
    <div class="box">
        <h3>Error Reporting:</h3>
        <ul>
            <li><strong>Error Reporting:</strong> <?php echo error_reporting(); ?></li>
            <li><strong>Display Errors:</strong> <?php echo ini_get('display_errors') ? 'On' : 'Off'; ?></li>
            <li><strong>Error Log:</strong> <?php echo ini_get('error_log') ?: 'Not set'; ?></li>
        </ul>
    </div>
    
    <div class="box">
        <h3>Try These URLs:</h3>
        <ul>
            <li><a href="info.txt">info.txt</a> - Plain text file test</li>
            <li><a href="index-test.html">index-test.html</a> - HTML file test</li>
            <li><a href="test.php">test.php</a> - Minimal PHP test</li>
        </ul>
    </div>
    
    <div class="box">
        <p><strong>Note:</strong> If you see this page, PHP is working. The 500 error on other pages might be due to:</p>
        <ul>
            <li>Missing Laravel files</li>
            <li>Database connection issues</li>
            <li>Missing .env file</li>
            <li>File permission issues</li>
        </ul>
    </div>
</body>
</html>


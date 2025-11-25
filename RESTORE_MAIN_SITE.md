# Restore Main Site - Emergency Guide

## What Happened
The diagnostic scripts and composer installation were intended only for the subdomain (`sellit.zimadsense.com`), but if both sites share the same document root, they may have affected the main site (`zimadsense.com`).

## Immediate Fixes Applied
1. ✅ Made `.htaccess` changes conditional (only apply to subdomain)
2. ✅ Made `index.php` error handling conditional (only apply to subdomain)

## If Main Site Still Broken

### Step 1: Check if Both Sites Share the Same Directory
If `zimadsense.com` and `sellit.zimadsense.com` share the same document root:
- They might share the same `core` directory
- Running `composer install` in one affects both

### Step 2: Restore Original index.php (If Needed)
If the main site still doesn't work, you may need to restore the original `index.php`. 

**Original Laravel index.php should look like:**
```php
<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

if (file_exists(__DIR__.'/core/storage/framework/maintenance.php')) {
    require __DIR__.'/core/storage/framework/maintenance.php';
}

require __DIR__.'/core/vendor/autoload.php';

$app = require_once __DIR__.'/core/bootstrap/app.php';

$kernel = $app->make(Kernel::class);

$response = tap($kernel->handle(
    $request = Request::capture()
))->send();

$kernel->terminate($request, $response);
```

### Step 3: Check Server Error Logs
Check your server's error logs:
- cPanel: Error Log section
- Apache: `/var/log/apache2/error.log` or `/var/log/httpd/error_log`
- Nginx: `/var/log/nginx/error.log`

### Step 4: Verify Vendor Directory
If both sites share the same `core` directory:
```bash
cd /path/to/shared/core
ls -la vendor/
```
If `vendor` is missing or incomplete, run:
```bash
composer install --no-dev --optimize-autoloader
```

### Step 5: Check File Permissions
```bash
chmod -R 755 core/vendor
chmod -R 775 core/storage
chmod -R 775 core/bootstrap/cache
```

### Step 6: Clear Laravel Cache
```bash
cd core
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

## Prevention: Separate the Sites
If both sites share the same directory, consider:
1. Moving the subdomain to its own directory
2. Using separate `core` directories for each site
3. Using separate `.env` files for each site

## Current Status
- ✅ `.htaccess` now only affects subdomain
- ✅ `index.php` now only shows detailed errors for subdomain
- ⚠️ Diagnostic PHP files in root are still accessible from both (but shouldn't break anything)

## Next Steps
1. Test the main site: `https://zimadsense.com/`
2. If still broken, check server error logs
3. If needed, restore original `index.php` from backup
4. Consider separating the two sites into different directories


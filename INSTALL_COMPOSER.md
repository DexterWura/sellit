# How to Install Composer Dependencies

## The Problem
The `vendor` directory is missing, which contains all PHP dependencies required by Laravel.

## Solution: Install Composer Dependencies

### Option 1: Using SSH (Recommended)

1. **Connect to your server via SSH**
   ```bash
   ssh your-username@sellit.zimadsense.com
   ```

2. **Navigate to the core directory**
   ```bash
   cd /path/to/your/site/core
   # or if you're in the root:
   cd core
   ```

3. **Run Composer Install**
   ```bash
   composer install --no-dev --optimize-autoloader
   ```
   
   - `--no-dev` - Skips development dependencies (faster, smaller)
   - `--optimize-autoloader` - Optimizes for production

4. **If composer is not installed**, install it first:
   ```bash
   php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
   php composer-setup.php
   php -r "unlink('composer-setup.php');"
   mv composer.phar /usr/local/bin/composer
   ```

### Option 2: Using cPanel Terminal

1. Log into cPanel
2. Find "Terminal" or "SSH Access"
3. Run the same commands as Option 1

### Option 3: Using cPanel File Manager + PHP Script

If you can't access SSH, you can try using the `composer-install.php` script I created (see below).

### Option 4: Upload vendor directory (if you have it locally)

If you have the `vendor` directory from a local installation:
1. Compress the `vendor` folder
2. Upload it to `core/vendor/`
3. Extract it on the server

## After Installation

Once `composer install` completes successfully:

1. **Set proper permissions:**
   ```bash
   chmod -R 755 core/storage
   chmod -R 755 core/bootstrap/cache
   ```

2. **Clear cache:**
   ```bash
   cd core
   php artisan config:clear
   php artisan cache:clear
   ```

3. **Check if .env exists:**
   - Make sure `core/.env` file exists
   - If not, copy from `core/.env.example`

4. **Run migrations (if needed):**
   ```bash
   cd core
   php artisan migrate
   ```

## Verify Installation

After installation, visit:
- `https://sellit.zimadsense.com/check.php` - Should show all green checkmarks
- `https://sellit.zimadsense.com/` - Should load your site

## Common Issues

### "composer: command not found"
- Install Composer first (see Option 1, step 4)
- Or use full path: `/usr/local/bin/composer` or `php composer.phar`

### "Permission denied"
- Make sure you have write permissions in the core directory
- Try: `chmod -R 775 core`

### "Memory limit exhausted"
- Increase PHP memory: `php -d memory_limit=512M composer install`

### "Could not find package"
- Check your `composer.json` is valid
- Try: `composer update` instead of `composer install`


# 500 Error Fix - Deployment Instructions for grabbaskets.com

## Issue
The homepage (index page) is showing a 500 Internal Server Error on production (grabbaskets.com) even though it works perfectly on local development.

## Root Cause Analysis
- **Local Testing**: All components working ✅
  - Database connection: Working
  - Category queries: Working (5 categories found)
  - Product queries: Working (5 products found)
  - Banner queries: Working (0 banners found)
  - HomeController: Working
  
- **Production Issue**: The error is likely due to:
  1. Cached configuration not updated after recent changes
  2. View cache with old errors
  3. Permissions issues on storage/logs directories
  4. PHP configuration differences between local and production

## Immediate Fix Steps

### Step 1: Deploy Updated Code to Hostinger
Since you're using Laravel Cloud for hosting, you need to push the changes:

```bash
# Commit all recent changes
git add .
git commit -m "Fix: Clear all caches and optimize for production deployment"
git push origin main
```

### Step 2: SSH into Hostinger Server
After pushing to git, access your Hostinger server via SSH or File Manager.

### Step 3: Clear All Caches on Production Server
Run these commands on your production server (via SSH or create a deployment script):

```bash
cd /path/to/your/application

# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan event:clear

# Rebuild optimized caches
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
php artisan optimize

# Ensure proper permissions
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Step 4: Verify .env File on Production
Make sure your production `.env` file has these settings:

```env
APP_ENV=production
APP_DEBUG=false  # Set to true temporarily to see the actual error
APP_URL=https://grabbaskets.com

# Session settings (already updated in config)
SESSION_DRIVER=file
SESSION_LIFETIME=720

# Timezone (already updated in config)
APP_TIMEZONE=Asia/Kolkata

# Database (already configured)
DB_CONNECTION=mysql
DB_HOST=db-a00cde8f-38c6-4d8e-8caf-dfdb13c5652e.ap-southeast-1.public.db.laravel.cloud
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=laravel
DB_PASSWORD=xHBa04pHpOi3g5axB3qMHJehmQD1Xp
```

**IMPORTANT**: Temporarily set `APP_DEBUG=true` to see the actual error message, then set it back to `false` after fixing.

### Step 5: Check Storage Permissions
On your production server, verify these directories are writable:

```bash
# Check permissions
ls -la storage/
ls -la storage/logs/
ls -la storage/framework/
ls -la storage/framework/sessions/
ls -la storage/framework/views/
ls -la storage/framework/cache/

# Fix if needed
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

### Step 6: Check PHP Version
Ensure your production server is running PHP 8.1 or higher:

```bash
php -v
```

### Step 7: Verify Database Connection from Production
Create a temporary test file on your server:

```php
<?php
// test-db.php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    DB::connection()->getPdo();
    echo "Database Connected Successfully\n";
    echo "Categories: " . App\Models\Category::count() . "\n";
    echo "Products: " . App\Models\Product::count() . "\n";
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
```

Access it: `https://grabbaskets.com/test-db.php` (then delete it after testing)

## Alternative: Quick Fix Script

Create this file on your production server as `fix-500.php` in the public directory:

```php
<?php
// fix-500.php
chdir(__DIR__ . '/..');

echo "Fixing 500 Error...\n\n";

// Clear caches
echo "1. Clearing config cache...\n";
exec('php artisan config:clear', $output1, $return1);
echo implode("\n", $output1) . "\n";

echo "\n2. Clearing application cache...\n";
exec('php artisan cache:clear', $output2, $return2);
echo implode("\n", $output2) . "\n";

echo "\n3. Clearing route cache...\n";
exec('php artisan route:clear', $output3, $return3);
echo implode("\n", $output3) . "\n";

echo "\n4. Clearing view cache...\n";
exec('php artisan view:clear', $output4, $return4);
echo implode("\n", $output4) . "\n";

echo "\n5. Rebuilding caches...\n";
exec('php artisan config:cache', $output5, $return5);
echo implode("\n", $output5) . "\n";

exec('php artisan route:cache', $output6, $return6);
echo implode("\n", $output6) . "\n";

echo "\n6. Optimizing...\n";
exec('php artisan optimize', $output7, $return7);
echo implode("\n", $output7) . "\n";

echo "\n✅ Done! Try accessing the homepage now.\n";
```

Access it: `https://grabbaskets.com/fix-500.php` (then delete it immediately after)

## Monitoring

After deployment, check the Laravel logs:

```bash
tail -f storage/logs/laravel.log
```

Or view the last 100 lines:

```bash
tail -100 storage/logs/laravel.log
```

## Expected Results

✅ Homepage loads successfully
✅ Categories display correctly
✅ Products display correctly  
✅ No 500 errors
✅ Session timeout set to 12 hours
✅ Timestamps show IST (Kolkata timezone)
✅ Mobile bottom navigation buttons work
✅ Payment verification working with extended session

## If Still Not Working

1. **Check Error Logs**: View `storage/logs/laravel.log` on production
2. **Enable Debug Mode**: Set `APP_DEBUG=true` in `.env` temporarily
3. **Check Hostinger Error Logs**: Access via cPanel → Error Logs
4. **Verify PHP Extensions**: Ensure all required extensions are installed
5. **Check .htaccess**: Ensure the `.htaccess` file is properly uploaded to `/public` directory

## Contact Support

If the issue persists, provide these details:
- Exact error message from `storage/logs/laravel.log`
- PHP version on production server
- Output of `php artisan about` command
- Screenshot of error with `APP_DEBUG=true`

---

**Last Updated**: 2025-01-20
**Status**: Ready for deployment

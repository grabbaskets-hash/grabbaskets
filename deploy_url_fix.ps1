# URGENT: Clear Cache on Hostinger Production Server
# This will fix all URLs showing grabbaskets.laravel.cloud instead of grabbaskets.com

Write-Host "=== HOSTINGER CACHE CLEAR DEPLOYMENT ===" -ForegroundColor Cyan
Write-Host ""

# Step 1: Prepare the cache clearing script
Write-Host "Step 1: Cache clearing script ready" -ForegroundColor Green
Write-Host "   File: clear_caches_hostinger.php" -ForegroundColor White
Write-Host ""

# Step 2: Show upload instructions
Write-Host "Step 2: UPLOAD TO HOSTINGER" -ForegroundColor Yellow
Write-Host "================================" -ForegroundColor Yellow
Write-Host ""
Write-Host "METHOD 1 - Hostinger File Manager (RECOMMENDED):" -ForegroundColor Cyan
Write-Host "1. Go to: https://hpanel.hostinger.com" -ForegroundColor White
Write-Host "2. Click on 'File Manager'" -ForegroundColor White
Write-Host "3. Navigate to: public_html/" -ForegroundColor White
Write-Host "4. Click 'Upload' button" -ForegroundColor White
Write-Host "5. Select: clear_caches_hostinger.php" -ForegroundColor White
Write-Host ""

Write-Host "METHOD 2 - FTP Upload:" -ForegroundColor Cyan
Write-Host "1. Connect to FTP: ftp.grabbaskets.com" -ForegroundColor White
Write-Host "2. Navigate to: /public_html/" -ForegroundColor White
Write-Host "3. Upload: clear_caches_hostinger.php" -ForegroundColor White
Write-Host ""

# Step 3: Show execution instructions
Write-Host "Step 3: RUN THE SCRIPT" -ForegroundColor Yellow
Write-Host "================================" -ForegroundColor Yellow
Write-Host ""
Write-Host "Open in browser:" -ForegroundColor White
Write-Host "   https://grabbaskets.com/clear_caches_hostinger.php" -ForegroundColor Cyan
Write-Host ""
Write-Host "Click: 'Clear All Caches Now' button" -ForegroundColor White
Write-Host ""

# Step 4: Security reminder
Write-Host "Step 4: DELETE THE FILE" -ForegroundColor Red
Write-Host "================================" -ForegroundColor Red
Write-Host "⚠️  IMPORTANT: Delete clear_caches_hostinger.php after running it!" -ForegroundColor Red
Write-Host ""

# Step 5: Verification
Write-Host "Step 5: VERIFY IT WORKED" -ForegroundColor Green
Write-Host "================================" -ForegroundColor Green
Write-Host "Visit these URLs and check they show 'grabbaskets.com' (not laravel.cloud):" -ForegroundColor White
Write-Host "   https://grabbaskets.com/buyer/category/2" -ForegroundColor Cyan
Write-Host "   https://grabbaskets.com/buyer/category/4" -ForegroundColor Cyan
Write-Host "   https://grabbaskets.com/buyer/category/5" -ForegroundColor Cyan
Write-Host ""

# Alternative: SSH method
Write-Host "ALTERNATIVE: If you have SSH access" -ForegroundColor Yellow
Write-Host "================================" -ForegroundColor Yellow
Write-Host "ssh your_username@your_server.hostinger.com" -ForegroundColor White
Write-Host "cd public_html" -ForegroundColor White
Write-Host "php artisan config:clear" -ForegroundColor White
Write-Host "php artisan cache:clear" -ForegroundColor White
Write-Host "php artisan route:clear" -ForegroundColor White
Write-Host "php artisan view:clear" -ForegroundColor White
Write-Host "php artisan optimize:clear" -ForegroundColor White
Write-Host ""

Write-Host "=== READY TO DEPLOY ===" -ForegroundColor Green
Write-Host ""
Write-Host "File location: $PWD\clear_caches_hostinger.php" -ForegroundColor Cyan
Write-Host ""
Write-Host "Press any key to open File Manager URL..." -ForegroundColor Yellow
$null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")

# Open Hostinger panel
Start-Process "https://hpanel.hostinger.com"

Write-Host ""
Write-Host "✅ Hostinger panel opened in browser" -ForegroundColor Green
Write-Host "   Follow the steps above to upload and run the script" -ForegroundColor White

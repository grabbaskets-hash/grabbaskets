@echo off
echo Starting deployment...

echo Creating storage directories...
if not exist "storage\framework\views" mkdir "storage\framework\views"
if not exist "storage\framework\cache\data" mkdir "storage\framework\cache\data"
if not exist "storage\framework\sessions" mkdir "storage\framework\sessions"
if not exist "storage\logs" mkdir "storage\logs"
if not exist "bootstrap\cache" mkdir "bootstrap\cache"

echo Clearing and caching configurations...
php artisan config:clear
php artisan config:cache

echo Clearing routes...
php artisan route:clear

echo Clearing application cache...
php artisan cache:clear

echo Clearing view cache...
if exist "storage\framework\views" (
    php artisan view:clear
) else (
    echo View cache directory doesn't exist, skipping view:clear
)

echo Installing dependencies...
composer install --optimize-autoloader --no-dev

echo Deployment completed successfully!
pause
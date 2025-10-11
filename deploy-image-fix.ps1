# Image Fix Deployment Script for PowerShell
Write-Host "Deploying image fixes..." -ForegroundColor Green

# Step 1: Clear caches
Write-Host "1. Clearing caches..." -ForegroundColor Yellow
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Step 2: Test a specific image URL
Write-Host "2. Testing image serving..." -ForegroundColor Yellow

$testUrl = "https://grabbaskets.laravel.cloud/serve-image/products/0Rc193BfOQ4pDAtqAYBc1SLfKm2E9Hoklwo643Fz.jpg"
Write-Host "Testing URL: $testUrl"

try {
    $response = Invoke-WebRequest -Uri $testUrl -ErrorAction SilentlyContinue
    if ($response.StatusCode -eq 200) {
        Write-Host "✅ SUCCESS! Serve route is working!" -ForegroundColor Green
    } else {
        Write-Host "❌ Status Code: $($response.StatusCode)" -ForegroundColor Red
    }
} catch {
    Write-Host "❌ FAILED! Error: $($_.Exception.Message)" -ForegroundColor Red
}

# Step 3: Test index page with images
Write-Host "3. Testing index page..." -ForegroundColor Yellow
$indexUrl = "https://grabbaskets.laravel.cloud/"
try {
    $indexResponse = Invoke-WebRequest -Uri $indexUrl -ErrorAction SilentlyContinue
    if ($indexResponse.StatusCode -eq 200) {
        Write-Host "✅ Index page accessible!" -ForegroundColor Green
    } else {
        Write-Host "❌ Index page error: $($indexResponse.StatusCode)" -ForegroundColor Red
    }
} catch {
    Write-Host "❌ Index page failed: $($_.Exception.Message)" -ForegroundColor Red
}

Write-Host "Deployment complete!" -ForegroundColor Green
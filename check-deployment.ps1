Write-Host "╔════════════════════════════════════════════════════════════╗" -ForegroundColor Cyan
Write-Host "║         CHECKING LARAVEL CLOUD DEPLOYMENT STATUS          ║" -ForegroundColor Cyan
Write-Host "╚════════════════════════════════════════════════════════════╝" -ForegroundColor Cyan
Write-Host ""

Write-Host "🔍 Testing if new code is deployed..." -ForegroundColor Yellow
Write-Host ""

# Test the diagnostic endpoint
$url = "https://grabbaskets.laravel.cloud/debug/image-display-test"
Write-Host "Testing URL: $url" -ForegroundColor Gray

try {
    $response = Invoke-WebRequest -Uri $url -UseBasicParsing -TimeoutSec 10
    $content = $response.Content
    
    Write-Host ""
    Write-Host "═══════════════════════════════════════════════" -ForegroundColor Cyan
    Write-Host "           DEPLOYMENT STATUS RESULT             " -ForegroundColor Cyan
    Write-Host "═══════════════════════════════════════════════" -ForegroundColor Cyan
    Write-Host ""
    
    if ($content -match "NEW CODE: Using /serve-image/") {
        Write-Host "✅ SUCCESS! NEW CODE IS DEPLOYED!" -ForegroundColor Green
        Write-Host ""
        Write-Host "The fix is live on production!" -ForegroundColor Green
        Write-Host ""
        Write-Host "Next steps:" -ForegroundColor Yellow
        Write-Host "1. Visit: https://grabbaskets.laravel.cloud/clear-caches-now.php" -ForegroundColor White
        Write-Host "2. Then go to: https://grabbaskets.laravel.cloud/seller/dashboard" -ForegroundColor White
        Write-Host "3. Images should now display correctly! ✅" -ForegroundColor Green
        Write-Host ""
        
    } elseif ($content -match "OLD CODE: Using R2 direct URL") {
        Write-Host "❌ OLD CODE STILL RUNNING" -ForegroundColor Red
        Write-Host ""
        Write-Host "Laravel Cloud has NOT deployed your changes yet." -ForegroundColor Red
        Write-Host ""
        Write-Host "IMMEDIATE ACTION REQUIRED:" -ForegroundColor Yellow
        Write-Host ""
        Write-Host "Option 1 (FASTEST - 5 minutes):" -ForegroundColor Cyan
        Write-Host "  Enable R2 public access in Cloudflare dashboard" -ForegroundColor White
        Write-Host "  See: FINAL_DIAGNOSIS_AND_SOLUTION.md → Option A" -ForegroundColor Gray
        Write-Host ""
        Write-Host "Option 2 (Requires access):" -ForegroundColor Cyan
        Write-Host "  Go to https://cloud.laravel.com" -ForegroundColor White
        Write-Host "  Click 'Deploy Now' button for grabbaskets project" -ForegroundColor White
        Write-Host ""
        
    } else {
        Write-Host "⚠️  CANNOT DETERMINE STATUS" -ForegroundColor Yellow
        Write-Host ""
        Write-Host "The page loaded but couldn't find expected text." -ForegroundColor Yellow
        Write-Host "Opening page in browser for manual inspection..." -ForegroundColor Gray
        Start-Process $url
    }
    
} catch {
    $statusCode = $_.Exception.Response.StatusCode.value__
    
    if ($statusCode -eq 404) {
        Write-Host "❌ DIAGNOSTIC ROUTE NOT FOUND (404)" -ForegroundColor Red
        Write-Host ""
        Write-Host "This confirms deployment has NOT happened yet." -ForegroundColor Red
        Write-Host "Even the diagnostic routes we added aren't there." -ForegroundColor Yellow
        Write-Host ""
        Write-Host "URGENT: You MUST manually trigger deployment!" -ForegroundColor Red
        Write-Host ""
        Write-Host "Go to: https://cloud.laravel.com" -ForegroundColor Cyan
        Write-Host "Find your 'grabbaskets' project" -ForegroundColor White
        Write-Host "Click the 'Deploy Now' or 'Redeploy' button" -ForegroundColor White
        Write-Host ""
    } else {
        Write-Host "❌ ERROR: HTTP $statusCode" -ForegroundColor Red
        Write-Host ""
        Write-Host "Could not access the diagnostic page." -ForegroundColor Yellow
        Write-Host "Error: $($_.Exception.Message)" -ForegroundColor Gray
    }
}

Write-Host ""
Write-Host "═══════════════════════════════════════════════" -ForegroundColor Cyan
Write-Host ""
Write-Host "For detailed instructions, see:" -ForegroundColor Yellow
Write-Host "  FINAL_DIAGNOSIS_AND_SOLUTION.md" -ForegroundColor White
Write-Host ""
Write-Host "Press any key to exit..."
$null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")

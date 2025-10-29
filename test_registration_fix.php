<?php
// Quick test for delivery partner registration debugging

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🧪 DELIVERY PARTNER REGISTRATION TEST\n";
echo "====================================\n\n";

try {
    // Test 1: Check if table and columns exist
    echo "1. Database Structure Check:\n";
    echo "   - delivery_partners table: " . (Schema::hasTable('delivery_partners') ? '✅' : '❌') . "\n";
    echo "   - delivery_partner_wallets table: " . (Schema::hasTable('delivery_partner_wallets') ? '✅' : '❌') . "\n";
    
    $columns = Schema::getColumnListing('delivery_partners');
    echo "   - registration_type column: " . (in_array('registration_type', $columns) ? '✅' : '❌') . "\n";
    
    // Test 2: Check model fillable fields
    echo "\n2. Model Configuration Check:\n";
    $model = new \App\Models\DeliveryPartner();
    $fillable = $model->getFillable();
    echo "   - registration_type in fillable: " . (in_array('registration_type', $fillable) ? '✅' : '❌') . "\n";
    
    // Test 3: Check wallet model
    echo "\n3. Wallet Model Check:\n";
    $walletModel = new \App\Models\DeliveryPartnerWallet();
    $walletFillable = $walletModel->getFillable();
    echo "   - total_earned in fillable: " . (in_array('total_earned', $walletFillable) ? '✅' : '❌') . "\n";
    echo "   - total_withdrawn in fillable: " . (in_array('total_withdrawn', $walletFillable) ? '✅' : '❌') . "\n";
    
    // Test 4: Route accessibility
    echo "\n4. Route Check:\n";
    $routes = \Illuminate\Support\Facades\Route::getRoutes();
    $quickRegisterRoutes = $routes->getByName('delivery-partner.quick-register');
    echo "   - quick-register GET route: " . ($quickRegisterRoutes ? '✅' : '❌') . "\n";
    
    $quickRegisterPostRoutes = $routes->getByName('delivery-partner.quick-register.post');
    echo "   - quick-register POST route: " . ($quickRegisterPostRoutes ? '✅' : '❌') . "\n";
    
    echo "\n✅ Registration system checks completed!\n";
    echo "\nNext steps:\n";
    echo "1. Test the registration form at: https://grabbaskets.laravel.cloud/delivery-partner/quick-register\n";
    echo "2. Check if registration now works without 'Registration failed' error\n";
    echo "3. Verify wallet creation after successful registration\n";
    
} catch (Exception $e) {
    echo "❌ Error during testing: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
}
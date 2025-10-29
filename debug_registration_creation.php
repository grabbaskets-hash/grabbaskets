<?php
// Test delivery partner creation to debug registration issues

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🔍 DEBUGGING DELIVERY PARTNER REGISTRATION\n";
echo "==========================================\n\n";

try {
    // Test data similar to what would come from the form
    $testData = [
        'name' => 'Test Delivery Partner ' . time(),
        'email' => 'test.delivery.' . time() . '@example.com',
        'phone' => '9' . str_pad(rand(100000000, 999999999), 9, '0', STR_PAD_LEFT),
        'password' => 'password123',
        'vehicle_type' => 'bike',
        'city' => 'Test City',
        'address' => 'To be provided',
        'state' => 'To be provided',
        'pincode' => '000000',
        'date_of_birth' => '2000-01-01',
        'gender' => 'male',
        'vehicle_number' => 'To be provided',
        'license_number' => 'To be provided',
        'license_expiry' => '2025-12-31',
        'aadhar_number' => 'To be provided',
        'status' => 'pending',
        'is_verified' => false,
        'is_online' => false,
        'is_available' => false,
        'registration_type' => 'quick',
    ];
    
    echo "1. Testing Delivery Partner Creation:\n";
    echo "   Data: " . json_encode($testData, JSON_PRETTY_PRINT) . "\n\n";
    
    // Hash the password
    $testData['password'] = Hash::make($testData['password']);
    
    // Create delivery partner
    $deliveryPartner = \App\Models\DeliveryPartner::create($testData);
    echo "   ✅ Delivery Partner created successfully with ID: " . $deliveryPartner->id . "\n";
    
    echo "\n2. Testing Wallet Creation:\n";
    $walletData = [
        'delivery_partner_id' => $deliveryPartner->id,
        'balance' => 0.00,
        'total_earned' => 0.00,
        'total_withdrawn' => 0.00,
        'pending_amount' => 0.00,
        'total_deliveries' => 0,
        'successful_deliveries' => 0,
        'average_rating' => 0.00,
        'is_active' => true,
    ];
    
    echo "   Wallet Data: " . json_encode($walletData, JSON_PRETTY_PRINT) . "\n";
    
    $wallet = \App\Models\DeliveryPartnerWallet::create($walletData);
    echo "   ✅ Wallet created successfully with ID: " . $wallet->id . "\n";
    
    // Clean up test data
    echo "\n3. Cleaning up test data:\n";
    $wallet->delete();
    $deliveryPartner->delete();
    echo "   ✅ Test data cleaned up\n";
    
    echo "\n✅ REGISTRATION COMPONENTS WORKING!\n";
    echo "The issue might be with form validation or request handling.\n";
    
} catch (\Illuminate\Database\QueryException $e) {
    echo "❌ Database Error: " . $e->getMessage() . "\n";
    echo "SQL: " . $e->getSql() . "\n";
    if ($e->getBindings()) {
        echo "Bindings: " . json_encode($e->getBindings()) . "\n";
    }
} catch (Exception $e) {
    echo "❌ General Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
    echo "Stack Trace: " . $e->getTraceAsString() . "\n";
}
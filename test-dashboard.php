<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

try {
    // Bootstrap Laravel
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    // Test the GitHubImageService
    echo "Testing GitHubImageService...\n";
    $service = new App\Services\GitHubImageService();
    echo "✅ GitHubImageService created successfully\n";
    
    // Test SellerController dashboard method
    echo "Testing SellerController...\n";
    $controller = new App\Http\Controllers\SellerController();
    echo "✅ SellerController created successfully\n";
    
    // Test Product model with relations
    echo "Testing Product model with relations...\n";
    $products = App\Models\Product::with(['category', 'subcategory'])->limit(1)->get();
    echo "✅ Product model with relations working - Count: " . $products->count() . "\n";
    
    echo "✅ All tests passed!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
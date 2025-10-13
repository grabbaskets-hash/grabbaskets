<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

use App\Models\Product;
use Illuminate\Support\Facades\DB;

echo "=== TESTING EDIT PRODUCT PAGE ===\n\n";

// Get a product with images
$product = Product::with('images')
    ->whereNotNull('image')
    ->where('image', '!=', '')
    ->first();

if (!$product) {
    echo "❌ No products found\n";
    exit(1);
}

echo "Testing Product ID: {$product->id}\n";
echo "Product Name: {$product->name}\n";
echo "Main Image: {$product->image}\n";
echo "\n";

try {
    // Test getLegacyImageUrl()
    echo "--- Testing getLegacyImageUrl() ---\n";
    $imageUrl = $product->getLegacyImageUrl();
    echo "URL: {$imageUrl}\n";
    echo "✅ getLegacyImageUrl() works\n\n";
} catch (Exception $e) {
    echo "❌ ERROR in getLegacyImageUrl(): {$e->getMessage()}\n";
    echo "Stack trace:\n{$e->getTraceAsString()}\n\n";
}

try {
    // Test images relationship
    echo "--- Testing images relationship ---\n";
    $images = $product->images;
    echo "Gallery images count: {$images->count()}\n";
    
    foreach ($images->take(3) as $image) {
        echo "  Image ID: {$image->id}\n";
        echo "  Path: {$image->image_path}\n";
        
        try {
            $url = $image->image_url;
            echo "  URL: {$url}\n";
            echo "  ✅ Works\n";
        } catch (Exception $e) {
            echo "  ❌ ERROR: {$e->getMessage()}\n";
        }
        echo "\n";
    }
    
    echo "✅ Images relationship works\n\n";
} catch (Exception $e) {
    echo "❌ ERROR in images relationship: {$e->getMessage()}\n";
    echo "Stack trace:\n{$e->getTraceAsString()}\n\n";
}

try {
    // Test all Product attributes that might be accessed in edit view
    echo "--- Testing Product attributes ---\n";
    $attributes = [
        'id', 'name', 'description', 'price', 'original_price', 
        'stock', 'category_id', 'seller_id', 'image', 'status'
    ];
    
    foreach ($attributes as $attr) {
        if (isset($product->$attr)) {
            echo "  {$attr}: " . (is_string($product->$attr) ? substr($product->$attr, 0, 50) : $product->$attr) . "\n";
        }
    }
    
    echo "✅ All attributes accessible\n\n";
} catch (Exception $e) {
    echo "❌ ERROR accessing attributes: {$e->getMessage()}\n";
    echo "Stack trace:\n{$e->getTraceAsString()}\n\n";
}

echo "=== TEST COMPLETE ===\n";
echo "If no errors above, check Laravel logs for the actual 500 error\n";

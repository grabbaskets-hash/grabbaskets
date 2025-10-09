<?php

// Simple test to check if the basic app loads
require_once __DIR__ . '/vendor/autoload.php';

try {
    // Test basic Laravel bootstrap
    $app = require_once __DIR__ . '/bootstrap/app.php';
    
    echo "✅ Laravel application bootstrap successful\n";
    
    // Test database connection
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    $pdo = DB::connection()->getPdo();
    echo "✅ Database connection successful\n";
    
    // Test basic model loading
    $categoryCount = App\Models\Category::count();
    echo "✅ Model loading successful - Categories: {$categoryCount}\n";
    
    echo "✅ All basic tests passed!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
}
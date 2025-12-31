<?php

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\FoodItem;
use Illuminate\Support\Facades\Storage;

$ids = [1, 165];

foreach ($ids as $id) {
    echo "--------------------------------------------------\n";
    echo "Checking Food Item ID: $id\n";
    $item = FoodItem::find($id);

    if (!$item) {
        echo "Item not found.\n";
        continue;
    }

    echo "Name: " . $item->name . "\n";
    echo "DB Image Column: '" . $item->image . "'\n";
    
    if (empty($item->image)) {
        echo "No image path in DB.\n";
        continue;
    }

    // Check physical file existence in various locations
    $locations = [
        'storage/app/public/' . $item->image,
        'storage/app/public/products/' . $item->image,
        'public/storage/' . $item->image,
        'public/' . $item->image,
    ];

    echo "Checking file system:\n";
    foreach ($locations as $loc) {
        $path = base_path($loc);
        echo "  - $path: " . (file_exists($path) ? "EXISTS" : "MISSING") . "\n";
    }

    // Check via Storage facade
    echo "Checking Storage Facade (disk: public):\n";
    echo "  - exists('" . $item->image . "'): " . (Storage::disk('public')->exists($item->image) ? "YES" : "NO") . "\n";

    echo "Generated URL (first_image_url): " . $item->first_image_url . "\n";
    
    // Check if URL matches what we expect
    $expectedUrl = asset('storage/' . $item->image);
    echo "Asset URL: $expectedUrl\n";
}

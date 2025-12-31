<?php

use Illuminate\Support\Facades\DB;
use App\Models\FoodItem;
use Illuminate\Support\Facades\Storage;

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    // Try to get raw PDO connection and list tables to ensure DB is working
    $tables = DB::select('SHOW TABLES');
    echo "DB Connected. Tables found: " . count($tables) . "\n";

    $ids = [1, 165];

    foreach ($ids as $id) {
        echo "--------------------------------------------------\n";
        echo "Checking Food Item ID: $id\n";
        
        try {
             $item = FoodItem::find($id);
        } catch (\Exception $e) {
            echo "Error finding item: " . $e->getMessage() . "\n";
            continue;
        }

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
            // Replace backslashes for Windows compatibility in generic output
            echo "  - $loc: " . (file_exists($path) ? "EXISTS" : "MISSING") . "\n";
        }

        // Check Storage Facade
        try {
            $exists = Storage::disk('public')->exists($item->image);
            echo "Checking Storage Facade (disk: public):\n";
            echo "  - exists('" . $item->image . "'): " . ($exists ? "YES" : "NO") . "\n";
        } catch(\Exception $e) {
             echo "Storage Facade Error: " . $e->getMessage() . "\n";
        }

        echo "Generated URL (first_image_url): " . $item->first_image_url . "\n";
    }

} catch (\Exception $e) {
    echo "CRITICAL ERROR: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}

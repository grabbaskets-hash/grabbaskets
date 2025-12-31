<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\FoodItem;
use Illuminate\Support\Facades\Storage;

$food = FoodItem::whereNotNull('image')->latest()->first();
if (!$food) {
    die("No food with image found\n");
}

$path = $food->getRawOriginal('image');
echo "Testing path: $path\n";

$exists = Storage::disk('r2')->exists($path);
echo "Exists in R2: " . ($exists ? "YES" : "NO") . "\n";

if ($exists) {
    try {
        $data = Storage::disk('r2')->get($path);
        echo "Data size: " . strlen($data) . " bytes\n";
    } catch (\Exception $e) {
        echo "Error getting data: " . $e->getMessage() . "\n";
    }
}

echo "Generated Model URL: " . $food->first_image_url . "\n";
echo "Proxy URL construction: " . url('/serve-image/r2/' . ltrim($path, '/')) . "\n";

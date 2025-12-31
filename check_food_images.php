<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\FoodItem;

$foods = FoodItem::where('is_available', 1)->take(10)->get();
foreach ($foods as $f) {
    echo "ID: " . $f->id . " | Name: " . $f->name . "\n";
    echo "  Image Field: " . ($f->image ?: 'NULL') . "\n";
    echo "  First Image URL: " . ($f->first_image_url ?: 'NULL') . "\n\n";
}

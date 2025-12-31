<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\FoodItem;

$food = new FoodItem();
$reflector = new ReflectionObject($food);
$method = $reflector->getMethod('isLaravelCloud');
$method->setAccessible(true);
$isCloud = $method->invoke($food);

echo "Environment: " . app()->environment() . "\n";
echo "Config App Env: " . config('app.env') . "\n";
echo "Is Laravel Cloud: " . ($isCloud ? 'YES' : 'NO') . "\n";
echo "R2 Config Driver: " . config('filesystems.disks.r2.driver') . "\n";
echo "R2 Config Bucket: " . config('filesystems.disks.r2.bucket') . "\n";
echo "AWS_URL: " . config('filesystems.disks.r2.url') . "\n";

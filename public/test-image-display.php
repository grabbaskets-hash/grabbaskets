<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Display Test</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; padding: 20px; }
        .img-test { max-width: 200px; margin: 10px; border: 2px solid #dee2e6; border-radius: 8px; }
        .test-card { margin-bottom: 20px; }
        .alert { margin-bottom: 10px; }
    </style>
</head>
<body>
<div class="container">
    <h2><i class="fas fa-image"></i> Image Display Test</h2>
    <p>Testing if images are properly accessible through storage symlink:</p>

    <?php
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();
    
    use App\Models\Product;
    
    $products = Product::whereNotNull('image')->take(5)->get();
    ?>
    
    <div class="row">
        <?php foreach($products as $product): ?>
        <div class="col-md-4">
            <div class="card test-card">
                <div class="card-header">
                    <strong><?php echo htmlspecialchars($product->name); ?></strong>
                </div>
                <div class="card-body">
                    <p><strong>Image Path:</strong> <?php echo $product->image; ?></p>
                    
                    <?php
                    $imagePath = $product->image;
                    $fullStoragePath = storage_path('app/public/' . $imagePath);
                    $publicPath = public_path('storage/' . $imagePath);
                    $imageUrl = asset('storage/' . $imagePath);
                    ?>
                    
                    <div class="alert alert-info">
                        <small>
                            <strong>Storage File Exists:</strong> <?php echo file_exists($fullStoragePath) ? '✅ YES' : '❌ NO'; ?><br>
                            <strong>Public Link Exists:</strong> <?php echo file_exists($publicPath) ? '✅ YES' : '❌ NO'; ?><br>
                            <strong>Image URL:</strong> <?php echo $imageUrl; ?>
                        </small>
                    </div>
                    
                    <!-- Test Image Display -->
                    <div class="text-center">
                        <img src="<?php echo $imageUrl; ?>" 
                             alt="<?php echo htmlspecialchars($product->name); ?>" 
                             class="img-test img-fluid"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                        <div style="display: none;" class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle"></i> Image failed to load
                        </div>
                    </div>
                    
                    <!-- Direct storage test -->
                    <div class="mt-2">
                        <small>
                            <a href="<?php echo $imageUrl; ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                Open Image in New Tab
                            </a>
                        </small>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    
    <hr>
    
    <h4>Storage Link Test</h4>
    <div class="alert alert-info">
        <?php
        $storageLink = public_path('storage');
        if (is_link($storageLink)) {
            echo "✅ Storage symlink exists and points to: " . readlink($storageLink);
        } elseif (is_dir($storageLink)) {
            echo "⚠️ Storage exists as directory (not symlink)";
        } else {
            echo "❌ Storage symlink does not exist";
        }
        ?>
    </div>
    
    <h4>Direct File Access Test</h4>
    <?php
    $testProduct = $products->first();
    if ($testProduct && $testProduct->image) {
        $testImagePath = $testProduct->image;
        $directUrl = "/storage/" . $testImagePath;
        echo "<p>Testing direct access: <a href='{$directUrl}' target='_blank'>{$directUrl}</a></p>";
    }
    ?>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</body>
</html>
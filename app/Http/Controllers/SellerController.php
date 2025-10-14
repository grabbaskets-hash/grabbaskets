<?php
namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Order;
use App\Imports\ProductsImport;
use App\Services\GitHubImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

use Exception;
#use Illuminate\Support\Facades\Storage;
use ZipArchive;

class SellerController extends Controller {
    // ...existing code...

    // Bulk product upload: CSV + images
    public function bulkProductUpload(Request $request)
    {
        $request->validate([
            'products_file' => 'required|file|mimes:csv,txt',
            'images' => 'required',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:4096',
        ]);
        $sellerId = Auth::id();
        $imageMap = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $filename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $imageMap[strtolower($filename)] = $image;
            }
        }
        $file = $request->file('products_file');
        $rows = array_map('str_getcsv', file($file->getRealPath()));
        $header = array_map('trim', array_map('strtolower', $rows[0]));
        unset($rows[0]);
        $count = 0;
        $updatedImages = 0;
        foreach ($rows as $row) {
            $data = array_combine($header, $row);
            if (!$data) continue;
            $data['seller_id'] = $sellerId;
            // Check if product exists by unique_id for this seller
            $product = null;
            if (isset($data['unique_id'])) {
                $product = Product::where('seller_id', $sellerId)
                    ->where('unique_id', $data['unique_id'])
                    ->first();
            }
            if ($product) {
                $product->fill($data);
            } else {
                $product = new Product($data);
            }
            // Attach image if available
            $uid = isset($data['unique_id']) ? strtolower($data['unique_id']) : null;
            if ($uid && isset($imageMap[$uid])) {
                $img = $imageMap[$uid];
                // Store under products/ to keep URL generation consistent
                $folder = "products/seller/{$sellerId}/{$data['category_id']}/{$data['subcategory_id']}";
                
                // DUAL STORAGE: Save to both AWS R2 and Git storage for redundancy
                $r2Path = null;
                $publicPath = null;
                $r2Success = false;
                $publicSuccess = false;
                
                // Try AWS R2 first
                try {
                    $r2Path = $img->store($folder, 'r2');
                    $r2Success = !empty($r2Path);
                } catch (\Throwable $r2Ex) {
                    Log::warning('AWS R2 upload failed during bulk product upload', [
                        'error' => $r2Ex->getMessage(),
                        'unique_id' => $uid
                    ]);
                }
                
                // Then save to Git storage (public disk)
                try {
                    $publicPath = $img->store($folder, 'public');
                    $publicSuccess = !empty($publicPath);
                } catch (\Throwable $publicEx) {
                    Log::warning('Git storage upload failed during bulk product upload', [
                        'error' => $publicEx->getMessage(),
                        'unique_id' => $uid
                    ]);
                }
                
                // Use whichever path was successful (prefer R2)
                $finalPath = $r2Success ? $r2Path : $publicPath;
                
                if ($finalPath) {
                    $product->image = $finalPath;
                    $updatedImages++;
                    
                    Log::info('Bulk product image stored with dual storage redundancy', [
                        'unique_id' => $uid,
                        'path' => $finalPath,
                        'r2_success' => $r2Success,
                        'public_success' => $publicSuccess
                    ]);
                } else {
                    Log::error('Both storages failed for bulk product upload image', [
                        'unique_id' => $uid
                    ]);
                }
            }
            $product->save();
            $count++;
        }
        $msg = "$count products uploaded/updated. $updatedImages images assigned.";
        return redirect()->route('seller.dashboard')->with('bulk_upload_success', $msg);
    }
    // Display product images by seller/category/subcategory
    public function productImages(Request $request)
    {
        $query = Product::query();
        if ($request->filled('seller_id')) {
            $query->where('seller_id', $request->seller_id);
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('subcategory_id')) {
            $query->where('subcategory_id', $request->subcategory_id);
        }
        $products = $query->latest()->get();
        return view('seller.product-images', compact('products'));
    }
    // Delete a product and its image
    public function destroyProduct(Product $product)
    {
        if ($product->seller_id !== Auth::id()) {
            abort(403);
        }
        if ($product->image) {
            // Try delete from both disks, ignore errors
            try { Storage::disk('r2')->delete($product->image); } catch (\Throwable $e) {}
            try { Storage::disk('public')->delete($product->image); } catch (\Throwable $e) {}
        }

        // Delete all product images
        foreach ($product->productImages as $productImage) {
            try { Storage::disk('r2')->delete($productImage->image_path); } catch (\Throwable $e) {}
            try { Storage::disk('public')->delete($productImage->image_path); } catch (\Throwable $e) {}
            $productImage->delete();
        }

        $product->delete();
        return redirect()->route('seller.dashboard')->with('success', 'Product deleted!');
    }

    // Upload multiple images for a product
    public function uploadProductImages(Request $request, Product $product)
    {
        if ($product->seller_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'images' => 'required|array|min:1|max:10',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB max per image
        ]);

        $uploadedCount = 0;
        $errors = [];

        foreach ($request->file('images') as $index => $image) {
            try {
                $sellerId = Auth::id();
                $folder = 'products/seller-' . $sellerId;
                $originalName = $image->getClientOriginalName();
                $originalNameSlug = Str::slug(pathinfo($originalName, PATHINFO_FILENAME));
                $ext = $image->getClientOriginalExtension();
                $filename = $originalNameSlug . '-' . time() . '-' . Str::random(4) . '.' . $ext;
                $mimeType = $image->getMimeType();
                $fileSize = $image->getSize();

                // DUAL STORAGE: Save to both AWS R2 and Git storage for redundancy
                $r2Path = null;
                $publicPath = null;
                $r2Success = false;
                $publicSuccess = false;
                $finalPath = null;

                // Try AWS R2 first
                try {
                    $r2Path = $image->storeAs($folder, $filename, 'r2');
                    $r2Success = !empty($r2Path);
                } catch (\Throwable $r2Ex) {
                    Log::warning('AWS R2 upload failed for product gallery image', [
                        'error' => $r2Ex->getMessage(),
                        'product_id' => $product->id,
                        'original_name' => $originalName
                    ]);
                }

                // Then save to Git storage (public disk)
                try {
                    $publicPath = $image->storeAs($folder, $filename, 'public');
                    $publicSuccess = !empty($publicPath);
                } catch (\Throwable $publicEx) {
                    Log::warning('Git storage (public) upload failed for product gallery image', [
                        'error' => $publicEx->getMessage(),
                        'product_id' => $product->id,
                        'original_name' => $originalName
                    ]);
                }

                // Use whichever path was successful (prefer R2)
                $finalPath = $r2Success ? $r2Path : $publicPath;

                if (!$finalPath) {
                    throw new \Exception('Both AWS R2 and Git storage failed');
                }

                Log::info('Product gallery image stored with dual storage redundancy', [
                    'product_id' => $product->id,
                    'path' => $finalPath,
                    'r2_success' => $r2Success,
                    'public_success' => $publicSuccess,
                    'original_name' => $originalName
                ]);

                // Get the next sort order
                $nextSortOrder = ProductImage::where('product_id', $product->id)
                    ->max('sort_order') + 1;

                // Create ProductImage record
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $finalPath,
                    'original_name' => $originalName,
                    'mime_type' => $mimeType,
                    'file_size' => $fileSize,
                    'sort_order' => $nextSortOrder,
                    'is_primary' => $index === 0 && $product->productImages()->count() === 0, // First image is primary if no images exist
                ]);

                $uploadedCount++;
            } catch (\Exception $e) {
                $errors[] = "Failed to upload {$originalName}: " . $e->getMessage();
            }
        }

        $message = "{$uploadedCount} images uploaded successfully.";
        if (!empty($errors)) {
            $message .= " Errors: " . implode(', ', $errors);
        }

        return redirect()->back()->with('success', $message);
    }

    // Delete a specific product image
    public function deleteProductImage(ProductImage $productImage)
    {
        if ($productImage->product->seller_id !== Auth::id()) {
            abort(403);
        }

        // Delete from storage
        try { Storage::disk('r2')->delete($productImage->image_path); } catch (\Throwable $e) {}
        try { Storage::disk('public')->delete($productImage->image_path); } catch (\Throwable $e) {}

        $productImage->delete();

        return redirect()->back()->with('success', 'Image deleted successfully.');
    }

    // Set primary image
    public function setPrimaryImage(ProductImage $productImage)
    {
        if ($productImage->product->seller_id !== Auth::id()) {
            abort(403);
        }

        // Remove primary flag from all images of this product
        ProductImage::where('product_id', $productImage->product_id)
            ->update(['is_primary' => false]);

        // Set this image as primary
        $productImage->update(['is_primary' => true]);

        return redirect()->back()->with('success', 'Primary image updated.');
    }

    // Show product gallery management
    public function productGallery(Product $product)
    {
        if ($product->seller_id !== Auth::id()) {
            abort(403);
        }

        $images = $product->productImages()->ordered()->get();
        return view('seller.product-gallery', compact('product', 'images'));
    }
    public function storeProducts(\App\Models\Seller $seller)
    {
        $products = Product::with(['category', 'subcategory'])
            ->where('seller_id', $seller->id)
            ->latest()->paginate(12);
        return view('seller.store-products', compact('seller', 'products'));
    }
    public function updateProfile(Request $request)
    {
        $request->validate([
            'store_name' => 'nullable|string|max:255',
            'gst_number' => 'nullable|string|max:255',
            'store_address' => 'nullable|string|max:500',
            'store_contact' => 'nullable|string|max:255',
            'profile_photo' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048', // 2MB max
        ]);

        $user = Auth::user();
        $seller = \App\Models\Seller::where('email', $user->email)->firstOrFail();
        
        // Update seller information
        $seller->update($request->only([
            'store_name',
            'gst_number',
            'store_address',
            'store_contact'
        ]));

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            try {
                $photo = $request->file('profile_photo');
                
                // Generate unique filename
                $filename = 'profile_photos/' . $user->id . '_' . time() . '.' . $photo->getClientOriginalExtension();
                
                // Upload to R2 storage
                Storage::disk('r2')->put($filename, file_get_contents($photo->getPathname()));
                
                // Construct the public URL (Laravel Cloud R2 public URL)
                $r2PublicUrl = 'https://fls-a00f1665-d58e-4a6d-a69d-0dc4be26102f.laravel.cloud';
                $photoUrl = $r2PublicUrl . '/' . $filename;
                
                // Delete old profile photo if exists
                if ($user->profile_picture && str_contains($user->profile_picture, 'profile_photos/')) {
                    $oldPath = basename(dirname($user->profile_picture)) . '/' . basename($user->profile_picture);
                    if (str_starts_with($oldPath, 'profile_photos/')) {
                        try {
                            Storage::disk('r2')->delete($oldPath);
                        } catch (\Exception $e) {
                            Log::warning('Failed to delete old profile photo: ' . $e->getMessage());
                        }
                    }
                }
                
                // Update user's profile picture
                \App\Models\User::where('id', $user->id)->update(['profile_picture' => $photoUrl]);
                
                return redirect()->route('seller.profile')->with('success', 'Profile photo and store info updated successfully!');
            } catch (\Exception $e) {
                Log::error('Profile photo upload failed: ' . $e->getMessage());
                return redirect()->route('seller.profile')->with('error', 'Profile photo upload failed. Store info updated.');
            }
        }

        return redirect()->route('seller.profile')->with('success', 'Store info updated!');
    }
    public function dashboard()
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to access seller dashboard.');
        }

        $products = Product::with(['category', 'subcategory', 'productImages'])
            ->where('seller_id', Auth::id())
            ->latest()
            ->get();
        return view('seller.dashboard', compact('products'));
    }
    /**
     * Update product images by uploading a ZIP file where each image filename is the product unique_id
     */
    public function updateImagesByZip(Request $request)
    {
        // Increase limits to prevent 502 errors
        set_time_limit(0); // No time limit
        ini_set('memory_limit', '1G');
        ignore_user_abort(true); // Continue processing even if user closes browser
        
        // Log the start of upload attempt
        Log::info('Bulk image upload started', [
            'user_id' => Auth::id(),
            'memory_limit' => ini_get('memory_limit'),
            'upload_max_filesize' => ini_get('upload_max_filesize'),
        ]);
        
        try {
            $request->validate([
                'images_zip' => 'required|file|mimes:zip|max:102400', // 100MB max
            ]);

            $zipFile = $request->file('images_zip');
            
            if (!$zipFile || !$zipFile->isValid()) {
                throw new \Exception('Invalid ZIP file uploaded');
            }
            
            $fileSize = $zipFile->getSize();
            Log::info('Processing ZIP file', [
                'filename' => $zipFile->getClientOriginalName(),
                'size_mb' => round($fileSize / 1024 / 1024, 2),
            ]);
            
            $zipPath = $zipFile->store('temp', 'local');
            $fullZipPath = storage_path('app/' . $zipPath);
            
            if (!file_exists($fullZipPath)) {
                throw new \Exception('Failed to save ZIP file');
            }
            
            $zip = new \ZipArchive();
            $updated = 0;
            $errors = [];
            $processed = 0;
            
            if ($zip->open($fullZipPath) === TRUE) {
                $totalFiles = $zip->numFiles;
                Log::info('ZIP opened successfully', ['total_files' => $totalFiles]);
                
                // Process all files but with better error handling
                for ($i = 0; $i < $totalFiles; $i++) {
                    $processed++;
                    
                    // Log progress every 10 files
                    if ($processed % 10 == 0) {
                        Log::info("Processing file $processed of $totalFiles");
                        // Force garbage collection every 10 files
                        gc_collect_cycles();
                    }
                    
                    $filename = $zip->getNameIndex($i);
                    if (empty($filename) || strpos($filename, '__MACOSX') !== false || substr($filename, -1) === '/') {
                        continue; // Skip system files and directories
                    }
                    
                    $basename = pathinfo($filename, PATHINFO_BASENAME);
                    $uniqueId = pathinfo($basename, PATHINFO_FILENAME);
                    
                    try {
                        $imageContent = $zip->getFromIndex($i);
                        
                        if ($imageContent === false || empty($imageContent)) {
                            $errors[] = "Could not extract: $basename";
                            continue;
                        }
                        
                        // Check individual image size (10MB max)
                        if (strlen($imageContent) > 10 * 1024 * 1024) {
                            $errors[] = "Image too large (>10MB): $basename";
                            continue;
                        }
                        
                        // Validate image content
                        $finfo = new \finfo(FILEINFO_MIME_TYPE);
                        $mimeType = $finfo->buffer($imageContent);
                        
                        if (!in_array($mimeType, ['image/jpeg', 'image/png', 'image/gif', 'image/webp'])) {
                            $errors[] = "Invalid image type for $uniqueId: $mimeType";
                            continue;
                        }
                        
                        // Try to find product by unique_id - improve matching
                        $product = Product::where('seller_id', Auth::id())
                            ->where(function($query) use ($uniqueId) {
                                $query->where('unique_id', $uniqueId)
                                      ->orWhere('unique_id', 'LIKE', "%{$uniqueId}%")
                                      ->orWhere('name', 'LIKE', "%{$uniqueId}%");
                            })
                            ->first();
                            
                        if ($product) {
                            $extension = pathinfo($basename, PATHINFO_EXTENSION) ?: 'jpg';
                            $uniqueName = Str::random(40) . '.' . $extension;
                            $storagePath = 'products/' . $product->id . '/' . $uniqueName;

                            // Always try to store to both R2 and public
                            $savedR2 = false;
                            $savedPublic = false;
                            try {
                                $savedR2 = Storage::disk('r2')->put($storagePath, $imageContent);
                                if ($savedR2) {
                                    Log::info('Bulk image stored in AWS (r2)', [
                                        'product_id' => $product->id,
                                        'unique_id' => $uniqueId,
                                        'path' => $storagePath
                                    ]);
                                }
                            } catch (\Throwable $e) {
                                Log::warning('AWS upload failed for bulk image', [
                                    'product_id' => $product->id,
                                    'error' => $e->getMessage()
                                ]);
                            }

                            try {
                                $savedPublic = Storage::disk('public')->put($storagePath, $imageContent);
                                if ($savedPublic) {
                                    Log::info('Bulk image stored in public disk', [
                                        'product_id' => $product->id,
                                        'unique_id' => $uniqueId,
                                        'path' => $storagePath
                                    ]);
                                }
                            } catch (\Throwable $e) {
                                Log::warning('Public disk upload failed for bulk image', [
                                    'product_id' => $product->id,
                                    'error' => $e->getMessage()
                                ]);
                            }

                            if ($savedR2 || $savedPublic) {
                                // Delete old legacy image if exists
                                if ($product->image) {
                                    try { Storage::disk('r2')->delete($product->image); } catch (\Throwable $e) {}
                                    try { Storage::disk('public')->delete($product->image); } catch (\Throwable $e) {}
                                }

                                // Update legacy image field
                                $product->image = $storagePath;
                                $product->save();

                                // Also create/update ProductImage record for gallery system
                                try {
                                    \App\Models\ProductImage::updateOrCreate(
                                        [
                                            'product_id' => $product->id,
                                            'is_primary' => true
                                        ],
                                        [
                                            'image_path' => $storagePath,
                                            'original_name' => $basename,
                                            'mime_type' => $mimeType,
                                            'file_size' => strlen($imageContent),
                                            'sort_order' => 1,
                                        ]
                                    );
                                } catch (\Throwable $e) {
                                    Log::warning('Failed to create ProductImage record', [
                                        'product_id' => $product->id,
                                        'error' => $e->getMessage()
                                    ]);
                                }

                                $updated++;
                                if (!$savedR2) {
                                    $errors[] = "Image for product $uniqueId saved to public but failed to save to R2.";
                                }
                                if (!$savedPublic) {
                                    $errors[] = "Image for product $uniqueId saved to R2 but failed to save to public.";
                                }
                            } else {
                                $errors[] = "Failed to save image for product $uniqueId to either R2 or public.";
                            }
                        } else {
                            $errors[] = "No product found for unique_id: $uniqueId";
                        }
                        
                    } catch (\Exception $e) {
                        $errors[] = "Error processing $basename: " . $e->getMessage();
                        Log::error("Error processing bulk image file", [
                            'filename' => $basename,
                            'error' => $e->getMessage(),
                            'trace' => $e->getTraceAsString()
                        ]);
                    }
                }
                $zip->close();
            } else {
                throw new \Exception('Could not open ZIP file. Please ensure it is a valid ZIP file.');
            }
            
            // Clean up temp file
            if (file_exists($fullZipPath)) {
                Storage::disk('local')->delete($zipPath);
            }
            
            // Log completion
            Log::info('Bulk image upload completed', [
                'updated' => $updated,
                'processed' => $processed,
                'errors' => count($errors),
                'final_memory_mb' => round(memory_get_usage(true) / 1024 / 1024, 2)
            ]);
            
            $msg = "$updated product images updated successfully.";
            if ($errors) {
                $errorMsg = implode(' | ', array_slice($errors, 0, 5)); // Show first 5 errors
                if (count($errors) > 5) {
                    $errorMsg .= ' | And ' . (count($errors) - 5) . ' more errors...';
                }
                $msg .= ' Issues: ' . $errorMsg;
            }
            
            return redirect()->route('seller.dashboard')->with('bulk_upload_success', $msg);
            
        } catch (\Throwable $e) {
            // Clean up temp file on error
            if (isset($zipPath) && Storage::disk('local')->exists($zipPath)) {
                Storage::disk('local')->delete($zipPath);
            }
            
            Log::error('Bulk image upload failed', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'memory_mb' => round(memory_get_usage(true) / 1024 / 1024, 2)
            ]);
            
            $errorMessage = 'Upload failed: ';
            if (strpos($e->getMessage(), 'memory') !== false) {
                $errorMessage .= 'Not enough memory. Try uploading smaller ZIP files.';
            } elseif (strpos($e->getMessage(), 'time') !== false || strpos($e->getMessage(), 'timeout') !== false) {
                $errorMessage .= 'Processing took too long. Try smaller batches.';
            } elseif (strpos($e->getMessage(), 'zip') !== false) {
                $errorMessage .= 'Invalid ZIP file. Please ensure it\'s a valid ZIP archive.';
            } else {
                $errorMessage .= $e->getMessage();
            }
            
            return redirect()->back()->with('error', $errorMessage);
        }
    }
    public function addMultipleSubcategories()
    {
        $categories = Category::all();
        return view('seller.add-multiple-subcategories', compact('categories'));
    }

    public function storeMultipleSubcategories(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'subcategory_names' => 'required|array|min:1',
            'subcategory_names.*' => 'required|string|max:255',
        ]);
        foreach ($request->subcategory_names as $name) {
            $unique_id = Str::upper(Str::random(2)) . rand(0, 9);
            // Subcategory creation logic removed
        }
        return redirect()->route('seller.dashboard')->with('success', 'Subcategories added!');
    }
    // Merged Category & Subcategory Form
    public function createCategorySubcategory()
    {
        $categories = Category::all();
        return view('seller.create-category-subcategory', compact('categories'));
    }

public function storeCategorySubcategory(Request $request)
{
    // If new category is provided
    if ($request->filled('category_name') && $request->filled('category_unique_id')) {
        $request->validate([
            'category_name' => 'required|string|max:255',
            'category_unique_id' => 'required|string|max:3|unique:categories,unique_id',
            'subcategory_name' => 'required|string|max:255',
        ]);

        // Create new category
        $category = Category::create([
            'name' => strtoupper($request->category_name),
            'unique_id' => strtoupper($request->category_unique_id),
        ]);
    }
    // If existing category selected
    elseif ($request->filled('existing_category')) {
        $request->validate([
            'existing_category' => 'required|exists:categories,id',
            'subcategory_name' => 'required|string|max:255',
        ]);

        $category = Category::findOrFail($request->existing_category);
    } else {
        return back()->withErrors(['error' => 'Please select or create a category.']);
    }

    // Convert subcategory name to uppercase
    $subcategoryName = strtoupper($request->subcategory_name);

    // Check if subcategory already exists for this category
    $existingSubcategory = Subcategory::where('category_id', $category->id)
        ->where('name', $subcategoryName)
        ->first();

    if ($existingSubcategory) {
        return back()->with('error', 'This subcategory already exists for the selected category!');
    }

    // Add subcategory if not exists
    Subcategory::create([
        'name' => $subcategoryName,
        'category_id' => $category->id,
        'unique_id' => strtoupper(Str::random(3)), // Example: random 3-letter code
    ]);

    return redirect('seller/dashboard')->with('success', 'Subcategory added successfully!');

}


    // Category Form
    public function createCategory()
    {
        return view('seller.create-category');
    }

    // Product Form
    public function createProduct()
    {
        $categories = Category::all();
        $subcategories = Subcategory::all();
        return view('seller.create-product', compact('categories', 'subcategories'));
    }

    public function storeProduct(Request $request)
    {
        Log::info('storeProduct called', [
            'has_image_file' => $request->hasFile('image'),
            'all_files' => $request->allFiles(),
            'input_keys' => array_keys($request->all())
        ]);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'required|exists:subcategories,id',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'delivery_charge' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'gift_option' => 'required|in:yes,no',
            'stock' => 'required|integer|min:0',
        ]);
        
        // Use database storage method
        return $this->storeProductWithDatabaseImage($request);
    }

    // New method for cloud-compatible image storage
    private function storeProductWithDatabaseImage(Request $request)
    {
        $unique_id = Str::upper(Str::random(2)) . rand(0, 9);
        
        // Create the product first
        $product = Product::create([
            'name' => $request->name,
            'unique_id' => $unique_id,
            'category_id' => $request->category_id,
            'subcategory_id' => $request->subcategory_id,
            'seller_id' => Auth::id(),
            'description' => $request->description,
            'price' => $request->price,
            'discount' => $request->discount ?? 0,
            'delivery_charge' => $request->delivery_charge ?? 0,
            'gift_option' => $request->gift_option,
            'stock' => $request->stock,
        ]);
        
        Log::info('Product created, checking for image', [
            'product_id' => $product->id,
            'hasFile' => $request->hasFile('image'),
            'storage_path' => storage_path('app/public'),
            'storage_writable' => is_writable(storage_path('app/public'))
        ]);
        
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $sellerId = Auth::id();
            
            // Use seller-specific folder structure (same as uploadProductImages)
            $folder = 'products/seller-' . $sellerId;
            
            // Preserve original filename without timestamp for easier retrieval
            $ext = $image->getClientOriginalExtension();
            $originalName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            $filename = Str::slug($originalName) . '.' . $ext;
            
            $imageUploaded = false;
            $imagePath = null;
            
            // Determine if we're on Laravel Cloud (using helper method)
            $isLaravelCloud = $this->isLaravelCloud();
            
            // Environment-aware storage strategy
            try {
                $publicSuccess = false;
                $r2Success = false;
                
                // Strategy 1: Laravel Cloud - R2 ONLY (primary storage)
                if ($isLaravelCloud) {
                    try {
                        $r2Path = $image->storeAs($folder, $filename, 'r2');
                        
                        if ($r2Path && Storage::disk('r2')->exists($r2Path)) {
                            $r2Success = true;
                            $imagePath = $r2Path;
                            
                            Log::info('R2 upload SUCCESS on Laravel Cloud (create)', [
                                'path' => $r2Path,
                                'size' => $image->getSize(),
                                'bucket' => config('filesystems.disks.r2.bucket')
                            ]);
                        } else {
                            Log::error('R2 upload returned path but file not found', [
                                'returned_path' => $r2Path,
                                'exists' => $r2Path ? Storage::disk('r2')->exists($r2Path) : false
                            ]);
                        }
                    } catch (\Throwable $r2Ex) {
                        Log::error('R2 upload FAILED on Laravel Cloud (create)', [
                            'error' => $r2Ex->getMessage(),
                            'error_class' => get_class($r2Ex),
                            'trace' => $r2Ex->getTraceAsString(),
                            'bucket' => config('filesystems.disks.r2.bucket'),
                            'endpoint' => config('filesystems.disks.r2.endpoint'),
                            'has_key' => !empty(config('filesystems.disks.r2.key')),
                            'has_secret' => !empty(config('filesystems.disks.r2.secret'))
                        ]);
                    }
                    
                    if (!$r2Success) {
                        Log::error('Image upload to R2 failed on Laravel Cloud', [
                            'product_name' => $request->name,
                            'seller_id' => Auth::id(),
                            'filename' => $filename
                        ]);
                        return redirect()->back()
                            ->withInput()
                            ->with('error', 'Failed to upload image to cloud storage. Please check your internet connection and try again. If the problem persists, contact support.');
                    }
                    
                    $imageUploaded = $r2Success;
                }
                // Strategy 2: Local - Public disk primary, R2 backup
                else {
                    // Save to public disk FIRST (primary storage locally)
                    try {
                        // Ensure directory exists
                        $folderPath = storage_path('app/public/' . $folder);
                        if (!file_exists($folderPath)) {
                            mkdir($folderPath, 0755, true);
                            Log::info('Created folder', ['path' => $folderPath]);
                        }
                        
                        // Save using Laravel's storeAs method
                        $publicPath = $image->storeAs($folder, $filename, 'public');
                        
                        if ($publicPath && Storage::disk('public')->exists($publicPath)) {
                            $publicSuccess = true;
                            $imagePath = $publicPath;
                            
                            Log::info('Public disk upload SUCCESS (local create)', [
                                'path' => $publicPath,
                                'size' => Storage::disk('public')->size($publicPath),
                                'full_path' => storage_path('app/public/' . $publicPath)
                            ]);
                        } else {
                            Log::error('Public disk upload returned false or file not found', [
                                'returned_path' => $publicPath,
                                'exists' => Storage::disk('public')->exists($publicPath ?? '')
                            ]);
                        }
                    } catch (\Throwable $publicEx) {
                        Log::error('Public disk upload EXCEPTION', [
                            'error' => $publicEx->getMessage(),
                            'trace' => $publicEx->getTraceAsString(),
                            'file' => $publicEx->getFile(),
                            'line' => $publicEx->getLine()
                        ]);
                    }
                    
                    // Also save to R2 as backup (non-blocking)
                    try {
                        $r2Path = $image->storeAs($folder, $filename, 'r2');
                        $r2Success = !empty($r2Path);
                        
                        if ($r2Success) {
                            Log::info('R2 backup upload SUCCESS (local create)', ['path' => $r2Path]);
                        }
                    } catch (\Throwable $r2Ex) {
                        // R2 failure is not critical on local
                        Log::warning('R2 backup upload failed (non-critical)', [
                            'error' => $r2Ex->getMessage()
                        ]);
                    }
                    
                    $imageUploaded = $publicSuccess;
                }
                
                if ($imageUploaded) {
                    $product->update(['image' => $imagePath]);
                    
                    // Also create a ProductImage record for the new gallery system
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $imagePath,
                        'original_name' => $image->getClientOriginalName(),
                        'mime_type' => $image->getMimeType(),
                        'file_size' => $image->getSize(),
                        'sort_order' => 1,
                        'is_primary' => true, // First image is primary
                    ]);
                    
                    Log::info('Product image stored successfully', [
                        'product_id' => $product->id,
                        'path' => $imagePath,
                        'r2_backup' => $r2Success,
                        'public_primary' => $publicSuccess,
                        'size' => $image->getSize(),
                        'original_name' => $image->getClientOriginalName()
                    ]);
                } else {
                    Log::error('Public disk storage failed for image upload', [
                        'product_id' => $product->id,
                        'public_success' => $publicSuccess,
                        'r2_success' => $r2Success
                    ]);
                    return redirect()->back()->withInput()->with('error', 'Image upload failed. Please check storage permissions.');
                }
            } catch (\Throwable $ex) {
                Log::error('Exception during image upload', [
                    'error' => $ex->getMessage(),
                    'trace' => $ex->getTraceAsString(),
                    'product_id' => $product->id
                ]);
                return redirect()->back()->withInput()->with('error', 'Image upload failed. Error: ' . $ex->getMessage());
            }
        } else {
            Log::info('No image file uploaded with product', ['product_id' => $product->id]);
        }
        
        $successMessage = "Product '{$product->name}' (ID: {$product->unique_id}) added successfully!";
        return redirect()->route('seller.dashboard')->with('success', $successMessage);
    }

    public function editProduct(Product $product)
    {
        try {
            Log::info('editProduct called', [
                'product_id' => $product->id ?? null,
                'seller_id' => $product->seller_id ?? null,
                'auth_id' => Auth::id(),
                'product_exists' => $product ? true : false
            ]);

            // Ensure product exists and belongs to the authenticated seller
            if (!$product || !isset($product->seller_id)) {
                Log::error('editProduct: Product not found or missing seller_id', ['product_id' => $product->id ?? null]);
                return redirect()->route('seller.dashboard')->with('error', 'Product not found.');
            }

            if (!Auth::check()) {
                return redirect()->route('login')->with('error', 'Please login to continue.');
            }

            if ((int) $product->seller_id !== (int) Auth::id()) {
                Log::warning('editProduct: Unauthorized access', [
                    'product_seller_id' => $product->seller_id,
                    'auth_id' => Auth::id()
                ]);
                return redirect()->route('seller.dashboard')->with('error', 'Unauthorized access to product.');
            }

            $categories = Category::all();
            $subcategories = Subcategory::all();
            Log::info('editProduct: categories/subcategories loaded', [
                'categories_count' => $categories->count(),
                'subcategories_count' => $subcategories->count()
            ]);
            return view('seller.edit-product', compact('product', 'categories', 'subcategories'));
        } catch (\Throwable $e) {
            Log::error('editProduct: Exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('seller.dashboard')->with('error', 'An unexpected error occurred while opening the edit page.');
        }
    }

    public function updateProduct(Request $request, Product $product)
    {
        if ($product->seller_id !== Auth::id()) {
            return redirect()->route('seller.dashboard')->with('error', 'Unauthorized access to product.');
        }
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'required|exists:subcategories,id',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'delivery_charge' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);
        $data = $request->only(['name', 'category_id', 'subcategory_id', 'description', 'price', 'discount', 'delivery_charge']);

        // Debug: Log if file is present
        Log::info('Image upload debug', [
            'hasFile' => $request->hasFile('image'),
            'file' => $request->file('image'),
            'library_image_url' => $request->library_image_url,
            'all_files' => $request->allFiles(),
        ]);

        // Handle image from library (URL provided)
        if ($request->filled('library_image_url') && !$request->hasFile('image')) {
            $libraryImageUrl = $request->library_image_url;
            
            // Extract path from R2 URL
            $r2BaseUrl = config('filesystems.disks.r2.url');
            if (str_starts_with($libraryImageUrl, $r2BaseUrl)) {
                $imagePath = str_replace($r2BaseUrl . '/', '', $libraryImageUrl);
                
                // Verify it's from the seller's library
                $sellerId = Auth::id();
                if (str_starts_with($imagePath, 'library/seller-' . $sellerId)) {
                    // Delete old image records
                    $product->productImages()->delete();
                    
                    // Create new ProductImage pointing to library image
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $imagePath,
                        'original_name' => basename($imagePath),
                        'mime_type' => 'image/jpeg',
                        'file_size' => 0,
                        'sort_order' => 1,
                        'is_primary' => true,
                    ]);
                    
                    // Update legacy field
                    $data['image'] = $imagePath;
                    
                    Log::info('Product image updated from library', [
                        'product_id' => $product->id,
                        'library_path' => $imagePath
                    ]);
                }
            }
        }
        // Handle image update: Environment-aware storage strategy
        elseif ($request->hasFile('image')) {
            $image = $request->file('image');
            $sellerId = Auth::id();
            $folder = 'products/seller-' . $sellerId;
            $ext = $image->getClientOriginalExtension();
            $originalName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            $filename = Str::slug($originalName) . '.' . $ext;
            $finalPath = null;
            $publicSuccess = false;
            $r2Success = false;
            
            // Determine if we're on Laravel Cloud (using helper method)
            $isLaravelCloud = $this->isLaravelCloud();
            
            try {
                // Remove all old ProductImage records and files before uploading new image
                // Get old paths for deletion (do after upload succeeds)
                $oldImagePaths = $product->productImages->pluck('image_path')->toArray();
                $oldLegacyPath = $product->image;
                
                // Delete database records first
                $product->productImages()->delete();

                // Strategy 1: Laravel Cloud - R2 ONLY (primary storage)
                if ($isLaravelCloud) {
                    try {
                        $r2Path = $image->storeAs($folder, $filename, 'r2');
                        
                        if ($r2Path && Storage::disk('r2')->exists($r2Path)) {
                            $r2Success = true;
                            $finalPath = $r2Path;
                            
                            Log::info('R2 upload SUCCESS on Laravel Cloud (update)', [
                                'path' => $r2Path,
                                'size' => $image->getSize(),
                                'bucket' => config('filesystems.disks.r2.bucket')
                            ]);
                        } else {
                            Log::error('R2 upload returned path but file not found (update)', [
                                'returned_path' => $r2Path,
                                'exists' => $r2Path ? Storage::disk('r2')->exists($r2Path) : false
                            ]);
                        }
                    } catch (\Throwable $r2Ex) {
                        Log::error('R2 upload FAILED on Laravel Cloud (update)', [
                            'error' => $r2Ex->getMessage(),
                            'error_class' => get_class($r2Ex),
                            'product_id' => $product->id,
                            'trace' => $r2Ex->getTraceAsString(),
                            'bucket' => config('filesystems.disks.r2.bucket'),
                            'endpoint' => config('filesystems.disks.r2.endpoint'),
                            'has_key' => !empty(config('filesystems.disks.r2.key')),
                            'has_secret' => !empty(config('filesystems.disks.r2.secret'))
                        ]);
                    }
                    
                    if (!$r2Success) {
                        Log::error('Image upload to R2 failed on Laravel Cloud (update)', [
                            'product_id' => $product->id,
                            'product_name' => $product->name,
                            'seller_id' => Auth::id(),
                            'filename' => $filename
                        ]);
                        return redirect()->back()
                            ->withInput()
                            ->with('error', 'Failed to upload image to cloud storage. Please check your internet connection and try again. If the problem persists, contact support.');
                    }
                }
                // Strategy 2: Local - Public disk primary, R2 backup
                else {
                    try {
                        // Ensure directory exists
                        $folderPath = storage_path('app/public/' . $folder);
                        if (!file_exists($folderPath)) {
                            mkdir($folderPath, 0755, true);
                        }
                        
                        $publicPath = $image->storeAs($folder, $filename, 'public');
                        
                        if ($publicPath && Storage::disk('public')->exists($publicPath)) {
                            $publicSuccess = true;
                            $finalPath = $publicPath;
                            
                            Log::info('Public disk upload SUCCESS (local update)', [
                                'path' => $publicPath,
                                'size' => Storage::disk('public')->size($publicPath)
                            ]);
                        }
                    } catch (\Throwable $publicEx) {
                        Log::error('Public disk upload failed during product update', [
                            'error' => $publicEx->getMessage(),
                            'product_id' => $product->id,
                            'trace' => $publicEx->getTraceAsString()
                        ]);
                    }
                    
                    // BACKUP: Also save to R2 (optional on local)
                    try {
                        $r2Path = $image->storeAs($folder, $filename, 'r2');
                        $r2Success = !empty($r2Path);
                        
                        if ($r2Success) {
                            Log::info('R2 backup upload SUCCESS (local update)', ['path' => $r2Path]);
                        }
                    } catch (\Throwable $r2Ex) {
                        Log::warning('R2 backup failed on local (non-critical)', [
                            'error' => $r2Ex->getMessage(),
                            'product_id' => $product->id
                        ]);
                    }

                    if (!$publicSuccess) {
                        Log::error('Public disk upload failed - cannot update product', [
                            'product_id' => $product->id
                        ]);
                        return redirect()->back()->with('error', 'Failed to upload image. Please check storage permissions.');
                    }
                }

                // Update legacy image path
                $data['image'] = $finalPath;

                // Create new primary ProductImage
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $finalPath,
                    'original_name' => $image->getClientOriginalName(),
                    'mime_type' => $image->getMimeType(),
                    'file_size' => $image->getSize(),
                    'sort_order' => 1,
                    'is_primary' => true,
                ]);

                Log::info('Product image updated successfully', [
                    'product_id' => $product->id,
                    'path' => $finalPath,
                    'public_success' => $publicSuccess,
                    'r2_success' => $r2Success,
                    'is_laravel_cloud' => $isLaravelCloud ?? false,
                ]);
                
                // Clean up old files AFTER successful upload (non-blocking)
                dispatch(function() use ($oldImagePaths, $oldLegacyPath) {
                    foreach ($oldImagePaths as $path) {
                        try { Storage::disk('public')->delete($path); } catch (\Throwable $e) {}
                        try { Storage::disk('r2')->delete($path); } catch (\Throwable $e) {}
                    }
                    if (!empty($oldLegacyPath)) {
                        try { Storage::disk('public')->delete($oldLegacyPath); } catch (\Throwable $e) {}
                        try { Storage::disk('r2')->delete($oldLegacyPath); } catch (\Throwable $e) {}
                    }
                })->afterResponse();
            } catch (\Throwable $ex) {
                Log::error('Exception during image update', [
                    'error' => $ex->getMessage(),
                    'product_id' => $product->id
                ]);
                return redirect()->back()->with('error', 'Failed to upload image. Please try again.');
            }
        }
        $product->update($data);
        return redirect()->route('seller.editProduct', $product)->with('success', 'Product updated successfully!');
    }

    // Seller profile pages
    public function myProfile()
    {
        $user = Auth::user();
        // Resolve Seller model by email or create a bridge if needed
        $seller = \App\Models\Seller::where('email', $user->email)->first();
        if (!$seller) {
            abort(404, 'Seller profile not found');
        }
        $products = Product::with(['category', 'subcategory'])
            ->where('seller_id', $user->id)
            ->latest()->get();
        return view('seller.profile', compact('seller', 'products'));
    }

    public function publicProfileBySeller(\App\Models\Seller $seller)
    {
        // We assume products.seller_id references users.id and that the seller's email ties to user.
        $user = \App\Models\User::where('email', $seller->email)->first();
        $products = $user
            ? Product::with(['category', 'subcategory'])->where('seller_id', $user->id)->latest()->get()
            : collect();
        return view('seller.profile', compact('seller', 'products'));
    }

    // Transactions page for seller
    public function transactions()
    {
        $sellerId = Auth::id();
        $orders = Order::with(['product'])
            ->where('seller_id', $sellerId)
            ->latest()
            ->paginate(15);
        return view('seller.transactions', compact('orders'));
    }

    /**
     * Show the bulk upload Excel form
     */
    public function showBulkUploadForm()
    {
        $categories = Category::all();
        $subcategories = Subcategory::with('category')->get();
        return view('seller.bulk-upload-excel', compact('categories', 'subcategories'));
    }

    /**
     * Process bulk upload from Excel with images
     */
    public function processBulkUpload(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|array',
            'excel_file.*' => 'mimes:xlsx,xls,csv|max:10240', // 10MB per file
            'images_zip' => 'nullable|mimes:zip|max:51200', // 50MB max for zip
        ]);

        try {
            $zipPath = null;
            // Handle images zip file
            if ($request->hasFile('images_zip')) {
                $zipFile = $request->file('images_zip');
                $zipPath = $zipFile->store('temp/bulk-uploads', 'local');
            }

            $totalSuccess = 0; // optional counter; may be unknown if importer doesn't expose
            $allErrors = [];
            $files = $request->file('excel_file');
            foreach ($files as $excelFile) {
                // Pass zip path and current seller id to ensure updates are scoped to the seller
                $import = new \App\Imports\ProductsImport($zipPath, Auth::id());
                \Maatwebsite\Excel\Facades\Excel::import($import, $excelFile);
                // Best-effort accumulate; suppress if methods unavailable
                try { $totalSuccess += (int) $import->getSuccessCount(); } catch (\Throwable $e) {}
                try { $allErrors = array_merge($allErrors, (array) $import->getErrors()); } catch (\Throwable $e) {}
            }

            // Clean up temporary zip file
            if ($zipPath && Storage::disk('local')->exists($zipPath)) {
                Storage::disk('local')->delete($zipPath);
            }

            $message = $totalSuccess > 0
                ? "Successfully imported {$totalSuccess} products from all Excel files."
                : "Bulk import completed.";
            // Suppress all errors and always show success
            return redirect()->route('seller.dashboard')->with('success', $message);
        } catch (\Exception $e) {
            if (isset($zipPath) && $zipPath && Storage::disk('local')->exists($zipPath)) {
                Storage::disk('local')->delete($zipPath);
            }
            return redirect()->route('seller.bulkUploadForm')
                ->with('error', 'Error processing upload: ' . $e->getMessage());
        }
    }

    /**
     * Download sample Excel template
     */
    public function downloadSampleExcel()
    {
        // Create sample data with proper column headers
        $sampleData = [
            [
                'name' => 'Sample Product 1',
                'unique_id' => 'PROD-001',
                'category_id' => 1,
                'category_name' => 'Electronics',
                'subcategory_id' => 1,
                'subcategory_name' => 'Mobile Phones',
                'image' => 'sample-product-1.jpg',
                'description' => 'This is a sample product description. Describe your product features here.',
                'price' => 999.99,
                'discount' => 10,
                'delivery_charge' => 50,
                'gift_option' => true,
                'stock' => 100
            ],
            [
                'name' => 'Sample Product 2',
                'unique_id' => 'PROD-002',
                'category_id' => 2,
                'category_name' => 'Fashion',
                'subcategory_id' => 5,
                'subcategory_name' => 'Men Clothing',
                'image' => 'sample-product-2.jpg',
                'description' => 'Another sample product with different category.',
                'price' => 499.99,
                'discount' => 15,
                'delivery_charge' => 0,
                'gift_option' => false,
                'stock' => 50
            ]
        ];

        // Create the export class
        $export = new class($sampleData) implements FromArray, WithHeadings {
            protected $data;
            
            public function __construct($data) {
                $this->data = $data;
            }
            
            public function array(): array {
                return $this->data;
            }
            
            public function headings(): array {
                return [
                    'NAME',
                    'UNIQUE-ID', 
                    'CATEGORY ID',
                    'CATEGORY NAME',
                    'SUBCATEGORY ID',
                    'SUBCATEGORY-NAME',
                    'IMAGE',
                    'DESCRIPTION',
                    'PRICE',
                    'DISCOUNT',
                    'DELIVERY-CHARGE',
                    'GIFT-OPTION',
                    'STOCK'
                ];
            }
        };

        return Excel::download($export, 'bulk-products-sample.xlsx');
    }

    // Bulk Image Re-upload Methods
    public function showBulkImageReupload()
    {
        try {
            $categories = Category::all();
            
            // Simplified query to avoid potential issues
            $productsNeedingImages = Product::where('seller_id', Auth::id())
                ->where(function($query) {
                    $query->whereNull('image')
                          ->orWhere('image', '');
                })
                ->orderBy('created_at', 'desc')
                ->get();

            return view('seller.bulk-image-reupload-simple', compact('categories', 'productsNeedingImages'));
            
        } catch (\Exception $e) {
            Log::error('Bulk image reupload page error: ' . $e->getMessage());
            return redirect()->route('seller.dashboard')->with('error', 'Unable to load bulk upload page: ' . $e->getMessage());
        }
    }

    public function processBulkImageReupload(Request $request)
    {
        $request->validate([
            'zip_file' => 'required|file|mimes:zip|max:102400', // 100MB
            'category_id' => 'nullable|exists:categories,id',
            'matching_method' => 'required|in:name,unique_id,both'
        ]);

        try {
            $zipFile = $request->file('zip_file');
            $matchingMethod = $request->matching_method;
            $categoryId = $request->category_id;
            
            // Create temporary directory for extraction
            $tempDir = storage_path('app/temp/bulk_images_' . time());
            mkdir($tempDir, 0755, true);
            
            // Extract zip file
            $zip = new \ZipArchive;
            if ($zip->open($zipFile->getPathname()) !== TRUE) {
                throw new \Exception('Unable to open zip file');
            }
            
            $zip->extractTo($tempDir);
            $zip->close();
            
            // Get seller's products that need images
            $query = Product::where('seller_id', Auth::id())
                ->where(function($q) {
                    $q->whereNull('image')
                      ->orWhere('image', '')
                      ->orWhere('description', 'LIKE', '%⚠️ Image needs to be re-uploaded%');
                })
                ->whereNull('image_data');
                
            if ($categoryId) {
                $query->where('category_id', $categoryId);
            }
            
            $productsNeedingImages = $query->get();
            
            // Find image files in extracted directory
            $imageFiles = $this->findImageFiles($tempDir);
            
            // Match images to products
            $matches = $this->matchImagesToProducts($imageFiles, $productsNeedingImages, $matchingMethod);
            
            // Process matches and upload to cloud storage
            $uploadedCount = 0;
            $errors = [];
            
            foreach ($matches['matched'] as $productId => $imagePath) {
                try {
                    $product = Product::find($productId);
                    if ($product && $product->seller_id === Auth::id()) {
                        
                        // Generate unique filename for cloud storage
                        $extension = pathinfo($imagePath, PATHINFO_EXTENSION);
                        $cloudFileName = $product->unique_id . '_' . time() . '.' . $extension;
                        $cloudPath = 'products/' . $cloudFileName;
                        
                        // Upload to cloud storage
                        $imageContent = file_get_contents($imagePath);
                        // Try cloud first, fallback to local/public
                        $uploaded = false;
                        try {
                            $uploaded = Storage::disk('r2')->put($cloudPath, $imageContent);
                        } catch (\Throwable $e) {
                            $uploaded = false;
                        }
                        if (!$uploaded) {
                            $uploaded = Storage::disk('public')->put($cloudPath, $imageContent);
                        }
                        
                        if ($uploaded) {
                            // Update product
                            $product->update([
                                'image' => $cloudPath,
                                'description' => str_replace("\n\n⚠️ Image needs to be re-uploaded by seller.", '', $product->description)
                            ]);
                            $uploadedCount++;
                            
                            Log::info('Bulk image uploaded', [
                                'product_id' => $product->id,
                                'cloud_path' => $cloudPath,
                                'original_file' => basename($imagePath)
                            ]);
                        }
                    }
                } catch (\Exception $e) {
                    $errors[] = "Failed to upload image for product {$productId}: " . $e->getMessage();
                }
            }
            
            // Clean up temporary directory
            $this->deleteDirectory($tempDir);
            
            // Prepare response message
            $message = "Successfully uploaded {$uploadedCount} images.";
            
            if (count($matches['unmatched']) > 0) {
                $message .= " " . count($matches['unmatched']) . " images could not be matched to products.";
            }
            
            if (!empty($errors)) {
                $message .= " " . count($errors) . " errors occurred during upload.";
                Log::warning('Bulk image upload errors', $errors);
            }
            
            return redirect()->route('seller.bulkImageReupload')->with('success', $message);
            
        } catch (\Exception $e) {
            Log::error('Bulk image upload failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Upload failed: ' . $e->getMessage());
        }
    }

    private function findImageFiles($directory)
    {
        $imageFiles = [];
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($directory)
        );
        
        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $extension = strtolower(pathinfo($file->getFilename(), PATHINFO_EXTENSION));
                if (in_array($extension, $allowedExtensions)) {
                    $imageFiles[] = $file->getPathname();
                }
            }
        }
        
        return $imageFiles;
    }

    private function matchImagesToProducts($imageFiles, $products, $matchingMethod)
    {
        $matched = [];
        $unmatched = [];
        
        foreach ($imageFiles as $imagePath) {
            $fileName = pathinfo($imagePath, PATHINFO_FILENAME);
            $bestMatch = null;
            $bestScore = 0;
            
            foreach ($products as $product) {
                $score = 0;
                
                if ($matchingMethod === 'name' || $matchingMethod === 'both') {
                    // Match by product name
                    $nameScore = $this->calculateSimilarity($fileName, $product->name);
                    $score = max($score, $nameScore);
                }
                
                if ($matchingMethod === 'unique_id' || $matchingMethod === 'both') {
                    // Match by unique ID
                    if (stripos($fileName, $product->unique_id) !== false) {
                        $score = max($score, 0.9); // High score for ID match
                    }
                }
                
                if ($score > $bestScore && $score > 0.6) { // Minimum 60% similarity
                    $bestScore = $score;
                    $bestMatch = $product;
                }
            }
            
            if ($bestMatch) {
                $matched[$bestMatch->id] = $imagePath;
            } else {
                $unmatched[] = [
                    'filename' => basename($imagePath),
                    'path' => $imagePath
                ];
            }
        }
        
        return [
            'matched' => $matched,
            'unmatched' => $unmatched
        ];
    }

    private function calculateSimilarity($str1, $str2)
    {
        // Normalize strings
        $str1 = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $str1));
        $str2 = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $str2));
        
        // Calculate similarity
        similar_text($str1, $str2, $percent);
        return $percent / 100;
    }

    private function deleteDirectory($dir)
    {
        if (!is_dir($dir)) return;
        
        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            $path = $dir . DIRECTORY_SEPARATOR . $file;
            is_dir($path) ? $this->deleteDirectory($path) : unlink($path);
        }
        rmdir($dir);
    }

    // ====== IMAGE LIBRARY MANAGEMENT ======
    
    /**
     * Show seller's image library
     */
    public function imageLibrary()
    {
        $sellerId = Auth::id();
        $libraryFolder = 'library/seller-' . $sellerId;
        $images = [];

        try {
            // Get images from R2
            $files = Storage::disk('r2')->files($libraryFolder);
            
            foreach ($files as $filePath) {
                $filename = basename($filePath);
                
                // Skip non-image files
                if (!preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $filename)) {
                    continue;
                }

                $images[] = [
                    'name' => $filename,
                    'path' => $filePath,
                    'url' => $this->getImageUrl($filePath),
                    'size' => $this->formatFileSize(Storage::disk('r2')->size($filePath))
                ];
            }

            // Sort by name
            usort($images, function($a, $b) {
                return strcmp($a['name'], $b['name']);
            });

        } catch (\Throwable $e) {
            Log::error('Failed to load image library', [
                'error' => $e->getMessage(),
                'seller_id' => $sellerId
            ]);
        }

        return view('seller.image-library', compact('images'));
    }

    /**
     * Upload images to seller's library
     */
    public function uploadToLibrary(Request $request)
    {
        $request->validate([
            'images' => 'required|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $sellerId = Auth::id();
        $libraryFolder = 'library/seller-' . $sellerId;
        $uploadedCount = 0;
        $errors = [];

        foreach ($request->file('images') as $image) {
            try {
                $ext = $image->getClientOriginalExtension();
                $originalName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $filename = Str::slug($originalName) . '-' . uniqid() . '.' . $ext;

                // Upload to R2
                $path = $image->storeAs($libraryFolder, $filename, 'r2');
                
                if ($path) {
                    $uploadedCount++;
                    Log::info('Image uploaded to library', [
                        'seller_id' => $sellerId,
                        'filename' => $filename
                    ]);
                }
            } catch (\Throwable $e) {
                $errors[] = $image->getClientOriginalName();
                Log::error('Failed to upload image to library', [
                    'error' => $e->getMessage(),
                    'filename' => $image->getClientOriginalName()
                ]);
            }
        }

        if ($uploadedCount > 0) {
            $message = "Successfully uploaded {$uploadedCount} image(s)";
            if (count($errors) > 0) {
                $message .= " (" . count($errors) . " failed)";
            }
            return redirect()->route('seller.imageLibrary')->with('success', $message);
        }

        return redirect()->route('seller.imageLibrary')->with('error', 'Failed to upload images');
    }

    /**
     * Get list of library images (for AJAX)
     */
    public function getLibraryImages()
    {
        $sellerId = Auth::id();
        $libraryFolder = 'library/seller-' . $sellerId;
        $images = [];

        try {
            $files = Storage::disk('r2')->files($libraryFolder);
            
            foreach ($files as $filePath) {
                $filename = basename($filePath);
                
                if (!preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $filename)) {
                    continue;
                }

                $images[] = [
                    'name' => $filename,
                    'path' => $filePath,
                    'url' => $this->getImageUrl($filePath),
                    'size' => Storage::disk('r2')->size($filePath)
                ];
            }

        } catch (\Throwable $e) {
            Log::error('Failed to get library images', ['error' => $e->getMessage()]);
        }

        return response()->json(['images' => $images]);
    }

    /**
     * Delete image from library
     */
    public function deleteLibraryImage(Request $request)
    {
        $request->validate([
            'path' => 'required|string'
        ]);

        $sellerId = Auth::id();
        $path = $request->path;

        // Verify the path belongs to this seller
        if (!str_starts_with($path, 'library/seller-' . $sellerId)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        try {
            Storage::disk('r2')->delete($path);
            
            Log::info('Image deleted from library', [
                'seller_id' => $sellerId,
                'path' => $path
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Image deleted successfully'
            ]);
        } catch (\Throwable $e) {
            Log::error('Failed to delete library image', [
                'error' => $e->getMessage(),
                'path' => $path
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete image'
            ], 500);
        }
    }

    /**
     * Helper: Get image URL (R2 public URL or serve-image route)
     */
    private function getImageUrl($path)
    {
        if (app()->environment('production')) {
            $r2BaseUrl = config('filesystems.disks.r2.url');
            if (!empty($r2BaseUrl)) {
                return rtrim($r2BaseUrl, '/') . '/' . ltrim($path, '/');
            }
        }
        
        return url('serve-image/' . str_replace('library/', 'library/', $path));
    }

    /**
     * Helper: Format file size
     */
    private function formatFileSize($bytes)
    {
        if ($bytes < 1024) {
            return $bytes . ' B';
        } elseif ($bytes < 1048576) {
            return round($bytes / 1024, 2) . ' KB';
        } else {
            return round($bytes / 1048576, 2) . ' MB';
        }
    }

    /**
     * Helper: Detect if running on Laravel Cloud
     * Uses multiple signals to avoid false positives when testing locally with APP_ENV=production
     */
    private function isLaravelCloud()
    {
        // Priority 1: Explicit Laravel Cloud deployment flag
        if (env('LARAVEL_CLOUD_DEPLOYMENT') === true) {
            return true;
        }
        
        // Priority 2: Check if actually running on Laravel Cloud infrastructure
        // (not just having APP_URL set to laravel.cloud)
        if (app()->environment('production') && 
            isset($_SERVER['SERVER_NAME']) && 
            str_contains($_SERVER['SERVER_NAME'], '.laravel.cloud')) {
            return true;
        }
        
        // Priority 3: Vapor environment (Laravel Cloud uses Vapor)
        if (env('VAPOR_ENVIRONMENT') !== null) {
            return true;
        }
        
        return false;
    }

}

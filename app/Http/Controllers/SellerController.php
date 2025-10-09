<?php
namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Product;
use App\Models\Order;
use App\Imports\ProductsImport;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Exception;

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
                $folder = "seller/{$sellerId}/{$data['category_id']}/{$data['subcategory_id']}";
                $path = $img->store($folder, 'public');
                $product->image = $path;
                $updatedImages++;
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
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();
        return redirect()->route('seller.dashboard')->with('success', 'Product deleted!');
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
        $user = Auth::user();
        $seller = \App\Models\Seller::where('email', $user->email)->firstOrFail();
        $seller->update($request->only([
            'store_name',
            'gst_number',
            'store_address',
            'store_contact'
        ]));
        return redirect()->route('seller.profile')->with('success', 'Store info updated!');
    }
    public function dashboard()
    {
        $products = Product::with(['category', 'subcategory'])
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
        // Log the start of upload attempt
        Log::info('Bulk upload attempt started', [
            'user_id' => Auth::id(),
            'memory_limit' => ini_get('memory_limit'),
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'max_execution_time' => ini_get('max_execution_time')
        ]);
        
        // Increase time limit and memory for large uploads
        set_time_limit(300); // 5 minutes
        ini_set('memory_limit', '512M');
        
        try {
            $request->validate([
                'images_zip' => 'required|file|mimes:zip|max:51200', // 50MB max
            ]);

            $zipFile = $request->file('images_zip');
            
            // Enhanced file validation
            if (!$zipFile || !$zipFile->isValid()) {
                throw new \Exception('Invalid ZIP file uploaded');
            }
            
            // Check file size before processing
            $fileSize = $zipFile->getSize();
            Log::info('Processing ZIP file', [
                'filename' => $zipFile->getClientOriginalName(),
                'size_mb' => round($fileSize / 1024 / 1024, 2),
                'mime_type' => $zipFile->getMimeType()
            ]);
            
            if ($fileSize > 50 * 1024 * 1024) { // 50MB
                return redirect()->back()->with('error', 'ZIP file is too large. Maximum size is 50MB.');
            }
            
            $zipPath = $zipFile->store('temp', 'local');
            $fullZipPath = storage_path('app/' . $zipPath);
            
            // Verify ZIP file exists
            if (!file_exists($fullZipPath)) {
                Log::error('ZIP file not found after upload', ['path' => $fullZipPath]);
                return redirect()->back()->with('error', 'Failed to upload ZIP file.');
            }
            
            $zip = new \ZipArchive();
            $updated = 0;
            $errors = [];
            $processed = 0;
            
            if ($zip->open($fullZipPath) === TRUE) {
                $totalFiles = $zip->numFiles;
                Log::info('ZIP opened successfully', ['total_files' => $totalFiles]);
                
                // Reduced limit to prevent 502 errors
                $maxFiles = min($totalFiles, 30); // Process max 30 files at once to prevent timeout
                
                for ($i = 0; $i < $maxFiles; $i++) {
                    $processed++;
                    
                    // Log progress every 5 files
                    if ($processed % 5 == 0) {
                        Log::info("Processing file $processed of $maxFiles", [
                            'memory_usage_mb' => round(memory_get_usage(true) / 1024 / 1024, 2)
                        ]);
                    }
                    
                    // Clear memory periodically
                    if ($processed % 10 == 0) {
                        gc_collect_cycles();
                    }
                    
                    // Check memory usage and stop if too high
                    $memoryUsage = memory_get_usage(true);
                    if ($memoryUsage > 300 * 1024 * 1024) { // 300MB threshold
                        Log::warning('Memory usage too high, stopping processing', [
                            'memory_mb' => round($memoryUsage / 1024 / 1024, 2)
                        ]);
                        $errors[] = "Processing stopped due to memory limits. Please use smaller ZIP files.";
                        break;
                    }
                    
                    $filename = $zip->getNameIndex($i);
                    if (empty($filename) || strpos($filename, '__MACOSX') !== false || is_dir($filename)) {
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
                        
                        // Check individual image size
                        if (strlen($imageContent) > 5 * 1024 * 1024) { // 5MB per image
                            $errors[] = "Image too large (>5MB): $basename";
                            continue;
                        }
                        
                        // Validate image content
                        $finfo = new \finfo(FILEINFO_MIME_TYPE);
                        $mimeType = $finfo->buffer($imageContent);
                        
                        if (!in_array($mimeType, ['image/jpeg', 'image/png', 'image/gif', 'image/webp'])) {
                            $errors[] = "Invalid image type for $uniqueId: $mimeType";
                            continue;
                        }
                        
                        $product = Product::where('unique_id', $uniqueId)
                            ->where('seller_id', Auth::id())
                            ->first();
                            
                        if ($product) {
                            $extension = pathinfo($basename, PATHINFO_EXTENSION) ?: 'jpg';
                            $uniqueName = Str::random(40) . '.' . $extension;
                            $storagePath = 'products/' . $uniqueName;
                            
                            if (Storage::disk('public')->put($storagePath, $imageContent)) {
                                // Delete old image if exists
                                if ($product->image && Storage::disk('public')->exists($product->image)) {
                                    Storage::disk('public')->delete($product->image);
                                }
                                $product->image = $storagePath;
                                $product->save();
                                $updated++;
                                
                                // Log successful update
                                if ($updated % 5 == 0) {
                                    Log::info("Successfully updated $updated products so far");
                                }
                            } else {
                                $errors[] = "Failed to save image for product $uniqueId";
                            }
                        } else {
                            $errors[] = "No product found for unique_id: $uniqueId";
                        }
                        
                    } catch (\Exception $e) {
                        $errors[] = "Error processing $basename: " . $e->getMessage();
                        Log::error("Error processing individual file", [
                            'filename' => $basename,
                            'error' => $e->getMessage()
                        ]);
                    }
                }
                $zip->close();
                
                if ($totalFiles > $maxFiles) {
                    $message = "Only processed first $maxFiles files out of $totalFiles. Please split your ZIP file into smaller batches to avoid server timeouts.";
                    $errors[] = $message;
                    Log::warning($message);
                }
            } else {
                $error = 'Could not open ZIP file. Please ensure it is a valid ZIP file.';
                $errors[] = $error;
                Log::error('Failed to open ZIP file', ['path' => $fullZipPath]);
            }
            
            // Clean up temp file
            if (file_exists($fullZipPath)) {
                Storage::delete($zipPath);
            }
            
            // Log completion
            Log::info('Bulk upload completed', [
                'updated' => $updated,
                'processed' => $processed,
                'errors' => count($errors),
                'final_memory_mb' => round(memory_get_usage(true) / 1024 / 1024, 2)
            ]);
            
            $msg = "$updated product images updated successfully.";
            if ($errors) {
                $errorMsg = implode(' | ', array_slice($errors, 0, 3)); // Limit error display
                if (count($errors) > 3) {
                    $errorMsg .= ' | And ' . (count($errors) - 3) . ' more errors...';
                }
                $msg .= ' Errors: ' . $errorMsg;
            }
            
            return redirect()->route('seller.dashboard')->with('bulk_upload_success', $msg);
            
        } catch (\Throwable $e) {
            // Clean up temp file on error
            if (isset($zipPath) && Storage::exists($zipPath)) {
                Storage::delete($zipPath);
            }
            
            // Enhanced error logging
            Log::error('Bulk image upload failed', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'memory_mb' => round(memory_get_usage(true) / 1024 / 1024, 2)
            ]);
            
            // Determine error type and provide helpful message
            $errorMessage = 'Upload failed: ';
            if (strpos($e->getMessage(), 'memory') !== false) {
                $errorMessage .= 'Not enough memory. Try uploading smaller ZIP files (max 20-30 images).';
            } elseif (strpos($e->getMessage(), 'time') !== false || strpos($e->getMessage(), 'timeout') !== false) {
                $errorMessage .= 'Processing took too long. Try splitting into smaller batches.';
            } elseif (strpos($e->getMessage(), 'zip') !== false) {
                $errorMessage .= 'Invalid ZIP file. Please ensure it\'s a valid ZIP archive.';
            } else {
                $errorMessage .= $e->getMessage() . '. Check server logs for details.';
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
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'required|exists:subcategories,id',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'delivery_charge' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gift_option' => 'required|in:yes,no',
            'stock' => 'required|integer|min:0',
        ]);
        $unique_id = Str::upper(Str::random(2)) . rand(0, 9);
        $imagePath = null;
        
        if ($request->hasFile('image')) {
            try {
                $image = $request->file('image');
                $sellerId = Auth::id();
                $categoryId = $request->category_id;
                $subcategoryId = $request->subcategory_id;
                
                // Create unique filename
                $extension = $image->getClientOriginalExtension();
                $filename = $unique_id . '_' . time() . '.' . $extension;
                
                // Store in products folder for consistency
                $folder = "products";
                
                // Try R2 first, fallback to local storage
                $uploadSuccess = false;
                
                try {
                    // Attempt R2 upload
                    $imagePath = $image->storeAs($folder, $filename, 'r2');
                    $uploadSuccess = true;
                    Log::info('Image uploaded to R2 storage', ['path' => $imagePath]);
                } catch (Exception $e) {
                    Log::warning('R2 upload failed, falling back to local storage: ' . $e->getMessage());
                    
                    try {
                        // Fallback to local storage
                        $imagePath = $image->storeAs($folder, $filename, 'public');
                        $uploadSuccess = true;
                        Log::info('Image uploaded to local storage', ['path' => $imagePath]);
                    } catch (Exception $localE) {
                        Log::error('Both R2 and local storage failed', [
                            'r2_error' => $e->getMessage(),
                            'local_error' => $localE->getMessage()
                        ]);
                        return redirect()->back()->withInput()->with('error', 'Failed to upload image. Please try again.');
                    }
                }
                
                if (!$uploadSuccess) {
                    return redirect()->back()->withInput()->with('error', 'Image upload failed. Please try again.');
                }
                
            } catch (Exception $e) {
                Log::error('Image processing failed: ' . $e->getMessage());
                return redirect()->back()->withInput()->with('error', 'Image upload failed. Please try again.');
            }
        }
        $product = Product::create([
            'name' => $request->name,
            'unique_id' => $unique_id,
            'category_id' => $request->category_id,
            'subcategory_id' => $request->subcategory_id,
            'seller_id' => Auth::id(),
            'image' => $imagePath,
            'description' => $request->description,
            'price' => $request->price,
            'discount' => $request->discount ?? 0,
            'delivery_charge' => $request->delivery_charge ?? 0,
            'gift_option' => $request->gift_option,
            'stock' => $request->stock,
        ]);
        
        $successMessage = "Product '{$product->name}' (ID: {$product->unique_id}) added successfully!";
        if ($imagePath) {
            $successMessage .= " Image uploaded and saved.";
        }
        
        return redirect()->route('seller.dashboard')->with('success', $successMessage);
    }

    public function editProduct(Product $product)
    {
        // Ensure only owner can edit
        if ($product->seller_id !== Auth::id()) {
            return redirect()->route('seller.dashboard')->with('error', 'Unauthorized access to product.');
        }
        $categories = Category::all();
        $subcategories = Subcategory::all();
        return view('seller.edit-product', compact('product', 'categories', 'subcategories'));
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        $data = $request->only(['name', 'category_id', 'subcategory_id', 'description', 'price', 'discount', 'delivery_charge']);
        if ($request->hasFile('image')) {
            // Delete old image if it exists (check both local and R2)
            if ($product->image) {
                try {
                    if (Storage::disk('public')->exists($product->image)) {
                        Storage::disk('public')->delete($product->image);
                    }
                } catch (Exception $e) {
                    Log::warning('Failed to delete local image: ' . $e->getMessage());
                }
                
                try {
                    if (Storage::disk('r2')->exists($product->image)) {
                        Storage::disk('r2')->delete($product->image);
                    }
                } catch (Exception $e) {
                    Log::warning('Failed to delete R2 image: ' . $e->getMessage());
                }
            }
            
            $sellerId = Auth::id();
            $categoryId = $request->category_id;
            $subcategoryId = $request->subcategory_id;
            $folder = "seller/{$sellerId}/{$categoryId}/{$subcategoryId}";
            
            // Try R2 first, fallback to local storage
            $imagePath = null;
            $uploadSuccess = false;
            
            try {
                // Attempt R2 upload
                $imagePath = $request->file('image')->store($folder, 'r2');
                $uploadSuccess = true;
                Log::info('Image uploaded to R2 storage', ['path' => $imagePath]);
            } catch (Exception $e) {
                Log::warning('R2 upload failed, falling back to local storage: ' . $e->getMessage());
                
                try {
                    // Fallback to local storage
                    $imagePath = $request->file('image')->store($folder, 'public');
                    $uploadSuccess = true;
                    Log::info('Image uploaded to local storage', ['path' => $imagePath]);
                } catch (Exception $localE) {
                    Log::error('Both R2 and local storage failed', [
                        'r2_error' => $e->getMessage(),
                        'local_error' => $localE->getMessage()
                    ]);
                    return redirect()->back()->with('error', 'Failed to upload image. Please try again.');
                }
            }
            
            if ($uploadSuccess && $imagePath) {
                $data['image'] = $imagePath;
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
            'excel_file' => 'required|mimes:xlsx,xls,csv|max:10240', // 10MB max
            'images_zip' => 'nullable|mimes:zip|max:51200', // 50MB max for zip
        ]);

        try {
            $zipPath = null;
            
            // Handle images zip file
            if ($request->hasFile('images_zip')) {
                $zipFile = $request->file('images_zip');
                $zipPath = $zipFile->store('temp/bulk-uploads', 'local');
            }

            // Process Excel file
            $import = new ProductsImport($zipPath);
            Excel::import($import, $request->file('excel_file'));

            // Clean up temporary zip file
            if ($zipPath && Storage::disk('local')->exists($zipPath)) {
                Storage::disk('local')->delete($zipPath);
            }

            $successCount = $import->getSuccessCount();
            $errors = $import->getErrors();

            $message = "Successfully imported {$successCount} products.";
            
            if (!empty($errors)) {
                $message .= " However, there were some errors: " . implode(', ', array_slice($errors, 0, 5));
                if (count($errors) > 5) {
                    $message .= "... and " . (count($errors) - 5) . " more errors.";
                }
                return redirect()->route('seller.bulkUploadForm')
                    ->with('warning', $message)
                    ->with('errors', $errors);
            }

            return redirect()->route('seller.dashboard')->with('success', $message);

        } catch (\Exception $e) {
            // Clean up on error
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
}

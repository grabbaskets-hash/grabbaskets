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
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

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
        $request->validate([
            'images_zip' => 'required|file|mimes:zip|max:51200', // 50MB max
        ]);

        $zipFile = $request->file('images_zip');
        $zipPath = $zipFile->store('temp', 'local');
        $fullZipPath = storage_path('app/' . $zipPath);
        $zip = new \ZipArchive();
        $updated = 0;
        $errors = [];
        if ($zip->open($fullZipPath) === TRUE) {
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $filename = $zip->getNameIndex($i);
                $basename = pathinfo($filename, PATHINFO_BASENAME);
                $uniqueId = pathinfo($basename, PATHINFO_FILENAME);
                $imageContent = $zip->getFromIndex($i);
                if ($imageContent !== false) {
                    $product = Product::where('unique_id', $uniqueId)
                        ->where('seller_id', Auth::id())
                        ->first();
                    if ($product) {
                        $extension = pathinfo($basename, PATHINFO_EXTENSION) ?: 'jpg';
                        $uniqueName = Str::random(40) . '.' . $extension;
                        $storagePath = 'products/' . $uniqueName;
                        if (Storage::disk('public')->put($storagePath, $imageContent)) {
                            // Delete old image if exists
                            if ($product->image) {
                                Storage::disk('public')->delete($product->image);
                            }
                            $product->image = $storagePath;
                            $product->save();
                            $updated++;
                        } else {
                            $errors[] = "Failed to save image for product $uniqueId";
                        }
                    } else {
                        $errors[] = "No product found for unique_id: $uniqueId";
                    }
                }
            }
            $zip->close();
        } else {
            $errors[] = 'Could not open ZIP file.';
        }
        // Clean up temp file
        Storage::delete($zipPath);
        $msg = "$updated product images updated.";
        if ($errors) {
            $msg .= ' Errors: ' . implode(' | ', $errors);
        }
        return redirect()->route('seller.dashboard')->with('bulk_upload_success', $msg);
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
            $sellerId = Auth::id();
            $categoryId = $request->category_id;
            $subcategoryId = $request->subcategory_id;
            $folder = "seller/{$sellerId}/{$categoryId}/{$subcategoryId}";
            $imagePath = $request->file('image')->store($folder, 'public');
        }
        Product::create([
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
        return redirect()->route('seller.dashboard')->with('success', 'Product added!');
    }

    public function editProduct(Product $product)
    {
        // Ensure only owner can edit
        if ($product->seller_id !== Auth::id()) {
            abort(403);
        }
        $categories = Category::all();
        $subcategories = Subcategory::all();
        return view('seller.edit-product', compact('product', 'categories', 'subcategories'));
    }

    public function updateProduct(Request $request, Product $product)
    {
        if ($product->seller_id !== Auth::id()) {
            abort(403);
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
            $sellerId = Auth::id();
            $categoryId = $request->category_id;
            $subcategoryId = $request->subcategory_id;
            $folder = "seller/{$sellerId}/{$categoryId}/{$subcategoryId}";
            $data['image'] = $request->file('image')->store($folder, 'public');
        }
        $product->update($data);
        return redirect()->route('seller.dashboard')->with('success', 'Product updated!');
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

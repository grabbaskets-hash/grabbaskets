<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;

class ProductImportExportController extends Controller
{
    /**
     * Show import/export page
     */
    public function index()
    {
        try {
            $seller = Auth::user();
            
            if (!$seller) {
                return redirect()->route('login')->with('error', 'Please login to access this page.');
            }
            
            $productsCount = Product::where('seller_id', $seller->id)->count();
            
            return view('seller.import-export', compact('productsCount'));
        } catch (\Exception $e) {
            Log::error('Import/Export page error: ' . $e->getMessage());
            
            if (config('app.debug')) {
                throw $e;
            }
            
            return redirect()->route('seller.dashboard')
                ->with('error', 'Unable to load import/export page. Please try again later.');
        }
    }

    /**
     * Export products to Excel
     */
    public function exportExcel()
    {
        try {
            $seller = Auth::user();
            $products = Product::where('seller_id', $seller->id)
                ->with(['category', 'subcategory'])
                ->get();

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Set headers
            $headers = [
                'Product ID',
                'Product Name',
                'Description',
                'Category',
                'Subcategory',
                'Price',
                'Original Price',
                'Discount %',
                'Stock',
                'SKU',
                'Barcode',
                'Weight (kg)',
                'Dimensions (LxWxH cm)',
                'Brand',
                'Model',
                'Color',
                'Size',
                'Material',
                'Status',
                'Featured',
                'Tags',
                'Meta Title',
                'Meta Description',
                'Image URL',
                'Created Date'
            ];

            // Style header row
            $sheet->fromArray($headers, NULL, 'A1');
            $sheet->getStyle('A1:Y1')->getFont()->setBold(true);
            $sheet->getStyle('A1:Y1')->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('FFE0E0E0');

            // Add data
            $row = 2;
            foreach ($products as $product) {
                $sheet->fromArray([
                    $product->unique_id ?? $product->id,
                    $product->name,
                    strip_tags($product->description ?? ''),
                    $product->category->name ?? '',
                    $product->subcategory->name ?? '',
                    $product->price,
                    $product->original_price,
                    $product->discount ?? 0,
                    $product->stock,
                    $product->sku ?? '',
                    $product->barcode ?? '',
                    $product->weight ?? '',
                    $product->dimensions ?? '',
                    $product->brand ?? '',
                    $product->model ?? '',
                    $product->color ?? '',
                    $product->size ?? '',
                    $product->material ?? '',
                    $product->status ?? 'active',
                    $product->featured ? 'Yes' : 'No',
                    $product->tags ?? '',
                    $product->meta_title ?? '',
                    $product->meta_description ?? '',
                    $product->getLegacyImageUrl() ?? '',
                    $product->created_at->format('Y-m-d')
                ], NULL, "A{$row}");
                $row++;
            }

            // Auto-size columns
            foreach (range('A', 'Y') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            // Generate filename
            $filename = 'products_' . $seller->business_name . '_' . date('Y-m-d_His') . '.xlsx';
            $tempFile = storage_path('app/temp/' . $filename);

            // Ensure temp directory exists
            if (!file_exists(storage_path('app/temp'))) {
                mkdir(storage_path('app/temp'), 0755, true);
            }

            $writer = new Xlsx($spreadsheet);
            $writer->save($tempFile);

            return response()->download($tempFile, $filename)->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            Log::error('Export Excel Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to export products: ' . $e->getMessage());
        }
    }

    /**
     * Export products to CSV
     */
    public function exportCsv()
    {
        try {
            $seller = Auth::user();
            $products = Product::where('seller_id', $seller->id)
                ->with(['category', 'subcategory'])
                ->get();

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Set headers
            $headers = [
                'Product ID', 'Product Name', 'Description', 'Category', 'Subcategory',
                'Price', 'Original Price', 'Discount %', 'Stock', 'SKU', 'Barcode',
                'Weight (kg)', 'Dimensions', 'Brand', 'Model', 'Color', 'Size',
                'Material', 'Status', 'Featured', 'Tags', 'Meta Title',
                'Meta Description', 'Image URL', 'Created Date'
            ];

            $sheet->fromArray($headers, NULL, 'A1');

            // Add data
            $row = 2;
            foreach ($products as $product) {
                $sheet->fromArray([
                    $product->unique_id ?? $product->id,
                    $product->name,
                    strip_tags($product->description ?? ''),
                    $product->category->name ?? '',
                    $product->subcategory->name ?? '',
                    $product->price,
                    $product->original_price,
                    $product->discount ?? 0,
                    $product->stock,
                    $product->sku ?? '',
                    $product->barcode ?? '',
                    $product->weight ?? '',
                    $product->dimensions ?? '',
                    $product->brand ?? '',
                    $product->model ?? '',
                    $product->color ?? '',
                    $product->size ?? '',
                    $product->material ?? '',
                    $product->status ?? 'active',
                    $product->featured ? 'Yes' : 'No',
                    $product->tags ?? '',
                    $product->meta_title ?? '',
                    $product->meta_description ?? '',
                    $product->getLegacyImageUrl() ?? '',
                    $product->created_at->format('Y-m-d')
                ], NULL, "A{$row}");
                $row++;
            }

            $filename = 'products_' . Str::slug($seller->business_name) . '_' . date('Y-m-d_His') . '.csv';
            $tempFile = storage_path('app/temp/' . $filename);

            if (!file_exists(storage_path('app/temp'))) {
                mkdir(storage_path('app/temp'), 0755, true);
            }

            $writer = new Csv($spreadsheet);
            $writer->save($tempFile);

            return response()->download($tempFile, $filename)->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            Log::error('Export CSV Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to export products: ' . $e->getMessage());
        }
    }

    /**
     * Export products to PDF
     */
    public function exportPdf()
    {
        try {
            $seller = Auth::user();
            $products = Product::where('seller_id', $seller->id)
                ->with(['category', 'subcategory'])
                ->get();

            $pdf = Pdf::loadView('seller.exports.products-pdf', [
                'products' => $products,
                'seller' => $seller,
                'exportDate' => now()
            ]);

            $pdf->setPaper('a4', 'landscape');
            
            $filename = 'products_' . Str::slug($seller->business_name) . '_' . date('Y-m-d') . '.pdf';
            
            return $pdf->download($filename);

        } catch (\Exception $e) {
            Log::error('Export PDF Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to export PDF: ' . $e->getMessage());
        }
    }

    /**
     * Export products to PDF with images, organized by category
     */
    public function exportPdfWithImages()
    {
        try {
            $seller = Auth::user();
            
            // Get all products with images, grouped by category
            $products = Product::where('seller_id', $seller->id)
                ->with(['category', 'subcategory', 'images'])
                ->orderBy('category_id')
                ->orderBy('name')
                ->get();

            // Group products by category
            $productsByCategory = $products->groupBy(function($product) {
                return $product->category->name ?? 'Uncategorized';
            });

            // Calculate statistics
            $stats = [
                'total_products' => $products->count(),
                'total_categories' => $productsByCategory->count(),
                'total_stock' => $products->sum('stock'),
                'total_value' => $products->sum(function($product) {
                    return $product->price * $product->stock;
                }),
                'active_products' => $products->where('status', 'active')->count(),
                'out_of_stock' => $products->where('stock', '<=', 0)->count(),
            ];

            $pdf = Pdf::loadView('seller.exports.products-pdf-with-images', [
                'productsByCategory' => $productsByCategory,
                'seller' => $seller,
                'exportDate' => now(),
                'stats' => $stats
            ]);

            // Set paper size to A4 portrait for better image display
            $pdf->setPaper('a4', 'portrait');
            
            // CRITICAL: Enable remote file access for loading images from URLs
            $pdf->setOption('isRemoteEnabled', true);
            $pdf->setOption('isHtml5ParserEnabled', true);
            $pdf->setOption('enable_remote', true);
            $pdf->setOption('chroot', base_path());
            
            // Increase timeout for large PDFs with images
            set_time_limit(300); // 5 minutes
            ini_set('memory_limit', '512M');
            
            $filename = 'products_with_images_' . Str::slug($seller->business_name ?? $seller->name) . '_' . date('Y-m-d') . '.pdf';
            
            return $pdf->download($filename);

        } catch (\Exception $e) {
            Log::error('Export PDF with Images Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Failed to export PDF with images: ' . $e->getMessage());
        }
    }

    /**
     * Import products from file (Excel/CSV)
     * Intelligently detects headers and maps to correct fields
     */
    public function import(Request $request)
    {
        // Enhanced validation with custom messages
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240'
        ], [
            'file.required' => 'Please select a file to import.',
            'file.file' => 'The uploaded file is invalid.',
            'file.mimes' => 'File must be in Excel (.xlsx, .xls) or CSV (.csv) format.',
            'file.max' => 'File size must not exceed 10MB.'
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please fix the validation errors and try again.');
        }

        try {
            $seller = Auth::user();
            $file = $request->file('file');
            
            // Check file extension manually as extra validation
            $extension = strtolower($file->getClientOriginalExtension());
            if (!in_array($extension, ['xlsx', 'xls', 'csv'])) {
                return back()->with('error', 'Invalid file format. Only Excel (.xlsx, .xls) and CSV (.csv) files are allowed.');
            }
            
            // Log import attempt
            Log::info('Import attempt', [
                'seller_id' => $seller->id,
                'filename' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'extension' => $extension
            ]);
            
            // Load spreadsheet
            try {
                $spreadsheet = IOFactory::load($file->getPathname());
            } catch (\Exception $e) {
                Log::error('Failed to load spreadsheet', ['error' => $e->getMessage()]);
                return back()->with('error', 'Failed to read file. Please ensure it is a valid Excel or CSV file and not corrupted.');
            }
            
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray();

            if (empty($data)) {
                return back()->with('error', 'File is empty. Please add data to your spreadsheet and try again.');
            }
            
            if (count($data) < 2) {
                return back()->with('error', 'File must contain at least one data row (in addition to headers).');
            }

            // Detect and map headers
            $headerRow = $data[0];
            $headerMap = $this->detectHeaderMapping($headerRow);

            Log::info('Header mapping detected', [
                'headers' => $headerRow,
                'map' => $headerMap
            ]);
            
            // Check if we have at least one mapped field
            if (empty($headerMap)) {
                return back()->with('error', 'Could not detect any valid columns. Please ensure your file has at least column headers like "Name", "Product Name", "Price", etc.');
            }

            $imported = 0;
            $updated = 0;
            $errors = [];
            $skipped = 0;

            // Process data rows
            for ($i = 1; $i < count($data); $i++) {
                $row = $data[$i];
                
                try {
                    $productData = $this->mapRowToProduct($row, $headerMap, $seller);
                    
                    if (empty($productData['name'])) {
                        continue; // Skip empty rows
                    }

                    // Check if product exists (by unique_id or name)
                    $product = null;
                    if (!empty($productData['unique_id'])) {
                        $product = Product::where('seller_id', $seller->id)
                            ->where('unique_id', $productData['unique_id'])
                            ->first();
                    }

                    if (!$product && !empty($productData['name'])) {
                        $product = Product::where('seller_id', $seller->id)
                            ->where('name', $productData['name'])
                            ->first();
                    }

                    if ($product) {
                        // Update existing product
                        $product->update($productData);
                        
                        // Handle image update if provided
                        if (!empty($productData['image'])) {
                            $this->processProductImage($product, $productData['image']);
                        }
                        
                        $updated++;
                    } else {
                        // Create new product
                        $productData['seller_id'] = $seller->id;
                        $productData['unique_id'] = $productData['unique_id'] ?? 'PRD-' . strtoupper(Str::random(8));
                        $newProduct = Product::create($productData);
                        
                        // Handle image if provided
                        if (!empty($productData['image'])) {
                            $this->processProductImage($newProduct, $productData['image']);
                        }
                        
                        $imported++;
                    }

                } catch (\Exception $e) {
                    // Hide SQL errors from users, show generic message
                    $errorMessage = $e->getMessage();
                    
                    // Check if it's a SQL error
                    if (stripos($errorMessage, 'SQLSTATE') !== false || 
                        stripos($errorMessage, 'SQL') !== false ||
                        stripos($errorMessage, 'Integrity constraint') !== false) {
                        $userMessage = "Data validation failed - please check required fields";
                    } else {
                        $userMessage = $errorMessage;
                    }
                    
                    $errors[] = "Row " . ($i + 1) . ": " . $userMessage;
                    Log::error("Import error on row " . ($i + 1), [
                        'error' => $errorMessage, // Log full error including SQL
                        'row_data' => $row
                    ]);
                    $skipped++;
                }
            }

            // Build success message
            $message = "✅ Import completed successfully! ";
            $message .= "Created: {$imported}, Updated: {$updated}";
            
            if ($skipped > 0) {
                $message .= ", Skipped: {$skipped}";
            }
            
            if (!empty($errors)) {
                Log::warning('Import completed with errors', [
                    'total_errors' => count($errors),
                    'errors' => array_slice($errors, 0, 10) // Log first 10 errors
                ]);
                
                // Show detailed error message
                $errorMessage = $message . " | ⚠️ " . count($errors) . " rows had errors. ";
                if (count($errors) <= 5) {
                    $errorMessage .= "Errors: " . implode('; ', $errors);
                } else {
                    $errorMessage .= "First 3 errors: " . implode('; ', array_slice($errors, 0, 3)) . "... (check logs for more)";
                }
                return back()->with('warning', $errorMessage);
            }

            return back()->with('success', $message);

        } catch (\Exception $e) {
            Log::error('Import Fatal Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Hide SQL errors from users
            $errorMessage = $e->getMessage();
            if (stripos($errorMessage, 'SQLSTATE') !== false || 
                stripos($errorMessage, 'SQL') !== false ||
                stripos($errorMessage, 'Integrity constraint') !== false) {
                $userMessage = 'Failed to import due to data validation errors. Please check your file format and ensure all required fields are filled correctly.';
            } else {
                $userMessage = 'Failed to import: ' . $errorMessage . '. Please check your file format and try again.';
            }
            
            return back()->with('error', '❌ ' . $userMessage);
        }
    }

    /**
     * Intelligent header detection and mapping
     * Supports various header formats from different sellers
     */
    private function detectHeaderMapping($headerRow)
    {
        $map = [];

        foreach ($headerRow as $index => $header) {
            $header = strtolower(trim($header));
            
            // Product ID variations
            if (preg_match('/product.*id|id|sku|item.*code|product.*code/', $header)) {
                $map['unique_id'] = $index;
            }
            
            // Product Name variations
            if (preg_match('/product.*name|name|title|item.*name|product/', $header) && !isset($map['name'])) {
                $map['name'] = $index;
            }
            
            // Description
            if (preg_match('/description|desc|details|about/', $header)) {
                $map['description'] = $index;
            }
            
            // Category
            if (preg_match('/category|cat|product.*category/', $header) && !preg_match('/sub/', $header)) {
                $map['category'] = $index;
            }
            
            // Subcategory
            if (preg_match('/subcategory|sub.*category|sub.*cat/', $header)) {
                $map['subcategory'] = $index;
            }
            
            // Price
            if (preg_match('/^price|selling.*price|sale.*price|mrp/', $header) && !preg_match('/original|old/', $header)) {
                $map['price'] = $index;
            }
            
            // Original Price
            if (preg_match('/original.*price|old.*price|list.*price|regular.*price/', $header)) {
                $map['original_price'] = $index;
            }
            
            // Discount
            if (preg_match('/discount|off/', $header)) {
                $map['discount'] = $index;
            }
            
            // Stock
            if (preg_match('/stock|quantity|qty|inventory|available/', $header)) {
                $map['stock'] = $index;
            }
            
            // SKU
            if (preg_match('/^sku|item.*code/', $header)) {
                $map['sku'] = $index;
            }
            
            // Barcode
            if (preg_match('/barcode|ean|upc|isbn/', $header)) {
                $map['barcode'] = $index;
            }
            
            // Weight
            if (preg_match('/weight|wt/', $header)) {
                $map['weight'] = $index;
            }
            
            // Dimensions
            if (preg_match('/dimension|size.*cm|measurements/', $header)) {
                $map['dimensions'] = $index;
            }
            
            // Brand
            if (preg_match('/brand|manufacturer|make/', $header)) {
                $map['brand'] = $index;
            }
            
            // Model
            if (preg_match('/model|model.*no/', $header)) {
                $map['model'] = $index;
            }
            
            // Color
            if (preg_match('/color|colour/', $header)) {
                $map['color'] = $index;
            }
            
            // Size
            if (preg_match('/^size|product.*size/', $header) && !preg_match('/dimension/', $header)) {
                $map['size'] = $index;
            }
            
            // Material
            if (preg_match('/material|fabric|composition/', $header)) {
                $map['material'] = $index;
            }
            
            // Status
            if (preg_match('/status|active|enabled/', $header)) {
                $map['status'] = $index;
            }
            
            // Featured
            if (preg_match('/featured|highlight|special/', $header)) {
                $map['featured'] = $index;
            }
            
            // Tags
            if (preg_match('/tags|keywords/', $header)) {
                $map['tags'] = $index;
            }
            
            // Meta Title
            if (preg_match('/meta.*title|seo.*title/', $header)) {
                $map['meta_title'] = $index;
            }
            
            // Meta Description
            if (preg_match('/meta.*desc|seo.*desc/', $header)) {
                $map['meta_description'] = $index;
            }
            
            // Image URL
            if (preg_match('/image|photo|picture|img/', $header)) {
                $map['image'] = $index;
            }
        }

        return $map;
    }

    /**
     * Map row data to product attributes based on detected headers
     */
    /**
     * Map row data to product attributes
     * FLEXIBLE: Only uses fields that are present in the Excel file
     * Skips empty/null values - leaves them as is in database
     */
    private function mapRowToProduct($row, $headerMap, $seller)
    {
        $productData = [];

        // Product ID - OPTIONAL (for updating existing products)
        if (isset($headerMap['unique_id']) && !empty($row[$headerMap['unique_id']])) {
            $productData['unique_id'] = trim($row[$headerMap['unique_id']]);
        }

        // Name - OPTIONAL but recommended
        if (isset($headerMap['name']) && !empty($row[$headerMap['name']])) {
            $productData['name'] = trim($row[$headerMap['name']]);
        }

        // Description - OPTIONAL
        if (isset($headerMap['description']) && !empty($row[$headerMap['description']])) {
            $productData['description'] = trim($row[$headerMap['description']]);
        }

        // Category - OPTIONAL (find or create if provided)
        if (isset($headerMap['category']) && !empty($row[$headerMap['category']])) {
            $categoryName = trim($row[$headerMap['category']]);
            if ($categoryName) {
                try {
                    $category = Category::firstOrCreate(
                        ['name' => $categoryName],
                        ['slug' => Str::slug($categoryName)]
                    );
                    $productData['category_id'] = $category->id;
                } catch (\Exception $e) {
                    Log::warning("Could not create category '{$categoryName}': " . $e->getMessage());
                }
            }
        }
        
        // If no category provided, use or create "Uncategorized" default category
        if (!isset($productData['category_id'])) {
            try {
                $defaultCategory = Category::firstOrCreate(
                    ['name' => 'Uncategorized'],
                    ['slug' => 'uncategorized']
                );
                $productData['category_id'] = $defaultCategory->id;
                Log::info('Using default Uncategorized category for product without category');
            } catch (\Exception $e) {
                // If default category creation fails, this will cause the import to fail
                // which is appropriate since category_id is required
                throw new \Exception('Product requires a category but default category could not be created: ' . $e->getMessage());
            }
        }

        // Subcategory - OPTIONAL (find or create if provided)
        if (isset($headerMap['subcategory']) && !empty($row[$headerMap['subcategory']]) && !empty($productData['category_id'])) {
            $subcategoryName = trim($row[$headerMap['subcategory']]);
            if ($subcategoryName) {
                try {
                    $subcategory = Subcategory::firstOrCreate(
                        ['name' => $subcategoryName, 'category_id' => $productData['category_id']],
                        ['slug' => Str::slug($subcategoryName)]
                    );
                    $productData['subcategory_id'] = $subcategory->id;
                } catch (\Exception $e) {
                    Log::warning("Could not create subcategory '{$subcategoryName}': " . $e->getMessage());
                }
            }
        }

        // Numeric fields - OPTIONAL (only add if present and valid)
        if (isset($headerMap['price']) && isset($row[$headerMap['price']]) && $row[$headerMap['price']] !== '') {
            $price = $this->parseNumeric($row[$headerMap['price']]);
            if ($price !== null) {
                $productData['price'] = $price;
            }
        }

        if (isset($headerMap['original_price']) && isset($row[$headerMap['original_price']]) && $row[$headerMap['original_price']] !== '') {
            $originalPrice = $this->parseNumeric($row[$headerMap['original_price']]);
            if ($originalPrice !== null) {
                $productData['original_price'] = $originalPrice;
            }
        }

        if (isset($headerMap['discount']) && isset($row[$headerMap['discount']]) && $row[$headerMap['discount']] !== '') {
            $discount = $this->parseNumeric($row[$headerMap['discount']]);
            if ($discount !== null) {
                $productData['discount'] = $discount;
            }
        }

        if (isset($headerMap['stock']) && isset($row[$headerMap['stock']]) && $row[$headerMap['stock']] !== '') {
            $productData['stock'] = (int) $row[$headerMap['stock']];
        }

        if (isset($headerMap['delivery_charge']) && isset($row[$headerMap['delivery_charge']]) && $row[$headerMap['delivery_charge']] !== '') {
            $deliveryCharge = $this->parseNumeric($row[$headerMap['delivery_charge']]);
            if ($deliveryCharge !== null) {
                $productData['delivery_charge'] = $deliveryCharge;
            }
        }

        // String fields - OPTIONAL (only add if present and not empty)
        $stringFields = [
            'sku', 'barcode', 'weight', 'dimensions', 'brand', 
            'model', 'color', 'size', 'material', 'tags', 
            'meta_title', 'meta_description'
        ];
        
        foreach ($stringFields as $field) {
            if (isset($headerMap[$field]) && isset($row[$headerMap[$field]]) && $row[$headerMap[$field]] !== '') {
                $value = trim($row[$headerMap[$field]]);
                if ($value !== '') {
                    $productData[$field] = $value;
                }
            }
        }

        // Status - OPTIONAL (only if provided)
        if (isset($headerMap['status']) && isset($row[$headerMap['status']]) && $row[$headerMap['status']] !== '') {
            $status = strtolower(trim($row[$headerMap['status']]));
            if (in_array($status, ['active', 'inactive', 'draft'])) {
                $productData['status'] = $status;
            }
        }

        // Featured (boolean) - OPTIONAL (only if provided)
        if (isset($headerMap['featured']) && isset($row[$headerMap['featured']]) && $row[$headerMap['featured']] !== '') {
            $featured = strtolower(trim($row[$headerMap['featured']]));
            $productData['featured'] = in_array($featured, ['yes', 'true', '1', 'featured', 'y']) ? 1 : 0;
        }

        // Gift Option - OPTIONAL (only if provided)
        if (isset($headerMap['gift_option']) && isset($row[$headerMap['gift_option']]) && $row[$headerMap['gift_option']] !== '') {
            $giftOption = strtolower(trim($row[$headerMap['gift_option']]));
            $productData['gift_option'] = in_array($giftOption, ['yes', 'true', '1', 'available', 'y']) ? 1 : 0;
        }

        // Image URL - OPTIONAL (if provided, accept any value)
        if (isset($headerMap['image']) && isset($row[$headerMap['image']]) && $row[$headerMap['image']] !== '') {
            $imageUrl = trim($row[$headerMap['image']]);
            if ($imageUrl) {
                $productData['image'] = $imageUrl;
            }
        }

        return $productData;
    }

    /**
     * Parse numeric value from string (handles currency symbols, commas, etc.)
     */
    private function parseNumeric($value)
    {
        if (empty($value)) {
            return null;
        }

        // Remove currency symbols and commas
        $cleaned = preg_replace('/[^\d.-]/', '', $value);
        
        return is_numeric($cleaned) ? (float) $cleaned : null;
    }

    /**
     * Download sample template
     */
    public function downloadTemplate()
    {
        try {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Headers
            $headers = [
                'Product ID', 'Product Name', 'Description', 'Category', 'Subcategory',
                'Price', 'Original Price', 'Discount %', 'Stock', 'SKU', 'Barcode',
                'Weight (kg)', 'Dimensions (LxWxH cm)', 'Brand', 'Model', 'Color',
                'Size', 'Material', 'Status', 'Featured', 'Tags', 'Meta Title',
                'Meta Description', 'Image URL'
            ];

            $sheet->fromArray($headers, NULL, 'A1');
            $sheet->getStyle('A1:X1')->getFont()->setBold(true);
            $sheet->getStyle('A1:X1')->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('FF4CAF50');
            $sheet->getStyle('A1:X1')->getFont()->getColor()->setARGB('FFFFFFFF');

            // Sample data
            $sampleData = [
                'PRD001', 'Sample Product 1', 'High quality product description', 'Electronics', 
                'Mobile Phones', '999.99', '1299.99', '23', '100', 'SKU001', '1234567890123',
                '0.5', '15x8x1', 'Samsung', 'Galaxy S21', 'Black', 'Medium', 'Plastic',
                'active', 'Yes', 'electronics, mobile, smartphone', 'Best Mobile Phone',
                'Buy the best smartphone online', 'https://example.com/image.jpg'
            ];

            $sheet->fromArray($sampleData, NULL, 'A2');

            // Auto-size columns
            foreach (range('A', 'X') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            $filename = 'product_import_template.xlsx';
            $tempFile = storage_path('app/temp/' . $filename);

            if (!file_exists(storage_path('app/temp'))) {
                mkdir(storage_path('app/temp'), 0755, true);
            }

            $writer = new Xlsx($spreadsheet);
            $writer->save($tempFile);

            return response()->download($tempFile, $filename)->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            Log::error('Template Download Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to download template.');
        }
    }

    /**
     * Process product image from import
     * Supports: URLs, file paths, base64 data
     */
    private function processProductImage($product, $imageData)
    {
        try {
            if (empty($imageData)) {
                return;
            }

            // Handle multiple images (comma-separated)
            $images = array_map('trim', explode(',', $imageData));
            
            foreach ($images as $index => $imageUrl) {
                if (empty($imageUrl)) {
                    continue;
                }

                // Case 1: Direct URL (http:// or https://)
                if (filter_var($imageUrl, FILTER_VALIDATE_URL)) {
                    // Download and upload to R2
                    $this->downloadAndUploadImage($product, $imageUrl, $index === 0);
                }
                // Case 2: Local file path (for bulk uploads)
                elseif (file_exists(public_path($imageUrl))) {
                    $this->uploadLocalImage($product, public_path($imageUrl), $index === 0);
                }
                // Case 3: Storage path
                elseif (Storage::disk('r2')->exists($imageUrl)) {
                    // Create product image record for existing R2 file
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $imageUrl,
                        'is_primary' => $index === 0,
                        'display_order' => $index
                    ]);
                }
                // Case 4: Just update product's legacy image field
                else {
                    $product->update(['image' => $imageUrl]);
                }
            }

            Log::info('Product images processed', [
                'product_id' => $product->id,
                'images_count' => count($images)
            ]);

        } catch (\Exception $e) {
            Log::error('Image processing error: ' . $e->getMessage(), [
                'product_id' => $product->id,
                'image_data' => $imageData
            ]);
        }
    }

    /**
     * Download image from URL and upload to R2
     */
    private function downloadAndUploadImage($product, $url, $isPrimary = false)
    {
        try {
            // Download image
            $imageContent = @file_get_contents($url);
            
            if ($imageContent === false) {
                Log::warning('Failed to download image', ['url' => $url]);
                return;
            }

            // Get file extension from URL or content type
            $extension = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION);
            if (empty($extension)) {
                $finfo = new \finfo(FILEINFO_MIME_TYPE);
                $mimeType = $finfo->buffer($imageContent);
                $extension = match($mimeType) {
                    'image/jpeg' => 'jpg',
                    'image/png' => 'png',
                    'image/gif' => 'gif',
                    'image/webp' => 'webp',
                    default => 'jpg'
                };
            }

            // Generate unique filename
            $filename = 'products/' . $product->id . '/' . Str::random(20) . '.' . $extension;
            
            // Upload to R2
            Storage::disk('r2')->put($filename, $imageContent);

            // Create product image record
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $filename,
                'is_primary' => $isPrimary,
                'display_order' => ProductImage::where('product_id', $product->id)->count()
            ]);

            // Update product's legacy image field if primary
            if ($isPrimary) {
                $product->update(['image' => $filename]);
            }

            Log::info('Image downloaded and uploaded', [
                'product_id' => $product->id,
                'url' => $url,
                'r2_path' => $filename
            ]);

        } catch (\Exception $e) {
            Log::error('Download and upload error: ' . $e->getMessage(), [
                'product_id' => $product->id,
                'url' => $url
            ]);
        }
    }

    /**
     * Upload local file to R2
     */
    private function uploadLocalImage($product, $localPath, $isPrimary = false)
    {
        try {
            if (!file_exists($localPath)) {
                return;
            }

            $extension = pathinfo($localPath, PATHINFO_EXTENSION);
            $filename = 'products/' . $product->id . '/' . Str::random(20) . '.' . $extension;
            
            // Upload to R2
            Storage::disk('r2')->put($filename, file_get_contents($localPath));

            // Create product image record
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $filename,
                'is_primary' => $isPrimary,
                'display_order' => ProductImage::where('product_id', $product->id)->count()
            ]);

            // Update product's legacy image field if primary
            if ($isPrimary) {
                $product->update(['image' => $filename]);
            }

        } catch (\Exception $e) {
            Log::error('Local upload error: ' . $e->getMessage(), [
                'product_id' => $product->id,
                'local_path' => $localPath
            ]);
        }
    }
}


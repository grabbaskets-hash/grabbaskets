<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
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
        $seller = Auth::user();
        $productsCount = Product::where('seller_id', $seller->id)->count();
        
        return view('seller.import-export', compact('productsCount'));
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
     * Import products from file (Excel/CSV)
     * Intelligently detects headers and maps to correct fields
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240'
        ]);

        try {
            $seller = Auth::user();
            $file = $request->file('file');
            
            // Load spreadsheet
            $spreadsheet = IOFactory::load($file->getPathname());
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray();

            if (empty($data)) {
                return back()->with('error', 'File is empty or invalid.');
            }

            // Detect and map headers
            $headerRow = $data[0];
            $headerMap = $this->detectHeaderMapping($headerRow);

            Log::info('Header mapping detected', ['map' => $headerMap]);

            $imported = 0;
            $updated = 0;
            $errors = [];

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
                        $updated++;
                    } else {
                        // Create new product
                        $productData['seller_id'] = $seller->id;
                        $productData['unique_id'] = $productData['unique_id'] ?? 'PRD-' . strtoupper(Str::random(8));
                        Product::create($productData);
                        $imported++;
                    }

                } catch (\Exception $e) {
                    $errors[] = "Row " . ($i + 1) . ": " . $e->getMessage();
                    Log::error("Import error on row " . ($i + 1), ['error' => $e->getMessage()]);
                }
            }

            $message = "Import completed! ";
            $message .= "New: {$imported}, Updated: {$updated}";
            
            if (!empty($errors)) {
                $message .= ". Errors: " . count($errors);
                Log::warning('Import errors', ['errors' => $errors]);
            }

            return back()->with('success', $message);

        } catch (\Exception $e) {
            Log::error('Import Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to import products: ' . $e->getMessage());
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
    private function mapRowToProduct($row, $headerMap, $seller)
    {
        $productData = [];

        // Map each field
        if (isset($headerMap['unique_id'])) {
            $productData['unique_id'] = $row[$headerMap['unique_id']] ?? null;
        }

        if (isset($headerMap['name'])) {
            $productData['name'] = $row[$headerMap['name']] ?? null;
        }

        if (isset($headerMap['description'])) {
            $productData['description'] = $row[$headerMap['description']] ?? null;
        }

        // Category - find or create
        if (isset($headerMap['category'])) {
            $categoryName = $row[$headerMap['category']] ?? null;
            if ($categoryName) {
                $category = Category::firstOrCreate(
                    ['name' => $categoryName],
                    ['slug' => Str::slug($categoryName)]
                );
                $productData['category_id'] = $category->id;
            }
        }

        // Subcategory - find or create
        if (isset($headerMap['subcategory']) && !empty($productData['category_id'])) {
            $subcategoryName = $row[$headerMap['subcategory']] ?? null;
            if ($subcategoryName) {
                $subcategory = Subcategory::firstOrCreate(
                    ['name' => $subcategoryName, 'category_id' => $productData['category_id']],
                    ['slug' => Str::slug($subcategoryName)]
                );
                $productData['subcategory_id'] = $subcategory->id;
            }
        }

        // Numeric fields
        if (isset($headerMap['price'])) {
            $productData['price'] = $this->parseNumeric($row[$headerMap['price']]);
        }

        if (isset($headerMap['original_price'])) {
            $productData['original_price'] = $this->parseNumeric($row[$headerMap['original_price']]);
        }

        if (isset($headerMap['discount'])) {
            $productData['discount'] = $this->parseNumeric($row[$headerMap['discount']]);
        }

        if (isset($headerMap['stock'])) {
            $productData['stock'] = (int) ($row[$headerMap['stock']] ?? 0);
        }

        // String fields
        $stringFields = ['sku', 'barcode', 'weight', 'dimensions', 'brand', 'model', 'color', 'size', 'material', 'tags', 'meta_title', 'meta_description'];
        
        foreach ($stringFields as $field) {
            if (isset($headerMap[$field])) {
                $productData[$field] = $row[$headerMap[$field]] ?? null;
            }
        }

        // Status
        if (isset($headerMap['status'])) {
            $status = strtolower($row[$headerMap['status']] ?? 'active');
            $productData['status'] = in_array($status, ['active', 'inactive', 'draft']) ? $status : 'active';
        }

        // Featured (boolean)
        if (isset($headerMap['featured'])) {
            $featured = strtolower($row[$headerMap['featured']] ?? 'no');
            $productData['featured'] = in_array($featured, ['yes', 'true', '1', 'featured']);
        }

        // Image URL (if provided)
        if (isset($headerMap['image'])) {
            $imageUrl = $row[$headerMap['image']] ?? null;
            if ($imageUrl && filter_var($imageUrl, FILTER_VALIDATE_URL)) {
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
}

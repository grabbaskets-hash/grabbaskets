<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
// ...existing code...

use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class ProductsImport implements ToCollection, WithHeadingRow, WithValidation, SkipsEmptyRows
{
    protected $errors = [];
    protected $successCount = 0;
    protected $zipFile;
    protected $sellerId;

    public function __construct($zipFile = null, $sellerId = null)
    {
        $this->zipFile = $zipFile;
        $this->sellerId = $sellerId ?: (Auth::check() ? Auth::id() : null);
    }

    protected function normalizeColumnName($columnName)
    {
        // Convert to lowercase and replace special characters
        $normalized = strtolower(trim($columnName));
        $normalized = preg_replace('/[^a-z0-9]/', '_', $normalized);
        $normalized = preg_replace('/_+/', '_', $normalized);
        $normalized = trim($normalized, '_');

        // Map common variations
        $mapping = [
            'category_id' => 'category_id',
            'categoryid' => 'category_id',
            'cat_id' => 'category_id',
            'category_name' => 'category_name',
            'categoryname' => 'category_name',
            'cat_name' => 'category_name',
            'subcategory_id' => 'subcategory_id',
            'subcategoryid' => 'subcategory_id',
            'sub_id' => 'subcategory_id',
            'subcategory_name' => 'subcategory_name',
            'subcategoryname' => 'subcategory_name',
            'sub_name' => 'subcategory_name',
            'unique_id' => 'unique_id',
            'uniqueid' => 'unique_id',
            'product_id' => 'unique_id',
            'productid' => 'unique_id',
            'discription' => 'description', // Handle common typo
            'desc' => 'description',
            'delivery_charge' => 'delivery_charge',
            'deliverycharge' => 'delivery_charge',
            'delivery_cost' => 'delivery_charge',
            'deliverycost' => 'delivery_charge',
            'gift_option' => 'gift_option',
            'giftoption' => 'gift_option',
            'gift' => 'gift_option',
        ];

        // Accept any column containing 'name' (but not 'category' or 'subcategory') as the product name
        if (!isset($mapping[$normalized]) && strpos($normalized, 'name') !== false && strpos($normalized, 'category') === false && strpos($normalized, 'subcategory') === false) {
            return 'name';
        }

        return $mapping[$normalized] ?? $normalized;
    }

    public function collection(Collection $rows)
    {
        $firstRow = $rows->first();
        $hasHeaders = true;
        // If the first row is all numeric keys, treat as no headers
        if ($firstRow && array_keys($firstRow->toArray()) === range(0, count($firstRow)-1)) {
            $hasHeaders = false;
        }

        // Define the expected order if no headers
        $expected = [
            'name', 'unique_id', 'category_id', 'category_name', 'subcategory_id', 'subcategory_name', 'image', 'description', 'price', 'discount', 'delivery_charge', 'gift_option', 'stock'
        ];

        foreach ($rows as $rowIndex => $row) {
            $rowArr = $row->toArray();
            if ($hasHeaders) {
                // Normalize the row data
                $normalizedRow = [];
                foreach ($rowArr as $key => $value) {
                    $normalizedKey = $this->normalizeColumnName($key);
                    $normalizedRow[$normalizedKey] = $value;
                }
                $row = $normalizedRow;
            } else {
                // Map by position
                $row = [];
                foreach ($expected as $i => $col) {
                    if (isset($rowArr[$i])) {
                        $row[$col] = $rowArr[$i];
                    }
                }
            }

            try {
                // Skip empty rows
                if (empty($row['name']) || empty($row['price'])) {
                    continue;
                }

                // Find or create category
                $category = $this->findOrCreateCategory($row);
                if (!$category) {
                    $this->errors[] = "Row " . ($rowIndex + 2) . ": Category not found or could not be created";
                    continue;
                }

                // Find or create subcategory
                $subcategory = $this->findOrCreateSubcategory($row, $category);

                // Handle image upload from zip
                $imagePath = $this->handleImageUpload($row);

                // Normalize discount value - handle "no", "none", empty as 0
                $discount = 0;
                if (!empty($row['discount'])) {
                    $discountValue = trim(strtolower($row['discount']));
                    if (in_array($discountValue, ['no', 'none', 'n/a', 'na', '0', 'null'])) {
                        $discount = 0;
                    } else {
                        $discount = (float) $row['discount'];
                    }
                }

                // Create product
                $product = Product::create([
                    'name' => $row['name'],
                    'unique_id' => $row['unique_id'] ?? 'PROD-' . Str::random(8),
                    'category_id' => $category->id,
                    'subcategory_id' => $subcategory ? $subcategory->id : null,
                    'seller_id' => $this->sellerId,
                    'image' => $imagePath,
                    'description' => $row['description'] ?? '',
                    'price' => (float) $row['price'],
                    'discount' => $discount,
                    'delivery_charge' => (float) ($row['delivery_charge'] ?? 0),
                    'gift_option' => filter_var($row['gift_option'] ?? false, FILTER_VALIDATE_BOOLEAN),
                    'stock' => (int) ($row['stock'] ?? 1),
                ]);

                $this->successCount++;

            } catch (\Exception $e) {
                $this->errors[] = "Row " . ($rowIndex + 2) . ": " . $e->getMessage();
            }
        }
    }

    protected function findOrCreateCategory($row)
    {
        // Try to find by ID first (only if it's numeric)
        if (!empty($row['category_id']) && is_numeric($row['category_id'])) {
            $category = Category::find((int)$row['category_id']);
            if ($category) return $category;
        }

        // Try to find by unique_id if provided and not numeric
        if (!empty($row['category_id']) && !is_numeric($row['category_id'])) {
            $category = Category::where('unique_id', trim($row['category_id']))->first();
            if ($category) return $category;
        }

        // Try to find by name
        if (!empty($row['category_name'])) {
            $category = Category::where('name', 'LIKE', '%' . trim($row['category_name']) . '%')->first();
            if ($category) return $category;

            // Create new category if it doesn't exist
            return Category::create([
                'name' => trim($row['category_name']),
                'unique_id' => 'CAT-' . Str::random(6),
                'image' => null,
                'gender' => 'all',
                'emoji' => '🛍️'
            ]);
        }

        return null;
    }

    protected function findOrCreateSubcategory($row, $category)
    {
        // Try to find by ID first (only if it's numeric)
        if (!empty($row['subcategory_id']) && is_numeric($row['subcategory_id'])) {
            $subcategory = Subcategory::find((int)$row['subcategory_id']);
            if ($subcategory) return $subcategory;
        }

        // Try to find by unique_id if provided and not numeric
        if (!empty($row['subcategory_id']) && !is_numeric($row['subcategory_id'])) {
            $subcategory = Subcategory::where('unique_id', trim($row['subcategory_id']))->first();
            if ($subcategory) return $subcategory;
        }

        // Try to find by name
        if (!empty($row['subcategory_name'])) {
            $subcategory = Subcategory::where('name', 'LIKE', '%' . trim($row['subcategory_name']) . '%')
                                     ->where('category_id', $category->id)
                                     ->first();
            if ($subcategory) return $subcategory;

            // Create new subcategory if it doesn't exist
            return Subcategory::create([
                'name' => trim($row['subcategory_name']),
                'unique_id' => 'SUB-' . Str::random(6),
                'category_id' => $category->id,
                'description' => 'Auto-created from bulk upload'
            ]);
        }

        return null;
    }

    protected function handleImageUpload($row)
    {
        // Build candidate names to match: image column and unique_id
        $candidates = [];
        if (!empty($row['image'])) {
            $imageName = trim($row['image']);
            $candidates[] = strtolower($imageName);
            $candidates[] = strtolower(pathinfo($imageName, PATHINFO_FILENAME));
        }
        if (!empty($row['unique_id'])) {
            $uid = trim($row['unique_id']);
            $candidates[] = strtolower($uid);
            $candidates[] = strtolower(pathinfo($uid, PATHINFO_FILENAME));
        }

        // Nothing to match
        if (empty($candidates)) {
            return null;
        }

        // If we have a zip file, extract the image
        if ($this->zipFile && Storage::exists($this->zipFile)) {
            try {
                $zip = new \ZipArchive();
                $zipPath = Storage::path($this->zipFile);

                if ($zip->open($zipPath) === TRUE) {
                    for ($i = 0; $i < $zip->numFiles; $i++) {
                        $filename = $zip->getNameIndex($i);
                        $basename = basename($filename);
                        $basenameLower = strtolower($basename);
                        $base = strtolower(pathinfo($basenameLower, PATHINFO_FILENAME));

                        // Match full filename or just base against candidates (case-insensitive)
                        if (in_array($basenameLower, $candidates, true) || in_array($base, $candidates, true)) {
                            $imageContent = $zip->getFromIndex($i);
                            if ($imageContent !== false) {
                                $extension = pathinfo($basename, PATHINFO_EXTENSION) ?: 'jpg';
                                $uniqueName = Str::random(40) . '.' . $extension;
                                $storagePath = 'products/' . $uniqueName;

                                // Try AWS (R2) first, then fallback to local
                                $saved = false;
                                try {
                                    $saved = Storage::disk('r2')->put($storagePath, $imageContent);
                                    if ($saved) {
                                        Log::info('Image stored in AWS (r2) from bulk import', [
                                            'matched' => $basename,
                                            'path' => $storagePath
                                        ]);
                                    }
                                } catch (\Throwable $e) {
                                    Log::warning('AWS (r2) upload failed in bulk import, trying local', [
                                        'error' => $e->getMessage()
                                    ]);
                                    $saved = false;
                                }

                                if (!$saved) {
                                    $saved = Storage::disk('public')->put($storagePath, $imageContent);
                                    if ($saved) {
                                        Log::info('Image stored in local storage from bulk import (fallback)', [
                                            'matched' => $basename,
                                            'path' => $storagePath
                                        ]);
                                    }
                                }

                                if ($saved) {
                                    $zip->close();
                                    return $storagePath;
                                }
                            }
                        }
                    }
                    $zip->close();
                    Log::warning('No image matched in ZIP for candidates', ['candidates' => $candidates]);
                }
            } catch (\Exception $e) {
                Log::error('Error extracting image from zip: ' . $e->getMessage());
            }
        }

        return null;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category_name' => 'required_without:category_id|string|max:255',
            'category_id' => 'nullable|string|max:255',
            'stock' => 'nullable|integer|min:0',
            'discount' => 'nullable|numeric|min:0|max:100',
            'delivery_charge' => 'nullable|numeric|min:0',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'name.required' => 'Product name is required',
            'price.required' => 'Price is required',
            'price.numeric' => 'Price must be a number',
            'category_name.required_without' => 'Category name is required when category ID is not provided',
        ];
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getSuccessCount()
    {
        return $this->successCount;
    }
}
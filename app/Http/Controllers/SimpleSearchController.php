<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\Category;

class SimpleSearchController extends Controller
{
    /**
     * Zepto/Blinkit style instant search with autocomplete
     */
    public function search(Request $request)
    {
        try {
            $searchQuery = trim($request->input('q', ''));
            $matchedStores = collect();
            
            // Basic product query with image filtering
            $query = Product::whereNotNull('image')
                ->where('image', '!=', '');

            // Apply search if provided
            if (!empty($searchQuery) && strlen($searchQuery) >= 2) {
                $query->where(function ($q) use ($searchQuery) {
                    $q->where('name', 'LIKE', "%{$searchQuery}%")
                      ->orWhere('description', 'LIKE', "%{$searchQuery}%");
                });
            }

            // Apply category filter (Zepto/Blinkit style)
            if ($request->filled('category_id')) {
                $query->where('category_id', $request->input('category_id'));
            }

            // Apply subcategory filter
            if ($request->filled('subcategory_id')) {
                $query->where('subcategory_id', $request->input('subcategory_id'));
            }

            // Apply basic filters
            if ($request->filled('price_min')) {
                $query->where('price', '>=', (float)$request->input('price_min'));
            }
            if ($request->filled('price_max')) {
                $query->where('price', '<=', (float)$request->input('price_max'));
            }
            if ($request->filled('discount_min')) {
                $query->where('discount', '>=', (float)$request->input('discount_min'));
            }

            // Apply sorting
            $sort = $request->input('sort', 'newest');
            switch ($sort) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'discount':
                    $query->orderBy('discount', 'desc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
            }

            // Get paginated results
            $products = $query->paginate(24)->appends($request->query());
            
            // Prepare response data
            $totalResults = $products->total();
            $filters = $request->only(['price_min', 'price_max', 'discount_min', 'sort', 'category_id', 'subcategory_id']);
            
            // Check if user is authenticated
            $isAuthenticated = Auth::check();

            // If this is a regular web request (not AJAX), return the view
            if (!$request->ajax() && !$request->expectsJson()) {
                return view('buyer.products', [
                    'products' => $products,
                    'filters' => $filters,
                    'matchedStores' => collect(), // Empty for now
                    'searchQuery' => $searchQuery,
                    'totalResults' => $totalResults
                ]);
            }
            
            // Return enhanced HTML response with images and cart functionality
            $html = "
            <!DOCTYPE html>
            <html>
            <head>
                <title>Search Results - GrabBaskets</title>
                <meta name='viewport' content='width=device-width, initial-scale=1'>
                <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
                <link href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css' rel='stylesheet'>
                <meta name='csrf-token' content='" . csrf_token() . "'>
                <style>
                    .product-card {
                        border: none;
                        border-radius: 15px;
                        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
                        transition: all 0.3s ease;
                        overflow: hidden;
                        position: relative;
                    }
                    .product-card:hover {
                        transform: translateY(-5px);
                        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
                    }
                    .product-image {
                        width: 100%;
                        height: 200px;
                        object-fit: cover;
                    }
                    .price-badge {
                        background: linear-gradient(135deg, #FF6B00, #FF9900);
                        color: white;
                        padding: 5px 10px;
                        border-radius: 15px;
                        font-weight: bold;
                    }
                    .cart-btn {
                        background: linear-gradient(135deg, #28a745, #20c997);
                        border: none;
                        border-radius: 25px;
                        padding: 8px 16px;
                        color: white;
                        font-weight: 600;
                        transition: all 0.3s ease;
                    }
                    .cart-btn:hover {
                        transform: scale(1.05);
                        box-shadow: 0 4px 15px rgba(40, 167, 69, 0.4);
                    }
                    .login-btn {
                        background: linear-gradient(135deg, #007bff, #0056b3);
                        border: none;
                        border-radius: 25px;
                        padding: 8px 16px;
                        color: white;
                        font-weight: 600;
                        text-decoration: none;
                        display: inline-block;
                        transition: all 0.3s ease;
                    }
                    .login-btn:hover {
                        transform: scale(1.05);
                        box-shadow: 0 4px 15px rgba(0, 123, 255, 0.4);
                        color: white;
                        text-decoration: none;
                    }
                    .search-header {
                        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                        color: white;
                        padding: 30px 0;
                        margin-bottom: 30px;
                        border-radius: 0 0 25px 25px;
                        position: relative;
                        z-index: 10;
                    }
                    
                    /* Mobile optimizations */
                    @media (max-width: 768px) {
                        .product-card {
                            border-radius: 12px;
                            margin-bottom: 15px;
                        }
                        .product-image {
                            height: 180px;
                        }
                        .search-header {
                            padding: 20px 0;
                            margin-bottom: 20px;
                        }
                        /* Ensure search results are not covered by banners */
                        body {
                            padding-top: 0 !important;
                        }
                    }
                </style>
            </head>
            <body style='background-color: #f8f9fa;'>
                <div class='search-header'>
                    <div class='container'>
                        <div class='row align-items-center'>
                            <div class='col-md-8'>
                                <h2><i class='bi bi-search'></i> Search Results</h2>
                                <p class='mb-0'><strong>Search Query:</strong> " . htmlspecialchars($searchQuery) . "</p>
                            </div>
                            <div class='col-md-4 text-end'>
                                <span class='badge bg-light text-dark' style='font-size: 1.1rem;'>$totalResults Products Found</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class='container'>
                    <div class='row'>";
            
            foreach ($products as $product) {
                // Properly handle image URLs
                $productImage = $product->image_url ?? $product->image ?? '/images/placeholder.png';
                $discountPercent = $product->discount ? round($product->discount, 0) : 0;
                
                $html .= "
                    <div class='col-lg-3 col-md-4 col-sm-6 mb-4'>
                        <div class='card product-card h-100'>
                            <a href='/product/{$product->id}' class='text-decoration-none text-dark'>
                            <div class='position-relative'";
                
                // Discount badge
                if ($discountPercent > 0) {
                    $html .= "<span class='badge bg-danger position-absolute top-0 start-0 m-2'>-{$discountPercent}%</span>";
                }
                
                $html .= "
                                <img src='" . htmlspecialchars($productImage) . "' 
                                     alt='" . htmlspecialchars($product->name) . "' 
                                     class='product-image'
                                     onerror=\"this.src='/images/placeholder.png'\">
                            </div>
                            <div class='card-body d-flex flex-column'>
                                <h6 class='card-title fw-bold mb-2' style='min-height: 50px;'>" . htmlspecialchars($product->name) . "</h6>
                                <p class='card-text text-muted small mb-3' style='min-height: 40px;'>" . 
                                    htmlspecialchars(Str::limit($product->description ?? 'Quality product from GrabBaskets', 60)) . "</p>
                                
                                <div class='mt-auto'>
                                    <div class='d-flex justify-content-between align-items-center mb-3'>
                                        <span class='price-badge'>₹" . number_format($product->price, 2) . "</span>";
                
                if ($product->stock_quantity !== null) {
                    $stockStatus = $product->stock_quantity > 0 ? 'In Stock' : 'Out of Stock';
                    $stockClass = $product->stock_quantity > 0 ? 'text-success' : 'text-danger';
                    $html .= "<small class='$stockClass'><i class='bi bi-box'></i> $stockStatus</small>";
                }
                
                $html .= "
                                    </div>";
                
                // Cart button based on authentication
                if ($isAuthenticated) {
                    if ($product->stock_quantity === null || $product->stock_quantity > 0) {
                        $html .= "
                                    <button class='btn cart-btn w-100' onclick='addToCart({$product->id})'>
                                        <i class='bi bi-cart-plus'></i> Add to Cart
                                    </button>";
                    } else {
                        $html .= "
                                    <button class='btn btn-secondary w-100' disabled>
                                        <i class='bi bi-x-circle'></i> Out of Stock
                                    </button>";
                    }
                } else {
                    $html .= "
                                    <a href='/login' class='login-btn w-100 text-center'>
                                        <i class='bi bi-box-arrow-in-right'></i> Login to Add to Cart
                                    </a>";
                }
                
                $html .= "
                                </div>
                            </a>
                            </div>
                        </div>
                    </div>";
            }
            
            $html .= "
                    </div>";
            
            // Pagination
            if ($products->hasPages()) {
                $html .= "
                    <div class='d-flex justify-content-center mt-4'>
                        <nav aria-label='Search results pagination'>
                            " . $products->links() . "
                        </nav>
                    </div>";
            }
            
            // Add JavaScript for cart functionality
            $html .= "
                </div>
                
                <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js'></script>
                <script>
                    // Set CSRF token for AJAX requests
                    const token = document.querySelector('meta[name=\"csrf-token\"]').getAttribute('content');
                    
                    async function addToCart(productId) {
                        try {
                            const response = await fetch('/cart/add', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': token,
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({
                                    product_id: productId,
                                    quantity: 1,
                                    delivery_type: 'standard'
                                })
                            });
                            
                            const data = await response.json();
                            
                            if (response.ok) {
                                // Show success message
                                showMessage('✅ Product added to cart successfully!', 'success');
                            } else {
                                showMessage('❌ ' + (data.message || 'Failed to add to cart'), 'error');
                            }
                        } catch (error) {
                            console.error('Cart error:', error);
                            showMessage('❌ Network error. Please try again.', 'error');
                        }
                    }
                    
                    function showMessage(message, type) {
                        // Remove existing alerts
                        const existingAlert = document.querySelector('.alert-custom');
                        if (existingAlert) {
                            existingAlert.remove();
                        }
                        
                        // Create new alert
                        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
                        const alertDiv = document.createElement('div');
                        alertDiv.className = 'alert ' + alertClass + ' alert-dismissible fade show alert-custom position-fixed';
                        alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 1050; min-width: 350px;';
                        alertDiv.innerHTML = message + '<button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\"></button>';
                        
                        document.body.appendChild(alertDiv);
                        
                        // Auto remove after 3 seconds
                        setTimeout(() => {
                            if (alertDiv) {
                                alertDiv.remove();
                            }
                        }, 3000);
                    }
                </script>
            </body>
            </html>";
            
            return response($html);
            
        } catch (\Exception $e) {
            // Log error
            Log::error('Simple Search Error', [
                'query' => $request->input('q'),
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            // Return empty result
            $emptyProducts = new \Illuminate\Pagination\LengthAwarePaginator(
                collect([]),
                0,
                24,
                1,
                ['path' => $request->url(), 'query' => $request->query()]
            );
            
            $html = "
            <!DOCTYPE html>
            <html>
            <head>
                <title>Search Error - GrabBaskets</title>
                <meta name='viewport' content='width=device-width, initial-scale=1'>
                <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
            </head>
            <body>
                <div class='container mt-4'>
                    <div class='alert alert-warning'>
                        <h4>⚠️ Search Temporarily Unavailable</h4>
                        <p>We're experiencing technical difficulties. Please try again later.</p>
                        <p><small>Error: " . htmlspecialchars($e->getMessage()) . "</small></p>
                    </div>
                </div>
            </body>
            </html>";
            
            return response($html, 503);
        }
    }
    
    /**
     * Instant search API for real-time suggestions (Zepto/Blinkit style)
     */
    public function instantSearch(Request $request)
    {
        try {
            $query = trim($request->input('q', ''));
            
            if (strlen($query) < 2) {
                return response()->json([
                    'suggestions' => [],
                    'products' => [],
                    'categories' => []
                ]);
            }
            
            // Cache key for suggestions
            $cacheKey = 'instant_search_' . md5($query);
            
            return Cache::remember($cacheKey, 300, function() use ($query) { // 5 minute cache
                
                // Get top matching products (limit to 6 for instant display)
                $products = Product::whereNotNull('image')
                    ->where('image', '!=', '')
                    ->where(function ($q) use ($query) {
                        $q->where('name', 'LIKE', "%{$query}%")
                          ->orWhere('description', 'LIKE', "%{$query}%");
                    })
                    ->orderBy('created_at', 'desc')
                    ->limit(6)
                    ->get(['id', 'name', 'price', 'discount', 'image', 'stock_quantity'])
                    ->map(function($product) {
                        return [
                            'id' => $product->id,
                            'name' => $product->name,
                            'price' => $product->price,
                            'discount' => $product->discount,
                            'image' => $product->image_url ?? $product->image ?? '/images/placeholder.png',
                            'in_stock' => $product->stock_quantity === null || $product->stock_quantity > 0,
                            'url' => "/product/{$product->id}"
                        ];
                    });
                
                // Get matching categories
                $categories = Category::where('name', 'LIKE', "%{$query}%")
                    ->limit(4)
                    ->get(['id', 'name', 'emoji'])
                    ->map(function($category) {
                        return [
                            'id' => $category->id,
                            'name' => $category->name,
                            'emoji' => $category->emoji,
                            'url' => "/products?category_id={$category->id}"
                        ];
                    });
                
                // Popular search suggestions
                $suggestions = [
                    $query . ' products',
                    $query . ' deals',
                    $query . ' offers'
                ];
                
                return [
                    'products' => $products,
                    'categories' => $categories,
                    'suggestions' => $suggestions,
                    'query' => $query
                ];
            });
            
        } catch (\Exception $e) {
            Log::error('Instant Search Error', [
                'query' => $request->input('q'),
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'products' => [],
                'categories' => [],
                'suggestions' => [],
                'error' => 'Search temporarily unavailable'
            ], 500);
        }
    }
    
    /**
     * Auto-complete suggestions API
     */
    public function suggestions(Request $request)
    {
        try {
            $query = trim($request->input('q', ''));
            
            if (strlen($query) < 2) {
                return response()->json(['suggestions' => []]);
            }
            
            $cacheKey = 'suggestions_' . md5($query);
            
            $suggestions = Cache::remember($cacheKey, 3600, function() use ($query) { // 1 hour cache
                
                // Get product name suggestions
                $productSuggestions = Product::where('name', 'LIKE', "%{$query}%")
                    ->limit(8)
                    ->pluck('name')
                    ->map(function($name) {
                        return ['text' => $name, 'type' => 'product'];
                    });
                
                // Get category suggestions
                $categorySuggestions = Category::where('name', 'LIKE', "%{$query}%")
                    ->limit(4)
                    ->get(['name', 'emoji'])
                    ->map(function($category) {
                        return [
                            'text' => $category->name,
                            'type' => 'category',
                            'emoji' => $category->emoji
                        ];
                    });
                
                return $productSuggestions->merge($categorySuggestions)->take(10);
            });
            
            return response()->json(['suggestions' => $suggestions]);
            
        } catch (\Exception $e) {
            Log::error('Suggestions Error', [
                'query' => $request->input('q'),
                'error' => $e->getMessage()
            ]);
            
            return response()->json(['suggestions' => []], 500);
        }
    }

    /**
     * Food delivery products - filtered by food categories
     */
    public function foodDelivery(Request $request)
    {
        try {
            // Get food-related categories
            $foodCategories = Category::where(function($query) {
                $query->where('name', 'LIKE', '%food%')
                      ->orWhere('name', 'LIKE', '%restaurant%')
                      ->orWhere('name', 'LIKE', '%meal%')
                      ->orWhere('name', 'LIKE', '%snack%')
                      ->orWhere('name', 'LIKE', '%beverage%')
                      ->orWhere('name', 'LIKE', '%drink%')
                      ->orWhere('name', 'LIKE', '%grocery%')
                      ->orWhere('name', 'LIKE', '%kitchen%');
            })->pluck('id');

            // Query food products
            $query = Product::whereNotNull('image')
                ->where('image', '!=', '');

            // Filter by food categories if found
            if ($foodCategories->isNotEmpty()) {
                $query->whereIn('category_id', $foodCategories);
            }

            // Search functionality
            $searchQuery = trim($request->input('q', ''));
            if (!empty($searchQuery) && strlen($searchQuery) >= 2) {
                $query->where(function ($q) use ($searchQuery) {
                    $q->where('name', 'LIKE', "%{$searchQuery}%")
                      ->orWhere('description', 'LIKE', "%{$searchQuery}%");
                });
            }

            // Apply filters
            if ($request->filled('price_min')) {
                $query->where('price', '>=', (float)$request->input('price_min'));
            }
            if ($request->filled('price_max')) {
                $query->where('price', '<=', (float)$request->input('price_max'));
            }

            // Sorting
            $sort = $request->input('sort', 'newest');
            switch ($sort) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'discount':
                    $query->orderBy('discount', 'desc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
            }

            // Pagination
            $perPage = $request->input('per_page', 20);
            $products = $query->paginate($perPage);

            // Get all categories for sidebar
            $categories = Category::with('subcategories')->get();

            return view('products.index', [
                'products' => $products,
                'categories' => $categories,
                'searchQuery' => $searchQuery,
                'currentSort' => $sort,
                'pageTitle' => 'Food Delivery - Fast & Fresh',
                'isFood' => true,
                'filters' => [
                    'price_min' => $request->input('price_min'),
                    'price_max' => $request->input('price_max'),
                ],
            ]);

        } catch (\Exception $e) {
            Log::error('Food Delivery Search Error', [
                'error' => $e->getMessage(),
                'query' => $request->input('q'),
            ]);

            return back()->with('error', 'Search temporarily unavailable. Please try again.');
        }
    }
}
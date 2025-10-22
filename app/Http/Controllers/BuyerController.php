<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Product;
use App\Models\Blog;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Http\Request;

class BuyerController extends Controller
{
public function index()
{
    $categories = Category::with('subcategories')->get();

    // Carousel products with higher discounts for banner
    $carouselProducts = Product::with('category')
        ->whereNotNull('image')
        ->where('image', '!=', '')
        ->where('image', 'NOT LIKE', '%unsplash%')
        ->where('image', 'NOT LIKE', '%placeholder%')
        ->where('image', 'NOT LIKE', '%via.placeholder%')
        ->where('discount', '>=', 20) // Only show products with 20% or higher discount in carousel
        ->orderBy('discount', 'desc') // Order by highest discount first
        ->take(10)
        ->get();
        
    // Get shuffled products from MASALA/COOKING, PERFUME/BEAUTY & DENTAL CARE - ONLY RELEVANT IMAGES
    $cookingCategory = Category::where('name', 'COOKING')->first();
    $beautyCategory = Category::where('name', 'BEAUTY & PERSONAL CARE')->first();
    $dentalCategory = Category::where('name', 'DENTAL CARE')->first();
    
    $mixedProducts = collect();
    
    // Get products from each category
    if ($cookingCategory) {
        $cookingProducts = Product::where('category_id', $cookingCategory->id)
            ->whereNotNull('image')
            ->where('image', '!=', '')
            ->where('image', 'NOT LIKE', '%unsplash%')
            ->where('image', 'NOT LIKE', '%placeholder%')
            ->where('image', 'NOT LIKE', '%via.placeholder%')
            ->inRandomOrder()
            ->take(8)
            ->get();
        $mixedProducts = $mixedProducts->merge($cookingProducts);
    }
    
    if ($beautyCategory) {
        $beautyProducts = Product::where('category_id', $beautyCategory->id)
            ->whereNotNull('image')
            ->where('image', '!=', '')
            ->where('image', 'NOT LIKE', '%unsplash%')
            ->where('image', 'NOT LIKE', '%placeholder%')
            ->where('image', 'NOT LIKE', '%via.placeholder%')
            ->inRandomOrder()
            ->take(2)
            ->get();
        $mixedProducts = $mixedProducts->merge($beautyProducts);
    }
    
    if ($dentalCategory) {
        $dentalProducts = Product::where('category_id', $dentalCategory->id)
            ->whereNotNull('image')
            ->where('image', '!=', '')
            ->where('image', 'NOT LIKE', '%unsplash%')
            ->where('image', 'NOT LIKE', '%placeholder%')
            ->where('image', 'NOT LIKE', '%via.placeholder%')
            ->inRandomOrder()
            ->take(2)
            ->get();
        $mixedProducts = $mixedProducts->merge($dentalProducts);
    }
    
    // Shuffle the mixed products and paginate
    $shuffledProducts = $mixedProducts->shuffle();
    $products = new \Illuminate\Pagination\LengthAwarePaginator(
        $shuffledProducts->forPage(1, 12),
        $shuffledProducts->count(),
        12,
        1,
        ['path' => request()->url()]
    );
    // 🔥 Trending items (fetch 5 random products)
    $trending = Product::whereNotNull('image')
        ->where('image', '!=', '')
        ->where('image', 'NOT LIKE', '%unsplash%')
        ->where('image', 'NOT LIKE', '%placeholder%')
        ->where('image', 'NOT LIKE', '%via.placeholder%')
        ->inRandomOrder()
        ->take(5)
        ->get();
 $lookbookProduct = Product::whereNotNull('image')
        ->where('image', '!=', '')
        ->where('image', 'NOT LIKE', '%unsplash%')
        ->where('image', 'NOT LIKE', '%placeholder%')
        ->where('image', 'NOT LIKE', '%via.placeholder%')
        ->inRandomOrder()
        ->first();
$blogProducts = Product::whereNotNull('image')
        ->where('image', '!=', '')
        ->where('image', 'NOT LIKE', '%unsplash%')
        ->where('image', 'NOT LIKE', '%placeholder%')
        ->where('image', 'NOT LIKE', '%via.placeholder%')
        ->inRandomOrder()
        ->take(3)
        ->get();
    // ✅ Deals of the day - products with discounts
    $deals = Product::whereNotNull('image')
        ->where('image', '!=', '')
        ->where('image', 'NOT LIKE', '%unsplash%')
        ->where('image', 'NOT LIKE', '%placeholder%')
        ->where('image', 'NOT LIKE', '%via.placeholder%')
        ->where('discount', '>', 0)
        ->inRandomOrder()
        ->take(12)
        ->get();
    
    // 🔥 Flash Sale - products with high discounts (>20%)
    $flashSale = Product::whereNotNull('image')
        ->where('image', '!=', '')
        ->where('image', 'NOT LIKE', '%unsplash%')
        ->where('image', 'NOT LIKE', '%placeholder%')
        ->where('image', 'NOT LIKE', '%via.placeholder%')
        ->where('discount', '>', 20)
        ->inRandomOrder()
        ->take(12)
        ->get();
    
    // 🚚 Free Delivery - products with no delivery charge
    $freeDelivery = Product::whereNotNull('image')
        ->where('image', '!=', '')
        ->where('image', 'NOT LIKE', '%unsplash%')
        ->where('image', 'NOT LIKE', '%placeholder%')
        ->where('image', 'NOT LIKE', '%via.placeholder%')
        ->where('delivery_charge', 0)
        ->inRandomOrder()
        ->take(12)
        ->get();

    return view('buyer.index', compact('categories', 'products', 'carouselProducts','trending','lookbookProduct','blogProducts','deals','flashSale','freeDelivery'));
}



public function search(Request $request)
{
    try {
        $searchQuery = $request->input('q', '');
        $matchedStores = collect();
        
        $query = Product::with(['category', 'subcategory'])
            ->whereNotNull('image')
            ->where('image', '!=', '')
            ->where('image', 'NOT LIKE', '%unsplash%')
            ->where('image', 'NOT LIKE', '%placeholder%')
            ->where('image', 'NOT LIKE', '%via.placeholder%');

        if ($request->filled('q')) {
            $search = trim($searchQuery);
            
            // Search for matching stores
            $matchedStores = Seller::where('name', 'like', "%{$search}%")
                ->orWhere('store_name', 'like', "%{$search}%")
                ->with(['user' => function($query) {
                    $query->select('id', 'email');
                }])
                ->get()
                ->map(function($seller) {
                    // Get user ID for this seller
                    $user = User::where('email', $seller->email)->first();
                    if ($user) {
                        $seller->user_id = $user->id;
                        // Count products for this seller
                        $seller->product_count = Product::where('seller_id', $user->id)->count();
                    }
                    return $seller;
                });
            
            $query->where(function ($q) use ($search) {
                // Search in product fields that actually exist in database
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('unique_id', 'like', "%{$search}%")
                  // Search in category
                  ->orWhereHas('category', function($query) use ($search) {
                      $query->where('name', 'like', "%{$search}%");
                  })
                  // Search in subcategory
                  ->orWhereHas('subcategory', function($query) use ($search) {
                      $query->where('name', 'like', "%{$search}%");
                  });
                  
                // Search in sellers table (match seller emails to user emails, then to product seller_id)
                $sellerEmails = Seller::where('name', 'like', "%{$search}%")
                    ->orWhere('store_name', 'like', "%{$search}%")
                    ->pluck('email');
                    
                if ($sellerEmails->isNotEmpty()) {
                    // Get user IDs that match these seller emails
                    $userIds = User::whereIn('email', $sellerEmails)->pluck('id');
                    if ($userIds->isNotEmpty()) {
                        $q->orWhereIn('seller_id', $userIds);
                    }
                }
            });
        }

        // Add sorting
        $sort = $request->input('sort', 'relevance');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'popular':
                $query->orderBy('view_count', 'desc');
                break;
            case 'discount':
                $query->orderBy('discount', 'desc');
                break;
            default: // relevance
                if ($request->filled('q')) {
                    // When searching, prioritize exact matches in existing columns
                    $query->orderByRaw("CASE 
                        WHEN name LIKE ? THEN 1
                        WHEN description LIKE ? THEN 2
                        ELSE 3
                    END", ["%{$search}%", "%{$search}%"])
                    ->orderBy('created_at', 'desc');
                } else {
                    $query->latest();
                }
        }

        $products = $query->paginate(24)->appends($request->query());
        
        // Get search statistics
        $totalResults = $products->total();
        
        // Prepare filters array for the view
        $filters = [
            'price_min' => $request->input('price_min'),
            'price_max' => $request->input('price_max'),
            'discount_min' => $request->input('discount_min'),
            'free_delivery' => $request->boolean('free_delivery'),
            'sort' => $request->input('sort', 'relevance')
        ];
        
        // Log search query for analytics
        if ($request->filled('q')) {
            \Illuminate\Support\Facades\Log::info('Search Query', [
                'query' => $searchQuery,
                'results' => $totalResults,
                'user_id' => auth()->id(),
                'ip' => $request->ip()
            ]);
        }

        return view('buyer.products', compact('products', 'searchQuery', 'totalResults', 'matchedStores', 'filters'));
        
    } catch (\Exception $e) {
        \Illuminate\Support\Facades\Log::error('Search Error', [
            'query' => $request->input('q'),
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return view('buyer.products', [
            'products' => collect([]),
            'searchQuery' => $request->input('q', ''),
            'totalResults' => 0,
            'matchedStores' => collect([]),
            'filters' => [],
            'error' => 'An error occurred while searching. Please try again.'
        ]);
    }
}


    public function storeCatalog(Request $request, $seller_id)
    {
        // Get seller information
        $user = User::findOrFail($seller_id);
        $seller = Seller::where('email', $user->email)->first();
        
        if (!$seller) {
            abort(404, 'Store not found');
        }
        
        // Get all products from this seller
        $query = Product::where('seller_id', $seller_id)
            ->whereNotNull('image')
            ->where('image', '!=', '')
            ->where('image', 'NOT LIKE', '%unsplash%')
            ->where('image', 'NOT LIKE', '%placeholder%')
            ->where('image', 'NOT LIKE', '%via.placeholder%');
        
        // Add sorting
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
            default: // newest
                $query->orderBy('created_at', 'desc');
        }
        
        $products = $query->paginate(24)->appends($request->query());
        $totalProducts = $query->count();
        
        return view('buyer.store-catalog', compact('seller', 'products', 'totalProducts'));
    }

    public function productsByCategory(Request $request, $category_id)
    {
        $category = Category::findOrFail($category_id);
        $query = Product::where('category_id', $category_id)
            ->whereNotNull('image')
            ->where('image', '!=', '')
            ->where('image', 'NOT LIKE', '%unsplash%')
            ->where('image', 'NOT LIKE', '%placeholder%')
            ->where('image', 'NOT LIKE', '%via.placeholder%');

        // Filters
        if ($request->filled('price_min')) {
            $query->where('price', '>=', (float)$request->input('price_min'));
        }
        if ($request->filled('price_max')) {
            $query->where('price', '<=', (float)$request->input('price_max'));
        }
        if ($request->filled('discount_min')) {
            $query->where('discount', '>=', (float)$request->input('discount_min'));
        }
        if ($request->boolean('free_delivery')) {
            $query->where(function($q){ $q->whereNull('delivery_charge')->orWhere('delivery_charge', 0); });
        }

        if ($request->filled('q')) {
        $search = $request->q;
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        });
    }

        // Sorting
        $sort = $request->input('sort');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->latest();
        }

        $products = $query->paginate(12)->appends($request->query());
        $allCategories = Category::orderBy('name')->get();
        $subsByCategory = Subcategory::orderBy('name')->get()->groupBy('category_id');
        return view('buyer.products', [
            'category' => $category,
            'products' => $products,
            'categories' => $allCategories,
            'subsByCategory' => $subsByCategory,
            'activeCategoryId' => (int)$category_id,
            'activeSubcategoryId' => null,
            'filters' => $request->only(['price_min','price_max','discount_min','free_delivery','sort']),
        ]);
    }

    public function productsBySubcategory(Request $request, $subcategory_id)
    {
         $subcategory = Subcategory::with('category')->findOrFail($subcategory_id);
          $products = Product::where('subcategory_id', $subcategory_id)
            ->whereNotNull('image')
            ->where('image', '!=', '')
            ->where('image', 'NOT LIKE', '%unsplash%')
            ->where('image', 'NOT LIKE', '%placeholder%')
            ->where('image', 'NOT LIKE', '%via.placeholder%')
            ->paginate(10);
        $query = Product::where('subcategory_id', $subcategory_id)
            ->whereNotNull('image')
            ->where('image', '!=', '')
            ->where('image', 'NOT LIKE', '%unsplash%')
            ->where('image', 'NOT LIKE', '%placeholder%')
            ->where('image', 'NOT LIKE', '%via.placeholder%');

        // Filters
        if ($request->filled('price_min')) {
            $query->where('price', '>=', (float)$request->input('price_min'));
        }
        if ($request->filled('price_max')) {
            $query->where('price', '<=', (float)$request->input('price_max'));
        }
        if ($request->filled('discount_min')) {
            $query->where('discount', '>=', (float)$request->input('discount_min'));
        }
        if ($request->boolean('free_delivery')) {
            $query->where(function($q){ $q->whereNull('delivery_charge')->orWhere('delivery_charge', 0); });
        }

        // Sorting
        $sort = $request->input('sort');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->latest();
        }

        $products = $query->paginate(12)->appends($request->query());
        $allCategories = Category::orderBy('name')->get();
        $subsByCategory = Subcategory::orderBy('name')->get()->groupBy('category_id');
        return view('buyer.products', [
            'subcategory' => $subcategory,
            'products' => $products,
            'categories' => $allCategories,
            'subsByCategory' => $subsByCategory,
            'activeCategoryId' => (int)$subcategory->category_id,
            'activeSubcategoryId' => (int)$subcategory_id,
            'filters' => $request->only(['price_min','price_max','discount_min','free_delivery','sort']),
        ]);
    }}


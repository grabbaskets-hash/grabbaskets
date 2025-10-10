<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Product;
use App\Models\Blog;
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
    // ✅ Deals of the day (all products or special filtered ones)
    // $deals = Product::latest()->take(10)->get();

    return view('buyer.index', compact('categories', 'products', 'carouselProducts','trending','lookbookProduct','blogProducts',));
}



public function search(Request $request)
{
    $query = Product::whereNotNull('image')
        ->where('image', '!=', '')
        ->where('image', 'NOT LIKE', '%unsplash%')
        ->where('image', 'NOT LIKE', '%placeholder%')
        ->where('image', 'NOT LIKE', '%via.placeholder%');

    if ($request->filled('q')) {
        $query->where('name', 'like', "%{$request->q}%")
              ->orWhere('description', 'like', "%{$request->q}%");
    }

    $products = $query->paginate(12);

    return view('buyer.products', compact('products'));
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


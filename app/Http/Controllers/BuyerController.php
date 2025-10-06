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

    $carouselProducts = Product::with('category')->inRandomOrder()->take(10)->get();
    $products = Product::with('category')->latest()->paginate(12);
    // 🔥 Trending items (fetch 5 random products)
    $trending = Product::inRandomOrder()->take(5)->get();
 $lookbookProduct = Product::inRandomOrder()->first();
$blogProducts = Product::inRandomOrder()->take(3)->get();
    // ✅ Deals of the day (all products or special filtered ones)
    // $deals = Product::latest()->take(10)->get();

    return view('buyer.index', compact('categories', 'products', 'carouselProducts','trending','lookbookProduct','blogProducts',));
}



public function search(Request $request)
{
    $query = Product::query();

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
        $query = Product::where('category_id', $category_id);

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
          $products = Product::where('subcategory_id', $subcategory_id)->paginate(10);
        $query = Product::where('subcategory_id', $subcategory_id);

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


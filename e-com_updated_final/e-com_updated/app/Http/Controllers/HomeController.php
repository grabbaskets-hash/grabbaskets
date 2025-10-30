<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Banner;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function index()
    {
        try {
            // Load basic data with error handling
            $categories = Category::with('subcategories')->limit(20)->get();
            $products = Product::with('category')->limit(12)->get();
            $trending = Product::limit(8)->get();
            $banners = Banner::active()->byPosition('hero')->get();
            
            // Default settings
            $settings = [
                'hero_title' => 'Welcome to GrabBaskets',
                'hero_subtitle' => 'Your one-stop shop for all your needs',
                'show_categories' => true,
                'show_featured_products' => true,
                'show_trending' => true,
                'theme_color' => '#FF6B00',
                'secondary_color' => '#FFD700',
            ];

            return view('index', [
                'categories' => $categories,
                'products' => $products,
                'trending' => $trending,
                'lookbookProduct' => $products->first(),
                'blogProducts' => $products->take(6),
                'categoryProducts' => [],
                'banners' => $banners,
                'settings' => $settings
            ]);
        } catch (\Exception $e) {
            // Log the error
            Log::error('Homepage error: ' . $e->getMessage());
            
            // Return error response for debugging
            if (config('app.debug')) {
                return response()->json([
                    'error' => 'Homepage error',
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ], 500);
            }
            
            // Return minimal fallback page
            return view('index', [
                'categories' => collect([]),
                'products' => collect([]),
                'trending' => collect([]),
                'lookbookProduct' => null,
                'blogProducts' => collect([]),
                'categoryProducts' => [],
                'banners' => collect([]),
                'settings' => [
                    'hero_title' => 'Welcome to GrabBaskets',
                    'hero_subtitle' => 'Temporarily unavailable',
                    'theme_color' => '#FF6B00',
                ],
                'database_error' => 'Service temporarily unavailable'
            ]);
        }
    }
}

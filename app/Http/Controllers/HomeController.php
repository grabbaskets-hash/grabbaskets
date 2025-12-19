<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Banner;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

use App\Models\Seller;

class HomeController extends Controller
{
    public function index()
    {
        try {
            // Test database connection first
            DB::connection()->getPdo();
            
            // Get user location from session
            $userLat = session('user_lat');
            $userLng = session('user_lng');
            $locationDetected = $userLat && $userLng;
            
            // Default collections
            $categories = collect([]);
            $products = collect([]);
            $trending = collect([]);
            $banners = collect([]);
            $ten_min_products = collect([]);
            $nearby_stores = collect([]);
            
            // 1. Load Categories (Always global for now, or filter if needed)
            try {
                $categories = Category::with('subcategories')->limit(20)->get();
            } catch (\Exception $e) {
                Log::warning('Categories load failed: ' . $e->getMessage());
            }
            
            // 2. Load Banners
            try {
                $banners = Banner::where('is_active', true)
                    ->where('position', 'hero')
                    ->orderBy('display_order')
                    ->get();
            } catch (\Exception $e) {
                Log::warning('Banners load failed: ' . $e->getMessage());
            }

            // 3. Location Based Logic
            if ($locationDetected) {
                // Fetch nearby stores (5km radius)
                $nearby_stores = $this->getNearbyStores($userLat, $userLng, 5);
                $storeIds = $nearby_stores->pluck('id')->toArray();
                
                if (!empty($storeIds)) {
                    // Fetch products from these stores (Nearby / Instant Munchies)
                    // Priority: 2km radius for "Instant" feel
                    $ten_min_products = $this->getProductsByLocationRange($userLat, $userLng, $storeIds, 5);
                    
                    // Trending from nearby stores
                    $trending = Product::whereIn('seller_id', $storeIds)
                        ->where('is_active', true)
                        ->orderBy('created_at', 'desc') // proxy for trending
                        ->limit(8)
                        ->get();
                        
                    // All products for desktop (wider range logic if needed, currently same stores)
                    $products = Product::whereIn('seller_id', $storeIds)
                        ->with('category')
                        ->limit(24)
                        ->get();
                }
            } else {
                // Fallback for no location: Show standard list but maybe trigger "Location Needed" UI
                $products = Product::with('category')->limit(24)->get();
                $trending = Product::orderBy('created_at', 'desc')->limit(8)->get();
                $ten_min_products = Product::limit(12)->get(); // Show something so it's not empty
            }

            // Default settings
            $settings = [
                'hero_title' => 'Welcome to GrabBaskets',
                'hero_subtitle' => 'Your one-stop shop for all your needs',
                'location_detected' => $locationDetected,
                'theme_color' => '#3C096C', // Deep Purple (Zepto-ish)
                'secondary_color' => '#FFD700',
            ];

            return view('index', [
                'categories' => $categories,
                'products' => $products, // Desktop Main Grids
                'trending' => $trending, // Rails
                'banners' => $banners,
                'settings' => $settings,
                'ten_min_products' => $ten_min_products, // Mobile "Instant" Rail
                'nearby_stores' => $nearby_stores,
                'user_lat' => $userLat,
                'user_lng' => $userLng
            ]);
            
        } catch (\PDOException $e) {
            Log::error('Database connection error on homepage: ' . $e->getMessage());
            return response()->view('errors.500', [], 500);
        } catch (\Throwable $e) {
            Log::error('Homepage error: ' . $e->getMessage());
            if (config('app.debug')) {
                 return response()->json(['error' => $e->getMessage()], 500);
            }
            return response()->view('errors.500', [], 500);
        }
    }

    /**
     * Get nearby stores within specified radius (in km)
     */
    private function getNearbyStores($userLat, $userLng, $radiusKm = 5)
    {
        try {
            return Seller::selectRaw(
                '*, 
                ( 6371 * acos( cos( radians(?) ) * cos( radians( latitude ) ) * 
                cos( radians( longitude ) - radians(?) ) + sin( radians(?) ) * 
                sin( radians( latitude ) ) ) ) AS distance',
                [$userLat, $userLng, $userLat]
            )
            ->where('available_for_10_min_delivery', true)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->havingRaw('distance < ?', [$radiusKm])
            ->orderBy('distance')
            ->limit(15)
            ->get();
        } catch (\Exception $e) {
            Log::warning('Haversine calculation failed: ' . $e->getMessage());
            return collect([]);
        }
    }

    /**
     * Get products from sellers within specified range
     */
    private function getProductsByLocationRange($userLat, $userLng, $storeIds, $radiusKm = 2)
    {
        try {
            // Re-query sellers with distance for products join if needed, 
            // but for simplicity we rely on storeIds which are already filtered.
            // We can refine this to order products by store distance if we join tables.
            
            return Product::whereIn('seller_id', $storeIds)
                ->with(['category', 'seller'])
                ->where('is_active', true)
                ->where('in_stock', true)
                ->orderBy('created_at', 'desc')
                ->limit(12)
                ->get();
            
        } catch (\Exception $e) {
            return collect([]);
        }
    }
}

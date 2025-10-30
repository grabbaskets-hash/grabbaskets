<?php

namespace App\Http\Controllers\HotelOwner;

use App\Http\Controllers\Controller;
use App\Models\FoodItem;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:hotel_owner');
    }

    public function index()
    {
        try {
            $hotelOwner = Auth::guard('hotel_owner')->user();
            
            // Dashboard statistics - using safe queries
            $stats = [
                'total_food_items' => $hotelOwner->foodItems()->count(),
                'active_food_items' => $hotelOwner->foodItems()->where('is_available', true)->count(),
                'total_orders' => 0, // Food orders will be implemented later
                'pending_orders' => 0,
                'completed_orders' => 0,
                'total_revenue' => 0,
                'today_orders' => 0,
                'this_month_revenue' => 0,
            ];

            // Recent orders - empty for now until food order system is implemented
            $recentOrders = collect([]);

            // Popular food items - based on existing food items
            $popularItems = $hotelOwner->foodItems()
                ->where('is_available', true)
                ->limit(5)
                ->get();

            return view('hotel-owner.dashboard', compact('stats', 'recentOrders', 'popularItems', 'hotelOwner'));
            
        } catch (\Exception $e) {
            Log::error('Hotel Owner Dashboard Error: ' . $e->getMessage());
            
            // Fallback data in case of error
            $hotelOwner = Auth::guard('hotel_owner')->user();
            $stats = [
                'total_food_items' => 0,
                'active_food_items' => 0,
                'total_orders' => 0,
                'pending_orders' => 0,
                'completed_orders' => 0,
                'total_revenue' => 0,
                'today_orders' => 0,
                'this_month_revenue' => 0,
            ];
            $recentOrders = collect([]);
            $popularItems = collect([]);
            
            return view('hotel-owner.dashboard', compact('stats', 'recentOrders', 'popularItems', 'hotelOwner'))
                ->with('error', 'Dashboard data temporarily unavailable. Please try again later.');
        }
    }

    public function profile()
    {
        $hotelOwner = Auth::guard('hotel_owner')->user();
        return view('hotel-owner.profile', compact('hotelOwner'));
    }

    public function updateProfile(Request $request)
    {
        $hotelOwner = Auth::guard('hotel_owner')->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'restaurant_name' => 'required|string|max:255',
            'restaurant_address' => 'required|string',
            'restaurant_phone' => 'required|string|max:20',
            'cuisine_type' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'delivery_fee' => 'nullable|numeric|min:0',
            'min_order_amount' => 'nullable|integer|min:0',
            'delivery_time' => 'nullable|integer|min:10|max:120',
            'opening_time' => 'nullable|date_format:H:i',
            'closing_time' => 'nullable|date_format:H:i',
            'operating_days' => 'nullable|array',
            'operating_days.*' => 'string|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
        ]);

        $hotelOwner->update($validated);

        return back()->with('success', 'Profile updated successfully!');
    }
}

<?php

namespace App\Http\Controllers\HotelOwner;

use App\Http\Controllers\Controller;
use App\Models\FoodItem;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:hotel_owner');
    }

    public function index()
    {
        $hotelOwner = Auth::guard('hotel_owner')->user();
        
        // Dashboard statistics
        $stats = [
            'total_food_items' => $hotelOwner->foodItems()->count(),
            'active_food_items' => $hotelOwner->foodItems()->where('is_available', true)->count(),
            'total_orders' => $hotelOwner->orders()->count(),
            'pending_orders' => $hotelOwner->orders()->where('status', 'pending')->count(),
            'completed_orders' => $hotelOwner->orders()->where('status', 'completed')->count(),
            'total_revenue' => $hotelOwner->orders()->where('status', 'completed')->sum('total_amount'),
            'today_orders' => $hotelOwner->orders()->whereDate('created_at', today())->count(),
            'this_month_revenue' => $hotelOwner->orders()
                ->where('status', 'completed')
                ->whereMonth('created_at', now()->month)
                ->sum('total_amount'),
        ];

        // Recent orders
        $recentOrders = $hotelOwner->orders()
            ->with(['user', 'orderItems.product'])
            ->latest()
            ->limit(10)
            ->get();

        // Popular food items
        $popularItems = $hotelOwner->foodItems()
            ->orderBy('total_orders', 'desc')
            ->limit(5)
            ->get();

        return view('hotel-owner.dashboard', compact('stats', 'recentOrders', 'popularItems', 'hotelOwner'));
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

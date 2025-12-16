<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\FoodItem;
use App\Models\FoodOrder;
use App\Models\FoodOrderItem;
use App\Models\FoodCart;
use App\Models\FoodCartItem;
use App\Models\HotelOwner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // 👈 ADD THIS

class CustomerFoodController extends Controller
{
    public function index(Request $request)
    {
        $now = now();
        $currentTime = $now->format('H:i:s');
        $today = strtolower($now->format('l'));

        $foodCategories = FoodItem::where('is_available', 1)
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->get();

        $query = FoodItem::with('hotelOwner')
            ->where('is_available', 1)
            ->whereHas('hotelOwner', function ($q) use ($currentTime, $today) {
                $q->where('is_active', true)
                  ->whereNotNull('opening_time')
                  ->whereNotNull('closing_time')
                  ->whereRaw("JSON_CONTAINS(operating_days, '" . json_encode($today) . "')")
                  ->where('opening_time', '<=', $currentTime)
                  ->where('closing_time', '>=', $currentTime);
            });

        $search = $request->input('search');
        $category = $request->input('category');
        $vegFilter = $request->input('veg');

        if ($search) {
            $query->where('name', 'LIKE', "%{$search}%");
        } else {
            if ($category) {
                $query->where('category', $category);
            }
            if ($vegFilter === '1') {
                $query->where('food_type', 'veg');
            } elseif ($vegFilter === '0') {
                $query->where('food_type', 'non-veg');
            }
        }

        $sort = $request->input('sort');
        if ($sort === 'costLow') {
            $query->orderBy('price', 'asc');
        } elseif ($sort === 'costHigh') {
            $query->orderBy('price', 'desc');
        } elseif ($sort === 'ratingHigh') {
            $query->orderBy('rating', 'desc');
        } else {
            $query->latest();
        }

        $foods = $query->get();
        return view('customer.food.index', compact('foodCategories', 'foods'));
    }

    public function ajaxIndex(Request $request)
    {
        $query = FoodItem::with('hotelOwner')->where('is_available', 1);

        $search = $request->input('search');
        $category = $request->input('category');
        $vegFilter = $request->input('veg');
        $sort = $request->input('sort');

        if ($search) {
            $query->where('name', 'LIKE', "%{$search}%");
        } else {
            if ($category) $query->where('category', $category);
            if ($vegFilter === '1') $query->where('food_type', 'veg');
            elseif ($vegFilter === '0') $query->where('food_type', 'non-veg');
        }

        if ($sort === 'costLow') $query->orderBy('price', 'asc');
        elseif ($sort === 'costHigh') $query->orderBy('price', 'desc');
        elseif ($sort === 'ratingHigh') $query->orderBy('rating', 'desc');
        else $query->latest();

        $foods = $query->get();

        return response()->json([
            'html' => view('customer.food.partials.food-cards', compact('foods'))->render(),
            'count' => $foods->count()
        ]);
    }

    public function category($categoryName)
    {
        $foods = FoodItem::with('hotelOwner')
            ->where('category', $categoryName)
            ->where('is_available', 1)
            ->latest()
            ->paginate(20);

        return view('customer.food.category', compact('foods', 'categoryName'));
    }

    public function details($id)
    {
        $food = FoodItem::with('hotelOwner')->findOrFail($id);
        return view('customer.food.details', compact('food'));
    }

public function cartAdd(Request $request)
{
    $request->validate([
        'food_id' => 'required|exists:food_items,id'
    ]);

    $user = Auth::user();
    
    // ✅ Load food with hotel_owner_id (and only needed fields)
    $food = FoodItem::select(
        'id',
        'name',
        'price',
        'discounted_price',
        'food_type',
        'category',
        'hotel_owner_id',
        'images'
    )->findOrFail($request->food_id);

    // ✅ Critical: Validate hotel_owner_id is valid
    if (!$food->hotel_owner_id || !\App\Models\HotelOwner::where('id', $food->hotel_owner_id)->exists()) {
        return back()->with('error', 'This food item is not available from a valid restaurant.');
    }

    $cart = FoodCart::firstOrCreate(['user_id' => $user->id]);

    // Enforce single restaurant
    $existingItem = $cart->items()->first();
    if ($existingItem && $existingItem->hotel_owner_id !== $food->hotel_owner_id) {
        $cart->items()->delete();
    }

    $cartItem = $cart->items()->where('food_item_id', $food->id)->first();

    if ($cartItem) {
        $cartItem->increment('quantity');
    } else {
        // ✅ SAVE ALL SNAPSHOT FIELDS — INCLUDING hotel_owner_id
        FoodCartItem::create([
            'food_cart_id' => $cart->id,
            'food_item_id' => $food->id,
            'quantity' => 1,
            'price' => $food->getFinalPrice(),
            'name' => $food->name,
            'image_url' => $food->first_image_url, // uses your accessor
            'food_type' => $food->food_type,
            'category' => $food->category,
            'hotel_owner_id' => $food->hotel_owner_id, // ← THIS FIXES THE ERROR
        ]);
    }

    return redirect()->route('customer.food.cart')->with('success', 'Added to cart!');
}
    public function cartIndex()
    {
        $user = Auth::user();
        // ✅ NO NEED to load foodItem relationship!
        $cart = FoodCart::firstOrCreate(['user_id' => $user->id]);
        $cartItems = $cart->items; // already has all data

        // For JS product lookup (optional, but your JS uses it)
        $foodsForJs = $cartItems->map(function ($item) {
            return [
                'id' => $item->food_item_id,
                'name' => $item->name,
                'price' => (float) $item->price,
                'img' => $item->image_url ?? 'https://via.placeholder.com/150?text=No+Image',
                'desc' => \Illuminate\Support\Str::limit($item->category ?? 'Food', 40),
                'prep' => rand(10, 30),
            ];
        })->values();

        // For cart data (matches old session structure)
        $cartData = $cartItems->map(function ($item) {
            return [
                'id' => $item->food_item_id,
                'name' => $item->name,
                'price' => (float) $item->price,
                'image' => $item->image_url,
                'quantity' => $item->quantity,
                'food_type' => $item->food_type,
                'category' => $item->category,
                'hotel_owner_id' => $item->hotel_owner_id,
            ];
        })->values()->all();

        return view('customer.food.cart', compact('cartData', 'foodsForJs'));
    }

    public function cartUpdate(Request $request, $foodId)
    {
        $quantity = $request->integer('quantity') ?? $request->json('quantity');
        if ($quantity === null || $quantity < 1) {
            return response()->json(['error' => 'Invalid quantity'], 422);
        }

        $user = Auth::user();
        $cart = FoodCart::firstOrCreate(['user_id' => $user->id]);
        $cartItem = $cart->items()->where('food_item_id', $foodId)->first();

        if ($cartItem) {
            $cartItem->update(['quantity' => $quantity]);
            return response()->json(['success' => true]);
        }

        return response()->json(['error' => 'Item not in cart'], 404);
    }

    public function cartRemove($foodId)
    {
        $user = Auth::user();
        $cart = FoodCart::firstOrCreate(['user_id' => $user->id]);
        $cart->items()->where('food_item_id', $foodId)->delete();

        return request()->ajax()
            ? response()->json(['success' => true])
            : redirect()->route('customer.food.cart')->with('success', 'Removed!');
    }

    public function checkout()
    {
        $user = Auth::user();
        $cart = FoodCart::firstOrCreate(['user_id' => $user->id]);
        $cartItems = $cart->items;

        if ($cartItems->isEmpty()) {
            return redirect()->route('customer.food.cart')->with('error', 'Cart is empty.');
        }

        // Validate all same hotel (using snapshot)
        $hotelOwnerId = $cartItems->first()->hotel_owner_id;
        $allSame = $cartItems->every(fn($item) => $item->hotel_owner_id === $hotelOwnerId);
        if (!$allSame) {
            return back()->with('error', 'Multiple restaurants not allowed.');
        }

        $foodTotal = $cartItems->sum(fn($item) => $item->price * $item->quantity);
        $deliveryFee = 50.00;
        $total = $foodTotal + $deliveryFee;

        $order = FoodOrder::create([
            'hotel_owner_id' => $hotelOwnerId,
            'customer_name' => $user->name ?? 'Customer',
            'customer_phone' => '0123456789',
            'delivery_address' => '123 Test Street',
            'food_total' => $foodTotal,
            'delivery_fee' => $deliveryFee,
            'total_amount' => $total,
            'status' => 'pending',
            'estimated_delivery_time' => now()->addMinutes(10),
        ]);

        foreach ($cartItems as $item) {
            FoodOrderItem::create([
                'food_order_id' => $order->id,
                'food_item_id' => $item->food_item_id,
                'food_name' => $item->name,        // ← from snapshot!
                'price' => $item->price,
                'quantity' => $item->quantity,
                'food_type' => $item->food_type,
            ]);
        }

        $cart->items()->delete(); // clear cart
        return redirect()->route('customer.food.order.success', $order->id);
    }
    
public function showCheckout()
{
    $user = Auth::user();
    $cart = FoodCart::with('items')->firstOrCreate(['user_id' => $user->id]);
    $cartItems = $cart->items;

    if ($cartItems->isEmpty()) {
        return redirect()->route('customer.food.cart')->with('error', 'Cart is empty.');
    }

    $hotelOwnerId = $cartItems->first()->hotel_owner_id;
    if (!$cartItems->every(fn($item) => $item->hotel_owner_id === $hotelOwnerId)) {
        return back()->with('error', 'Multiple restaurants not allowed.');
    }

    // ✅ CORRECTED: Use restaurant_name and restaurant_address
    $hotel = HotelOwner::find($hotelOwnerId);
    $hotelName = $hotel ? $hotel->restaurant_name : 'Unknown Restaurant';

    $customerName = $user->name ?? 'Buyer';
    $customerPhone = $user->phone ?? '0123456789';
    $customerEmail = $user->email ?? 'user@example.com';
    $deliveryAddress = $user->address ?? '123 Test Street';

    $foodTotal = $cartItems->sum(fn($item) => $item->price * $item->quantity);
    $deliveryFee = 50.00;
    $total = $foodTotal + $deliveryFee;

    return view('customer.food.checkout', compact(
        'cartItems',
        'foodTotal',
        'deliveryFee',
        'total',
        'customerName',
        'customerPhone',
        'customerEmail',
        'deliveryAddress',
        'hotelName'
    ));
}
// ✅ ONLY ONE placeOrder()
public function placeOrder(Request $request)
{
    $user = Auth::user();
    $cart = FoodCart::firstOrCreate(['user_id' => $user->id]);
    $cartItems = $cart->items;

    if ($cartItems->isEmpty()) {
        return redirect()->route('customer.food.cart')->with('error', 'Cart is empty.');
    }

    $hotelOwnerId = $cartItems->first()->hotel_owner_id;
    if (!$cartItems->every(fn($item) => $item->hotel_owner_id === $hotelOwnerId)) {
        return back()->with('error', 'Multiple restaurants not allowed.');
    }

    $foodTotal = $cartItems->sum(fn($item) => $item->price * $item->quantity);
    $deliveryFee = 50.00;
    $total = $foodTotal + $deliveryFee;

    // ✅ CORRECTED: Use restaurant_name and restaurant_address
    $hotel = HotelOwner::find($hotelOwnerId);
    $shopName = $hotel ? $hotel->restaurant_name : 'Unknown Shop';
    $shopAddress = $hotel ? $hotel->restaurant_address : 'Address not available';

    $deliveryAddress = $request->input('delivery_address', $user->address ?? '123 Test Street');
    $customerPhone = $request->input('phone', $user->phone ?? '0123456789');
    $customerEmail = $request->input('email', $user->email);
    $paymentMethod = $request->input('payment_method', 'cod');

    $order = FoodOrder::create([
        'hotel_owner_id' => $hotelOwnerId,
        'shop_name' => $shopName,
        'shop_address' => $shopAddress,
        'customer_name' => $user->name ?? 'Customer',
        'customer_phone' => $customerPhone,
        'customer_email' => $customerEmail,
        'delivery_address' => $deliveryAddress,
        'food_total' => $foodTotal,
        'delivery_fee' => $deliveryFee,
        'total_amount' => $total,
        'payment_method' => $paymentMethod,
        'status' => 'pending',
        'estimated_delivery_time' => now()->addMinutes(10),
    ]);

    foreach ($cartItems as $item) {
        FoodOrderItem::create([
            'food_order_id' => $order->id,
            'food_item_id' => $item->food_item_id,
            'food_name' => $item->name,
            'price' => $item->price,
            'quantity' => $item->quantity,
            'food_type' => $item->food_type,
        ]);
    }

    $cart->items()->delete();
    return redirect()->route('customer.food.order.success', $order->id);
}
// ✅ ONLY ONE orderSuccess()
public function orderSuccess($orderId)
{
    $order = FoodOrder::with('items')->findOrFail($orderId);
    return view('customer.food.order-success', compact('order'));
}
}
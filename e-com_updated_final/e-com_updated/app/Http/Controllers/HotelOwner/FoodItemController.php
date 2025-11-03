<?php

namespace App\Http\Controllers\HotelOwner;

use App\Http\Controllers\Controller;
use App\Models\FoodItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FoodItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $hotelOwner = Auth::guard('hotel_owner')->user();
        $foodItems = $hotelOwner->foodItems()->latest()->paginate(12);
        
        return view('hotel-owner.food-items.index', compact('foodItems'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('hotel-owner.food-items.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'discounted_price' => 'nullable|numeric|min:0',
            'category' => 'required|string|max:100',
            'food_type' => 'required|in:veg,non-veg,vegan',
            'preparation_time' => 'nullable|integer|min:1',
            'ingredients' => 'nullable|string',
            'spice_level' => 'nullable|in:mild,medium,hot,very_hot',
            'allergens' => 'nullable|array',
            'calories' => 'nullable|integer|min:0',
            'is_available' => 'boolean',
            'is_popular' => 'boolean',
        ]);

        $validated['hotel_owner_id'] = Auth::guard('hotel_owner')->id();
        
        FoodItem::create($validated);

        return redirect()->route('hotel-owner.food-items.index')
            ->with('success', 'Food item created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(FoodItem $foodItem)
    {
        $this->authorize('view', $foodItem);
        return view('hotel-owner.food-items.show', compact('foodItem'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FoodItem $foodItem)
    {
        $this->authorize('update', $foodItem);
        return view('hotel-owner.food-items.edit', compact('foodItem'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FoodItem $foodItem)
    {
        $this->authorize('update', $foodItem);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'discounted_price' => 'nullable|numeric|min:0',
            'category' => 'required|string|max:100',
            'food_type' => 'required|in:veg,non-veg,vegan',
            'preparation_time' => 'nullable|integer|min:1',
            'ingredients' => 'nullable|string',
            'spice_level' => 'nullable|in:mild,medium,hot,very_hot',
            'allergens' => 'nullable|array',
            'calories' => 'nullable|integer|min:0',
            'is_available' => 'boolean',
            'is_popular' => 'boolean',
        ]);

        $foodItem->update($validated);

        return redirect()->route('hotel-owner.food-items.index')
            ->with('success', 'Food item updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FoodItem $foodItem)
    {
        $this->authorize('delete', $foodItem);
        
        $foodItem->delete();

        return redirect()->route('hotel-owner.food-items.index')
            ->with('success', 'Food item deleted successfully!');
    }

    /**
     * Authorize access to food item for current hotel owner
     */
    protected function authorize($action, FoodItem $foodItem)
    {
        if ($foodItem->hotel_owner_id !== Auth::guard('hotel_owner')->id()) {
            abort(403, 'Unauthorized access to this food item.');
        }
    }
}

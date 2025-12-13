<?php

namespace App\Http\Controllers\HotelOwner;

use App\Http\Controllers\Controller;
use App\Models\FoodItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;


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
    private function isLaravelCloud()
    {
        // Priority 1: Explicit Laravel Cloud deployment flag
        if (env('LARAVEL_CLOUD_DEPLOYMENT') === true) {
            return true;
        }

        // Priority 2: Check if actually running on Laravel Cloud infrastructure
        // (not just having APP_URL set to laravel.cloud)
        if (
            app()->environment('production') &&
            isset($_SERVER['SERVER_NAME']) &&
            str_contains($_SERVER['SERVER_NAME'], '.laravel.cloud')
        ) {
            return true;
        }

        // Priority 3: Vapor environment (Laravel Cloud uses Vapor)
        if (env('VAPOR_ENVIRONMENT') !== null) {
            return true;
        }

        return false;
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'discounted_price' => 'nullable|numeric|min:0',
            'category' => 'required|string|max:100',
            'food_type' => 'required|in:veg,non_veg,vegan',
            'preparation_time' => 'nullable|integer|min:1',
            'ingredients' => 'nullable|string',
            'spice_level' => 'nullable|in:mild,medium,hot,very_hot',
            'allergens' => 'nullable|string',
            'calories' => 'nullable|integer|min:0',
            'is_available' => 'nullable|boolean',
            'is_popular' => 'nullable|boolean',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $hotelOwner = Auth::guard('hotel_owner')->user();

        if (!$hotelOwner) {
            return back()->with('error', 'Authentication error');
        }

        // ---------------- IMAGE UPLOAD ----------------
        $imagePath = null;

        if ($request->hasFile('image')) {
            $image = $request->file('image');

            $folder = 'food-items/hotel-' . $hotelOwner->id;

            $filename = Str::slug(
                pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME)
            ) . '-' . time() . '.' . $image->getClientOriginalExtension();

            $disk = $this->isLaravelCloud() ? 'r2' : 'public';

            $imagePath = $image->storeAs($folder, $filename, $disk);
        }

        // ---------------- SAVE FOOD ITEM ----------------
        FoodItem::create([
            'hotel_owner_id' => $hotelOwner->id,
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'discounted_price' => $validated['discounted_price'] ?? null,
            'category' => $validated['category'],
            'food_type' => $validated['food_type'],
            'preparation_time' => $validated['preparation_time'] ?? null,
            'ingredients' => $validated['ingredients'] ?? null,
            'spice_level' => $validated['spice_level'] ?? null,
            'allergens' => $validated['allergens'] ?? null,
            'calories' => $validated['calories'] ?? null,
            'is_available' => $request->boolean('is_available'),
            'is_popular' => $request->boolean('is_popular'),
            'image' => $imagePath, // ✅ saved correctly
        ]);

        return redirect()
            ->route('hotel-owner.food-items.index')
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
        'food_type' => 'required|in:veg,non_veg,vegan',
        'preparation_time' => 'nullable|integer|min:1',
        'ingredients' => 'nullable|string',
        'spice_level' => 'nullable|in:mild,medium,hot,very_hot',
        'allergens' => 'nullable|string',
        'calories' => 'nullable|integer|min:0',
        'is_available' => 'nullable|boolean',
        'is_popular' => 'nullable|boolean',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

    // ---------------- IMAGE UPDATE ----------------
    if ($request->hasFile('image')) {

        // Delete old image if exists
        if ($foodItem->image) {
            $disk = $this->isLaravelCloud() ? 'r2' : 'public';
            Storage::disk($disk)->delete($foodItem->image);
        }

        $image = $request->file('image');
        $folder = 'food-items/hotel-' . $foodItem->hotel_owner_id;

        $filename = Str::slug(
            pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME)
        ) . '-' . time() . '.' . $image->getClientOriginalExtension();

        $disk = $this->isLaravelCloud() ? 'r2' : 'public';

        $validated['image'] = $image->storeAs($folder, $filename, $disk);
    }

    // Checkbox safe handling
    $validated['is_available'] = $request->boolean('is_available');
    $validated['is_popular'] = $request->boolean('is_popular');

    // ---------------- UPDATE FOOD ITEM ----------------
    $foodItem->update($validated);

    return redirect()
        ->route('hotel-owner.food-items.index')
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

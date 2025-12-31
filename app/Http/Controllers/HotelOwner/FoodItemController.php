<?php

namespace App\Http\Controllers\HotelOwner;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Models\FoodItem;
use Illuminate\Http\Request;

class FoodItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
  public function index()
{
    $foodItems = FoodItem::where('hotel_owner_id', auth('hotel_owner')->id())
        ->latest()
        ->paginate(9);

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
        Log::info('FoodItem store called', [
            'has_image' => $request->hasFile('image'),
            'inputs' => array_keys($request->all()),
        ]);

        // ---------------- VALIDATION ----------------
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
            'is_available' => 'boolean',
            'is_popular' => 'boolean',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // ---------------- AUTH ----------------
        $hotelOwner = Auth::guard('hotel_owner')->user();
        if (!$hotelOwner) {
            return back()->with('error', 'Authentication error');
        }

        $validated['hotel_owner_id'] = $hotelOwner->id;

        // ---------------- IMAGE UPLOAD (CLOUD READY) ----------------
        $imagePath = null;

        if ($request->hasFile('image')) {
            $image = $request->file('image');

            $folder = 'food-items/hotel-' . $hotelOwner->id;
            $filename = Str::slug(
                pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME)
            ) . '-' . time() . '.' . $image->getClientOriginalExtension();

            // Auto switch between cloud & local
            $disk = $this->isLaravelCloud() ? 'r2' : 'public';

            try {
                $imagePath = $image->storeAs($folder, $filename, $disk);
            } catch (\Exception $e) {
                Log::error('Image upload failed: ' . $e->getMessage());
                return back()->with('error', 'Failed to upload image: ' . $e->getMessage())->withInput();
            }
        }

        $validated['image'] = $imagePath;

        // ---------------- CREATE FOOD ITEM ----------------
        try {
            FoodItem::create($validated);
        } catch (\Exception $e) {
            Log::error('Food creation failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to save food item: ' . $e->getMessage())->withInput();
        }

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

    // ---------------- VALIDATION ----------------
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
        'is_available' => 'boolean',
        'is_popular' => 'boolean',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

    // ---------------- AUTH ----------------
    $hotelOwner = Auth::guard('hotel_owner')->user();
    if (!$hotelOwner) {
        return back()->with('error', 'Authentication error');
    }

    // ---------------- IMAGE UPDATE ----------------
    if ($request->hasFile('image')) {

        // Auto disk selection (local / cloud)
        $disk = $this->isLaravelCloud() ? 'r2' : 'public';

        // Delete old image if exists
        if ($foodItem->image && \Storage::disk($disk)->exists($foodItem->image)) {
            \Storage::disk($disk)->delete($foodItem->image);
        }

        $image = $request->file('image');
        $folder = 'food-items/hotel-' . $hotelOwner->id;

        $filename = Str::slug(
            pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME)
        ) . '-' . time() . '.' . $image->getClientOriginalExtension();

        $validated['image'] = $image->storeAs($folder, $filename, $disk);
    }

    // ---------------- UPDATE FOOD ITEM ----------------
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

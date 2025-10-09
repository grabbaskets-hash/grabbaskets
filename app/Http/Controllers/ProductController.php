<?php
namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Review;
use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
class ProductController extends Controller
{
    public function show($id)
    {
        try {
            // Add debug info
            Log::info("ProductController::show called with ID: " . $id);
            
            $product = Product::with(['category','subcategory'])->findOrFail($id);
            Log::info("Product found: " . $product->name);
            
            $seller = Seller::where('id', $product->seller_id)->first();
            Log::info("Seller found: " . ($seller ? $seller->name : 'none'));
            
            $reviews = Review::where('product_id', $product->id)->with('user')->latest()->get();
            Log::info("Reviews found: " . $reviews->count());
            
            $otherProducts = Product::where('seller_id', $product->seller_id)
                ->where('id', '!=', $product->id)
                ->latest()->take(8)->get();
            Log::info("Other products found: " . $otherProducts->count());
            
            // Test image URL generation
            $imageUrl = $product->image_url;
            Log::info("Image URL generated: " . $imageUrl);
            
            Log::info("About to render view...");
            
            // Temporarily return JSON instead of view to test if view is the issue
            return response()->json([
                'success' => true,
                'product' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'image' => $product->image,
                    'image_url' => $imageUrl,
                ],
                'seller' => $seller ? $seller->name : null,
                'reviews_count' => $reviews->count(),
                'other_products_count' => $otherProducts->count(),
                'message' => 'Controller working fine - issue might be in the view'
            ]);
            
            // Original view call (commented out for testing)
            // return view('buyer.product-details', compact('product', 'seller', 'reviews', 'otherProducts'));
            
        } catch (\Exception $e) {
            Log::error("Error in ProductController::show: " . $e->getMessage());
            Log::error("File: " . $e->getFile() . " Line: " . $e->getLine());
            Log::error("Trace: " . $e->getTraceAsString());
            
            // Return JSON error for debugging instead of 500 page
            return response()->json([
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => explode("\n", $e->getTraceAsString())
            ], 500);
        }
    }
    public function addReview(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);
        Review::create([
            'product_id' => $id,
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);
        return back()->with('success', 'Review added!');
    }
}

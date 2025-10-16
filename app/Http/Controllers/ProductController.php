<?php
namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Review;
use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
class ProductController extends Controller
{
    public function show($id)
    {
        try {
            $product = Product::with(['category','subcategory'])->findOrFail($id);
            
            // Ensure seller exists, if not use default values
            $seller = Seller::where('id', $product->seller_id)->first();
            
            // If seller not found, create a dummy seller object to prevent errors
            if (!$seller) {
                $seller = new Seller();
                $seller->id = 0;
                $seller->store_name = 'Store Not Available';
                $seller->store_address = 'N/A';
                $seller->store_contact = 'N/A';
                
                Log::warning("Product {$id} has no valid seller (seller_id: {$product->seller_id})");
            }
            
            $reviews = Review::where('product_id', $product->id)->with('user')->latest()->get();
            
            $otherProducts = Product::where('seller_id', $product->seller_id)
                ->where('id', '!=', $product->id)
                ->whereNotNull('image')
                ->where('image', '!=', '')
                ->where('image', 'NOT LIKE', '%unsplash%')
                ->where('image', 'NOT LIKE', '%placeholder%')
                ->where('image', 'NOT LIKE', '%via.placeholder%')
                ->latest()->take(8)->get();
            
            return view('buyer.product-details', compact('product', 'seller', 'reviews', 'otherProducts'));
            
        } catch (\Exception $e) {
            Log::error("Error loading product {$id}: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            // Return user-friendly error page
            return response()->view('errors.500', [
                'message' => 'Unable to load product details. The product may not exist or there was a server error.'
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
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('products.edit', compact('product'));
    }
}

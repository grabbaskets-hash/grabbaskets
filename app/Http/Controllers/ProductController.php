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
            // Load product with relationships including seller (User)
            $product = Product::with(['category', 'subcategory', 'seller'])->findOrFail($id);
            
            // Get seller info from sellers table via email match
            $seller = null;
            if ($product->seller && $product->seller->email) {
                $seller = Seller::where('email', $product->seller->email)->first();
            }
            
            // If seller not found, set to null (view will handle the fallback message)
            if (!$seller) {
                $seller = null;
                Log::warning("Product {$id} has no valid seller info", [
                    'seller_id' => $product->seller_id,
                    'user_exists' => $product->seller ? 'yes' : 'no',
                    'user_email' => $product->seller ? $product->seller->email : 'N/A'
                ]);
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

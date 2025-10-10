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
        $product = Product::with(['category','subcategory'])->findOrFail($id);
        $seller = Seller::where('id', $product->seller_id)->first();
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

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
            $product = Product::findOrFail($id);
            return "Product found: " . $product->name . " | Image: " . $product->image;
        } catch (\Exception $e) {
            return "Error: " . $e->getMessage() . " | File: " . $e->getFile() . " | Line: " . $e->getLine();
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

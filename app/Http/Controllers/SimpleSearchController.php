<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Product;

class SimpleSearchController extends Controller
{
    /**
     * Ultra-simple search that just returns JSON first to test if it works
     */
    public function search(Request $request)
    {
        try {
            $searchQuery = trim($request->input('q', ''));
            $matchedStores = collect();
            
            // Basic product query with image filtering
            $query = Product::whereNotNull('image')
                ->where('image', '!=', '');

            // Apply search if provided
            if (!empty($searchQuery) && strlen($searchQuery) >= 2) {
                $query->where(function ($q) use ($searchQuery) {
                    $q->where('name', 'LIKE', "%{$searchQuery}%")
                      ->orWhere('description', 'LIKE', "%{$searchQuery}%");
                });
            }

            // Apply basic filters
            if ($request->filled('price_min')) {
                $query->where('price', '>=', (float)$request->input('price_min'));
            }
            if ($request->filled('price_max')) {
                $query->where('price', '<=', (float)$request->input('price_max'));
            }
            if ($request->filled('discount_min')) {
                $query->where('discount', '>=', (float)$request->input('discount_min'));
            }

            // Apply sorting
            $sort = $request->input('sort', 'newest');
            switch ($sort) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'discount':
                    $query->orderBy('discount', 'desc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
            }

            // Get paginated results
            $products = $query->paginate(24)->appends($request->query());
            
            // Prepare response data
            $totalResults = $products->total();
            $filters = $request->only(['price_min', 'price_max', 'discount_min', 'sort']);
            
            // For now, return a simple HTML response to confirm it works
            $html = "
            <!DOCTYPE html>
            <html>
            <head>
                <title>Search Results - GrabBaskets</title>
                <meta name='viewport' content='width=device-width, initial-scale=1'>
                <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
            </head>
            <body>
                <div class='container mt-4'>
                    <h2>🔍 Search Results</h2>
                    <p><strong>Search Query:</strong> " . htmlspecialchars($searchQuery) . "</p>
                    <p><strong>Total Results:</strong> $totalResults</p>
                    <div class='row'>";
            
            foreach ($products as $product) {
                $html .= "
                    <div class='col-md-3 mb-3'>
                        <div class='card'>
                            <div class='card-body'>
                                <h6 class='card-title'>" . htmlspecialchars($product->name) . "</h6>
                                <p class='card-text'>₹" . number_format($product->price, 2) . "</p>
                            </div>
                        </div>
                    </div>";
            }
            
            $html .= "
                    </div>
                    <div class='mt-3'>
                        " . $products->links() . "
                    </div>
                </div>
            </body>
            </html>";
            
            return response($html);
            
        } catch (\Exception $e) {
            // Log error
            Log::error('Simple Search Error', [
                'query' => $request->input('q'),
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            // Return empty result
            $emptyProducts = new \Illuminate\Pagination\LengthAwarePaginator(
                collect([]),
                0,
                24,
                1,
                ['path' => $request->url(), 'query' => $request->query()]
            );
            
            $html = "
            <!DOCTYPE html>
            <html>
            <head>
                <title>Search Error - GrabBaskets</title>
                <meta name='viewport' content='width=device-width, initial-scale=1'>
                <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
            </head>
            <body>
                <div class='container mt-4'>
                    <div class='alert alert-warning'>
                        <h4>⚠️ Search Temporarily Unavailable</h4>
                        <p>We're experiencing technical difficulties. Please try again later.</p>
                        <p><small>Error: " . htmlspecialchars($e->getMessage()) . "</small></p>
                    </div>
                </div>
            </body>
            </html>";
            
            return response($html, 503);
        }
    }
}
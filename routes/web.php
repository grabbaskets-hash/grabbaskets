<?php
// Debug logging for edit product route



use App\Http\Controllers\BuyerController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Auth\OtpController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CourierTrackingController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
#use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;
// Test image upload route
#use Illuminate\Http\Request;
#use Illuminate\Support\Facades\Storage;
// Universal image serving route for public and R2 disks
use App\Http\Controllers\ImageServeController;




// Removed temporary debug route for seller edit to avoid duplication/conflicts
Route::match(['get', 'post'], '/test-upload', function(Request $request) {
    if ($request->isMethod('post')) {
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $path = $file->storeAs('products', $file->getClientOriginalName(), 'public');
            return back()->with('success', 'Image uploaded: ' . $path);
        } else {
            return back()->with('error', 'No file uploaded.');
        }
    }
    return view('test-upload');
});
// Test direct upload to R2
Route::match(['get', 'post'], '/test-upload-r2', function(Request $request) {
    if ($request->isMethod('post')) {
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $path = $file->storeAs('products', $file->getClientOriginalName(), 'r2');
            return back()->with('success', 'Image uploaded to R2: ' . $path);
        } else {
            return back()->with('error', 'No file uploaded.');
        }
    }
    return view('test-upload');
});

// Admin: Update product seller
Route::post('/admin/products/{product}/update-seller', function (Request $request, $product) {
    if (!session('is_admin')) {
        return redirect()->route('admin.login');
    }
    return app(\App\Http\Controllers\AdminController::class)->updateProductSeller($request, \App\Models\Product::findOrFail($product));
})->name('admin.products.updateSeller');

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/


Route::get('/', function () {
    // Simple test first - return basic HTML
    if (request()->has('simple')) {
        return '<h1>Simple Test Working</h1><p>Time: ' . now() . '</p>';
    }
    
    // Test with minimal template
    if (request()->has('minimal')) {
        try {
            $categories = \App\Models\Category::with('subcategories')->get();
            
        // Get sample products from ALL categories for better showcase - ONLY LEGITIMATE SELLER PRODUCTS
        $categoryProducts = [];
        foreach ($categories as $category) {
            $categoryProducts[$category->name] = \App\Models\Product::where('category_id', $category->id)
                ->whereNotNull('seller_id') // Only legitimate seller/admin products
                ->whereNotNull('image')
                ->where('image', '!=', '')
                ->where('image', 'NOT LIKE', '%unsplash%')
                ->where('image', 'NOT LIKE', '%placeholder%')
                ->where('image', 'NOT LIKE', '%via.placeholder%')
                ->inRandomOrder()
                ->take(6) // Increased to show more realistic products
                ->get();
        }
        
        // Get shuffled products from MASALA/COOKING, PERFUME/BEAUTY & DENTAL CARE - ONLY LEGITIMATE SELLER PRODUCTS
        $cookingCategory = \App\Models\Category::where('name', 'COOKING')->first();
        $beautyCategory = \App\Models\Category::where('name', 'BEAUTY & PERSONAL CARE')->first();
        $dentalCategory = \App\Models\Category::where('name', 'DENTAL CARE')->first();
        
        $mixedProducts = collect();
        
        // Get products from each category
        if ($cookingCategory) {
            $cookingProducts = \App\Models\Product::where('category_id', $cookingCategory->id)
                ->whereNotNull('seller_id') // Only legitimate seller/admin products
                ->whereNotNull('image')
                ->where('image', '!=', '')
                ->where('image', 'NOT LIKE', '%unsplash%')
                ->where('image', 'NOT LIKE', '%placeholder%')
                ->where('image', 'NOT LIKE', '%via.placeholder%')
                ->inRandomOrder()
                ->take(6)
                ->get();
            $mixedProducts = $mixedProducts->merge($cookingProducts);
        }
        
        if ($beautyCategory) {
            $beautyProducts = \App\Models\Product::where('category_id', $beautyCategory->id)
                ->whereNotNull('seller_id') // Only legitimate seller/admin products
                ->whereNotNull('image')
                ->where('image', '!=', '')
                ->where('image', 'NOT LIKE', '%unsplash%')
                ->where('image', 'NOT LIKE', '%placeholder%')
                ->where('image', 'NOT LIKE', '%via.placeholder%')
                ->inRandomOrder()
                ->take(3)
                ->get();
            $mixedProducts = $mixedProducts->merge($beautyProducts);
        }
        
        if ($dentalCategory) {
            $dentalProducts = \App\Models\Product::where('category_id', $dentalCategory->id)
                ->whereNotNull('seller_id') // Only legitimate seller/admin products
                ->whereNotNull('image')
                ->where('image', '!=', '')
                ->where('image', 'NOT LIKE', '%unsplash%')
                ->where('image', 'NOT LIKE', '%placeholder%')
                ->where('image', 'NOT LIKE', '%via.placeholder%')
                ->inRandomOrder()
                ->take(3)
                ->get();
            $mixedProducts = $mixedProducts->merge($dentalProducts);
        }
        
        // Shuffle the mixed products and paginate
        $shuffledProducts = $mixedProducts->shuffle();
        $products = new \Illuminate\Pagination\LengthAwarePaginator(
            $shuffledProducts->forPage(1, 12),
            $shuffledProducts->count(),
            12,
            1,
            ['path' => request()->url()]
        );
            $trending = \App\Models\Product::whereNotNull('seller_id') // Only legitimate seller/admin products
                ->whereNotNull('image')
                ->where('image', '!=', '')
                ->where('image', 'NOT LIKE', '%unsplash%')
                ->where('image', 'NOT LIKE', '%placeholder%')
                ->where('image', 'NOT LIKE', '%via.placeholder%')
                ->inRandomOrder()
                ->take(8)
                ->get(); // Increased for better showcase
            $lookbookProduct = \App\Models\Product::whereNotNull('seller_id') // Only legitimate seller/admin products
                ->whereNotNull('image')
                ->where('image', '!=', '')
                ->where('image', 'NOT LIKE', '%unsplash%')
                ->where('image', 'NOT LIKE', '%placeholder%')
                ->where('image', 'NOT LIKE', '%via.placeholder%')
                ->inRandomOrder()
                ->first();
            $blogProducts = \App\Models\Product::whereNotNull('seller_id') // Only legitimate seller/admin products
                ->whereNotNull('image')
                ->where('image', '!=', '')
                ->where('image', 'NOT LIKE', '%unsplash%')
                ->where('image', 'NOT LIKE', '%placeholder%')
                ->where('image', 'NOT LIKE', '%via.placeholder%')
                ->inRandomOrder()
                ->take(6)
                ->get(); // Increased for variety

            return view('index-simple', compact('categories', 'products', 'trending', 'lookbookProduct', 'blogProducts', 'categoryProducts'));
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Minimal template test failed',
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }
    
    try {
        // Force fresh data by adding a timestamp parameter that changes the cache key
        $categories = \App\Models\Category::with('subcategories')->get();
        
        // Get sample products from ALL categories for better showcase - ONLY LEGITIMATE SELLER PRODUCTS
        $categoryProducts = [];
        foreach ($categories as $category) {
            $categoryProducts[$category->name] = \App\Models\Product::where('category_id', $category->id)
                ->whereNotNull('seller_id') // Only legitimate seller/admin products
                ->whereNotNull('image')
                ->where('image', '!=', '')
                ->where('image', 'NOT LIKE', '%unsplash%')
                ->where('image', 'NOT LIKE', '%placeholder%')
                ->where('image', 'NOT LIKE', '%via.placeholder%')
                ->inRandomOrder()
                ->take(6) // Increased to show more realistic products
                ->get();
        }
        
        // Get shuffled products from MASALA/COOKING, PERFUME/BEAUTY & DENTAL CARE - ONLY LEGITIMATE SELLER PRODUCTS
        $cookingCategory = \App\Models\Category::where('name', 'COOKING')->first();
        $beautyCategory = \App\Models\Category::where('name', 'BEAUTY & PERSONAL CARE')->first();
        $dentalCategory = \App\Models\Category::where('name', 'DENTAL CARE')->first();
        
        $mixedProducts = collect();
        
        // Get products from each category
        if ($cookingCategory) {
            $cookingProducts = \App\Models\Product::where('category_id', $cookingCategory->id)
                ->whereNotNull('seller_id') // Only legitimate seller/admin products
                ->whereNotNull('image')
                ->where('image', '!=', '')
                ->where('image', 'NOT LIKE', '%unsplash%')
                ->where('image', 'NOT LIKE', '%placeholder%')
                ->where('image', 'NOT LIKE', '%via.placeholder%')
                ->inRandomOrder()
                ->take(8)
                ->get();
            $mixedProducts = $mixedProducts->merge($cookingProducts);
        }
        
        if ($beautyCategory) {
            $beautyProducts = \App\Models\Product::where('category_id', $beautyCategory->id)
                ->whereNotNull('seller_id') // Only legitimate seller/admin products
                ->whereNotNull('image')
                ->where('image', '!=', '')
                ->where('image', 'NOT LIKE', '%unsplash%')
                ->where('image', 'NOT LIKE', '%placeholder%')
                ->where('image', 'NOT LIKE', '%via.placeholder%')
                ->inRandomOrder()
                ->take(4)
                ->get();
            $mixedProducts = $mixedProducts->merge($beautyProducts);
        }
        
        if ($dentalCategory) {
            $dentalProducts = \App\Models\Product::where('category_id', $dentalCategory->id)
                ->whereNotNull('seller_id') // Only legitimate seller/admin products
                ->whereNotNull('image')
                ->where('image', '!=', '')
                ->where('image', 'NOT LIKE', '%unsplash%')
                ->where('image', 'NOT LIKE', '%placeholder%')
                ->where('image', 'NOT LIKE', '%via.placeholder%')
                ->inRandomOrder()
                ->take(3)
                ->get();
            $mixedProducts = $mixedProducts->merge($dentalProducts);
        }
        
        // Shuffle the mixed products and paginate
        $shuffledProducts = $mixedProducts->shuffle();
        $products = new \Illuminate\Pagination\LengthAwarePaginator(
            $shuffledProducts->forPage(1, 15),
            $shuffledProducts->count(),
            15,
            1,
            ['path' => request()->url()]
        );
        $trending = \App\Models\Product::whereNotNull('seller_id') // Only legitimate seller/admin products
            ->whereNotNull('image')
            ->where('image', '!=', '')
            ->where('image', 'NOT LIKE', '%unsplash%')
            ->where('image', 'NOT LIKE', '%placeholder%')
            ->where('image', 'NOT LIKE', '%via.placeholder%')
            ->inRandomOrder()
            ->take(12)
            ->get(); // Increased for better showcase
        $lookbookProduct = \App\Models\Product::whereNotNull('seller_id') // Only legitimate seller/admin products
            ->whereNotNull('image')
            ->where('image', '!=', '')
            ->where('image', 'NOT LIKE', '%unsplash%')
            ->where('image', 'NOT LIKE', '%placeholder%')
            ->where('image', 'NOT LIKE', '%via.placeholder%')
            ->inRandomOrder()
            ->first();
        $blogProducts = \App\Models\Product::whereNotNull('seller_id') // Only legitimate seller/admin products
            ->whereNotNull('image')
            ->where('image', '!=', '')
            ->where('image', 'NOT LIKE', '%unsplash%')
            ->where('image', 'NOT LIKE', '%placeholder%')
            ->where('image', 'NOT LIKE', '%via.placeholder%')
            ->inRandomOrder()
            ->take(8)
            ->get(); // Increased for variety

        return view('index', compact('categories', 'products', 'trending', 'lookbookProduct', 'blogProducts', 'categoryProducts'));
    } catch (\Exception $e) {
        // Log the error for debugging
        Log::error('Database error on homepage: ' . $e->getMessage());
        
        // For debugging, show the actual error
        if (config('app.debug')) {
            return response()->json([
                'error' => 'Index page error',
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
        
        // Return a graceful fallback with empty data
        return view('index', [
            'categories' => collect([]),
            'products' => new \Illuminate\Pagination\LengthAwarePaginator([], 0, 12),
            'trending' => collect([]),
            'lookbookProduct' => null,
            'blogProducts' => collect([]),
            'database_error' => 'Unable to load products at this time. Please try again later.'
        ]);
    }
})->name('home');

Route::get('/otp/verify-page', function (Request $request) {
    $user_id = $request->query('user_id');
    $type = $request->query('type', 'email');
    return view('auth.verify-otp', ['user_id' => $user_id, 'type' => $type]);
})->name('otp.verify.page');

// Authenticated user routes
Route::middleware(['auth', 'prevent.back'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Test route without middleware
Route::get('/test-seller-dashboard', function () {
    $controller = new App\Http\Controllers\SellerController();
    return $controller->dashboard();
});

// Verified user routes (buyer + seller)
Route::middleware(['auth', 'verified', 'prevent.back'])->group(function () {
    // Seller: Category & Subcategory
    Route::get('/seller/category-subcategory/create', [SellerController::class, 'createCategorySubcategory'])->name('seller.createCategorySubcategory');
    Route::post('/seller/category-subcategory/store', [SellerController::class, 'storeCategorySubcategory'])->name('seller.storeCategorySubcategory');
    Route::get('/seller/subcategory/add-multiple', [SellerController::class, 'addMultipleSubcategories'])->name('seller.addMultipleSubcategories');
    Route::post('/seller/subcategory/store-multiple', [SellerController::class, 'storeMultipleSubcategories'])->name('seller.storeMultipleSubcategories');

    // Seller: Product Management
    Route::get('/seller/product/create', [SellerController::class, 'createProduct'])->name('seller.createProduct');
    Route::post('/seller/product/store', [SellerController::class, 'storeProduct'])->name('seller.storeProduct');
    Route::get('/seller/product/{product}/edit', [SellerController::class, 'editProduct'])->name('seller.editProduct');
    Route::put('/seller/product/{product}', [SellerController::class, 'updateProduct'])->name('seller.updateProduct');
    Route::delete('/seller/product/{product}', [SellerController::class, 'destroyProduct'])->name('seller.destroyProduct');
    
    // Product Gallery Management
    Route::get('/seller/product/{product}/gallery', [SellerController::class, 'productGallery'])->name('seller.productGallery');
    Route::post('/seller/product/{product}/images', [SellerController::class, 'uploadProductImages'])->name('seller.uploadProductImages');
    Route::delete('/seller/product-image/{productImage}', [SellerController::class, 'deleteProductImage'])->name('seller.deleteProductImage');
    Route::patch('/seller/product-image/{productImage}/primary', [SellerController::class, 'setPrimaryImage'])->name('seller.setPrimaryImage');

    // Bulk Image Reupload
    Route::get('/seller/bulk-image-reupload', [SellerController::class, 'showBulkImageReupload'])->name('seller.bulkImageReupload');
    Route::post('/seller/bulk-image-reupload', [SellerController::class, 'processBulkImageReupload'])->name('seller.processBulkImageReupload');

    // Legacy bulk uploads (keep for compatibility)
    Route::post('/seller/bulk-image-upload-legacy', [SellerController::class, 'bulkImageUpload'])->name('seller.bulkImageUpload');
    Route::post('/seller/bulk-product-upload', [SellerController::class, 'bulkProductUpload'])->name('seller.bulkProductUpload');
    
    // Excel Bulk Upload Routes
    Route::get('/seller/bulk-upload-excel', [SellerController::class, 'showBulkUploadForm'])->name('seller.bulkUploadForm');
    Route::post('/seller/bulk-upload-excel', [SellerController::class, 'processBulkUpload'])->name('seller.processBulkUpload');
    Route::get('/seller/download-sample-excel', [SellerController::class, 'downloadSampleExcel'])->name('seller.downloadSampleExcel');

    // Seller: Dashboard & Profile
    Route::get('/seller/dashboard', [SellerController::class, 'dashboard'])->name('seller.dashboard');
    Route::get('/seller/my-profile', [SellerController::class, 'myProfile'])->name('seller.profile');
    Route::post('/seller/update-profile', [SellerController::class, 'updateProfile'])->name('seller.updateProfile');
    Route::get('/seller/profile/{seller}', [SellerController::class, 'publicProfileBySeller'])->name('seller.publicProfile');
    Route::get('/seller/transactions', [SellerController::class, 'transactions'])->name('seller.transactions');
    Route::get('/store/{seller}', [SellerController::class, 'storeProducts'])->name('store.products');

    // Seller: Image Library
    Route::get('/seller/image-library', [SellerController::class, 'imageLibrary'])->name('seller.imageLibrary');
    Route::post('/seller/upload-to-library', [SellerController::class, 'uploadToLibrary'])->name('seller.uploadToLibrary');
    Route::get('/seller/get-library-images', [SellerController::class, 'getLibraryImages'])->name('seller.getLibraryImages');
    Route::delete('/seller/delete-library-image', [SellerController::class, 'deleteLibraryImage'])->name('seller.deleteLibraryImage');

    // Orders (user & seller)
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/track', [OrderController::class, 'track'])->name('orders.track');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::get('/seller/orders', [OrderController::class, 'sellerOrders'])->name('seller.orders');
    Route::post('/orders/{order}/update-status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::post('/orders/{order}/update-tracking', [OrderController::class, 'updateTracking'])->name('orders.updateTracking');

    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/{cartItem}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cartItem}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/cart', [CartController::class, 'clear'])->name('cart.clear');
    Route::post('/cart/{cartItem}/move-to-wishlist', [CartController::class, 'moveToWishlist'])->name('cart.moveToWishlist');
    Route::get('/checkout', [CartController::class, 'showCheckout'])->name('cart.checkout.page');
    Route::post('/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
    // Payment routes
    Route::post('/payment/create-order', [PaymentController::class, 'createOrder'])->name('payment.createOrder');
    Route::post('/payment/verify', [PaymentController::class, 'verifyPayment'])->name('payment.verify');

    // Wishlist
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/add', [WishlistController::class, 'add'])->name('wishlist.add');
    Route::post('/wishlist/remove', [WishlistController::class, 'remove'])->name('wishlist.remove');
    Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::post('/wishlist/move-to-cart', [WishlistController::class, 'moveToCart'])->name('wishlist.moveToCart');
    Route::get('/wishlist/check/{product}', [WishlistController::class, 'checkStatus'])->name('wishlist.check');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
    Route::get('/notifications/unread-count', [NotificationController::class, 'getUnreadCount'])->name('notifications.unreadCount');
    Route::get('/notifications/recent', [NotificationController::class, 'getRecent'])->name('notifications.recent');

    // Chatbot
    Route::post('/chatbot/support', [SupportController::class, 'chatbotSupport'])->name('chatbot.support');

    // Buyer dashboard & browsing
    Route::get('/buyer/dashboard', [BuyerController::class, 'index'])->name('buyer.dashboard');
    Route::get('/buyer/category/{category_id}', [BuyerController::class, 'productsByCategory'])->name('buyer.productsByCategory');
    Route::get('/buyer/subcategory/{subcategory_id}', [BuyerController::class, 'productsBySubcategory'])->name('buyer.productsBySubcategory');
    
    // Courier Tracking (Authenticated users)
    Route::get('/tracking/order/{order}', [CourierTrackingController::class, 'trackOrder'])->name('tracking.order');
    Route::post('/tracking/track-multiple', [CourierTrackingController::class, 'trackMultiple'])->name('tracking.multiple');
});

// OTP Auth
Route::post('/otp/send', [OtpController::class, 'send'])->name('otp.send');
Route::post('/otp/verify', [OtpController::class, 'verify'])->name('otp.verify');

// Product details & reviews
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.details');
Route::post('/product/{id}/review', [ProductController::class, 'addReview'])
    ->middleware(['auth', 'verified'])
    ->name('product.addReview');

// Public product search
Route::get('/products', [BuyerController::class, 'search'])->name('products.index');

// Courier Tracking (Public access)
Route::get('/tracking', [CourierTrackingController::class, 'showForm'])->name('tracking.form');
Route::post('/tracking/track', [CourierTrackingController::class, 'track'])->name('tracking.track');
Route::get('/tracking/detect/{trackingNumber}', [CourierTrackingController::class, 'detectCourier'])->name('tracking.detect');

// API Routes for Courier Tracking
Route::prefix('api/tracking')->group(function () {
    Route::post('/track', [CourierTrackingController::class, 'apiTrack'])->name('api.tracking.track');
    Route::get('/detect/{trackingNumber}', [CourierTrackingController::class, 'apiDetectCourier'])->name('api.tracking.detect');
});

/*
|--------------------------------------------------------------------------
| Admin Routes (Session-based, not auth middleware)
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| Admin Routes (Session-based)
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| Admin Routes (Session-based)
|--------------------------------------------------------------------------
*/

Route::get('/admin/login', function () {
    return view('admin.login');
})->name('admin.login');

Route::post('/admin/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    if ($request->email === 'admin@swivel.co.in' && $request->password === 'swivel') {
        session(['is_admin' => true]);
        return redirect('/admin/dashboard');
    }

    return back()->withErrors(['email' => 'Invalid admin credentials']);
})->name('admin.login.submit');

Route::get('/admin/logout', function () {
    session()->forget('is_admin');
    return redirect('/');
})->name('admin.logout');

// Admin dashboard
Route::get('/admin/dashboard', function () {
    if (!session('is_admin')) {
        return redirect()->route('admin.login');
    }
    return app(AdminController::class)->transactions();
})->name('admin.dashboard');

// Admin pages (GET)
Route::get('/admin/manageuser', function (Request $request) {
    if (!session('is_admin')) {
        return redirect()->route('admin.login');
    }
    return app(AdminController::class)->users($request);
})->name('admin.manageuser');

Route::get('/admin/orders', function () {
    if (!session('is_admin')) {
        return redirect()->route('admin.login');
    }
    return app(AdminController::class)->orders(request());
})->name('admin.orders');

Route::get('/admin/products', function () {
    if (!session('is_admin')) {
        return redirect()->route('admin.login');
    }
    return app(AdminController::class)->products(request());
})->name('admin.products');

Route::get('/admin/bulk-product-upload', function () {
    if (!session('is_admin')) {
        return redirect()->route('admin.login');
    }
    return app(AdminController::class)->showBulkProductUpload();
})->name('admin.bulkProductUpload');

// Admin actions (POST/DELETE)
Route::post('/admin/bulk-product-upload', function (Request $request) {
    if (!session('is_admin')) {
        return redirect()->route('admin.login');
    }
    return app(AdminController::class)->handleBulkProductUpload($request);
})->name('admin.bulkProductUpload.post');

Route::delete('/admin/users/{id}', function ($id) {
    if (!session('is_admin')) {
        return redirect()->route('admin.login');
    }
    return app(AdminController::class)->destroy($id);
})->name('admin.users.delete');

Route::post('/admin/users/{user}/suspend', function (Request $request, $user) {
    if (!session('is_admin')) {
        return redirect()->route('admin.login');
    }
    return app(AdminController::class)->suspendUser(\App\Models\User::findOrFail($user));
})->name('admin.users.suspend');

Route::post('/admin/orders/{order}/update-status', function (Request $request, $order) {
    if (!session('is_admin')) {
        return redirect()->route('admin.login');
    }
    return app(AdminController::class)->updateOrderStatus($request, \App\Models\Order::findOrFail($order));
})->name('admin.updateOrderStatus');

Route::post('/admin/orders/{order}/update-tracking', function (Request $request, $order) {
    if (!session('is_admin')) {
        return redirect()->route('admin.login');
    }
    return app(AdminController::class)->updateTracking($request, \App\Models\Order::findOrFail($order));
})->name('admin.updateTracking');

Route::delete('/admin/products/{product}', function ($product) {
    if (!session('is_admin')) {
        return redirect()->route('admin.login');
    }
    return app(AdminController::class)->destroyProduct(\App\Models\Product::findOrFail($product));
})->name('admin.products.destroy');

// Admin Promotional Notifications
Route::get('/admin/promotional-notifications', function () {
    if (!session('is_admin')) {
        return redirect()->route('admin.login');
    }
    return app(AdminController::class)->showPromotionalForm();
})->name('admin.promotional.form');

Route::post('/admin/send-promotional-notification', function (Request $request) {
    if (!session('is_admin')) {
        return redirect()->route('admin.login');
    }
    return app(AdminController::class)->sendPromotionalNotification($request);
})->name('admin.promotional.send');

Route::post('/admin/send-automated-notifications', function (Request $request) {
    if (!session('is_admin')) {
        return redirect()->route('admin.login');
    }
    return app(AdminController::class)->sendAutomatedNotifications($request);
})->name('admin.promotional.automated');

Route::post('/admin/send-bulk-promotional-email', function (Request $request) {
    if (!session('is_admin')) {
        return redirect()->route('admin.login');
    }
    return app(AdminController::class)->sendBulkPromotionalEmail($request);
})->name('admin.promotional.bulk.email');

// SMS Management Routes
Route::get('/admin/sms-management', function () {
    if (!session('is_admin')) {
        return redirect()->route('admin.login');
    }
    return app(\App\Http\Controllers\SmsController::class)->index();
})->name('admin.sms.dashboard');

Route::post('/admin/sms/test', function (Request $request) {
    if (!session('is_admin')) {
        return redirect()->route('admin.login');
    }
    return app(\App\Http\Controllers\SmsController::class)->testSms($request);
})->name('admin.sms.test');

Route::post('/admin/sms/test-sellers', function (Request $request) {
    if (!session('is_admin')) {
        return redirect()->route('admin.login');
    }
    return app(\App\Http\Controllers\SmsController::class)->testWithCurrentSellers($request);
})->name('admin.sms.test.sellers');

Route::post('/admin/sms/bulk-promotion', function (Request $request) {
    if (!session('is_admin')) {
        return redirect()->route('admin.login');
    }
    return app(\App\Http\Controllers\SmsController::class)->sendBulkPromotion($request);
})->name('admin.sms.bulk');

Route::post('/admin/sms/order-reminders', function (Request $request) {
    if (!session('is_admin')) {
        return redirect()->route('admin.login');
    }
    return app(\App\Http\Controllers\SmsController::class)->sendOrderReminders($request);
})->name('admin.sms.reminders');

// Admin Category Emoji Management Routes
Route::prefix('admin/category-emojis')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\CategoryEmojiController::class, 'index'])->name('admin.category-emojis.index');
    Route::put('/{category}', [App\Http\Controllers\Admin\CategoryEmojiController::class, 'update'])->name('admin.category-emojis.update');
    Route::post('/bulk-update', [App\Http\Controllers\Admin\CategoryEmojiController::class, 'bulkUpdate'])->name('admin.category-emojis.bulk-update');
    Route::post('/suggestions', [App\Http\Controllers\Admin\CategoryEmojiController::class, 'getSuggestions'])->name('admin.category-emojis.suggestions');
});

// Debug route to check emojis
Route::get('/debug/emojis', function () {
    $categories = App\Models\Category::select('id', 'name', 'emoji')->get();
    $output = '<h1>Category Emojis</h1><ul>';
    foreach ($categories as $cat) {
        $output .= '<li>' . $cat->id . ': ' . $cat->name . ' = ' . ($cat->emoji ?: 'NULL') . '</li>';
    }
    $output .= '</ul>';
    return $output;
});

// Test route to update an emoji manually
Route::get('/debug/test-emoji-update/{id}/{emoji}', function ($id, $emoji) {
    $category = App\Models\Category::find($id);
    if ($category) {
        $category->emoji = $emoji;
        $category->save();
        return "Updated category {$category->name} with emoji: {$emoji}";
    }
    return "Category not found";
});

Route::post('seller/update-images-zip', [App\Http\Controllers\SellerController::class, 'updateImagesByZip'])->name('seller.updateImagesByZip');

// Include test routes
require __DIR__ . '/test.php';

// Include debug routes
require __DIR__ . '/debug.php';

require __DIR__ . '/auth.php';

// Public debug routes (no authentication required)
Route::get('/debug-bulk-system', function() {
    try {
        return response()->json([
            'status' => 'OK',
            'ziparchive_available' => class_exists('ZipArchive'),
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'storage_driver' => config('filesystems.default'),
            'categories_count' => \App\Models\Category::count(),
            'products_count' => \App\Models\Product::count(),
            'auth_middleware' => 'Route requires login to test seller features'
        ]);
    } catch (Exception $e) {
        return response()->json([
            'status' => 'ERROR',
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
});

// Image serving route as fallback for storage symlink issues
// Log facade is available via global alias; explicit import not required here

Route::get('/serve-image/{type}/{path}', function ($type, $path) {
    Log::info("/serve-image route hit", ['type' => $type, 'path' => $path]);
    // Wrap the entire closure in try/catch for error logging
    try {
        // Validate allowed types
        $allowedTypes = ['products', 'public', 'library'];
        if (!in_array($type, $allowedTypes, true)) {
            Log::warning("/serve-image: Invalid type", ['type' => $type, 'path' => $path]);
            return response()->json(['error' => 'Invalid type', 'type' => $type], 404);
        }

        // Normalize the storage-relative path
        $leafPath = ltrim($path, '/');
        if ($type === 'public') {
            $storagePath = preg_replace('/^public\//', '', $leafPath);
        } elseif ($type === 'library') {
            $storagePath = 'library/' . $leafPath;
        } else {
            // products
            $storagePath = 'products/' . $leafPath;
        }

        // Try public disk first
        try {
            $publicExists = Storage::disk('public')->exists($storagePath);
            Log::info("/serve-image: Public disk check", [
                'path' => $storagePath, 
                'exists' => $publicExists,
                'disk_root' => Storage::disk('public')->path(''),
                'full_path' => Storage::disk('public')->path($storagePath)
            ]);
            
            if ($publicExists) {
                Log::info("/serve-image: Found in public disk", ['path' => $storagePath]);
                $file = Storage::disk('public')->get($storagePath);
                $fullPath = Storage::disk('public')->path($storagePath);
                $mimeType = 'image/jpeg';
                if (function_exists('mime_content_type')) {
                    $detectedType = mime_content_type($fullPath);
                    if ($detectedType) {
                        $mimeType = $detectedType;
                    }
                }
                return Response::make($file, 200, [
                    'Content-Type' => $mimeType,
                    'Cache-Control' => 'public, max-age=86400',
                ]);
            } else {
                Log::info("/serve-image: Not found in public disk", ['path' => $storagePath]);
            }
        } catch (\Throwable $publicEx) {
            Log::warning("/serve-image: Public disk error", [
                'path' => $storagePath,
                'error' => $publicEx->getMessage()
            ]);
        }
        // Try R2 SDK directly 
        try {
            if (Storage::disk('r2')->exists($storagePath)) {
                Log::info("/serve-image: Found in r2 disk via SDK", ['path' => $storagePath]);
                $file = Storage::disk('r2')->get($storagePath);
                $ext = strtolower(pathinfo($storagePath, PATHINFO_EXTENSION));
                $mimeType = 'image/jpeg';
                if (in_array($ext, ['png', 'gif', 'webp'])) {
                    $mimeType = 'image/' . $ext;
                }
                return Response::make($file, 200, [
                    'Content-Type' => $mimeType,
                    'Cache-Control' => 'public, max-age=86400',
                ]);
            } else {
                Log::info("/serve-image: Not found in r2 disk via SDK", ['path' => $storagePath]);
            }
        } catch (\Throwable $sdkEx) {
            Log::warning('R2 SDK error in /serve-image', [
                'path' => $storagePath,
                'message' => $sdkEx->getMessage(),
            ]);
        }
        
        // For legacy paths, try multiple fallback paths
        if ($type === 'products') {
            $legacyPaths = [
                $leafPath, // Just the filename part without products/ prefix
                'images/' . $leafPath, // Old images/ prefix
                'storage/' . $leafPath, // Old storage/ prefix
                'uploads/' . $leafPath, // Old uploads/ prefix
            ];
            
            foreach ($legacyPaths as $legacyPath) {
                try {
                    if (Storage::disk('public')->exists($legacyPath)) {
                        Log::info("/serve-image: Found legacy path in public disk", ['path' => $legacyPath]);
                        $file = Storage::disk('public')->get($legacyPath);
                        $fullPath = Storage::disk('public')->path($legacyPath);
                        $mimeType = 'image/jpeg';
                        if (function_exists('mime_content_type')) {
                            $detectedType = mime_content_type($fullPath);
                            if ($detectedType) {
                                $mimeType = $detectedType;
                            }
                        }
                        return Response::make($file, 200, [
                            'Content-Type' => $mimeType,
                            'Cache-Control' => 'public, max-age=86400',
                        ]);
                    }
                    
                    if (Storage::disk('r2')->exists($legacyPath)) {
                        Log::info("/serve-image: Found legacy path in r2 disk", ['path' => $legacyPath]);
                        $file = Storage::disk('r2')->get($legacyPath);
                        $ext = strtolower(pathinfo($legacyPath, PATHINFO_EXTENSION));
                        $mimeType = 'image/jpeg';
                        if (in_array($ext, ['png', 'gif', 'webp'])) {
                            $mimeType = 'image/' . $ext;
                        }
                        return Response::make($file, 200, [
                            'Content-Type' => $mimeType,
                            'Cache-Control' => 'public, max-age=86400',
                        ]);
                    }
                } catch (\Throwable $legacyEx) {
                    // Continue to next legacy path
                    Log::debug('Legacy path not found', ['path' => $legacyPath]);
                }
            }
            
            Log::warning('All legacy paths failed for /serve-image', [
                'tested_paths' => $legacyPaths,
                'original_path' => $storagePath
            ]);
        }
        
        // If R2 public URL is configured, try redirect as fallback
        $r2Base = config('filesystems.disks.r2.url');
        if (!empty($r2Base)) {
            $target = rtrim($r2Base, '/') . '/' . ltrim($storagePath, '/');
            Log::info("/serve-image: Redirecting to R2 public URL", ['target' => $target]);
            return redirect()->away($target, 302, [
                'Cache-Control' => 'public, max-age=86400'
            ]);
        }


    Log::warning("/serve-image: File not found in any disk", ['path' => $storagePath]);
        
        // Return 404 - no placeholder
        return response()->json(['error' => 'Image not found', 'path' => $storagePath], 404);
    } catch (\Throwable $e) {
        Log::error('Error in /serve-image route', [
            'type' => $type,
            'path' => $path,
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);
        // Fail closed as 404 rather than 500 for better UX when images are missing
        return response()->json(['error' => 'Not Found'], 404);
    }
})->where('path', '.*');

// Debug: List files in storage to see what actually exists
Route::get('/debug/storage-files', function (Request $request) {
    $directory = $request->get('dir', 'products');
    $result = [
        'directory' => $directory,
        'public_files' => [],
        'r2_files' => [],
        'errors' => []
    ];
    
    try {
        $publicFiles = Storage::disk('public')->allFiles($directory);
        $result['public_files'] = array_slice($publicFiles, 0, 20); // Limit to first 20
    } catch (\Throwable $e) {
        $result['errors']['public'] = $e->getMessage();
    }
    
    try {
        $r2Files = Storage::disk('r2')->allFiles($directory);
        $result['r2_files'] = array_slice($r2Files, 0, 20); // Limit to first 20
    } catch (\Throwable $e) {
        $result['errors']['r2'] = $e->getMessage();
    }
    
    return response()->json($result);
});

// Debug: Check file system and storage configuration
Route::get('/debug/file-system', function (Request $request) {
    $path = $request->get('path', 'products/1551/teat-1760330018-OtAw4b.jpg');
    
    $result = [
        'path_tested' => $path,
        'public_disk' => [
            'exists' => false,
            'root_path' => '',
            'full_path' => '',
            'error' => null,
        ],
        'r2_disk' => [
            'exists' => false,
            'config' => [],
            'error' => null,
        ],
        'app_env' => app()->environment(),
        'storage_link_exists' => is_link(public_path('storage')),
    ];
    
    // Test public disk
    try {
        $result['public_disk']['root_path'] = Storage::disk('public')->path('');
        $result['public_disk']['full_path'] = Storage::disk('public')->path($path);
        $result['public_disk']['exists'] = Storage::disk('public')->exists($path);
    } catch (\Throwable $e) {
        $result['public_disk']['error'] = $e->getMessage();
    }
    
    // Test R2 disk
    try {
        $result['r2_disk']['config'] = config('filesystems.disks.r2');
        $result['r2_disk']['exists'] = Storage::disk('r2')->exists($path);
    } catch (\Throwable $e) {
        $result['r2_disk']['error'] = $e->getMessage();
    }
    
    return response()->json($result);
});

// Debug: Inspect a product's image resolution details by id or name
Route::get('/debug/product-image', function (Request $request) {
    $query = \App\Models\Product::with(['productImages' => function($q){ $q->orderByDesc('is_primary')->orderBy('id'); }]);
    if ($request->filled('id')) {
        $query->where('id', $request->id);
    } elseif ($request->filled('name')) {
        $query->where('name', 'LIKE', '%' . $request->name . '%');
    } else {
        return response()->json(['error' => 'Provide id or name query param'], 400);
    }

    $product = $query->first();
    if (!$product) {
        return response()->json(['error' => 'Product not found'], 404);
    }

    $details = [
        'id' => $product->id,
        'name' => $product->name,
        'legacy_image_field' => $product->image,
        'computed_image_url' => $product->image_url,
        'has_image_data' => (bool) ($product->image_data && $product->image_mime_type),
        'product_images' => $product->productImages->map(function($img){
            return [
                'path' => $img->image_path,
                'is_primary' => (bool) $img->is_primary,
                'computed_url' => $img->image_url,
            ];
        }),
        'exists_in_public_disk' => false,
        'public_disk_path_tested' => null,
        'r2_candidate_url' => null,
    ];

    // Check file existence on public disk for legacy path
    if ($product->image && !str_starts_with($product->image, 'http')) {
        $imagePath = ltrim($product->image, '/');
        $details['public_disk_path_tested'] = $imagePath;
        try {
            $details['exists_in_public_disk'] = Storage::disk('public')->exists($imagePath);
        } catch (\Throwable $e) {
            $details['exists_in_public_disk'] = false;
        }
        $r2Base = config('filesystems.disks.r2.url');
        if (!empty($r2Base)) {
            $details['r2_candidate_url'] = rtrim($r2Base, '/') . '/' . $imagePath;
        }
    }

    return response()->json($details);
});

// Public test route for simple upload (no auth required)
Route::get('/test-simple-upload', function() {
    try {
        // Add deployment verification to existing route
        $deploymentInfo = [
            'serve_route_exists' => false,
            'product_count_with_seller' => 0,
            'sample_image_url' => '',
            'routes_found' => []
        ];
        
        // Check for serve-image route
        $router = app('router');
        $routes = $router->getRoutes();
        
        foreach ($routes->getRoutes() as $route) {
            if (str_contains($route->uri(), 'serve-image')) {
                $deploymentInfo['serve_route_exists'] = true;
                $deploymentInfo['routes_found'][] = $route->uri();
            }
        }
        
        // Check product filtering
        $deploymentInfo['product_count_with_seller'] = \App\Models\Product::whereNotNull('seller_id')->count();
        
        // Get sample image URL
        $sampleProduct = \App\Models\Product::whereNotNull('seller_id')
            ->whereNotNull('image')
            ->where('image', '!=', '')
            ->first();
            
        if ($sampleProduct) {
            $deploymentInfo['sample_image_url'] = $sampleProduct->image_url;
        }
        
        return response()->json([
            'status' => 'Simple upload system working',
            'routes_available' => [
                'simple_upload_form' => url('/seller/simple-upload'),
                'login_first' => url('/login'),
                'dashboard' => url('/seller/dashboard')
            ],
            'note' => 'You need to login first to access seller routes',
            'deployment_verification' => $deploymentInfo
        ]);
    } catch (Exception $e) {
        return response()->json([
            'status' => 'ERROR',
            'message' => $e->getMessage()
        ], 500);
    }
});

// Deployment verification route
Route::get('/test-deployment', function () {
    $response = [
        'status' => 'deployment-test',
        'timestamp' => now()->toDateTimeString(),
        'routes' => [],
        'serve_route_exists' => false,
        'sample_image_url' => '',
        'product_count' => 0,
        'git_commit' => '02681ff' // Latest commit
    ];
    
    try {
        // Check if routes are loaded
        $router = app('router');
        $routes = $router->getRoutes();
        
        foreach ($routes->getRoutes() as $route) {
            if (str_contains($route->uri(), 'serve-image')) {
                $response['serve_route_exists'] = true;
                $response['routes'][] = $route->uri();
            }
        }
        
        // Get product count with seller filter
        $response['product_count'] = \App\Models\Product::whereNotNull('seller_id')->count();
        
        // Get sample image URL
        $sampleProduct = \App\Models\Product::whereNotNull('seller_id')
            ->whereNotNull('image')
            ->where('image', '!=', '')
            ->first();
            
        if ($sampleProduct) {
            $response['sample_image_url'] = $sampleProduct->image_url;
            $response['sample_product_name'] = $sampleProduct->name;
        }
        
        $response['status'] = 'success';
        
    } catch (\Exception $e) {
        $response['status'] = 'error';
        $response['error'] = $e->getMessage();
    }
    
    return response()->json($response, 200, [], JSON_PRETTY_PRINT);
});

// Test route for image display verification
Route::get('/test-images', function () {
    return view('test-images');
})->name('test.images');
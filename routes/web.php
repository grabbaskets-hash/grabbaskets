<?php

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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Health check route
Route::get('/health', function () {
    try {
        $dbConnected = false;
        $dbError = null;
        
        try {
            DB::connection()->getPdo();
            $dbConnected = true;
        } catch (\Exception $e) {
            $dbError = $e->getMessage();
        }
        
        return response()->json([
            'status' => 'OK',
            'timestamp' => now(),
            'app' => [
                'env' => config('app.env'),
                'debug' => config('app.debug'),
                'url' => config('app.url'),
            ],
            'database' => [
                'connected' => $dbConnected,
                'connection' => config('database.default'),
                'error' => $dbError
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'ERROR',
            'message' => $e->getMessage()
        ], 500);
    }
});

Route::get('/', function () {
    // Simple test first - return basic HTML
    if (request()->has('simple')) {
        return '<h1>Simple Test Working</h1><p>Time: ' . now() . '</p>';
    }
    
    // Test with minimal template
    if (request()->has('minimal')) {
        try {
            $categories = \App\Models\Category::with('subcategories')->get();
            $products = \App\Models\Product::latest()->paginate(12);
            $trending = \App\Models\Product::inRandomOrder()->take(5)->get();
            $lookbookProduct = \App\Models\Product::inRandomOrder()->first();
            $blogProducts = \App\Models\Product::latest()->take(3)->get();

            return view('index-simple', compact('categories', 'products', 'trending', 'lookbookProduct', 'blogProducts'));
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
        $products = \App\Models\Product::latest()->paginate(12);
        $trending = \App\Models\Product::inRandomOrder()->take(5)->get();
        $lookbookProduct = \App\Models\Product::inRandomOrder()->first();
        $blogProducts = \App\Models\Product::latest()->take(3)->get();

        return view('index', compact('categories', 'products', 'trending', 'lookbookProduct', 'blogProducts'));
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
    Route::get('/seller/product-images', [SellerController::class, 'productImages'])->name('seller.productImages');

    // Bulk Image Re-upload
    Route::get('/seller/bulk-image-reupload', [SellerController::class, 'showBulkImageReupload'])->name('seller.bulkImageReupload');
    Route::post('/seller/bulk-image-upload', [SellerController::class, 'processBulkImageReupload'])->name('seller.processBulkImageReupload');

    // Simple Image Upload
    Route::get('/seller/simple-upload', [SellerController::class, 'showSimpleUpload'])->name('seller.simpleUpload');
    Route::post('/seller/simple-upload', [SellerController::class, 'processSimpleUpload'])->name('seller.processSimpleUpload');

    // Debug route for testing
    Route::get('/test-bulk-upload-debug', function() {
        try {
            return response()->json([
                'status' => 'OK',
                'ziparchive_available' => class_exists('ZipArchive'),
                'authenticated' => Auth::check(),
                'user_id' => Auth::id(),
                'seller_products' => Auth::check() ? \App\Models\Product::where('seller_id', Auth::id())->count() : 0,
                'categories_available' => \App\Models\Category::count(),
                'storage_configured' => config('filesystems.default'),
                'php_version' => PHP_VERSION,
                'laravel_version' => app()->version()
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'ERROR',
                'message' => $e->getMessage()
            ]);
        }
    });
    
    // Test route for debugging
    Route::get('/test-bulk-simple', [App\Http\Controllers\TestBulkController::class, 'showBulkTest'])->name('test.bulk');

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

// Public test route for simple upload (no auth required)
Route::get('/test-simple-upload', function() {
    try {
        return response()->json([
            'status' => 'Simple upload system working',
            'routes_available' => [
                'simple_upload_form' => url('/seller/simple-upload'),
                'login_first' => url('/login'),
                'dashboard' => url('/seller/dashboard')
            ],
            'note' => 'You need to login first to access seller routes'
        ]);
    } catch (Exception $e) {
        return response()->json([
            'status' => 'ERROR',
            'message' => $e->getMessage()
        ], 500);
    }
});
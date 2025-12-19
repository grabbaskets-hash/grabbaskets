@extends('layouts.app')

@section('content')
<style>
    /* Global Zepto Theme */
    :root {
        --primary-zepto: #3C096C; /* Deep Purple */
        --accent-zepto: #FF3269; /* Pink/Red for calls to action */
        --bg-gray: #f5f7fd;
        --border-radius-card: 16px;
        --nav-height-mobile: 60px;
    }

    body {
        background-color: var(--bg-gray);
        font-family: 'Inter', sans-serif;
    }

    /* === MOBILE STYLES (< 992px) === */
    @media (max-width: 991.98px) {
        .desktop-only { display: none !important; }
        .mobile-only { display: block !important; }

        /* Sticky Header */
        .mobile-header-sticky {
            position: sticky;
            top: 0;
            z-index: 1000;
            background: #fff;
            padding: 12px 16px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .location-header {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
        }

        .location-text {
            flex-grow: 1;
            margin-left: 10px;
        }

        .location-title {
            font-size: 0.95rem;
            font-weight: 800;
            color: #2e2e2e;
            display: flex;
            align-items: center;
        }

        .location-subtitle {
            font-size: 0.75rem;
            color: #666;
            margin-top: -2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 250px;
        }

        .search-container {
            position: relative;
        }

        .search-input {
            width: 100%;
            background: #f0f5ff;
            border: 1px solid #e0e7ff;
            border-radius: 12px;
            padding: 10px 12px 10px 42px;
            font-size: 0.9rem;
            color: #333;
        }

        .search-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary-zepto);
            font-size: 1.1rem;
        }

        /* Hero Banner */
        .hero-banner-mobile {
            margin: 16px;
            border-radius: var(--border-radius-card);
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        /* Categories Zoom Grid */
        .category-grid-mobile {
            padding: 0 16px;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin-bottom: 24px;
        }

        .cat-item-mobile {
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .cat-img-box {
            width: 100%;
            aspect-ratio: 1;
            background: #eef2ff;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 6px;
            overflow: hidden;
        }

        .cat-img-box img {
            width: 80%;
            height: 80%;
            object-fit: contain;
        }

        .cat-name {
            font-size: 0.7rem;
            font-weight: 600;
            color: #444;
            line-height: 1.2;
        }

        /* Rails */
        .rail-section {
            margin-bottom: 24px;
            padding-left: 16px;
        }

        .rail-title {
            font-size: 1.1rem;
            font-weight: 800;
            color: #1a1a1a;
            margin-bottom: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-right: 16px;
        }

        .view-all {
            font-size: 0.8rem;
            color: var(--primary-zepto);
            text-decoration: none;
            font-weight: 600;
        }

        .scroll-container {
            display: flex;
            overflow-x: auto;
            gap: 12px;
            padding-right: 16px;
            padding-bottom: 8px; /* Hide scrollbar visual impact */
            -ms-overflow-style: none; /* IE/Edge */
            scrollbar-width: none; /* Firefox */
        }
        .scroll-container::-webkit-scrollbar { display: none; }

        .product-card-mobile {
            min-width: 140px;
            width: 140px;
            background: #fff;
            border-radius: 12px;
            padding: 8px;
            border: 1px solid #eee;
            position: relative;
        }

        .pm-img-box {
            width: 100%;
            height: 100px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 8px;
        }

        .pm-img-box img {
            max-height: 100%;
            max-width: 100%;
        }

        .pm-time {
            font-size: 0.6rem;
            background: #f0fdf4;
            color: #15803d;
            padding: 2px 6px;
            border-radius: 4px;
            position: absolute;
            top: 8px;
            left: 8px;
            font-weight: 700;
        }

        .pm-title {
            font-size: 0.8rem;
            color: #333;
            font-weight: 600;
            height: 32px;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            line-height: 1.2;
            margin-bottom: 4px;
        }

        .pm-weight {
            font-size: 0.75rem;
            color: #888;
            margin-bottom: 8px;
        }

        .pm-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .pm-price {
            font-size: 0.9rem;
            font-weight: 700;
            color: #1a1a1a;
        }

        .add-btn {
            border: 1px solid var(--accent-zepto);
            color: var(--accent-zepto);
            background: #fff;
            padding: 4px 16px;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
        }

        /* Bottom Nav */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            width: 100%;
            background: #fff;
            border-top: 1px solid #eee;
            display: flex;
            justify-content: space-around;
            padding: 10px 0;
            z-index: 1001;
        }

        .nav-item-m {
            display: flex;
            flex-direction: column;
            align-items: center;
            color: #999;
            text-decoration: none;
            font-size: 0.7rem;
        }

        .nav-item-m.active {
            color: var(--primary-zepto);
            font-weight: 600;
        }

        .nav-icon {
            font-size: 1.4rem;
            margin-bottom: 2px;
        }
    }

    /* === DESKTOP STYLES (>= 992px) === */
    @media (min-width: 992px) {
        .mobile-only { display: none !important; }
        .desktop-only { display: flex !important; }

        .layout-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
            display: flex;
            gap: 24px;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 240px;
            flex-shrink: 0;
            background: #fff;
            border-radius: 16px;
            height: calc(100vh - 40px);
            position: sticky;
            top: 20px;
            overflow-y: auto;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
            padding: 20px 0;
        }

        .sidebar-item {
            display: flex;
            align-items: center;
            padding: 12px 24px;
            color: #444;
            text-decoration: none;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }

        .sidebar-item:hover, .sidebar-item.active {
            background: #f8f6fc;
            color: var(--primary-zepto);
            border-left-color: var(--primary-zepto);
        }

        .sidebar-icon img {
            width: 32px;
            height: 32px;
            object-fit: contain;
            margin-right: 12px;
        }

        /* Main Content */
        .main-content {
            flex-grow: 1;
            width: 0; /* Flex fix */
        }

        /* Desktop Header */
        .desktop-header {
            background: #fff;
            padding: 16px 24px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        }

        .d-location {
            display: flex;
            flex-direction: column;
        }

        .d-search {
            flex-grow: 1;
            margin: 0 40px;
            position: relative;
        }

        .d-actions {
            display: flex;
            gap: 16px;
        }

        .products-grid-d {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
        }

        .product-card-d {
            background: #fff;
            border: 1px solid #f0f0f0;
            border-radius: 12px;
            padding: 12px;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .product-card-d:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.06);
            border-color: var(--primary-zepto);
        }
    }
</style>

<!-- === MOBILE VIEW === -->
<div class="mobile-only" style="padding-bottom: 80px;">
    <!-- Sticky Header -->
    <div class="mobile-header-sticky">
        <div class="location-header" onclick="window.location.reload()"> <!-- Quick reload hack -->
            <div class="bg-light rounded-circle p-2 d-flex">
                <i class="bi bi-geo-alt-fill text-danger fs-5"></i>
            </div>
            <div class="location-text">
                <div class="location-title">
                    Delivery to Home <i class="bi bi-chevron-down ms-1 fs-6"></i>
                </div>
                <div class="location-subtitle" id="mobile-location-text">
                    @if($settings['location_detected'])
                        Detected Location (Within 2km)
                    @else
                        Select Location to View Products...
                    @endif
                </div>
            </div>
            <a href="{{ route('profile.show') }}" class="text-dark">
                <i class="bi bi-person-circle fs-2" style="color: var(--primary-zepto);"></i>
            </a>
        </div>
        
        <div class="search-container">
            <i class="bi bi-search search-icon"></i>
            <input type="text" class="search-input" placeholder="Search for 'chips'">
        </div>
    </div>

    <!-- Location Warning Banner -->
    @if(!$settings['location_detected'])
    <div class="bg-warning p-3 mx-3 mt-3 rounded-3 d-flex align-items-center justify-content-between" id="location-warning-m">
        <div class="text-dark fw-bold fs-7">📍 Enable location to see nearby stores!</div>
        <button class="btn btn-sm btn-dark rounded-pill px-3" onclick="requestLocation()">Enable</button>
    </div>
    @endif

    <!-- Hero Banner -->
    @if($banners->count() > 0)
    <div class="hero-banner-mobile bg-white">
        <div id="heroCarouselM" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                @foreach($banners as $index => $banner)
                <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                    <img src="{{ asset('storage/' . $banner->image_path) }}" class="d-block w-100" style="aspect-ratio: 2/1; object-fit: cover;" alt="{{ $banner->title }}">
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Service Options Grid (Mobile) -->
    <!-- Service Options Grid (Mobile) -->
    <div class="mx-3 mb-4 mt-3">
        <div class="row g-2">
            <!-- 10 Min Delivery -->
            <div class="col-3 px-1">
                <a href="#instant-munchies-rail" class="text-decoration-none text-dark">
                    <div class="d-flex flex-column align-items-center text-center">
                        <div class="rounded-4 d-flex align-items-center justify-content-center mb-1 shadow-sm" style="width: 100%; aspect-ratio: 1; max-width: 60px; background: #ecfccb;">
                            <i class="bi bi-stopwatch-fill fs-3 text-success"></i>
                        </div>
                        <span style="font-size: 0.65rem; font-weight: 700; line-height: 1.1; display: block; width: 100%;">10 Mins<br>Delivery</span>
                    </div>
                </a>
            </div>
            
            <!-- Normal Delivery -->
            <div class="col-3 px-1">
                <a href="{{ route('categories.index') }}" class="text-decoration-none text-dark">
                    <div class="d-flex flex-column align-items-center text-center">
                        <div class="rounded-4 d-flex align-items-center justify-content-center mb-1 shadow-sm" style="width: 100%; aspect-ratio: 1; max-width: 60px; background: #dbeafe;">
                            <i class="bi bi-box-seam-fill fs-3 text-primary"></i>
                        </div>
                        <span style="font-size: 0.65rem; font-weight: 700; line-height: 1.1; display: block; width: 100%;">Normal<br>Delivery</span>
                    </div>
                </a>
            </div>
            
            <!-- Food Order -->
            <div class="col-3 px-1">
                <a href="{{ route('food.index') }}" class="text-decoration-none text-dark">
                    <div class="d-flex flex-column align-items-center text-center">
                        <div class="rounded-4 d-flex align-items-center justify-content-center mb-1 shadow-sm" style="width: 100%; aspect-ratio: 1; max-width: 60px; background: #ffedd5;">
                            <i class="bi bi-cup-hot-fill fs-3 text-warning"></i>
                        </div>
                        <span style="font-size: 0.65rem; font-weight: 700; line-height: 1.1; display: block; width: 100%;">Food<br>Order</span>
                    </div>
                </a>
            </div>

            <!-- Delivery Partner -->
            <div class="col-3 px-1">
                <a href="{{ route('delivery-partner.login') }}" class="text-decoration-none text-dark">
                    <div class="d-flex flex-column align-items-center text-center">
                        <div class="rounded-4 d-flex align-items-center justify-content-center mb-1 shadow-sm" style="width: 100%; aspect-ratio: 1; max-width: 60px; background: #f3f4f6;">
                            <i class="bi bi-bicycle fs-3 text-secondary"></i>
                        </div>
                        <span style="font-size: 0.65rem; font-weight: 700; line-height: 1.1; display: block; width: 100%;">Partner<br>Join</span>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Categories Grid -->
    <div class="d-flex justify-content-between px-3 mb-2 align-items-center">
        <h5 class="fw-bold mb-0 text-dark">Explore By Category</h5>
    </div>
    <div class="category-grid-mobile">
        @foreach($categories->take(8) as $cat)
        <a href="{{ route('categories.index') }}" class="text-decoration-none">
            <div class="cat-item-mobile">
                <div class="cat-img-box">
                    <img src="{{ asset('storage/' . $cat->image_path) }}" onerror="this.src='/images/no-image.png'" alt="{{ $cat->name }}">
                </div>
                <div class="cat-name">{{ Str::limit($cat->name, 10) }}</div>
            </div>
        </a>
        @endforeach
    </div>

    <!-- 1. Instant Munchies Rail (Always Location Based) -->
    <div class="rail-section">
        <div class="rail-title">
            <span>⚡ Instant Munchies</span>
            <a href="#" class="view-all">See All</a>
        </div>
        <div class="scroll-container" id="instant-munchies-rail">
            @if($settings['location_detected'] && $ten_min_products->count() > 0)
                @foreach($ten_min_products as $product)
                <div class="product-card-mobile" onclick="window.location.href='/product/{{ $product->id }}'">
                    <span class="pm-time">10 MINS</span>
                    <div class="pm-img-box">
                        <img src="{{ $product->image_url ? asset('storage/' . $product->image_url) : '/images/no-image.png' }}" onerror="this.src='/images/no-image.png'">
                    </div>
                    <div class="pm-title">{{ $product->name }}</div>
                    <div class="pm-weight text-muted"></div>
                    <div class="pm-footer">
                        <div class="pm-price">₹{{ number_format($product->price, 0) }}</div>
                        <button class="add-btn" onclick="event.stopPropagation(); addToCart({{ $product->id }})">ADD</button>
                    </div>
                    <div class="fs-8 text-secondary mt-1 truncate-1"><i class="bi bi-shop"></i> {{ $product->seller->shop_name ?? 'Nearby Store' }}</div>
                </div>
                @endforeach
            @else
                <div class="text-center w-100 py-4 text-muted fs-7">
                    @if(!$settings['location_detected'])
                        Getting your location to show instant products...
                    @else
                        No instant stores nearby :(
                    @endif
                </div>
            @endif
        </div>
    </div>

    <!-- 2. Trending Rail -->
    <div class="rail-section">
        <div class="rail-title">
            <span>🔥 Trending Near You</span>
            <a href="#" class="view-all">See All</a>
        </div>
        <div class="scroll-container">
            @foreach($trending as $product)
            <div class="product-card-mobile" onclick="window.location.href='/product/{{ $product->id }}'">
                <div class="pm-img-box">
                    <img src="{{ $product->image_url ? asset('storage/' . $product->image_url) : '/images/no-image.png' }}" onerror="this.src='/images/no-image.png'">
                </div>
                <div class="pm-title">{{ $product->name }}</div>
                <div class="pm-footer">
                    <div class="pm-price">₹{{ number_format($product->price, 0) }}</div>
                    <button class="add-btn" onclick="event.stopPropagation(); addToCart({{ $product->id }})">ADD</button>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Bottom Nav -->
    <div class="bottom-nav">
        <a href="/" class="nav-item-m active">
            <i class="bi bi-house-door-fill nav-icon"></i>
            <span>Home</span>
        </a>
        <a href="{{ route('categories.index') }}" class="nav-item-m">
            <i class="bi bi-grid-fill nav-icon"></i>
            <span>Categories</span>
        </a>
        <a href="{{ route('cart.index') }}" class="nav-item-m position-relative">
            <i class="bi bi-cart3 nav-icon"></i>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="margin-left: -20px; margin-top: 5px;">
                 @auth
                    {{ \App\Models\CartItem::where('user_id', auth()->id())->sum('quantity') ?? 0 }}
                @else
                    0
                @endauth
            </span>
            <span>Cart</span>
        </a>
    </div>
</div>

<!-- === DESKTOP VIEW === -->
<div class="desktop-only layout-container">
    
    <!-- Sidebar Navigation -->
    <aside class="sidebar">
        <div class="px-4 mb-4">
            <h4 class="fw-bold" style="color: var(--primary-zepto);">GrabBaskets</h4>
        </div>
        @foreach($categories as $cat)
        <a href="#" class="sidebar-item">
            <div class="sidebar-icon">
                <img src="{{ asset('storage/' . $cat->image_path) }}" onerror="this.src='/images/no-image.png'">
            </div>
            <span class="fw-medium">{{ $cat->name }}</span>
        </a>
        @endforeach
    </aside>

    <!-- Main Content Area -->
    <main class="main-content">
        <!-- Desktop Header -->
        <header class="desktop-header">
            <div class="d-location">
                <span class="text-secondary fs-7 fw-bold">DELIVERY IN 10 MINS</span>
                <span class="fw-bold fs-6" id="d-location-text">
                    @if($settings['location_detected'])
                        Location Detected
                    @else
                        Select Location
                    @endif
                    <i class="bi bi-chevron-down ms-1"></i>
                </span>
            </div>

            <div class="d-search">
                <i class="bi bi-search search-icon" style="left: 14px;"></i>
                <input type="text" class="search-input" placeholder="Search for 'paneer, chips, milk'" style="background: #f8f8f8;">
            </div>

            <div class="d-actions">
                @guest
                    <a href="{{ route('login') }}" class="fw-bold text-dark text-decoration-none">Login</a>
                @else
                    <a href="{{ route('profile.show') }}" class="fw-bold text-dark text-decoration-none">Profile</a>
                @endguest
                <a href="{{ route('cart.index') }}" class="btn btn-success fw-bold d-flex align-items-center gap-2" style="background: #0c831f; border: none;">
                    <i class="bi bi-cart3"></i>
                    <span>My Cart</span>
                </a>
            </div>
        </header>

        <!-- Location Alert Desktop -->
        @if(!$settings['location_detected'])
        <div class="alert alert-warning d-flex justify-content-between align-items-center shadow-sm border-0 rounded-3 mb-4">
            <div><i class="bi bi-geo-alt-fill me-2"></i> Please enable location to see products available in your area.</div>
            <button class="btn btn-dark fw-bold" onclick="requestLocation()">Identify Location</button>
        </div>
        @endif

        <!-- Banners -->
        @if($banners->count() > 0)
        <div class="row mb-5">
            @foreach($banners->take(2) as $banner)
            <div class="col-md-6 mb-3">
                <img src="{{ asset('storage/' . $banner->image_path) }}" class="w-100 rounded-4 shadow-sm" style="height: 220px; object-fit: cover;">
            </div>
            @endforeach
        </div>
        @endif

        <!-- Main Product Grid -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold text-dark">Bestsellers Near You</h3>
            <a href="#" class="text-success fw-bold text-decoration-none">see all</a>
        </div>

        <div class="products-grid-d">
            @foreach($products as $product)
            <div class="product-card-d">
                <div class="position-relative mb-3">
                    <img src="{{ $product->image_url ? asset('storage/' . $product->image_url) : '/images/no-image.png' }}" class="w-100 rounded-3" style="aspect-ratio: 1; object-fit: contain; background: #f9f9f9;" onerror="this.src='/images/no-image.png'">
                    <span class="position-absolute top-0 start-0 badge bg-white text-success shadow-sm mt-2 ms-2 border">10 MINS</span>
                </div>
                <h6 class="fw-bold mb-1 truncate-2" style="height: 40px;">{{ $product->name }}</h6>
                <div class="text-secondary fs-7 mb-3">{{ $product->quantity ?? '1 unit' }}</div>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="fw-bold">₹{{ number_format($product->price, 0) }}</span>
                    <button class="btn btn-outline-danger btn-sm fw-bold rounded-3 px-3" onclick="addToCart({{ $product->id }})">ADD</button>
                </div>
            </div>
            @endforeach
        </div>
    </main>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/location-delivery.js') }}"></script>
<script>
    const delivery = new LocationDelivery();

    // 1. Force Location if not detected
    async function requestLocation() {
        try {
            await delivery.enforceGlobalLocation();
        } catch (e) {
            alert('Location access is required to show nearby stores.');
        }
    }

    document.addEventListener('DOMContentLoaded', async () => {
        // Auto-request if not set? Maybe too aggressive.
        // Let's check status and update UI text at least.
        
        // Populate address in header if available via JS (cookies/localstorage fallback)
        // or re-fetch from lat/lng in session if we passed it to view?
        // We have {{ $user_lat }}, let's reverse geocode valid lat
        @if($settings['location_detected'])
            const address = await delivery.getAddressFromCoordinates({{ $user_lat }}, {{ $user_lng }});
            
            const mobText = document.getElementById('mobile-location-text');
            if(mobText) mobText.innerText = address;
            
            const deskText = document.getElementById('d-location-text');
            if(deskText) deskText.innerHTML = address + '<i class="bi bi-chevron-down ms-1"></i>';
            
        @else
            // If strictly enforcing, uncomment below:
            // requestLocation(); 
            // For now, we show the "Enable" banners.
        @endif
    });

    function addToCart(productId) {
        @auth
            fetch('{{ route('cart.add') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ product_id: productId, quantity: 1 })
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    // Update badge (simple reload for now or dom update)
                    window.location.reload(); 
                } else {
                    alert('Failed to add');
                }
            });
        @else
            window.location.href = "{{ route('login') }}";
        @endauth
    }
</script>
@endsection

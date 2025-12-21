<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'GrabBaskets') }} - 10 Minute Grocery Delivery</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        :root {
            /* Brand Colors */
            --primary: #3C096C; /* Deep Purple (Zepto-ish) */
            --primary-light: #5A189A;
            --secondary: #FF6B00; /* Orange Accent */
            --accent: #FFD700; /* Gold */
            
            /* UI Colors */
            --bg-body: #f5f5f5;
            --bg-white: #ffffff;
            --text-main: #212529;
            --text-muted: #6c757d;
            --border-light: #e9ecef;
            
            /* Spacing */
            --header-height-mobile: 110px;
            --bottom-nav-height: 70px;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-body);
            color: var(--text-main);
            -webkit-tap-highlight-color: transparent;
        }

        /* =========================================
           UTILITIES
           ========================================= */
        .text-primary { color: var(--primary) !important; }
        .bg-primary { background-color: var(--primary) !important; }
        .text-secondary { color: var(--secondary) !important; }
        .fw-bold { font-weight: 700 !important; }
        .fw-semibold { font-weight: 600 !important; }
        .fs-7 { font-size: 0.85rem; }
        .fs-8 { font-size: 0.75rem; }
        
        .shadow-sm-custom { box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
        .rounded-3 { border-radius: 12px !important; }
        .rounded-4 { border-radius: 16px !important; }

        .truncate-1 {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .truncate-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* =========================================
           MOBILE VIEW
           ========================================= */
        @media (max-width: 991px) {
            .desktop-only { display: none !important; }
            
            body { padding-bottom: calc(var(--bottom-nav-height) + 20px); }

            /* Mobile Header */
            .mobile-header {
                position: sticky;
                top: 0;
                z-index: 1000;
                background: var(--bg-white);
                padding: 10px 15px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            }

            .location-bar {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 12px;
            }

            .location-info h6 {
                margin: 0;
                font-size: 1.1rem;
                color: var(--text-main);
                display: flex;
                align-items: center;
                gap: 5px;
            }

            .location-info p {
                margin: 0;
                font-size: 0.8rem;
                color: var(--text-muted);
            }

            .search-bar-container {
                position: relative;
            }

            .search-input {
                width: 100%;
                background: #f3f4f6;
                border: none;
                padding: 12px 15px 12px 45px;
                border-radius: 12px;
                font-size: 0.95rem;
                transition: all 0.2s;
            }
            
            .search-input:focus {
                background: #fff;
                box-shadow: 0 0 0 2px var(--primary-light);
                outline: none;
            }

            .search-icon {
                position: absolute;
                left: 15px;
                top: 50%;
                transform: translateY(-50%);
                color: var(--text-muted);
                font-size: 1.1rem;
            }

            /* Categories Grid */
            .category-grid {
                display: grid;
                grid-template-columns: repeat(4, 1fr);
                gap: 15px 10px;
                padding: 20px 15px;
                background: #fff;
                border-radius: 0 0 20px 20px;
                margin-bottom: 10px;
            }

            .category-item {
                text-align: center;
                text-decoration: none;
                color: var(--text-main);
            }

            .cat-icon-box {
                width: 60px;
                height: 60px;
                background: #f0fdf4; /* Light green tint */
                border-radius: 16px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.8rem;
                margin: 0 auto 8px;
                transition: transform 0.2s;
            }
            .category-item:nth-child(2n) .cat-icon-box { background: #fff7ed; } /* Orange tint */
            .category-item:nth-child(3n) .cat-icon-box { background: #eff6ff; } /* Blue tint */
            .category-item:nth-child(4n) .cat-icon-box { background: #fef2f2; } /* Red tint */

            .category-item:active .cat-icon-box { transform: scale(0.95); }

            /* Horizontal Product Rail */
            .product-rail {
                background: #fff;
                padding: 20px 0;
                margin-bottom: 10px;
            }

            .rail-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 0 15px 15px;
            }

            .rail-scroll {
                display: flex;
                overflow-x: auto;
                gap: 15px;
                padding: 0 15px;
                scrollbar-width: none; /* Hide scrollbar Firefox */
            }
            .rail-scroll::-webkit-scrollbar { display: none; } /* Hide scrollbar Chrome/Safari */

            .product-card-mobile {
                min-width: 140px;
                width: 140px;
                flex-shrink: 0;
                position: relative;
            }

            .pm-image-box {
                width: 100%;
                height: 140px;
                background: #f8f9fa;
                border-radius: 14px;
                padding: 10px;
                display: flex;
                align-items: center;
                justify-content: center;
                margin-bottom: 10px;
                border: 1px solid var(--border-light);
            }

            .pm-image {
                max-width: 100%;
                max-height: 100%;
                object-fit: contain;
                mix-blend-mode: multiply;
            }

            .add-btn {
                background: #fff;
                border: 1px solid var(--primary);
                color: var(--primary);
                padding: 5px 0;
                border-radius: 6px;
                width: 100%;
                font-weight: 600;
                font-size: 0.9rem;
                margin-top: 5px;
            }
            .add-btn:active {
                background: var(--primary);
                color: #fff;
            }

            /* Bottom Nav */
            .bottom-nav {
                position: fixed;
                bottom: 0;
                left: 0;
                width: 100%;
                height: var(--bottom-nav-height);
                background: #fff;
                display: flex;
                justify-content: space-around;
                align-items: center;
                box-shadow: 0 -5px 15px rgba(0,0,0,0.05);
                z-index: 1000;
                padding-bottom: 5px; /* iOS safe area adjustment */
            }

            .nav-link-mobile {
                display: flex;
                flex-direction: column;
                align-items: center;
                text-decoration: none;
                color: var(--text-muted);
                font-size: 0.75rem;
                font-weight: 500;
                gap: 4px;
            }

            .nav-link-mobile i {
                font-size: 1.4rem;
                margin-bottom: -2px;
            }

            .nav-link-mobile.active {
                color: var(--primary);
            }
            
            .banner-carousel {
                padding: 0 15px;
                margin-bottom: 15px;
            }
            .banner-img {
                width: 100%;
                border-radius: 16px;
                aspect-ratio: 16/7;
                object-fit: cover;
            }
        }

        /* =========================================
           DESKTOP VIEW
           ========================================= */
        @media (min-width: 992px) {
            .mobile-only { display: none !important; }

            /* Desktop Navbar */
            .desktop-navbar {
                background: var(--bg-white);
                padding: 15px 0;
                position: sticky;
                top: 0;
                z-index: 1000;
                box-shadow: 0 2px 10px rgba(0,0,0,0.03);
            }

            .brand-logo {
                font-size: 1.8rem;
                font-weight: 800;
                color: var(--primary);
                text-decoration: none;
                display: flex;
                align-items: center;
                gap: 10px;
            }

            .delivery-info {
                max-width: 200px;
                line-height: 1.2;
                border-left: 1px solid var(--border-light);
                padding-left: 15px;
                margin-left: 15px;
            }

            .desktop-search {
                width: 500px;
                margin: 0 40px;
            }

            /* Layout */
            .main-layout {
                padding-top: 30px;
                display: grid;
                grid-template-columns: 240px 1fr;
                gap: 30px;
            }

            /* Sidebar */
            .sidebar-menu {
                background: #fff;
                border-radius: 16px;
                padding: 15px;
                position: sticky;
                top: 100px;
                max-height: calc(100vh - 120px);
                overflow-y: auto;
            }

            .side-link {
                display: flex;
                align-items: center;
                gap: 12px;
                padding: 12px 15px;
                color: var(--text-main);
                text-decoration: none;
                border-radius: 10px;
                transition: all 0.2s;
                font-weight: 500;
            }

            .side-link:hover {
                background: #f8f9fa;
                color: var(--primary);
                transform: translateX(5px);
            }

            /* Product Grids */
            .desktop-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
                gap: 20px;
                margin-bottom: 40px;
            }

            .product-card-desktop {
                background: #fff;
                border: 1px solid var(--border-light);
                border-radius: 16px;
                padding: 15px;
                display: flex;
                flex-direction: column;
                transition: all 0.3s ease;
                height: 100%;
            }

            .product-card-desktop:hover {
                transform: translateY(-5px);
                box-shadow: 0 10px 20px rgba(0,0,0,0.08);
                border-color: transparent;
            }

            .pd-image-box {
                height: 180px;
                display: flex;
                align-items: center;
                justify-content: center;
                margin-bottom: 15px;
                padding: 10px;
            }

            .pd-image {
                max-height: 100%;
                max-width: 100%;
                object-fit: contain;
            }

            .add-to-cart-btn-desktop {
                margin-top: auto;
                background: #fff;
                border: 1px solid var(--primary);
                color: var(--primary);
                padding: 8px;
                border-radius: 8px;
                font-weight: 600;
                width: 100%;
                transition: all 0.2s;
            }

            .add-to-cart-btn-desktop:hover {
                background: var(--primary);
                color: #fff;
            }
        }
    </style>
</head>
<body>

    <!-- =========================
         MOBILE VIEW CONTENT
         ========================= -->
    <div class="mobile-only">
        
        <!-- Sticky Header -->
        <div class="mobile-header">
            <!-- Location & Avatar -->
            <div class="location-bar">
                <div class="location-info">
                    <h6 class="fw-bold"><i class="bi bi-geo-alt-fill text-secondary"></i> Home <i class="bi bi-chevron-down fs-8 ms-1"></i></h6>
                    <p class="truncate-1">123, Green Park, Civil Lines, Nagpur</p>
                </div>
                <a href="{{ route('profile.show') }}" class="text-decoration-none">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="bi bi-person-fill fs-5"></i>
                    </div>
                </a>
            </div>

            <!-- Search Bar -->
            <div class="search-bar-container" onclick="document.querySelector('.search-input').focus()">
                <i class="bi bi-search search-icon"></i>
                <input type="text" class="search-input" placeholder="Search for 'milk'">
            </div>
        </div>

        <!-- Scrollable Content -->
        <main style="padding-bottom: 20px;">
            
            <!-- Hero Banner -->
            <div class="banner-carousel mt-3">
                <div style="background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%); border-radius: 16px; padding: 20px; color: white;">
                    <span class="badge bg-warning text-dark mb-2">⚡ Superfast Delivery</span>
                    <h2>Groceries in<br><strong>10 Minutes</strong></h2>
                    <p class="mb-0 opacity-75">Everything you need, lickety-split!</p>
                </div>
            </div>

            <!-- Categories Grid -->
            <div class="category-grid">
                @foreach(($categories ?? [])->take(8) as $cat)
                <a href="{{ route('buyer.productsByCategory', $cat->id ?? 1) }}" class="category-item">
                    <div class="cat-icon-box shadow-sm-custom">
                        {{ $cat->emoji ?? '🥬' }}
                    </div>
                    <span class="fs-8 fw-semibold truncate-1">{{ $cat->name ?? 'Category' }}</span>
                </a>
                @endforeach
                <!-- Fallback if empty -->
                @if(count($categories ?? []) == 0)
                    @foreach(['Fruits', 'Veggies', 'Dairy', 'Bakery', 'Munchies', 'Cold Drinks', 'Instant', 'Cleaning'] as $dummy)
                    <a href="#" class="category-item">
                        <div class="cat-icon-box shadow-sm-custom">📦</div>
                        <span class="fs-8 fw-semibold truncate-1">{{ $dummy }}</span>
                    </a>
                    @endforeach
                @endif
            </div>

            <!-- Trending Rail -->
            <div class="product-rail">
                <div class="rail-header">
                    <h5 class="fw-bold mb-0">🔥 Trending Now</h5>
                    <a href="#" class="text-primary text-decoration-none fs-7 fw-bold">See All</a>
                </div>
                <div class="rail-scroll">
                    @foreach(($ten_min_products ?? [])->take(6) as $prod)
                    <div class="product-card-mobile" onclick="window.location.href='{{ route('product.details', $prod->id) }}'">
                        <div class="pm-image-box">
                            <img src="{{ $prod->image_url ?? asset('images/no-image.png') }}" alt="{{ $prod->name }}" class="pm-image" onerror="this.src='{{ asset('images/no-image.png') }}'">
                        </div>
                        <div class="fs-8 text-muted truncate-1">1 unit</div>
                        <div class="fs-7 fw-bold truncate-2 mb-1" style="height: 38px;">{{ $prod->name }}</div>
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="fs-7 fw-bold">₹{{ number_format($prod->price, 0) }}</span>
                            <s class="fs-8 text-muted">₹{{ number_format($prod->price * 1.2, 0) }}</s>
                        </div>
                        <button class="add-btn" onclick="event.stopPropagation(); addToCart({{ $prod->id }})">ADD</button>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Instant Munchies (Location Based) -->
            <div class="product-rail">
                <div class="rail-header">
                    <h5 class="fw-bold mb-0">⚡ Instant Munchies (Nearby)</h5>
                    <div class="fs-8 text-muted" id="user-location-label"><i class="bi bi-geo-alt"></i> Locating...</div>
                </div>
                <div id="instant-munchies-rail" class="rail-scroll">
                    <!-- Loading State -->
                    <div class="d-flex align-items-center justify-content-center w-100 py-3 text-muted">
                        <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                        Finding stores near you...
                    </div>
                </div>
            </div>

            <!-- Fresh Rail -->
            <div class="product-rail">
                <div class="rail-header">
                    <h5 class="fw-bold mb-0">🥬 Fresh Vegetables</h5>
                    <a href="#" class="text-primary text-decoration-none fs-7 fw-bold">See All</a>
                </div>
                <div class="rail-scroll">
                    @forelse(($products ?? [])->take(6) as $prod)
                    <div class="product-card-mobile" onclick="window.location.href='{{ route('product.details', $prod->id) }}'">
                        <div class="pm-image-box">
                           <img src="{{ $prod->image_url ?? asset('images/no-image.png') }}" alt="{{ $prod->name }}" class="pm-image" onerror="this.src='{{ asset('images/no-image.png') }}'">
                        </div>
                        <div class="fs-8 text-muted truncate-1">500g</div>
                        <div class="fs-7 fw-bold truncate-2 mb-1" style="height: 38px;">{{ $prod->name }}</div>
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="fs-7 fw-bold">₹{{ number_format($prod->price, 0) }}</span>
                        </div>
                        <button class="add-btn" onclick="event.stopPropagation(); addToCart({{ $prod->id }})">ADD</button>
                    </div>
                    @empty
                    <div class="p-3 text-center w-100 text-muted">No items available</div>
                    @endforelse
                </div>
            </div>

        </main>

        <!-- Bottom Navigation -->
        <nav class="bottom-nav">
            <a href="{{ route('home') }}" class="nav-link-mobile active">
                <i class="bi bi-house-door-fill"></i>
                <span>Home</span>
            </a>
            <a href="{{ route('categories.index') }}" class="nav-link-mobile">
                <i class="bi bi-grid"></i>
                <span>Categories</span>
            </a>
            <a href="{{ route('cart.index') }}" class="nav-link-mobile position-relative">
                <i class="bi bi-cart3"></i>
                <span class="position-absolute translate-middle badge rounded-pill bg-danger" style="top: 5px; right: 15px; font-size: 0.6rem;">
                    @auth
                        {{ \App\Models\CartItem::where('user_id', auth()->id())->sum('quantity') ?? 0 }}
                    @else
                        0
                    @endauth
                </span>
                <span>Cart</span>
            </a>
            <a href="{{ route('profile.show') }}" class="nav-link-mobile">
                <i class="bi bi-person"></i>
                <span>Account</span>
            </a>
        </nav>
    </div>


    <!-- =========================
         DESKTOP VIEW CONTENT
         ========================= -->
    <div class="desktop-only">
        
        <!-- Navbar -->
        <nav class="desktop-navbar">
            <div class="container d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <a href="{{ route('home') }}" class="brand-logo">
                        <i class="bi bi-bag-check-fill text-secondary"></i> GrabBaskets
                    </a>
                    <div class="delivery-info">
                        <div class="fw-bold fs-7">Delivery in 10 mins</div>
                        <div class="text-muted fs-8 truncate-1">Nagpur, Maharashtra, India</div>
                    </div>
                </div>

                <div class="search-bar-container desktop-search">
                    <i class="bi bi-search search-icon"></i>
                    <input type="text" class="search-input" placeholder="Search for products, brands and more">
                </div>

                <div class="d-flex align-items-center gap-4">
                    @guest
                    <a href="{{ route('login') }}" class="fw-semibold text-decoration-none text-main">Login</a>
                    @else
                    <a href="{{ route('profile.show') }}" class="fw-semibold text-decoration-none text-main">Account</a>
                    @endguest
                    
                    <a href="{{ route('cart.index') }}" class="btn btn-primary rounded-4 px-4 d-flex align-items-center gap-2">
                        <i class="bi bi-cart3"></i> 
                        <span class="fw-bold">My Cart</span>
                    </a>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="container main-layout">
            
            <!-- Sidebar -->
            <aside>
                <div class="sidebar-menu shadow-sm-custom">
                    <div class="text-muted fs-8 fw-bold mb-3 px-3 text-uppercase tracking-wider">Categories</div>
                    @foreach(($categories ?? [])->take(10) as $cat)
                    <a href="{{ route('buyer.productsByCategory', $cat->id ?? 1) }}" class="side-link">
                        <span class="fs-5">{{ $cat->emoji ?? '📦' }}</span> {{ $cat->name ?? 'Category' }}
                    </a>
                    @endforeach
                    <a href="{{ route('categories.index') }}" class="side-link text-primary mt-2">
                        <i class="bi bi-grid fs-5"></i> View All
                    </a>
                </div>
            </aside>

            <!-- Right Content -->
            <main>
                
                <!-- Hero Banners -->
                <div class="row mb-5">
                    <div class="col-8">
                        <div style="background: linear-gradient(to right, #4facfe 0%, #00f2fe 100%); height: 250px; border-radius: 20px; display: flex; align-items: center; padding: 40px; color: white;">
                            <div>
                                <h1 class="fw-bold display-5 mb-2">Fresh Vegetables</h1>
                                <p class="fs-5 mb-4 opacity-90">Farm fresh at your doorstep</p>
                                <button class="btn btn-light rounded-pill px-4 fw-bold text-primary">Shop Now</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div style="background: #fff0c2; height: 250px; border-radius: 20px; padding: 30px; display: flex; flex-direction: column; justify-content: center;">
                            <h3 class="fw-bold mb-2">Instant<br>Munchies</h3>
                            <p class="text-muted">Desires delivered in minutes</p>
                            <a href="#" class="text-decoration-none fw-bold text-dark mt-auto">Browse <i class="bi bi-arrow-right"></i></a>
                        </div>
                    </div>
                </div>

                <!-- Section: Best Sellers -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="fw-bold m-0">Daily Staples</h3>
                    <a href="#" class="text-primary text-decoration-none fw-bold">see all</a>
                </div>

                <div class="desktop-grid">
                    @forelse(($all_products ?? [])->take(8) as $prod)
                    <div class="product-card-desktop">
                        <div class="pd-image-box">
                             <img src="{{ $prod->image_url ?? asset('images/no-image.png') }}" class="pd-image" alt="{{ $prod->name }}" onerror="this.src='{{ asset('images/no-image.png') }}'">
                        </div>
                        <div class="text-muted fs-8 mb-1">1 unit</div>
                        <h6 class="fw-bold truncate-2 mb-3" style="min-height: 40px;">{{ $prod->name }}</h6>
                        
                        <div class="d-flex justify-content-between align-items-end mt-auto">
                            <div>
                                <div class="text-decoration-line-through text-muted fs-8">₹{{ number_format($prod->price * 1.1, 0) }}</div>
                                <div class="fw-bold fs-5">₹{{ number_format($prod->price, 0) }}</div>
                            </div>
                            <button class="btn btn-outline-primary rounded-3 px-3 fw-bold" onclick="addToCart({{ $prod->id }})">ADD</button>
                        </div>
                    </div>
                    @empty
                    <div class="col-12 text-center py-5 text-muted bg-white rounded-4">
                        <i class="bi bi-basket display-4 mb-3 d-block"></i>
                        No products found
                    </div>
                    @endforelse
                </div>

                <!-- Section: Snacks -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="fw-bold m-0">Snacks & Drinks</h3>
                    <a href="#" class="text-primary text-decoration-none fw-bold">see all</a>
                </div>
                
                <div class="desktop-grid">
                    @foreach(($ten_min_products ?? [])->skip(6)->take(4) as $prod)
                     <div class="product-card-desktop">
                        <div class="pd-image-box">
                             <img src="{{ $prod->image_url ?? asset('images/no-image.png') }}" class="pd-image" alt="{{ $prod->name }}" onerror="this.src='{{ asset('images/no-image.png') }}'">
                        </div>
                        <div class="text-muted fs-8 mb-1">Pack</div>
                        <h6 class="fw-bold truncate-2 mb-3" style="min-height: 40px;">{{ $prod->name }}</h6>
                        
                        <div class="d-flex justify-content-between align-items-end mt-auto">
                            <div class="fw-bold fs-5">₹{{ number_format($prod->price, 0) }}</div>
                            <button class="btn btn-outline-primary rounded-3 px-3 fw-bold" onclick="addToCart({{ $prod->id }})">ADD</button>
                        </div>
                    </div>
                    @endforeach
                </div>

            </main>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/location-delivery.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', async () => {
            if (window.innerWidth < 992) { // Only on mobile/tablet for now or check layout
                console.log('Initializing Location Delivery for Instant Munchies...');
                const delivery = new LocationDelivery();
                
                try {
                    // 1. Get Location
                    await delivery.getUserLocation();
                    
                    // 2. Update status label
                    const locLabel = document.getElementById('user-location-label');
                    if (locLabel) {
                        const address = await delivery.getAddressFromCoordinates(delivery.userLat, delivery.userLng);
                        locLabel.innerHTML = `<i class="bi bi-geo-alt-fill text-success"></i> ${address || 'Nearby'}`;
                    }

                    // 3. Fetch Products (categoryId: null for all/mixed, or specify if known)
                    // Passing null category to get broad "Instant" products
                    const products = await delivery.getLocationBasedProducts(null, 5, 12); // 5km radius, 12 items
                    
                    // 4. Render
                    if (products && products.success) {
                        delivery.displayProducts(products, 'instant-munchies-rail');
                    }
                    
                } catch (error) {
                    console.error('Location error:', error);
                    const container = document.getElementById('instant-munchies-rail');
                    if (container) {
                        container.innerHTML = `
                            <div class="text-center w-100 py-3 px-3">
                                <p class="mb-2 text-muted fs-8">Location access needed for Instant Munchies</p>
                                <button class="btn btn-sm btn-outline-primary rounded-pill" onclick="window.location.reload()">Enable Location</button>
                            </div>`;
                    }
                }
            }
        });

        function addToCart(productId) {
            @auth
                fetch('{{ route('cart.add') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        quantity: 1
                    })
                })
                .then(response => {
                    if (response.ok) {
                        // Show visible feedback (simple toast for now)
                        const btn = event.target;
                        const originalText = btn.innerText;
                        btn.innerText = '✔ Added';
                        btn.classList.remove('btn-outline-primary');
                        btn.classList.add('btn-success', 'text-white');
                        
                        setTimeout(() => {
                            btn.innerText = originalText;
                            btn.classList.add('btn-outline-primary');
                            btn.classList.remove('btn-success', 'text-white');
                        }, 1000);
                        
                        // Reload to update cart count (or implement dynamic update)
                        // window.location.reload(); 
                    } else {
                        window.location.href = '{{ route('login') }}';
                    }
                })
                .catch(error => console.error('Error:', error));
            @else
                window.location.href = '{{ route('login') }}';
            @endauth
        }
    </script>
</body>
</html>

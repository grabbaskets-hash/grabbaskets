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
            --primary: #3C096C;
            --primary-light: #5A189A;
            --secondary: #FF6B00;
            --accent: #FFD700; /* Gold */
            --logout-red: linear-gradient(135deg, #ff416c, #ff4b2b);
            /* UI Colors */
            --bg-body: #f5f5f5;
            --bg-white: #ffffff;
            --text-main: #212529;
            --text-muted: #6c757d;
            --border-light: #e9ecef;
            --search-border: #d0d0d0;
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
        /* Utilities */
        .text-primary { color: var(--primary) !important; }
        .fw-bold { font-weight: 700 !important; }
        .fw-semibold { font-weight: 600 !important; }
        .fs-7 { font-size: 0.85rem; }
        .fs-8 { font-size: 0.75rem; }
        .truncate-1 {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* ===== ALL NAV BUTTONS: 10px RADIUS ===== */
        .join-btn,
        .cart-btn,
        .auth-name,
        .logout-btn,
        .icon-btn,
        .mobile-logout-btn,
        .browse-btn,
        .add-btn,
        .add-to-cart-btn-desktop {
            border-radius: 10px !important;
        }

        .navbar-gradient {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
        }

        /* Join Button */
        .join-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 22px;
            font-weight: 700;
            font-size: 0.95rem;
            border: none;
            transition: all 0.3s ease;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: white;
            box-shadow: 0 4px 10px rgba(60, 9, 108, 0.25);
        }
        .join-btn i {
            color: var(--accent); /* Gold icon */
            font-size: 1.2rem;
        }
        .join-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(60, 9, 108, 0.4);
            background: linear-gradient(135deg, var(--primary-light), #7b2cbf);
        }

        /* Cart Button - Now matches Join Button */
        .cart-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 22px;
            font-weight: 700;
            font-size: 0.95rem;
            border: none;
            transition: all 0.3s ease;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: white;
            box-shadow: 0 4px 10px rgba(60, 9, 108, 0.25);
            text-decoration: none;
        }
        .cart-btn i {
            color: var(--accent); /* Gold icon */
            font-size: 1.2rem;
        }
        .cart-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(60, 9, 108, 0.4);
            background: linear-gradient(135deg, var(--primary-light), #7b2cbf);
        }

        /* Auth buttons */
        .auth-name {
            color: var(--primary);
            font-weight: 600;
            text-decoration: none;
            padding: 8px 12px;
            background: white;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .auth-name i {
            color: var(--accent); /* Gold icon */
        }
        .auth-name:hover {
            background: #f8f9fa;
        }

        .logout-btn {
            background: var(--logout-red);
            color: white;
            border: none;
            padding: 6px 14px;
            font-weight: 600;
            font-size: 0.85rem;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
            transition: transform 0.2s;
        }
        .logout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 6px rgba(255, 75, 43, 0.4);
        }
        .logout-btn i {
            font-size: 1rem;
        }

        /* Search Bar */
        .search-bar-container {
            position: relative;
            width: 100%;
        }
        .search-input {
            width: 100%;
            background: transparent !important;
            border: 1px solid var(--search-border) !important;
            border-radius: 50px !important;
            padding: 10px 20px 10px 50px;
            color: white !important;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        .search-input::placeholder {
            color: rgba(255, 255, 255, 0.8) !important;
        }
        .search-input:focus {
            background: white !important;
            color: var(--text-main) !important;
            border: 1px solid var(--search-border) !important;
            outline: none;
            box-shadow: 0 0 0 2px rgba(60, 9, 108, 0.2) !important;
        }
        .search-icon {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.9) !important;
            font-size: 1.2rem;
            pointer-events: none;
        }
        .search-input:focus ~ .search-icon {
            color: var(--text-muted) !important;
        }

        /* Bounce Animation for Down Arrow */
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
            40% { transform: translateY(-8px); }
            60% { transform: translateY(-4px); }
        }
        .down-arrow {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            animation: bounce 2s infinite;
        }

        /* ===== MOBILE VIEW ===== */
        @media (max-width: 991px) {
            .desktop-only { display: none !important; }
            body { padding-bottom: calc(var(--bottom-nav-height) + 20px); }
            .mobile-header {
                position: sticky;
                top: 0;
                z-index: 1000;
                padding: 12px 15px;
            }
            .brand-mobile {
                font-size: 1.6rem;
                font-weight: 800;
                color: white;
                text-decoration: none;
                display: flex;
                align-items: center;
                gap: 8px;
            }
            .search-bar-container { margin-top: 12px; }
            .search-input {
                padding: 12px 15px 12px 48px;
                border-radius: 12px !important;
            }
            .search-icon { left: 15px; font-size: 1.1rem; }

            /* Mobile Icons - Now use navbar background */
            .mobile-icons {
                display: flex;
                align-items: center;
                gap: 14px;
            }
            .icon-btn {
                width: 44px;
                height: 44px;
                border-radius: 10px; /* Updated to 10px */
                background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%); /* Navbar bg */
                display: flex;
                align-items: center;
                justify-content: center;
                text-decoration: none;
                transition: all 0.2s ease;
                box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            }
            .icon-btn i {
                color: var(--accent) !important; /* Gold icon */
                font-size: 1.3rem;
                font-weight: bold;
            }
            .icon-btn:hover {
                transform: scale(1.08);
                opacity: 0.9;
            }

            .mobile-auth-group {
                display: flex;
                align-items: center;
                gap: 10px;
            }
            .mobile-logout-btn {
                background: var(--logout-red);
                border: none;
                color: white;
                width: 36px;
                height: 36px;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .mobile-logout-btn i {
                font-size: 1.1rem;
            }
            .mobile-logout-btn:hover {
                transform: scale(1.1);
            }

            /* Banners - Match desktop style */
            .hero-banner {
                background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
                height: 250px;
                border-radius: 20px;
                display: flex;
                flex-direction: column;
                justify-content: center;
                padding: 30px;
                color: white;
                margin: 20px 15px 20px;
                position: relative;
            }
            .munchies-banner {
                background: linear-gradient(135deg, #FFD700 0%, #FF9A00 100%);
                height: 250px;
                border-radius: 20px;
                padding: 30px;
                display: flex;
                flex-direction: column;
                justify-content: center;
                margin: 20px 15px;
            }
            .munchies-banner h3 {
                font-size: 1.8rem;
                margin-bottom: 10px;
                color: #212529;
                font-weight: 700;
            }
            .munchies-banner p {
                color: #5a5a5a;
                margin-bottom: 20px;
                font-weight: 500;
            }

            .browse-btn {
                background: #3C096C;
                color: white;
                border: none;
                padding: 10px 24px;
                font-weight: 600;
                display: inline-flex;
                align-items: center;
                gap: 8px;
                text-decoration: none;
                transition: all 0.3s ease;
                width: fit-content;
            }
            .browse-btn:hover {
                background: #2a064d;
                transform: translateY(-2px);
                box-shadow: 0 4px 8px rgba(60, 9, 108, 0.3);
            }

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
                background: #f0fdf4;
                border-radius: 16px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.8rem;
                margin: 0 auto 8px;
                transition: transform 0.2s;
                
            }
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
                scrollbar-width: none;
            }
            .rail-scroll::-webkit-scrollbar { display: none; }
            .product-card-mobile {
                min-width: 140px;
                width: 140px;
                flex-shrink: 0;
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
                width: 100%;
                font-weight: 600;
                font-size: 0.9rem;
                margin-top: 5px;
            }
            .add-btn:active {
                background: var(--primary);
                color: #fff;
            }
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
                padding-bottom: 5px;
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
        }

        /* ===== DESKTOP VIEW ===== */
        @media (min-width: 992px) {
            .mobile-only { display: none !important; }
            .desktop-navbar {
                background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
                padding: 15px 0;
                position: sticky;
                top: 0;
                z-index: 1000;
                box-shadow: 0 2px 10px rgba(0,0,0,0.03);
            }
            .navbar-container {
                display: flex;
                align-items: center;
                justify-content: space-between;
                width: 100%;
                max-width: 1400px;
                margin: 0 auto;
                padding: 0 20px;
            }
            .brand-section {
                display: flex;
                align-items: center;
            }
            .brand-logo {
                font-size: 1.8rem;
                font-weight: 800;
                color: white;
                text-decoration: none;
                display: flex;
                align-items: center;
                gap: 10px;
            }
            .delivery-info {
                max-width: 200px;
                line-height: 1.2;
                border-left: 1px solid rgba(255, 255, 255, 0.3);
                padding-left: 15px;
                margin-left: 15px;
                color: rgba(255, 255, 255, 0.9);
            }
            .desktop-search {
                width: 400px;
                margin: 0 30px;
            }
            .nav-actions {
                display: flex;
                align-items: center;
                gap: 20px;
            }
            .main-layout {
                padding-top: 30px;
                display: grid;
                grid-template-columns: 240px 1fr;
                gap: 30px;
            }
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
                font-weight: 600;
                width: 100%;
                transition: all 0.2s;
            }
            .add-to-cart-btn-desktop:hover {
                background: var(--primary);
                color: #fff;
            }
            .hero-banner {
                background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
                height: 250px;
                border-radius: 20px;
                display: flex;
                align-items: center;
                padding: 40px;
                color: white;
                margin-bottom: 30px;
               position: relative;
            }
            .hero-banner .content {
                z-index: 2;
            }
            .hero-banner h1 {
                font-size: 2.2rem;
                margin-bottom: 10px;
            }
            .hero-banner p {
                opacity: 0.9;
                margin-bottom: 20px;
                font-size: 1.1rem;
            }
            .munchies-banner {
                background: linear-gradient(135deg, #FFD700 0%, #FF9A00 100%);
                height: 250px;
                width: 370px;
                border-radius: 20px;
                padding: 30px;
                display: flex;
                flex-direction: column;
                justify-content: center;
                margin-top: 10px;
            }
            .munchies-banner h3 {
                font-size: 1.8rem;
                margin-bottom: 10px;
                color: #212529;
                font-weight: 700;
            }
            .munchies-banner p {
                color: #5a5a5a;
                margin-bottom: 20px;
                font-weight: 500;
            }
            .browse-btn {
                background: #3C096C;
                color: white;
                border: none;
                padding: 10px 24px;
                font-weight: 600;
                display: inline-flex;
                align-items: center;
                gap: 8px;
                text-decoration: none;
                transition: all 0.3s ease;
                width: fit-content;
            }
            .browse-btn:hover {
                background: #2a064d;
                transform: translateY(-2px);
                box-shadow: 0 4px 8px rgba(60, 9, 108, 0.3);
            }
        }

        .location-tracker {
    cursor: pointer;
}

.location-tracker:hover {
    opacity: 0.8;
}

    </style>
</head>
<body>
    <!-- MOBILE VIEW -->
    <div class="mobile-only">
        <div class="mobile-header navbar-gradient">
            <div class="d-flex align-items-center justify-content-between">
                <a href="{{ route('home') }}" class="brand-mobile">
                    <i class="bi bi-bag-check-fill"></i> GrabBaskets
                </a>
                <div class="d-flex align-items-center">
                    <div class="mobile-icons">
                        <a href="#" class="icon-btn" title="Join With Us">
                            <i class="bi bi-shop"></i>
                        </a>
                        <a href="{{ route('cart.index') }}" class="icon-btn" title="Cart">
                            <i class="bi bi-cart3"></i>
                        </a>
                    </div>
                    @auth
                    <div class="mobile-auth-group">
                        <a href="{{ route('profile.show') }}" class="auth-name" style="font-size: 0.95rem;">{{ Auth::user()->name }}</a>
                        <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="mobile-logout-btn" title="Logout">
                                <i class="bi bi-box-arrow-right"></i>
                            </button>
                        </form>
                    </div>
                    @else
                    <a href="{{ route('login') }}" class="icon-btn" title="Login">
                        <i class="bi bi-person-fill"></i>
                    </a>
                    @endauth
                </div>
            </div>
            <div class="search-bar-container">
                <input type="text" class="search-input" placeholder="Search for products...">
                <i class="bi bi-search search-icon"></i>
            </div>
        </div>

        <main style="padding-bottom: 20px;">
            <div class="hero-banner">
                <span class="badge bg-warning text-dark mb-2">⚡ Superfast Delivery</span>
                <h2>GrabBaskets<br>Ecommerce Website</h2>
                <p>Your one-stop shop for all grocery needs with 10-minute delivery!</p>
                <div class="down-arrow">
                    <i class="bi bi-chevron-down text-white fs-4"></i>
                </div>
            </div>
            <div class="category-grid">
                @foreach(($categories ?? [])->take(8) as $cat)
                <a href="{{ route('buyer.productsByCategory', $cat->id ?? 1) }}" class="category-item">
                    <div class="cat-icon-box shadow-sm-custom">
                        {{ $cat->emoji ?? '🥬' }}
                    </div>
                    <span class="fs-8 fw-semibold truncate-1">{{ $cat->name ?? 'Category' }}</span>
                </a>
                @endforeach
                @if(count($categories ?? []) == 0)
                    @foreach(['Fruits', 'Veggies', 'Dairy', 'Bakery', 'Munchies', 'Cold Drinks', 'Instant', 'Cleaning'] as $dummy)
                    <a href="#" class="category-item">
                        <div class="cat-icon-box shadow-sm-custom">📦</div>
                        <span class="fs-8 fw-semibold truncate-1">{{ $dummy }}</span>
                    </a>
                    @endforeach
                @endif
            </div>
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
@auth
    <button 
        class="add-btn"
        onclick="event.stopPropagation(); addToCart({{ $prod->id }})">
        ADD
    </button>
@else
    <a 
        href="{{ route('login') }}"
        class="add-btn"
        style="text-align:center; text-decoration:none;">
        Login
    </a>
@endauth
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="munchies-banner">
                <h3>⚡ Instant Munchies (Nearby)</h3>
                <p>Quick snacks and drinks delivered in minutes!</p>
                <a href="/tenmins" class="browse-btn">Browse <i class="bi bi-arrow-right"></i></a>
            </div>
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
            @auth
            <a href="{{ route('profile.show') }}" class="nav-link-mobile">
                <i class="bi bi-person"></i>
                <span>{{ Auth::user()->name }}</span>
            </a>
            @else
            <a href="{{ route('login') }}" class="nav-link-mobile">
                <i class="bi bi-person"></i>
                <span>Login</span>
            </a>
            @endauth
        </nav>
    </div>

    <!-- DESKTOP VIEW -->
    <div class="desktop-only">
        <nav class="desktop-navbar">
            <div class="navbar-container">
                <div class="brand-section">
                    <a href="{{ route('home') }}" class="brand-logo">
                        <i class="bi bi-bag-check-fill"></i> GrabBaskets
                    </a>
                   <div class="delivery-info location-tracker" onclick="getUserLocation()">
    <div class="fw-bold fs-7">
        <i class="bi bi-geo-alt-fill"></i>
        <span id="location-text">Detect your location</span>
    </div>
    <div class="text-muted fs-8 truncate-1" id="location-subtext">
        Click to find your location
    </div>
</div>

                </div>
                <div class="search-bar-container desktop-search">
                    <input type="text" class="search-input" placeholder="Search for products, brands and more">
                    <i class="bi bi-search search-icon"></i>
                </div>
                <div class="nav-actions">
                    <button class="join-btn" onclick="window.location.href='/joinus'">
    <i class="bi bi-shop"></i> Join With Us
</button>

                    <a href="{{ route('cart.index') }}" class="cart-btn">
                        <i class="bi bi-cart3"></i> Cart
                    </a>
                    @auth
                    <div class="d-flex align-items-center gap-2">
                        <a href="{{ route('profile.show') }}" class="auth-name">{{ Auth::user()->name }}</a>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="logout-btn">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </button>
                        </form>
                    </div>
                    @else
                    <a href="{{ route('login') }}" class="auth-name">
                        <i class="bi bi-person"></i> Login
                    </a>
                    @endauth
                </div>
            </div>
        </nav>

        <div class="container main-layout">
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
            <main>
                <div class="row mb-5">
                    <div class="col-8">
                        <div class="hero-banner position-relative">
                            <div class="content">
                                <h1>GrabBaskets Ecommerce Website</h1>
                                <p>
                                A dedicated e-commerce platform for Tamil Nadu products — buy Products like Groceries,Products,Gadgets and essentials 
                                from anywhere in Tamil Nadu with ease and Get Fast delivery.
                                </p>
                               <a href="#daily"> <button class="btn btn-light rounded-pill px-4 fw-bold text-primary ">Shop Now</button></a>
                            </div>
                            <div class="down-arrow">
                                <i class="bi bi-chevron-down text-white fs-4"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="munchies-banner">
                            <h3>⚡ Instant Munchies (Nearby)</h3>
                            <p>Quick snacks and drinks delivered in minutes!</p>
                            <a href="/tenmins" class="browse-btn">Browse <i class="bi bi-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="fw-bold m-0" id="daily">Daily Staples</h3>
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

    <script>
function getUserLocation() {
    if (!navigator.geolocation) {
        alert("Geolocation is not supported by your browser");
        return;
    }

    document.getElementById("location-text").innerText = "Detecting...";
    document.getElementById("location-subtext").innerText = "Please allow location access";

    navigator.geolocation.getCurrentPosition(
        (position) => {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;

            // Show coordinates (fast & safe)
            document.getElementById("location-text").innerText = "Your Location";
            document.getElementById("location-subtext").innerText =
                `Lat: ${lat.toFixed(4)}, Lng: ${lng.toFixed(4)}`;

            // OPTIONAL: Open Google Maps
            // window.open(`https://www.google.com/maps?q=${lat},${lng}`, "_blank");
        },
        () => {
            document.getElementById("location-text").innerText = "Location denied";
            document.getElementById("location-subtext").innerText =
                "Please enable location access";
        }
    );
}
</script>

</body>
</html>
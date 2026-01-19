<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=0" />
    <title>GrabBaskets — Food Delivery</title>
    <!-- Google Fonts: Inter & Outfit -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --primary: #FF6B00;
            --primary-dark: #E65A00;
            --secondary: #282C3F;
            --text-main: #3D4152;
            --text-light: #686B78;
            --bg-gray: #F1F4F6;
            --rating-bg: #48C479;
            --white: #FFFFFF;
            --border-light: #E9E9EB;
            --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.04);
            --shadow-md: 0 4px 16px rgba(0, 0, 0, 0.08);
            --shadow-lg: 0 8px 24px rgba(0, 0, 0, 0.12);
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 20px;
            --container-padding: 1rem;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--white);
            color: var(--text-main);
            margin: 0;
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
            line-height: 1.5;
        }

        /* --- UTILITIES --- */
        .premium-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 var(--container-padding);
        }

        .section-title {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            font-size: clamp(1.1rem, 4vw, 1.5rem);
            margin-bottom: 1.5rem;
            color: var(--secondary);
            letter-spacing: -0.02em;
        }

        /* --- NAVBAR --- */
        .swiggy-nav {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            position: sticky;
            top: 0;
            z-index: 1100;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            height: 64px;
            display: flex;
            align-items: center;
        }

        .nav-content {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .left-nav {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .logo-box {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            color: var(--primary);
        }

        .logo-box i { font-size: 1.5rem; }

        .logo-text {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            font-size: 1.25rem;
            letter-spacing: -0.5px;
        }

        .location-box {
            display: none;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.85rem;
            padding-left: 1rem;
            border-left: 1px solid var(--border-light);
            cursor: pointer;
        }

        .right-nav {
            display: flex;
            align-items: center;
            gap: 1.25rem;
        }

        .nav-link-item {
            text-decoration: none;
            color: var(--text-main);
            font-weight: 500;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .nav-link-item span { display: none; }

        @media (min-width: 768px) {
            .swiggy-nav { height: 80px; }
            .location-box { display: flex; }
            .nav-link-item span { display: inline; }
            .right-nav { gap: 2.5rem; }
        }

        /* --- SEARCH --- */
        .search-section {
            padding: 1rem 0;
            background: var(--white);
            position: sticky;
            top: 64px;
            z-index: 1050;
        }

        .swiggy-search-input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 3rem;
            border: 1px solid var(--border-light);
            border-radius: var(--radius-md);
            font-size: 0.95rem;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-sm);
        }

        .swiggy-search-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 4px 15px rgba(255, 107, 0, 0.1);
        }

        .search-icon-inside {
            position: absolute;
            left: 2rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
        }

        @media (min-width: 768px) {
            .search-section { top: 80px; padding: 1.5rem 0; }
        }

        /* --- CATEGORIES --- */
        .categories-carousel {
            display: flex;
            gap: 1.25rem;
            overflow-x: auto;
            padding: 0.5rem 0 1.5rem;
            scrollbar-width: none;
            scroll-snap-type: x mandatory;
        }

        .categories-carousel::-webkit-scrollbar { display: none; }

        .cat-item {
            min-width: 75px;
            text-align: center;
            text-decoration: none;
            scroll-snap-align: start;
        }

        .cat-image-wrapper {
            width: 75px;
            height: 75px;
            border-radius: 50%;
            overflow: hidden;
            margin-bottom: 0.5rem;
            background: var(--bg-gray);
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }

        .cat-item.active .cat-image-wrapper { border-color: var(--primary); transform: scale(1.05); }

        .cat-image-wrapper img { width: 100%; height: 100%; object-fit: cover; }

        .cat-name { font-size: 0.7rem; font-weight: 600; color: var(--text-main); }

        @media (min-width: 768px) {
            .cat-item { min-width: 110px; }
            .cat-image-wrapper { width: 110px; height: 110px; }
            .cat-name { font-size: 0.85rem; }
            .categories-carousel { gap: 2rem; }
        }

        /* --- FILTER BAR --- */
        .filter-bar {
            display: flex;
            gap: 0.75rem;
            padding: 1rem 0;
            overflow-x: auto;
            white-space: nowrap;
            scrollbar-width: none;
            position: sticky;
            top: 128px;
            background: var(--white);
            z-index: 1040;
            border-bottom: 1px solid var(--border-light);
        }

        .filter-bar::-webkit-scrollbar { display: none; }

        .filter-pill {
            padding: 0.4rem 0.9rem;
            border: 1px solid var(--border-light);
            border-radius: 2rem;
            font-size: 0.8rem;
            font-weight: 500;
            color: var(--text-main);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.4rem;
            background: var(--white);
            cursor: pointer;
        }

        .filter-pill.active { background: var(--bg-gray); border-color: var(--secondary); font-weight: 600; }

        .filter-pill select { border: none; background: transparent; font-weight: inherit; font-size: inherit; outline: none; }

        @media (min-width: 768px) {
            .filter-bar { top: 160px; gap: 1rem; }
            .filter-pill { padding: 0.6rem 1.25rem; font-size: 0.9rem; }
        }

        /* --- FOOD CARDS --- */
        .items-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            padding-bottom: 2rem;
        }

        @media (min-width: 992px) {
            .items-grid { grid-template-columns: repeat(3, 1fr); gap: 2rem; }
        }

        @media (min-width: 1200px) {
            .items-grid { grid-template-columns: repeat(4, 1fr); }
        }

        .premium-food-card {
            text-decoration: none;
            color: inherit;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .premium-food-card:hover { transform: translateY(-4px); }

        .card-img-container {
            position: relative;
            aspect-ratio: 4/3;
            border-radius: var(--radius-md);
            overflow: hidden;
            margin-bottom: 0.75rem;
            box-shadow: var(--shadow-sm);
        }

        .card-img-container img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease; }
        .premium-food-card:hover .card-img-container img { transform: scale(1.05); }

        .img-overlay-gradient {
            position: absolute;
            bottom: 0; left: 0; right: 0; height: 50%;
            background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);
        }

        .discount-tag {
            position: absolute;
            bottom: 0.5rem; left: 0.5rem;
            color: var(--white);
            font-weight: 800;
            font-size: 0.7rem;
            text-transform: uppercase;
        }

        .veg-nonveg-indicator {
            position: absolute;
            top: 0.5rem; right: 0.5rem;
            background: rgba(255,255,255,0.9);
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 0.55rem;
            font-weight: 700;
            display: flex; align-items: center; gap: 3px;
        }

        .veg-indicator { color: var(--rating-bg); border: 1px solid var(--rating-bg); }
        .nonveg-indicator { color: #D12939; border: 1px solid #D12939; }

        .food-info { padding: 0 0.25rem; }

        .food-name-h {
            font-weight: 700;
            font-size: 0.9rem;
            margin-bottom: 0.2rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            color: var(--secondary);
        }

        .meta-row {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.75rem;
            margin-bottom: 0.25rem;
            font-weight: 600;
        }

        .rating-box {
            background: var(--rating-bg);
            color: var(--white);
            padding: 0px 4px;
            border-radius: 4px;
            display: flex; align-items: center; gap: 2px;
        }

        .dot-sep { width: 3px; height: 3px; background: var(--text-light); border-radius: 50%; opacity: 0.5; }

        .food-details-text {
            color: var(--text-light);
            font-size: 0.75rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-bottom: 0.25rem;
        }

        .price-tag { font-weight: 600; font-size: 0.8rem; color: var(--text-main); }

        @media (min-width: 768px) {
            .card-img-container { border-radius: var(--radius-lg); }
            .food-name-h { font-size: 1.1rem; }
            .meta-row { font-size: 0.9rem; }
            .discount-tag { font-size: 0.9rem; left: 0.75rem; bottom: 0.75rem; }
            .price-tag { font-size: 1rem; }
            .food-details-text { font-size: 0.85rem; }
        }

        /* --- BOTTOM CATEGORIES GRID --- */
        .categories-bottom-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            margin-bottom: 3rem;
        }

        @media (min-width: 768px) {
            .categories-bottom-grid { grid-template-columns: repeat(3, 1fr); gap: 1.5rem; }
        }

        @media (min-width: 1024px) {
            .categories-bottom-grid { grid-template-columns: repeat(4, 1fr); }
        }

        .category-card {
            text-decoration: none;
            border-radius: var(--radius-md);
            overflow: hidden;
            background: var(--white);
            box-shadow: var(--shadow-md);
            transition: all 0.3s ease;
            height: 180px;
            position: relative;
        }

        .category-card:hover { transform: scale(1.02); box-shadow: var(--shadow-lg); }

        .category-card-image { width: 100%; height: 100%; }
        .category-card-image img { width: 100%; height: 100%; object-fit: cover; }

        .category-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
        }

        .category-card-content {
            position: absolute;
            bottom: 0; left: 0; right: 0;
            padding: 1rem;
            color: var(--white);
        }

        .category-card-title { font-family: 'Outfit', sans-serif; font-weight: 700; font-size: 1rem; margin: 0; }
        .category-card-subtitle { font-size: 0.7rem; opacity: 0.8; margin: 0; }

        @media (min-width: 768px) {
            .category-card { height: 260px; border-radius: var(--radius-lg); }
            .category-card-title { font-size: 1.25rem; }
            .category-card-subtitle { font-size: 0.85rem; }
        }

        /* --- MOBILE BOTTOM NAV --- */
        .mobile-bottom-nav {
            position: fixed;
            bottom: 0; left: 0; right: 0;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            display: flex;
            justify-content: space-around;
            padding: 0.75rem 0.5rem;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.05);
            z-index: 1200;
            border-top: 1px solid var(--border-light);
        }

        .bottom-nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-decoration: none;
            color: var(--text-light);
            gap: 2px;
        }

        .bottom-nav-item.active { color: var(--primary); }
        .bottom-nav-item i { font-size: 1.2rem; }
        .bottom-nav-item span { font-size: 0.6rem; font-weight: 600; }

        @media (min-width: 768px) {
            .mobile-bottom-nav { display: none; }
        }

        .footer-spacer { height: 80px; }
    </style>
</head>

<body>

    <!-- STICKY NAVBAR -->
    <nav class="swiggy-nav">
        <div class="premium-container nav-content">
            <div class="left-nav">
                <a href="{{ url('/') }}" class="logo-box">
                    <i class="fa-solid fa-basket-shopping"></i>
                    <span class="logo-text">GrabBaskets</span>
                </a>
                <div class="location-box">
                    <span class="location-bold">Other</span>
                    <span>Bengaluru, Karnataka, India</span>
                    <i class="fa-solid fa-chevron-down ms-2 text-primary"></i>
                </div>
            </div>

            <div class="right-nav">
                <a href="{{ route('customer.food.cart') }}" class="nav-link-item position-relative">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <span>Cart</span>
                    @if(session('cart_count', 0) > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.5rem; padding: 0.25em 0.5em;">
                            {{ session('cart_count') }}
                        </span>
                    @endif
                </a>

                @auth
                    <div class="dropdown">
                        <a href="#" class="nav-link-item dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fa-solid fa-user"></i>
                            <span>{{ Auth::user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 p-2" style="border-radius: var(--radius-md);">
                            <li><a class="dropdown-item rounded py-2" href="{{ url('/profile') }}"><i class="fa-solid fa-user-circle me-2"></i> Profile</a></li>
                            <li><a class="dropdown-item rounded py-2" href="/food/my-orders"><i class="fa-solid fa-bag-shopping me-2"></i> Orders</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger rounded py-2">
                                        <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="nav-link-item">
                        <i class="fa-solid fa-sign-in-alt"></i>
                        <span>Login</span>
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- STICKY SEARCH BAR -->
    <div class="search-section">
        <div class="premium-container">
            <div class="position-relative">
                <form method="GET" action="{{ route('customer.food.index') }}">
                    <i class="fa-solid fa-magnifying-glass search-icon-inside"></i>
                    <input name="search" class="swiggy-search-input" placeholder="Search for restaurant, cuisine or a dish" value="{{ request('search') }}" autocomplete="off" />
                    <input type="hidden" name="category" value="{{ request('category') }}">
                    <input type="hidden" name="veg" value="{{ request('veg') }}">
                    <input type="hidden" name="sort" value="{{ request('sort') }}">
                </form>
            </div>
        </div>
    </div>

    <main class="premium-container">
        <!-- WHAT'S ON YOUR MIND (CATEGORIES) -->
        <h2 class="section-title">What's on your mind?</h2>
        <div class="categories-carousel">
            @foreach($foodCategories as $cat)
                @php
                    $categoryName = strtolower($cat['id']);
                    $isActive = request('category') === $cat['id'];
                    $url = route('customer.food.index', ['category' => $cat['id'], 'veg' => request('veg'), 'sort' => request('sort')]);

                    $categoryImages = [
                        'dessert' => asset('images/categories/dessert.jpeg'),
                        'beverage' => asset('images/categories/beverage.jpeg'),
                        'appetizer' => asset('images/categories/appetizer.jpeg'),
                        'main_course' => asset('images/categories/main_course.jpeg'),
                        'snack' => asset('images/categories/snack.jpeg'),
                        'salad' => asset('images/categories/salad.jpeg'),
                        'soup' => asset('images/categories/soup.jpeg'),
                        'staters' => asset('images/categories/staters.jpeg'),
                        'rice' => asset('images/categories/rice.jpeg'),
                        'seafood' => asset('images/categories/seafood.jpeg'),
                        'chicken' => asset('images/categories/chicken.jpeg'),
                        'mutton' => asset('images/categories/mutton.jpeg'),
                        'burger' => asset('images/categories/burger.jpeg'),
                        'pizza' => asset('images/categories/pizza.jpeg'),
                        'briyani' => asset('images/categories/briyani.jpeg'),
                    ];
                    $image = $categoryImages[$categoryName] ?? asset('images/categories/default.png');
                @endphp

                <a href="{{ $url }}" class="cat-item {{ $isActive ? 'active' : '' }}">
                    <div class="cat-image-wrapper">
                        <img src="{{ $image }}" alt="{{ $cat['name'] }}" />
                    </div>
                    <div class="cat-name">{{ ucwords($cat['name']) }}</div>
                </a>
            @endforeach
        </div>

        <hr class="my-4 opacity-10">

        <!-- FILTER BAR -->
        <div class="filter-bar">
            <a href="{{ route('customer.food.index') }}" class="filter-pill {{ !request('category') && !request('search') ? 'active' : '' }}">
                <i class="fa-solid fa-list"></i> All
            </a>

            <div class="filter-pill {{ request('veg') !== null ? 'active' : '' }}">
                <form method="GET" style="display:inline-flex; align-items:center; gap:5px;">
                    <i class="fa-solid fa-leaf text-success"></i>
                    <input type="hidden" name="search" value="{{ request('search') }}" />
                    <input type="hidden" name="category" value="{{ request('category') }}" />
                    <input type="hidden" name="sort" value="{{ request('sort') }}" />
                    <select name="veg" onchange="this.form.submit()">
                        <option value="">Dietary</option>
                        <option value="1" {{ request('veg') === '1' ? 'selected' : '' }}>Veg Only</option>
                        <option value="0" {{ request('veg') === '0' ? 'selected' : '' }}>Non-Veg</option>
                    </select>
                </form>
            </div>

            <div class="filter-pill {{ request('sort') ? 'active' : '' }}">
                <form method="GET" style="display:inline-flex; align-items:center; gap:5px;">
                    <i class="fa-solid fa-sort"></i>
                    <input type="hidden" name="search" value="{{ request('search') }}" />
                    <input type="hidden" name="category" value="{{ request('category') }}" />
                    <input type="hidden" name="veg" value="{{ request('veg') }}" />
                    <select name="sort" onchange="this.form.submit()">
                        <option value="">Sort By</option>
                        <option value="costLow" {{ request('sort') === 'costLow' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="costHigh" {{ request('sort') === 'costHigh' ? 'selected' : '' }}>Price: High to Low</option>
                        <option value="ratingHigh" {{ request('sort') === 'ratingHigh' ? 'selected' : '' }}>Ratings: High to Low</option>
                    </select>
                </form>
            </div>
            
            @if(request('category'))
                <div class="filter-pill active">
                    <i class="fa-solid fa-tag"></i> {{ ucwords(request('category')) }}
                </div>
            @endif
        </div>

        <!-- ITEMS SECTION -->
        <h2 class="section-title mt-4">
            @if(request('search'))
                Results for "{{ request('search') }}"
            @elseif(request('category'))
                {{ ucwords(str_replace('_', ' ', request('category'))) }} Specialists
            @else
                Top Restaurants for you
            @endif
        </h2>

        <div class="items-grid">
            @forelse($foods as $food)
                <a href="{{ route('customer.food.details', $food->id) }}" class="premium-food-card">
                    <div class="card-img-container">
                        <img src="{{ $food->first_image_url ?: 'https://via.placeholder.com/480x300?text=' . urlencode($food->name) }}" 
                             alt="{{ $food->name }}"
                             onerror="this.onerror=null;this.src='https://via.placeholder.com/480x300?text=No+Image';">
                        
                        <div class="img-overlay-gradient"></div>
                        
                        @php $discount = rand(10, 60); @endphp
                        <div class="discount-tag">{{ $discount }}% OFF UPTO ₹120</div>
                        
                        <div class="veg-nonveg-indicator {{ $food->food_type === 'veg' ? 'veg-indicator' : 'nonveg-indicator' }}">
                            <i class="fa-solid fa-circle" style="font-size: 0.5rem;"></i> 
                            {{ strtoupper($food->food_type) }}
                        </div>
                    </div>

                    <div class="food-info">
                        <div class="food-name-h">{{ $food->name }}</div>
                        <div class="meta-row">
                            <div class="rating-box">
                                <i class="fa-solid fa-star" style="font-size: 0.7rem;"></i>
                                {{ number_format($food->rating ?? 4.0, 1) }}
                            </div>
                            <div class="dot-sep"></div>
                            <div class="delivery-time">{{ rand(20, 45) }} mins</div>
                        </div>
                        <div class="food-details-text">
                            {{ $food->hotelOwner ? $food->hotelOwner->name : 'Gourmet Kitchen' }}
                        </div>
                        <div class="price-tag">
                            ₹{{ number_format($food->discounted_price ?? $food->price, 0) }} for one
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-12 text-center py-5">
                    <img src="https://res.cloudinary.com/swiggy/image/upload/fl_lossy,f_auto,q_auto/2x_empty_cart_ybi7ss" alt="Empty" style="width: 150px; opacity: 0.5;">
                    <h5 class="mt-4 text-muted">No items found matching your criteria</h5>
                    <a href="{{ route('customer.food.index') }}" class="btn btn-primary mt-2" style="background: var(--primary); border: none;">Explore All Food</a>
                </div>
            @endforelse
        </div>

        <!-- Food Categories Section at Bottom -->
        <hr class="my-5 opacity-10">
        
        <h2 class="section-title">Browse All Food Categories</h2>
        
        <div class="categories-bottom-grid">
            @foreach($foodCategories as $cat)
                @php
                    $categoryName = strtolower($cat['id']);
                    $isActive = request('category') === $cat['id'];
                    $url = route('customer.food.index', ['category' => $cat['id']]);

                    $categoryImages = [
                        'dessert' => asset('images/categories/dessert.jpeg'),
                        'beverage' => asset('images/categories/beverage.jpeg'),
                        'appetizer' => asset('images/categories/appetizer.jpeg'),
                        'main_course' => asset('images/categories/main_course.jpeg'),
                        'snack' => asset('images/categories/snack.jpeg'),
                        'salad' => asset('images/categories/salad.jpeg'),
                        'soup' => asset('images/categories/soup.jpeg'),
                        'staters' => asset('images/categories/staters.jpeg'),
                        'rice' => asset('images/categories/rice.jpeg'),
                        'seafood' => asset('images/categories/seafood.jpeg'),
                        'chicken' => asset('images/categories/chicken.jpeg'),
                        'mutton' => asset('images/categories/mutton.jpeg'),
                        'burger' => asset('images/categories/burger.jpeg'),
                        'pizza' => asset('images/categories/pizza.jpeg'),
                        'briyani' => asset('images/categories/briyani.jpeg'),
                    ];
                    $image = $categoryImages[$categoryName] ?? asset('images/categories/default.png');
                @endphp

                <a href="{{ $url }}" class="category-card {{ $isActive ? 'active' : '' }}">
                    <div class="category-card-image">
                        <img src="{{ $image }}" alt="{{ $cat['name'] }}" />
                        <div class="category-overlay"></div>
                    </div>
                    <div class="category-card-content">
                        <h3 class="category-card-title">{{ ucwords($cat['name']) }}</h3>
                        <p class="category-card-subtitle">Explore {{ strtolower($cat['name']) }}</p>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="footer-spacer"></div>
    </main>

    <!-- MOBILE BOTTOM NAVIGATION -->
    <div class="mobile-bottom-nav">
        <a href="{{ url('/') }}" class="bottom-nav-item active">
            <i class="fa-solid fa-house"></i>
            <span>Home</span>
        </a>
        <a href="{{ route('customer.food.index') }}" class="bottom-nav-item">
            <i class="fa-solid fa-magnifying-glass"></i>
            <span>Search</span>
        </a>
        <a href="/food/my-orders" class="bottom-nav-item">
            <i class="fa-solid fa-bag-shopping"></i>
            <span>Orders</span>
        </a>
        <a href="{{ url('/profile') }}" class="bottom-nav-item">
            <i class="fa-solid fa-user"></i>
            <span>Account</span>
        </a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
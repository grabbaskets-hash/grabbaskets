<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>GrabBasket — 10-Minute Products</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Gilroy:wght@300;400;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        /* ZEPTO-INSPIRED DESIGN SYSTEM */
        :root {
            --primary: #0c0c0c;
            --brand: #2f7a2f;
            --brand-light: #e6f7e6;
            --accent: #ff3269;
            --surface: #f3f4f6;
            --white: #ffffff;
            --border: #e5e7eb;
            --text-sec: #6b7280;
            --radius: 16px;
            --shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #fcfcfc;
            color: var(--primary);
            overflow-x: hidden;
            padding-bottom: 80px;
            /* Space for bottom nav on mobile */
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        button {
            font-family: inherit;
        }

        /* ========== HEADER ========== */
        header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            position: sticky;
            top: 0;
            z-index: 100;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            padding: 12px 0;
        }

        .nav-container {
            max-width: 1300px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            align-items: center;
            gap: 24px;
        }

        .logo {
            font-size: 24px;
            font-weight: 800;
            letter-spacing: -0.5px;
            background: linear-gradient(135deg, #2f7a2f, #16a34a);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            flex-shrink: 0;
        }

        .search-bar {
            flex: 1;
            max-width: 600px;
            position: relative;
            margin-left: 20px;
        }

        .search-input {
            width: 100%;
            background: #f3f4f6;
            border: 1px solid transparent;
            padding: 14px 20px 14px 48px;
            border-radius: 12px;
            font-size: 15px;
            transition: all 0.2s;
            color: #1f2937;
        }

        .search-input:focus {
            background: #fff;
            border-color: var(--brand);
            box-shadow: 0 4px 12px rgba(47, 122, 47, 0.1);
            outline: none;
        }

        .search-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            pointer-events: none;
        }

        .nav-right {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-left: auto;
        }

        .nav-btn {
            font-weight: 600;
            font-size: 14px;
            color: #374151;
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 8px;
            transition: background 0.2s;
        }

        .nav-btn:hover {
            background: #f3f4f6;
        }

        .cart-btn {
            background: var(--brand);
            color: white !important;
            padding: 10px 20px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(47, 122, 47, 0.25);
            transition: transform 0.2s;
        }

        .cart-btn:active {
            transform: scale(0.96);
        }

        .logout-btn {
            background: linear-gradient(135deg, #ff416c, #ff4b2b);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .logout-btn:hover {
            transform: translateY(-2px);
        }

        /* ========== LAYOUT ========== */
        .main-layout {
            display: grid;
            grid-template-columns: 260px 1fr;
            max-width: 1300px;
            margin: 30px auto;
            padding: 0 20px;
            gap: 32px;
            align-items: start;
        }

        /* ========== SIDEBAR (Categories) ========== */
        .sidebar {
            position: sticky;
            top: 100px;
            max-height: calc(100vh - 120px);
            overflow-y: auto;
            padding-right: 10px;
        }

        .sidebar::-webkit-scrollbar {
            width: 5px;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: #e5e7eb;
            border-radius: 10px;
        }

        .cat-label {
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            color: #9ca3af;
            letter-spacing: 0.5px;
            margin-bottom: 16px;
            padding-left: 12px;
        }

        .cat-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 12px;
            margin-bottom: 4px;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.2s;
            font-weight: 500;
            border: 1px solid transparent;
        }

        .cat-item:hover {
            background: #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
            transform: translateX(2px);
        }

        .cat-item.active {
            background: #e9f5e9;
            border-color: rgba(47, 122, 47, 0.1);
            color: var(--brand);
            font-weight: 700;
        }

        .cat-img {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            object-fit: cover;
            background: #e5e7eb;
        }

        /* ========== CONTENT AREA ========== */
        .content-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .content-title {
            font-size: 28px;
            font-weight: 800;
            color: #111;
        }

        .subcategories {
            display: flex;
            gap: 10px;
            overflow-x: auto;
            padding-bottom: 12px;
            margin-bottom: 20px;
            scrollbar-width: none;
        }

        .subcategories::-webkit-scrollbar {
            display: none;
        }

        .sub-pill {
            padding: 8px 16px;
            border-radius: 99px;
            background: #fff;
            border: 1px solid #e5e7eb;
            font-size: 14px;
            font-weight: 600;
            color: #4b5563;
            white-space: nowrap;
            cursor: pointer;
            transition: all 0.2s;
        }

        .sub-pill:hover {
            border-color: #d1d5db;
            background: #f9fafb;
        }

        .sub-pill.active {
            background: #111;
            color: #fff;
            border-color: #111;
        }

        /* ========== GRID ========== */
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
        }

        .product-card {
            background: #fff;
            border-radius: 16px;
            padding: 16px;
            border: 1px solid transparent;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            position: relative;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
        }

        .product-card:hover {
            box-shadow: 0 12px 30px -10px rgba(0, 0, 0, 0.12);
            transform: translateY(-4px);
            border-color: #f3f4f6;
        }

        .p-img-box {
            width: 100%;
            aspect-ratio: 1;
            margin-bottom: 14px;
            position: relative;
            border-radius: 12px;
            overflow: hidden;
            background: #f8f8f8;
        }

        .p-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .product-card:hover .p-img {
            transform: scale(1.05);
        }

        .discount-badge {
            position: absolute;
            top: 8px;
            left: 8px;
            background: #ffecf0;
            color: #d1004b;
            font-size: 11px;
            font-weight: 800;
            padding: 4px 8px;
            border-radius: 6px;
        }

        .p-title {
            font-size: 15px;
            font-weight: 600;
            line-height: 1.4;
            color: #1f2937;
            margin-bottom: 4px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            min-height: 42px;
        }

        .p-weight {
            font-size: 13px;
            color: #9ca3af;
            margin-bottom: 12px;
        }

        .p-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: auto;
        }

        .p-price {
            display: flex;
            flex-direction: column;
        }

        .current-price {
            font-size: 16px;
            font-weight: 700;
            color: #111;
        }

        .old-price {
            font-size: 12px;
            text-decoration: line-through;
            color: #9ca3af;
            margin-top: 2px;
        }

        .add-btn-sm {
            background: #fff;
            color: var(--brand);
            border: 1px solid var(--brand);
            padding: 8px 24px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .add-btn-sm:hover {
            background: #e6f7e6;
        }

        .add-btn-sm:active {
            background: var(--brand);
            color: #fff;
            transform: scale(0.95);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 0;
            color: #6b7280;
            grid-column: 1 / -1;
        }

        /* ========== MOBILE BOTTOM NAV ========== */
        .bottom-nav {
            display: none;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            border-top: 1px solid #e5e7eb;
            padding: 8px 0 calc(8px + env(safe-area-inset-bottom));
            z-index: 1000;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.05);
        }

        .bottom-nav-content {
            display: flex;
            justify-content: space-around;
            align-items: center;
            max-width: 600px;
            margin: 0 auto;
        }

        .nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            padding: 8px 16px;
            color: #6b7280;
            font-size: 11px;
            font-weight: 600;
            transition: all 0.2s;
            border-radius: 8px;
        }

        .nav-item.active {
            color: var(--brand);
        }

        .nav-item i {
            font-size: 20px;
        }

        /* ========== MOBILE OVERRIDES ========== */
        @media (max-width: 900px) {
            body {
                padding-bottom: 70px;
            }

            .main-layout {
                grid-template-columns: 1fr;
                padding: 0 16px;
                margin: 20px auto;
                gap: 20px;
            }

            .sidebar {
                display: none;
            }

            /* Mobile Categories - Horizontal Scroll */
            .mobile-cats {
                display: flex;
                gap: 12px;
                overflow-x: auto;
                padding: 12px 16px;
                background: #fff;
                margin: 0 -16px 20px -16px;
                scrollbar-width: none;
            }

            .mobile-cats::-webkit-scrollbar {
                display: none;
            }

            .m-cat-item {
                display: flex;
                flex-direction: column;
                align-items: center;
                min-width: 72px;
                flex-shrink: 0;
                gap: 6px;
                padding: 8px;
                border-radius: 12px;
                transition: background 0.2s;
            }

            .m-cat-item.active {
                background: #e9f5e9;
            }

            .m-cat-img {
                width: 56px;
                height: 56px;
                border-radius: 50%;
                object-fit: cover;
                background: #f3f4f6;
                border: 2px solid transparent;
            }

            .m-cat-item.active .m-cat-img {
                border-color: var(--brand);
            }

            .m-cat-name {
                font-size: 11px;
                text-align: center;
                font-weight: 600;
                color: #4b5563;
                line-height: 1.2;
            }

            .nav-container {
                padding: 0 16px;
                gap: 12px;
            }

            .search-bar {
                display: none;
            }

            .logo {
                font-size: 20px;
            }

            /* Mobile Search */
            .mobile-search {
                display: block;
                padding: 10px 16px;
                background: #fff;
                margin: 0 -16px 16px -16px;
            }

            .mobile-search .search-input {
                width: 100%;
                padding: 12px 12px 12px 42px;
                border: 1px solid #e5e7eb;
                border-radius: 10px;
                background: #f9fafb;
            }

            .product-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
            }

            .product-card {
                padding: 12px;
                border-radius: 12px;
            }

            .add-btn-sm {
                padding: 6px 14px;
                font-size: 12px;
            }

            .content-title {
                font-size: 22px;
            }

            .bottom-nav {
                display: block;
            }

            .nav-right .cart-btn {
                display: none;
                /* Hide desktop cart, show in bottom nav */
            }
        }

        @media (min-width: 901px) {

            .mobile-cats,
            .mobile-search,
            .bottom-nav {
                display: none;
            }
        }
    </style>
</head>

<body>

    <!-- Header -->
    <header>
        <div class="nav-container">
            <a href="/" class="logo">GB 10Min</a>

            <div class="search-bar">
                <i class="fa-solid fa-magnifying-glass search-icon"></i>
                <input id="globalSearch" class="search-input" placeholder="Search for 'milk', 'chips', 'soap'..." />
            </div>

            <div class="nav-right">
                @auth
                    <span style="font-size:14px;font-weight:600;">Hi, {{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}" style="display:inline; margin:0;">
                        @csrf
                        <button type="submit" class="logout-btn" title="Logout">
                            <i class="fa-solid fa-right-from-bracket"></i> Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="nav-btn">
                        <i class="fa-regular fa-user"></i>&nbsp;Login
                    </a>
                @endauth

                <a href="{{ route('tenmin.cart.view') }}" class="nav-btn cart-btn">
                    <i class="fa-solid fa-cart-shopping"></i>&nbsp;
                    <span
                        id="cartCountBadge">{{ \App\Models\TenMinGroceryCartItem::where('user_id', auth()->id())->sum('quantity') }}</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Mobile Search -->
    <div class="mobile-search">
        <div style="position:relative;">
            <i class="fa-solid fa-magnifying-glass search-icon"></i>
            <input class="search-input" placeholder="Search..." id="mobileSearchInput">
        </div>
    </div>

    <!-- Mobile Categories -->
    <div class="mobile-cats">
        @foreach($categories as $cat)
            @php
                $keyword = urlencode($cat->name);
                $imgUrl = "https://loremflickr.com/100/100/{$keyword},grocery";
                $isActive = isset($activeCategory) && $activeCategory->id == $cat->id;
            @endphp
            <div class="m-cat-item {{ $isActive ? 'active' : '' }}"
                onclick="window.location.href='{{ route('ten.min.products', ['category' => $cat->id]) }}'">
                <img src="{{ $imgUrl }}" class="m-cat-img" alt="{{ $cat->name }}">
                <span class="m-cat-name">{{ $cat->name }}</span>
            </div>
        @endforeach
    </div>

    <!-- Main Layout -->
    <div class="main-layout">

        <!-- Desktop Sidebar -->
        <aside class="sidebar">
            <div class="cat-label">Shop by Category</div>

            @foreach($categories as $cat)
                @php
                    $isActive = isset($activeCategory) && $activeCategory->id == $cat->id;
                    $keyword = urlencode($cat->name);
                    $imgUrl = "https://loremflickr.com/80/80/{$keyword},food?lock={$cat->id}";
                @endphp
                <div class="cat-item {{ $isActive ? 'active' : '' }}"
                    onclick="window.location.href='{{ route('ten.min.products', ['category' => $cat->id]) }}'">
                    <img src="{{ $imgUrl }}" class="cat-img" alt="">
                    <span>{{ $cat->name }}</span>
                    @if($isActive) <i class="fa-solid fa-chevron-right" style="margin-left:auto;font-size:12px;"></i> @endif
                </div>
            @endforeach
        </aside>

        <!-- Content -->
        <main>
            @if(isset($activeCategory))
                <div class="content-header">
                    <div>
                        <h1 class="content-title">{{ $activeCategory->name }}</h1>
                        <div style="color:#6b7280; margin-top:4px;">{{ $activeCategory->tenMinProducts->count() }} items
                            available</div>
                    </div>
                </div>

                <!-- Subcategories -->
                <div class="subcategories" id="subCats">
                    <div class="sub-pill active" data-sub="All">All</div>
                    @foreach($activeCategory->filteredSubcategories as $sub)
                        <div class="sub-pill" data-sub="{{ $sub->name }}">{{ $sub->name }}</div>
                    @endforeach
                </div>

                <!-- Products -->
                <div class="product-grid" id="productGrid">
                    @foreach($activeCategory->tenMinProducts as $product)
                        <div class="product-card" data-subcat="{{ $product->subcategory?->name ?? 'Other' }}"
                            onclick="window.location.href='{{ route('product.details', $product->id) }}'">

                            @if($product->discount > 0)
                                <div class="discount-badge">{{ $product->discount }}% OFF</div>
                            @endif

                            <div class="p-img-box">
                                <img src="{{ $product->image ? asset('product_images/' . $product->image) : 'https://via.placeholder.com/300' }}"
                                    class="p-img" alt="{{ $product->name }}">
                            </div>

                            <div>
                                <div class="p-title">{{ $product->name }}</div>
                                <div class="p-weight">{{ $product->subcategory?->name ?? 'Standard' }}</div>
                            </div>

                            <div class="p-footer">
                                <div class="p-price">
                                    <span class="current-price">₹{{ $product->price }}</span>
                                    @if($product->discount > 0)
                                        <span
                                            class="old-price">₹{{ $product->price + ($product->price * $product->discount / 100) }}</span>
                                    @endif
                                </div>
                                <button class="add-btn-sm"
                                    onclick="event.stopPropagation(); addToCart({{ $product->id }}, this)">
                                    ADD
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div id="noResults" class="empty-state" style="display:none;">
                    <i class="fa-regular fa-face-frown" style="font-size:48px;margin-bottom:16px;"></i>
                    <h3>No products found</h3>
                    <p>Try selecting a different subcategory.</p>
                </div>

            @else
                <div class="empty-state">
                    <h3>Please select a category to start shopping</h3>
                </div>
            @endif
        </main>

    </div>

    <!-- Mobile Bottom Navigation -->
    <nav class="bottom-nav">
        <div class="bottom-nav-content">
            <a href="{{ route('home') }}" class="nav-item">
                <i class="fa-solid fa-house"></i>
                <span>Home</span>
            </a>
            <a href="{{ route('ten.min.products') }}" class="nav-item active">
                <i class="fa-solid fa-bolt"></i>
                <span>10 Min</span>
            </a>
            <a href="{{ route('tenmin.cart.view') }}" class="nav-item">
                <i class="fa-solid fa-cart-shopping"></i>
                <span>Cart</span>
            </a>
            @auth
                <a href="{{ route('profile.show') }}" class="nav-item">
                    <i class="fa-solid fa-user"></i>
                    <span>Profile</span>
                </a>
            @else
                <a href="{{ route('login') }}" class="nav-item">
                    <i class="fa-solid fa-user"></i>
                    <span>Login</span>
                </a>
            @endauth
        </div>
    </nav>

    <script>
        // ========== SUBCATEGORY FILTERING ==========
        const subPills = document.querySelectorAll('.sub-pill');
        const cards = document.querySelectorAll('.product-card');
        const noResults = document.getElementById('noResults');

        subPills.forEach(pill => {
            pill.addEventListener('click', () => {
                subPills.forEach(p => p.classList.remove('active'));
                pill.classList.add('active');

                const selectedSub = pill.dataset.sub;
                let visibleCount = 0;

                cards.forEach(card => {
                    if (selectedSub === 'All' || card.dataset.subcat === selectedSub) {
                        card.style.display = 'flex';
                        visibleCount++;
                    } else {
                        card.style.display = 'none';
                    }
                });

                noResults.style.display = visibleCount === 0 ? 'block' : 'none';
            });
        });

        // ========== SEARCH ==========
        const searchInputs = [document.getElementById('globalSearch'), document.getElementById('mobileSearchInput')];

        searchInputs.forEach(input => {
            if (!input) return;
            input.addEventListener('input', (e) => {
                const term = e.target.value.toLowerCase();
                let visibleCount = 0;

                cards.forEach(card => {
                    const title = card.querySelector('.p-title').innerText.toLowerCase();
                    const sub = card.querySelector('.p-weight').innerText.toLowerCase();

                    if (title.includes(term) || sub.includes(term)) {
                        card.style.display = 'flex';
                        visibleCount++;
                    } else {
                        card.style.display = 'none';
                    }
                });

                noResults.style.display = visibleCount === 0 ? 'block' : 'none';
            });
        });

        // ========== ADD TO CART ==========
        async function addToCart(productId, btn) {
            if (btn.disabled) return;

            const originalText = btn.innerText;
            btn.innerText = "•";
            btn.disabled = true;
            btn.style.width = "60px";

            try {
                const res = await fetch("{{ route('tenmin.cart.add') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ product_id: productId, quantity: 1 })
                });

                const data = await res.json();

                if (data.success) {
                    const badge = document.getElementById('cartCountBadge');
                    if (badge) badge.innerText = data.cart_count;

                    btn.style.background = "#2f7a2f";
                    btn.style.color = "#fff";
                    btn.innerText = "✓";

                    setTimeout(() => {
                        btn.innerText = "ADD";
                        btn.style.background = "";
                        btn.style.color = "";
                        btn.disabled = false;
                        btn.style.width = "";
                    }, 1500);
                } else {
                    throw new Error(data.error || data.message || 'Error');
                }
            } catch (err) {
                alert(err.message);
                btn.innerText = originalText;
                btn.disabled = false;
                btn.style.width = "";
            }
        }
    </script>

</body>

</html>
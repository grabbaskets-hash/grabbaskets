<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no" />
  <title>GrabBaskets — 10 Minute Delivery</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    /* 
    ZEPTO-STYLE DESIGN SYSTEM 
    Primary: #36007c (Deep Violet)
    Secondary: #ff3269 (Vibrant Pink)
    Accent: #fec500 (Yellow/Gold)
    Background: #f5f7fd
*/
    :root {
      --primary: #36007c;
      --primary-dark: #2a0060;
      --secondary: #ff3269;
      --accent: #fec500;
      --text-main: #1d1d1d;
      --text-muted: #666;
      --bg-body: #f5f7fd;
      --bg-card: #ffffff;
      --radius-lg: 16px;
      --radius-md: 12px;
      --radius-sm: 8px;
      --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.05);
      --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    * {
      box-sizing: border-box;
      -webkit-tap-highlight-color: transparent;
    }

    body {
      margin: 0;
      padding: 0;
      font-family: 'Inter', sans-serif;
      background: var(--bg-body);
      color: var(--text-main);
      overflow-x: hidden;
      padding-bottom: 80px;
      /* Space for mobile floating cart */
    }

    /* --- HEADER --- */
    header {
      background: #fff;
      position: sticky;
      top: 0;
      z-index: 999;
      box-shadow: 0 1px 0 rgba(0, 0, 0, 0.05);
      padding: 12px 0;
    }

    .header-container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 16px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 20px;
    }

    .nav-left {
      display: flex;
      align-items: center;
      gap: 16px;
      flex-shrink: 0;
    }

    .brand-area {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .brand-logo {
      width: 40px;
      height: 40px;
      background: var(--primary);
      color: #fff;
      border-radius: var(--radius-md);
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 800;
      font-size: 18px;
    }

    .brand-text h1 {
      margin: 0;
      font-size: 18px;
      font-weight: 800;
      line-height: 1.1;
      color: var(--primary);
    }

    .brand-text span {
      font-size: 11px;
      font-weight: 600;
      color: var(--secondary);
      display: block;
    }

    .location-pill {
      background: #f1f4f9;
      padding: 8px 16px;
      border-radius: 20px;
      font-size: 13px;
      font-weight: 600;
      display: flex;
      align-items: center;
      gap: 6px;
      max-width: 200px;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      cursor: pointer;
      transition: background .2s;
      display: none;
      /* Hidden on small mobile by default */
    }

    .location-pill:hover {
      background: #eef2ff;
      color: var(--primary);
    }

    /* SEARCH BAR */
    .search-wrapper {
      position: relative;
      width: 100%;
      max-width: 600px;
      margin: 0 auto;
      /* Center it */
    }

    .search-input {
      width: 100%;
      padding: 10px 16px 10px 40px;
      background: #f1f4f9;
      border: 1px solid transparent;
      border-radius: var(--radius-md);
      font-size: 14px;
      font-family: inherit;
      color: var(--text-main);
      transition: all 0.2s;
    }

    .search-input:focus {
      background: #fff;
      border-color: var(--primary);
      outline: none;
      box-shadow: 0 0 0 3px rgba(54, 0, 124, 0.1);
    }

    .search-icon {
      position: absolute;
      left: 12px;
      top: 50%;
      transform: translateY(-50%);
      color: #999;
      pointer-events: none;
      font-size: 16px;
    }

    /* DESKTOP NAV ACTIONS */
    .nav-actions {
      display: flex;
      align-items: center;
      gap: 20px;
      flex-shrink: 0;
      justify-content: flex-end;
    }

    .auth-links {
      display: flex;
      align-items: center;
      gap: 16px;
      font-size: 14px;
      font-weight: 600;
    }

    .auth-links a {
      text-decoration: none;
      color: var(--text-main);
      transition: color .2s;
    }

    .auth-links a:hover {
      color: var(--primary);
    }

    .btn-login {
      background: var(--primary);
      /* Login button color */
      color: #fff !important;
      padding: 8px 20px;
      border-radius: 8px;
      transition: transform .2s;
    }

    .btn-login:hover {
      transform: translateY(-1px);
      opacity: 0.95;
    }

    .user-profile {
      display: flex;
      align-items: center;
      gap: 8px;
      cursor: pointer;
      font-size: 14px;
      font-weight: 600;
    }

    .avatar-circle {
      width: 36px;
      height: 36px;
      background: #eef2ff;
      color: var(--primary);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 700;
    }

    .cart-icon-desk {
      position: relative;
      font-size: 20px;
      cursor: pointer;
      width: 40px;
      height: 40px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 50%;
      transition: background .2s;
    }

    .cart-icon-desk:hover {
      background: #f5f5f5;
    }

    .cart-badge {
      position: absolute;
      top: -2px;
      right: -2px;
      background: var(--secondary);
      color: #fff;
      font-size: 11px;
      font-weight: 700;
      width: 20px;
      height: 20px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      border: 2px solid #fff;
    }

    /* LOGOUT BUTTON */
    .btn-logout {
      border: 0;
      background: #fff0f0;
      color: #d32f2f;
      padding: 6px 12px;
      border-radius: 6px;
      font-size: 12px;
      font-weight: 700;
      cursor: pointer;
      transition: all 0.2s;
      display: flex;
      align-items: center;
      gap: 4px;
    }

    .btn-logout:hover {
      background: #ffe0e0;
    }

    /* MOBILE HEADER ICONS */
    .mobile-icons {
      display: none;
      margin-left: auto;
      align-items: center;
      gap: 12px;
    }

    /* --- CATEGORIES (Story Style) --- */
    .categories-rail {
      background: #fff;
      padding: 16px 0 20px 0;
      margin-bottom: 20px;
      border-bottom: 1px solid rgba(0, 0, 0, 0.04);
    }

    .cat-scroll {
      max-width: 1200px;
      margin: 0 auto;
      display: flex;
      gap: 20px;
      overflow-x: auto;
      padding: 0 16px;
      scroll-behavior: smooth;
      -webkit-overflow-scrolling: touch;
    }

    .cat-scroll::-webkit-scrollbar {
      display: none;
    }

    .cat-item {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 8px;
      min-width: 80px;
      cursor: pointer;
      flex-shrink: 0;
      transition: transform .2s;
    }

    .cat-item:hover {
      transform: translateY(-3px);
    }

    .cat-thumb {
      width: 72px;
      height: 72px;
      border-radius: 50%;
      background: #f8f9fa;
      overflow: hidden;
      border: 2px solid transparent;
      padding: 2px;
      transition: all 0.2s;
      box-shadow: var(--shadow-sm);
    }

    .cat-thumb img {
      width: 100%;
      height: 100%;
      border-radius: 50%;
      object-fit: cover;
    }

    .cat-name {
      font-size: 12px;
      font-weight: 600;
      text-align: center;
      line-height: 1.3;
      color: var(--text-muted);
      max-width: 84px;
    }

    .cat-item.active .cat-thumb {
      border-color: var(--primary);
      box-shadow: 0 0 0 2px rgba(54, 0, 124, 0.1);
    }

    .cat-item.active .cat-name {
      color: var(--primary);
      font-weight: 700;
    }

    /* --- MAIN LAYOUT --- */
    .main-wrapper {
      max-width: 1200px;
      margin: 0 auto;
      display: grid;
      grid-template-columns: 260px 1fr;
      gap: 24px;
      padding: 0 16px 40px 16px;
    }

    /* SIDEBAR (Desktop) */
    .sidebar {
      background: #fff;
      border-radius: var(--radius-lg);
      padding: 16px;
      position: sticky;
      top: 100px;
      height: calc(100vh - 120px);
      overflow-y: auto;
      box-shadow: var(--shadow-sm);
      border: 1px solid rgba(0, 0, 0, 0.03);
    }

    .sidebar::-webkit-scrollbar {
      width: 4px;
    }

    .sidebar::-webkit-scrollbar-thumb {
      background: #ddd;
      border-radius: 4px;
    }

    .sub-nav-item {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 12px 14px;
      margin-bottom: 6px;
      border-radius: var(--radius-md);
      font-size: 14px;
      font-weight: 500;
      color: var(--text-muted);
      cursor: pointer;
      transition: all 0.2s;
    }

    .sub-nav-item:hover {
      background: #f8f9fa;
      color: var(--primary);
    }

    .sub-nav-item.active {
      background: #f0f3ff;
      color: var(--primary);
      font-weight: 700;
    }

    .sub-count {
      background: #fff;
      border: 1px solid #eee;
      border-radius: 10px;
      padding: 2px 8px;
      font-size: 11px;
      font-weight: 600;
      color: #888;
    }

    .sub-nav-item.active .sub-count {
      background: var(--primary);
      color: #fff;
      border-color: var(--primary);
    }

    /* PRODUCT GRID */
    .product-section h2 {
      margin: 0 0 20px 0;
      font-size: 22px;
      font-weight: 800;
      color: var(--text-main);
    }

    .product-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
      gap: 16px;
    }

    .product-card {
      background: var(--bg-card);
      border-radius: var(--radius-lg);
      padding: 16px;
      position: relative;
      border: 1px solid rgba(0, 0, 0, 0.04);
      display: flex;
      flex-direction: column;
      transition: transform .2s, box-shadow .2s;
    }

    .product-card:hover {
      transform: translateY(-4px);
      box-shadow: var(--shadow-md);
    }

    .badge-off {
      position: absolute;
      top: 12px;
      left: 0;
      background: var(--secondary);
      color: #fff;
      font-size: 11px;
      font-weight: 700;
      padding: 4px 10px;
      border-top-right-radius: 6px;
      border-bottom-right-radius: 6px;
      z-index: 2;
      box-shadow: 2px 2px 8px rgba(255, 50, 105, 0.2);
    }

    .p-img-wrap {
      width: 100%;
      height: 140px;
      margin-bottom: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .p-img-wrap img {
      width: 100%;
      height: 100%;
      object-fit: contain;
    }

    .p-time {
      display: flex;
      align-items: center;
      gap: 4px;
      font-size: 10px;
      font-weight: 700;
      color: #333;
      background: #f4f6f8;
      padding: 4px 8px;
      border-radius: 6px;
      width: fit-content;
      margin-bottom: 8px;
    }

    .p-title {
      font-size: 14px;
      font-weight: 700;
      line-height: 1.4;
      margin-bottom: 4px;
      color: var(--text-main);
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
      height: 40px;
    }

    .p-weight {
      font-size: 12px;
      color: #999;
      margin-bottom: 14px;
      font-weight: 500;
    }

    .p-footer {
      margin-top: auto;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .p-price {
      display: flex;
      flex-direction: column;
    }

    .p-price .new {
      font-weight: 800;
      font-size: 16px;
      color: var(--text-main);
    }

    .p-price .old {
      font-size: 12px;
      color: #aaa;
      text-decoration: line-through;
    }

    /* QUANTITY BUTTON (ZEPTO CLASSIC) */
    .add-btn-group {
      width: 80px;
      height: 36px;
      position: relative;
    }

    .btn-add {
      width: 100%;
      height: 100%;
      background: #f7ffff;
      border: 1px solid var(--primary);
      color: var(--primary);
      border-radius: 8px;
      font-size: 13px;
      font-weight: 800;
      text-transform: uppercase;
      cursor: pointer;
      transition: all 0.2s;
    }

    .btn-add:hover {
      background: #eff3ff;
    }

    .qty-controls {
      display: none;
      /* Hidden strictly by default */
      width: 100%;
      height: 100%;
      background: var(--primary);
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      color: #fff;
      display: none;
      /* Logic toggles this */
    }

    .qty-btn {
      width: 28px;
      text-align: center;
      cursor: pointer;
      font-weight: 700;
      font-size: 18px;
      user-select: none;
      line-height: 34px;
    }

    .qty-count {
      font-size: 14px;
      font-weight: 700;
    }

    /* CART FLOATING (Mobile only) */
    .floating-cart {
      position: fixed;
      bottom: 16px;
      left: 16px;
      right: 16px;
      background: var(--primary);
      color: #fff;
      padding: 14px 20px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      box-shadow: 0 10px 20px rgba(54, 0, 124, 0.3);
      z-index: 1000;
      cursor: pointer;
      transform: translateY(150%);
      transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .floating-cart.show {
      transform: translateY(0);
    }

    .fc-details {
      display: flex;
      flex-direction: column;
    }

    .fc-items {
      font-size: 12px;
      font-weight: 600;
      text-transform: uppercase;
      opacity: 0.9;
    }

    .fc-total {
      font-size: 16px;
      font-weight: 800;
    }

    .fc-view {
      font-weight: 700;
      font-size: 14px;
      display: flex;
      align-items: center;
      gap: 6px;
    }

    /* RESPONSIVE TWEAKS */
    @media (min-width: 900px) {
      .location-pill {
        display: flex;
      }
    }

    @media (max-width: 899px) {
      .header-container {
        flex-direction: column;
        gap: 12px;
        align-items: stretch;
      }

      .nav-actions {
        display: none;
        /* Hide full desktop nav on mobile/tablet */
      }

      .location-pill {
        display: none;
      }

      /* Simplify header on mobile */
      .mobile-icons {
        display: flex;
      }

      /* Show mobile icons */
      .brand-area {
        justify-content: space-between;
        width: 100%;
        flex-grow: 1;
      }

      .nav-left {
        width: 100%;
        display: flex;
      }

      .search-wrapper {
        max-width: 100%;
      }

      .main-wrapper {
        display: flex;
        flex-direction: column;
        padding: 12px;
        gap: 0;
      }

      .sidebar {
        position: sticky;
        top: 110px;
        height: auto;
        display: flex;
        overflow-x: auto;
        background: #fff;
        padding: 8px 12px;
        margin: 0 -12px 12px -12px;
        /* Full width bleed */
        z-index: 98;
        white-space: nowrap;
        border-radius: 0;
        border: 0;
        border-bottom: 1px solid #eee;
        box-shadow: none;
      }

      .sub-nav-item {
        margin: 0 8px 0 0;
        padding: 6px 14px;
        border: 1px solid #eee;
        background: #fff;
        flex-shrink: 0;
        border-radius: 20px;
      }

      .sub-nav-item.active {
        border-color: var(--primary);
        background: var(--primary);
        color: #fff;
      }

      .sub-nav-item.active .sub-count {
        background: rgba(255, 255, 255, 0.2);
        color: #fff;
        border-color: transparent;
      }

      .product-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
      }

      .product-card {
        padding: 10px;
        border-radius: var(--radius-md);
      }

      .p-img-wrap {
        height: 110px;
      }

      .p-title {
        font-size: 13px;
      }

      .p-price .new {
        font-size: 15px;
      }

      .add-btn-group {
        width: 70px;
        height: 32px;
      }

      .qty-btn {
        width: 24px;
        font-size: 16px;
        line-height: 30px;
      }
    }
  </style>
</head>

<body>

  <header>
    <div class="header-container">
      <!-- LEFT: Brand + Location + Mobile Icons -->
      <div class="nav-left">
        <div class="brand-area">
          <button onclick="history.back()" style="border:0;background:none;font-size:20px;cursor:pointer;padding:0;margin-right:4px;">←</button>
          <div class="brand-logo">GB</div>
          <div class="brand-text">
            <h1>GrabBaskets</h1>
            <span>10 Mins Delivery</span>
          </div>

          <!-- Mobile Right Icons -->
          <div class="mobile-icons">
            @auth
            <!-- Mobile Logout -->
            <form method="POST" action="{{ route('logout') }}" style="display:inline;">
              @csrf
              <button type="submit" style="background:none; border:0; font-size:18px; cursor:pointer;" title="Logout">
                🚪
              </button>
            </form>
            @endauth

            <!-- Mobile Cart -->
            <div class="cart-icon-desk" style="width:32px; height:32px; font-size:18px;" onclick="window.location.href='{{ route('cart.index') }}'">
              <span>🛒</span>
              <div class="cart-badge" id="mobileCartBadge">0</div>
            </div>
          </div>
        </div>

        <!-- Location (Desktop) -->
        <div class="location-pill">
          <span>📍</span>
          <span>New York, USA</span>
        </div>
      </div>

      <!-- SEARCH (Center) -->
      <div class="search-wrapper">
        <span class="search-icon">🔍</span>
        <input type="text" class="search-input" id="globalSearch" placeholder='Search "chips"'>
      </div>

      <!-- RIGHT: Desktop Actions -->
      <div class="nav-actions">
        @auth
        <div class="user-profile">
          <div class="avatar-circle">
            {{ substr(Auth::user()->name, 0, 1) }}
          </div>
          <span>Hi, {{ strtok(Auth::user()->name, ' ') }}</span>
        </div>

        <!-- Desktop Logout -->
        <form method="POST" action="{{ route('logout') }}" style="margin-left: 10px;">
          @csrf
          <button type="submit" class="btn-logout">
            Logout
          </button>
        </form>
        @else
        <div class="auth-links">
          <a href="{{ route('login') }}" class="btn-login">Login</a>
          <a href="{{ route('register') }}">Sign Up</a>
        </div>
        @endauth

        <div class="cart-icon-desk" onclick="window.location.href='{{ route('cart.index') }}'">
          <span style="font-size: 22px;">🛒</span>
          <div class="cart-badge" id="desktopCartBadge">0</div>
        </div>
      </div>
    </div>
  </header>

  <!-- Categories Rail -->
  <div class="categories-rail">
    <div class="cat-scroll">
      @foreach($categories as $cat)
      @php
      // Fallback images map
      $fallbackImages = [
      // 'ELECTRONICS' => 'https://images.unsplash.com/photo-1519125323398-675f0ddb6308?auto=format&fit=crop&w=150&q=80',
      // 'MEN\'S FASHION' => 'https://images.unsplash.com/photo-1516257984-b1b4d707412e?auto=format&fit=crop&w=150&q=80',
      // 'WOMEN\'S FASHION' => 'https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=150&q=80',
      // 'HOME & KITCHEN' => 'https://images.unsplash.com/photo-1507089947368-19c1da9775ae?auto=format&fit=crop&w=150&q=80',
      // 'BEAUTY & PERSONAL CARE' => 'https://images.unsplash.com/photo-1515378791036-0648a3ef77b2?auto=format&fit=crop&w=150&q=80',
      // 'SPORTS & FITNESS' => 'https://images.unsplash.com/photo-1519864600265-abb23847ef2c?auto=format&fit=crop&w=150&q=80',
      // 'BOOKS & EDUCATION' => 'https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&w=150&q=80',
      // 'KIDS & TOYS' => 'https://images.unsplash.com/photo-1503457574465-0ec62fae31a0?auto=format&fit=crop&w=150&q=80',
      // 'GROCERY & FOOD' => 'https://images.unsplash.com/photo-1502741338009-cac2772e18bc?auto=format&fit=crop&w=150&q=80',
      ];
      $imgUrl = $cat->image ?? ($fallbackImages[strtoupper($cat->name)] ?? $fallbackImages[$cat->name] ?? 'https://images.unsplash.com/photo-1602052577122-f73b9710adba?auto=format&fit=crop&w=150&q=80');
      @endphp
      <div class="cat-item {{ $activeCategory && $activeCategory->id === $cat->id ? 'active' : '' }}"
        onclick="window.location.href='?category={{ $cat->id }}'">
        <div class="cat-thumb">
          <img src="{{ $imgUrl }}" alt="{{ $cat->name }}">
        </div>
        <div class="cat-name">{{ $cat->name }}</div>
      </div>
      @endforeach
    </div>
  </div>

  <!-- Main Content -->
  <div class="main-wrapper">
    <!-- Subcategory Sidebar -->
    <aside class="sidebar">
      <div class="sub-nav-item active" onclick="filterSub(this, 'All')">
        <span>All Items</span>
        <span class="sub-count">{{ $activeCategory->tenMinProducts->count() }}</span>
      </div>
      @foreach($activeCategory->filteredSubcategories as $sub)
      @php $subCount = $activeCategory->tenMinProducts->where('subcategory_id', $sub->id)->count(); @endphp
      <div class="sub-nav-item" onclick="filterSub(this, '{{ $sub->name }}')">
        <span>{{ $sub->name }}</span>
        <span class="sub-count">{{ $subCount }}</span>
      </div>
      @endforeach
    </aside>

    <!-- Product Grid -->
    <div class="product-section">
      <h2 id="catTitle">{{ $activeCategory->name }}</h2>
      <div id="productGrid" class="product-grid">
        @foreach($activeCategory->tenMinProducts as $product)
        <div class="product-card" data-sub="{{ $product->subcategory?->name ?? 'Other' }}" data-name="{{ strtolower($product->name) }}">
          @if($product->discount > 0)
          <div class="badge-off">{{ $product->discount }}% OFF</div>
          @endif
          <div class="p-img-wrap">
            <img src="{{ $product->image_url ?? asset('images/placeholder.png') }}" onerror="this.src='{{ asset('images/placeholder.png') }}'">
          </div>
          <div class="p-time">⚡ 10 mins</div>
          <div class="p-title">{{ $product->name }}</div>
          <div class="p-weight">{{ $product->subcategory?->name ?? '1 unit' }}</div>

          <div class="p-footer">
            <div class="p-price">
              <span class="new">₹{{ $product->price }}</span>
              @if($product->discount > 0)
              <span class="old">₹{{ $product->price + $product->discount }}</span>
              @endif
            </div>
            <div class="add-btn-group">
              <button class="btn-add" onclick="addToCart(this, {{ $product->id }}, {{ $product->price }})">ADD</button>
              <div class="qty-controls">
                <div class="qty-btn" onclick="updateQty(this, -1)">-</div>
                <div class="qty-count">1</div>
                <div class="qty-btn" onclick="updateQty(this, 1)">+</div>
              </div>
            </div>
          </div>
        </div>
        @endforeach
      </div>

      <div id="noResults" style="display:none; text-align:center; padding:40px; color:#999;">
        <h3>No products found under this category</h3>
      </div>
    </div>
  </div>

  <!-- Floating Cart -->
  <div id="floatingCart" class="floating-cart" onclick="window.location.href='{{ route('cart.index') }}'">
    <div class="fc-details">
      <span class="fc-items"><span id="fcCount">0</span> ITEMS</span>
      <span class="fc-total">₹<span id="fcTotal">0</span></span>
    </div>
    <div class="fc-view">
      View Cart <span>→</span>
    </div>
  </div>

  <script>
    let activeSub = 'All';
    let cartTotal = 0;
    let cartCount = 0;

    // Subcategory Filter
    function filterSub(el, subName) {
      // Update Active UI
      document.querySelectorAll('.sub-nav-item').forEach(i => i.classList.remove('active'));
      el.classList.add('active');

      activeSub = subName;
      renderGrid();
    }

    // Search Filter
    document.getElementById('globalSearch').addEventListener('input', function(e) {
      const term = e.target.value.toLowerCase();
      const cards = document.querySelectorAll('.product-card');
      let visible = 0;

      cards.forEach(card => {
        const name = card.dataset.name;
        const sub = card.dataset.sub;
        // logic: text match AND sub match
        const matchesText = name.includes(term);
        const matchesSub = activeSub === 'All' || sub === activeSub;

        if (matchesText && matchesSub) {
          card.style.display = 'flex';
          visible++;
        } else {
          card.style.display = 'none';
        }
      });

      document.getElementById('noResults').style.display = visible === 0 ? 'block' : 'none';
      if (term.length > 0) document.getElementById('catTitle').textContent = 'Search Results';
      else document.getElementById('catTitle').textContent = '{{ $activeCategory->name }}';
    });

    function renderGrid() {
      const cards = document.querySelectorAll('.product-card');
      let visible = 0;
      cards.forEach(card => {
        if (activeSub === 'All' || card.dataset.sub === activeSub) {
          card.style.display = 'flex';
          visible++;
        } else {
          card.style.display = 'none';
        }
      });
      document.getElementById('noResults').style.display = visible === 0 ? 'block' : 'none';
    }

    // Add to Cart Logic (UI simulation + Server stub)
    function addToCart(btn, pid, price) {
      // Swap UI to Qty controls
      const parent = btn.parentElement;
      const controls = parent.querySelector('.qty-controls');
      btn.style.display = 'none';
      controls.style.display = 'flex';

      // Update Cart State
      updateCartState(1, price);

      // Trigger Server (Stub)
      // fetch('/cart/add', { ... }) 
    }

    function updateQty(btn, change) {
      const controls = btn.parentElement;
      const countSpan = controls.querySelector('.qty-count');
      let current = parseInt(countSpan.textContent);
      let next = current + change;

      if (next < 1) {
        // Revert to ADD button
        controls.style.display = 'none';
        controls.parentElement.querySelector('.btn-add').style.display = 'block';
        updateCartState(-1, 0); // Price needed ideally, assuming simplified for now
      } else {
        countSpan.textContent = next;
        updateCartState(change > 0 ? 1 : -1, 0); // Price needed
      }

      // Stop propagation to avoid card click
      event.stopPropagation();
    }

    function updateCartState(countChange, priceChange) {
      cartCount += countChange;
      cartTotal += priceChange; // Note: In real app, need price per item for +/- logic

      document.getElementById('headerCartCount').textContent = cartCount;
      document.getElementById('fcCount').textContent = cartCount;
      document.getElementById('fcTotal').textContent = cartTotal;

      const fc = document.getElementById('floatingCart');
      if (cartCount > 0) fc.classList.add('show');
      else fc.classList.remove('show');
    }
  </script>

</body>

</html>
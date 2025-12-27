<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
<<<<<<< HEAD
<meta name="viewport" content="width=device-width,initial-scale=1" />
<meta name="csrf-token" content="{{ csrf_token() }}"> {{-- ✅ Required for AJAX --}}
<title>GrabBasket — 10-Minute Products</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
<style>
/* ---------- base (from your css) ---------- */ 
html, body {
margin: 0;
padding: 0;
overflow-x: hidden;
font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
background: #f4f6f8;
color: #0b1a12;
}
 
/* Layout and header */
header {
background: linear-gradient(180deg,#ffffff,#fbfdfb);
padding: 12px 16px;
border-bottom: 1px solid rgba(8,10,10,0.03);
position: sticky;
top: 0;
z-index: 200;
box-shadow: 0 1px 0 rgba(0,0,0,0.02);
}
 
.container {
max-width: 1200px;
margin: 0 auto;
padding: 0 16px;
}
.header-row {
display: flex;
align-items: center;
justify-content: space-between;
gap: 12px;
flex-wrap: wrap;
}
 
/* brand + menu button */
.brand {
display: flex;
align-items: center;
gap: 12px;
}
.logo {
width: 44px;
height: 44px;
border-radius: 10px;
background: linear-gradient(135deg,#cff2b6,#ffd86b);
display: flex;
align-items: center;
justify-content: center;
font-weight: 800;
color: #063310;
font-size: 18px;
}
.brand .title { font-weight: 700; font-size: 16px; }
.brand .sub { font-size: 12px; color: #6b7280; }
 
/* hamburger menu (visible on mobile) */
.menu-btn {
display: none;
background: transparent;
border: 0;
padding: 8px;
border-radius: 8px;
cursor: pointer;
font-size: 20px;
}
.menu-btn:focus { outline: 2px solid rgba(47,122,47,0.18); }
 
/* header actions (right side) */
.header-actions {
display: flex;
align-items: center;
gap: 12px;
}
 
/* ---------- SEARCH BAR ---------- */
.search {
display: flex;
align-items: center;
gap: 10px;
background: #fff;
padding: 10px 16px;
border-radius: 14px;
box-shadow: 0 8px 24px rgba(15,23,36,0.08);
width: 100%;
max-width: 400px;
transition: all 0.3s ease;
box-sizing: border-box;
}
.search svg {
opacity: 0.7;
flex-shrink: 0;
font-size: 18px;
}
.search input {
border: 0;
outline: 0;
background: transparent;
width: 100%;
font-size: 14px;
color: #08120a;
padding: 4px 0;
}
.search input::placeholder {
color: #6b7280;
}
.search button {
border: 0;
background: linear-gradient(180deg,#2f7a2f,#2f7a2f);
color: #fff;
padding: 8px 14px;
border-radius: 10px;
cursor: pointer;
font-weight: 700;
box-shadow: 0 6px 18px rgba(47,122,47,0.14);
flex-shrink: 0;
transition: transform 0.15s ease;
}
.search button:hover { transform: translateY(-2px); }
 
/* Categories row */
.categories-row { padding: 18px 0; }
.categories { display: flex; flex-wrap: wrap; gap: 12px; align-items: center; }
.category {
display: flex;
flex-direction: column;
align-items: center;
gap: 8px;
min-width: 86px;
cursor: pointer;
text-align: center;
padding: 8px;
border-radius: 12px;
transition: all .18s;
background: transparent;
}
.category .badge {
width: 68px;
height: 68px;
border-radius: 50%;
display: flex;
align-items: center;
justify-content: center;
font-size: 28px;
background: linear-gradient(135deg,#f1f9ef,#fff);
box-shadow: 0 6px 18px rgba(15,23,36,0.06);
}
/* Ensure images inside badge are centered and sized */
.category .badge img {
  width: 36px;
  height: 36px;
  object-fit: contain;
}
.category .label { font-size: 13px; font-weight: 600; color: #0b1a12; }
.category:hover { transform: translateY(-6px); box-shadow: 0 18px 40px rgba(15,23,36,0.09); }
.category.active .badge { background: linear-gradient(135deg,#e6f7e6,#eafbed); box-shadow: 0 18px 40px rgba(39,122,39,0.12); }
 
/* Main layout */
.layout { display: grid; grid-template-columns: 280px 1fr; gap: 22px; max-width: 1200px; margin: 0 auto; padding: 0 16px; }
 
/* Sidebar desktop */
aside.sidebar {
background: #fff;
padding: 18px;
border-radius: 12px;
box-shadow: 0 6px 18px rgba(15,23,36,0.06);
height: calc(100vh - 160px);
position: sticky;
top: 84px;
overflow-y: auto;
transition: transform .28s ease, left .28s ease;
}
 
/* Sidebar items */
.sub-list { display: flex; flex-direction: column; gap: 10px; }
.sub-item {
display: flex;
align-items: center;
gap: 12px;
padding: 10px;
border-radius: 10px;
cursor: pointer;
transition: transform .12s ease, background .12s ease;
background: transparent;
}
.sub-item:hover { transform: translateX(6px); background: linear-gradient(90deg,#fbfff6,#fff); }
.sub-item.active { background: linear-gradient(90deg,#ecffd6,#fff); box-shadow: 0 10px 30px rgba(47,122,47,0.06); }
.sub-item .icon {
width: 44px;
height: 44px;
border-radius: 10px;
display: flex;
align-items: center;
justify-content: center;
font-size: 18px;
background: linear-gradient(135deg,#f1f9ef,#e6f3d8);
color: #2f7a2f;
box-shadow: 0 6px 12px rgba(15,23,36,0.04);
flex-shrink: 0;
}
.sub-item .meta .name { font-weight: 600; }
.sub-item .meta .hint { font-size: 13px; color: #6b7280; }
.sub-item .count { font-size: 13px; color: #6b7280; padding: 6px 8px; border-radius: 8px; background: rgba(15,23,36,0.04); }
 
/* Products */
.products { display: grid; gap: 18px; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); }
.card {
background: #fff;
border-radius: 12px;
padding: 12px;
box-shadow: 0 6px 18px rgba(15,23,36,0.06);
display: flex;
flex-direction: column;
transition: transform .12s ease, box-shadow .12s ease;
position: relative;
}
.card:hover { transform: translateY(-8px); box-shadow: 0 18px 40px rgba(15,23,36,0.09); }
.thumb { width: 100%; height: 150px; border-radius: 10px; background: linear-gradient(180deg,#eef4f7,#f7fbfb); display: flex; align-items: center; justify-content: center; overflow: hidden; }
.thumb img { width: 100%; height: 100%; object-fit: cover; display: block; }
.ribbon { position: absolute; top: 12px; right: 12px; background: linear-gradient(180deg,#ff6b6b,#e33434); color: #fff; padding: 6px 8px; border-radius: 8px; font-weight: 700; font-size: 12px; box-shadow:0 8px 22px rgba(227,52,52,0.16); }
.card h4 { margin: 12px 0 6px 0; font-size: 15px; color: #07140a; }
.meta-sub { font-size: 13px; color: #6b7280; }
.price-row { display: flex; align-items: center; gap: 8px; margin-top: 10px; flex-wrap: wrap; }
.price-original { font-size: 13px; color: #6b7280; text-decoration: line-through; }
.price-now { font-weight: 800; color: #2f7a2f; }
.discount-text { font-weight: 700; color: #d8392c; font-size: 13px; margin-left: auto; white-space: nowrap; }
.add-btn { margin-top: 12px; background: linear-gradient(180deg,#2f7a2f,#2f7a2f); color: #fff; border: 0; padding: 10px; border-radius: 10px; font-weight: 700; cursor: pointer; }
.add-btn:active { transform: translateY(1px); }
 
/* MOBILE OVERLAY */
.mobile-overlay { display: none; position: fixed; inset: 0; background: rgba(6,10,8,0.36); z-index: 180; opacity: 0; transition: opacity .22s ease; }
.mobile-overlay.show { display: block; opacity: 1; }
 
/* ==========================
MOBILE SIDEBAR
========================== */
@media (max-width: 900px) {
.menu-btn { display: inline-flex; }
.header-row { align-items: center; }
.container.header-row { padding-left: 12px; padding-right: 12px; }
 
.categories { display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; align-items: stretch; }
.category { min-width: auto; }
.layout { grid-template-columns: 1fr; gap: 12px; }
 
aside.sidebar {
    position: fixed;
    left: -320px;
    top: 60px;
    bottom: 0;
    width: 280px;
    height: calc(100% - 60px);
    border-radius: 0;
    margin: 0;
    padding: 18px;
    box-shadow: 0 40px 80px rgba(6,10,8,0.18);
    z-index: 190;
    transition: left .28s cubic-bezier(.2,.9,.3,1);
    overflow-y: auto;
    background: #fff;
}
aside.sidebar.open { left: 0; }
 
.products, .product-grid, .product-list { display: grid; grid-template-columns: repeat(2, 1fr) !important; gap: 14px !important; }
}
 
/* SMALL MOBILE */
@media (max-width: 600px) {
.products, .product-grid, .product-list { grid-template-columns: repeat(2, 1fr) !important; }
.categories { grid-template-columns: repeat(2, 1fr); }
.search input { width: 100%; }
}
 
/* DESKTOP: SEARCH NEXT TO LOGO */
@media (min-width: 901px) {
.header-row {
display: flex;
align-items: center;
gap: 16px;
}
.brand { flex-shrink: 0; }
.search { flex: 1; }
.header-actions { flex-shrink: 0; }
}
 
.card {
  cursor: pointer;
=======
<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no" />
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>GrabBasket — 10-Minute Delivery</title>
<link href="https://fonts.googleapis.com/css2?family=Gilroy:wght@300;400;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<style>
/* 
    ZEPTO-INSPIRED DESIGN SYSTEM
    Primary: #ff3269 (Magenta) or #36007a (Deep Purple) - Kept Green for GrabBaskets Identity
*/
:root {
    --primary: #0c0c0c;       /* Main text */
    --brand: #2f7a2f;         /* GrabBasket Green */
    --brand-light: #e6f7e6;
    --accent: #ff3269;        /* Zepto-ish Magenta for promos */
    --surface: #f3f4f6;       /* Light grey background */
    --white: #ffffff;
    --border: #e5e7eb;
    --text-sec: #6b7280;
    --radius: 16px;
    --shadow: 0 4px 20px rgba(0,0,0,0.06);
}

* { margin:0; padding:0; box-sizing:border-box; -webkit-tap-highlight-color:transparent; }

body {
    font-family: 'Inter', system-ui, sans-serif;
    background: #fcfcfc;
    color: var(--primary);
    overflow-x: hidden;
}

a { text-decoration:none; color:inherit; }
button { font-family:inherit; }

/* ========== HEADER ========== */
header {
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(12px);
    position: sticky;
    top: 0;
    z-index: 100;
    border-bottom: 1px solid rgba(0,0,0,0.05);
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
    box-shadow: 0 4px 12px rgba(47,122,47,0.1);
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

.nav-btn:hover { background: #f3f4f6; }

.cart-btn {
    background: var(--brand);
    color: white !important;
    padding: 10px 20px;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(47,122,47,0.25);
    transition: transform 0.2s;
}
.cart-btn:active { transform: scale(0.96); }

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
/* Custom Scrollbar for Sidebar */
.sidebar::-webkit-scrollbar { width: 5px; }
.sidebar::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 10px; }

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
    box-shadow: 0 2px 8px rgba(0,0,0,0.03);
    transform: translateX(2px);
}

.cat-item.active {
    background: #e9f5e9; /* Light green tint */
    border-color: rgba(47,122,47,0.1);
    color: var(--brand);
    font-weight: 700;
}

/* Dynamic Image Placeholder using Keyword */
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
    scrollbar-width: none; /* Hide scrollbar Firefox */
}
.subcategories::-webkit-scrollbar { display: none; }

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

.sub-pill:hover { border-color: #d1d5db; background: #f9fafb; }
.sub-pill.active {
    background: #111; /* Black pill like new design trends */
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
    box-shadow: 0 12px 30px -10px rgba(0,0,0,0.12);
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

.product-card:hover .p-img { transform: scale(1.05); }

.discount-badge {
    position: absolute;
    top: 8px;
    left: 8px;
    background: #ffecf0;
    color: #d1004b; /* Vibrant pink/red text */
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

/* ========== MOBILE OVERRIDES ========== */
@media (max-width: 900px) {
    .main-layout { grid-template-columns: 1fr; padding: 0 16px; display: block; }
    .sidebar { display: none; } /* Hide heavy sidebar on mobile */
    
    .mobile-cats {
        display: flex;
        gap: 12px;
        overflow-x: auto;
        padding: 12px 16px;
        background: #fff;
        position: sticky;
        top: 65px;
        z-index: 90;
        border-bottom: 1px solid #f3f4f6;
        margin: 0 -16px 20px -16px;
    }

    .m-cat-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 72px;
        flex-shrink: 0;
        gap: 6px;
    }

    .m-cat-img {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        object-fit: cover;
        background: #f3f4f6;
        border: 2px solid transparent;
    }
    .m-cat-item.active .m-cat-img { border-color: var(--brand); }

    .m-cat-name {
        font-size: 11px;
        text-align: center;
        font-weight: 600;
        color: #4b5563;
        line-height: 1.2;
    }

    .nav-container { padding: 0 16px; gap: 12px; }
    .search-bar { display: none; margin: 0; } /* Simplify header on mobile */
    .logo { font-size: 20px; }
    
    /* Show compact search below header */
    .mobile-search {
        display: block;
        padding: 10px 16px;
        background: #fff;
        margin-bottom: 10px;
    }
    
    .product-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }
    
    .product-card { padding: 12px; border-radius: 12px; }
    .add-btn-sm { padding: 6px 14px; font-size: 12px; }
}

@media (min-width: 901px) {
    .mobile-cats, .mobile-search { display: none; }
>>>>>>> a9bc5205ec22eeddf3f4ac9a1a0d9385b463a0cb
}
</style>
</head>
<body>
<<<<<<< HEAD
 
<header>
  <div class="container header-row">
    <!-- Brand + Mobile Menu -->
    <div class="brand-group">
      <button id="menuToggle" class="menu-btn" aria-label="Open menu" title="Menu">☰</button>
      <div class="brand">
        <div class="logo">GB</div>
        <div>
          <div class="title">GrabBaskets</div>
          <div class="sub">Fresh & fast — 10-min delivery</div>
        </div>
      </div>
    </div>

    <!-- 🔍 SEARCH BAR -->
    <div class="search-container">
      <div class="search">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
          <path d="M21 21l-4.35-4.35" stroke="#111" stroke-width="1.6" stroke-linecap="round"/>
          <circle cx="11" cy="11" r="6" stroke="#111" stroke-width="1.6"/>
        </svg>
        <input id="globalSearch" placeholder="Search products" aria-label="Search products">
        <button id="searchBtn">Search</button>
      </div>
    </div>

    <!-- Auth / Cart -->
    <div class="header-actions">
      @auth
        <a href="{{ route('tenmin.cart.view') }}" class="btn-link" style="color:#0b1a12;text-decoration:none;font-weight:600">Cart</a>
        <form method="POST" action="{{ route('logout') }}" style="display:inline">
          @csrf
          <button type="submit" style="background:none;border:none;color:#6b7280;cursor:pointer;font-size:14px">Logout</button>
        </form>
      @else
        <a href="{{ route('login') }}" style="color:#0b1a12;text-decoration:none;font-weight:600">Login</a>
        <a href="{{ route('register') }}" style="background:#2f7a2f;color:white;padding:6px 12px;border-radius:6px;text-decoration:none;font-weight:600;font-size:14px">Signup</a>
      @endauth
    </div>
  </div>
</header>
 
<div id="mobileOverlay" class="mobile-overlay" aria-hidden="true"></div>
<section class="categories-row">
  <div class="container">
    <div id="categoriesContainer" class="categories">
 @foreach($categories as $cat)
    @php
        $categoryName = $cat->name;
        $isActive = (request('category') == $cat->id && !request('search'));
        $url = route('ten.min.products', ['category' => $cat->id]);
        $firstLetter = mb_strtoupper(mb_substr($categoryName, 0, 1));
        $fallback = 'https://via.placeholder.com/85/FF6B00/FFFFFF?text=  ' . urlencode($firstLetter);
    @endphp

    <a href="{{ $url }}" class="category {{ $isActive ? 'active' : '' }}">
        <div class="badge">
            <img src="{{ $fallback }}" alt="{{ $categoryName }}" />
        </div>
        <div class="label">{{ $categoryName }}</div>
    </a>
@endforeach

    </div>
  </div>
</section>
 
<main>
  <div class="layout container">
    <!-- Sidebar -->
    <aside id="sidebar" class="sidebar" aria-hidden="false">
      <h4>Subcategories</h4>
      <div id="subList" class="sub-list">
        <div class="sub-item active" data-sub="All">
          <div class="icon">★</div>
          <div class="meta">
            <div class="name">All</div>
            <div class="hint">All items in {{ $activeCategory->name }}</div>
          </div>
          <div class="count">{{ $activeCategory->tenMinProducts->count() }}</div>
        </div>
 
        @foreach($activeCategory->filteredSubcategories as $sub)
          @php
            $subCount = $activeCategory->tenMinProducts->where('subcategory_id', $sub->id)->count();
          @endphp
          <div class="sub-item" data-sub="{{ $sub->name }}">
            <div class="icon">📦</div>
            <div class="meta">
              <div class="name">{{ $sub->name }}</div>
              <div class="hint">{{ $subCount }} items</div>
            </div>
            <div class="count">{{ $subCount }}</div>
          </div>
        @endforeach
      </div>
    </aside>
 
    <!-- Products -->
    <section>
      <div class="catalog-head">
        <div>
          <h2 id="catalogTitle">{{ $activeCategory->name }}</h2>
          <div style="color:#6b7280;font-size:13px">
            Showing <span id="showCount">{{ $activeCategory->tenMinProducts->count() }}</span> items
          </div>
        </div>
        <div>
          <select id="sortBy">
            <option value="relevance">Sort: Relevance</option>
            <option value="price-asc">Price: Low to High</option>
            <option value="price-desc">Price: High to Low</option>
          </select>
        </div>
      </div>
 
      <div id="productGrid" class="products">
        @foreach($activeCategory->tenMinProducts as $product)
          <div class="card" data-subcat="{{ $product->subcategory?->name ?? 'Other' }}">
            @if($product->discount)
              <div class="ribbon">{{ $product->discount }}% OFF</div>
            @endif
            <!-- Clickable area for product details -->
            <a href="{{ route('product.details', $product->id) }}" style="text-decoration: none; color: inherit; display: block;">
              <div class="thumb">
                <img src="{{ asset('product_images/'.$product->image) }}" alt="{{ $product->name }}">
              </div>
              <h4>{{ $product->name }}</h4>
              <div class="meta-sub">{{ $product->subcategory?->name ?? 'Other' }}</div>
              <div class="price-row">
                <div class="price-original">₹{{ $product->price + ($product->discount ?? 0) }}</div>
                <div class="price-now">₹{{ $product->price }}</div>
                @if($product->discount)
                  <div class="discount-text">-{{ $product->discount }}%</div>
                @endif
              </div>
            </a>
            <!-- Add to cart button OUTSIDE the link -->
            <button class="add-btn" data-product-id="{{ $product->id }}">Add to cart</button>
          </div>
        @endforeach
      </div>
 
      <div id="noResults" style="display:none;margin-top:18px">
        <div class="empty">
          <h4>No products found</h4>
          <div>Try another subcategory or search</div>
        </div>
      </div>
    </section>
  </div>
</main>
 
<script>
const categoriesData = @json($jsCategories);
let activeCategory = categoriesData.find(c => c.id === {{ $activeCategory->id ? "'{$activeCategory->id}'" : 'null' }}) || categoriesData[0];
let activeSub = 'All';

const sidebar = document.getElementById('sidebar');
const mobileOverlay = document.getElementById('mobileOverlay');
const menuToggle = document.getElementById('menuToggle');

function openSidebar() {
    sidebar.classList.add('open');
    mobileOverlay.classList.add('show');
    sidebar.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';
}
function closeSidebar() {
    sidebar.classList.remove('open');
    mobileOverlay.classList.remove('show');
    sidebar.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
}

menuToggle.addEventListener('click', () => {
    sidebar.classList.contains('open') ? closeSidebar() : openSidebar();
});
mobileOverlay.addEventListener('click', closeSidebar);
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeSidebar();
});

function renderCategory(cat) {
    activeCategory = cat;
    activeSub = 'All';
    document.getElementById('catalogTitle').textContent = cat.name;

    const subList = document.getElementById('subList');
    subList.innerHTML = '';
    const allItem = document.createElement('div');
    allItem.className = 'sub-item active';
    allItem.dataset.sub = 'All';
    allItem.innerHTML = `<div class="icon">★</div><div class="meta"><div class="name">All</div><div class="hint">All items in ${cat.name}</div></div><div class="count">${cat.products.length}</div>`;
    subList.appendChild(allItem);

    cat.subcategories.forEach(sub => {
        const subCount = cat.products.filter(p => p.subcategory === sub.name).length;
        const subItem = document.createElement('div');
        subItem.className = 'sub-item';
        subItem.dataset.sub = sub.name;
        subItem.innerHTML = `<div class="icon">📦</div><div class="meta"><div class="name">${sub.name}</div><div class="hint">${subCount} items</div></div><div class="count">${subCount}</div>`;
        subList.appendChild(subItem);
    });

    renderProducts();
    attachSubItemEvents();
}

function renderProducts() {
    const productGrid = document.getElementById('productGrid');
    productGrid.innerHTML = '';
    const products = activeCategory.products.filter(p => activeSub === 'All' || p.subcategory === activeSub);

    if(products.length === 0){
        document.getElementById('noResults').style.display = 'block';
    } else {
        document.getElementById('noResults').style.display = 'none';
        products.forEach(p => {
            const card = document.createElement('div');
            card.className = 'card';
            card.dataset.subcat = p.subcategory;
            card.innerHTML = `
                ${p.discount ? `<div class="ribbon">${p.discount}% OFF</div>` : ''}
                <a href="/product/${p.id}" style="text-decoration:none;color:inherit;display:block;">
                    <div class="thumb">
                        <img src="${p.img}" alt="${p.name}">
                    </div>
                    <h4>${p.name}</h4>
                    <div class="meta-sub">${p.subcategory}</div>
                    <div class="price-row">
                        <div class="price-original">₹${p.price + (p.discount || 0)}</div>
                        <div class="price-now">₹${p.price}</div>
                        ${p.discount ? `<div class="discount-text">-${p.discount}%</div>` : ''}
                    </div>
                </a>
                <button class="add-btn" data-product-id="${p.id}">Add to cart</button>
            `;
            productGrid.appendChild(card);
        });
    }
    document.getElementById('showCount').textContent = products.length;
    attachAddBtnEvents();
}

function attachSubItemEvents(){
    document.querySelectorAll('.sub-item').forEach(item => {
        item.addEventListener('click', () => {
            document.querySelectorAll('.sub-item').forEach(i => i.classList.remove('active'));
            item.classList.add('active');
            activeSub = item.dataset.sub;
            renderProducts();
            if (window.innerWidth <= 900) closeSidebar();
        });
    });
}

function attachAddBtnEvents(){
    document.querySelectorAll('.add-btn').forEach(btn => {
        btn.addEventListener('click', async (e) => {
            e.preventDefault();
            const productId = btn.dataset.productId;
            const quantity = 1;

            try {
                const res = await fetch('/ten-min/cart/add', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ product_id: productId, quantity })
                });

                const data = await res.json();
                if (data.success) {
                    btn.textContent = 'Added ✓';
                    setTimeout(() => btn.textContent = 'Add to cart', 700);
                } else {
                    alert(data.error || 'Failed to add item');
                }
            } catch (err) {
                console.error(err);
                alert('Network error');
            }
        });
    });
}

function attachCategoryButtons() {
    document.querySelectorAll('.category').forEach(catEl => {
        catEl.addEventListener('click', () => {
            document.querySelectorAll('.category').forEach(c => c.classList.remove('active'));
            catEl.classList.add('active');
            const catId = catEl.href.split('category=')[1];
            const cat = categoriesData.find(c => c.id == catId);
            if (cat) renderCategory(cat);
            if (window.innerWidth <= 900) closeSidebar();
        });
    });
}

// Initial render
if (activeCategory) {
    renderCategory(activeCategory);
}
attachCategoryButtons();

window.addEventListener('resize', () => {
    if (window.innerWidth > 900) {
        closeSidebar();
        document.body.style.overflow = '';
    }
});

document.getElementById('searchBtn').addEventListener('click', () => {
    const q = document.getElementById('globalSearch').value.trim().toLowerCase();
    if (!q) {
        activeSub = 'All';
        renderProducts();
        return;
    }
    const productGrid = document.getElementById('productGrid');
    productGrid.innerHTML = '';
    const matched = activeCategory.products.filter(p => (p.name || '').toLowerCase().includes(q) || (p.subcategory || '').toLowerCase().includes(q));
    if (matched.length === 0) {
        document.getElementById('noResults').style.display = 'block';
    } else {
        document.getElementById('noResults').style.display = 'none';
        matched.forEach(p => {
            const card = document.createElement('div');
            card.className = 'card';
            card.innerHTML = `
              ${p.discount ? `<div class="ribbon">${p.discount}% OFF</div>` : ''}
              <a href="/product/${p.id}" style="text-decoration:none;color:inherit;display:block;">
                <div class="thumb"><img src="${p.img}" alt="${p.name}"></div>
                <h4>${p.name}</h4>
                <div class="meta-sub">${p.subcategory}</div>
                <div class="price-row">
                  <div class="price-original">₹${p.price + (p.discount || 0)}</div>
                  <div class="price-now">₹${p.price}</div>
                  ${p.discount ? `<div class="discount-text">-${p.discount}%</div>` : ''}
                </div>
              </a>
              <button class="add-btn" data-product-id="${p.id}">Add to cart</button>
            `;
            productGrid.appendChild(card);
        });
    }
    attachAddBtnEvents();
});
</script>
=======

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
                    <button type="submit" class="nav-btn" style="border:none; background:transparent; cursor:pointer;" title="Logout">
                        <i class="fa-solid fa-right-from-bracket"></i>
                    </button>
                 </form>
            @else
                <a href="{{ route('login') }}" class="nav-btn">
                    <i class="fa-regular fa-user"></i>&nbsp;Login
                </a>
            @endauth
            
            <a href="{{ route('tenmin.cart.view') }}" class="nav-btn cart-btn">
                <i class="fa-solid fa-cart-shopping"></i>&nbsp;
                <span id="cartCountBadge">{{ \App\Models\TenMinGroceryCartItem::where('user_id', auth()->id())->sum('quantity') }}</span>
            </a>
        </div>
    </div>
</header>

<!-- Mobile Search & Categories -->
<div class="mobile-search">
    <div style="position:relative;">
        <i class="fa-solid fa-magnifying-glass search-icon"></i>
        <input style="width:100%;padding:12px 12px 12px 42px;border:1px solid #e5e7eb;border-radius:10px;background:#f9fafb;" placeholder="Search..." id="mobileSearchInput">
    </div>
</div>

<div class="mobile-cats">
    <div class="m-cat-item {{ !$activeCategory ? 'active' : '' }}" onclick="window.location.href='{{ route('ten.min.products') }}'">
        <div class="m-cat-img" style="display:flex;align-items:center;justify-content:center;background:#000;color:white;">
            <i class="fa-solid fa-star"></i>
        </div>
        <span class="m-cat-name">All</span>
    </div>
    @foreach($categories as $cat)
        @php
            // Dynamic image based on category name
            $keyword = urlencode($cat->name);
            $imgUrl = "https://loremflickr.com/100/100/{$keyword},grocery";
        @endphp
        <div class="m-cat-item {{ isset($activeCategory) && $activeCategory->id == $cat->id ? 'active' : '' }}" 
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
                // Randomize image slightly to avoid caching same image for all
                $rand = rand(1, 100); 
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
                    <div style="color:#6b7280; margin-top:4px;">{{ $activeCategory->tenMinProducts->count() }} items available</div>
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
                    <div class="product-card" 
                         data-subcat="{{ $product->subcategory?->name ?? 'Other' }}"
                         onclick="window.location.href='{{ route('product.details', $product->id) }}'">
                        
                        @if($product->discount > 0)
                            <div class="discount-badge">{{ $product->discount }}% OFF</div>
                        @endif

                        <div class="p-img-box">
                            <img src="{{ $product->image ? asset('product_images/'.$product->image) : 'https://via.placeholder.com/300' }}" 
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
                                    <span class="old-price">₹{{ $product->price + ($product->price * $product->discount / 100) }}</span>
                                @endif
                            </div>
                            <!-- Stop propagation to prevent card click -->
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
            <!-- Fallback if no category selected (though controller usually ensures one) -->
            <div class="empty-state">
                <h3>Please select a category to start shopping</h3>
            </div>
        @endif
    </main>

</div>

<script>
    // ========== SUBCATEGORY FILTERING ==========
    const subPills = document.querySelectorAll('.sub-pill');
    const cards = document.querySelectorAll('.product-card');
    const noResults = document.getElementById('noResults');

    subPills.forEach(pill => {
        pill.addEventListener('click', () => {
            // Visualize active state
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
        if(!input) return;
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
            if(visibleCount === 0 && term === '') {
                // If cleared, reset based on active subcategory
                const activeSub = document.querySelector('.sub-pill.active').dataset.sub;
                subPills.forEach(p => { if(p.dataset.sub === activeSub) p.click(); });
            }
        });
    });

    // ========== ADD TO CART ==========
    async function addToCart(productId, btn) {
        if(btn.disabled) return;
        
        const originalText = btn.innerText;
        btn.innerText = "•";
        btn.disabled = true;
        btn.style.width = "60px"; // Prevent layout jump

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
                // Update badge
                const badge = document.getElementById('cartCountBadge');
                if(badge) badge.innerText = data.cart_count;
                
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

>>>>>>> a9bc5205ec22eeddf3f4ac9a1a0d9385b463a0cb
</body>
</html>
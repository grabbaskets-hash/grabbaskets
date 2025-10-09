<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'grabbaskets') }}</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('asset/images/grabbasket.jpg') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        /* Navbar */
        .navbar-brand i {
            margin-right: 5px;
        }

        /* Sidebar Filters */
        .filter-card {
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }

        .filter-card h5 {
            font-weight: 600;
            margin-bottom: 15px;
        }

        /* Product Cards */
        .product-card {
            background: #fff;
            border-radius: 8px;
            padding: 4px;
            margin-bottom: 8px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
            display: flex;
            gap: 4px;
            min-height: 80px;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .product-card:hover {
            transform: translateY(-2px) scale(0.97);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
        }

        .product-img {
            width: 10px;
            height: 10px;
            object-fit: cover;
            border-radius: 6px;
        }

        .price {
            font-size: 1.2rem;
            font-weight: 600;
            color: #d32f2f;
        }

        .old-price {
            text-decoration: line-through;
            color: #888;
            margin-left: 10px;
            font-size: 0.9rem;
        }

        .rating {
            background: #388e3c;
            color: #fff;
            font-size: 0.75rem;
            padding: 2px 6px;
            border-radius: 4px;
        }

        /* Search */
        .search-bar input {
            border-radius: 50px 0 0 50px;
        }

        .search-bar button {
            border-radius: 0 50px 50px 0;
            background-color: #ffb800;
            border: none;
        }

        .search-bar button:hover {
            background-color: #e6a600;
        }

       /* Footer Styles */
        footer { background-color: #343a40; color: #fff; width: 100%; }
        footer a { color: #fff; text-decoration: none; }
        footer a:hover { color: #ddd; }

        .footer-main-grid {
            display: grid;
            grid-template-columns: 1.2fr 1fr 1fr 1.2fr;
            gap: 3rem;
            align-items: start;
            max-width: 1200px;
            margin: 0 auto;
        }
        .follow{
            position: relative;
            left: -40px;
           
        }
        .para{
            font-size: 15px;
            top:15px;
            position:relative;
        }

        .brand-column { padding-left:0; margin-left:-0.5rem; }
        .brand-column h3, .brand-column p { margin-left:-3rem; }
        .quick-links-column, .support-column { padding: 0 1rem; }
        .follow-column { text-align: right; padding-right:0; }
        .follow-icons { display:flex; gap:0.9rem; justify-content:flex-end; }

        .bottom-bar { background-color:#212529; padding:10px 0; text-align:center; font-size:0.9rem; color:#ccc; }

        /* Tablet */
        @media (max-width: 991px) {
            .footer-main-grid { grid-template-columns: 1fr 1fr; gap:2.5rem; }
            .brand-column { grid-column:1; margin-left:-0.5rem; }
            .quick-links-column { grid-column:2; }
            .support-column { grid-column:1; }
            .follow-column { grid-column:2; text-align:right; }
        }

        /* Mobile */
        @media (max-width: 767px) {
            .footer-main-grid { grid-template-columns: 1fr; gap:2rem; }
            .brand-column { grid-column:1; margin-left:0; padding-left:0; }
            .quick-links-column, .support-column { padding:0; }
            .follow-column { text-align:left; padding-right:0; }
            .follow-icons { justify-content:flex-start; margin-top:1rem; }
        }

        /* Extra Small */
        @media (max-width: 575px) {
            .footer-main-grid { gap:1.5rem; }
            .brand-column h3 { font-size:1.25rem; }
            .brand-column p { font-size:0.813rem; }
            .follow-icons { flex-wrap:wrap; gap:0.75rem; }
        }
    </style>
</head>

<body>
    <x-back-button />


  <nav class="navbar navbar-expand-lg" style="background-color:rgb(30, 30, 55);">
  <div class="container-fluid d-flex justify-content-around align-items-center">

    <!-- Logo -->
    <a href="{{ url('/') }}" class="nav-link text-orange">
      <img src="{{ asset('asset/images/grabbasket.png') }}" alt="Logo" width="180px">
    </a>

    <!-- Search Bar -->
    <form class="d-none d-lg-flex mx-auto" role="search" style="width: 600px;">
      <input class="form-control me-2" type="search" placeholder="Search products,brands and more..." aria-label="Search">
      <button class="btn btn-outline-warning" type="submit">Search</button>
    </form>

    <!-- Right Side: Hello + Buttons -->
    <div class="d-flex align-items-center gap-2">

    <!-- Hello User -->
    <span class="d-none d-lg-inline" style="color:beige;">Hello, {{ optional(Auth::user())->name ?? 'Guest' }}</span>

      <!-- My Account Dropdown -->
      <div class="dropdown">
        <button class="btn btn-outline-warning btn-sm dropdown-toggle d-flex align-items-center gap-1" 
                type="button" id="accountDropdown" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="bi bi-person-circle"></i> My Account
        </button>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="accountDropdown" style="min-width: 220px;">
          <li><a class="dropdown-item" href="{{ url('/profile') }}"><i class="bi bi-person"></i> Profile</a></li>
          <li><a class="dropdown-item" href="{{  url('/cart')  }}"><i class="bi bi-cart"></i> Cart</a></li>
          <li><a class="dropdown-item" href="{{ route('buyer.dashboard') }}"><i class="bi bi-shop"></i> Shop</a></li>
          <li><a class="dropdown-item" href="{{  url('/wishlist') }}"><i class="bi bi-heart"></i> Wishlist</a></li>
          <li><a class="dropdown-item" href="{{ url('/') }}"><i class="bi bi-house"></i> Home</a></li>
        </ul>
      </div>

      <a href="{{ route('logout') }}" class="btn btn-outline-warning btn-sm d-flex align-items-center gap-1"
         onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class="bi bi-box-arrow-right"></i> Logout
      </a>
      <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
      </form>
    </div>
  </div>
</nav>

    <!-- Main Container -->
    <div class="container my-4">
        <div class="row">
            <!-- Sidebar Filters -->
            <div class="col-lg-3">
                <div class="filter-card">
                    <h5>Filters</h5>
                    <form method="GET">
                        <div class="mb-3">
                            <label>Min Price</label>
                            <input type="number" step="0.01" name="price_min" class="form-control"
                                value="{{ $filters['price_min'] ?? '' }}">
                        </div>
                        <div class="mb-3">
                            <label>Max Price</label>
                            <input type="number" step="0.01" name="price_max" class="form-control"
                                value="{{ $filters['price_max'] ?? '' }}">
                        </div>
                        <div class="mb-3">
                            <label>Min Discount (%)</label>
                            <input type="number" min="0" name="discount_min" class="form-control"
                                value="{{ $filters['discount_min'] ?? '' }}">
                        </div>
                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" name="free_delivery" value="1" {{ !empty($filters['free_delivery']) ? 'checked' : '' }}>
                            <label class="form-check-label">Free Delivery</label>
                        </div>
                        <button class="btn btn-primary w-100 mb-2">Apply Filters</button>
                        <a href="{{ url()->current() }}" class="btn btn-danger w-100">Clear</a>
                    </form>
                </div>
            </div>

            <!-- Product Listing -->
            <div class="col-lg-9">

                <!-- Gender-Based Product Suggestions -->
                @php
                    $user = Auth::user();
                    $suggested = collect();
                    if ($user && isset($user->sex)) {
                        $sex = strtolower($user->sex);
                        $suggested = $products->filter(function($prod) use ($sex) {
                            $cat = strtolower(optional($prod->category)->name ?? '');
                            if ($sex === 'female') {
                                return str_contains($cat, 'women') || str_contains($cat, 'beauty') || str_contains($cat, 'fashion');
                            } elseif ($sex === 'male') {
                                return str_contains($cat, 'men') || str_contains($cat, 'electronics') || str_contains($cat, 'sports');
                            }
                            return true;
                        })->take(6);
                    }
                @endphp
                @if($user && $suggested->count())
                <div class="mb-4">
                    <h4 class="fw-bold mb-3 text-primary"><i class="bi bi-stars"></i> Recommended for You</h4>
                    <div class="row row-cols-1 row-cols-md-3 row-cols-lg-3 g-3">
                        @foreach($suggested as $product)
                        <div class="col">
                            <div class="card h-100 product-card position-relative">
                                <!-- Wishlist Heart Button -->
                                <form method="POST" action="{{ route('wishlist.toggle') }}" class="position-absolute top-0 end-0 m-2" style="z-index:2;">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <button type="submit" class="btn btn-link p-0 border-0 bg-transparent">
                                        <i class="bi bi-heart{{ $product->isWishlistedBy(auth()->user()) ? '-fill text-danger' : '' }} fs-4"></i>
                                    </button>
                                </form>
                                <a href="{{ route('product.details', $product->id) }}" class="text-decoration-none text-dark d-block w-100 h-100" style="z-index:1;">
                                    <div class="flex-shrink-0 w-100 h-50 rounded-lg overflow-hidden border">
                                        @if ($product->image || $product->image_data)
                                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-100 h-100 object-cover"
                                                 onerror="this.src='https://via.placeholder.com/200?text=No+Image'">
                                        @else
                                            <div class="w-100 h-100 bg-gray-200 d-flex align-items-center justify-content-center">
                                                <span class="text-muted">No Image</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1 ms-2">
                                        <span class="block fw-bold fs-6">{{ $product->name }}</span>
                                        <div class="price mt-1">
                                            ₹{{ number_format($product->discount > 0 ? $product->price * (1 - $product->discount / 100) : $product->price, 2) }}
                                            @if($product->discount > 0)
                                                <span class="old-price text-muted">₹{{ number_format($product->price, 2) }}</span>
                                                <span class="badge bg-success ms-2">{{ $product->discount }}% off</span>
                                            @endif
                                        </div>
                                    </div>
                                </a>
                                <div class="mt-2 ms-2 d-flex gap-2 justify-content-between">
                                    @auth
                                        <form method="POST" action="{{ route('cart.add') }}" class="flex-grow-1">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <button type="submit" class="btn btn-warning btn-sm">Add to Cart</button>
                                        </form>
                                    @else
                                        <a href="{{ route('login') }}" class="btn btn-warning btn-sm flex-grow-1">Login to add</a>
                                    @endauth
                                    
                                    <!-- Share Button for Recommended Products -->
                                    <div class="dropdown">
                                        <button class="btn btn-outline-secondary btn-sm" type="button" data-bs-toggle="dropdown">
                                            <i class="bi bi-share"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#" onclick="shareProduct('{{ $product->id }}', 'whatsapp', '{{ $product->name }}', '{{ $product->price }}'); event.preventDefault();"><i class="bi bi-whatsapp text-success"></i> WhatsApp</a></li>
                                            <li><a class="dropdown-item" href="#" onclick="shareProduct('{{ $product->id }}', 'facebook', '{{ $product->name }}', '{{ $product->price }}'); event.preventDefault();"><i class="bi bi-facebook text-primary"></i> Facebook</a></li>
                                            <li><a class="dropdown-item" href="#" onclick="shareProduct('{{ $product->id }}', 'twitter', '{{ $product->name }}', '{{ $product->price }}'); event.preventDefault();"><i class="bi bi-twitter text-info"></i> Twitter</a></li>
                                            <li><a class="dropdown-item" href="#" onclick="shareProduct('{{ $product->id }}', 'copy', '{{ $product->name }}', '{{ $product->price }}'); event.preventDefault();"><i class="bi bi-link-45deg"></i> Copy Link</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Search & Sort -->
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                    <form method="GET" action="{{ url()->current() }}" class="search-bar d-flex me-2 mb-2"
                        style="width: 60%;">
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Search products..."
                            class="form-control px-3 py-2">
                        <button type="submit" class="btn btn-warning ms-2"><i class="bi bi-search"></i></button>
                    </form>

                    <form method="GET" class="d-flex align-items-center mb-2">
                        <label class="me-2 mb-0">Sort by:</label>
                        <select name="sort" class="form-select w-auto" onchange="this.form.submit()">
                            <option value="" {{ ($filters['sort'] ?? '') === '' ? 'selected' : '' }}>Relevance</option>
                            <option value="newest" {{ ($filters['sort'] ?? '') === 'newest' ? 'selected' : '' }}>Newest
                            </option>
                            <option value="price_asc" {{ ($filters['sort'] ?? '') === 'price_asc' ? 'selected' : '' }}>
                                Price: Low to High</option>
                            <option value="price_desc" {{ ($filters['sort'] ?? '') === 'price_desc' ? 'selected' : '' }}>
                                Price: High to Low</option>
                        </select>
                    </form>
                </div>

                <!-- Product Cards -->
                @forelse($products as $product)
                    <div class="product-card position-relative mb-3">
                        <!-- Wishlist Heart Button -->
                        <form method="POST" action="{{ route('wishlist.toggle') }}" class="position-absolute top-0 end-0 m-2" style="z-index:2;">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <button type="submit" class="btn btn-link p-0 border-0 bg-transparent">
                                <i class="bi bi-heart{{ $product->isWishlistedBy(auth()->user()) ? '-fill text-danger' : '' }} fs-4"></i>
                            </button>
                        </form>
                        <a href="{{ route('product.details', $product->id) }}" class="text-decoration-none text-dark d-block w-100 h-100" style="z-index:1;">
                            <!-- Product Image -->
                            <div class="flex-shrink-0 w-32 h-50 rounded-lg overflow-hidden border">
                                @if ($product->image || $product->image_data)
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                                        class="w-full h-full object-cover"
                                        onerror="this.src='https://via.placeholder.com/200?text=No+Image'">
                                @else
                                    <div class="w-full h-full bg-gray-200 d-flex align-items-center justify-content-center">
                                        <span class="text-muted">No Image</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Product Info -->
                            <div class="flex-grow-1 ms-3">
                                <span class="block fw-bold fs-5">{{ $product->name }}</span>

                                <p class="text-muted small mt-1 line-clamp-3">
                                    {{ $product->description }}
                                </p>

                                <div class="price mt-1">
                                    ₹{{ number_format($product->discount > 0 ? $product->price * (1 - $product->discount / 100) : $product->price, 2) }}
                                    @if($product->discount > 0)
                                        <span class="old-price text-muted">₹{{ number_format($product->price, 2) }}</span>
                                        <span class="badge bg-success ms-2">{{ $product->discount }}% off</span>
                                    @endif
                                </div>

                                <!-- Add to Cart Button -->
                                <div class="mt-2 d-flex gap-2 align-items-center">
                                    @auth
                                        <form method="POST" action="{{ route('cart.add') }}" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <button type="submit" class="btn btn-warning btn-sm">Add to Cart</button>
                                        </form>
                                    @else
                                        <a href="{{ route('login') }}" class="btn btn-warning btn-sm">Login to add</a>
                                    @endauth
                                    
                                    <!-- Share Button -->
                                    <div class="dropdown">
                                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" onclick="event.stopPropagation();">
                                            <i class="bi bi-share"></i> Share
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#" onclick="shareProduct('{{ $product->id }}', 'whatsapp', '{{ $product->name }}', '{{ $product->price }}'); event.preventDefault();"><i class="bi bi-whatsapp text-success"></i> WhatsApp</a></li>
                                            <li><a class="dropdown-item" href="#" onclick="shareProduct('{{ $product->id }}', 'facebook', '{{ $product->name }}', '{{ $product->price }}'); event.preventDefault();"><i class="bi bi-facebook text-primary"></i> Facebook</a></li>
                                            <li><a class="dropdown-item" href="#" onclick="shareProduct('{{ $product->id }}', 'twitter', '{{ $product->name }}', '{{ $product->price }}'); event.preventDefault();"><i class="bi bi-twitter text-info"></i> Twitter</a></li>
                                            <li><a class="dropdown-item" href="#" onclick="shareProduct('{{ $product->id }}', 'copy', '{{ $product->name }}', '{{ $product->price }}'); event.preventDefault();"><i class="bi bi-link-45deg"></i> Copy Link</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="alert alert-warning">No products found.</div>
                @endforelse

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $products->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Start -->
    <footer>
        <div class="container py-5">
            <div class="footer-main-grid">
                <!-- Brand Column -->
                <div class="brand-column">
                    <h3 class="mb-3"><img src="{{ asset('asset/images/grabbasket.png') }}" alt="Logo"  width="180px">
                    <p class="para">Your trusted online marketplace for quality products at the best prices.</p>
                </div>

                <!-- Quick Links -->
                <div class="quick-links-column">
                    <h4 class="mb-3">Quick Links</h4>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('buyer.dashboard') }}">Shop</a></li>
                        <li><a href="{{ route('seller.dashboard') }}">Seller</a></li>
                        <li><a href="{{ route('cart.index') }}">Cart</a></li>
                        <li><a href="{{ route('login') }}">Login</a></li>
                    </ul>
                </div>

                <!-- Support -->
                <div class="support-column">
                    <h4 class="mb-3">Customer Support</h4>
                    <ul class="list-unstyled">
                        <li><a href="#">Help Center</a></li>
                        <li><a href="#">FAQ</a></li>
                        <li><a href="#">Contact Us</a></li>
                    </ul>
                </div>

                <!-- Follow Us -->
                <div class="follow-column">
                    <h4 class="mb-3 follow"> Follow Us</h4>
                    <div class="follow-icons">
                        <a href="https://wa.me/1234567890" target="_blank" class="bg-secondary rounded-circle p-2">
                            <i class="bi bi-whatsapp text-white"></i>
                        </a>
                        <a href="https://instagram.com/yourprofile" target="_blank" class="bg-secondary rounded-circle p-2">
                            <i class="bi bi-instagram text-white"></i>
                        </a>
                        <a href="https://twitter.com/yourprofile" target="_blank" class="bg-secondary rounded-circle p-2">
                            <i class="bi bi-twitter text-white"></i>
                        </a>
                        <a href="https://facebook.com/yourprofile" target="_blank" class="bg-secondary rounded-circle p-2">
                            <i class="bi bi-facebook text-white"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="bottom-bar">
            &copy; 2025 grabbasket. All Rights Reserved.
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Share Functions for Product Listing
        function shareProduct(productId, platform, productName, price) {
            const baseUrl = window.location.origin;
            const productUrl = `${baseUrl}/product/${productId}`;
            const text = `Check out this amazing product: ${productName} - ₹${price} on grabbasket!`;
            
            switch(platform) {
                case 'whatsapp':
                    const whatsappUrl = `https://wa.me/?text=${encodeURIComponent(text + ' ' + productUrl)}`;
                    window.open(whatsappUrl, '_blank');
                    break;
                    
                case 'facebook':
                    const facebookUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(productUrl)}`;
                    window.open(facebookUrl, '_blank', 'width=600,height=400');
                    break;
                    
                case 'twitter':
                    const twitterUrl = `https://twitter.com/intent/tweet?text=${encodeURIComponent(text)}&url=${encodeURIComponent(productUrl)}`;
                    window.open(twitterUrl, '_blank', 'width=600,height=400');
                    break;
                    
                case 'copy':
                    navigator.clipboard.writeText(productUrl).then(function() {
                        // Show success feedback
                        const dropdown = event.target.closest('.dropdown');
                        const btn = dropdown.querySelector('button');
                        const originalHtml = btn.innerHTML;
                        btn.innerHTML = '<i class="bi bi-check text-success"></i>';
                        
                        setTimeout(function() {
                            btn.innerHTML = originalHtml;
                        }, 2000);
                    }).catch(function(err) {
                        alert('Failed to copy link. Please copy manually: ' + productUrl);
                    });
                    break;
            }
        }
    </script>
</body>

</html>
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
            border-radius: 12px;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
        }

        .card.h-100 {
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .card.h-100 .card-body {
            flex: 1 1 auto;
            display: flex;
            flex-direction: column;
        }

        .card.h-100 .card-body .mt-auto {
            margin-top: auto !important;
        }

        .product-img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            border-radius: 12px 12px 0 0;
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

        /* Store Card Enhancements */
        .hover-lift {
            transition: all 0.3s ease;
        }

        .hover-lift:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 30px rgba(12, 131, 31, 0.25) !important;
        }

        .hover-scale {
            transition: all 0.3s ease;
        }

        .hover-scale:hover {
            transform: scale(1.03);
            box-shadow: 0 8px 20px rgba(12, 131, 31, 0.3) !important;
        }

        .bg-success-subtle {
            background-color: rgba(25, 135, 84, 0.1);
        }

        .text-success {
            color: #198754 !important;
        }

        /* Responsive Grid Enhancements */
        @media (min-width: 1200px) {
            .col-xl-3 {
                flex: 0 0 25%;
                max-width: 25%;
            }
        }

        @media (min-width: 992px) and (max-width: 1199.98px) {
            .col-lg-4 {
                flex: 0 0 33.333333%;
                max-width: 33.333333%;
            }
        }

        @media (min-width: 768px) and (max-width: 991.98px) {
            .col-md-6 {
                flex: 0 0 50%;
                max-width: 50%;
            }
        }

        @media (min-width: 576px) and (max-width: 767.98px) {
            .col-sm-6 {
                flex: 0 0 50%;
                max-width: 50%;
            }
        }

        /* Mobile Responsive */
        @media (max-width: 767px) {
            .filter-card {
                margin-bottom: 15px;
            }

            .search-bar {
                width: 100% !important;
                margin-bottom: 10px;
            }

            .card-body {
                padding: 15px !important;
            }

            .card-title {
                font-size: 0.95rem !important;
                min-height: 38px !important;
            }

            .card-text {
                font-size: 0.85rem !important;
                min-height: 50px !important;
            }

            .price-section {
                padding: 8px !important;
            }

            .price-section span {
                font-size: 1.1rem !important;
            }
        }

        @media (max-width: 575px) {
            .col-12 {
                flex: 0 0 100%;
                max-width: 100%;
            }

            .navbar-brand img {
                width: 140px !important;
            }

            .container {
                padding-left: 10px;
                padding-right: 10px;
            }

            .row.g-4 {
                gap: 1rem !important;
            }

            .card {
                margin-bottom: 1rem;
            }
        }

        .footer {
            font-size: 0.9rem;
        }

        .footer h6 {
            font-size: 0.95rem;
            margin-bottom: 1rem;
            color: #fff;
        }

        .footer a:hover {
            color: #fff;
            text-decoration: underline;
        }

        .footer .social-icons i {
            font-size: 1.3rem;
            transition: color 0.3s;
        }

        .footer .social-icons i:hover {
            color: #fff;
        }

        .follow {
            position: relative;
            left: -40px;

        }

        .para {
            font-size: 15px;
            top: 15px;
            position: relative;
        }

        .brand-column {
            padding-left: 0;
            margin-left: -0.5rem;
        }

        .brand-column h3,
        .brand-column p {
            margin-left: -3rem;
        }

        .quick-links-column,
        .support-column {
            padding: 0 1rem;
        }

        .follow-column {
            text-align: right;
            padding-right: 0;
        }

        .follow-icons {
            display: flex;
            gap: 0.9rem;
            justify-content: flex-end;
        }

        .bottom-bar {
            background-color: #212529;
            padding: 10px 0;
            text-align: center;
            font-size: 0.9rem;
            color: #ccc;
        }

        /* Tablet */
        @media (max-width: 991px) {
            .footer-main-grid {
                grid-template-columns: 1fr 1fr;
                gap: 2.5rem;
            }

            .brand-column {
                grid-column: 1;
                margin-left: -0.5rem;
            }

            .quick-links-column {
                grid-column: 2;
            }

            .support-column {
                grid-column: 1;
            }

            .follow-column {
                grid-column: 2;
                text-align: right;
            }
        }

        /* Mobile */
        @media (max-width: 767px) {
            .footer-main-grid {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .brand-column {
                grid-column: 1;
                margin-left: 0;
                padding-left: 0;
            }

            .quick-links-column,
            .support-column {
                padding: 0;
            }

            .follow-column {
                text-align: left;
                padding-right: 0;
            }

            .follow-icons {
                justify-content: flex-start;
                margin-top: 1rem;
            }
        }

        /* Extra Small */
        @media (max-width: 575px) {
            .footer-main-grid {
                gap: 1.5rem;
            }

            .brand-column h3 {
                font-size: 1.25rem;
            }

            .brand-column p {
                font-size: 0.813rem;
            }

            .follow-icons {
                flex-wrap: wrap;
                gap: 0.75rem;
            }
        }
    </style>
</head>

<body>
    <x-back-button />


  <nav class="navbar navbar-expand-lg navbar-dark" style="background-color:rgb(30, 30, 55);">
    <div class="container-fluid">
 
      <a href="{{ url('/') }}" class="navbar-brand d-flex align-items-center">
        <img src="{{ asset('asset/images/logo-image.png') }}" alt="Logo" width="150" class="me-2">
      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
        aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarContent">

        <ul class="navbar-nav ms-auto align-items-lg-center">
          <li class="nav-item d-none d-lg-block me-2">
            <span class="text-light small">Hello, {{ Auth::user()->name }}</span>
          </li>

          <li class="nav-item dropdown">
            <a class="btn btn-outline-warning btn-sm dropdown-toggle d-flex align-items-center gap-1" href="/cart"
              aria-expanded="false">
              <i class="bi bi-person-circle"></i> Cart
            </a>

          </li>

          <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
            <a href="{{ route('logout') }}" class="btn btn-outline-warning btn-sm d-flex align-items-center gap-1 w-100"
              onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
              <i class="bi bi-box-arrow-right"></i> Logout
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
              @csrf
            </form>
          </li>
        </ul>
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

                <!-- Search Results Header -->
                @if(request('search'))
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center border-bottom pb-3">
                        <div>
                            <h4 class="fw-bold mb-1">
                                <i class="bi bi-search"></i> Search Results
                            </h4>
                            <p class="text-muted mb-0">
                                Found <strong>{{ $products->total() }}</strong> result{{ $products->total() !== 1 ? 's' : '' }} for "<strong>{{ request('search') }}</strong>"
                            </p>
                        </div>
                        <div>
                            <select class="form-select" onchange="window.location.href = this.value;">
                                <option value="">Sort by Relevance</option>
                                <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_asc']) }}" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                                <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_desc']) }}" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                                <option value="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}" {{ request('sort') === 'newest' ? 'selected' : '' }}>Newest First</option>
                                <option value="{{ request()->fullUrlWithQuery(['sort' => 'discount']) }}" {{ request('sort') === 'discount' ? 'selected' : '' }}>Highest Discount</option>
                            </select>
                        </div>
                    </div>
                </div>
                @endif

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
                                        @php
                                        $categoryName = optional($product->category)->name ?? '';
                                        $unsplashQuery = trim($product->name . ' ' . $categoryName . ' colorful');
                                        $unsplashQuery = $unsplashQuery ?: 'product colorful';
                                        @endphp
                                        @if ($product->image || $product->image_data)
                                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-100 h-100 object-cover"
                                            onerror="
                                                    if (!this.dataset.fallback) {
                                                        this.dataset.fallback = '1';
                                                        this.src = 'https://source.unsplash.com/400x400/?{{ urlencode($unsplashQuery) }}';
                                                    } else {
                                                        this.src = 'https://source.unsplash.com/400x400/?product,shopping,retail,colorful';
                                                    }
                                                 ">
                                        @else
                                        <img src="https://source.unsplash.com/400x400/?{{ urlencode($unsplashQuery) }}" alt="{{ $product->name }}" class="w-100 h-100 object-cover">
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

                <!-- Store Cards (if any stores match the search) -->
                @if(isset($matchedStores) && $matchedStores->isNotEmpty())
                <div class="mb-5">
                    <div class="alert alert-success border-0 shadow-sm mb-4" style="background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%); border-left: 5px solid #0C831F !important;">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-shop-window fs-3 text-success me-3"></i>
                            <div>
                                <h5 class="alert-heading mb-1 fw-bold">🎉 Store Found!</h5>
                                <p class="mb-0">We found {{ $matchedStores->count() }} store(s) matching your search "{{ request('q') }}"</p>
                            </div>
                        </div>
                    </div>

                    <div class="row g-4">
                        @foreach($matchedStores as $store)
                        <div class="col-md-6">
                            <div class="card h-100 shadow-lg hover-lift" style="border-radius: 16px; border: 3px solid #0C831F; overflow: hidden; transition: all 0.3s ease;">
                                <!-- Store Header with Gradient -->
                                <div class="card-header text-white py-3" style="background: linear-gradient(135deg, #0C831F, #0A6917); border: none;">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h4 class="mb-1 fw-bold">
                                                <i class="bi bi-shop-window"></i> {{ $store->store_name ?? $store->name }}
                                            </h4>
                                            @if($store->store_name && $store->name && $store->store_name !== $store->name)
                                            <small class="opacity-75">
                                                <i class="bi bi-person-circle"></i> Owned by {{ $store->name }}
                                            </small>
                                            @endif
                                        </div>
                                        <div class="text-center">
                                            <div class="badge bg-white text-success fs-6 px-3 py-2" style="border-radius: 12px;">
                                                <i class="bi bi-box-seam"></i>
                                                <strong>{{ $store->product_count ?? 0 }}</strong>
                                                <div style="font-size: 0.75rem;">Products</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Store Body with Details -->
                                <div class="card-body p-4">
                                    <div class="row g-3">
                                        <!-- Location Information -->
                                        @if($store->store_address || $store->city || $store->state)
                                        <div class="col-12">
                                            <div class="d-flex align-items-start mb-2">
                                                <div class="bg-danger bg-opacity-10 rounded-circle p-2 me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                                    <i class="bi bi-geo-alt-fill text-danger fs-5"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1 fw-bold text-dark">Location</h6>
                                                    @if($store->store_address)
                                                    <p class="mb-1 text-muted small">{{ $store->store_address }}</p>
                                                    @endif
                                                    @if($store->city || $store->state || $store->pincode)
                                                    <p class="mb-0 text-muted small">
                                                        <i class="bi bi-pin-map"></i>
                                                        {{ implode(', ', array_filter([$store->city, $store->state, $store->pincode])) }}
                                                    </p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        @endif

                                        <!-- Contact Information -->
                                        @if($store->store_contact || $store->email)
                                        <div class="col-12">
                                            <div class="d-flex align-items-start mb-2">
                                                <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                                    <i class="bi bi-telephone-fill text-primary fs-5"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1 fw-bold text-dark">Contact</h6>
                                                    @if($store->store_contact)
                                                    <p class="mb-1 text-muted small">
                                                        <i class="bi bi-phone"></i> {{ $store->store_contact }}
                                                    </p>
                                                    @endif
                                                    @if($store->email)
                                                    <p class="mb-0 text-muted small">
                                                        <i class="bi bi-envelope"></i> {{ $store->email }}
                                                    </p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        @endif

                                        <!-- Business Information -->
                                        @if($store->gst_number || $store->gift_option)
                                        <div class="col-12">
                                            <div class="d-flex align-items-start mb-2">
                                                <div class="bg-warning bg-opacity-10 rounded-circle p-2 me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                                    <i class="bi bi-file-text-fill text-warning fs-5"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1 fw-bold text-dark">Business Info</h6>
                                                    @if($store->gst_number)
                                                    <p class="mb-1 text-muted small">
                                                        <i class="bi bi-receipt"></i> GST: <strong>{{ $store->gst_number }}</strong>
                                                    </p>
                                                    @endif
                                                    @if($store->gift_option)
                                                    <span class="badge bg-success-subtle text-success">
                                                        <i class="bi bi-gift"></i> Gift Wrapping Available
                                                    </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Store Footer with Action Button -->
                                <div class="card-footer bg-light border-0 p-4">
                                    @if(isset($store->user_id))
                                    <a href="{{ route('store.catalog', $store->user_id) }}"
                                        class="btn btn-lg w-100 text-white fw-bold shadow-sm hover-scale"
                                        style="background: linear-gradient(135deg, #0C831F, #0A6917); border-radius: 12px; padding: 14px; transition: all 0.3s ease;">
                                        <i class="bi bi-grid-3x3-gap-fill me-2"></i>
                                        View Complete Catalog
                                        <i class="bi bi-arrow-right ms-2"></i>
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Divider -->
                    <hr class="my-5" style="border-top: 2px dashed #dee2e6;">

                    <div class="text-center mb-4">
                        <h5 class="text-muted">
                            <i class="bi bi-box-seam"></i> Products from this store
                        </h5>
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

                <!-- Product Cards Grid -->
                <div class="row g-4">
                    @forelse($products as $product)
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                        <div class="card h-100 position-relative" style="border-radius: 12px; overflow: hidden; transition: transform 0.3s ease, box-shadow 0.3s ease;">
                            <!-- Wishlist Heart Button -->
                            <form method="POST" action="{{ route('wishlist.toggle') }}" class="position-absolute top-0 end-0 m-2" style="z-index:10;">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <button type="submit" class="btn btn-link p-0 border-0 bg-white rounded-circle" style="width: 40px; height: 40px; box-shadow: 0 2px 8px rgba(0,0,0,0.15);">
                                    <i class="bi bi-heart{{ $product->isWishlistedBy(auth()->user()) ? '-fill text-danger' : '' }} fs-5"></i>
                                </button>
                            </form>

                            <!-- Product Image -->
                            <div style="position: relative; overflow: hidden; height: 250px;">
                                @php
                                $categoryName = optional($product->category)->name ?? '';
                                $unsplashQuery = trim($product->name . ' ' . $categoryName . ' colorful');
                                $unsplashQuery = $unsplashQuery ?: 'product colorful';
                                @endphp
                                <a href="{{ route('product.details', $product->id) }}">
                                    @if ($product->image || $product->image_data)
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                                        class="card-img-top" style="height: 250px; object-fit: cover; transition: transform 0.3s;"
                                        onmouseover="this.style.transform='scale(1.05)'"
                                        onmouseout="this.style.transform='scale(1)'"
                                        onerror="
                                                if (!this.dataset.fallback) {
                                                    this.dataset.fallback = '1';
                                                    this.src = 'https://source.unsplash.com/400x400/?{{ urlencode($unsplashQuery) }}';
                                                } else {
                                                    this.src = 'https://source.unsplash.com/400x400/?product,shopping,retail,colorful';
                                                }
                                            ">
                                    @else
                                    <img src="https://source.unsplash.com/400x400/?{{ urlencode($unsplashQuery) }}" alt="{{ $product->name }}" class="card-img-top" style="height: 250px; object-fit: cover;">
                                    @endif
                                </a>

                                @if($product->discount > 0)
                                <div style="position: absolute; top: 10px; left: 10px; background: linear-gradient(135deg, #FF4444, #FF6B00); color: white; padding: 6px 12px; border-radius: 20px; font-weight: bold; font-size: 0.85rem; box-shadow: 0 4px 12px rgba(255, 68, 68, 0.4);">
                                    {{ $product->discount }}% OFF
                                </div>
                                @endif
                            </div>

                            <!-- Product Body -->
                            <div class="card-body d-flex flex-column">
                                <h6 class="card-title fw-bold mb-2" style="color: #333; font-size: 1rem; line-height: 1.4; min-height: 44px;">
                                    <a href="{{ route('product.details', $product->id) }}" class="text-decoration-none text-dark">
                                        {{ \Illuminate\Support\Str::limit($product->name, 50) }}
                                    </a>
                                </h6>

                                <p class="card-text small text-muted mb-3" style="line-height: 1.5; min-height: 60px;">
                                    {{ \Illuminate\Support\Str::limit($product->description, 80) }}
                                </p>

                                <div class="mt-auto">
                                    <!-- Price Section -->
                                    <div class="price-section mb-3" style="background: linear-gradient(135deg, rgba(255, 215, 0, 0.1), rgba(255, 107, 0, 0.1)); padding: 10px; border-radius: 10px; border: 1px solid rgba(255, 107, 0, 0.2);">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div>
                                                <span class="fw-bold d-block" style="color: #FF6B00; font-size: 1.3rem;">
                                                    ₹{{ number_format($product->discount > 0 ? $product->price * (1 - $product->discount / 100) : $product->price, 2) }}
                                                </span>
                                                @if($product->discount > 0)
                                                <small class="text-muted text-decoration-line-through">₹{{ number_format($product->price, 2) }}</small>
                                                @endif
                                            </div>
                                            @if($product->discount > 0)
                                            <div class="text-end">
                                                <small class="badge bg-success">Save ₹{{ number_format($product->price * ($product->discount / 100), 2) }}</small>
                                            </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="d-grid gap-2">
                                        @auth
                                        <form method="POST" action="{{ route('cart.add') }}" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <button type="submit" class="btn btn-warning w-100 fw-semibold" style="border-radius: 8px;">
                                                <i class="bi bi-cart-plus"></i> Add to Cart
                                            </button>
                                        </form>
                                        @else
                                        <a href="{{ route('login') }}" class="btn btn-warning w-100 fw-semibold" style="border-radius: 8px;">
                                            <i class="bi bi-box-arrow-in-right"></i> Login to Buy
                                        </a>
                                        @endauth

                                        <!-- Share Button -->
                                        <div class="dropdown">
                                            <button class="btn btn-outline-secondary btn-sm w-100 dropdown-toggle" type="button" data-bs-toggle="dropdown" style="border-radius: 8px;">
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
                        </div>
                    </div>
                    @empty
                    <div class="col-12">
                        <div class="alert alert-warning">No products found.</div>
                    </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $products->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Start -->
    <footer class="footer bg-dark text-white pt-5 pb-4 mt-5">
        <div class="container">
            <div class="row">

                <!-- About -->
                <div class="col-md-2 col-6 mb-3">
                    <h6 class="fw-bold text-uppercase">About</h6>
                    <ul class="list-unstyled small">
                        <li><a href="#" class="text-white-50 text-decoration-none">Contact Us</a></li>
                        <li><a href="#" class="text-white-50 text-decoration-none">About Us</a></li>
                        <li><a href="#" class="text-white-50 text-decoration-none">Careers</a></li>
                        <li><a href="#" class="text-white-50 text-decoration-none">Grabbasket Stories</a></li>
                        <li><a href="#" class="text-white-50 text-decoration-none">Corporate Info</a></li>
                    </ul>
                </div>

                <!-- Group Companies -->
                <div class="col-md-2 col-6 mb-3">
                    <h6 class="fw-bold text-uppercase">Quick Links</h6>
                    <ul class="list-unstyled small">
                        <li><a href="/cart" class="text-white-50 text-decoration-none">Cart</a></li>
                        <li><a href="/wishlist" class="text-white-50 text-decoration-none">Wishlist</a></li>
                        <li><a href="/orders/track" class="text-white-50 text-decoration-none">Orders</a></li>
                    </ul>
                </div>

                <!-- Help -->
                <div class="col-md-2 col-6 mb-3">
                    <h6 class="fw-bold text-uppercase">Help</h6>
                    <ul class="list-unstyled small">
                        <li><a href="#" class="text-white-50 text-decoration-none">Payments</a></li>
                        <li><a href="#" class="text-white-50 text-decoration-none">Shipping</a></li>
                        <li><a href="#" class="text-white-50 text-decoration-none">Cancellation & Returns</a></li>
                        <li><a href="#" class="text-white-50 text-decoration-none">FAQ</a></li>
                    </ul>
                </div>

                <!-- Policy -->
                <div class="col-md-2 col-6 mb-3">
                    <h6 class="fw-bold text-uppercase">Consumer Policy</h6>
                    <ul class="list-unstyled small">
                        <li><a href="#" class="text-white-50 text-decoration-none">Return Policy</a></li>
                        <li><a href="#" class="text-white-50 text-decoration-none">Terms of Use</a></li>
                        <li><a href="#" class="text-white-50 text-decoration-none">Security</a></li>
                        <li><a href="#" class="text-white-50 text-decoration-none">Privacy</a></li>
                        <li><a href="#" class="text-white-50 text-decoration-none">Sitemap</a></li>
                    </ul>
                </div>

                <!-- Address -->
                <div class="col-md-4 col-12 mb-3">
                    <h6 class="fw-bold text-uppercase">Registered Office Address</h6>
                    <p class="text-white-50 small mb-1">
                        Swivel IT and Training Institute<br>
                        Mahatma Gandhi Nagar Rd, near Annai Therasa English School,<br>
                        MRR Nagar, Palani Chettipatti,,<br>
                        Theni, 625531, TamilNadu, India.
                    </p>
                    <!-- <p class="text-white-50 small mb-0">CIN: U51109KA2012PTC066107</p> -->
                    <p class="text-white-50 small mb-0">Contact us: <a href="tel:+91 8300504230" class="text-white-50 text-decoration-none">+91 8300504230</a></p>
                </div>
            </div>

            <hr class="border-secondary">

            <!-- Bottom Row -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center text-white-50 small">
                <div class="mb-2 mb-md-0">
                    © 2025 grabbaskets.com
                </div>
                <div class="social-icons">
                    <a href="#" class="text-white-50 me-3"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="text-white-50 me-3"><i class="bi bi-twitter"></i></a>
                    <a href="#" class="text-white-50 me-3"><i class="bi bi-youtube"></i></a>
                    <a href="https://www.instagram.com/grab_baskets/" class="text-white-50"><i class="bi bi-instagram"></i></a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Share Functions for Product Listing
        function shareProduct(productId, platform, productName, price) {
            const baseUrl = window.location.origin;
            const productUrl = `${baseUrl}/product/${productId}`;
            const text = `Check out this amazing product: ${productName} - ₹${price} on grabbasket!`;

            switch (platform) {
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

        // AJAX Wishlist Toggle - Prevents redirect and updates UI
        document.addEventListener('DOMContentLoaded', function() {
            // Get all wishlist forms
            const wishlistForms = document.querySelectorAll('form[action*="wishlist.toggle"]');

            wishlistForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault(); // Prevent form submission/redirect

                    const formData = new FormData(form);
                    const button = form.querySelector('button');
                    const icon = button.querySelector('i');

                    // Send AJAX request
                    fetch(form.action, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': formData.get('_token'),
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                product_id: formData.get('product_id')
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Toggle heart icon
                                if (data.in_wishlist) {
                                    // Added to wishlist - show filled red heart
                                    icon.classList.remove('bi-heart');
                                    icon.classList.add('bi-heart-fill', 'text-danger');
                                } else {
                                    // Removed from wishlist - show empty heart
                                    icon.classList.remove('bi-heart-fill', 'text-danger');
                                    icon.classList.add('bi-heart');
                                }

                                // Optional: Show brief success message (can be removed if you don't want notifications)
                                // You can uncomment below for visual feedback
                                /*
                                const toast = document.createElement('div');
                                toast.className = 'position-fixed bottom-0 end-0 p-3';
                                toast.style.zIndex = '9999';
                                toast.innerHTML = `
                                    <div class="toast show" role="alert">
                                        <div class="toast-body bg-success text-white">
                                            ${data.message}
                                        </div>
                                    </div>
                                `;
                                document.body.appendChild(toast);
                                setTimeout(() => toast.remove(), 2000);
                                */
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            // Optionally show error message
                        });
                });
            });
        });
    </script>
</body>

</html>
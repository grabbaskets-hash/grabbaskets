<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>GrabBasket — Indian Menu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --accent: #FF6B00;
            --rating: #28a745;
            --bg: #f5f4f7;
        }

        body {
            font-family: "Poppins", sans-serif;
            background: var(--bg);
            margin: 0;
        }

        .nav-app {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            padding: 15px 25px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .brand {
            font-weight: 700;
            font-size: 1.6rem;
            color: var(--accent);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .location-input,
        .search-input {
            border-radius: 30px;
            border: 1px solid #ddd;
            padding: 8px 16px;
            color: #212529;
            caret-color: var(--accent);
        }

        .btn-outline-secondary,
        .btn-primary {
            transition: 0.3s;
        }

        .btn-primary {
            background: var(--accent);
            border: none;
        }

        .btn-primary:hover {
            background: #e75e00;
        }

        .hamburger-btn {
            display: none;
            font-size: 1.4rem;
            background: none;
            border: none;
            cursor: pointer;
            margin-right: 12px;
        }

        .nav-items {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        @media (max-width: 767px) {
            .nav-items {
                width: 100%;
                flex-direction: column;
                align-items: flex-start;
                padding-top: 10px;
                border-top: 1px solid #eee;
                background: white;
                margin-top: 10px;
                display: none;
            }

            .nav-items.active {
                display: flex !important;
            }

            .location-input,
            .search-input {
                width: 100% !important;
                font-size: 14px;
            }

            .nav-items form {
                width: 100%;
            }
        }

        @media (min-width: 768px) {
            .search-input {
                width: 600px !important;
            }
        }

        .page-wrapper {
            max-width: 1250px;
            margin: auto;
            padding: 20px 20px 20px 20px;
        }

        @media(max-width:767px) {
            .page-wrapper {
                padding-top: 80px;
            }
        }

        .category-wrapper {
            position: relative;
            padding: 12px 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .categories-scroll {
            display: flex;
            gap: 16px;
            overflow-x: auto;
            padding: 16px 6px;
            width: 85%;
        }

        .categories-scroll::-webkit-scrollbar {
            display: none;
        }

        .category-card {
            min-width: 90px;
            text-align: center;
            cursor: pointer;
        }

        .category-card img {
            width: 85px;
            height: 85px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #fff;
            box-shadow: 0 6px 14px rgba(0, 0, 0, 0.08);
        }

        .category-card small {
            display: block;
            margin-top: 6px;
            font-weight: 600;
            color: #444;
        }

        .controls-row {
            display: flex;
            gap: 10px;
            align-items: center;
            margin: 22px 0;
        }

        .filter-pill {
            background: var(--accent);
            color: #fff;
            padding: 10px 16px;
            border-radius: 22px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .food-card {
            background: #fff;
            border-radius: 14px;
            padding: 12px;
            box-shadow: 0 10px 28px rgba(0, 0, 0, 0.06);
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .food-card img {
            width: 100%;
            height: 170px;
            object-fit: cover;
            border-radius: 12px;
            margin-bottom: 10px;
        }

        .food-title {
            font-weight: 700;
            margin-top: 10px;
            color: #111;
            font-size: 1rem;
            flex-grow: 1;
        }

        .food-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
            font-weight: 700;
        }

        .price {
            color: var(--accent);
        }

        .rating {
            color: var(--rating);
        }

        .food-card>*:last-child {
            margin-bottom: 0;
        }

        @media(max-width:575px) {
            .col-sm-responsive {
                flex: 0 0 50%;
                max-width: 50%;
            }
        }

        @media(min-width:576px)and (max-width:991px) {
            .col-sm-responsive {
                flex: 0 0 33%;
                max-width: 33%;
            }
        }

        @media(min-width:992px) {
            .col-sm-responsive {
                flex: 0 0 25%;
                max-width: 25%;
            }
        }

        @media(max-width:767px) {
            .hamburger-btn {
                display: block;
                margin-right: 12px;
            }

            .nav-items {
                display: none;
                flex-direction: column;
                gap: 12px;
                width: 100%;
                padding: 15px 0;
                border-top: 1px solid #eee;
                background: white;
                position: absolute;
                top: 100%;
                left: 0;
                z-index: 999;
            }
        }
    </style>
</head>

<body>

    <div class="nav-app" id="navBar">
        <div class="d-flex justify-content-between align-items-center flex-wrap w-100">
            <div class="d-flex align-items-center">
                <button class="hamburger-btn d-md-none" id="hamburgerBtn">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <div class="brand"><i class="fa-solid fa-basket-shopping"></i> GrabBasket</div>
            </div>

            <div class="nav-items" id="navItems">



                <div class="d-md-flex d-none gap-2 align-items-center">
                    @auth
                    <!-- Show user name + cart icon when logged in -->
                    <div class="dropdown">
                        <a class="text-decoration-none d-flex align-items-center gap-1 text-dark" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fa-solid fa-user"></i>
                            <span>{{ Auth::user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('customer.food.cart') }}"><i class="fa-solid fa-cart-shopping"></i> My Cart</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="fa-solid fa-right-from-bracket"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                    <a href="{{ route('customer.food.cart') }}" class="btn btn-outline-secondary position-relative">
                        <i class="fa-solid fa-cart-shopping"></i>
                        @if(session('cart_count', 0) > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            {{ session('cart_count') }}
                        </span>
                        @endif
                    </a>
                    @else
                    <!-- Show Login/Signup when guest -->
                    <a href="{{ route('login') }}" class="btn btn-outline-secondary">
                        <i class="fa-solid fa-user"></i> Login
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-primary">
                        <i class="fa-solid fa-user-plus"></i> Signup
                    </a>
                    @endauth
                </div>

                <!-- Mobile -->
                <div class="d-md-none d-flex flex-column gap-3 mt-3">
                    <div class="d-flex align-items-center gap-2 w-100">
                        <i class="fa-solid fa-location-dot"></i>
                        <input class="location-input" placeholder="Enter your location" style="width:100%;" />
                    </div>

                    <!-- ✅ Fixed: Search form now wraps button -->
                    <form method="GET" class="d-flex flex-column w-100" style="gap:8px;">
                        <div class="d-flex align-items-center gap-2 w-100">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            <input name="search" class="search-input" placeholder="Search food or item..." value="{{ request('search') }}" style="width:100%;" />
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Search</button>
                    </form>

                    @auth
                    <!-- Mobile: User menu + Cart -->
                    <div class="w-100">
                        <div class="text-center mb-2">Hello, {{ Auth::user()->name }}!</div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('customer.food.cart') }}" class="btn btn-outline-secondary w-50">
                                <i class="fa-solid fa-cart-shopping"></i> Cart
                                @if(session('cart_count', 0) > 0)
                                <span class="badge bg-danger ms-1">{{ session('cart_count') }}</span>
                                @endif
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="w-50">
                                @csrf
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fa-solid fa-right-from-bracket"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                    @else
                    <!-- Mobile: Login/Signup -->
                    <div class="d-flex gap-2 w-100">
                        <a href="{{ route('login') }}" class="btn btn-outline-secondary w-50">
                            <i class="fa-solid fa-user"></i> Login
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-primary w-50">
                            <i class="fa-solid fa-user-plus"></i> Signup
                        </a>
                    </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <div class="page-wrapper">
        <!-- Categories -->
        <div class="my-3">
            <h5 class="mb-2">Categories</h5>
            <div class="category-wrapper">
                <div class="categories-scroll">
                    
                    @foreach($foodCategories as $cat)
@php
    $categoryName = strtolower($cat->category);

    $isActive = request('category') === $cat->category;

    $urlParams = ['category' => $cat->category];
    if (!request('search')) {
        if (request('veg') !== null) $urlParams['veg'] = request('veg');
        if (request('sort')) $urlParams['sort'] = request('sort');
    }

    $url = route('customer.food.index', $urlParams);

    // Category image mapping
    $categoryImages = [
        'dessert' => asset('images/categories/dessert.jpeg'),
        'beverage' => asset('images/categories/beverage.jpeg'),
        'appetizer' => asset('images/categories/appetizer.jpeg'),
        'main_course' => asset('images/categories/main_course.jpeg'),
        'snack' => asset('images/categories/snack.jpeg'),
        'salad' => asset('images/categories/salad.jpeg'),
        'soup' => asset('images/categories/soup.jpeg'),
        'staters'=>asset('images/categories/staters.jpeg'),
        'rice'=>asset('images/categories/rice.jpeg'),
        'seafood'=>asset('images/categories/seafood.jpeg'),
        'chicken'=>asset('images/categories/chicken.jpeg'),
        'mutton'=>asset('images/categories/mutton.jpeg'),
        'burger'=>asset('images/categories/burger.jpeg'),
        'pizza'=>asset('images/categories/pizza.jpeg'),

    ];

    $image = $categoryImages[$categoryName] 
             ?? asset('images/categories/default.png');
@endphp

<a href="{{ $url }}" class="category-card text-decoration-none">
    <img src="{{ $image }}" alt="{{ $cat->category }}" />
    <small class="{{ $isActive ? 'text-primary fw-bold' : 'text-muted' }}">
        {{ ucwords($cat->category) }}
    </small>
</a>
@endforeach


                </div>
            </div>
        </div>

        <!-- Controls -->
        <div class="d-flex justify-content-between align-items-center">
            <div class="controls-row">
                @if(request('category') || request('search') || request('sort') || request('veg'))
                <a href="{{ route('customer.food.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fa fa-arrow-left"></i> All
                </a>
                @endif

                <div class="d-flex align-items-center gap-2">
                    <form method="GET" style="display:inline;" id="vegForm">
                        <input type="hidden" name="search" value="{{ request('search') }}" />
                        <input type="hidden" name="category" value="{{ request('category') }}" />
                        <input type="hidden" name="sort" value="{{ request('sort') }}" />
                        <select name="veg" onchange="this.form.submit()" class="filter-pill" style="background:#444;">
                            <option value="">All Food</option>
                            <option value="1" {{ request('veg') === '1' ? 'selected' : '' }}>Veg Only</option>
                        </select>
                    </form>

                    <form method="GET" style="display:inline;" id="sortForm">
                        <input type="hidden" name="search" value="{{ request('search') }}" />
                        <input type="hidden" name="category" value="{{ request('category') }}" />
                        <input type="hidden" name="veg" value="{{ request('veg') }}" />
                        <select name="sort" onchange="this.form.submit()" class="filter-pill">
                            <option value="">Sort</option>
                            <option value="costLow" {{ request('sort') === 'costLow' ? 'selected' : '' }}>Cost: Low to High</option>
                            <option value="costHigh" {{ request('sort') === 'costHigh' ? 'selected' : '' }}>Cost: High to Low</option>
                            <option value="ratingHigh" {{ request('sort') === 'ratingHigh' ? 'selected' : '' }}>Ratings: High to Low</option>
                        </select>
                    </form>
                </div>
            </div>
            <div id="itemCount">Showing {{ $foods->count() }} items</div>
        </div>

        <!-- Food Items Heading -->
        <h5 class="mt-3">
            @if(request('search'))
            Search Results for "{{ request('search') }}"
            @elseif(request('category'))
            {{ ucwords(str_replace('_', ' ', request('category'))) }}
            @else
            All Food Items
            @endif
        </h5>

        <!-- Food Items Container -->
        <div id="foodItemsContainer" class="d-flex flex-wrap">
            @forelse($foods as $food)
            <div class="col-sm-responsive p-2">
                <a href="{{ route('customer.food.details', $food->id) }}" class="text-decoration-none">
                    <div class="food-card">
                        @if(!empty($food->images) && is_array($food->images))
                        <img src="{{ $food->images[0] }}" onerror="this.src='https://via.placeholder.com/480x300?text=No+Image'">
                        @else
                        <img src="https://via.placeholder.com/480x300?text=No+Image" />
                        @endif
                        <div class="food-title">{{ $food->name }}</div>
                        <div class="mt-1">
                            @if($food->food_type === 'veg')
                            <span class="badge bg-success">Veg</span>
                            @elseif($food->food_type === 'non-veg')
                            <span class="badge bg-danger">Non-Veg</span>
                            @endif
                        </div>
                        <div class="food-meta">
                            <span class="price">₹{{ number_format($food->discounted_price ?? $food->price, 0) }}</span>
                            <span class="rating">
                                <i class="fa-solid fa-star"></i> {{ number_format($food->rating ?? 4.0, 1) }}
                            </span>
                        </div>
                        <div>
                            <i class="fa-solid fa-location-dot"></i>
                            {{ $food->hotelOwner ? $food->hotelOwner->name : 'Unknown Hotel' }}
                        </div>
                    </div>
                </a>
            </div>
            @empty
            <div class="col-12 text-center py-5">No food items found.</div>
            @endforelse
        </div>
    </div>

    <script>
        // Just toggle hamburger menu
        document.getElementById('hamburgerBtn').addEventListener('click', () => {
            document.getElementById('navItems').classList.toggle('active');
        });
    </script>

</body>

</html>
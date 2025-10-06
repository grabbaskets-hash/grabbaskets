<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Buyer Dashboard - Grabbasket</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('asset/images/grabbasket.jpg') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        body {
            background-color: #f8f9fa;
        }
        
        .dashboard-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem 0;
        }
        
        .dashboard-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            transition: transform 0.2s;
        }
        
        .dashboard-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
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
                <span class="d-none d-lg-inline" style="color:beige;">Hello, {{ Auth::user()->name }}</span>

                <!-- Notification Bell -->
                <x-notification-bell />

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
                        <li><a class="dropdown-item" href="{{ route('seller.dashboard') }}"><i class="bi bi-briefcase"></i> Seller</a></li>
                        <li><a class="dropdown-item" href="{{  url('/wishlist') }}"><i class="bi bi-heart"></i> Wishlist</a></li>
                        <li><a class="dropdown-item" href="{{ route('tracking.form') }}"><i class="bi bi-truck"></i> Track Package</a></li>
                        <li><a class="dropdown-item" href="{{ route('notifications.index') }}"><i class="bi bi-bell"></i> Notifications</a></li>
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

    <!-- Dashboard Header -->
    <div class="dashboard-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="mb-2">Welcome back, {{ Auth::user()->name }}!</h1>
                    <p class="mb-0 opacity-75">Explore amazing products and manage your shopping experience</p>
                </div>
                <div class="col-md-4 text-end">
                    <i class="bi bi-bag-heart" style="font-size: 4rem; opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Dashboard Content -->
    <div class="container py-5">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Quick Stats -->
        <div class="row g-4 mb-5">
            <div class="col-md-3">
                <div class="card dashboard-card">
                    <div class="card-body text-center">
                        <div class="stat-icon bg-primary text-white mx-auto mb-3">
                            <i class="bi bi-cart"></i>
                        </div>
                        <h5 class="card-title">My Cart</h5>
                        <p class="text-muted">{{ Auth::user()->cartItems()->count() }} items</p>
                        <a href="{{ url('/cart') }}" class="btn btn-outline-primary btn-sm">View Cart</a>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card dashboard-card">
                    <div class="card-body text-center">
                        <div class="stat-icon bg-danger text-white mx-auto mb-3">
                            <i class="bi bi-heart"></i>
                        </div>
                        <h5 class="card-title">Wishlist</h5>
                        <p class="text-muted">{{ Auth::user()->wishlists()->count() }} items</p>
                        <a href="{{ url('/wishlist') }}" class="btn btn-outline-danger btn-sm">View Wishlist</a>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card dashboard-card">
                    <div class="card-body text-center">
                        <div class="stat-icon bg-success text-white mx-auto mb-3">
                            <i class="bi bi-bag-check"></i>
                        </div>
                        <h5 class="card-title">My Orders</h5>
                        <p class="text-muted">{{ Auth::user()->orders()->count() }} orders</p>
                        <a href="#" class="btn btn-outline-success btn-sm">View Orders</a>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card dashboard-card">
                    <div class="card-body text-center">
                        <div class="stat-icon bg-warning text-white mx-auto mb-3">
                            <i class="bi bi-bell"></i>
                        </div>
                        <h5 class="card-title">Notifications</h5>
                        <p class="text-muted">{{ Auth::user()->notifications()->whereNull('read_at')->count() }} unread</p>
                        <a href="{{ route('notifications.index') }}" class="btn btn-outline-warning btn-sm">View All</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row g-4">
            <div class="col-md-8">
                <div class="card dashboard-card">
                    <div class="card-header bg-transparent">
                        <h5 class="mb-0"><i class="bi bi-lightning-charge"></i> Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <a href="{{ route('buyer.dashboard') }}" class="btn btn-outline-primary w-100 py-3">
                                    <i class="bi bi-shop"></i><br>
                                    <small>Browse Products</small>
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="{{ url('/cart') }}" class="btn btn-outline-success w-100 py-3">
                                    <i class="bi bi-cart-check"></i><br>
                                    <small>Checkout Cart</small>
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="{{ url('/profile') }}" class="btn btn-outline-info w-100 py-3">
                                    <i class="bi bi-person-gear"></i><br>
                                    <small>Edit Profile</small>
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="{{ route('seller.dashboard') }}" class="btn btn-outline-warning w-100 py-3">
                                    <i class="bi bi-briefcase"></i><br>
                                    <small>Seller Dashboard</small>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card dashboard-card">
                    <div class="card-header bg-transparent">
                        <h5 class="mb-0"><i class="bi bi-person-circle"></i> Profile</h5>
                    </div>
                    <div class="card-body text-center">
                        <div class="bg-primary text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; font-size: 2rem;">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <h6>{{ Auth::user()->name }}</h6>
                        <p class="text-muted small">{{ Auth::user()->email }}</p>
                        <a href="{{ url('/profile') }}" class="btn btn-primary btn-sm">Edit Profile</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

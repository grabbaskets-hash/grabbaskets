<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .order-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s ease;
        }

        .order-card:hover {
            transform: translateY(-2px);
            box-shadow: 0px 6px 14px rgba(0, 0, 0, 0.08);
        }

        .status-badge {
            font-size: 0.875rem;
            padding: 0.5rem 1rem;
            border-radius: 20px;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-confirmed {
            background-color: #d1ecf1;
            color: #0c5460;
        }

        .status-paid {
            background-color: #d4edda;
            color: #155724;
        }

        .status-shipped {
            background-color: #e2e3e5;
            color: #383d41;
        }

        .status-delivered {
            background-color: #d4edda;
            color: #155724;
        }

        .status-cancelled {
            background-color: #f8d7da;
            color: #721c24;
        }

        .progress-steps {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 20px 0;
        }

        .step {
            flex: 1;
            text-align: center;
            position: relative;
        }

        .step::after {
            content: '';
            position: absolute;
            top: 20px;
            left: 60%;
            width: 80%;
            height: 2px;
            background-color: #dee2e6;
            z-index: 1;
        }

        .step:last-child::after {
            display: none;
        }

        .step-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #dee2e6;
            color: #6c757d;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            position: relative;
            z-index: 2;
        }

        .step.active .step-icon {
            background-color: #28a745;
            color: white;
        }

        .step.active::after {
            background-color: #28a745;
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
                <input class="form-control me-2" type="search" placeholder="Search products,brands and more..."
                    aria-label="Search">
                <button class="btn btn-outline-warning" type="submit">Search</button>
            </form>

            <!-- Right Side: Hello + Buttons -->
            <div class="d-flex align-items-center gap-2">

                <!-- Hello User -->
                <span class="d-none d-lg-inline" style="color:beige;">Hello, {{ Auth::user()->name }}</span>

                <!-- My Account Dropdown -->
                <div class="dropdown">
                    <button class="btn btn-outline-warning btn-sm dropdown-toggle d-flex align-items-center gap-1"
                        type="button" id="accountDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle"></i> My Account
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="accountDropdown"
                        style="min-width: 220px;">
                        <li><a class="dropdown-item" href="{{ url('/profile') }}"><i class="bi bi-person"></i>
                                Profile</a></li>
                        <li><a class="dropdown-item" href="{{  url('/cart')  }}"><i class="bi bi-cart"></i> Cart</a>
                        </li>
                        <li><a class="dropdown-item" href="{{ route('buyer.dashboard') }}"><i class="bi bi-shop"></i>
                                Shop</a></li>
                        <li><a class="dropdown-item" href="{{ route('seller.dashboard') }}"><i
                                    class="bi bi-briefcase"></i> Seller</a></li>
                        <li><a class="dropdown-item" href="{{  url('/wishlist') }}"><i class="bi bi-heart"></i>
                                Wishlist</a></li>
                        <li><a class="dropdown-item" href="{{ url('/') }}"><i class="bi bi-house"></i>
                                Home</a></li>
                    </ul>
                </div>

            

                <!-- Logout Button -->
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

    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <h3 class="mb-4"><i class="bi bi-box-seam text-primary"></i> My Orders</h3>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if($orders->isEmpty())
                    <div class="text-center py-5">
                        <i class="bi bi-box" style="font-size: 5rem; color: #ccc;"></i>
                        <h4 class="mt-3 text-muted">No orders found</h4>
                        <p class="text-muted">Start shopping to see your orders here!</p>
                        <a href="{{ route('buyer.dashboard') }}" class="btn btn-primary">Start Shopping</a>
                    </div>
                @else
                    @foreach($orders as $order)
                        <div class="order-card">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div>
                                            <h5 class="fw-semibold mb-1">{{ $order->product->name }}</h5>
                                            <small class="text-muted">Order #{{ $order->id }} •
                                                {{ $order->created_at->format('d M Y, h:i A') }}</small>
                                        </div>
                                        <span class="status-badge status-{{ $order->status }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </div>

                                    <div class="mb-3">
                                        <p class="mb-1"><strong>Seller:</strong> {{ $order->sellerUser->name ?? 'N/A' }}</p>
                                        <p class="mb-1"><strong>Amount:</strong> ₹{{ number_format($order->amount, 2) }}</p>
                                        <p class="mb-1"><strong>Payment:</strong> {{ ucfirst($order->payment_method) }}</p>
                                        @if($order->product->gift_option === 'yes')
                                            <p class="mb-1 text-warning"><i class="bi bi-gift"></i> Gift option available</p>
                                        @endif
                                    </div>

                                    <!-- Order Progress -->
                                    <div class="progress-steps">
                                        <div
                                            class="step {{ in_array($order->status, ['pending', 'paid', 'confirmed', 'shipped', 'delivered']) ? 'active' : '' }}">
                                            <div class="step-icon">
                                                <i class="bi bi-check-circle"></i>
                                            </div>
                                            <small>Ordered</small>
                                        </div>
                                        <div
                                            class="step {{ in_array($order->status, ['confirmed', 'shipped', 'delivered']) ? 'active' : '' }}">
                                            <div class="step-icon">
                                                <i class="bi bi-check2-circle"></i>
                                            </div>
                                            <small>Confirmed</small>
                                        </div>
                                        <div
                                            class="step {{ in_array($order->status, ['shipped', 'delivered']) ? 'active' : '' }}">
                                            <div class="step-icon">
                                                <i class="bi bi-truck"></i>
                                            </div>
                                            <small>Shipped</small>
                                        </div>
                                        <div class="step {{ $order->status === 'delivered' ? 'active' : '' }}">
                                            <div class="step-icon">
                                                <i class="bi bi-house-check"></i>
                                            </div>
                                            <small>Delivered</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4 text-end">
                                    @if($order->product->image)
                                        <img src="{{ asset('storage/' . $order->product->image) }}"
                                            alt="{{ $order->product->name }}" class="img-fluid rounded mb-3"
                                            style="max-height: 120px; object-fit: cover;">
                                    @endif

                                    <div class="d-grid gap-2">
                                        <a href="{{ route('orders.show', $order) }}" class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-eye"></i> View Details
                                        </a>

                                        @if(in_array($order->status, ['delivered']))
                                            <a href="{{ route('product.details', $order->product->id) }}"
                                                class="btn btn-outline-success btn-sm">
                                                <i class="bi bi-star"></i> Rate & Review
                                            </a>
                                        @endif

                                        @if($order->status === 'delivered')
                                            <button class="btn btn-outline-secondary btn-sm">
                                                <i class="bi bi-arrow-repeat"></i> Buy Again
                                            </button>
                                        @endif

                                        @if(in_array($order->status, ['pending', 'paid', 'confirmed']))
                                            <form method="POST" action="{{ route('orders.cancel', $order) }}"
                                                onsubmit="return confirm('Are you sure you want to cancel this order?');">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                                    <i class="bi bi-x-circle"></i> Cancel Order
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Tracking Number -->
                            @if($order->tracking_number)
                                <div class="row mt-2">
                                    <div class="col-12">
                                        <span class="badge bg-success"><i class="bi bi-truck"></i> Tracking #:
                                            {{ $order->tracking_number }}</span>
                                    </div>
                                </div>
                            @endif
                            <!-- Delivery Address -->
                            <div class="row mt-3 pt-3 border-top">
                                <div class="col-12">
                                    <small class="text-muted">
                                        <i class="bi bi-geo-alt"></i>
                                        <strong>Delivery Address:</strong>
                                        {{ $order->delivery_address }}, {{ $order->delivery_city }},
                                        {{ $order->delivery_state }} - {{ $order->delivery_pincode }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
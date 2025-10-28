<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Cart</title>
  <link rel="icon" type="image/png" style="width:1500px;" href="{{ asset('build/assets/icon (3).png') }}">


  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    html,
    body {
      height: 100%;
      margin: 0;
      display: flex;
      flex-direction: column;
      background-color: #f8f9fa;
    }

    /* Main content wrapper */
    main {
      flex: 1;
    }

    /* Navbar */
    .navbar {
      padding: 0.8rem 1rem;
    }

    .navbar-brand {
      font-size: 1.4rem;
      letter-spacing: 1px;
    }

    /* Product Image */
    .product-img {
      width: 150px;
      height: 150px;
      border-radius: 12px;
      object-fit: cover;
      margin-right: 20px;
      border: 1px solid #eee;
    }

    /* Cart Items */
    .cart-item {
      background: white;
      border-radius: 12px;
      padding: 20px;
      margin-bottom: 20px;
      box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.05);
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .cart-item:hover {
      transform: translateY(-3px);
      box-shadow: 0px 6px 14px rgba(0, 0, 0, 0.08);
    }

    /* Cart Summary */
    .cart-summary {
      background: white;
      border-radius: 12px;
      padding: 25px;
      box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
      position: sticky;
      top: 90px;
    }

    .cart-summary h5 {
      font-weight: 600;
    }

    .cart-summary strong.text-danger {
      font-size: 1.3rem;
    }

    .btn-lg {
      padding: 0.8rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
      .product-img {
        width: 100px;
        height: 100px;
      }

      .cart-item {
        flex-direction: column;
        align-items: flex-start !important;
      }

      .cart-item .text-end {
        margin-top: 10px;
        width: 100%;
      }
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
    @media (max-width: 767px) {
  .navbar .btn,
  .navbar .dropdown-menu {
    width: 100%;
  }

  .navbar .dropdown-menu {
    margin-top: 0.5rem;
  }
}

  </style>
</head>

<body>
  <x-back-button />

<nav class="navbar navbar-expand-lg navbar-dark" style="background-color:rgb(30, 30, 55);">
  <div class="container-fluid">
    <!-- Logo -->
    <a href="{{ url('/') }}" class="navbar-brand d-flex align-items-center">
      <img src="{{ asset('build/assets/logo-image.png') }}" alt="Logo" width="150" class="me-2">
    </a>

    <!-- Mobile Toggle Button -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
      aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Navbar Content -->
    <div class="collapse navbar-collapse" id="navbarContent">
      <!-- Search Bar -->
      <form class="d-flex mx-lg-auto my-3 my-lg-0" role="search" style="max-width: 600px; width: 100%;">
        <input class="form-control me-2" type="search" placeholder="Search products, brands and more..."
          aria-label="Search">
        <button class="btn btn-outline-warning" type="submit">Search</button>
      </form>

      <!-- Right Side -->
      <ul class="navbar-nav ms-auto align-items-lg-center">
        <li class="nav-item d-none d-lg-block me-2">
          <span class="text-light small">Hello, {{ Auth::user()->name }}</span>
        </li>

        <!-- Account Dropdown -->
        <li class="nav-item dropdown">
          <a class="btn btn-outline-warning btn-sm dropdown-toggle d-flex align-items-center gap-1" href="#"
            id="accountDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-person-circle"></i> My Account
          </a>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="accountDropdown" style="min-width: 220px;">
            <li><a class="dropdown-item" href="{{ url('/profile') }}"><i class="bi bi-person"></i> Profile</a></li>
            <li><a class="dropdown-item" href="{{ url('/cart') }}"><i class="bi bi-cart"></i> Cart</a></li>
            <li><a class="dropdown-item" href="{{ route('buyer.dashboard') }}"><i class="bi bi-shop"></i> Shop</a></li>
            <li><a class="dropdown-item" href="{{ url('/orders/track') }}"><i class="bi bi-briefcase"></i> My Orders</a></li>
            <li><a class="dropdown-item" href="{{ url('/wishlist') }}"><i class="bi bi-heart"></i> Wishlist</a></li>
            <li><a class="dropdown-item" href="{{ url('/') }}"><i class="bi bi-house"></i> Dashboard</a></li>
          </ul>
        </li>

        <!-- Clear Cart Button -->
        @if($items->count())
        <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
          <form method="POST" action="{{ route('cart.clear') }}">
            @csrf
            @method('DELETE')
            <button class="btn btn-outline-warning btn-sm d-flex align-items-center gap-1 w-100">
              <i class="bi bi-trash"></i> Clear
            </button>
          </form>
        </li>
        @endif

        <!-- Logout -->
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


  <!-- Main content -->
  <main>
    <div class="container mt-4">
      <div class="row">
        <!-- Cart Items -->
        <div class="col-lg-8">
          <h3 class="mb-4"><i class="bi bi-cart-check-fill text-primary"></i> My Shopping Cart</h3>

          @if(session('success'))
          <div class="alert alert-success">{{ session('success') }}</div>
          @endif

          @if(!$items->count())
          <p class="text-muted">Your cart is empty.</p>
          @else
          @foreach($items as $item)
          <div class="cart-item d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
              @if(optional($item->product)->image)
              <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="product-img">
              @endif

              <div>
                <h5 class="fw-semibold mb-2">{{ optional($item->product)->name ?? 'Product' }}</h5>
                <p class="text-muted mb-2">
                  Price: <strong>₹{{ number_format($item->price, 2) }}</strong> |
                  Discount: <strong>{{ $item->discount ? $item->discount . '%' : '-' }}</strong> |
                  Delivery:
                  <strong>{{ $item->delivery_charge ? '₹' . number_format($item->delivery_charge, 2) : 'Free' }}</strong>
                </p>

                <form method="POST" action="{{ route('cart.update', $item) }}" class="d-flex align-items-center">
                  @csrf
                  @method('PATCH')
                  <input type="number" min="1" max="10" name="quantity" value="{{ $item->quantity }}"
                    class="form-control form-control-sm me-2" style="width: 80px;">
                  <button class="btn btn-sm btn-outline-primary">Update</button>
                </form>
              </div>
            </div>

            <div class="text-end">
              @php
              $price = (float) $item->price;
              $disc = (float) $item->discount;
              $qty = (int) $item->quantity;
              $delivery = (float) $item->delivery_charge;
              $base = $price * $qty;
              $less = $disc > 0 ? ($base * ($disc / 100)) : 0;
              $lineTotal = $base - $less + $delivery;
              @endphp
              <h5 class="text-danger fw-bold">₹{{ number_format($lineTotal, 2) }}</h5>
              <form method="POST" action="{{ route('cart.remove', $item) }}">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-outline-danger mt-2"><i class="bi bi-x-circle"></i> Remove</button>
              </form>
              <form method="POST" action="{{ route('cart.moveToWishlist', $item) }}" class="mt-2">
                @csrf
                <button class="btn btn-sm btn-outline-warning"><i class="bi bi-heart"></i> Move to Wishlist</button>
              </form>
            </div>
          </div>
          @endforeach
          @endif
        </div>

        <!-- Cart Summary -->
        <div class="col-lg-4">
          @if($items->count())
          <div class="cart-summary">
            <h5 class="mb-3">Order Summary</h5>
            <div class="d-flex justify-content-between mb-2">
              <span>Subtotal</span>
              <span>₹{{ number_format($totals['subtotal'], 2) }}</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
              <span>Discount</span>
              <span>-₹{{ number_format($totals['discountTotal'], 2) }}</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
              <span>Delivery</span>
              <span>₹{{ number_format($totals['deliveryTotal'], 2) }}</span>
            </div>
            <hr>
            <div class="d-flex justify-content-between">
              <strong>Total</strong>
              <strong class="text-danger">₹{{ number_format($totals['total'], 2) }}</strong>
            </div>
            <div class="d-grid gap-2 mt-4">
              <a href="{{ route('cart.checkout.page') }}"
                class="btn btn-success btn-lg fw-semibold shadow-sm d-flex align-items-center justify-content-center gap-2">
                <i class="bi bi-bag-check-fill"></i> Checkout
              </a>

              <a href="{{ route('buyer.dashboard') }}"
                class="btn btn-outline-primary btn-lg fw-semibold shadow-sm d-flex align-items-center justify-content-center gap-2">
                <i class="bi bi-arrow-left-circle"></i> Continue Shopping
              </a>
            </div>
          </div>
          @endif
        </div>
      </div>
    </div>
  </main>

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

  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
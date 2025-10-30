<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Wishlist</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <style>
    body {
      background-color: #f8f9fa;
    }

    .wishlist-item {
      background: white;
      border-radius: 12px;
      padding: 20px;
      margin-bottom: 20px;
      box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.05);
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .wishlist-item:hover {
      transform: translateY(-3px);
      box-shadow: 0px 6px 14px rgba(0, 0, 0, 0.08);
    }

    .product-img {
      width: 150px;
      height: 150px;
      border-radius: 12px;
      object-fit: cover;
      border: 1px solid #eee;
    }

    .heart-icon {
      color: #e74c3c;
      font-size: 1.5rem;
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
      <!-- Logo -->
      <a href="{{ url('/') }}" class="navbar-brand d-flex align-items-center">
      <img src="{{ asset('asset/images/logo-image.png') }}" alt="Logo" width="150" class="me-2">
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
              <li><a class="dropdown-item" href="{{ url('/orders/track') }}"><i class="bi bi-briefcase"></i> My Order</a></li>
              <li><a class="dropdown-item" href="{{ url('/wishlist') }}"><i class="bi bi-heart"></i> Wishlist</a></li>
              <li><a class="dropdown-item" href="{{ url('/') }}"><i class="bi bi-house"></i> Dashboard</a></li>
            </ul>
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

  <div class="container mt-4">
    <div class="row">
      <div class="col-12">
        <h3 class="mb-4"><i class="bi bi-heart-fill text-danger"></i> My Wishlist ({{ $wishlists->count() }} items)</h3>

        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($wishlists->isEmpty())
        <div class="text-center py-5">
          <i class="bi bi-heart" style="font-size: 5rem; color: #ccc;"></i>
          <h4 class="mt-3 text-muted">Your wishlist is empty</h4>
          <p class="text-muted">Add items you love to keep track of them!</p>
          <a href="{{ route('buyer.dashboard') }}" class="btn btn-primary">Continue Shopping</a>
        </div>
        @else
        <div class="row">
          @foreach($wishlists as $wishlist)
          <div class="col-lg-6 col-xl-4 mb-4">
            <div class="wishlist-item h-100">
              <div class="position-relative">
                <a href="{{ route('product.details', $wishlist->product->id) }}" class="d-block">
                  @if($wishlist->product && ($wishlist->product->image || $wishlist->product->image_data))
                  <img src="{{ $wishlist->product->image_url }}"
                    alt="{{ $wishlist->product->name }}"
                    class="product-img w-100 mb-3" style="cursor: pointer; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                  @else
                  <div class="product-img w-100 mb-3 d-flex align-items-center justify-content-center bg-light" style="cursor: pointer;">
                    <i class="bi bi-image text-muted" style="font-size: 2rem;"></i>
                  </div>
                  @endif
                </a>

                <button class="btn btn-link position-absolute top-0 end-0 p-1 remove-wishlist"
                  data-product-id="{{ $wishlist->product->id }}" style="z-index: 10;">
                  <i class="bi bi-heart-fill heart-icon"></i>
                </button>
              </div>

              <h5 class="fw-semibold mb-2">
                <a href="{{ route('product.details', $wishlist->product->id) }}" class="text-decoration-none text-dark">
                  {{ $wishlist->product->name }}
                </a>
              </h5>

              <div class="mb-2">
                <span class="badge bg-secondary">{{ $wishlist->product->category->name ?? 'N/A' }}</span>
                @if($wishlist->product->subcategory)
                <span class="badge bg-info">{{ $wishlist->product->subcategory->name }}</span>
                @endif
              </div>

              <div class="mb-3">
                <h4 class="text-primary fw-bold">₹{{ number_format($wishlist->product->price, 2) }}</h4>
                @if($wishlist->product->discount > 0)
                <small class="text-success">{{ $wishlist->product->discount }}% off</small>
                @endif
              </div>

              @if($wishlist->product->gift_option === 'yes')
              <div class="mb-2">
                <span class="badge bg-warning text-dark">
                  <i class="bi bi-gift"></i> Gift Option Available
                </span>
              </div>
              @endif

              <div class="d-grid gap-2">
                <button class="btn btn-success move-to-cart"
                  data-product-id="{{ $wishlist->product->id }}">
                  <i class="bi bi-cart-plus"></i> Add to Cart
                </button>
                <a href="{{ route('product.details', $wishlist->product->id) }}"
                  class="btn btn-outline-primary">
                  <i class="bi bi-eye"></i> View Details
                </a>
              </div>
            </div>
          </div>
          @endforeach
        </div>
        @endif
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Setup CSRF token for all AJAX requests
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Remove from wishlist
    document.querySelectorAll('.remove-wishlist').forEach(button => {
      button.addEventListener('click', function() {
        const productId = this.getAttribute('data-product-id');

        fetch('{{ route("wishlist.remove") }}', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
              product_id: productId
            })
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              location.reload(); // Refresh to update the page
            } else {
              alert(data.message || 'Failed to remove item');
            }
          })
          .catch(error => {
            console.error('Error:', error);
            alert('An error occurred');
          });
      });
    });

    // Move to cart
    document.querySelectorAll('.move-to-cart').forEach(button => {
      button.addEventListener('click', function() {
        const productId = this.getAttribute('data-product-id');

        fetch('{{ route("wishlist.moveToCart") }}', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
              product_id: productId
            })
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              alert(data.message);
              location.reload(); // Refresh to update the page
            } else {
              alert(data.message || 'Failed to move item to cart');
            }
          })
          .catch(error => {
            console.error('Error:', error);
            alert('An error occurred');
          });
      });
    });
  </script>

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
  
</body>

</html>
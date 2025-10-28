<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $product->name }} | MyShop</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body {
      font-family: 'Playfair Display', serif;
      background: #f8f6f1;
      color: #2c2c2c;
    }

    /* Navbar */
    .navbar {
      background: #0a1a3f;
      padding: 0.8rem 1rem;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
    }

    .navbar-brand {
      font-weight: 700;
      font-size: 1.6rem;
      color: #d4af37 !important;
      letter-spacing: 1px;
    }

    .navbar .nav-link {
      color: #fff !important;
      margin-left: 1rem;
      transition: 0.3s;
    }

    .navbar .nav-link:hover {
      color: #d4af37 !important;
    }

    /* Product section */
    .product-section {
      background: #fff;
      border-radius: 20px;
      padding: 2rem;
      box-shadow: 0 6px 25px rgba(0, 0, 0, 0.1);
    }

    .product-image {
      border-radius: 16px;
      border: 3px solid #d4af37;
      max-height: 450px;
      object-fit: contain;
      background: #fffdf8;
      padding: 10px;
    }

    .product-title {
      font-size: 2.2rem;
      font-weight: 700;
      color: #0a1a3f;
    }

    .price {
      font-size: 2rem;
      font-weight: bold;
      color: #d4af37;
    }

    .old-price {
      text-decoration: line-through;
      color: #888;
      font-size: 1rem;
      margin-left: 10px;
    }

    .discount {
      color: #e91e63;
      font-weight: 600;
      margin-left: 8px;
    }

    /* Buttons */
    .btn-gold {
      background: #d4af37;
      color: #0a1a3f;
      font-weight: 600;
      border-radius: 50px;
      transition: 0.3s;
      border: none;
    }

    .btn-gold:hover {
      background: #b5942a;
      color: #fff;
    }

    .btn-dark {
      background: #0a1a3f;
      border-radius: 50px;
    }

    .btn-dark:hover {
      background: #142b6f;
    }

    /* Tabs */
    .nav-pills .nav-link {
      border-radius: 50px;
      font-weight: 600;
      color: #0a1a3f;
    }

    .nav-pills .nav-link.active {
      background: #d4af37;
      color: #fff;
    }

    /* Reviews */
    .review-card {
      background: rgba(255, 255, 255, 0.8);
      border-radius: 16px;
      padding: 1rem;
      backdrop-filter: blur(6px);
      border: 1px solid #eee;
    }

    .review-user {
      font-weight: 600;
      color: #0a1a3f;
    }

    .review-stars i {
      color: #d4af37;
    }

    /* Other products */
    .other-products .card {
      border: none;
      border-radius: 12px;
      box-shadow: 0 4px 14px rgba(0, 0, 0, 0.08);
      transition: transform 0.2s;
    }

    .other-products .card:hover {
      transform: translateY(-6px);
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
    }

    .other-products h6 {
      font-weight: 600;
      color: #0a1a3f;
    }
    
    /* Footer Styles */
    footer {
      background-color: #343a40;
      color: #fff;
      width: 100%;
      margin-top: auto;
    }

    footer a {
      color: #fff;
      text-decoration: none;
    }

    footer a:hover {
      color: #ddd;
    }

    .footer-main-grid {
      display: grid;
      grid-template-columns: 1.2fr 1fr 1fr 1.2fr;
      gap: 3rem;
      align-items: start;
      max-width: 1200px;
      margin: 0 auto;
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
     .bottom-bar {
      background-color: #212529;
      padding: 10px 0;
      text-align: center;
      font-size: 0.9rem;
      color: #ccc;
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


  <!-- Product Section -->
  <div class="container py-5">
    <div class="row g-4 product-section">
      <!-- Image -->
      <div class="col-lg-6 text-center">
        @if($product->image || $product->image_data)
          <img src="{{ $product->original_image_url }}" class="product-image img-fluid" alt="{{ $product->name }}" 
               onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
          <div class="mt-3">
            <a href="{{ $product->original_image_url }}" target="_blank" rel="noopener" class="btn btn-outline-secondary btn-sm">
              <i class="bi bi-box-arrow-up-right"></i> View original
            </a>
          </div>
          <div style="display: none; padding: 40px; background: #f8f9fa; border-radius: 16px; color: #6c757d;">
            <i class="bi bi-image" style="font-size: 3rem;"></i>
            <p class="mt-2 mb-0">Image not available</p>
          </div>
        @else
          <div style="padding: 40px; background: #f8f9fa; border-radius: 16px; color: #6c757d;">
            <i class="bi bi-image" style="font-size: 3rem;"></i>
            <p class="mt-2 mb-0">No image available</p>
          </div>
        @endif
      </div>

      <!-- Details -->
      <div class="col-lg-6">
        <h1 class="product-title">{{ $product->name }}</h1>
        <p class="mb-3">
          <span class="badge bg-dark">{{ optional($product->category)->name }}</span>
          <span class="badge bg-secondary">{{ optional($product->subcategory)->name }}</span>
          <span class="badge bg-success">Stock: {{ $product->stock }}</span>
        </p>

        <p class="price">
          @if($product->discount > 0)
            ₹{{ number_format($product->price * (1 - $product->discount / 100), 2) }}
            <span class="old-price">₹{{ number_format($product->price, 2) }}</span>
            <span class="discount">{{ $product->discount }}% off</span>
          @else
            ₹{{ number_format($product->price, 2) }}
          @endif
        </p>
        <p class="text-muted">Delivery:
          {{ $product->delivery_charge ? '₹' . number_format($product->delivery_charge, 2) : 'Free' }}
        </p>

        <!-- Share Options -->
        <div class="mb-3">
          <h6 class="mb-2">Share this product:</h6>
          <div class="d-flex gap-2 flex-wrap">
            <!-- WhatsApp Share -->
            <button class="btn btn-success btn-sm" onclick="shareOnWhatsApp()">
              <i class="bi bi-whatsapp"></i> WhatsApp
            </button>
            
            <!-- Facebook Share -->
            <button class="btn btn-primary btn-sm" onclick="shareOnFacebook()">
              <i class="bi bi-facebook"></i> Facebook
            </button>
            
            <!-- Twitter Share -->
            <button class="btn btn-info btn-sm" onclick="shareOnTwitter()">
              <i class="bi bi-twitter"></i> Twitter
            </button>
            
            <!-- Copy Link -->
            <button class="btn btn-secondary btn-sm" onclick="copyLink()">
              <i class="bi bi-link-45deg"></i> Copy Link
            </button>
            
            <!-- Email Share -->
            <button class="btn btn-warning btn-sm" onclick="shareViaEmail()">
              <i class="bi bi-envelope"></i> Email
            </button>
          </div>
        </div>

        <!-- Add to Cart -->
        @auth
        <form method="POST" action="{{ route('cart.add') }}">
          @csrf
          <input type="hidden" name="product_id" value="{{ $product->id }}">
          <div class="d-flex align-items-center mb-3">
            <button type="button" class="btn btn-dark rounded-circle"
              onclick="var q=document.getElementById('cartQty');if(q.value>1)q.value--">-</button>
            <input type="number" id="cartQty" name="quantity" value="1" min="1" max="{{ $product->stock }}"
              class="form-control mx-2 text-center rounded-pill" style="max-width:80px;">
            <button type="button" class="btn btn-dark rounded-circle"
              onclick="var q=document.getElementById('cartQty');if(q.value<{{ $product->stock }})q.value++">+</button>
          </div>

          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-gold flex-fill"><i class="bi bi-cart-fill"></i> Add to Cart</button>
        </form>
        <form method="POST" action="{{ route('wishlist.toggle') }}" class="flex-fill" id="wishlist-form">
          @csrf
          <input type="hidden" name="product_id" value="{{ $product->id }}">
          <button type="submit" class="btn btn-outline-dark w-100" id="wishlist-btn">
            <i class="bi bi-heart{{ $product->isWishlistedBy(auth()->user()) ? '-fill text-danger' : '' }}"></i>
            Wishlist
          </button>
        </form>
        @else
        <!-- Guest User - Show Login Prompts -->
        <div class="d-flex align-items-center mb-3">
          <button type="button" class="btn btn-dark rounded-circle" disabled>-</button>
          <input type="number" value="1" min="1" class="form-control mx-2 text-center rounded-pill" style="max-width:80px;" disabled>
          <button type="button" class="btn btn-dark rounded-circle" disabled>+</button>
        </div>

        <div class="d-flex gap-2">
          <a href="{{ route('login') }}" class="btn btn-gold flex-fill">
            <i class="bi bi-cart-fill"></i> Login to Add to Cart
          </a>
          <a href="{{ route('login') }}" class="btn btn-outline-dark flex-fill">
            <i class="bi bi-heart"></i> Login to Wishlist
          </a>
        </div>
        @endauth
      </div>

    </div>
  </div>

  <!-- Tabs -->
  <ul class="nav nav-pills mt-5 justify-content-center" id="productTabs">
    <li class="nav-item"><button class="nav-link active" data-bs-toggle="pill"
        data-bs-target="#description">Description</button></li>
    <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#store-info">Store
        Info</button></li>
    <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#reviews">Reviews</button>
    </li>
  </ul>

  <div class="tab-content mt-3">
    <div class="tab-pane fade show active" id="description">
      <p>{{ $product->description ?? 'No description available.' }}</p>
    </div>
    <div class="tab-pane fade" id="store-info">
      @if($seller && $seller->id > 0)
        <h4>{{ $seller->store_name ?? 'N/A' }}</h4>
        <p><strong>Shop Name:</strong> {{ $seller->store_name ?? 'N/A' }}</p>
        <p><strong>Address:</strong> {{ $seller->store_address ?? 'N/A' }}</p>
        <p><strong>Contact:</strong> {{ $seller->store_contact ?? 'N/A' }}</p>
        <a href="{{ route('store.products', $seller->id) }}" class="btn btn-outline-dark">View Store Products</a>
      @else
        <div class="alert alert-warning mb-0">
          <i class="bi bi-exclamation-triangle me-2"></i>
          Seller information is currently not available for this product.
        </div>
      @endif
    </div>
    <div class="tab-pane fade" id="reviews">
      <!-- Review form -->
      @auth
        <form method="POST" action="{{ route('product.addReview', $product->id) }}" class="mb-4 p-3 rounded review-card">
          @csrf
          <label class="form-label fw-bold">Your Rating</label>
          <div class="rating-stars mb-2 d-flex gap-1">
            @for($i = 5; $i >= 1; $i--)
              <input type="radio" name="rating" value="{{ $i }}" id="star{{ $i }}">
              <label for="star{{ $i }}" style="cursor:pointer; font-size:1.5rem;">★</label>
            @endfor
          </div>
          <textarea name="comment" class="form-control mb-3 rounded" rows="3"
            placeholder="Share your experience..."></textarea>
          <button class="btn btn-gold px-4"><i class="bi bi-send"></i> Submit Review</button>
        </form>
      @endauth

      <h5 class="fw-bold mb-3">Customer Reviews</h5>
      @if($reviews->count())
        @foreach($reviews as $review)
          <div class="review-card mb-3 p-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <span class="review-user">{{ $review->user->name }}</span>
              <div class="review-stars">
                @for($i = 1; $i <= 5; $i++)
                  <i class="bi {{ $i <= $review->rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                @endfor
              </div>
            </div>
            <p class="mb-0">{{ $review->comment }}</p>
            <small class="text-muted">{{ $review->created_at->format('M j, Y') }}</small>
          </div>
        @endforeach
      @else
        <p class="text-muted">No reviews yet. Be the first to share your thoughts!</p>
      @endif
    </div>
  </div>

  <!-- Other Products -->
  <div class="other-products mt-5">
    <h4 class="mb-3 text-dark">Other Products from this Store</h4>
    <div class="row g-3">
      @forelse($otherProducts as $op)
        <div class="col-6 col-md-3">
          <a href="{{ route('product.details', $op->id) }}" class="card h-100 text-decoration-none text-dark">
            @if($op->image || $op->image_data)
              <img src="{{ $op->image_url }}" class="card-img-top" alt="{{ $op->name }}" 
                   style="height: 200px; object-fit: cover;"
                   onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
              <div style="display: none; height: 200px; background: #f8f9fa; display: flex; align-items: center; justify-content: center; color: #6c757d;">
                <i class="bi bi-image" style="font-size: 2rem;"></i>
              </div>
            @else
              <div style="height: 200px; background: #f8f9fa; display: flex; align-items: center; justify-content: center; color: #6c757d;">
                <i class="bi bi-image" style="font-size: 2rem;"></i>
              </div>
            @endif
            <div class="card-body text-center">
              <h6>{{ $op->name }}</h6>
              @if($op->discount > 0)
                <div class="text-gold fw-bold">₹{{ number_format($op->price * (1 - $op->discount / 100), 2) }}</div>
                <small class="text-muted text-decoration-line-through">₹{{ number_format($op->price, 2) }}</small>
                <small class="text-danger">({{ $op->discount }}% off)</small>
              @else
                <div class="text-gold fw-bold">₹{{ number_format($op->price, 2) }}</div>
              @endif
            </div>
          </a>
        </div>
      @empty
        <p>No other products.</p>
      @endforelse
    </div>
  </div>
  </div>
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
    // Share Functions
    function shareOnWhatsApp() {
      const url = window.location.href;
      const text = `Check out this amazing product: {{ $product->name }} - ₹{{ $product->price }} on grabbasket!`;
      const whatsappUrl = `https://wa.me/?text=${encodeURIComponent(text + ' ' + url)}`;
      window.open(whatsappUrl, '_blank');
    }

    function shareOnFacebook() {
      const url = window.location.href;
      const facebookUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`;
      window.open(facebookUrl, '_blank', 'width=600,height=400');
    }

    function shareOnTwitter() {
      const url = window.location.href;
      const text = `Check out this amazing product: {{ $product->name }} - ₹{{ $product->price }} on grabbasket!`;
      const twitterUrl = `https://twitter.com/intent/tweet?text=${encodeURIComponent(text)}&url=${encodeURIComponent(url)}`;
      window.open(twitterUrl, '_blank', 'width=600,height=400');
    }

    function copyLink() {
      const url = window.location.href;
      navigator.clipboard.writeText(url).then(function() {
        // Show success message
        const btn = event.target.closest('button');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-check"></i> Copied!';
        btn.classList.remove('btn-secondary');
        btn.classList.add('btn-success');
        
        setTimeout(function() {
          btn.innerHTML = originalText;
          btn.classList.remove('btn-success');
          btn.classList.add('btn-secondary');
        }, 2000);
      }).catch(function(err) {
        alert('Failed to copy link. Please copy manually: ' + url);
      });
    }

    function shareViaEmail() {
      const url = window.location.href;
      const subject = `Check out this product: {{ $product->name }}`;
      const body = `I found this amazing product on grabbasket:\n\n{{ $product->name }}\nPrice: ₹{{ $product->price }}\n\nCheck it out: ${url}`;
      const emailUrl = `mailto:?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`;
      window.location.href = emailUrl;
    }

    // AJAX Wishlist Toggle - Prevents redirect and updates UI
    document.addEventListener('DOMContentLoaded', function() {
      const wishlistForm = document.getElementById('wishlist-form');
      
      if (wishlistForm) {
        wishlistForm.addEventListener('submit', function(e) {
          e.preventDefault(); // Prevent form submission/redirect
          
          const formData = new FormData(this);
          const button = this.querySelector('#wishlist-btn');
          const icon = button.querySelector('i');
          
          // Send AJAX request
          fetch(this.action, {
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
                button.classList.add('btn-danger');
                button.classList.remove('btn-outline-dark');
              } else {
                // Removed from wishlist - show empty heart
                icon.classList.remove('bi-heart-fill', 'text-danger');
                icon.classList.add('bi-heart');
                button.classList.remove('btn-danger');
                button.classList.add('btn-outline-dark');
              }
              
              // Optional: Show brief success toast
              const toast = document.createElement('div');
              toast.className = 'position-fixed top-0 start-50 translate-middle-x mt-3 alert alert-success alert-dismissible fade show';
              toast.style.zIndex = '9999';
              toast.innerHTML = `
                <i class="bi bi-check-circle-fill"></i> ${data.message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
              `;
              document.body.appendChild(toast);
              
              setTimeout(() => toast.remove(), 3000);
            }
          })
          .catch(error => {
            console.error('Error:', error);
            alert('Failed to update wishlist. Please try again.');
          });
        });
      }
    });
  </script>
</body>

</html>
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


  <!-- Product Section -->
  <div class="container py-5">
    <div class="row g-4 product-section">
      <!-- Image -->
      <div class="col-lg-6 text-center">
        @if($product->image || $product->image_data)
          <img src="{{ $product->image_url }}" class="product-image img-fluid" alt="{{ $product->name }}" 
               onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
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
      @if($seller)
        <h4>{{ $seller->store_name ?? 'N/A' }}</h4>
        <p><strong>Shop_Name:</strong> {{ $seller->store_name ?? 'N/A' }}</p>
        <p><strong>Address:</strong> {{ $seller->store_address ?? 'N/A' }}</p>
        <p><strong>Contact:</strong> {{ $seller->store_contact ?? 'N/A' }}</p>
        <a href="{{ route('store.products', $seller->id) }}" class="btn btn-outline-dark">View Store Products</a>
      @else
        <div class="alert alert-warning mb-0">Seller information not available.</div>
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
    <footer>
    <div class="container py-5">
      <div class="footer-main-grid">
        <div class="brand-column">
          <h3 class="mb-3"><img src="{{ asset('asset/images/grabbasket.png') }}" alt="Logo" width="180px"></h3>
          <p class="para">Your trusted online marketplace for quality products at the best prices.</p>
        </div>

        <div class="quick-links-column">
          <h4 class="mb-3">Quick Links</h4>
          <ul class="list-unstyled">
            <li><a href="{{ route('buyer.dashboard') }}">Shop</a></li>
            <li><a href="{{ route('seller.dashboard') }}">Seller</a></li>
            <li><a href="{{ route('cart.index') }}">Cart</a></li>
            <li><a href="{{ route('login') }}">Login</a></li>
          </ul>
        </div>

        <div class="support-column">
          <h4 class="mb-3">Customer Support</h4>
          <ul class="list-unstyled">
            <li><a href="#">Help Center</a></li>
            <li><a href="#">FAQ</a></li>
            <li><a href="#">Contact Us</a></li>
          </ul>
        </div>

        <div class="follow-column">
          <h4 class="mb-3 follow">Follow Us</h4>
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
  </script>
</body>

</html>
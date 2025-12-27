<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $product->name }} | GrabBasket</title>

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

<style>
*{
  margin:0;
  padding:0;
  box-sizing:border-box;
  font-family:Poppins,system-ui;
}

body{
  background:linear-gradient(135deg,#f4f6ff,#fefefe);
}

/* ================= NAVBAR ================= */
.navbar{
  background:linear-gradient(135deg,#6d28d9,#9333ea);
  color:#fff;
  padding:14px 20px;
  display:flex;
  align-items:center;
  gap:18px;
  box-shadow:0 8px 30px rgba(109,40,217,.4);
  position:sticky;
  top:0;
  z-index:10;
}

.logo{ font-size:22px; font-weight:800; }
.location{ font-size:14px; white-space:nowrap; cursor:pointer; }

.search{
  flex:1;
  display:flex;
  align-items:center;
  gap:10px;
  background:#fff;
  padding:10px 16px;
  border-radius:999px;
  color:#6b7280;
}

.search input{ border:none; outline:none; width:100%; }

.nav-icons{ display:flex; gap:18px; }

.cart{ position:relative; cursor:pointer; }

.cart-badge{
  position:absolute;
  top:-6px;
  right:-8px;
  background:#ef4444;
  color:#fff;
  font-size:11px;
  padding:2px 6px;
  border-radius:999px;
  display:none;
}

/* ================= MAIN ================= */
.main{
  max-width:1100px;
  margin:30px auto;
  padding:0 16px;
}

.grid{
  display:grid;
  grid-template-columns:1fr 1fr;
  gap:30px;
}

/* ================= PRODUCT ================= */
.product-box{
  background:#fff;
  border-radius:22px;
  padding:26px;
  box-shadow:0 14px 40px rgba(0,0,0,.08);
}

.product-img{
  width:100%;
  max-width:330px;
  margin:auto;
  display:block;
}

/* action row */
.action-row{
  display:flex;
  justify-content:space-between;
  align-items:center;
  margin-top:24px;
}

/* price */
.price-btn{
  background:linear-gradient(135deg,#16a34a,#22c55e);
  color:#fff;
  padding:14px 22px;
  border-radius:16px;
  font-weight:800;
  font-size:16px;
}

/* add to cart */
.add-btn{
  background:linear-gradient(135deg,#ec4899,#f472b6);
  border:none;
  color:#fff;
  padding:18px 42px;
  border-radius:20px;
  font-weight:800;
  font-size:16px;
  cursor:pointer;
  transition:.3s;
  box-shadow:0 10px 25px rgba(236,72,153,.45);
}

.action-row:hover .add-btn{
  transform:scale(.9);
}

/* qty */
.qty-box{
  display:none;
  align-items:center;
  gap:14px;
  background:#f9fafb;
  padding:10px 16px;
  border-radius:18px;
}

.qty-btn{
  width:42px;
  height:42px;
  border:none;
  border-radius:50%;
  background:linear-gradient(135deg,#a78bfa,#7c3aed);
  color:#fff;
  font-size:20px;
  font-weight:700;
  cursor:pointer;
}

#qty{
  font-size:18px;
  font-weight:700;
  min-width:20px;
  text-align:center;
}

/* ================= INFO ================= */
.info-card{
  background:#fff;
  border-radius:22px;
  padding:26px;
  box-shadow:0 14px 40px rgba(0,0,0,.08);
}

.info-card h2{
  font-size:22px;
  margin-bottom:16px;
}

.info-row{
  font-size:14.5px;
  color:#374151;
  margin-bottom:14px;
  line-height:1.6;
}

/* feature icons */
.feature-box{
  display:flex;
  gap:16px;
  margin:18px 0;
}

.feature{
  flex:1;
  background:#f8fafc;
  border-radius:16px;
  padding:14px;
  text-align:center;
  font-size:13px;
}

.feature i{
  font-size:22px;
  margin-bottom:8px;
  color:#7c3aed;
}

/* ================= MOBILE ================= */
@media(max-width:768px){
  .grid{ grid-template-columns:1fr; }
}
</style>

</head>

<body>

<!-- NAVBAR -->
<div class="navbar">
  <div class="logo">GrabBaskets</div>

  <div class="location">
    <i class="fa-solid fa-location-dot"></i> Location
  </div>

  <div class="search">
    <i class="fa-solid fa-magnifying-glass"></i>
    <input placeholder="Search rice, oil, atta..." />
  </div>

  <div class="nav-icons">
    <i class="fa-regular fa-user"></i>
    <a href="{{ route('tenmin.cart.view') }}" class="cart" style="color:white;text-decoration:none;">
      <i class="fa-solid fa-cart-shopping"></i>
      <span class="cart-badge" id="badge">0</span>
    </a>
  </div>
</div>

<!-- MAIN -->
<div class="main">
  <div class="grid">

    <!-- LEFT -->
    <div class="product-box">
      <img id="productImage" class="product-img" src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/330?text=No+Image' }}" alt="{{ $product->name }}">

      <div class="action-row">
        <div class="price-btn">₹{{ number_format($product->price, 2) }}</div>

        <button 
            class="add-btn" 
            id="addBtn"
            @if($product->stock <= 0) disabled @endif
        >
            {{ $product->stock > 0 ? 'Add to Cart' : 'Out of Stock' }}
        </button>

        <div class="qty-box" id="qtyBox">
          <button class="qty-btn" id="minus">−</button>
          <span id="qty">1</span>
          <button class="qty-btn" id="plus">+</button>
        </div>
      </div>
    </div>

    <!-- RIGHT -->
    <div class="info-card">
      <h2>{{ $product->name }}</h2>

      <div class="action-row">
        <div class="price-btn">₹{{ number_format($product->price, 2) }}</div>
      </div>

      <div class="feature-box">
        <div class="feature">
          <i class="fa-solid fa-ban"></i>
          No Return<br>or Exchange
        </div>
        <div class="feature">
          <i class="fa-solid fa-bolt"></i>
          Fast<br>Delivery
        </div>
      </div>

      <div class="info-row">
        <b>Customer Care</b><br>
        In case of any issue, contact us<br>
        Email: <b>grabbaskets@gmail.com</b>
      </div>

      {{-- DYNAMIC SELLER INFO --}}
      @php
          $seller = $product->seller;
      @endphp

      @if($seller)
          <div class="info-row"><b>Seller:</b> {{ $seller->name }}</div>
          <div class="info-row"><b>Email:</b> {{ $seller->email }}</div>
          @if($seller->address)
              <div class="info-row"><b>Address:</b> {{ $seller->address }}</div>
          @else
              <div class="info-row"><b>Address:</b> {{ $seller->city ?? 'Chennai' }}, {{ $seller->state ?? 'Tamil Nadu' }}</div>
          @endif
          <div class="info-row"><b>State:</b> {{ $seller->state ?? 'Tamil Nadu' }}</div>
          <div class="info-row"><b>Country:</b> {{ $seller->country ?? 'India' }}</div>
      @else
          <div class="info-row"><b>Seller:</b> <em>Information not available</em></div>
      @endif

      @if($product->description)
        <div class="info-row"><b>Description:</b> {{ $product->description }}</div>
      @endif
    </div>

  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // ========== STATE ==========
    let qty = 1;
    const addBtn = document.getElementById("addBtn");
    const qtyBox = document.getElementById("qtyBox");
    const badge = document.getElementById("badge");
    const qtyText = document.getElementById("qty");
    const csrfToken = '{{ csrf_token() }}';
    const productId = {{ $product->id }};
    const priceBtn = document.querySelector('.price-btn');

    // ========== CART BADGE ==========
    function updateCartBadge() {
        const cartCount = parseInt(badge.innerText) || 0;
        badge.style.display = cartCount > 0 ? 'block' : 'none';
    }
    updateCartBadge();

    // ========== REAL-TIME STOCK ==========
    function fetchProductDetails() {
        fetch(`/api/product/${productId}`)
            .then(response => {
                if (!response.ok) {
                    if (priceBtn) priceBtn.textContent = 'Unavailable';
                    if (addBtn) {
                        addBtn.disabled = true;
                        addBtn.textContent = 'Out of Stock';
                    }
                    throw new Error('Unavailable');
                }
                return response.json();
            })
            .then(data => {
                if (priceBtn) {
                    priceBtn.textContent = `₹${parseFloat(data.price).toFixed(2)}`;
                }
                const inStock = data.stock > 0;
                if (addBtn) {
                    addBtn.disabled = !inStock;
                    addBtn.textContent = inStock ? 'Add to Cart' : 'Out of Stock';
                }
            })
            .catch(err => console.warn('Fetch failed:', err));
    }

    fetchProductDetails();
    const interval = setInterval(fetchProductDetails, 15000);
    window.addEventListener('beforeunload', () => clearInterval(interval));

    // ========== ADD TO CART ==========
    addBtn.onclick = () => {
        if (addBtn.disabled) return;

        fetch("{{ route('tenmin.cart.add') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: qty
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update badge immediately
                badge.innerText = data.cart_count;
                updateCartBadge();
                
                // Redirect to cart page after short delay
                setTimeout(() => {
                    window.location.href = "{{ route('tenmin.cart.view') }}";
                }, 500);
            } else {
                alert(data.error || 'Failed to add');
            }
        })
        .catch(err => {
            console.error('Error:', err);
            alert('Failed to add to cart');
        });
    };

    // ========== QUANTITY CONTROLS ==========
    document.getElementById("plus").onclick = () => {
        qty++;
        qtyText.innerText = qty;
    };

    document.getElementById("minus").onclick = () => {
        if (qty > 1) {
            qty--;
            qtyText.innerText = qty;
        }
    };
});
</script>

</body>
</html>
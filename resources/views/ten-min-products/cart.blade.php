  <!DOCTYPE html>
  <html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GrabBasket — Cart</title>

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
        max-width:1000px;
        margin:30px auto;
        padding:0 16px;
      }

      .cart-header{
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
      }

      .cart-header h2{
        font-size: 24px;
        color: #1e293b;
      }

      .empty-cart{
        text-align: center;
        padding: 40px 20px;
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 6px 20px rgba(0,0,0,0.06);
      }

      .empty-cart h3{
        margin-bottom: 12px;
        color: #64748b;
      }

      .empty-cart a{
        display: inline-block;
        margin-top: 16px;
        background: linear-gradient(135deg,#6d28d9,#9333ea);
        color: white;
        padding: 10px 24px;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 600;
      }

      /* ================= CART ITEM ================= */
      .cart-item{
        display: flex;
        gap: 16px;
        padding: 16px;
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 6px 20px rgba(0,0,0,0.06);
        margin-bottom: 16px;
        align-items: center;
      }

      .item-img{
        width: 80px;
        height: 80px;
        border-radius: 10px;
        overflow: hidden;
        flex-shrink: 0;
      }

      .item-img img{
        width: 100%;
        height: 100%;
        object-fit: cover;
      }

      .item-info{
        flex: 1;
      }

      .item-info h4{
        font-size: 16px;
        margin-bottom: 6px;
        color: #0f172a;
      }

      .item-price-row{
        display: flex;
        gap: 8px;
        align-items: center;
        margin-top: 6px;
      }

      .price-unit{
        color: #64748b;
        font-size: 14px;
      }

      .price-total{
        font-weight: 700;
        color: #16a34a;
      }

      /* qty */
      .qty-controls{
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 10px;
      }

      .qty-btn{
        width: 32px;
        height: 32px;
        border: none;
        border-radius: 8px;
        background: #f1f5f9;
        color: #334155;
        font-weight: bold;
        cursor: pointer;
      }

      .qty-value{
        min-width: 24px;
        text-align: center;
        font-weight: 600;
      }

      /* remove */
      .remove-btn{
        color: #ef4444;
        background: none;
        border: none;
        font-size: 14px;
        cursor: pointer;
        margin-top: 10px;
        text-decoration: underline;
      }

      /* ================= FOOTER ================= */
      .cart-footer{
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 24px;
      }

      .continue-btn,
      .checkout-btn{
        padding: 12px 24px;
        border-radius: 12px;
        font-weight: 700;
        text-decoration: none;
        display: inline-block;
        text-align: center;
      }

      .continue-btn{
        background: #e2e8f0;
        color: #334155;
      }

      .checkout-btn{
        background: linear-gradient(135deg,#16a34a,#22c55e);
        color: white;
        box-shadow: 0 6px 18px rgba(22,163,74,0.3);
      }

      /* MOBILE */
      @media(max-width:600px){
        .cart-header{
          flex-direction: column;
          gap: 12px;
          align-items: flex-start;
        }
        
        .cart-footer{
          flex-direction: column;
          gap: 12px;
          align-items: stretch;
        }
        
        .continue-btn, .checkout-btn{
          width: 100%;
        }
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
      <a href="{{ route('ten.min.products') }}" style="color:white;text-decoration:none;">
        <i class="fa-regular fa-user"></i>
      </a>
      <a href="{{ route('tenmin.cart.view') }}" class="cart">
        <i class="fa-solid fa-cart-shopping"></i>
        <span class="cart-badge" id="cartBadge">{{ $cartCount }}</span>
      </a>
    </div>
  </div>

  <!-- MAIN -->
  <div class="main">
    <div class="cart-header">
      <h2>Your 10-Minute Cart</h2>
    </div>

    <!-- FLASH MESSAGES -->
@if(session('error'))
  <div style="background:#fee; color:#dc2626; padding:12px; border-radius:8px; margin-bottom:20px; text-align:center;">
    {{ session('error') }}
  </div>
@endif

@if(session('success'))
  <div style="background:#ecfdf5; color:#065f46; padding:12px; border-radius:8px; margin-bottom:20px; text-align:center;">
    {{ session('success') }}
  </div>
@endif

    @if($cartItems->isEmpty())
      <div class="empty-cart">
        <h3>Your cart is empty</h3>
        <p>Add some fast-delivery items to get started!</p>
        <a href="{{ route('ten.min.products') }}">← Continue Shopping</a>
      </div>
    @else
      @foreach($cartItems as $item)
        <div class="cart-item" data-product-id="{{ $item->product_id }}" data-price="{{ $item->price }}">
          <div class="item-img">
            <img src="{{ $item->image ? asset('product_images/' . $item->image) : 'https://via.placeholder.com/80' }}" alt="{{ $item->name }}">
          </div>
          <div class="item-info">
            <h4>{{ $item->name }}</h4>
            <div class="item-price-row">
              <span class="price-unit">₹{{ number_format($item->price, 2) }} × <span class="qty-display">{{ $item->quantity }}</span></span>
              <span class="price-total">₹{{ number_format($item->price * $item->quantity, 2) }}</span>
            </div>
            <div class="qty-controls">
              <button class="qty-btn minus" data-id="{{ $item->product_id }}">−</button>
              <span class="qty-value">{{ $item->quantity }}</span>
              <button class="qty-btn plus" data-id="{{ $item->product_id }}">+</button>
            </div>
            <button class="remove-btn" data-id="{{ $item->product_id }}">Remove</button>
          </div>
        </div>
      @endforeach

      <div class="cart-footer">
  <a href="{{ route('ten.min.products') }}" class="continue-btn">← Continue Shopping</a>
  <a href="{{ route('tenmin.checkout') }}" class="checkout-btn">Proceed to Checkout</a>
    @endif
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const csrfToken = '{{ csrf_token() }}';
      const cartBadge = document.getElementById('cartBadge');

      // Show cart badge only if count > 0
      if ({{ $cartCount }} > 0) {
        cartBadge.style.display = 'block';
      }

      // Update quantity (+ / -)
      document.querySelectorAll('.plus').forEach(btn => {
        btn.addEventListener('click', function() {
          const id = this.dataset.id;
          updateQuantity(id, 1);
        });
      });

      document.querySelectorAll('.minus').forEach(btn => {
        btn.addEventListener('click', function() {
          const id = this.dataset.id;
          updateQuantity(id, -1);
        });
      });

      // Remove item
      document.querySelectorAll('.remove-btn').forEach(btn => {
        btn.addEventListener('click', function() {
          if (!confirm('Remove this item?')) return;
          const id = this.dataset.id;
          fetch("{{ route('tenmin.cart.remove') }}", {
            method: "POST",
            headers: { 
              "X-CSRF-TOKEN": csrfToken,
              "Content-Type": "application/json"
            },
            body: JSON.stringify({ product_id: id })
          })
          .then(res => res.json())
          .then(() => {
            // Update cart count or reload
            location.reload();
          })
          .catch(err => {
            console.error('Remove failed:', err);
            alert('Failed to remove item');
          });
        });
      });

      function updateQuantity(productId, change) {
        const item = document.querySelector(`.cart-item[data-product-id="${productId}"]`);
        if (!item) return;

        const qtyEl = item.querySelector('.qty-value');
        const qtyDisplay = item.querySelector('.qty-display');
        const totalEl = item.querySelector('.price-total');
        const current = parseInt(qtyEl.textContent);
        const newQty = Math.max(1, current + change);
        const unitPrice = parseFloat(item.dataset.price);

        // No need to update if no change (e.g., 1 - 1 = 1)
        if (newQty === current) return;

        fetch("{{ route('tenmin.cart.update') }}", {
          method: "POST",
          headers: { 
            "X-CSRF-TOKEN": csrfToken,
            "Content-Type": "application/json"
          },
          body: JSON.stringify({ product_id: productId, quantity: newQty })
        })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            // Update UI without reload
            qtyEl.textContent = newQty;
            qtyDisplay.textContent = newQty;
            totalEl.textContent = '₹' + (unitPrice * newQty).toFixed(2);
            
            // Optionally update global cart count
            // (requires returning new total count from backend)
          } else {
            alert(data.error || 'Failed to update quantity');
          }
        })
        .catch(err => {
          console.error('Update failed:', err);
          alert('Failed to update item');
        });
      }
    });
  </script>

  </body>
  </html>
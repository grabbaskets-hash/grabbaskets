    <!DOCTYPE html>
    <html lang="en">
    <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>GrabBasket - Your Cart</title>

    <!-- Icons & Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- CSRF Token (MUST be in head) -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
    :root{
    --primary:#ff7b00;
    --secondary:#ff9f00;
    --bg:#f6f7fb;
    --cardBg:#fff;
    --radius:14px;
    --shadow:0 10px 25px rgba(0,0,0,0.07);
    --font:'Poppins', sans-serif;
    }
    *{margin:0;padding:0;box-sizing:border-box;font-family:var(--font);}
    body{background:var(--bg);color:#0b1720}

    /* NAVBAR */
    .navbar{
    background:white;padding:14px 20px;display:flex;justify-content:space-between;align-items:center;
    box-shadow:var(--shadow);position:sticky;top:0;z-index:50;
    }
    .brand{font-size:22px;font-weight:700;color:var(--primary);display:flex;align-items:center;gap:8px}
    .brand i{color:var(--secondary)}
    .nav-middle{display:flex;align-items:center;gap:12px}
    .nav-input{display:flex;align-items:center;background:#f1f1f1;border-radius:30px;padding:10px 14px;width:220px}
    .nav-input input{border:none;outline:none;background:transparent;width:100%}
    .cart-btn{background:linear-gradient(90deg,var(--primary),var(--secondary));border:none;padding:10px 14px;border-radius:30px;color:white;font-weight:600;display:flex;align-items:center;gap:10px;position:relative;cursor:pointer}
    .cart-count{background:white;color:var(--primary);font-size:12px;width:18px;height:18px;border-radius:50%;position:absolute;top:-5px;right:-5px;display:flex;align-items:center;justify-content:center;font-weight:700}
    .hamburger{display:none;font-size:22px;cursor:pointer}

    /* LAYOUT */
    .container{max-width:1200px;margin:25px auto;padding:0 16px;display:grid;grid-template-columns:2fr 1fr;gap:20px}
    .cart-box{background:var(--cardBg);padding:20px;border-radius:var(--radius);box-shadow:var(--shadow)}
    .cart-item{display:flex;gap:15px;align-items:center;padding:12px;border-radius:12px;margin-bottom:15px;background:#fff6eb;border:1px solid #fff0e6;position:relative}
    .cart-item img{width:85px;height:85px;border-radius:10px;object-fit:cover;border:2px solid #ffe6c7}
    .item-info{flex:1}
    .item-title{font-weight:600}
    .price{color:var(--primary);font-weight:600;margin-top:6px}
    .meta{font-size:13px;color:#6b7280;margin-top:8px}
    .qty-box{display:flex;gap:6px;align-items:center;margin-top:10px}
    .qty-btn{width:30px;height:30px;border-radius:6px;border:0;background:var(--primary);color:#fff;cursor:pointer;font-weight:800}
    .qty-num{min-width:30px;text-align:center;font-weight:700}
    .delete-btn{position:absolute;right:12px;top:12px;color:#d43a3a;font-size:18px;cursor:pointer;padding:6px;border-radius:8px}

    /* SUMMARY */
    .summary{background:var(--cardBg);padding:20px;border-radius:var(--radius);box-shadow:var(--shadow);height:fit-content}
    .summary h3{margin-bottom:12px}
    .summary-row{display:flex;justify-content:space-between;margin-bottom:8px;font-weight:500}
    .checkout-btn{width:100%;padding:12px;margin-top:15px;background:linear-gradient(90deg,var(--primary),var(--secondary));border:none;border-radius:30px;color:white;font-weight:600;cursor:pointer}
    .shop-btn{width:100%;padding:12px;margin-top:10px;border:2px solid var(--primary);border-radius:30px;color:var(--primary);font-weight:600;cursor:pointer}

    /* MOBILE */
    @media(max-width:900px){
    .container{grid-template-columns:1fr}
    .nav-middle{display:none}
    .hamburger{display:block}
    }

    /* small helpers */
    .muted{color:#6b7280;font-size:13px}
    .subtle{font-size:13px;color:#394b5a}
    </style>
    </head>
    <body>

    <!-- NAVBAR -->
    <div class="navbar">
    <div class="brand"><i class="fa-solid fa-basket-shopping"></i> GrabBaskets</div>

    

    <button class="cart-btn" id="cartBtn" title="Open cart">
        <i class="fa-solid fa-cart-shopping"></i> Cart
        <div class="cart-count" id="cartCount">0</div>
    </button>
    </div>

    <!-- MAIN -->
    <div class="container">

    <!-- LEFT: cart items list -->
    <div class="cart-box" id="cartBox">
        <h2 style="margin:0 0 12px 0">Your Cart</h2>
        <div id="itemsContainer">
        <!-- cart items inserted here -->
        </div>

        <div id="emptyState" class="cart-box" style="display:none;background:transparent;box-shadow:none;padding:0">
        <div class="muted" style="padding:20px 0">Your cart is empty</div>
        </div>
    </div>

    <!-- RIGHT: summary -->
    <aside class="summary" id="summaryBox">
        <h3>Order Summary</h3>
        <div class="summary-row"><span>Subtotal</span><span id="subtotal">₹0</span></div>
        <div class="summary-row"><span>Delivery</span><span id="delivery">₹50</span></div>
        <div class="summary-row"><span>Tax (5%)</span><span id="tax">₹0</span></div>
        <hr style="border:none;border-top:1px solid #f2f2f2;margin:12px 0">
        <div class="summary-row" style="font-weight:800"><span>Total</span><span id="total">₹0</span></div>

        <button class="checkout-btn" onclick="checkout()">Proceed to Checkout</button>
        <button class="shop-btn" onclick="continueShopping()">Continue Shopping</button>
    </aside>
    </div>

    <!-- SAFE JSON DATA (PREPARED IN CONTROLLER) -->
    <div id="products-data" style="display:none">@json($foodsForJs)</div>
<div id="cart-data" style="display:none">@json(array_values($cartData))</div>
    <!-- PASS DYNAMIC ROUTES & TOKEN TO JS (AFTER DATA) -->
    <script>
    // Get CSRF token (meta tag is in head, so it's available)
    const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const CHECKOUT_URL = "{{ route('customer.food.checkout') }}";
    const INDEX_URL = "{{ route('customer.food.index') }}";
    const UPDATE_URL_TEMPLATE = "{{ route('customer.food.cart.update', ['foodId' => 'ID']) }}";
    const REMOVE_URL_TEMPLATE = "{{ route('customer.food.cart.remove', ['foodId' => 'ID']) }}";

    // Initialize data
    const PRODUCTS = JSON.parse(document.getElementById('products-data').textContent);
    const INITIAL_CART = JSON.parse(document.getElementById('cart-data').textContent);

    let cart = INITIAL_CART.map(item => ({
        id: item.id,
        qty: item.quantity
    }));

    // DOM refs
    const itemsContainer = document.getElementById('itemsContainer');
    const subtotalEl = document.getElementById('subtotal');
    const deliveryEl = document.getElementById('delivery');
    const taxEl = document.getElementById('tax');
    const totalEl = document.getElementById('total');
    const cartCountEl = document.getElementById('cartCount');
    const emptyState = document.getElementById('emptyState');
    const summaryBox = document.getElementById('summaryBox');

    function findProduct(id) {
        return PRODUCTS.find(p => p.id === id);
    }

    function renderCart() {
        itemsContainer.innerHTML = '';
        if (cart.length === 0) {
            emptyState.style.display = 'block';
            summaryBox.style.display = 'none';
            cartCountEl.textContent = '0';
            subtotalEl.textContent = '₹0';
            taxEl.textContent = '₹0';
            totalEl.textContent = '₹0';
            return;
        }
        emptyState.style.display = 'none';
        summaryBox.style.display = 'block';

        let subtotal = 0;
        cart.forEach(entry => {
            const prod = findProduct(entry.id);
            if (!prod) return;
            const itemTotal = prod.price * entry.qty;
            subtotal += itemTotal;

            const item = document.createElement('div');
            item.className = 'cart-item';
            item.innerHTML = `
                <img src="${prod.img}" alt="${prod.name}">
                <div class="item-info">
                    <div class="item-title">${prod.name}</div>
                    <div class="muted">${prod.desc}</div>
                    <div class="price">₹${prod.price.toFixed(2)}</div>
                    <div class="meta">⏱ ${prod.prep} mins</div>
                    <div class="qty-box">
                        <button class="qty-btn" data-action="decrease" data-id="${prod.id}">-</button>
                        <div class="qty-num" id="qty-${prod.id}">${entry.qty}</div>
                        <button class="qty-btn" data-action="increase" data-id="${prod.id}">+</button>
                    </div>
                </div>
                <div style="text-align:right;min-width:84px">
                    <div class="subtle">Subtotal</div>
                    <div style="font-weight:800">₹<span id="sub-${prod.id}">${itemTotal.toFixed(0)}</span></div>
                </div>
                <div class="delete-btn" data-id="${prod.id}" title="Remove item"><i class="fa-solid fa-trash"></i></div>
            `;
            itemsContainer.appendChild(item);
        });

        const delivery = 50; // Fixed delivery fee
        const tax = Math.round(subtotal * 0.05);
        const grand = subtotal + delivery + tax;

        subtotalEl.textContent = '₹' + subtotal.toFixed(0);
        deliveryEl.textContent = '₹' + delivery;
        taxEl.textContent = '₹' + tax;
        totalEl.textContent = '₹' + grand.toFixed(0);

        // Reattach event listeners
        itemsContainer.querySelectorAll('.qty-btn').forEach(btn => {
            btn.onclick = function () {
                const id = parseInt(this.dataset.id, 10);
                const action = this.dataset.action;
                if (action === 'increase') changeQty(id, +1);
                else changeQty(id, -1);
            };
        });
        itemsContainer.querySelectorAll('.delete-btn').forEach(b => {
            b.onclick = function () {
                const id = parseInt(this.dataset.id, 10);
                removeItem(id);
            };
        });

        updateBadge();
    }

    function updateBadge() {
        const count = cart.reduce((s, c) => s + c.qty, 0);
        cartCountEl.textContent = count;
    }

    async function changeQty(id, delta) {
        const entry = cart.find(c => c.id === id);
        if (!entry) return;

        const newQty = entry.qty + delta;
        if (newQty < 1) return;

        const url = UPDATE_URL_TEMPLATE.replace('ID', id);
        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                },
                body: JSON.stringify({ quantity: newQty })
            });

            if (response.ok) {
                entry.qty = newQty;
                const qtyEl = document.getElementById(`qty-${id}`);
                const subEl = document.getElementById(`sub-${id}`);
                if (qtyEl) qtyEl.textContent = newQty;
                if (subEl) subEl.textContent = (findProduct(id).price * newQty).toFixed(0);
                renderCart();
            } else {
                alert('Failed to update item (status: ' + response.status + ')');
            }
        } catch (e) {
            console.error('Network error:', e);
            alert('Network error: ' + e.message);
        }
    }

    async function removeItem(id) {
        if (!confirm('Remove this item?')) return;

        const url = REMOVE_URL_TEMPLATE.replace('ID', id);
        try {
            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': CSRF_TOKEN
                }
            });

            if (response.ok) {
                cart = cart.filter(c => c.id !== id);
                renderCart();
            } else {
                alert('Failed to remove item (status: ' + response.status + ')');
            }
        } catch (e) {
            console.error('Network error:', e);
            alert('Network error: ' + e.message);
        }
    }

    function checkout() {
        if (cart.length === 0) {
            alert('Your cart is empty');
            return;
        }
        window.location.href = CHECKOUT_URL;
    }

    function continueShopping() {
        window.location.href = INDEX_URL;
    }

    // Initialize
    renderCart();
    </script>

    </body>
    </html>
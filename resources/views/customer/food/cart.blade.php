<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>GrabBaskets - Your Cart</title>

    <!-- UI Libraries -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        :root {
            --primary: #FF6B00;
            --secondary: #ff9f00;
            --bg: #f8f9fa;
            --card-shadow: 0 10px 30px rgba(0,0,0,0.05);
            --font: 'Poppins', sans-serif;
            --glass: rgba(255, 255, 255, 0.95);
        }

        body {
            background-color: var(--bg);
            font-family: var(--font);
            color: #2d3436;
            padding-bottom: 80px; /* Space for sticky bottom bar on mobile */
        }

        /* Navbar Styling */
        .navbar-custom {
            background: var(--glass);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            padding: 15px 0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .brand-logo {
            font-size: 24px;
            font-weight: 800;
            color: var(--primary);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Cart Page Specifics */
        .cart-title {
            font-weight: 700;
            margin: 30px 0 20px;
            color: #1a1a1a;
        }

        .cart-card {
            background: #fff;
            border-radius: 20px;
            border: none;
            box-shadow: var(--card-shadow);
            overflow: hidden;
            margin-bottom: 20px;
        }

        .cart-item {
            padding: 20px;
            border-bottom: 1px solid #f1f1f1;
            transition: all 0.3s ease;
            position: relative;
        }

        .cart-item:last-child {
            border-bottom: none;
        }

        .cart-item:hover {
            background-color: #fcfcfc;
        }

        .item-img-wrapper {
            width: 100px;
            height: 100px;
            border-radius: 15px;
            overflow: hidden;
            flex-shrink: 0;
        }

        .item-img-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .item-details {
            flex-grow: 1;
            padding-left: 20px;
        }

        .item-name {
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 4px;
            color: #111;
        }

        .item-desc {
            font-size: 0.85rem;
            color: #636e72;
            margin-bottom: 8px;
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .item-price {
            font-weight: 700;
            color: var(--primary);
            font-size: 1.1rem;
        }

        /* Quantity Controls */
        .qty-controls {
            display: flex;
            align-items: center;
            background: #f8f9fa;
            border-radius: 10px;
            padding: 4px;
            gap: 10px;
            width: fit-content;
        }

        .qty-btn {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            border: none;
            background: #fff;
            color: var(--primary);
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            transition: all 0.2s;
        }

        .qty-btn:hover {
            background: var(--primary);
            color: #fff;
        }

        .qty-val {
            font-weight: 700;
            min-width: 25px;
            text-align: center;
        }

        /* Summary Sidebar */
        .summary-box {
            position: sticky;
            top: 100px;
            padding: 25px;
            background: #fff;
            border-radius: 20px;
            box-shadow: var(--card-shadow);
        }

        .summary-title {
            font-weight: 700;
            font-size: 1.25rem;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px dashed #ddd;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            font-weight: 500;
            color: #636e72;
        }

        .summary-row.total {
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #f1f1f1;
            font-size: 1.25rem;
            font-weight: 800;
            color: #000;
        }

        .btn-checkout {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border: none;
            border-radius: 12px;
            padding: 16px;
            width: 100%;
            color: #fff;
            font-weight: 700;
            margin-top: 20px;
            transition: transform 0.2s;
        }

        .btn-checkout:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 107, 0, 0.3);
            color: #fff;
        }

        .btn-continue {
            background: #fff;
            border: 2px solid #eee;
            border-radius: 12px;
            padding: 12px;
            width: 100%;
            color: #111;
            font-weight: 600;
            margin-top: 10px;
            transition: all 0.3s;
        }

        .btn-continue:hover {
            border-color: var(--primary);
            color: var(--primary);
        }

        /* Mobile Sticky Bottom */
        .mobile-bottom-bar {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: #fff;
            padding: 15px 20px;
            box-shadow: 0 -5px 20px rgba(0,0,0,0.1);
            display: none;
            z-index: 1001;
            border-top-left-radius: 20px;
            border-top-right-radius: 20px;
        }

        .remove-btn {
            color: #ff4757;
            cursor: pointer;
            padding: 5px;
            font-size: 1.1rem;
            transition: 0.2s;
        }

        .remove-btn:hover {
            transform: scale(1.1);
        }

        /* Empty State */
        .empty-cart-container {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-icon {
            font-size: 80px;
            color: #dfe6e9;
            margin-bottom: 20px;
        }

        @media (max-width: 768px) {
            .mobile-bottom-bar {
                display: flex;
                flex-direction: column;
                gap: 10px;
            }
            body {
                background-color: #fff; /* Better for mobile lists */
            }
            .summary-box {
                margin-bottom: 30px;
            }
            .item-img-wrapper {
                width: 80px;
                height: 80px;
            }
            .item-details {
                padding-left: 15px;
            }
            .item-name {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>

    <!-- NAVBAR -->
    <nav class="navbar-custom">
        <div class="container d-flex justify-content-between align-items-center">
            <a href="{{ route('customer.food.index') }}" class="brand-logo">
                <i class="fa-solid fa-basket-shopping"></i> GrabBaskets
            </a>
            <div class="position-relative">
                <a href="#" class="text-dark fs-4" id="cartBtn" title="Your Cart">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <span id="cartCount" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">0</span>
                </a>
            </div>
        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <div class="container">
        <h2 class="cart-title">Your Food Cart</h2>

        <div class="row">
            <!-- Left: Cart Items -->
            <div class="col-lg-8" id="cartItemsSection">
                <div class="cart-card" id="cartList">
                    <div id="itemsContainer">
                        <!-- Dynamic items here -->
                    </div>
                </div>

                <div id="emptyState" class="empty-cart-container" style="display:none;">
                    <i class="fa-solid fa-cart-arrow-down empty-icon"></i>
                    <h3>Your cart is empty</h3>
                    <p class="text-muted">Looks like you haven't added anything yet.</p>
                    <a href="{{ route('customer.food.index') }}" class="btn btn-primary rounded-pill px-4 mt-2">Browse Menu</a>
                </div>
            </div>

            <!-- Right: Order Summary -->
            <div class="col-lg-4 d-none d-lg-block" id="summarySection">
                <div class="summary-box">
                    <h5 class="summary-title">Order Summary</h5>
                    <div class="summary-row">
                        <span>Items Subtotal</span>
                        <span id="subtotal">₹0</span>
                    </div>
                    <div class="summary-row">
                        <span>Delivery Fee</span>
                        <span id="delivery">₹50</span>
                    </div>
                    <div class="summary-row">
                        <span>GST & Restaurant Charges (5%)</span>
                        <span id="tax">₹0</span>
                    </div>
                    <div class="summary-row total">
                        <span>Grand Total</span>
                        <span id="total">₹0</span>
                    </div>

                    <button class="btn btn-checkout" onclick="checkout()">
                        Proceed to Checkout <i class="fa-solid fa-arrow-right ms-2"></i>
                    </button>
                    <button class="btn btn-continue" onclick="continueShopping()">
                        Continue Shopping
                    </button>
                    
                    <p class="text-center mt-3 text-muted" style="font-size: 0.75rem;">
                        <i class="fa-solid fa-shield-halved"></i> Secure checkout powered by GrabBaskets
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Sticky Bottom Bar -->
    <div class="mobile-bottom-bar d-lg-none" id="mobileBottomBar">
        <div class="d-flex justify-content-between align-items-center w-100">
            <div>
                <div class="text-muted small">Total Amount</div>
                <div class="fw-bold fs-5" id="mobileTotal">₹0</div>
            </div>
            <button class="btn btn-checkout py-2 px-4 mt-0 w-auto" onclick="checkout()">
                Checkout <i class="fa-solid fa-arrow-right ms-1"></i>
            </button>
        </div>
    </div>

    <!-- DATA FOR JS -->
    <div id="products-data" style="display:none">@json($foodsForJs)</div>
    <div id="cart-data" style="display:none">@json(array_values($cartData))</div>

    <!-- SCRIPTS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Configuration
        const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const CHECKOUT_URL = "{{ route('customer.food.checkout') }}";
        const INDEX_URL = "{{ route('customer.food.index') }}";
        const UPDATE_URL_TEMPLATE = "{{ route('customer.food.cart.update', ['foodId' => 'ID']) }}";
        const REMOVE_URL_TEMPLATE = "{{ route('customer.food.cart.remove', ['foodId' => 'ID']) }}";

        // State
        const PRODUCTS = JSON.parse(document.getElementById('products-data').textContent);
        const INITIAL_CART = JSON.parse(document.getElementById('cart-data').textContent);
        let cart = INITIAL_CART.map(item => ({
            id: item.id,
            qty: item.quantity
        }));

        // DOM Elements
        const itemsContainer = document.getElementById('itemsContainer');
        const subtotalEl = document.getElementById('subtotal');
        const deliveryEl = document.getElementById('delivery');
        const taxEl = document.getElementById('tax');
        const totalEl = document.getElementById('total');
        const mobileTotalEl = document.getElementById('mobileTotal');
        const cartCountEl = document.getElementById('cartCount');
        const emptyState = document.getElementById('emptyState');
        const summarySection = document.getElementById('summarySection');
        const cartList = document.getElementById('cartList');
        const mobileBottomBar = document.getElementById('mobileBottomBar');

        function findProduct(id) {
            return PRODUCTS.find(p => p.id === id);
        }

        function renderCart() {
            itemsContainer.innerHTML = '';
            
            if (cart.length === 0) {
                emptyState.style.display = 'block';
                cartList.style.display = 'none';
                summarySection.classList.add('d-none');
                mobileBottomBar.style.display = 'none';
                cartCountEl.textContent = '0';
                return;
            }

            emptyState.style.display = 'none';
            cartList.style.display = 'block';
            summarySection.classList.remove('d-none');
            
            // Check if mobile width for bottom bar
            if(window.innerWidth < 992) {
                mobileBottomBar.style.display = 'flex';
            } else {
                mobileBottomBar.style.display = 'none';
            }

            let subtotal = 0;
            cart.forEach(entry => {
                const prod = findProduct(entry.id);
                if (!prod) return;
                
                const itemTotal = prod.price * entry.qty;
                subtotal += itemTotal;

                const itemHtml = `
                    <div class="cart-item d-flex align-items-center" id="item-row-${prod.id}">
                        <div class="item-img-wrapper">
                            <img src="${prod.img}" alt="${prod.name}">
                        </div>
                        <div class="item-details">
                            <div class="item-name">${prod.name}</div>
                            <div class="item-desc">${prod.desc}</div>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <div class="item-price">₹${prod.price.toFixed(0)}</div>
                                <div class="qty-controls">
                                    <button class="qty-btn" onclick="changeQty(${prod.id}, -1)">-</button>
                                    <span class="qty-val" id="qty-${prod.id}">${entry.qty}</span>
                                    <button class="qty-btn" onclick="changeQty(${prod.id}, 1)">+</button>
                                </div>
                            </div>
                        </div>
                        <div class="ms-3 d-flex flex-column align-items-end h-100">
                            <i class="fa-solid fa-trash-can remove-btn mb-auto" onclick="removeItem(${prod.id})" title="Remove"></i>
                            <div class="mt-auto fw-bold" style="font-size: 0.9rem;">₹${itemTotal.toFixed(0)}</div>
                        </div>
                    </div>
                `;
                itemsContainer.insertAdjacentHTML('beforeend', itemHtml);
            });

            const delivery = 50;
            const tax = Math.round(subtotal * 0.05);
            const grand = subtotal + delivery + tax;

            subtotalEl.textContent = '₹' + subtotal.toFixed(0);
            deliveryEl.textContent = '₹' + delivery;
            taxEl.textContent = '₹' + tax;
            totalEl.textContent = '₹' + grand.toFixed(0);
            mobileTotalEl.textContent = '₹' + grand.toFixed(0);
            
            const count = cart.reduce((s, c) => s + c.qty, 0);
            cartCountEl.textContent = count;
        }

        async function changeQty(id, delta) {
            const entry = cart.find(c => c.id === id);
            if (!entry) return;

            const newQty = entry.qty + delta;
            if (newQty < 1) {
                removeItem(id);
                return;
            }

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
                    renderCart();
                } else {
                    showToast('Failed to update cart', 'danger');
                }
            } catch (e) {
                console.error('Error:', e);
                showToast('Network error', 'danger');
            }
        }

        async function removeItem(id) {
            if (!confirm('Remove this item from your cart?')) return;

            const url = REMOVE_URL_TEMPLATE.replace('ID', id);
            try {
                const response = await fetch(url, {
                    method: 'GET',
                    headers: { 'X-CSRF-TOKEN': CSRF_TOKEN }
                });

                if (response.ok) {
                    const row = document.getElementById(`item-row-${id}`);
                    if (row) {
                        row.style.opacity = '0';
                        row.style.transform = 'translateX(20px)';
                        setTimeout(() => {
                            cart = cart.filter(c => c.id !== id);
                            renderCart();
                        }, 300);
                    } else {
                        cart = cart.filter(c => c.id !== id);
                        renderCart();
                    }
                } else {
                    showToast('Failed to remove item', 'danger');
                }
            } catch (e) {
                console.error('Error:', e);
            }
        }

        function checkout() {
            if (cart.length === 0) return;
            // Add loading effect
            const buttons = document.querySelectorAll('.btn-checkout');
            buttons.forEach(btn => {
                btn.disabled = true;
                btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i> Processing...';
            });
            window.location.href = CHECKOUT_URL;
        }

        function continueShopping() {
            window.location.href = INDEX_URL;
        }

        function showToast(msg, type = 'dark') {
            alert(msg);
        }

        window.addEventListener('resize', () => {
            if (cart.length > 0) {
                mobileBottomBar.style.display = (window.innerWidth < 992) ? 'flex' : 'none';
            }
        });

        // Initial Render
        renderCart();
    </script>
</body>
</html>
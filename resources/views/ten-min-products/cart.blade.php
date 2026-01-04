<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GrabBasket — Your Cart</title>

    <!-- UI Libraries -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #6d28d9;
            --secondary: #9333ea;
            --success: #16a34a;
            --bg: #f8f9fa;
            --card-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            --font: 'Poppins', sans-serif;
        }

        body {
            background-color: var(--bg);
            font-family: var(--font);
            color: #2d3436;
            padding-bottom: 80px;
        }

        /* Navbar */
        .navbar-custom {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            box-shadow: 0 8px 30px rgba(109, 40, 217, .4);
            padding: 15px 0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .brand-logo {
            font-size: 24px;
            font-weight: 800;
            color: #fff;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-section {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-name {
            color: #fff;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .logout-btn {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: #fff;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .logout-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            color: #fff;
        }

        .cart-icon-nav {
            position: relative;
            color: #fff;
            font-size: 20px;
        }

        .cart-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #ef4444;
            color: #fff;
            font-size: 11px;
            padding: 2px 6px;
            border-radius: 999px;
            font-weight: 700;
        }

        /* Cart Page */
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

        .item-layout {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .item-price-section {
            min-width: 100px;
            text-align: left;
        }

        .price-label {
            font-size: 12px;
            color: #64748b;
            margin-bottom: 4px;
        }

        .price-value {
            font-size: 20px;
            font-weight: 700;
            color: var(--success);
        }

        .item-img-wrapper {
            width: 80px;
            height: 80px;
            border-radius: 12px;
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
        }

        .item-name {
            font-weight: 600;
            font-size: 1rem;
            margin-bottom: 8px;
            color: #111;
        }

        .item-unit-price {
            font-size: 14px;
            color: #64748b;
            margin-bottom: 10px;
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
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            transition: all 0.2s;
            cursor: pointer;
        }

        .qty-btn:hover {
            background: var(--primary);
            color: #fff;
        }

        .qty-value {
            font-weight: 700;
            min-width: 25px;
            text-align: center;
        }

        .remove-btn {
            color: #ef4444;
            background: none;
            border: none;
            font-size: 14px;
            cursor: pointer;
            margin-top: 8px;
            text-decoration: underline;
            padding: 0;
        }

        /* Summary & Buttons */
        .cart-summary {
            background: #fff;
            border-radius: 20px;
            padding: 24px;
            box-shadow: var(--card-shadow);
            margin-top: 20px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            font-size: 15px;
        }

        .summary-row.total {
            border-top: 1.5px solid #f1f1f1;
            padding-top: 15px;
            margin-top: 15px;
            font-weight: 700;
            font-size: 18px;
            color: var(--primary);
        }

        .wallet-promo {
            background: #f0fdf4;
            border: 1.5px dashed #22c55e;
            padding: 14px;
            border-radius: 12px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .wallet-promo input {
            width: 20px;
            height: 20px;
            cursor: pointer;
        }

        .wallet-promo label {
            font-size: 14px;
            font-weight: 600;
            color: #166534;
            cursor: pointer;
        }

        .cart-footer {
            margin-top: 30px;
            display: flex;
            gap: 15px;
            justify-content: space-between;
        }

        .continue-btn,
        .checkout-btn {
            padding: 14px 28px;
            border-radius: 12px;
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
        }

        .continue-btn {
            background: #e2e8f0;
            color: #334155;
            border: 2px solid #e2e8f0;
        }

        .continue-btn:hover {
            background: #cbd5e1;
            color: #334155;
        }

        .checkout-btn {
            background: linear-gradient(135deg, var(--success), #22c55e);
            color: white;
            box-shadow: 0 6px 18px rgba(22, 163, 74, 0.3);
            border: none;
        }

        .checkout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(22, 163, 74, 0.4);
            color: white;
        }

        /* Empty State */
        .empty-cart-container {
            text-align: center;
            padding: 60px 20px;
            background: #fff;
            border-radius: 20px;
            box-shadow: var(--card-shadow);
        }

        .empty-icon {
            font-size: 80px;
            color: #dfe6e9;
            margin-bottom: 20px;
        }

        /* Mobile Sticky Bottom */
        .mobile-bottom-bar {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: #fff;
            padding: 15px 20px;
            box-shadow: 0 -5px 20px rgba(0, 0, 0, 0.1);
            display: none;
            z-index: 1001;
            border-top-left-radius: 20px;
            border-top-right-radius: 20px;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .mobile-bottom-bar {
                display: flex;
                flex-direction: column;
                gap: 10px;
            }

            .user-section {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }

            .logout-btn {
                padding: 6px 12px;
                font-size: 14px;
            }

            .cart-footer {
                flex-direction: column;
            }

            .continue-btn,
            .checkout-btn {
                width: 100%;
                justify-content: center;
            }

            .item-layout {
                flex-wrap: wrap;
            }

            .item-price-section {
                order: -1;
                width: 100%;
                margin-bottom: 10px;
            }

            .item-img-wrapper {
                width: 70px;
                height: 70px;
            }
        }

        @media (max-width: 576px) {
            .brand-logo {
                font-size: 18px;
            }

            .user-name {
                font-size: 14px;
            }
        }
    </style>
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar-custom">
        <div class="container d-flex justify-content-between align-items-center">
            <a href="{{ route('ten.min.products') }}" class="brand-logo">
                <i class="fa-solid fa-basket-shopping"></i> GrabBaskets
            </a>

            <div class="user-section">
                @auth
                    <div class="user-name">
                        <i class="fa-solid fa-user-circle"></i>
                        <span class="d-none d-md-inline">{{ auth()->user()->name }}</span>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="logout-btn">
                            <i class="fa-solid fa-right-from-bracket"></i>
                            <span class="d-none d-sm-inline">Logout</span>
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="logout-btn">
                        <i class="fa-solid fa-right-to-bracket"></i> Login
                    </a>
                @endauth

                <a href="{{ route('tenmin.cart.view') }}" class="cart-icon-nav">
                    <i class="fa-solid fa-cart-shopping"></i>
                    @if($cartCount > 0)
                        <span class="cart-badge">{{ $cartCount }}</span>
                    @endif
                </a>
            </div>
        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <div class="container">
        <h2 class="cart-title">Your 10-Minute Cart</h2>

        <!-- Flash Messages -->
        @if(session('error'))
            <div class="alert alert-danger" role="alert">
                {{ session('error') }}
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        @if($cartItems->isEmpty())
            <div class="empty-cart-container">
                <i class="fa-solid fa-cart-arrow-down empty-icon"></i>
                <h3>Your cart is empty</h3>
                <p class="text-muted">Add some fast-delivery items to get started!</p>
                <a href="{{ route('ten.min.products') }}" class="btn btn-primary rounded-pill px-4 mt-2">
                    Browse Products
                </a>
            </div>
        @else
            <div class="row">
                <div class="col-12">
                    <div class="cart-card">
                        @foreach($cartItems as $item)
                            <div class="cart-item" data-product-id="{{ $item->product_id }}" data-price="{{ $item->price }}">
                                <div class="item-layout">
                                    <!-- Price Section (Left Side) -->
                                    <div class="item-price-section">
                                        <div class="price-label">Total</div>
                                        <div class="price-value price-total">
                                            ₹{{ number_format((float) ($item->price * $item->quantity), 0) }}</div>
                                    </div>

                                    <!-- Image -->
                                    <div class="item-img-wrapper">
                                        <img src="{{ $item->image_url ?? 'https://via.placeholder.com/80' }}"
                                            alt="{{ $item->name }}">
                                    </div>

                                    <!-- Details -->
                                    <div class="item-details">
                                        <div class="item-name">{{ $item->name }}</div>
                                        <div class="item-unit-price">
                                            ₹{{ number_format((float) $item->price, 0) }} × <span
                                                class="qty-display">{{ $item->quantity }}</span>
                                        </div>

                                        <div class="qty-controls">
                                            <button class="qty-btn minus" data-id="{{ $item->product_id }}">−</button>
                                            <span class="qty-value">{{ $item->quantity }}</span>
                                            <button class="qty-btn plus" data-id="{{ $item->product_id }}">+</button>
                                        </div>

                                        <button class="remove-btn" data-id="{{ $item->product_id }}">Remove</button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Summary Box -->
                    <div class="cart-summary">
                        <div class="wallet-promo">
                            <input type="checkbox" id="cartUseWallet" onchange="renderCartTotals()">
                            <label for="cartUseWallet">
                                🎁 Use Wallet (Save 15%) — Balance: ₹{{ number_format($walletPoint, 0) }}
                            </label>
                        </div>

                        <div class="summary-row">
                            <span>Subtotal</span>
                            <span id="summarySubtotal">₹0</span>
                        </div>
                        <div class="summary-row" id="walletDiscountRow" style="display: none; color: var(--success);">
                            <span>Wallet Discount (15%)</span>
                            <span id="summaryDiscount">-₹0</span>
                        </div>
                        <div class="summary-row total">
                            <span>Grand Total</span>
                            <span id="summaryGrandTotal">₹0</span>
                        </div>
                    </div>

                    <!-- Footer Buttons -->
                    <div class="cart-footer">
                        <a href="{{ route('ten.min.products') }}" class="continue-btn">
                            <i class="fa-solid fa-arrow-left"></i> Continue Shopping
                        </a>
                        <a href="{{ route('tenmin.checkout') }}" class="checkout-btn">
                            Proceed to Checkout <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Mobile Sticky Bottom Bar -->
            <div class="mobile-bottom-bar d-md-none">
                <a href="{{ route('tenmin.checkout') }}" class="btn checkout-btn w-100 mt-0">
                    Proceed to Checkout <i class="fa-solid fa-arrow-right ms-1"></i>
                </a>
            </div>
        @endif
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const csrfToken = '{{ csrf_token() }}';
            const walletPoint = {{ $walletPoint }};

            // Load wallet preference
            const savedWalletPref = localStorage.getItem('tenmin_use_wallet');
            if (savedWalletPref === 'true') {
                document.getElementById('cartUseWallet').checked = true;
            }

            renderCartTotals();

            function renderCartTotals() {
                const items = document.querySelectorAll('.cart-item');
                let subtotal = 0;
                items.forEach(item => {
                    const price = parseFloat(item.dataset.price);
                    const qty = parseInt(item.querySelector('.qty-value').textContent);
                    subtotal += price * qty;
                });

                document.getElementById('summarySubtotal').textContent = '₹' + subtotal.toFixed(0);

                const useWallet = document.getElementById('cartUseWallet').checked;
                localStorage.setItem('tenmin_use_wallet', useWallet);

                let discount = 0;
                if (useWallet && walletPoint > 0) {
                    discount = Math.round(Math.min(0.15 * subtotal, walletPoint));
                }

                if (discount > 0) {
                    document.getElementById('walletDiscountRow').style.display = 'flex';
                    document.getElementById('summaryDiscount').textContent = '-₹' + discount;
                } else {
                    document.getElementById('walletDiscountRow').style.display = 'none';
                }

                const grandTotal = subtotal - discount;
                document.getElementById('summaryGrandTotal').textContent = '₹' + grandTotal.toFixed(0);
            }

            window.renderCartTotals = renderCartTotals; // Expose to global if needed

            // Update quantity (+ / -)
            document.querySelectorAll('.plus').forEach(btn => {
                btn.addEventListener('click', function () {
                    const id = this.dataset.id;
                    updateQuantity(id, 1);
                });
            });

            document.querySelectorAll('.minus').forEach(btn => {
                btn.addEventListener('click', function () {
                    const id = this.dataset.id;
                    updateQuantity(id, -1);
                });
            });

            // Remove item
            document.querySelectorAll('.remove-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    if (!confirm('Remove this item from your cart?')) return;
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
                            qtyEl.textContent = newQty;
                            qtyDisplay.textContent = newQty;
                            totalEl.textContent = '₹' + (unitPrice * newQty).toFixed(0);
                            renderCartTotals();
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
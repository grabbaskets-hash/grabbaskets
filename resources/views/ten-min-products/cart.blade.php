<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Cart - GrabBasket</title>
    
    <!-- Fonts & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --brand-primary: #9333ea;
            --brand-secondary: #0c831f; 
            --text-dark: #111827;
            --text-gray: #6b7280;
            --bg-body: #f8fafc;
            --surface-white: #ffffff;
            --border-color: #e2e8f0;
            --shadow-soft: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            --radius-default: 16px;
        }

        body { background: var(--bg-body); font-family: 'Inter', sans-serif; color: var(--text-dark); padding-bottom: 120px; }
        
        /* NAVBAR - Matches Product Page V2 */
        .navbar-custom {
            background: linear-gradient(120deg, #7e22ce, #a855f7);
            padding: 16px 0;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            position: sticky; top:0; z-index: 100;
            box-shadow: 0 4px 20px rgba(109, 40, 217, 0.25);
            backdrop-filter: blur(10px);
            color: white;
        }
        .nav-logo { font-family: 'Outfit', sans-serif; font-weight: 800; font-size: 1.7rem; color: #fff; text-decoration: none; display: flex; align-items: center; gap: 10px; text-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .nav-logo:hover { transform: scale(1.02); transition: 0.3s; color: #fff; }

        /* CONTAINERS */
        .cart-wrapper { max-width: 1100px; margin: 3rem auto; padding: 0 1rem; }
        .page-title { font-family: 'Outfit', sans-serif; font-size: 2rem; font-weight: 800; margin-bottom: 1.5rem; color: var(--text-dark); }

        /* LEFT: ITEMS */
        .delivery-ticket {
            background: #fff; border-radius: var(--radius-default); padding: 20px; margin-bottom: 1.5rem;
            display: flex; align-items: center; gap: 20px; 
            border: 2px dashed #bbf7d0; position: relative;
            box-shadow: var(--shadow-soft);
        }
        .delivery-ticket::before, .delivery-ticket::after {
            content: ""; position: absolute; left: -10px; top: 50%; transform: translateY(-50%);
            width: 20px; height: 20px; background: var(--bg-body); border-radius: 50%;
        }
        .delivery-ticket::after { left: auto; right: -10px; }
        .delivery-icon {
            width: 56px; height: 56px; background: #ecfccb; border-radius: 50%; 
            display: flex; align-items: center; justify-content: center; color: #4d7c0f; font-size: 1.5rem;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.05);
        }

        .cart-items-container { 
            background: var(--surface-white); border-radius: var(--radius-default); padding: 0 24px; 
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05); border: 1px solid var(--border-color);
        }
        .cart-item-row {
            display: flex; align-items: center; padding: 24px 0; border-bottom: 1px solid var(--border-color);
            gap: 24px; transition: 0.2s;
        }
        .cart-item-row:hover { background: #fafafa; }
        .cart-item-row:last-child { border-bottom: none; }
        
        .item-thumb { width: 80px; height: 80px; object-fit: contain; border-radius: 12px; border: 1px solid #f1f5f9; padding: 4px; background: #fff; }
        
        .item-details { flex: 1; }
        .item-name { font-family: 'Outfit', sans-serif; font-size: 1.15rem; font-weight: 700; margin-bottom: 4px; line-height: 1.3; color: var(--text-dark); }
        .item-unit { font-size: 0.85rem; color: var(--text-gray); margin-bottom: 6px; }
        .item-price { font-weight: 800; font-size: 1.1rem; color: var(--text-dark); }

        /* QTY */
        .qty-picker {
            background: #fff; color: var(--text-dark); border-radius: 12px; border: 1px solid #e2e8f0;
            display: flex; align-items: center; padding: 6px 6px; width: 110px; justify-content: space-between;
            font-size: 1rem; font-weight: 700; box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .qty-action { 
            background: #f1f5f9; border: none; border-radius: 8px; color: var(--text-dark); 
            cursor: pointer; height: 32px; width: 32px; display: flex; align-items: center; justify-content: center; transition: 0.2s; 
        }
        .qty-action:hover { background: #e2e8f0; color: var(--brand-primary); }
        
        /* RIGHT: BILL (RECEIPT STYLE) */
        .receipt-card { 
            background: #fff; padding: 0; 
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1); 
            position: sticky; top: 100px; 
             /* Jagged edge usually requires complex masking or images. We'll use a simple CSS trick for a "serrated" look at the bottom */
            position: sticky; top: 100px;
            filter: drop-shadow(0 4px 6px rgba(0,0,0,0.05));
        }
        
        .receipt-top {
            background: #fff; padding: 24px; border-radius: 16px 16px 0 0;
        }
        .receipt-bottom {
             background: #fff; height: 16px; border-radius: 0 0 16px 16px; 
             background-image: radial-gradient(circle, transparent 50%, #fff 50%);
             background-size: 20px 20px;
             background-position: 0 100%;
             margin-top: -10px; /* Slight overlap tweak if needed, or just standard border */
             /* Reverting to standard visually clean card for compatibility robustness */
        }
        .receipt-actual {
            background: #fff; border-radius: 16px; overflow: hidden; position: sticky; top: 100px;
            border: 1px solid var(--border-color);
        }

        .bill-header { font-family: 'Outfit', sans-serif; font-weight: 800; font-size: 1.2rem; margin-bottom: 20px; border-bottom: 2px dashed #f1f5f9; padding-bottom: 15px;}
        .bill-row { display: flex; justify-content: space-between; margin-bottom: 16px; font-size: 0.95rem; color: #4b5563; }
        .bill-row.total { 
            border-top: 2px dashed var(--text-dark); padding-top: 20px; margin-top: 20px;
            color: var(--text-dark); font-weight: 900; font-size: 1.4rem; font-family: 'Outfit', sans-serif;
        }
        
        /* CHECKOUT BTN */
        .checkout-bar {
            background: rgba(255,255,255,0.9); backdrop-filter: blur(10px); 
            position: fixed; bottom: 0; left: 0; right: 0;
            padding: 16px 24px; box-shadow: 0 -4px 20px rgba(0,0,0,0.08); z-index: 999;
            display: flex; justify-content: center; border-top: 1px solid #e2e8f0;
        }
        .checkout-btn {
            background: linear-gradient(135deg, #0c831f, #15803d); color: #fff; border-radius: 16px; 
            padding: 16px 40px; width: 100%; max-width: 600px;
            display: flex; justify-content: space-between; align-items: center;
            text-decoration: none; font-weight: 700; font-size: 1.2rem;
            box-shadow: 0 10px 20px rgba(21, 128, 61, 0.3);
            transition: all 0.2s; font-family: 'Outfit', sans-serif;
        }
        .checkout-btn:hover { color: #fff; transform: translateY(-3px); box-shadow: 0 15px 30px rgba(21, 128, 61, 0.4); }

        /* EMPTY STATE */
        .empty-cart { text-align: center; padding: 6rem 1rem; background: var(--surface-white); border-radius: 20px; border: 2px dashed var(--border-color); }
        .empty-icon { font-size: 6rem; color: #cbd5e1; margin-bottom: 2rem; animation: float 6s ease-in-out infinite; }
        
        @keyframes float { 0% {transform:translateY(0)} 50% {transform:translateY(-10px)} 100% {transform:translateY(0)} }
    </style>
</head>
<body>

    <nav class="navbar-custom">
        <div class="container d-flex justify-content-between align-items-center">
            <a href="{{ route('ten.min.products') }}" class="nav-logo"><i class="fa-solid fa-bag-shopping" style="color:#fbbf24;"></i> GrabBaskets</a>
            <div class="d-none d-md-flex gap-3 text-white-50 small align-items-center">
                <span class="text-white"><i class="fa-solid fa-shield-halved"></i> 100% Secure Payments</span>
                @auth <span class="badge bg-white text-dark rounded-pill px-3 py-2"><i class="fa-solid fa-user me-1"></i> {{ auth()->user()->name }}</span> @endauth
            </div>
        </div>
    </nav>

    <div class="container cart-wrapper">
        
        @if(session('error'))
            <div class="alert alert-danger shadow-sm border-0 rounded-4 mb-4">{{ session('error') }}</div>
        @endif

        @if($cartItems->isEmpty())
            <div class="empty-cart mt-4">
                <i class="fa-solid fa-basket-shopping empty-icon"></i>
                <h3 style="font-family: 'Outfit', sans-serif; font-weight: 800; color: var(--text-dark); margin-bottom: 10px;">Your Cart is Empty</h3>
                <p class="text-muted mb-5">Your pantry looks a bit lonely. Let's fill it up!</p>
                <a href="{{ route('ten.min.products') }}" class="btn btn-warning fw-bold px-5 py-3 rounded-pill shadow-lg text-dark" style="background: #facc15; border:none; font-size: 1.1rem; transition: 0.2s;">Start Shopping</a>
            </div>
        @else
            <h2 class="page-title mt-2">My Cart</h2>

            <div class="row">
                <!-- ITEMS SECTION -->
                <div class="col-lg-8">
                    <div class="delivery-ticket">
                        <div class="delivery-icon"><i class="fa-solid fa-bolt-lightning"></i></div>
                        <div>
                            <div style="font-weight: 800; color: #3f6212; font-size: 1.1rem; font-family: 'Outfit', sans-serif;">Delivery in 10 minutes</div>
                            <div class="small text-muted">Superfast delivery for your {{ $cartCount }} items</div>
                        </div>
                    </div>

                    <div class="cart-items-container">
                        @foreach($cartItems as $item)
                        <div class="cart-item-row cart-item" data-product-id="{{ $item->product_id }}" data-price="{{ $item->price }}">
                            <!-- Image -->
                            <img src="{{ $item->image_url ?? 'https://via.placeholder.com/80' }}" class="item-thumb" alt="{{ $item->name }}">
                            
                            <!-- Info -->
                            <div class="item-details">
                                <div class="item-name">{{ $item->name }}</div>
                                <div class="item-unit">1 unit</div>
                                <div class="item-price">₹{{ number_format((float)$item->price, 0) }}</div>
                            </div>

                            <!-- Qty Control -->
                            <div class="qty-picker">
                                <button class="qty-action minus" data-id="{{ $item->product_id }}"><i class="fa-solid fa-minus" style="font-size: 0.8rem;"></i></button>
                                <span class="qty-value px-1">{{ $item->quantity }}</span>
                                <button class="qty-action plus" data-id="{{ $item->product_id }}"><i class="fa-solid fa-plus" style="font-size: 0.8rem;"></i></button>
                            </div>
                            <!-- Helper for js total calc -->
                            <div class="d-none price-total">{{ $item->price * $item->quantity }}</div> 
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- BILL SECTION -->
                <div class="col-lg-4 mt-4 mt-lg-0">
                    <div class="receipt-actual">
                        <div class="receipt-top">
                            <div class="bill-header">Bill Summary</div>
                            
                            <div class="bill-row">
                                <span>Item Total</span>
                                <span id="summarySubtotal" style="font-weight: 600; color: var(--text-dark);">₹0</span>
                            </div>
                            <div class="bill-row">
                                <span>Delivery Fee</span>
                                <span class="text-success fw-bold">FREE</span>
                            </div>
                            
                            <!-- Wallet -->
                            <div class="py-3 px-0 border-top border-bottom my-3" style="border-color: #f1f5f9 !important;">
                                <div class="d-flex align-items-center gap-2">
                                     <input class="form-check-input m-0" type="checkbox" id="cartUseWallet" onchange="renderCartTotals()" style="width: 1.2em; height: 1.2em; cursor: pointer;">
                                     <div class="flex-grow-1">
                                        <div style="font-weight: 700; font-size: 0.9rem; color: #15803d;">Use Wallet</div>
                                        <div style="font-size: 0.75rem; color: #166534;">Bal: ₹{{ number_format($walletPoint, 0) }}</div>
                                     </div>
                                </div>
                            </div>

                            <div class="bill-row" id="walletDiscountRow" style="display:none; color: var(--brand-secondary);">
                                <span>Wallet Discount</span>
                                <span id="summaryDiscount" style="font-weight: 700;">-₹0</span>
                            </div>
                            <div class="bill-row total">
                                <span>To Pay</span>
                                <span id="summaryGrandTotal">₹0</span>
                            </div>
                        </div>
                        <!-- Decorative serrated bottom if we used complex css, but simple rounded is cleaner for now -->
                    </div>
                </div>
            </div>

            <!-- STICKY CHECKOUT FOOTER -->
            <div class="checkout-bar">
                <a href="{{ route('tenmin.checkout') }}" class="checkout-btn">
                    <div style="display:flex; flex-direction:column; align-items:flex-start; line-height:1.1;">
                        <span style="font-size:0.85rem; opacity:0.9; font-weight: 500; text-transform: uppercase; letter-spacing: 1px;">Total</span>
                        <span id="stickyTotal">₹0</span>
                    </div>
                    <div>
                        Proceed <i class="fa-solid fa-arrow-right-long ms-2"></i>
                    </div>
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
            const walletCheckbox = document.getElementById('cartUseWallet');

            if(localStorage.getItem('tenmin_use_wallet') === 'true' && walletCheckbox) {
                walletCheckbox.checked = true;
            }

            renderCartTotals();

            function renderCartTotals() {
                const items = document.querySelectorAll('.cart-item');
                if(items.length === 0) return; 

                let subtotal = 0;
                items.forEach(div => {
                    const price = parseFloat(div.dataset.price);
                    const qty = parseInt(div.querySelector('.qty-value').innerText);
                    subtotal += price * qty;
                });

                const summarySubEl = document.getElementById('summarySubtotal');
                if(summarySubEl) summarySubEl.innerText = '₹' + subtotal.toFixed(0);

                let discount = 0;
                const isWalletChecked = walletCheckbox ? walletCheckbox.checked : false;
                if(walletCheckbox) localStorage.setItem('tenmin_use_wallet', isWalletChecked);

                if (isWalletChecked && walletPoint > 0) {
                     discount = Math.round(Math.min(0.15 * subtotal, walletPoint));
                }

                const discountRow = document.getElementById('walletDiscountRow');
                const discountEl = document.getElementById('summaryDiscount');
                if(discount > 0) {
                    if(discountRow) discountRow.style.display = 'flex';
                    if(discountEl) discountEl.innerText = '-₹' + discount;
                } else {
                    if(discountRow) discountRow.style.display = 'none';
                }

                const grandTotal = subtotal - discount;
                const grandTotalEl = document.getElementById('summaryGrandTotal');
                const stickyTotalEl = document.getElementById('stickyTotal');
                
                const fmtTotal = '₹' + grandTotal.toFixed(0);
                if(grandTotalEl) grandTotalEl.innerText = fmtTotal;
                if(stickyTotalEl) stickyTotalEl.innerText = fmtTotal;
            }
            window.renderCartTotals = renderCartTotals;

            document.body.addEventListener('click', function(e) {
                const btn = e.target.closest('.qty-action');
                if(btn) {
                    const id = btn.dataset.id;
                    const isPlus = btn.classList.contains('plus');
                    const change = isPlus ? 1 : -1;
                    updateQuantity(id, change);
                }
            });

            function updateQuantity(productId, change) {
                const row = document.querySelector(`.cart-item[data-product-id="${productId}"]`);
                if(!row) return;

                const qtyValEl = row.querySelector('.qty-value');
                const currentQty = parseInt(qtyValEl.innerText);
                const newQty = currentQty + change;
                
                if(newQty < 1) {
                    if(confirm("Remove item from cart?")) {
                       removeItem(productId);
                    }
                    return;
                }

                qtyValEl.innerText = newQty;
                renderCartTotals(); 

                fetch("{{ route('tenmin.cart.update') }}", {
                    method: "POST",
                    headers: { "X-CSRF-TOKEN": csrfToken, "Content-Type": "application/json" },
                    body: JSON.stringify({ product_id: productId, quantity: newQty })
                })
                .then(res => res.json())
                .then(data => {
                    if(!data.success) {
                        alert(data.error || 'Check stock');
                        qtyValEl.innerText = currentQty;
                        renderCartTotals();
                    }
                })
                .catch(err => console.error(err));
            }

            function removeItem(productId) {
                 fetch("{{ route('tenmin.cart.remove') }}", {
                    method: "POST",
                    headers: { "X-CSRF-TOKEN": csrfToken, "Content-Type": "application/json" },
                    body: JSON.stringify({ product_id: productId })
                })
                .then(res => res.json())
                .then(data => { location.reload(); });
            }
        });
    </script>
</body>
</html>
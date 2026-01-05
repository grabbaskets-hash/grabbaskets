<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders | 10-Mins Food</title>

    <!-- Bootstrap 5.3.2 (fixed URL) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f1f3f6;
        }

        .filter-box {
            background: #fff;
            padding: 20px;
            border-radius: 6px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        }

        .order-card {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
            margin-bottom: 1.5rem;
        }

        .order-img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 6px;
        }

        .shop-box {
            background: #f8f9fa;
            border-left: 4px solid #0d6efd;
            padding: 10px;
            border-radius: 6px;
        }

        .status-badge {
            font-size: 0.8rem;
            padding: 6px 10px;
            font-weight: 500;
        }

        .search-section {
            background: #fff;
            padding: 16px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
            margin-bottom: 1.5rem;
        }
    </style>
</head>

<body>

    <div class="container mt-4">
        <div class="row">
            <!-- LEFT FILTER -->
            <div class="col-lg-3 mb-3">
                <div class="filter-box">
                    <h6 class="fw-bold mb-3">Filters</h6>

                    <p class="fw-semibold mb-2">Order Status</p>
                    <div class="form-check mb-1">
                        <input class="form-check-input" type="checkbox" id="status-onway" value="On the way">
                        <label class="form-check-label" for="status-onway">On the way</label>
                    </div>
                    <div class="form-check mb-1">
                        <input class="form-check-input" type="checkbox" id="status-delivered" value="Delivered">
                        <label class="form-check-label" for="status-delivered">Delivered</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="status-cancelled" value="Cancelled">
                        <label class="form-check-label" for="status-cancelled">Cancelled</label>
                    </div>
                </div>
            </div>

            <!-- RIGHT CONTENT -->
            <div class="col-lg-9">
                <!-- Search Section -->
                <div class="search-section">
                    <div class="input-group">
                        <input type="text" class="form-control" id="search-input" placeholder="Search by Order ID or Food Name">
                        <button class="btn btn-dark" id="search-btn">
                            <i class="fas fa-search"></i> Search
                        </button>
                    </div>
                </div>

                @if($orders->count() > 0)
                    <p class="fw-semibold">
                        {{ $orders->count() }} Order{{ $orders->count() === 1 ? '' : 's' }} Found
                    </p>

                    @foreach($orders as $order)
                        <div class="order-card">
                            <!-- ORDER HEADER -->
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <strong>Order #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</strong><br>
                                    <small class="text-muted">
                                        Ordered on {{ $order->created_at->format('M d, Y \a\t h:i A') }}
                                    </small>
                                </div>

                                <span class="badge status-badge rounded-pill
                                    @if($order->status === 'Delivered') bg-success
                                    @elseif($order->status === 'Cancelled') bg-danger
                                    @elseif($order->status === 'On the way') bg-warning text-dark
                                    @else bg-secondary @endif">
                                    {{ $order->status }}
                                </span>
                            </div>

                            <hr class="my-3">

                            <!-- SHOP INFO -->
                            <div class="shop-box mb-3 p-2">
                                <div class="fw-semibold text-dark d-flex align-items-center">
                                    <span class="me-2">🏪</span>
                                    {{ $order->shop_name ?? 'Shop Name' }}
                                </div>
                                <div class="small text-muted d-flex align-items-start mt-1">
                                    <span class="me-2">📍</span>
                                    {{ $order->shop_address ?? 'Shop Address' }}
                                </div>
                            </div>

                            <hr class="my-3">

                            <!-- ORDER ITEMS -->
                            @foreach($order->items as $item)
                                <div class="d-flex align-items-center mb-3">
                                    <img 
                                        src="{{ $item->image ?? 'https://via.placeholder.com/80' }}" 
                                        class="order-img me-3"
                                        alt="{{ $item->food_name }}"
                                        loading="lazy"
                                    >

                                    <div>
                                        <h6 class="mb-1">{{ $item->food_name }}</h6>
                                        <small class="text-muted d-block">{{ $item->category ?? '–' }}</small>
                                        <div class="mt-1">
                                            <strong>₹{{ number_format($item->price, 2) }}</strong>
                                            @if($item->quantity > 1)
                                                <span class="text-muted ms-2">× {{ $item->quantity }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            <!-- TOTAL & ACTIONS -->
                            <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top">
                                <div>
                                    <small class="text-muted">
                                        Total: <strong>₹{{ number_format($order->total_amount, 2) }}</strong>
                                    </small>
                                </div>
                                <a href="{{ route('food.order.details', $order->id) }}" class="text-primary fw-semibold small">
                                    View Order Details →
                                </a>
                            </div>
                        </div>
                    @endforeach

                    <div class="text-center text-muted my-4 py-2">
                        <small>— End of Orders —</small>
                    </div>

                @else
                    <!-- EMPTY STATE -->
                    <div class="text-center mt-5">
                        <img 
                            src="https://cdn-icons-png.flaticon.com/512/2038/2038854.png" 
                            width="160" 
                            class="mb-3"
                            alt="No orders"
                        >

                        <h5 class="fw-bold text-secondary">You Have No Orders Yet</h5>
                        <p class="text-muted small mb-4">
                            Start ordering your favorite food — your orders will appear here.
                        </p>

                        <a href="{{ route('customer.food.index') }}" class="btn btn-primary px-4 py-2">
                            🍔 Browse Food
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Optional: Font Awesome for search icon (lightweight) -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

    <!-- Optional: JS for dynamic filtering (uncomment & enhance as needed) -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchBtn = document.getElementById('search-btn');
            const searchInput = document.getElementById('search-input');
            const checkboxes = document.querySelectorAll('.form-check-input');

            // Search on button click
            searchBtn?.addEventListener('click', function () {
                const query = searchInput.value.trim();
                if (query) {
                    const url = new URL(window.location);
                    url.searchParams.set('search', query);
                    window.location.href = url.toString();
                }
            });

            // Allow Enter key to trigger search
            searchInput?.addEventListener('keypress', function (e) {
                if (e.key === 'Enter') {
                    searchBtn?.click();
                }
            });

            // Status filter (multi-select)
            checkboxes.forEach(cb => {
                cb.addEventListener('change', function () {
                    const selectedStatuses = Array.from(checkboxes)
                        .filter(c => c.checked)
                        .map(c => c.value);

                    const url = new URL(window.location);
                    if (selectedStatuses.length > 0) {
                        url.searchParams.set('status', selectedStatuses.join(','));
                    } else {
                        url.searchParams.delete('status');
                    }
                    window.location.href = url.toString();
                });
            });

            // Pre-check filters if present in URL
            const urlParams = new URLSearchParams(window.location.search);
            const statusParam = urlParams.get('status');
            if (statusParam) {
                const statuses = statusParam.split(',');
                checkboxes.forEach(cb => {
                    if (statuses.includes(cb.value)) {
                        cb.checked = true;
                    }
                });
            }

            const searchParam = urlParams.get('search');
            if (searchParam) {
                searchInput.value = searchParam;
            }
        });
    </script>

</body>

</html>
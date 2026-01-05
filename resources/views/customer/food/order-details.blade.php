<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }} | 10-Mins Food</title>

    <!-- Bootstrap 5.3.2 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f8f9fa;
        }

        .card-header {
            font-weight: 600;
        }

        .order-badge {
            font-size: 0.85rem;
            padding: 6px 12px;
        }

        .list-group-item {
            border-left: 4px solid #e9ecef;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            text-decoration: none;
            color: #0d6efd;
        }

        .back-link:hover {
            color: #0a58ca;
            text-decoration: underline;
        }

        .back-link i {
            margin-right: 4px;
        }
    </style>
</head>

<body>

    <div class="container mt-4 mb-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                <!-- Back Link -->
                <div class="mb-3">
                    <a href="{{ route('food.my-orders') }}" class="back-link">
                        <i class="fas fa-arrow-left"></i> Back to Orders
                    </a>
                </div>

                <!-- Order Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="fw-bold">Order #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</h2>
                    <span class="badge order-badge rounded-pill
                        @if($order->status === 'Delivered') bg-success
                        @elseif($order->status === 'Cancelled') bg-danger
                        @elseif($order->status === 'On the way') bg-warning text-dark
                        @else bg-secondary @endif">
                        {{ $order->status }}
                    </span>
                </div>

                <!-- Summary Card -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-light">
                        📋 Order Summary
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <strong>Placed on:</strong><br>
                                {{ $order->created_at->format('M d, Y \a\t h:i A') }}
                            </div>
                            <div class="col-md-6 mb-2">
                                <strong>Est. Delivery:</strong><br>
                                {{ $order->estimated_delivery_time?->format('M d, Y \a\t h:i A') ?? '—' }}
                            </div>
                            <div class="col-12 mb-2">
                                <strong>Payment Method:</strong><br>
                                {{ ucfirst($order->payment_method) }}
                                @if($order->payment_reference)
                                    <small class="text-muted">(Ref: {{ substr($order->payment_reference, -6) }})</small>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Restaurant Card -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-light">
                        🏪 {{ $order->shop_name ?? 'Restaurant' }}
                    </div>
                    <div class="card-body">
                        <p class="mb-0">
                            <strong>Address:</strong><br>
                            {{ $order->shop_address ?? '—' }}
                        </p>
                    </div>
                </div>

                <!-- Delivery Address Card -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-light">
                        📍 Delivery Address
                    </div>
                    <div class="card-body">
                        <p class="mb-0">
                            <strong>{{ $order->customer_name }}</strong><br>
                            📞 {{ $order->customer_phone }}<br>
                            🏠 {{ $order->delivery_address ?? '—' }}
                        </p>
                    </div>
                </div>

                <!-- Items Card -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-light">
                        🍔 Items ({{ $order->items->count() }})
                    </div>
                    <ul class="list-group list-group-flush">
                        @foreach($order->items as $item)
                            <li class="list-group-item">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="mb-1">{{ $item->food_name }}</h6>
                                        <small class="text-muted">
                                            {{ $item->category ?? '—' }} • {{ ucfirst($item->food_type ?? '—') }}
                                        </small>
                                    </div>
                                    <div class="text-end">
                                        <div>₹{{ number_format($item->price, 2) }}</div>
                                        @if($item->quantity > 1)
                                            <small class="text-muted">×{{ $item->quantity }}</small>
                                        @endif
                                    </div>
                                </div>
                                @if($item->quantity > 1)
                                    <div class="mt-1 text-end fw-bold">
                                        ₹{{ number_format($item->price * $item->quantity, 2) }}
                                    </div>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Totals Card -->
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        💰 Total Amount
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Food Total:</span>
                            <span>₹{{ number_format($order->food_total, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Delivery Fee:</span>
                            <span>₹{{ number_format($order->delivery_fee, 2) }}</span>
                        </div>
                        @if($order->wallet_discount > 0)
                        <div class="d-flex justify-content-between mb-2 text-success">
                            <span>Wallet Discount:</span>
                            <span>-₹{{ number_format($order->wallet_discount, 2) }}</span>
                        </div>
                        @endif
                        <hr>
                        <div class="d-flex justify-content-between fw-bold fs-5">
                            <span>Total Paid:</span>
                            <span>₹{{ number_format($order->total_amount, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Back Button -->
                <div class="text-center mt-4">
                    <a href="{{ route('food.my-orders') }}" class="btn btn-outline-primary px-4">
                        ← Back to My Orders
                    </a>
                </div>

            </div>
        </div>
    </div>

    <!-- Font Awesome (for arrow icon) -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

</body>

</html>
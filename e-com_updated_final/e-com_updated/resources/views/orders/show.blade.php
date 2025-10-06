<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4"><i class="bi bi-receipt"></i> Order #{{ $order->id }}</h2>
        <div class="card mb-4">
            <div class="card-body">
                <h4 class="card-title">{{ $order->product->name ?? 'Product' }}</h4>
                <p><strong>Status:</strong> <span class="badge bg-primary">{{ ucfirst($order->status) }}</span></p>
                <p><strong>Amount:</strong> ₹{{ number_format($order->amount, 2) }}</p>
                <p><strong>Payment Method:</strong> {{ ucfirst($order->payment_method) }}</p>
                <p><strong>Payment Reference:</strong> {{ $order->payment_reference }}</p>
                @if($order->tracking_number)
                    <p><strong>Tracking Number:</strong> <span class="badge bg-success">{{ $order->tracking_number }}</span></p>
                @endif
                <hr>
                <h5>Delivery Address</h5>
                <p>{{ $order->delivery_address }}<br>
                {{ $order->delivery_city }}, {{ $order->delivery_state }} - {{ $order->delivery_pincode }}</p>
                <hr>
                <p><strong>Order Date:</strong> {{ $order->created_at->format('d M Y, h:i A') }}</p>
            </div>
        </div>
        <a href="{{ route('orders.track') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Back to Orders</a>
    </div>
</body>
</html>

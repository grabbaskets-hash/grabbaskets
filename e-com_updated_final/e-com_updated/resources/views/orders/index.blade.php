@extends('layouts.app')

@section('title', 'My Orders')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="text-dark">My Orders</h2>
                <a href="{{ route('index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Shop
                </a>
            </div>
            
            @if($orders->count() > 0)
                <div class="row">
                    @foreach($orders as $order)
                        <div class="col-12 mb-4">
                            <div class="card shadow-sm border-0">
                                <div class="card-header bg-white border-bottom">
                                    <div class="row align-items-center">
                                        <div class="col-md-6">
                                            <h5 class="mb-1">Order #{{ $order->id }}</h5>
                                            <small class="text-muted">
                                                Placed on {{ $order->created_at->format('M d, Y \a\t h:i A') }}
                                            </small>
                                        </div>
                                        <div class="col-md-6 text-md-end">
                                            <span class="badge badge-{{ $order->status == 'delivered' ? 'success' : ($order->status == 'pending' ? 'warning' : 'info') }} p-2">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="card-body">
                                    @if($order->orderItems && $order->orderItems->count() > 0)
                                        @foreach($order->orderItems as $item)
                                            <div class="row align-items-center mb-3 {{ !$loop->last ? 'border-bottom pb-3' : '' }}">
                                                <div class="col-md-2">
                                                    @if($item->product && $item->product->image)
                                                        <img src="{{ asset('storage/' . $item->product->image) }}" 
                                                             alt="{{ $item->product->name }}" 
                                                             class="img-fluid rounded" 
                                                             style="max-height: 80px; object-fit: cover;">
                                                    @else
                                                        <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                                             style="height: 80px; width: 80px;">
                                                            <i class="fas fa-image text-muted"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <h6 class="mb-1">{{ $item->product->name ?? 'Product Not Found' }}</h6>
                                                    <p class="text-muted mb-1">Quantity: {{ $item->quantity }}</p>
                                                    @if($item->product && $item->product->seller)
                                                        <small class="text-muted">
                                                            Sold by: {{ $item->product->seller->name }}
                                                        </small>
                                                    @endif
                                                </div>
                                                <div class="col-md-4 text-md-end">
                                                    <h6 class="text-primary mb-0">
                                                        ₹{{ number_format($item->price * $item->quantity, 2) }}
                                                    </h6>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <p class="text-muted">No items found for this order.</p>
                                    @endif
                                    
                                    <div class="row mt-3 pt-3 border-top">
                                        <div class="col-md-8">
                                            @if($order->sellerUser)
                                                <small class="text-muted">
                                                    <strong>Seller:</strong> {{ $order->sellerUser->name }}<br>
                                                    <strong>Email:</strong> {{ $order->sellerUser->email }}
                                                </small>
                                            @endif
                                        </div>
                                        <div class="col-md-4 text-md-end">
                                            <h5 class="text-success mb-0">
                                                Total: ₹{{ number_format($order->total_amount, 2) }}
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="card-footer bg-light border-top-0">
                                    <div class="row">
                                        <div class="col-md-8">
                                            @if($order->delivery_address)
                                                <small class="text-muted">
                                                    <i class="fas fa-map-marker-alt"></i>
                                                    <strong>Delivery Address:</strong> {{ $order->delivery_address }}
                                                </small>
                                            @endif
                                        </div>
                                        <div class="col-md-4 text-md-end">
                                            @if($order->status == 'pending')
                                                <button class="btn btn-sm btn-outline-danger" 
                                                        onclick="cancelOrder({{ $order->id }})">
                                                    Cancel Order
                                                </button>
                                            @elseif($order->status == 'delivered')
                                                <span class="text-success">
                                                    <i class="fas fa-check-circle"></i> Delivered
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                @if($orders->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $orders->links() }}
                    </div>
                @endif
                
            @else
                <!-- No Orders State -->
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-shopping-bag fa-5x text-muted"></i>
                    </div>
                    <h4 class="text-muted mb-3">No Orders Yet</h4>
                    <p class="text-muted mb-4">You haven't placed any orders yet. Start shopping to see your orders here!</p>
                    <a href="{{ route('index') }}" class="btn btn-primary">
                        <i class="fas fa-shopping-cart"></i> Start Shopping
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function cancelOrder(orderId) {
    if (confirm('Are you sure you want to cancel this order?')) {
        // Add AJAX call to cancel order
        fetch(`/orders/${orderId}/cancel`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error cancelling order. Please try again.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error cancelling order. Please try again.');
        });
    }
}
</script>

<style>
.badge {
    font-size: 0.875rem;
    padding: 0.5rem 1rem !important;
}

.badge-success {
    background-color: #28a745;
    color: white;
}

.badge-warning {
    background-color: #ffc107;
    color: #000;
}

.badge-info {
    background-color: #17a2b8;
    color: white;
}

.card {
    transition: transform 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
}

@media (max-width: 768px) {
    .card-header .row > div {
        text-align: center !important;
        margin-bottom: 0.5rem;
    }
    
    .card-body .row > div {
        margin-bottom: 1rem;
        text-align: center !important;
    }
    
    .card-footer .row > div {
        margin-bottom: 0.5rem;
        text-align: center !important;
    }
}
</style>
@endsection
@extends('layouts.minimal')

@section('title', 'Orders')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="display-5 fw-bold text-primary">
            <i class="fas fa-clipboard-list me-3"></i>Orders
        </h1>
        <a href="{{ route('hotel-owner.dashboard') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Dashboard
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle me-2 fs-5"></i>
                {{ session('success') }}
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($orders->count() > 0)
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <select id="status-filter" class="form-select">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="preparing" {{ request('status') == 'preparing' ? 'selected' : '' }}>Preparing</option>
                    <option value="ready" {{ request('status') == 'ready' ? 'selected' : '' }}>Ready</option>
                    <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="col-md-9">
                <div class="alert alert-info small p-2 mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Click on an order to view details, update status, or manage actions.
                </div>
            </div>
        </div>

        <div class="row g-4">
            @foreach($orders as $order)
            <div class="col-12">
                <div class="card shadow-sm border-0 hover-lift transition-all">
                    <div class="card-body p-4">
                        <div class="d-flex flex-wrap justify-content-between align-items-start mb-3">
                            <div>
                                <h5 class="fw-bold mb-1">Order #{{ $order->id }}</h5>
                                <p class="text-muted mb-1">
                                    <i class="fas fa-user me-2"></i>{{ $order->customer_name }}
                                    <span class="mx-2">•</span>
                                    <i class="fas fa-clock me-2"></i>{{ $order->created_at->format('M j, Y g:i A') }}
                                </p>
                            </div>
                            <span class="badge bg-{{ [
                                'pending' => 'warning',
                                'confirmed' => 'info',
                                'preparing' => 'primary',
                                'ready' => 'success',
                                'delivered' => 'secondary',
                                'cancelled' => 'danger'
                            ][$order->status] }} fs-6 px-3 py-2 rounded-pill">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <h6 class="fw-semibold text-muted mb-2">Items ({{ $order->items->count() }})</h6>
                                <ul class="list-unstyled mb-0" style="max-height: 120px; overflow-y: auto;">
                                    @foreach($order->items as $item)
                                        <li class="small">
                                            <span class="fw-medium">{{ $item->quantity }}× {{ $item->foodItem->name }}</span>
                                            <span class="text-muted">₹{{ number_format($item->price, 2) }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6 class="fw-semibold text-muted mb-2">Order Summary</h6>
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Subtotal:</span>
                                    <span>₹{{ number_format($order->subtotal, 2) }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Delivery Fee:</span>
                                    <span>₹{{ number_format($order->delivery_fee, 2) }}</span>
                                </div>
                                @if($order->discount_amount > 0)
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Discount:</span>
                                    <span class="text-success">-₹{{ number_format($order->discount_amount, 2) }}</span>
                                </div>
                                @endif
                                <hr class="my-2">
                                <div class="d-flex justify-content-between fw-bold fs-5">
                                    <span>Total:</span>
                                    <span>₹{{ number_format($order->total_amount, 2) }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                            <div>
                                <span class="badge bg-light text-dark">
                                    <i class="fas fa-map-marker-alt me-1"></i>{{ Str::limit($order->delivery_address, 40) }}
                                </span>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('hotel-owner.orders.show', $order) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-eye me-1"></i>View Details
                                </a>
                                @if(in_array($order->status, ['pending', 'confirmed', 'preparing', 'ready']))
                                <form action="{{ route('hotel-owner.orders.update-status', $order) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <select name="status" class="form-select form-select-sm me-2" style="width: auto; display: inline-block;" onchange="this.form.submit()">
                                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                        <option value="preparing" {{ $order->status == 'preparing' ? 'selected' : '' }}>Preparing</option>
                                        <option value="ready" {{ $order->status == 'ready' ? 'selected' : '' }}>Ready</option>
                                        <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancel</option>
                                    </select>
                                </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $orders->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
    @else
        <div class="text-center py-5 my-5">
            <i class="fas fa-box-open fa-5x text-muted opacity-75 mb-4"></i>
            <h4 class="text-dark fw-bold mb-3">No Orders Yet</h4>
            <p class="text-muted mb-4" style="max-width: 600px; margin: 0 auto;">
                Orders from customers will appear here once they place them.
            </p>
            <a href="{{ route('hotel-owner.dashboard') }}" class="btn btn-primary btn-lg px-4">
                <i class="fas fa-home me-2"></i>Return to Dashboard
            </a>
        </div>
    @endif
</div>

<style>
    .hover-lift {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .hover-lift:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 15px rgba(0,0,0,0.1);
    }
    .transition-all {
        transition: all 0.3s ease;
    }
    .card {
        border-radius: 12px;
        overflow: hidden;
    }
    .badge {
        font-weight: 500;
    }
    .form-select-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
        border-radius: 6px;
    }
</style>

<script>
    document.getElementById('status-filter').addEventListener('change', function() {
        const status = this.value;
        const url = new URL(window.location);
        if (status) {
            url.searchParams.set('status', status);
        } else {
            url.searchParams.delete('status');
        }
        window.location.href = url.toString();
    });
</script>
@endsection
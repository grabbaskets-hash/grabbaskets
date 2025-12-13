@extends('layouts.minimal')

@section('title', 'Food Items')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="display-5 fw-bold text-primary">
            <i class="fas fa-hamburger me-3"></i>My Menu Items
        </h1>
        <div>
            <a href="{{ route('hotel-owner.dashboard') }}" class="btn btn-outline-secondary me-2">
                <i class="fas fa-arrow-left me-2"></i>Dashboard
            </a>
            <a href="{{ route('hotel-owner.food-items.create') }}" class="btn btn-success">
                <i class="fas fa-plus me-2"></i>Add New Item
            </a>
        </div>
    </div>

    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <div class="d-flex align-items-center">
            <i class="fas fa-check-circle me-2 fs-5"></i>
            <span>{{ session('success') }}</span>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if($foodItems->count() > 0)
    <div class="row g-4">
        @foreach($foodItems as $item)
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-sm border-0 h-100 hover-lift transition-all">
                <div class="position-relative">
                    @if($item->image)
                    @php
                        $isLaravelCloud = (
                            env('LARAVEL_CLOUD_DEPLOYMENT') === true ||
                            (app()->environment('production') &&
                             isset($_SERVER['SERVER_NAME']) &&
                             strpos($_SERVER['SERVER_NAME'], '.laravel.cloud') !== false)
                        );
                        $type = $isLaravelCloud ? 'r2' : 'public';
                    @endphp
                    <img src="{{ url('/serve-image/' . $type . '/' . $item->image) }}" class="card-img-top" style="height: 200px; object-fit: cover;" alt="{{ $item->name }}"
                         onerror="this.onerror=null; this.src='{{ asset('images/placeholder.png') }}'">
                    @else
                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                        <i class="fas fa-utensils fa-3x text-muted"></i>
                    </div>
                    @endif

                    <!-- Status Badge -->
                    <div class="position-absolute top-0 end-0 m-2">
                        <span class="badge bg-{{ $item->is_available ? 'success' : 'secondary' }} rounded-pill px-3 py-2">
                            {{ $item->is_available ? 'Available' : 'Unavailable' }}
                        </span>
                    </div>

                    <!-- Food Type Badge -->
                    <div class="position-absolute top-0 start-0 m-2">
                        <span class="badge bg-{{ $item->food_type == 'veg' ? 'success' : ($item->food_type == 'non-veg' ? 'danger' : 'warning') }} rounded-pill px-3 py-2">
                            {{ strtoupper($item->food_type) }}
                        </span>
                    </div>
                </div>

                <div class="card-body d-flex flex-column p-3">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h5 class="card-title fw-bold mb-0">{{ $item->name }}</h5>
                        @if($item->is_popular)
                        <span class="badge bg-info rounded-pill">
                            <i class="fas fa-star me-1"></i>Popular
                        </span>
                        @endif
                    </div>

                    <p class="card-text text-muted mt-2 mb-3" style="font-size: 0.9rem;">
                        {{ Str::limit($item->description, 80) }}
                    </p>

                    <div class="mb-3">
                        <span class="badge bg-primary me-2">{{ $item->category }}</span>
                        @if($item->spice_level)
                        <span class="badge bg-warning me-2">{{ ucfirst($item->spice_level) }}</span>
                        @endif
                        @if($item->preparation_time)
                        <span class="badge bg-secondary">{{ $item->preparation_time }} min</span>
                        @endif
                    </div>

                    <div class="mt-auto">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <strong class="text-primary fs-5">₹{{ number_format($item->getFinalPrice(), 2) }}</strong>
                                @if($item->discounted_price)
                                <small class="text-muted ms-2"><del>₹{{ number_format($item->price, 2) }}</del></small>
                                <span class="badge bg-success ms-2">{{ $item->getDiscountPercentage() }}% OFF</span>
                                @endif
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <a href="{{ route('hotel-owner.food-items.show', $item) }}"
                                class="btn btn-outline-info btn-sm flex-grow-1"
                                title="View Details">
                                <i class="fas fa-eye me-1"></i>View
                            </a>
                            <a href="{{ route('hotel-owner.food-items.edit', $item) }}"
                                class="btn btn-outline-primary btn-sm flex-grow-1"
                                title="Edit Item">
                                <i class="fas fa-edit me-1"></i>Edit
                            </a>
                            <form action="{{ route('hotel-owner.food-items.destroy', $item) }}" method="POST" class="flex-grow-1">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm w-100"
                                    onclick="return confirm('Are you sure you want to delete this food item?')"
                                    title="Delete Item">
                                    <i class="fas fa-trash me-1"></i>Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $foodItems->links('pagination::bootstrap-5') }}
    </div>
    @else
    <div class="text-center py-5 my-5">
        <div class="mb-4">
            <i class="fas fa-utensils fa-5x text-muted opacity-75"></i>
        </div>
        <h4 class="text-dark fw-bold mb-3">Your Menu is Empty</h4>
        <p class="text-muted mb-4" style="max-width: 600px; margin: 0 auto;">
            Start building your delicious menu by adding your first food item. Your customers will love discovering your culinary creations!
        </p>
        <div class="d-flex justify-content-center gap-3">
            <a href="{{ route('hotel-owner.food-items.create') }}" class="btn btn-success btn-lg px-4">
                <i class="fas fa-plus me-2"></i>Add First Item
            </a>
            <a href="{{ route('hotel-owner.dashboard') }}" class="btn btn-outline-secondary btn-lg px-4">
                <i class="fas fa-home me-2"></i>Return to Dashboard
            </a>
        </div>
    </div>
    @endif
</div>

<style>
    /* Custom CSS for enhanced design */
    .hover-lift {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    .transition-all {
        transition: all 0.3s ease;
    }

    .card {
        border-radius: 12px;
        overflow: hidden;
    }

    .card-img-top {
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
    }

    .btn {
        border-radius: 8px;
        font-weight: 500;
    }

    .btn-sm {
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
    }

    .badge {
        font-weight: 500;
        padding: 0.5em 0.75em;
        font-size: 0.85rem;
    }

    .card-title {
        line-height: 1.3;
    }

    .card-text {
        line-height: 1.5;
    }

    @media (max-width: 768px) {
        .card-body {
            padding: 1rem;
        }

        .btn {
            font-size: 0.875rem;
            padding: 0.375rem 0.5rem;
        }
    }
</style>

@endsection
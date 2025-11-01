@extends('layouts.delivery-partner')

@section('title', 'Available Orders')

@section('content')
<div class="container py-4">
    <h2>Available Orders</h2>
    <p class="text-muted">Orders available for pickup in your area</p>
    
    @if(count($availableOrders) > 0)
        <div class="row">
            @foreach($availableOrders as $order)
                <!-- Order cards will go here -->
            @endforeach
        </div>
    @else
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> No orders available at the moment. Check back later!
        </div>
    @endif
</div>
@endsection

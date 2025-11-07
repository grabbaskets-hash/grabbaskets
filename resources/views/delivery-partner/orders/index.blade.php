@extends('delivery-partner.layouts.dashboard')

@section('title', 'My Orders')

@section('content')
<div class="container py-4">
    <h2>My Orders</h2>
    <p class="text-muted">Your delivery history and active orders</p>
    
    @if(count($orders) > 0)
        <div class="table-responsive">
            <!-- Orders table will go here -->
        </div>
    @else
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> No orders yet. Start accepting orders from the available orders page!
        </div>
    @endif
</div>
@endsection

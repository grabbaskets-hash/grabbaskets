@extends('layouts.admin')

@section('title', 'Delivery Partners Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3">
                    <i class="fas fa-shipping-fast text-primary me-2"></i>Delivery Partners Management
                </h1>
                <a href="{{ route('admin.delivery-partners.index') }}" class="btn btn-primary">
                    <i class="fas fa-list me-2"></i>View All Partners
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-left-primary">
                <div class="card-body">
                    <div class="text-primary text-uppercase mb-1">Total Partners</div>
                    <div class="h3 mb-0">{{ $stats['total_partners'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-success">
                <div class="card-body">
                    <div class="text-success text-uppercase mb-1">Online Now</div>
                    <div class="h3 mb-0">{{ $stats['online_partners'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-info">
                <div class="card-body">
                    <div class="text-info text-uppercase mb-1">Available</div>
                    <div class="h3 mb-0">{{ $stats['available_partners'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-warning">
                <div class="card-body">
                    <div class="text-warning text-uppercase mb-1">Pending Approval</div>
                    <div class="h3 mb-0">{{ $stats['pending_partners'] }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Deliveries & Today's Completed -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="text-primary text-uppercase mb-2">
                        <i class="fas fa-box me-2"></i>Active Deliveries
                    </div>
                    <div class="h2 mb-0">{{ $stats['active_deliveries'] }}</div>
                    <small class="text-muted">In pickup or delivery</small>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="text-success text-uppercase mb-2">
                        <i class="fas fa-check-circle me-2"></i>Completed Today
                    </div>
                    <div class="h2 mb-0">{{ $stats['completed_today'] }}</div>
                    <small class="text-muted">Delivered successfully</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Recent Delivery Activity</h5>
                </div>
                <div class="card-body">
                    @if($recentActivity->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Order</th>
                                        <th>Delivery Partner</th>
                                        <th>Status</th>
                                        <th>Assigned At</th>
                                        <th>Last Update</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentActivity as $activity)
                                        <tr>
                                            <td>
                                                <strong>#{{ $activity->order->order_number ?? 'N/A' }}</strong>
                                            </td>
                                            <td>
                                                {{ $activity->deliveryPartner->name ?? 'Unassigned' }}
                                            </td>
                                            <td>
                                                <span class="badge badge-{{ $this->getStatusBadgeClass($activity->status) }}">
                                                    {{ ucfirst(str_replace('_', ' ', $activity->status)) }}
                                                </span>
                                            </td>
                                            <td>{{ $activity->assigned_at?->format('M d, H:i') ?? '-' }}</td>
                                            <td>{{ $activity->updated_at?->diffForHumans() ?? '-' }}</td>
                                            <td>
                                                <a href="{{ route('admin.delivery-partners.show', $activity->deliveryPartner->id ?? '#') }}" 
                                                   class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No recent activity found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Online Delivery Partners -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Online Delivery Partners</h5>
                </div>
                <div class="card-body">
                    @if($onlinePartners->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Status</th>
                                        <th>Available</th>
                                        <th>Rating</th>
                                        <th>Today's Earnings</th>
                                        <th>Active Deliveries</th>
                                        <th>Last Active</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($onlinePartners as $partner)
                                        <tr>
                                            <td><strong>{{ $partner->name }}</strong></td>
                                            <td>{{ $partner->phone }}</td>
                                            <td>
                                                <span class="badge badge-{{ $partner->status === 'active' ? 'success' : 'warning' }}">
                                                    {{ ucfirst($partner->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($partner->is_available)
                                                    <span class="badge badge-success">Available</span>
                                                @else
                                                    <span class="badge badge-secondary">Busy</span>
                                                @endif
                                            </td>
                                            <td>
                                                <i class="fas fa-star text-warning"></i> {{ $partner->rating ?? 0 }}
                                            </td>
                                            <td>₹{{ $partner->wallet?->today_earnings ?? 0 }}</td>
                                            <td>
                                                <span class="badge badge-info">
                                                    {{ \App\Models\DeliveryRequest::where('delivery_partner_id', $partner->id)
                                                        ->whereIn('status', ['accepted', 'picked_up'])
                                                        ->count() }}
                                                </span>
                                            </td>
                                            <td>{{ $partner->last_active_at?->diffForHumans() ?? 'Never' }}</td>
                                            <td>
                                                <a href="{{ route('admin.delivery-partners.show', $partner->id) }}" 
                                                   class="btn btn-sm btn-primary">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No online delivery partners at the moment.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@php
// Helper method for status badge class
function getStatusBadgeClass($status) {
    return match($status) {
        'pending' => 'warning',
        'accepted' => 'info',
        'picked_up' => 'info',
        'in_transit' => 'primary',
        'completed' => 'success',
        'cancelled' => 'danger',
        default => 'secondary'
    };
}
@endphp
@endsection

@push('styles')
<style>
    .border-left-primary {
        border-left: 4px solid #007bff;
    }
    .border-left-success {
        border-left: 4px solid #28a745;
    }
    .border-left-info {
        border-left: 4px solid #17a2b8;
    }
    .border-left-warning {
        border-left: 4px solid #ffc107;
    }
</style>
@endpush

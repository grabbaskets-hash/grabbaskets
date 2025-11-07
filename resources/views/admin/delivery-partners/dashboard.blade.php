<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Partners Dashboard - Admin</title>
    <link rel="icon" type="image/png" href="{{ asset('asset/images/grabbasket.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            padding: 20px;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .stat-card {
            padding: 20px;
            border-radius: 10px;
            color: white;
            margin-bottom: 20px;
        }
        .bg-primary { background-color: #0d6efd !important; }
        .bg-success { background-color: #198754 !important; }
        .bg-info { background-color: #0dcaf0 !important; }
        .bg-warning { background-color: #ffc107 !important; }
        .table-responsive {
            border-radius: 10px;
            background: white;
            padding: 20px;
        }
        .badge {
            padding: 5px 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2><i class="fas fa-motorcycle me-2"></i>Delivery Partners Dashboard</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Admin</a></li>
                    <li class="breadcrumb-item active">Delivery Partners</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('admin.delivery-partners.index') }}" class="btn btn-primary">
                <i class="fas fa-list me-2"></i>View All Partners
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1">Total Partners</h6>
                            <h2 class="mb-0">{{ $stats['total_partners'] ?? 0 }}</h2>
                        </div>
                        <i class="fas fa-users fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1">Online Now</h6>
                            <h2 class="mb-0">{{ $stats['online_partners'] ?? 0 }}</h2>
                        </div>
                        <i class="fas fa-circle fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1">Available</h6>
                            <h2 class="mb-0">{{ $stats['available_partners'] ?? 0 }}</h2>
                        </div>
                        <i class="fas fa-check-circle fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1">Pending Approval</h6>
                            <h2 class="mb-0">{{ $stats['pending_partners'] ?? 0 }}</h2>
                        </div>
                        <i class="fas fa-clock fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1">Active Deliveries</h6>
                            <h2 class="mb-0">{{ $stats['active_deliveries'] ?? 0 }}</h2>
                        </div>
                        <i class="fas fa-motorcycle fa-2x text-primary"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1">Completed Today</h6>
                            <h2 class="mb-0">{{ $stats['completed_today'] ?? 0 }}</h2>
                        </div>
                        <i class="fas fa-check-double fa-2x text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Online Partners -->
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Online Delivery Partners</h5>
                </div>
                <div class="card-body">
                    @if(isset($onlinePartners) && $onlinePartners->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Status</th>
                                        <th>Rating</th>
                                        <th>Last Active</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($onlinePartners as $partner)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <span class="badge bg-success rounded-circle p-1 me-2">
                                                        <i class="fas fa-circle"></i>
                                                    </span>
                                                    {{ $partner->name }}
                                                </div>
                                            </td>
                                            <td>{{ $partner->phone }}</td>
                                            <td>
                                                @if($partner->is_available)
                                                    <span class="badge bg-success">Available</span>
                                                @else
                                                    <span class="badge bg-warning">Busy</span>
                                                @endif
                                            </td>
                                            <td>
                                                <i class="fas fa-star text-warning"></i>
                                                {{ number_format($partner->rating ?? 0, 1) }}
                                            </td>
                                            <td>{{ $partner->updated_at?->diffForHumans() ?? 'N/A' }}</td>
                                            <td>
                                                <a href="{{ route('admin.delivery-partners.show', $partner->id) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No delivery partners online at the moment</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Recent Activity</h5>
                </div>
                <div class="card-body">
                    @if(isset($recentActivity) && count($recentActivity) > 0)
                        <div class="list-group list-group-flush">
                            @foreach($recentActivity as $activity)
                                <div class="list-group-item px-0">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1">{{ $activity->deliveryPartner->name ?? 'Unknown' }}</h6>
                                            <small class="text-muted">
                                                Order #{{ $activity->order->id ?? 'N/A' }}
                                                <span class="badge bg-{{ $activity->status === 'completed' ? 'success' : 'primary' }} ms-1">
                                                    {{ ucfirst($activity->status) }}
                                                </span>
                                            </small>
                                        </div>
                                        <small class="text-muted">{{ $activity->updated_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-bell fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No recent activity</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

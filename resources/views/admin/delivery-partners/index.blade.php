<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Partners Management - Admin</title>
    <link rel="icon" type="image/png" href="{{ asset('asset/images/grabbasket.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; padding: 20px; }
        .card { border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .table-responsive { border-radius: 10px; background: white; }
        .badge { padding: 5px 10px; border-radius: 5px; }
    </style>
</head>
<body>

<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3">
                        <i class="fas fa-users text-primary me-2"></i>All Delivery Partners
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Admin</a></li>
                            <li class="breadcrumb-item active">Delivery Partners</li>
                        </ol>
                    </nav>
                </div>
                <a href="{{ route('admin.delivery-partners.dashboard') }}" class="btn btn-info">
                    <i class="fas fa-chart-line me-2"></i>Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-md-3">
                            <input type="text" name="search" class="form-control" 
                                   placeholder="Search by name/phone/email" value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <select name="status" class="form-control">
                                <option value="">All Status</option>
                                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="is_online" class="form-control">
                                <option value="">All Online Status</option>
                                <option value="1" {{ request('is_online') === '1' ? 'selected' : '' }}>Online</option>
                                <option value="0" {{ request('is_online') === '0' ? 'selected' : '' }}>Offline</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="is_available" class="form-control">
                                <option value="">All Availability</option>
                                <option value="1" {{ request('is_available') === '1' ? 'selected' : '' }}>Available</option>
                                <option value="0" {{ request('is_available') === '0' ? 'selected' : '' }}>Busy</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search me-2"></i>Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Partners Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if($partners->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                        <th>Status</th>
                                        <th>Online</th>
                                        <th>Available</th>
                                        <th>Rating</th>
                                        <th>Joined</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($partners as $partner)
                                        <tr>
                                            <td><strong>{{ $partner->name }}</strong></td>
                                            <td>{{ $partner->phone }}</td>
                                            <td>{{ $partner->email }}</td>
                                            <td>
                                                <span class="badge bg-{{ $partner->status === 'active' ? 'success' : ($partner->status === 'pending' ? 'warning' : 'danger') }}">
                                                    {{ ucfirst($partner->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($partner->is_online)
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-circle"></i> Online
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary">Offline</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($partner->is_available)
                                                    <span class="badge bg-info">Available</span>
                                                @else
                                                    <span class="badge bg-danger">Busy</span>
                                                @endif
                                            </td>
                                            <td>
                                                <i class="fas fa-star text-warning"></i> {{ $partner->rating ?? 0 }}/5
                                            </td>
                                            <td>{{ $partner->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.delivery-partners.show', $partner->id) }}" 
                                                       class="btn btn-sm btn-primary" title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.delivery-partners.track', $partner->id) }}" 
                                                       class="btn btn-sm btn-info" title="Track">
                                                        <i class="fas fa-map-marker-alt"></i>
                                                    </a>
                                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" 
                                                            data-bs-target="#statusModal{{ $partner->id }}" title="Update Status">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Status Update Modal -->
                                        <div class="modal fade" id="statusModal{{ $partner->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Update Status - {{ $partner->name }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form method="POST" action="{{ route('admin.delivery-partners.update-status', $partner->id) }}">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label>Status</label>
                                                                <select name="status" class="form-control" required>
                                                                    <option value="active" {{ $partner->status === 'active' ? 'selected' : '' }}>Active</option>
                                                                    <option value="pending" {{ $partner->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                                                    <option value="suspended" {{ $partner->status === 'suspended' ? 'selected' : '' }}>Suspended</option>
                                                                    <option value="rejected" {{ $partner->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                                                    <option value="inactive" {{ $partner->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-primary">Update</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $partners->links() }}
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>No delivery partners found.
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
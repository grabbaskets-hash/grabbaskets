<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage User Withdrawals | Admin</title>
    <link rel="icon" type="image/png" href="{{ asset('asset/images/grabbasket.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            width: 250px;
            background-color: #1e1e2f;
            color: #fff;
            padding-top: 20px;
            z-index: 1000;
            box-shadow: 3px 0 10px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
            overflow: hidden;
            height: 100vh;
        }

        .sidebar .logo {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 4px 10px;
            border-radius: 6px;
            width: 100%;
            height: 100px;
            margin-top: -40px;
        }

        .sidebar .logo img {
            width: 150px;
            height: 200px;
            object-fit: cover;
            margin-top: 60px;
            position: relative;
            left: 30px;
        }

        .sidebar-content {
            overflow-y: auto;
            overflow-x: hidden;
            padding-bottom: 20px;
            height: calc(100vh - 180px);
            padding-left: 15px;
            padding-right: 15px;
        }

        .sidebar .nav-link {
            color: #bdc3c7;
            margin: 8px 5px;
            padding: 12px 20px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: #2d2d40;
            color: #fff;
        }

        .sidebar .nav-link.active {
            background-color: #007bff;
            color: white;
            border-left: 4px solid #0056b3;
        }

        .sidebar .nav-link i {
            font-size: 18px;
            width: 24px;
            text-align: center;
        }

        /* ===== MAIN CONTENT AREA ===== */
        .content {
            margin-left: 250px;
            padding: 40px 20px;
            transition: margin-left 0.3s ease-in-out;
            min-height: 100vh;
        }

        .menu-toggle {
            position: fixed;
            top: 15px;
            left: 15px;
            font-size: 1.8rem;
            cursor: pointer;
            color: #1e1e2f;
            z-index: 1200;
            background: white;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }

        @media (max-width: 768px) {
            .sidebar { left: -250px; }
            .sidebar.show { left: 0; }
            .content { margin-left: 0; }
        }

        /* ===== TABLE & CARDS ===== */
        .card { border-radius: 12px; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .card-header { background-color: #2d2d40; color: white; border-radius: 12px 12px 0 0 !important; font-weight: 600; }
        .table thead th { background-color: #f8f9fa; color: #333; font-weight: 600; border-bottom: 2px solid #dee2e6; text-align: center; }
        .table tbody td { vertical-align: middle; text-align: center; }
        
        .badge-status { padding: 6px 12px; border-radius: 20px; font-weight: 600; font-size: 0.8rem; }
        .bg-pending { background-color: #fff7e6; color: #faad14; }
        .bg-completed { background-color: #f6ffed; color: #52c41a; }
        .bg-rejected { background-color: #fff1f0; color: #ff4d4f; }
    </style>
</head>

<body>
    <div class="menu-toggle d-md-none">
        <i class="bi bi-list"></i>
    </div>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebarMenu">
        <div class="sidebar-header">
            <div class="logo">
                <img src="{{ asset('asset/images/grabbasket.png') }}" alt="Logo">
            </div>
        </div>
        <div class="sidebar-content">
            <ul class="nav nav-pills flex-column">
                <li><a class="nav-link" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
                <li><a class="nav-link" href="{{ route('admin.products') }}"><i class="bi bi-box-seam"></i> Products</a></li>
                <li><a class="nav-link" href="{{ route('admin.orders') }}"><i class="bi bi-cart-check"></i> Orders</a></li>
                <li><a class="nav-link" href="{{ route('admin.manageuser') }}"><i class="bi bi-people"></i> Users</a></li>
                <li><a class="nav-link active" href="{{ route('admin.user-withdrawals.index') }}"><i class="bi bi-bank"></i> User Withdrawals</a></li>
                <li><a class="nav-link" href="{{ route('admin.delivery-partners.dashboard') }}"><i class="bi bi-bicycle"></i> Delivery Partners</a></li>
                <li><a class="nav-link text-danger" href="{{ route('admin.logout') }}"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
            </ul>
        </div>
    </div>

    <!-- Main Content -->
    <div class="content">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold"><i class="bi bi-bank text-primary"></i> User Withdrawals</h2>
                <div class="text-muted">Manage payout requests from shoppers</div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-header py-3">
                    <div class="row align-items-center">
                        <div class="col">
                            <i class="bi bi-list-task me-2"></i> Withdrawal Requests
                        </div>
                        <div class="col-auto">
                            <form method="GET" class="d-flex gap-2">
                                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                    <option value="">All Status</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>User Info</th>
                                    <th>Amount</th>
                                    <th>Bank Details</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($withdrawals as $withdrawal)
                                    <tr>
                                        <td>
                                            <div class="fw-semibold">{{ $withdrawal->created_at->format('d M, Y') }}</div>
                                            <small class="text-muted">{{ $withdrawal->created_at->format('h:i A') }}</small>
                                        </td>
                                        <td class="text-start ps-4">
                                            <div class="fw-bold">{{ $withdrawal->user->name }}</div>
                                            <div class="small text-muted"><i class="bi bi-telephone"></i> {{ $withdrawal->user->phone }}</div>
                                        </td>
                                        <td>
                                            <div class="fs-5 fw-bold text-success">₹{{ number_format($withdrawal->amount, 0) }}</div>
                                            <small class="text-muted">{{ $withdrawal->amount }} Points</small>
                                        </td>
                                        <td class="text-start">
                                            @if($withdrawal->user->bankDetails)
                                                <div class="small">
                                                    <strong>{{ $withdrawal->user->bankDetails->bank_name }}</strong><br>
                                                    <span class="text-muted">A/C:</span> {{ $withdrawal->user->bankDetails->account_number }}<br>
                                                    <span class="text-muted">IFSC:</span> {{ $withdrawal->user->bankDetails->ifsc_code }}<br>
                                                    <span class="text-muted">Holder:</span> {{ $withdrawal->user->bankDetails->account_holder_name }}
                                                </div>
                                            @else
                                                <span class="badge bg-danger">Not Provided</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge-status bg-{{ $withdrawal->status }}">
                                                {{ ucfirst($withdrawal->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($withdrawal->status == 'pending')
                                                <div class="btn-group btn-group-sm">
                                                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#approveModal{{ $withdrawal->id }}">
                                                        Approve
                                                    </button>
                                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $withdrawal->id }}">
                                                        Reject
                                                    </button>
                                                </div>

                                                <!-- Approve Modal -->
                                                <div class="modal fade" id="approveModal{{ $withdrawal->id }}" tabindex="-1">
                                                    <div class="modal-dialog">
                                                        <form action="{{ route('admin.user-withdrawals.approve', $withdrawal) }}" method="POST">
                                                            @csrf
                                                            <div class="modal-content text-start">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">Complete Transfer</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="alert alert-info py-2 small">
                                                                        Transfer <strong>₹{{ number_format($withdrawal->amount, 0) }}</strong> to the bank details listed above before completing.
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label class="form-label small fw-bold">Transaction Reference ID</label>
                                                                        <input type="text" name="transaction_id" class="form-control" placeholder="e.g. UTR12345678" required>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label class="form-label small fw-bold">Notes (Optional)</label>
                                                                        <textarea name="admin_notes" class="form-control" rows="2" placeholder="Paid via IMPS..."></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                    <button type="submit" class="btn btn-success px-4">Confirm Payment</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>

                                                <!-- Reject Modal -->
                                                <div class="modal fade" id="rejectModal{{ $withdrawal->id }}" tabindex="-1">
                                                    <div class="modal-dialog">
                                                        <form action="{{ route('admin.user-withdrawals.reject', $withdrawal) }}" method="POST">
                                                            @csrf
                                                            <div class="modal-content text-start">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title text-danger">Reject Withdrawal</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <p class="text-muted small">The user will be notified and points will be refunded to their wallet.</p>
                                                                    <div class="mb-3">
                                                                        <label class="form-label small fw-bold">Reason for Rejection</label>
                                                                        <textarea name="reason" class="form-control" rows="3" placeholder="Incorrect bank details / suspicious activity..." required></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                    <button type="submit" class="btn btn-danger px-4">Reject & Refund</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="small text-muted">
                                                    Processed on<br>
                                                    {{ $withdrawal->processed_at?->format('d M, Y') }}
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-5 text-muted">
                                            <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                            No withdrawal requests found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white py-3">
                    {{ $withdrawals->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.querySelector('.menu-toggle');
            const sidebar = document.getElementById('sidebarMenu');

            if (menuToggle) {
                menuToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                });
            }

            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(event) {
                if (window.innerWidth <= 768 && sidebar.classList.contains('show')) {
                    const isClickInsideSidebar = sidebar.contains(event.target) || menuToggle.contains(event.target);
                    if (!isClickInsideSidebar) {
                        sidebar.classList.remove('show');
                    }
                }
            });
        });
    </script>
</body>

</html>

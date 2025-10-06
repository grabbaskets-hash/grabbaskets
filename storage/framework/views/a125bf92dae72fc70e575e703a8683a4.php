<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
        }
        
        /* Keep original sidebar styling exactly as shown */
        .sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            width: 230px;
            background: #212529;
            color: #fff;
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease-in-out;
            z-index: 1000;
        }
        
        @media (max-width: 768px) {
            .sidebar { left: -230px; }
            .sidebar.show { left: 0; }
        }
        
        .sidebar .logo {
            position: sticky;
            top: 0;
            width: 100%;
            text-align: center;
            padding: 20px 0;
            background: #212529;
            z-index: 1100;
            border-bottom: 1px solid #343a40;
        }
        
        .sidebar .logo img {
            max-width: 160px;
            object-fit: contain;
            display: inline-block;
            transition: transform 0.2s;
        }
        
        .sidebar .logo img:hover {
            transform: scale(1.05);
        }
        
        .sidebar .nav-link {
            color: #adb5bd;
            margin: 6px 0;
            border-radius: 6px;
            padding: 10px 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .sidebar .nav-link.active,
        .sidebar .nav-link:hover {
            background: #0d6efd;
            color: #fff;
        }
        
        .sidebar .nav-link i {
            margin-right: 8px;
            font-size: 18px;
        }
        
        .content {
            margin-left: 230px;
            padding: 20px;
            transition: margin-left 0.3s ease-in-out;
        }
        
        @media (max-width: 768px) {
            .content {
                margin-left: 0;
            }
        }
        
        .menu-toggle {
            position: fixed;
            top: 10px;
            left: 15px;
            font-size: 1.8rem;
            cursor: pointer;
            color: #212529;
            z-index: 1200;
        }
        
        .img {
            position: relative;
            margin-top: -40px;
        }
        
        .filter-controls {
            background: #fff;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.08);
        }
        
        /* Fix pagination styling to match your design */
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
            gap: 5px;
        }
        
        .pagination .page-item {
            margin: 0;
        }
        
        .pagination .page-link {
            color: #2c3e50;
            border: 1px solid #ddd;
            padding: 8px 12px;
            border-radius: 4px;
            transition: all 0.3s ease;
        }
        
        .pagination .page-link:hover {
            background-color: #3498db;
            color: white;
            border-color: #3498db;
        }
        
        .pagination .page-item.active .page-link {
            background-color: #3498db;
            color: white;
            border-color: #3498db;
        }
        
        /* Table styling to match your design */
        .table thead th {
            background-color: #2c3e50;
            color: white;
            font-weight: 600;
            text-align: center;
        }
        
        .table tbody tr:hover {
            background-color: #f8f9fa;
        }
        
        .btn-update {
            padding: 5px 10px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }
        
        .btn-update:hover {
            background-color: #2980b9;
        }
        
        .btn-save {
            padding: 5px 10px;
            background-color: #2ecc71;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }
        
        .btn-save:hover {
            background-color: #27ae60;
        }
        
        .payment-badge {
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .payment-badge.paypal {
            background-color: #003087;
            color: white;
        }
        
        .payment-badge.credit-card {
            background-color: #2c3e50;
            color: white;
        }
        
        .payment-badge.cod {
            background-color: #e67e22;
            color: white;
        }
        
        /* Header styling */
        .card-header {
            background-color: #2c3e50;
            color: white;
            font-weight: 600;
        }
        
        /* Status dropdown styling */
        .form-select {
            padding: 5px 10px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="menu-toggle d-md-none">
        <i class="bi bi-list"></i>
    </div>

    <div class="sidebar" id="sidebarMenu">
        <div class="logo">
            <img src="<?php echo e(asset('asset/images/grabbasket.jpg')); ?>" class="img"
                 alt="Grab Basket Admin Panel"
                 onerror="this.onerror=null;this.src='https://via.placeholder.com/150x50?text=Logo';">
        </div>
        <ul class="nav nav-pills flex-column">
            <li><a class="nav-link" href="<?php echo e(route('admin.dashboard')); ?>"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
            <li><a class="nav-link" href="/admin/products"><i class="bi bi-box-seam"></i> Products</a></li>
            <li><a class="nav-link active" href="/admin/orders"><i class="bi bi-cart-check"></i> Orders</a></li>
            <li><a class="nav-link" href="/admin/manageuser"><i class="bi bi-people"></i> Users</a></li>
            <li><a class="nav-link" href="<?php echo e(route('admin.bulkProductUpload')); ?>"><i class="bi bi-upload"></i> Bulk Product Upload</a></li>
            <li><a class="nav-link text-danger" href="<?php echo e(route('admin.logout')); ?>">
                    <i class="bi bi-box-arrow-right"></i> Logout</a>
            </li>
        </ul>
    </div>

    <div class="content">
        <div class="container-fluid">
            <h2 class="mb-4"><i class="bi bi-cart-check"></i> Orders Dashboard</h2>

            
            <div class="filter-controls">
                <form method="GET" class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label">Search</label>
                        <input type="text" name="search" class="form-control"
                               value="<?php echo e(request('search')); ?>"
                               placeholder="Customer or Product...">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="all">All Statuses</option>
                            <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($status); ?>" <?php echo e(request('status') == $status ? 'selected' : ''); ?>>
                                    <?php echo e($status); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">From</label>
                        <input type="date" name="start_date" class="form-control"
                               value="<?php echo e(request('start_date')); ?>">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">To</label>
                        <input type="date" name="end_date" class="form-control"
                               value="<?php echo e(request('end_date')); ?>">
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-funnel"></i> Filter
                        </button>
                        <a href="<?php echo e(route('admin.orders')); ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-repeat"></i> Reset
                        </a>
                    </div>
                </form>
            </div>

            
            <div class="card shadow-lg border-0">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-cart-check"></i> Orders List</h5>
                    <span class="badge bg-light text-dark"><?php echo e($orders->total()); ?> Orders</span>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-hover table-bordered align-middle text-center">
                        <thead class="table-dark sticky-top">
                            <tr>
                                <th>#</th>
                                <th>Customer</th>
                                <th>Product</th>
                                <th>Qty</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Tracking #</th>
                                <th>Payment</th>
                                <th>Ordered At</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td class="fw-bold"><?php echo e($order->id); ?></td>
                                    <td>
                                        <i class="bi bi-person-circle text-primary"></i>
                                        <?php echo e($order->buyerUser->name ?? 'Unknown'); ?>

                                    </td>
                                    <td>
                                        <i class="bi bi-box-seam text-success"></i>
                                        <?php echo e($order->product->name ?? '-'); ?>

                                    </td>
                                    <td><span class="badge bg-info"><?php echo e($order->quantity ?? 1); ?></span></td>
                                    <td class="fw-semibold text-success">₹<?php echo e(number_format($order->amount, 2)); ?></td>
                                    <td>
                                        <!-- ✅ Form-based status update -->
                                        <form action="<?php echo e(route('admin.updateOrderStatus', $order->id)); ?>" method="POST" class="d-flex align-items-center justify-content-center gap-2">
                                            <?php echo csrf_field(); ?>
                                            <select name="status" class="form-select form-select-sm w-auto">
                                                <option value="Pending" <?php echo e($order->status == 'Pending' ? 'selected' : ''); ?>>Pending</option>
                                                <option value="Shipped" <?php echo e($order->status == 'Shipped' ? 'selected' : ''); ?>>Shipped</option>
                                                <option value="Delivered" <?php echo e($order->status == 'Delivered' ? 'selected' : ''); ?>>Delivered</option>
                                                <option value="Cancelled" <?php echo e($order->status == 'Cancelled' ? 'selected' : ''); ?>>Cancelled</option>
                                            </select>
                                            <button type="submit" class="btn btn-sm btn-outline-primary">Update</button>
                                        </form>
                                    </td>
                                    <td>
                                        <!-- ✅ Tracking number update -->
                                        <form action="<?php echo e(route('admin.updateTracking', $order->id)); ?>" method="POST" class="d-flex flex-column gap-2">
                                            <?php echo csrf_field(); ?>
                                            <div class="d-flex gap-2">
                                                <input type="text" name="tracking_number" value="<?php echo e($order->tracking_number); ?>" class="form-control form-control-sm" placeholder="Tracking Number">
                                                <select name="courier_name" class="form-select form-select-sm">
                                                    <option value="">Select Courier</option>
                                                    <option value="Delhivery" <?php echo e($order->courier_name == 'Delhivery' ? 'selected' : ''); ?>>Delhivery</option>
                                                    <option value="Blue Dart" <?php echo e($order->courier_name == 'Blue Dart' ? 'selected' : ''); ?>>Blue Dart</option>
                                                    <option value="DTDC" <?php echo e($order->courier_name == 'DTDC' ? 'selected' : ''); ?>>DTDC</option>
                                                    <option value="India Post" <?php echo e($order->courier_name == 'India Post' ? 'selected' : ''); ?>>India Post</option>
                                                    <option value="FedEx" <?php echo e($order->courier_name == 'FedEx' ? 'selected' : ''); ?>>FedEx</option>
                                                    <option value="Ecom Express" <?php echo e($order->courier_name == 'Ecom Express' ? 'selected' : ''); ?>>Ecom Express</option>
                                                    <option value="Professional Couriers" <?php echo e($order->courier_name == 'Professional Couriers' ? 'selected' : ''); ?>>Professional Couriers</option>
                                                    <option value="GATI" <?php echo e($order->courier_name == 'GATI' ? 'selected' : ''); ?>>GATI</option>
                                                </select>
                                            </div>
                                            <button type="submit" class="btn btn-sm btn-outline-success">
                                                <i class="bi bi-truck"></i> Update Tracking
                                            </button>
                                        </form>
                                    </td>
                                    <td>
                                        <?php if($order->payment_method === 'Online'): ?>
                                            <span class="badge payment-badge paypal">Online</span>
                                        <?php elseif($order->payment_method === 'COD'): ?>
                                            <span class="badge payment-badge cod">COD</span>
                                        <?php else: ?>
                                            <span class="badge payment-badge credit-card"><?php echo e($order->payment_method); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td><i class="bi bi-calendar-event"></i> <?php echo e($order->created_at->format('d M Y, h:i A')); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="9" class="text-muted py-3">
                                        <i class="bi bi-inbox fs-4"></i> No orders found.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>

                    <div class="d-flex justify-content-center mt-3">
                        <?php echo e($orders->links()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Mobile Sidebar Toggle -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const menuToggle = document.querySelector('.menu-toggle');
            const sidebar = document.getElementById('sidebarMenu');

            if (menuToggle) {
                menuToggle.addEventListener('click', function () {
                    sidebar.classList.toggle('show');
                });
            }
        });
    </script>
</body>
</html><?php /**PATH E:\e-com_updated_final\e-com_updated\resources\views/admin/orders.blade.php ENDPATH**/ ?>
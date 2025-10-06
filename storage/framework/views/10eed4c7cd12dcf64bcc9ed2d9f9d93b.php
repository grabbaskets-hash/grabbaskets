
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
        }

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
            .sidebar {
                left: -230px;
            }
            .sidebar.show {
                left: 0;
            }
        }

        .sidebar .logo {
            position: sticky;
            top: 0;
            width: 100%;
            text-align: center;
            padding-bottom: 70px;
            background: #212529;
            z-index: 1100;
            height: 20px;
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
        }

        .sidebar .nav-link.active,
        .sidebar .nav-link:hover {
            background: #0d6efd;
            color: #fff;
        }

        .sidebar .nav-link i {
            margin-right: 8px;
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
            .content.shifted {
                margin-left: 230px;
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
            margin-left: -50px;
        }

        .filter-controls {
            background: #fff;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.08);
        }

        .filter-controls .form-control,
        .filter-controls .form-select {
            height: calc(1.5em + 0.75rem + 2px);
        }

        .card {
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }

        .card-header {
            background-color: #0d6efd;
            color: white;
            font-weight: 500;
            padding: 12px 20px;
        }

        .table th,
        .table td {
            vertical-align: middle;
            text-align: center;
        }

        .action-btns .btn {
            margin: 0 2px;
            padding: 5px 10px;
            font-size: 0.85rem;
        }

        .btn-suspend {
            background-color: #ffc107;
            border-color: #ffc107;
            color: #212529;
        }

        .btn-suspend:hover {
            background-color: #e0a800;
            border-color: #e0a800;
        }

        .btn-delete {
            background-color: #dc3545;
            border-color: #dc3545;
            color: white;
        }

        .btn-delete:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }

        .badge-status {
            padding: 5px 10px;
            font-size: 0.8rem;
            border-radius: 20px;
        }

        .badge-active {
            background-color: #28a745;
            color: white;
        }

        .badge-suspended {
            background-color: #ffc107;
            color: #212529;
        }

        .badge-admin {
            background-color: #6c757d;
            color: white;
        }
    </style>
</head>

<body>
    <!-- Mobile Menu Toggle -->
    <div class="menu-toggle d-md-none">
        <i class="bi bi-list"></i>
    </div>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebarMenu">
        <div class="logo">
            <img src="<?php echo e(asset('asset/images/grabbasket.png')); ?>" class="img"
                 alt="Grab Basket Admin Panel"
                 onerror="this.onerror=null;this.src='https://via.placeholder.com/150x50?text=Logo';">
        </div>
        <ul class="nav nav-pills flex-column">
            <li><a class="nav-link" href="<?php echo e(route('admin.dashboard')); ?>"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
            <li><a class="nav-link" href="<?php echo e(route('admin.products')); ?>"><i class="bi bi-box-seam"></i> Products</a></li>
            <li><a class="nav-link" href="<?php echo e(route('admin.orders')); ?>"><i class="bi bi-cart-check"></i> Orders</a></li>
            <li><a class="nav-link active" href="<?php echo e(route('admin.manageuser')); ?>"><i class="bi bi-people"></i> Users</a></li>
            <li><a class="nav-link" href="<?php echo e(route('admin.bulkProductUpload')); ?>"><i class="bi bi-upload"></i> Bulk Product Upload</a></li>
            <li><a class="nav-link text-danger" href="<?php echo e(route('admin.logout')); ?>">
                    <i class="bi bi-box-arrow-right"></i> Logout</a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="content" id="mainContent">
        <h2 class="mb-4"><i class="bi bi-people"></i> Manage Users</h2>

        <!-- Filter Section -->
        <form method="GET" class="mb-4">
            <div class="row g-2">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Search by name or email..."
                           value="<?php echo e(request('search')); ?>">
                </div>
                <div class="col-md-3">
                    <select name="role" class="form-select">
                        <option value="all">All Roles</option>
                        <option value="admin" <?php echo e(request('role') == 'admin' ? 'selected' : ''); ?>>Admin</option>
                        <option value="seller" <?php echo e(request('role') == 'seller' ? 'selected' : ''); ?>>Seller</option>
                        <option value="buyer" <?php echo e(request('role') == 'buyer' ? 'selected' : ''); ?>>Buyer</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="active" <?php echo e(request('status') == 'active' ? 'selected' : ''); ?>>Active</option>
                        <option value="suspended" <?php echo e(request('status') == 'suspended' ? 'selected' : ''); ?>>Suspended</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
            </div>
        </form>

        <!-- User List Card -->
        <div class="card shadow-sm">
            <div class="card-header bg-gradient bg-dark text-white d-flex justify-content-between align-items-center">
                <span><i class="bi bi-people me-2"></i> Users List</span>
                <span class="badge bg-secondary"><?php echo e($users->total()); ?> Users</span>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-striped mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($loop->iteration); ?></td>
                                <td><?php echo e($user->name); ?></td>
                                <td><?php echo e($user->email); ?></td>
                                <td><?php echo e(ucfirst($user->role)); ?></td>
                                <td><?php echo e($user->phone ?? '-'); ?></td>
                                <td>
                                    <?php if($user->role === 'admin'): ?>
                                        <span class="badge badge-admin">Admin</span>
                                    <?php elseif($user->is_suspended ?? false): ?>
                                        <span class="badge badge-suspended">Suspended</span>
                                    <?php else: ?>
                                        <span class="badge badge-active">Active</span>
                                    <?php endif; ?>
                                </td>
                                <td class="action-btns">
                                    <!-- Suspend Button -->
                                    <form action="<?php echo e(route('admin.users.suspend', $user->id)); ?>" method="POST" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="btn btn-suspend btn-sm">
                                            <i class="bi bi-person-x"></i> <?php echo e($user->is_suspended ? 'Restore' : 'Suspend'); ?>

                                        </button>
                                    </form>

                                    <!-- Delete -->
                                    <form action="<?php echo e(route('admin.users.delete', $user->id)); ?>" method="POST" class="d-inline"
                                          onsubmit="return confirm('Are you sure?');">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-delete btn-sm">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" class="text-muted text-center py-4">No users found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="mt-3">
                    <?php echo e($users->links()); ?>

                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Sidebar Toggle Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.querySelector('.menu-toggle');
            const sidebar = document.getElementById('sidebarMenu');
            const mainContent = document.getElementById('mainContent');
            
            if (menuToggle) {
                menuToggle.addEventListener('click', function () {
                    sidebar.classList.toggle('show');
                    mainContent.classList.toggle('shifted');
                });
            }
        });
    </script>
</body>
</html><?php /**PATH E:\e-com_updated_final\e-com_updated\resources\views/admin/manageuser.blade.php ENDPATH**/ ?>
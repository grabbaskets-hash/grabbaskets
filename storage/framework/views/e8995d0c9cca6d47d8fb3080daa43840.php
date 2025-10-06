<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Seller Dashboard</title>
    <link rel="icon" type="image/jpeg" href="<?php echo e(asset('asset/images/grabbasket.jpg')); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            width: 240px;
            background: #212529;
            color: #fff;
            padding-top: 60px;
            transition: all 0.3s;
            z-index: 1000;
        }

        .sidebar .nav-link {
            color: #adb5bd;
            margin: 6px 0;
            border-radius: 6px;
        }

        .sidebar .nav-link.active,
        .sidebar .nav-link:hover {
            background: #0d6efd;
            color: #fff;
        }

        .sidebar .nav-link i {
            margin-right: 8px;
        }

        /* Content area */
        .content {
            margin-left: 240px;
            padding: 20px;
        }

        .dashboard-header {
            background: linear-gradient(90deg, #0d6efd, #6610f2);
            color: white;
            padding: 40px 20px;
            border-radius: 12px;
            margin-bottom: 30px;
            text-align: center;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
        }

        .dashboard-header img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 3px solid white;
            margin-bottom: 10px;
        }

        /* Stat cards */
        .stat-card {
            border-radius: 12px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.08);
            transition: transform 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        /* Orders Table */
        .orders-table {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
            background: white;
        }

        .table thead {
            background: #343a40;
            color: white;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                left: -240px;
            }

            .sidebar.show {
                left: 0;
            }

            .content {
                margin-left: 0;
            }
        }

        /* Toggle button */
        .menu-toggle {
            position: fixed;
            top: 15px;
            left: 15px;
            font-size: 1.8rem;
            cursor: pointer;
            color: #212529;
            z-index: 1101;
        }

        .nav-pills{
            position: relative;
            bottom: 50px;
        }
    </style>
</head>

<body>
    <!-- Toggle Button (mobile) -->
    <div class="menu-toggle d-md-none">
        <i class="bi bi-list"></i>
    </div>

    <!-- Sidebar -->
    <div class="sidebar d-flex flex-column p-3" id="sidebarMenu">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <img src="<?php echo e(asset('asset/images/grablogo.jpg')); ?>" alt="Logo" class="logoimg" width="150px">
            <?php if (isset($component)) { $__componentOriginal6541145ad4a57bfb6e6f221ba77eb386 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6541145ad4a57bfb6e6f221ba77eb386 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.notification-bell','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('notification-bell'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6541145ad4a57bfb6e6f221ba77eb386)): ?>
<?php $attributes = $__attributesOriginal6541145ad4a57bfb6e6f221ba77eb386; ?>
<?php unset($__attributesOriginal6541145ad4a57bfb6e6f221ba77eb386); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6541145ad4a57bfb6e6f221ba77eb386)): ?>
<?php $component = $__componentOriginal6541145ad4a57bfb6e6f221ba77eb386; ?>
<?php unset($__componentOriginal6541145ad4a57bfb6e6f221ba77eb386); ?>
<?php endif; ?>
        </div>
        <ul class="nav nav-pills flex-column" style="margin-top:65px;">
            <li>
                <a class="nav-link" href="<?php echo e(route('seller.createProduct')); ?>">
                    <i class="bi bi-plus-circle"></i> Add Product
                </a>
            </li>
            <li>
                <a class="nav-link" href="<?php echo e(route('seller.createCategorySubcategory')); ?>">
                    <i class="bi bi-plus-square"></i> Add Category
                </a>
            </li>
            <li>
                <a class="nav-link active" href="<?php echo e(route('seller.dashboard')); ?>">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>
            <li>
                <a class="nav-link" href="<?php echo e(route('seller.transactions')); ?>">
                    <i class="bi bi-cart-check"></i> Orders
                </a>
            </li>
            <li>
                <a class="nav-link" href="<?php echo e(route('notifications.index')); ?>">
                    <i class="bi bi-bell"></i> Notifications
                </a>
            </li>
            <li>
                <a class="nav-link" href="<?php echo e(route('seller.profile')); ?>">
                    <i class="bi bi-person-circle"></i> Profile
                </a>
            </li>
            <li>
                <a class="nav-link" href="#"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </li>
        </ul>
        <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" class="d-none">
            <?php echo csrf_field(); ?>
        </form>
    </div>
    
    <!-- Content -->
    <div class="content">
        <!-- Dashboard Header -->
        <div class="dashboard-header">
            <img src="<?php echo e(asset('asset/images/grabbasket.png')); ?>" alt="Seller Profile">
            <h2>Welcome, <?php echo e(Auth::user()->name ?? 'Seller'); ?>!</h2>
            <p class="mb-0">Here's an overview of your store performance.</p>
        </div>

        <!-- Stats -->
        <div class="row g-4 mb-5">
            <div class="col-md-3">
                <div class="card stat-card text-center p-3 bg-light">
                    <div class="text-primary fs-2"><i class="bi bi-currency-dollar"></i></div>
                    <h6>Revenue</h6>
                    <p class="display-6 fw-bold">
                        ₹<?php echo e(number_format(\App\Models\Order::where('seller_id', Auth::id())->sum('amount'), 2)); ?></p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card text-center p-3 bg-light">
                    <div class="text-success fs-2"><i class="bi bi-box-seam"></i></div>
                    <h6>Products</h6>
                    <p class="display-6 fw-bold"><?php echo e($products->count()); ?></p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card text-center p-3 bg-light">
                    <div class="text-warning fs-2"><i class="bi bi-cart-check"></i></div>
                    <h6>Orders</h6>
                    <p class="display-6 fw-bold"><?php echo e(\App\Models\Order::where('seller_id', Auth::id())->count()); ?></p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card text-center p-3 bg-light">
                    <div class="text-warning fs-2"><i class="bi bi-star-fill"></i></div>
                    <h6>Reviews</h6>
                    <p class="display-6 fw-bold">
                        <?php echo e(\App\Models\Review::whereIn('product_id', $products->pluck('id'))->count()); ?>

                    </p>
                </div>
            </div>
        </div>

        <!-- Products Table -->
        <div class="orders-table p-3">
            <h4 class="mb-3"><i class="bi bi-clock-history"></i> Your Products</h4>
            <?php if(isset($products) && $products->count()): ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Subcategory</th>
                                <th>Price</th>
                                <th>Discount</th>
                                <th>Delivery</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>
                                        <?php if($p->image): ?>
                                            <a href="<?php echo e(route('product.details', $p->id)); ?>" class="d-inline-block">
                                                <img src="<?php echo e(asset('storage/'.$p->image)); ?>" alt="<?php echo e($p->name); ?>" style="height:48px; width:48px; object-fit:cover; border-radius:8px; border:1px solid #eee; cursor:pointer; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted small">No Image</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><a href="<?php echo e(route('product.details', $p->id)); ?>" class="text-decoration-none text-dark"><?php echo e($p->name); ?></a></td>
                                    <td><?php echo e(optional($p->category)->name ?? '-'); ?></td>
                                    <td><?php echo e(optional($p->subcategory)->name ?? '-'); ?></td>
                                    <td>₹<?php echo e(number_format($p->price, 2)); ?></td>
                                    <td><?php echo e($p->discount ? $p->discount . '%' : '-'); ?></td>
                                    <td><?php echo e($p->delivery_charge ? '₹' . number_format($p->delivery_charge, 2) : 'Free'); ?></td>
                                    <td><?php echo e($p->created_at?->format('d M Y')); ?></td>
                                    <td>
                                        <a href="<?php echo e(route('seller.editProduct', $p->id)); ?>"
                                            class="btn btn-sm btn-outline-primary">Edit</a>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="mb-0">You haven't added any products yet.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- JS for toggle -->
    <script>
        const toggleBtn = document.querySelector('.menu-toggle');
        const sidebar = document.getElementById('sidebarMenu');

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('show');
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html><?php /**PATH E:\e-com_updated_final\e-com_updated\resources\views/seller/dashboard.blade.php ENDPATH**/ ?>
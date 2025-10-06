<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Seller Orders</title>
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

    /* Content */
    .content {
      margin-left: 240px;
      padding: 20px;
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

    .table img {
      width: 80px;
      height: 80px;
      object-fit: cover;
      border-radius: 8px;
    }

    .badge {
      font-size: 0.9rem;
    }
  </style>
</head>

<body>
  <!-- Toggle Button (mobile only) -->
  <div class="menu-toggle d-md-none">
    <i class="bi bi-list"></i>
  </div>

  <!-- Sidebar -->
  <div class="sidebar d-flex flex-column p-3" id="sidebarMenu">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <img src="<?php echo e(asset('asset/images/grabbasket.png')); ?>" alt="Logo" class="logoimg" width="150px">
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
        <a class="nav-link" href="<?php echo e(route('seller.dashboard')); ?>">
          <i class="bi bi-speedometer2"></i> Dashboard
        </a>
      </li>
      <li>
        <a class="nav-link active" href="<?php echo e(route('seller.transactions')); ?>">
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

  <!-- Main Content -->
  <div class="content">
    <div class="container-fluid mt-4">
      <h2 class="mb-4"><i class="bi bi-cart-check"></i> Seller Orders</h2>

      <?php if(session('success')): ?>
      <div class="alert alert-success"><?php echo e(session('success')); ?></div>
      <?php endif; ?>

      <?php if($orders->isEmpty()): ?>
      <div class="alert alert-info text-center py-5">
        <i class="bi bi-box" style="font-size: 4rem; color: #ccc;"></i>
        <h4 class="mt-3 text-muted">No orders found</h4>
        <p>Orders for your products will appear here soon.</p>
      </div>
      <?php else: ?>
      <div class="card shadow-sm">
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
              <thead class="table-dark">
                <tr>
                  <th>Image</th>
                  <th>Product</th>
                  <th>Buyer</th>
                  <th>Amount</th>
                  <th>Status</th>
                  <th>Payment Method</th>
                  <th>Tracking #</th>
                  <th>Placed At</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                  <td>
                    <?php if($order->product->image): ?>
                      <img src="<?php echo e(asset('storage/' . $order->product->image)); ?>" alt="<?php echo e($order->product->name); ?>">
                    <?php else: ?>
                      <img src="<?php echo e(asset('images/no-image.png')); ?>" alt="No Image">
                    <?php endif; ?>
                  </td>
                  <td><?php echo e($order->product->name ?? '-'); ?></td>
                  <td><?php echo e($order->buyerUser->name ?? 'Unknown Buyer'); ?></td>
                  <td>₹<?php echo e(number_format($order->amount, 2)); ?></td>
                  <td>
                    <span class="badge 
                      <?php if($order->status === 'delivered'): ?> bg-success 
                      <?php elseif($order->status === 'pending'): ?> bg-warning text-dark 
                      <?php elseif($order->status === 'cancelled'): ?> bg-danger 
                      <?php else: ?> bg-secondary <?php endif; ?>">
                      <?php echo e(ucfirst($order->status)); ?>

                    </span>
                  </td>
                  <td><?php echo e(ucfirst($order->payment_method) ?? 'N/A'); ?></td>
                  <td>
                    <?php if(in_array($order->status, ['shipped', 'confirmed'])): ?>
                      <form action="<?php echo e(route('orders.updateTracking', $order->id)); ?>" method="POST" class="d-flex align-items-center">
                        <?php echo csrf_field(); ?>
                        <input type="text" name="tracking_number" value="<?php echo e($order->tracking_number); ?>" class="form-control form-control-sm me-2" placeholder="Enter tracking #" required>
                        <button type="submit" class="btn btn-primary btn-sm">Save</button>
                      </form>
                    <?php else: ?>
                      <?php echo e($order->tracking_number ?? '-'); ?>

                    <?php endif; ?>
                  </td>
                  <td><?php echo e($order->created_at->format('d M Y, h:i A')); ?></td>
                  <td>
                    <form method="POST" action="<?php echo e(route('orders.updateStatus', $order)); ?>" class="d-flex gap-2">
                      <?php echo csrf_field(); ?>
                      <select name="status" class="form-select form-select-sm">
                        <option value="pending" <?php echo e($order->status === 'pending' ? 'selected' : ''); ?>>Pending</option>
                        <option value="confirmed" <?php echo e($order->status === 'confirmed' ? 'selected' : ''); ?>>Confirmed</option>
                        <option value="shipped" <?php echo e($order->status === 'shipped' ? 'selected' : ''); ?>>Shipped</option>
                        <option value="delivered" <?php echo e($order->status === 'delivered' ? 'selected' : ''); ?>>Delivered</option>
                        <option value="cancelled" <?php echo e($order->status === 'cancelled' ? 'selected' : ''); ?>>Cancelled</option>
                      </select>
                      <button type="submit" class="btn btn-outline-primary btn-sm">Update</button>
                    </form>
                  </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </tbody>
            </table>
          </div>
          <div class="d-flex justify-content-center mt-3">
            <?php echo e($orders->links()); ?>

          </div>
        </div>
      </div>
      <?php endif; ?>
    </div>
  </div>

  <script>
    const toggleBtn = document.querySelector('.menu-toggle');
    const sidebar = document.getElementById('sidebarMenu');
    toggleBtn.addEventListener('click', () => sidebar.classList.toggle('show'));
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
<?php /**PATH E:\e-com_updated_final\e-com_updated\resources\views/seller/transactions.blade.php ENDPATH**/ ?>
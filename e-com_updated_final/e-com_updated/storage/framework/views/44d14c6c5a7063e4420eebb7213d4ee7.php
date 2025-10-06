<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Seller Profile</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body {
      background-color: #f8f9fa;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /* Sidebar */
    .sidebar {
      position: fixed;
      top: 0;
      bottom: 0;
      left: 0;
      width: 240px;
      background: #1f1f2e;
      color: #fff;
      padding-top: 60px;
      transition: all 0.3s;
      box-shadow: 2px 0 8px rgba(0, 0, 0, 0.2);
      z-index: 1000;
    }

    .sidebar img {
      display: block;
      margin: 0 auto;
    }

    .sidebar .nav-link {
      color: #cfd2d6;
      margin: 8px 0;
      padding: 10px 15px;
      border-radius: 8px;
      font-weight: 500;
      /* transition: all 0.2s ease-in-out; */
    }

    .sidebar .nav-link.active,
    .sidebar .nav-link:hover {
      background: #0d6efd;
      color: #fff;
      /* transform: translateX(4px); */
    }

    .sidebar .nav-link i {
      margin-right: 10px;
    }

    /* Content */
    .content {
      margin-left: 240px;
      padding: 20px;
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

    /* Profile Card */
    .profile-header {
      background: linear-gradient(135deg, #0d6efd, #6c63ff);
      color: white;
      border-radius: 10px 10px 0 0;
      padding: 20px;
      text-align: center;
    }

    .profile-header h2 {
      font-weight: 700;
    }

    .profile-card {
      border: none;
      border-radius: 12px;
      overflow: hidden;
    }

    .profile-avatar {
      width: 90px;
      height: 90px;
      border-radius: 50%;
      border: 3px solid white;
      object-fit: cover;
      margin-top: -45px;
    }

    /* Store info box */
    .info-box {
      background: #f9fafb;
      border: 1px solid #e0e0e0;
      border-radius: 10px;
      padding: 15px;
      transition: 0.3s;
    }

    .info-box:hover {
      background: #f1f5ff;
      border-color: #b3c7ff;
    }

    /* Product Cards */
    .product-card {
      border: none;
      transition: transform 0.2s, box-shadow 0.2s;
    }

    .product-card:hover {
      transform: translateY(-5px);
      box-shadow: 0px 6px 16px rgba(0, 0, 0, 0.1);
    }

    .product-card img {
      transition: transform 0.3s;
    }

    .product-card:hover img {
      transform: scale(1.05);
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
     .nav-pills {
      position: relative;
      bottom: 50px;
    }
  </style>
</head>

<body>
  <div class="menu-toggle d-md-none">
    <i class="bi bi-list"></i>
  </div>

  <!-- Sidebar -->
  <div class="sidebar d-flex flex-column p-3" id="sidebarMenu">
     <div class="d-flex justify-content-between align-items-center mb-4">
            <img src="<?php echo e(asset('asset/images/grabbasket.png')); ?>" alt="Logo"  width="150px">
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
      <li><a class="nav-link" href="<?php echo e(route('seller.createProduct')); ?>"><i class="bi bi-plus-circle"></i> Add Product</a></li>
      <li><a class="nav-link" href="<?php echo e(route('seller.createCategorySubcategory')); ?>"><i class="bi bi-plus-square"></i> Add Category</a></li>
      <li><a class="nav-link" href="<?php echo e(route('seller.dashboard')); ?>"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
      <li><a class="nav-link" href="<?php echo e(route('seller.transactions')); ?>"><i class="bi bi-cart-check"></i> Orders</a></li>
      <li><a class="nav-link active" href="<?php echo e(route('seller.profile')); ?>"><i class="bi bi-person-circle"></i> Profile</a></li>
      <li><a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
    </ul>
    <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" class="d-none"><?php echo csrf_field(); ?></form>
  </div>

  <!-- Main Content -->
  <div class="container py-5 content">
    <div class="row justify-content-center">
      <div class="col-lg-10">

        
        <div class="card profile-card shadow mb-4">
          <div class="profile-header">
            <h2>Seller Profile</h2>
          </div>
          <div class="card-body text-center">
            <img src="https://ui-avatars.com/api/?name=<?php echo e(urlencode($seller->name)); ?>&background=0d6efd&color=fff"
              alt="Avatar" class="profile-avatar shadow">
            <h4 class="mt-3"><?php echo e($seller->name); ?></h4>
            <p class="text-muted">📍 <?php echo e($seller->city); ?>, <?php echo e($seller->state); ?></p>
            <div class="mt-3">
              <p><i class="bi bi-envelope-fill text-primary"></i> <strong>Email:</strong> <?php echo e($seller->email); ?></p>
              <p><i class="bi bi-telephone-fill text-success"></i> <strong>Phone:</strong> <?php echo e($seller->phone); ?></p>
            </div>
          </div>
        </div>

        
        <div class="row g-3 mb-4">
          <div class="col-md-6"><div class="info-box"><strong>Store Name:</strong> <?php echo e($seller->store_name ?? 'N/A'); ?></div></div>
          <div class="col-md-6"><div class="info-box"><strong>GST Number:</strong> <?php echo e($seller->gst_number ?? 'N/A'); ?></div></div>
          <div class="col-md-6"><div class="info-box"><strong>Store Address:</strong> <?php echo e($seller->store_address ?? 'N/A'); ?></div></div>
          <div class="col-md-6"><div class="info-box"><strong>Store Contact:</strong> <?php echo e($seller->store_contact ?? 'N/A'); ?></div></div>
        </div>

        
        <?php if(auth()->guard()->check()): ?>
          <?php if(Auth::user()->email === $seller->email): ?>
            <div class="card shadow-sm mb-4">
              <div class="card-body">
                <a href="<?php echo e(route('seller.createProduct')); ?>" class="btn btn-warning w-100 mb-3 fw-semibold shadow">
                  <i class="bi bi-plus-circle"></i> Add Product
                </a>

                <form method="POST" action="<?php echo e(route('seller.updateProfile')); ?>" class="border rounded p-3 bg-light">
                  <?php echo csrf_field(); ?>
                  <h5 class="fw-bold mb-3">Update Store Info</h5>
                  <div class="mb-3">
                    <label class="form-label fw-bold">Store Name</label>
                    <input type="text" name="store_name" class="form-control"
                      value="<?php echo e(old('store_name', $seller->store_name)); ?>">
                  </div>
                  <div class="mb-3">
                    <label class="form-label fw-bold">GST Number (optional)</label>
                    <input type="text" name="gst_number" class="form-control"
                      value="<?php echo e(old('gst_number', $seller->gst_number)); ?>">
                  </div>
                  <div class="mb-3">
                    <label class="form-label fw-bold">Store Address</label>
                    <input type="text" name="store_address" class="form-control"
                      value="<?php echo e(old('store_address', $seller->store_address)); ?>">
                  </div>
                  <div class="mb-3">
                    <label class="form-label fw-bold">Store Contact</label>
                    <input type="text" name="store_contact" class="form-control"
                      value="<?php echo e(old('store_contact', $seller->store_contact)); ?>">
                  </div>
                  <button type="submit" class="btn btn-primary w-100 fw-semibold">Update</button>
                </form>
              </div>
            </div>
          <?php endif; ?>
        <?php endif; ?>

        
        <div class="card shadow">
          <div class="card-body">
            <h4 class="fw-bold text-secondary mb-4">Products</h4>
            <?php if($products->count()): ?>
              <div class="row g-4">
                <?php $__currentLoopData = $products->sortByDesc('created_at'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <div class="col-md-6 col-lg-4">
                    <div class="card product-card h-100 shadow-sm">
                      <div class="card-body text-center">
                        <?php if($p->image): ?>
                          <img src="<?php echo e(asset('storage/' . $p->image)); ?>" class="rounded mb-3 border shadow-sm"
                            style="width:120px; height:120px; object-fit:cover;" alt="<?php echo e($p->name); ?>">
                        <?php else: ?>
                          <div class="text-muted fs-1">🖼</div>
                        <?php endif; ?>
                        <h6 class="fw-bold text-primary"><?php echo e($p->name); ?></h6>
                        <div class="text-muted small mb-2"><?php echo e(optional($p->category)->name); ?> / <?php echo e(optional($p->subcategory)->name); ?></div>
                        <div class="fw-bold text-success">₹<?php echo e(number_format($p->price, 2)); ?></div>
                      </div>
                    </div>
                  </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </div>
            <?php else: ?>
              <p class="text-center text-muted">No products yet.</p>
            <?php endif; ?>
          </div>
        </div>

      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  

<script>
  document.addEventListener("DOMContentLoaded", function () {
    const toggleBtn = document.querySelector(".menu-toggle");
    const sidebar = document.getElementById("sidebarMenu");

    toggleBtn.addEventListener("click", function () {
      sidebar.classList.toggle("show");
    });
  });
</script>

</body>
</html><?php /**PATH E:\e-com_updated_final\e-com_updated\resources\views/seller/profile.blade.php ENDPATH**/ ?>
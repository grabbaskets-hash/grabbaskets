<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Cart</title>
  <link rel="icon" type="image/jpeg" href="<?php echo e(asset('asset/images/grabbasket.jpg')); ?>">


  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    html,
    body {
      height: 100%;
      margin: 0;
      display: flex;
      flex-direction: column;
      background-color: #f8f9fa;
    }

    /* Main content wrapper */
    main {
      flex: 1;
    }

    /* Navbar */
    .navbar {
      padding: 0.8rem 1rem;
    }

    .navbar-brand {
      font-size: 1.4rem;
      letter-spacing: 1px;
    }

    /* Product Image */
    .product-img {
      width: 150px;
      height: 150px;
      border-radius: 12px;
      object-fit: cover;
      margin-right: 20px;
      border: 1px solid #eee;
    }

    /* Cart Items */
    .cart-item {
      background: white;
      border-radius: 12px;
      padding: 20px;
      margin-bottom: 20px;
      box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.05);
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .cart-item:hover {
      transform: translateY(-3px);
      box-shadow: 0px 6px 14px rgba(0, 0, 0, 0.08);
    }

    /* Cart Summary */
    .cart-summary {
      background: white;
      border-radius: 12px;
      padding: 25px;
      box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
      position: sticky;
      top: 90px;
    }

    .cart-summary h5 {
      font-weight: 600;
    }

    .cart-summary strong.text-danger {
      font-size: 1.3rem;
    }

    .btn-lg {
      padding: 0.8rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
      .product-img {
        width: 100px;
        height: 100px;
      }

      .cart-item {
        flex-direction: column;
        align-items: flex-start !important;
      }

      .cart-item .text-end {
        margin-top: 10px;
        width: 100%;
      }
    }

    /* Footer Styles */
    footer {
      background-color: #343a40;
      color: #fff;
      width: 100%;
      margin-top: auto;
    }

    footer a {
      color: #fff;
      text-decoration: none;
    }

    footer a:hover {
      color: #ddd;
    }

    .footer-main-grid {
      display: grid;
      grid-template-columns: 1.2fr 1fr 1fr 1.2fr;
      gap: 3rem;
      align-items: start;
      max-width: 1200px;
      margin: 0 auto;
    }

    .follow {
      position: relative;
      left: -40px;
    }

    .para {
      font-size: 15px;
      top: 15px;
      position: relative;
    }

    .brand-column {
      padding-left: 0;
      margin-left: -0.5rem;
    }

    .brand-column h3,
    .brand-column p {
      margin-left: -3rem;
    }

    .quick-links-column,
    .support-column {
      padding: 0 1rem;
    }

    .follow-column {
      text-align: right;
      padding-right: 0;
    }

    .follow-icons {
      display: flex;
      gap: 0.9rem;
      justify-content: flex-end;
    }

    .bottom-bar {
      background-color: #212529;
      padding: 10px 0;
      text-align: center;
      font-size: 0.9rem;
      color: #ccc;
    }

    /* Tablet */
    @media (max-width: 991px) {
      .footer-main-grid {
        grid-template-columns: 1fr 1fr;
        gap: 2.5rem;
      }

      .brand-column {
        grid-column: 1;
        margin-left: -0.5rem;
      }

      .quick-links-column {
        grid-column: 2;
      }

      .support-column {
        grid-column: 1;
      }

      .follow-column {
        grid-column: 2;
        text-align: right;
      }
    }

    /* Mobile */
    @media (max-width: 767px) {
      .footer-main-grid {
        grid-template-columns: 1fr;
        gap: 2rem;
      }

      .brand-column {
        grid-column: 1;
        margin-left: 0;
        padding-left: 0;
      }

      .quick-links-column,
      .support-column {
        padding: 0;
      }

      .follow-column {
        text-align: left;
        padding-right: 0;
      }

      .follow-icons {
        justify-content: flex-start;
        margin-top: 1rem;
      }
    }

    /* Extra Small */
    @media (max-width: 575px) {
      .footer-main-grid {
        gap: 1.5rem;
      }

      .brand-column h3 {
        font-size: 1.25rem;
      }

      .brand-column p {
        font-size: 0.813rem;
      }

      .follow-icons {
        flex-wrap: wrap;
        gap: 0.75rem;
      }
    }
  </style>
</head>

<body>
  <?php if (isset($component)) { $__componentOriginal5c84f04e4e4c3f6b2afa5416a6776687 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5c84f04e4e4c3f6b2afa5416a6776687 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.back-button','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('back-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5c84f04e4e4c3f6b2afa5416a6776687)): ?>
<?php $attributes = $__attributesOriginal5c84f04e4e4c3f6b2afa5416a6776687; ?>
<?php unset($__attributesOriginal5c84f04e4e4c3f6b2afa5416a6776687); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5c84f04e4e4c3f6b2afa5416a6776687)): ?>
<?php $component = $__componentOriginal5c84f04e4e4c3f6b2afa5416a6776687; ?>
<?php unset($__componentOriginal5c84f04e4e4c3f6b2afa5416a6776687); ?>
<?php endif; ?>

  <nav class="navbar navbar-expand-lg" style="background-color:rgb(30, 30, 55);">
    <div class="container-fluid d-flex justify-content-around align-items-center">

      <!-- Logo -->
      <a href="<?php echo e(url('/')); ?>" class="nav-link text-orange">
        <img src="<?php echo e(asset('asset/images/grabbasket.png')); ?>" alt="Logo" width="180px">
      </a>

      <!-- Search Bar -->
      <form class="d-none d-lg-flex mx-auto" role="search" style="width: 600px;">
        <input class="form-control me-2" type="search" placeholder="Search products,brands and more..." aria-label="Search">
        <button class="btn btn-outline-warning" type="submit">Search</button>
      </form>

      <!-- Right Side -->
      <div class="d-flex align-items-center gap-2">
        <span class="d-none d-lg-inline" style="color:beige;">Hello, <?php echo e(Auth::user()->name); ?></span>

        <div class="dropdown">
          <button class="btn btn-outline-warning btn-sm dropdown-toggle d-flex align-items-center gap-1" type="button"
            id="accountDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-person-circle"></i> My Account
          </button>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="accountDropdown" style="min-width: 220px;">
            <li><a class="dropdown-item" href="<?php echo e(url('/profile')); ?>"><i class="bi bi-person"></i> Profile</a></li>
            <li><a class="dropdown-item" href="<?php echo e(url('/cart')); ?>"><i class="bi bi-cart"></i> Cart</a></li>
            <li><a class="dropdown-item" href="<?php echo e(route('buyer.dashboard')); ?>"><i class="bi bi-shop"></i> Shop</a></li>
            <li><a class="dropdown-item" href="<?php echo e(route('seller.dashboard')); ?>"><i class="bi bi-briefcase"></i> Seller</a></li>
            <li><a class="dropdown-item" href="<?php echo e(url('/wishlist')); ?>"><i class="bi bi-heart"></i> Wishlist</a></li>
            <li><a class="dropdown-item" href="<?php echo e(url('/')); ?>"><i class="bi bi-house"></i> Home</a></li>
          </ul>
        </div>

        <?php if($items->count()): ?>
        <form method="POST" action="<?php echo e(route('cart.clear')); ?>">
          <?php echo csrf_field(); ?>
          <?php echo method_field('DELETE'); ?>
          <button class="btn btn-outline-warning btn-sm d-flex align-items-center gap-1">
            <i class="bi bi-trash"></i> Clear
          </button>
        </form>
        <?php endif; ?>

        <a href="<?php echo e(route('logout')); ?>" class="btn btn-outline-warning btn-sm d-flex align-items-center gap-1"
          onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
          <i class="bi bi-box-arrow-right"></i> Logout
        </a>
        <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" class="d-none">
          <?php echo csrf_field(); ?>
        </form>
      </div>
    </div>
  </nav>

  <!-- Main content -->
  <main>
    <div class="container mt-4">
      <div class="row">
        <!-- Cart Items -->
        <div class="col-lg-8">
          <h3 class="mb-4"><i class="bi bi-cart-check-fill text-primary"></i> My Shopping Cart</h3>

          <?php if(session('success')): ?>
          <div class="alert alert-success"><?php echo e(session('success')); ?></div>
          <?php endif; ?>

          <?php if(!$items->count()): ?>
          <p class="text-muted">Your cart is empty.</p>
          <?php else: ?>
          <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <div class="cart-item d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
              <?php if(optional($item->product)->image): ?>
              <a href="<?php echo e(route('product.details', $item->product->id)); ?>" class="me-3">
                <img src="<?php echo e(asset('storage/' . $item->product->image)); ?>" alt="<?php echo e($item->product->name); ?>" class="product-img" style="cursor: pointer; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
              </a>
              <?php endif; ?>

              <div>
                <h5 class="fw-semibold mb-2">
                  <a href="<?php echo e(route('product.details', $item->product->id)); ?>" class="text-decoration-none text-dark">
                    <?php echo e(optional($item->product)->name ?? 'Product'); ?>

                  </a>
                </h5>
                <p class="text-muted mb-2">
                  Price: <strong>₹<?php echo e(number_format($item->price, 2)); ?></strong> |
                  Discount: <strong><?php echo e($item->discount ? $item->discount . '%' : '-'); ?></strong> |
                  Delivery:
                  <strong><?php echo e($item->delivery_charge ? '₹' . number_format($item->delivery_charge, 2) : 'Free'); ?></strong>
                </p>

                <form method="POST" action="<?php echo e(route('cart.update', $item)); ?>" class="d-flex align-items-center">
                  <?php echo csrf_field(); ?>
                  <?php echo method_field('PATCH'); ?>
                  <input type="number" min="1" max="10" name="quantity" value="<?php echo e($item->quantity); ?>"
                    class="form-control form-control-sm me-2" style="width: 80px;">
                  <button class="btn btn-sm btn-outline-primary">Update</button>
                </form>
              </div>
            </div>

            <div class="text-end">
              <?php
              $price = (float) $item->price;
              $disc = (float) $item->discount;
              $qty = (int) $item->quantity;
              $delivery = (float) $item->delivery_charge;
              $base = $price * $qty;
              $less = $disc > 0 ? ($base * ($disc / 100)) : 0;
              $lineTotal = $base - $less + $delivery;
              ?>
              <h5 class="text-danger fw-bold">₹<?php echo e(number_format($lineTotal, 2)); ?></h5>
              <form method="POST" action="<?php echo e(route('cart.remove', $item)); ?>">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                <button class="btn btn-sm btn-outline-danger mt-2"><i class="bi bi-x-circle"></i> Remove</button>
              </form>
              <form method="POST" action="<?php echo e(route('cart.moveToWishlist', $item)); ?>" class="mt-2">
                <?php echo csrf_field(); ?>
                <button class="btn btn-sm btn-outline-warning"><i class="bi bi-heart"></i> Move to Wishlist</button>
              </form>
            </div>
          </div>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          <?php endif; ?>
        </div>

        <!-- Cart Summary -->
        <div class="col-lg-4">
          <?php if($items->count()): ?>
          <div class="cart-summary">
            <h5 class="mb-3">Order Summary</h5>
            <div class="d-flex justify-content-between mb-2">
              <span>Subtotal</span>
              <span>₹<?php echo e(number_format($totals['subtotal'], 2)); ?></span>
            </div>
            <div class="d-flex justify-content-between mb-2">
              <span>Discount</span>
              <span>-₹<?php echo e(number_format($totals['discountTotal'], 2)); ?></span>
            </div>
            <div class="d-flex justify-content-between mb-2">
              <span>Delivery</span>
              <span>₹<?php echo e(number_format($totals['deliveryTotal'], 2)); ?></span>
            </div>
            <hr>
            <div class="d-flex justify-content-between">
              <strong>Total</strong>
              <strong class="text-danger">₹<?php echo e(number_format($totals['total'], 2)); ?></strong>
            </div>
            <div class="d-grid gap-2 mt-4">
              <a href="<?php echo e(route('cart.checkout.page')); ?>"
                class="btn btn-success btn-lg fw-semibold shadow-sm d-flex align-items-center justify-content-center gap-2">
                <i class="bi bi-bag-check-fill"></i> Checkout
              </a>

              <a href="<?php echo e(route('buyer.dashboard')); ?>"
                class="btn btn-outline-primary btn-lg fw-semibold shadow-sm d-flex align-items-center justify-content-center gap-2">
                <i class="bi bi-arrow-left-circle"></i> Continue Shopping
              </a>
            </div>
          </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </main>

  <!-- Footer Start -->
  <footer>
    <div class="container py-5">
      <div class="footer-main-grid">
        <div class="brand-column">
          <h3 class="mb-3"><img src="<?php echo e(asset('asset/images/grabbasket.png')); ?>" alt="Logo" width="180px"></h3>
          <p class="para">Your trusted online marketplace for quality products at the best prices.</p>
        </div>

        <div class="quick-links-column">
          <h4 class="mb-3">Quick Links</h4>
          <ul class="list-unstyled">
            <li><a href="<?php echo e(route('buyer.dashboard')); ?>">Shop</a></li>
            <li><a href="<?php echo e(route('seller.dashboard')); ?>">Seller</a></li>
            <li><a href="<?php echo e(route('cart.index')); ?>">Cart</a></li>
            <li><a href="<?php echo e(route('login')); ?>">Login</a></li>
          </ul>
        </div>

        <div class="support-column">
          <h4 class="mb-3">Customer Support</h4>
          <ul class="list-unstyled">
            <li><a href="#">Help Center</a></li>
            <li><a href="#">FAQ</a></li>
            <li><a href="#">Contact Us</a></li>
          </ul>
        </div>

        <div class="follow-column">
          <h4 class="mb-3 follow">Follow Us</h4>
          <div class="follow-icons">
            <a href="https://wa.me/1234567890" target="_blank" class="bg-secondary rounded-circle p-2">
              <i class="bi bi-whatsapp text-white"></i>
            </a>
            <a href="https://instagram.com/yourprofile" target="_blank" class="bg-secondary rounded-circle p-2">
              <i class="bi bi-instagram text-white"></i>
            </a>
            <a href="https://twitter.com/yourprofile" target="_blank" class="bg-secondary rounded-circle p-2">
              <i class="bi bi-twitter text-white"></i>
            </a>
            <a href="https://facebook.com/yourprofile" target="_blank" class="bg-secondary rounded-circle p-2">
              <i class="bi bi-facebook text-white"></i>
            </a>
          </div>
        </div>
      </div>
    </div>
    <div class="bottom-bar">
      &copy; 2025 grabbasket. All Rights Reserved.
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html><?php /**PATH E:\e-com_updated_final\e-com_updated\resources\views/cart/index.blade.php ENDPATH**/ ?>
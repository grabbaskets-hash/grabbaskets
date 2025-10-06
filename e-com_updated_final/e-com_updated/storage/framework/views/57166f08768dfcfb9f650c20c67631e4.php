<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Buyer Dashboard - Shop Now</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

       

        .dashboard-header {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            padding: 40px 20px;
            border-radius: 12px;
            margin-bottom: 30px;
            text-align: center;
            box-shadow: 0px 4px 15px rgba(0, 123, 255, 0.3);
        }

        .product-card {
            border-radius: 12px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            background: white;
            border: none;
            overflow: hidden;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.12);
        }

        .product-img {
            height: 200px;
            object-fit: cover;
            width: 100%;
        }

        .price-tag {
            font-size: 1.2rem;
            font-weight: bold;
            color: #007bff;
        }

        .discount-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #ff4757;
            color: white;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.8rem;
        }

        .gift-badge {
            background: #ffa726;
            color: white;
            font-size: 0.8rem;
        }

        .quick-stats {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.08);
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
                <input class="form-control me-2" type="search" placeholder="Search products,brands and more..."
                    aria-label="Search">
                <button class="btn btn-outline-warning" type="submit">Search</button>
            </form>

            <!-- Right Side: Hello + Buttons -->
            <div class="d-flex align-items-center gap-2">

                <!-- Hello User -->
                <span class="d-none d-lg-inline" style="color:beige;">Hello, <?php echo e(Auth::user()->name); ?></span>

                <!-- My Account Dropdown -->
                <div class="dropdown">
                    <button class="btn btn-outline-warning btn-sm dropdown-toggle d-flex align-items-center gap-1"
                        type="button" id="accountDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle"></i> My Account
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="accountDropdown"
                        style="min-width: 220px;">
                        <li><a class="dropdown-item" href="<?php echo e(url('/profile')); ?>"><i class="bi bi-person"></i>
                                Profile</a></li>
                        <li><a class="dropdown-item" href="<?php echo e(url('/cart')); ?>"><i class="bi bi-cart"></i> Cart</a>
                        </li>
                        <li><a class="dropdown-item" href="<?php echo e(route('buyer.dashboard')); ?>"><i class="bi bi-shop"></i>
                                Shop</a></li>
                        <li><a class="dropdown-item" href="<?php echo e(route('seller.dashboard')); ?>"><i
                                    class="bi bi-briefcase"></i> Seller</a></li>
                        <li><a class="dropdown-item" href="<?php echo e(url('/wishlist')); ?>"><i class="bi bi-heart"></i>
                                Wishlist</a></li>
                        <li><a class="dropdown-item" href="<?php echo e(url('/')); ?>"><i class="bi bi-house"></i>
                                Home</a></li>
                    </ul>
                </div>

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

    <div class="container mt-4">
        <!-- Dashboard Header -->
        <div class="dashboard-header">
            <?php
                $user = Auth::user();
                $gender = $user->sex ?? 'other';
                $greeting = match ($gender) {
                    'male' => "Welcome back, Mr. {$user->name}!",
                    'female' => "Welcome back, Ms. {$user->name}!",
                    default => "Welcome back, {$user->name}!"
                };
            ?>
            <h2><?php echo e($greeting); ?></h2>
            <p class="mb-0">Discover amazing products and great deals!</p>
        </div>

        <?php if(session('success')): ?>
            <div class="alert alert-success"><?php echo e(session('success')); ?></div>
        <?php endif; ?>

        <!-- Quick Stats -->
        <div class="quick-stats">
            <div class="row text-center">
                <div class="col-md-3">
                    <div class="p-3">
                        <i class="bi bi-bag-check display-6 text-primary"></i>
                        <h5 class="mt-2"><?php echo e(Auth::user()->buyerOrders()->count()); ?></h5>
                        <small class="text-muted">Total Orders</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="p-3">
                        <i class="bi bi-heart-fill display-6 text-danger"></i>
                        <h5 class="mt-2"><?php echo e(Auth::user()->wishlists()->count()); ?></h5>
                        <small class="text-muted">Wishlist Items</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="p-3">
                        <i class="bi bi-cart-fill display-6 text-success"></i>
                        <h5 class="mt-2"><?php echo e(Auth::user()->cartItems()->count()); ?></h5>
                        <small class="text-muted">Cart Items</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="p-3">
                        <i class="bi bi-bell-fill display-6 text-warning"></i>
                        <h5 class="mt-2"><?php echo e(Auth::user()->notifications()->whereNull('read_at')->count()); ?></h5>
                        <small class="text-muted">Unread Notifications</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Categories -->
        <div class="mb-4">
            <h4 class="mb-3"><i class="bi bi-grid-3x3-gap"></i> Browse by Categories</h4>
            <div class="row">
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-md-2 col-sm-4 col-6 mb-3">
                        <a href="<?php echo e(route('buyer.productsByCategory', $category->id)); ?>"
                            class="btn btn-outline-primary w-100 py-3 text-center">
                            <i class="bi bi-tag d-block mb-2"></i>
                            <?php echo e($category->name); ?>

                        </a>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        <!-- Latest Products -->
        <h4 class="mb-3"><i class="bi bi-star"></i> Latest Products</h4>
        <div class="row">
            <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="product-card position-relative">
                        <?php if($product->discount > 0): ?>
                            <span class="discount-badge"><?php echo e($product->discount); ?>% OFF</span>
                        <?php endif; ?>

                        <?php if($product->image): ?>
                            <img src="<?php echo e(asset('storage/' . $product->image)); ?>" alt="<?php echo e($product->name); ?>" class="product-img">
                        <?php else: ?>
                            <div class="product-img d-flex align-items-center justify-content-center bg-light">
                                <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                            </div>
                        <?php endif; ?>

                        <div class="p-3">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="mb-0 flex-grow-1"><?php echo e($product->name); ?></h6>
                                <?php if (isset($component)) { $__componentOriginal015430dd305fffb822ae1c1dd84aac8e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal015430dd305fffb822ae1c1dd84aac8e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.wishlist-heart','data' => ['product' => $product]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('wishlist-heart'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['product' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($product)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal015430dd305fffb822ae1c1dd84aac8e)): ?>
<?php $attributes = $__attributesOriginal015430dd305fffb822ae1c1dd84aac8e; ?>
<?php unset($__attributesOriginal015430dd305fffb822ae1c1dd84aac8e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal015430dd305fffb822ae1c1dd84aac8e)): ?>
<?php $component = $__componentOriginal015430dd305fffb822ae1c1dd84aac8e; ?>
<?php unset($__componentOriginal015430dd305fffb822ae1c1dd84aac8e); ?>
<?php endif; ?>
                            </div>

                            <p class="text-muted small mb-2"><?php echo e(Str::limit($product->description, 60)); ?></p>

                            <div class="mb-2">
                                <?php if($product->gift_option === 'yes'): ?>
                                    <span class="badge gift-badge me-1">
                                        <i class="bi bi-gift"></i> Gift Option
                                    </span>
                                <?php endif; ?>
                                <?php if($product->delivery_charge == 0): ?>
                                    <span class="badge bg-success">Free Delivery</span>
                                <?php endif; ?>
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="price-tag">₹<?php echo e(number_format($product->price, 2)); ?></span>
                                    <?php if($product->delivery_charge > 0): ?>
                                        <small class="text-muted d-block">+ ₹<?php echo e($product->delivery_charge); ?> delivery</small>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="mt-3 d-grid gap-2">
                                <form method="POST" action="<?php echo e(route('cart.add')); ?>">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="product_id" value="<?php echo e($product->id); ?>">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-cart-plus"></i> Add to Cart
                                    </button>
                                </form>
                                <a href="<?php echo e(route('product.details', $product->id)); ?>"
                                    class="btn btn-outline-secondary btn-sm">
                                    <i class="bi bi-eye"></i> View Details
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            <?php echo e($products->links()); ?>

        </div>
    </div>
    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html><?php /**PATH E:\e-com_updated_final\e-com_updated\resources\views/buyer/index.blade.php ENDPATH**/ ?>
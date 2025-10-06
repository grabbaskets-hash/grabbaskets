<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login - grabbasket</title>
  <link rel="icon" type="image/jpeg" href="<?php echo e(asset('asset/images/grabbasket.jpg')); ?>">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    body {
      background: linear-gradient(135deg, #fdfbfb, #ebedee);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .card {
      border: none;
      border-radius: 20px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.08);
      padding: 2.5rem;
      background: #fff;
    }
    .brand {
      font-size: 1.8rem;
      font-weight: 700;
      color: darkorange;
      text-transform: uppercase;
      letter-spacing: 2px;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .brand img {
      width: 40px;
      height: 40px;
      margin-right: 10px;
      object-fit: contain;
    }
    .form-label {
      font-weight: 600;
      color: #333;
    }
    .input-group-text {
      background: #f8f9fa;
      border-right: 0;
      border-radius: 12px 0 0 12px;
      color: #888;
    }
    .form-control {
      border-radius: 0 12px 12px 0;
      padding: 0.7rem 1rem;
      border: 1px solid #ddd;
      transition: all 0.3s ease;
    }
    .form-control:focus {
      border-color: #ff6b00;
      box-shadow: 0 0 0 0.2rem rgba(255,107,0,.2);
    }
    .btn-primary {
      background: linear-gradient(to right, #ff9900, #ff6b00);
      border: none;
      border-radius: 12px;
      padding: 0.8rem 1.5rem;
      font-weight: 600;
      transition: all 0.3s ease;
    }
    .btn-primary:hover {
      background: #232f3e;
      color: #fff;
    }
    .register-link {
      color: #ff6b00;
      font-weight: 600;
    }
    .register-link:hover {
      color: #232f3e;
      text-decoration: underline;
    }
    .forgot-link {
      color: #555;
      font-size: 0.9rem;
    }
    .forgot-link:hover {
      color: #ff6b00;
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-md-6 col-lg-5">
        
        <!-- Brand Logo -->
        <div class="text-center mb-4">
          <a href="/" class="brand text-decoration-none">
            <img src="<?php echo e(asset('asset/images/grabbasket.png')); ?>" alt="Grabbasket Logo">
            grabbasket
          </a>
        </div>

        <!-- Card -->
        <div class="card">
          <?php if(session('status')): ?>
            <div class="alert alert-info"><?php echo e(session('status')); ?></div>
          <?php endif; ?>
          <h4 class="mb-4 text-center">Login to Your Account</h4>

          <form method="POST" action="<?php echo e(route('login')); ?>" novalidate>
            <?php echo csrf_field(); ?>

            <!-- Role Selection -->
            <div class="mb-3">
              <label class="form-label d-block">Login as</label>
              <div class="d-flex gap-3">
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="role" id="role_buyer" value="buyer" <?php echo e(old('role', 'buyer') == 'buyer' ? 'checked' : ''); ?>>
                  <label class="form-check-label" for="role_buyer">Buyer</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="role" id="role_seller" value="seller" <?php echo e(old('role') == 'seller' ? 'checked' : ''); ?>>
                  <label class="form-check-label" for="role_seller">Seller</label>
                </div>
              </div>
              <?php $__errorArgs = ['role'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger small mt-1"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- Email / Phone -->
            <div class="mb-3">
              <label for="login" class="form-label">Email or Phone</label>
              <div class="input-group">
                <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                <input type="text" class="form-control" id="login" name="login" value="<?php echo e(old('login')); ?>" required autocomplete="username" autofocus>
              </div>
              <?php $__errorArgs = ['login'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger small mt-1"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- Password -->
            <div class="mb-3">
              <label for="password" class="form-label">Password</label>
              <div class="input-group">
                <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                <input type="password" class="form-control" id="password" name="password" required autocomplete="current-password">
              </div>
              <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger small mt-1"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- Remember me -->
            <div class="mb-3 form-check">
              <input type="checkbox" class="form-check-input" id="remember_me" name="remember">
              <label class="form-check-label" for="remember_me">Remember me</label>
            </div>

            <!-- Forgot password -->
            <div class="d-flex justify-content-between align-items-center mb-3">
              <?php if(Route::has('password.request')): ?>
                <a class="forgot-link" href="<?php echo e(route('password.request')); ?>">Forgot your password?</a>
              <?php endif; ?>
            </div>

            <!-- Submit -->
            <button type="submit" class="btn btn-primary w-100">Log in</button>
          </form>

          <!-- Register -->
          <?php if(Route::has('register')): ?>
          <div class="text-center mt-3">
            <span class="text-muted small">New user?</span>
            <a class="ms-1 register-link" href="<?php echo e(route('register')); ?>">Register here</a>
          </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html><?php /**PATH E:\e-com_updated_final\e-com_updated\resources\views/auth/login.blade.php ENDPATH**/ ?>
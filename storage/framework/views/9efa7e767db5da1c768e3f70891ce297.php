<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Register - grabbasket</title>
<link rel="icon" type="image/jpeg" href="<?php echo e(asset('asset/images/grabbasket.jpg')); ?>">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
  body {
    background: linear-gradient(135deg, #fdfbfb, #ebedee);
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  }
  .card {
    border: none;
    border-radius: 20px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
    padding: 2rem;
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
  .form-control, .form-select {
    border-radius: 12px;
    padding: 0.7rem 1rem;
    border: 1px solid #ddd;
    transition: all 0.3s ease;
  }
  .form-control:focus, .form-select:focus {
    border-color: #ff6b00;
    box-shadow: 0 0 0 0.2rem rgba(255,107,0,.2);
  }
  .btn-primary {
    background: #ff6b00;
    border: none;
    border-radius: 12px;
    padding: 0.7rem 1.5rem;
    font-weight: 600;
    transition: all 0.3s ease;
  }
  .btn-primary:hover {
    background: #232f3e;
    color: #fff;
  }
  .login-link {
    color: #ff6b00;   /* orange to match theme */
    font-weight: 600;
    text-decoration: none;
    transition: color 0.3s ease;
  }
  .login-link:hover {
    color: #232f3e;   /* dark navy on hover */
    text-decoration: underline;
  }
</style>
</head>
<body>
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-7">
      <!-- Brand with Icon -->
      <div class="text-center mb-4">
        <a href="/" class="brand text-decoration-none">
          <img src="<?php echo e(asset('asset/images/grabbasket.png')); ?>" alt="Grabbasket Logo">
          grabbasket
        </a>
      </div>

      <div class="card">
        <h4 class="mb-4 text-center">Create Your Account</h4>
        <form method="POST" action="<?php echo e(route('register')); ?>" novalidate>
          <?php echo csrf_field(); ?>
          <div class="row g-3">
            <div class="col-md-6">
              <label for="name" class="form-label">Full Name</label>
              <input id="name" type="text" class="form-control" name="name" value="<?php echo e(old('name')); ?>" required>
              <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger small mt-1"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="col-md-6">
              <label for="email" class="form-label">Email</label>
              <input id="email" type="email" class="form-control" name="email" value="<?php echo e(old('email')); ?>" required>
              <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger small mt-1"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="col-md-6">
              <label for="phone" class="form-label">Phone Number</label>
              <input id="phone" type="text" class="form-control" name="phone" value="<?php echo e(old('phone')); ?>" required>
              <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger small mt-1"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="col-md-6">
              <label for="sex" class="form-label">Gender</label>
              <select id="sex" name="sex" class="form-select" required>
                <option value="">Select Gender</option>
                <option value="male" <?php echo e(old('sex') == 'male' ? 'selected' : ''); ?>>Male</option>
                <option value="female" <?php echo e(old('sex') == 'female' ? 'selected' : ''); ?>>Female</option>
                <option value="other" <?php echo e(old('sex') == 'other' ? 'selected' : ''); ?>>Other</option>
              </select>
              <?php $__errorArgs = ['sex'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger small mt-1"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="col-md-6">
              <label for="role" class="form-label">Register As</label>
              <select id="role" name="role" class="form-select" required>
                <option value="">Select Role</option>
                <option value="seller" <?php echo e(old('role') == 'seller' ? 'selected' : ''); ?>>Seller</option>
                <option value="buyer" <?php echo e(old('role') == 'buyer' ? 'selected' : ''); ?>>Buyer</option>
              </select>
              <?php $__errorArgs = ['role'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger small mt-1"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="col-12">
              <label for="billing_address" class="form-label">Address</label>
              <input id="billing_address" type="text" class="form-control" name="billing_address" value="<?php echo e(old('billing_address')); ?>" required>
              <?php $__errorArgs = ['billing_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger small mt-1"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="col-md-4">
              <label for="state" class="form-label">State</label>
              <input id="state" type="text" class="form-control" name="state" value="<?php echo e(old('state')); ?>" required>
              <?php $__errorArgs = ['state'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger small mt-1"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="col-md-4">
              <label for="city" class="form-label">City</label>
              <input id="city" type="text" class="form-control" name="city" value="<?php echo e(old('city')); ?>" required>
              <?php $__errorArgs = ['city'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger small mt-1"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="col-md-4">
              <label for="pincode" class="form-label">Pincode</label>
              <input id="pincode" type="text" class="form-control" name="pincode" value="<?php echo e(old('pincode')); ?>" required>
              <?php $__errorArgs = ['pincode'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger small mt-1"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="col-md-6">
              <label for="password" class="form-label">Password</label>
              <input id="password" type="password" class="form-control" name="password" required>
              <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger small mt-1"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="col-md-6">
              <label for="password_confirmation" class="form-label">Confirm Password</label>
              <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required>
              <?php $__errorArgs = ['password_confirmation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger small mt-1"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
          </div>
          <div class="d-flex justify-content-between align-items-center mt-4">
            <a href="<?php echo e(route('login')); ?>" class="small login-link">Already registered? Login</a>
            <button type="submit" class="btn btn-primary">Register</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html><?php /**PATH E:\e-com_updated_final\e-com_updated\resources\views/auth/register.blade.php ENDPATH**/ ?>
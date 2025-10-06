<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <link rel="icon" type="image/jpeg" href="<?php echo e(asset('asset/images/grabbasket.jpg')); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex align-items-center justify-content-center vh-100">
    <div class="card p-4 shadow" style="width: 400px;">
        <div class="text-center mb-3">
            <img src="<?php echo e(asset('asset/images/grabbasket.png')); ?>" alt="GrabBasket Logo" style="height: 60px;">
        </div>
        <h3 class="text-center mb-3">Admin Login</h3>
        <?php if($errors->any()): ?>
            <div class="alert alert-danger">
                <?php echo e($errors->first()); ?>

            </div>
        <?php endif; ?>
        <form method="POST" action="<?php echo e(route('admin.login.submit')); ?>">
            <?php echo csrf_field(); ?>
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required autofocus>
            </div>
            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button class="btn btn-primary w-100">Login</button>
        </form>
    </div>
</body>
</html>
<?php /**PATH E:\e-com_updated_final\e-com_updated\resources\views/admin/login.blade.php ENDPATH**/ ?>
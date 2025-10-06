<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Customer Profile</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f4f6f8;
      margin: 0;
      padding: 0;
    }
    .container {
      max-width: 900px;
      margin: 40px auto;
      background: #fff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 6px 10px rgba(0,0,0,0.1);
    }
    h2 {
      text-align: center;
      margin-bottom: 25px;
      color: #222;
    }
    .profile-header {
      display: flex;
      align-items: center;
      gap: 20px;
      margin-bottom: 30px;
    }
    .profile-header img {
      width: 120px;
      height: 120px;
      border-radius: 50%;
      object-fit: cover;
      border: 3px solid #ffcc00;
    }
    .profile-header div {
      line-height: 1.6;
    }
    .profile-header h3 {
      margin: 0;
    }
    .section {
      margin-bottom: 30px;
    }
    .section h3 {
      border-bottom: 2px solid #eee;
      padding-bottom: 10px;
      margin-bottom: 15px;
      color: #444;
    }
    .info-list p {
      margin: 8px 0;
      font-size: 15px;
    }
    .info-list strong {
      width: 150px;
      display: inline-block;
      color: #333;
    }
    .btn {
      padding: 8px 15px;
      background: #ffcc00;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-weight: bold;
      transition: 0.3s;
      margin-top: 10px;
    }
    .btn:hover {
      background: #e6b800;
    }
    .alert {
      padding: 10px;
      border-radius: 5px;
      margin-bottom: 20px;
    }
    .alert-success {
      background: #d4edda;
      color: #155724;
    }
    .alert-danger {
      background: #f8d7da;
      color: #721c24;
    }
  </style>
</head>
<body>

<div class="container">

  <h2>My Profile</h2>

  <!-- Success & Error Messages -->
  <?php if(session('success')): ?>
    <div class="alert alert-success"><?php echo e(session('success')); ?></div>
  <?php endif; ?>

  <?php if($errors->any()): ?>
    <div class="alert alert-danger">
      <ul>
        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <li><?php echo e($error); ?></li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </ul>
    </div>
  <?php endif; ?>

  <!-- Profile Header -->
  <div class="profile-header">
    
    <div>
      <h3>Name:<?php echo e($user->name); ?></h3>
      <p><strong>Phone Number:</strong> <?php echo e($user->phone ?? 'Not set'); ?></p>
    </div>
  </div>

  <!-- Personal Information -->
  <div class="section">
    <h3>Personal Information</h3>
    <div class="info-list">
      <p><strong>Full Name:</strong> <?php echo e($user->name); ?></p>
      <p><strong>Gender:</strong> <?php echo e($user->sex ?? 'Not set'); ?></p>
      <p><strong>Date of Birth:</strong> <?php echo e($user->dob ?? 'Not set'); ?></p>
    </div>
  </div>

  <!-- Contact Information -->
  <div class="section">
    <h3>Contact Information</h3>
    <div class="info-list">
      <p><strong>Email:</strong> <?php echo e($user->email); ?></p>
      <p><strong>Phone:</strong> <?php echo e($user->phone ?? 'Not set'); ?></p>
    </div>
  </div>

  <!-- Address Information -->
  <div class="section">
    <h3>Address Information</h3>
    <div class="info-list">
      <p><strong>Default Address:</strong> <?php echo e($user->default_address ?? 'Not set'); ?></p>
      <p><strong>Billing Address:</strong> <?php echo e($user->billing_address ?? 'Not set'); ?></p>
    </div>
  </div>

  <!-- Account Settings -->
  <div class="section">
    <h3>Account Settings</h3>
    <div class="info-list">
      <p><strong>Password:</strong> ******** 
        <a href="<?php echo e(route('password.request')); ?>" class="btn">Change</a>
      </p>
    </div>
  </div>

  <!-- Edit Profile Form -->
  <div class="section" style="text-align: center; ">
    <a href="<?php echo e(route('profile.edit')); ?>" class="btn" style="text-decoration:none;">Edit Profile</a>
  </div> 

</div>

</body>
</html>
<?php /**PATH E:\e-com_updated_final\e-com_updated\resources\views/profile/index.blade.php ENDPATH**/ ?>
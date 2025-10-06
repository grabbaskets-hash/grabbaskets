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
  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  @if($errors->any())
    <div class="alert alert-danger">
      <ul>
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <!-- Profile Header -->
  <div class="profile-header">
    {{-- <img src="https://via.placeholder.com/120" alt="Profile Picture"> --}}
    <div>
      <h3>Name:{{ $user->name }}</h3>
      <p><strong>Phone Number:</strong> {{ $user->phone ?? 'Not set' }}</p>
    </div>
  </div>

  <!-- Personal Information -->
  <div class="section">
    <h3>Personal Information</h3>
    <div class="info-list">
      <p><strong>Full Name:</strong> {{ $user->name }}</p>
      <p><strong>Gender:</strong> {{ $user->sex ?? 'Not set' }}</p>
      <p><strong>Date of Birth:</strong> {{ $user->dob ?? 'Not set' }}</p>
    </div>
  </div>

  <!-- Contact Information -->
  <div class="section">
    <h3>Contact Information</h3>
    <div class="info-list">
      <p><strong>Email:</strong> {{ $user->email }}</p>
      <p><strong>Phone:</strong> {{ $user->phone ?? 'Not set' }}</p>
    </div>
  </div>

  <!-- Address Information -->
  <div class="section">
    <h3>Address Information</h3>
    <div class="info-list">
      <p><strong>Default Address:</strong> {{ $user->default_address ?? 'Not set' }}</p>
      <p><strong>Billing Address:</strong> {{ $user->billing_address ?? 'Not set' }}</p>
    </div>
  </div>

  <!-- Account Settings -->
  <div class="section">
    <h3>Account Settings</h3>
    <div class="info-list">
      <p><strong>Password:</strong> ******** 
        <a href="{{ route('password.request') }}" class="btn">Change</a>
      </p>
    </div>
  </div>

  <!-- Edit Profile Form -->
  <div class="section" style="text-align: center; ">
    <a href="{{ route('profile.edit') }}" class="btn" style="text-decoration:none;">Edit Profile</a>
  </div> 

</div>

</body>
</html>

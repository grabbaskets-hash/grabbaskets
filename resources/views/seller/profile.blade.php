<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Seller Profile</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body {
      background-color: #f8f9fa;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /* === SIDEBAR === */
    .sidebar {
      position: fixed;
      top: 0;
      bottom: 0;
      left: 0;
      width: 260px;
      background: #1a1a1a;
      color: #fff;
      transition: all 0.3s ease;
      z-index: 1000;
      height: 100vh;
      overflow-y: auto;
      /* ✅ Scroll inside sidebar */
      overflow-x: hidden;
      box-shadow: 2px 0 8px rgba(0, 0, 0, 0.2);
    }

    /* === SIDEBAR LOGO BOX === */
    /* === SIDEBAR LOGO BOX === */
    .sidebar-logo-box {
      display: flex;
      align-items: center;
      gap: 8px;
      padding: 4px 10px;
      background: #003366;
      border-radius: 6px;
      width: 100%;
      box-sizing: border-box;
    }

    .sidebar-logo-img {
      width: 150px;
      height: 200px;
      object-fit: cover;
      margin-top: -3px;
      /* Pull up slightly to counter height increase */
      margin-bottom: -3px;
    }

    .sidebar-logo-text {
      color: #fff;
      font-size: 0.85rem;
      line-height: 1.1;
      text-align: left;
      margin: 0;
      padding: 0;
    }

    .sidebar-logo-text strong {
      font-size: 0.95rem;
      display: block;
      font-weight: 600;
    }

    .sidebar-logo-text small {
      opacity: 0.8;
      font-size: 0.7rem;
      font-weight: 400;
    }

    /* Fixed Header */
    .sidebar-header {
      position: sticky;
      top: 0;
      padding: 12px 20px;
      z-index: 1001;
      /* Must be higher than other sidebar content */
      background: #1a1a1a;
      /* Match sidebar background to avoid "ghosting" */
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
      display: flex;
      justify-content: space-between;
      align-items: center;
      height: 100px;
      /* Adjusted height for better fit */
    }

    .sidebar-header .logoimg {
      width: 130px;
      height: auto;
      filter: brightness(0.9);
    }

    .sidebar-header .notification-bell {
      font-size: 1.2rem;
      color: #adb5bd;
      cursor: pointer;
      padding: 4px 8px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.05);
      transition: background 0.2s;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 32px;
      width: 32px;
    }

    .sidebar-header .notification-bell:hover {
      background: rgba(255, 255, 255, 0.1);
      color: #fff;
    }

    /* Scrollable Content */
    .sidebar-content {
      padding: 0;
      padding-bottom: 60px;
      margin-top: 30px;
    }

    .sidebar-content::-webkit-scrollbar {
      width: 6px;
    }


    /* === NOTIFICATION BELL DROPDOWN FIX === */
    .sidebar-header .notification-bell {
      position: relative;
      /* Ensure it's a positioning context for its children */
    }

    /* Target the notification dropdown (assuming it's a direct child or descendant of the bell) */
    .sidebar-header .notification-bell~.dropdown-menu,
    .sidebar-header .notification-bell+.dropdown-menu,
    .sidebar-header .notification-bell .dropdown-menu {
      position: absolute;
      top: 100%;
      /* Position below the bell */
      left: 50%;
      /* Start from the center of the bell */
      transform: translateX(-50%);
      /* Center it horizontally */
      z-index: 1002;
      /* Higher than the sidebar (z-index: 1000) */
      background: #fff;
      border: 1px solid rgba(0, 0, 0, 0.15);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
      border-radius: 8px;
      min-width: 280px;
      max-width: 320px;
      padding: 1rem;
      margin-top: 0.5rem;
    }

    /* Optional: If the dropdown needs to appear on the right side specifically */
    .sidebar-header .notification-bell .dropdown-menu {
      left: auto;
      /* Override the centering */
      right: -10px;
      /* Position slightly to the right of the bell */
      transform: none;
      /* Remove centering transform */
    }

    /* Ensure the dropdown doesn't get clipped by the sidebar */
    .sidebar-header .notification-bell .dropdown-menu {
      /* This is the key: use 'fixed' positioning to escape the sidebar's bounds */
      position: fixed;
      top: calc(100% + 0px);
      /* Position below the header with a small gap */
      left: calc(100vw - 350px);
      /* Position near the right edge of the viewport */
      width: 320px;
      z-index: 1002;
      background: #fff;
      border: 1px solid rgba(0, 0, 0, 0.15);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
      border-radius: 8px;
      padding: 1rem;
    }

    /* === NOTIFICATION BELL DROPDOWN FIX === */
    /* === NOTIFICATION BELL DROPDOWN FIX === */
    .dropdown-menu {
      position: fixed !important;
      top: 0 !important;
      /* Fixed at the very top of the screen */
      right: 20px !important;
      /* Position near the right edge */
      z-index: 1002 !important;
      /* Ensure it's above the sidebar */
      width: 320px !important;
      background: #fff !important;
      border: 1px solid rgba(0, 0, 0, 0.15) !important;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
      border-radius: 8px !important;
      padding: 1rem !important;
    }

    /* Optional: Adjust the arrow if needed */
    .dropdown-menu::before {
      display: none !important;
    }

    .sidebar-content::-webkit-scrollbar-track {
      background: #2d2d2d;
      border-radius: 10px;
    }

    .sidebar-content::-webkit-scrollbar-thumb {
      background: #555;
      border-radius: 10px;
    }

    .sidebar-content::-webkit-scrollbar-thumb:hover {
      background: #777;
    }

    /* Nav Links */
    .sidebar .nav-link {
      color: #adb5bd;
      margin: 6px 15px;
      border-radius: 6px;
      padding: 10px 15px;
      transition: all 0.2s ease;
    }

    .sidebar .nav-link:hover,
    .sidebar .nav-link.active {
      background: linear-gradient(90deg, #0d6efd, #6610f2);
      color: white;
      box-shadow: 0 4px 12px rgba(0, 123, 255, 0.2);
    }

    .sidebar .nav-link i {
      margin-right: 10px;
      font-size: 1.1rem;
    }

    /* Logout Highlight */
    .sidebar .nav-link[href="#"] {
      color: #dc3545;
    }

    .sidebar .nav-link[href="#"]:hover {
      background: #dc3545;
      color: white;
    }

    /* === CONTENT AREA === */
    .content {
      margin-left: 240px;
      padding: 20px;
      transition: margin-left 0.3s ease;
      min-height: 100vh;
      /* Ensure full height */
      background: #f8f9fa;
      position: relative;
      z-index: 999;
      /* Ensure content stays above other elements */
    }

    /* === MOBILE TOGGLE === */
    .menu-toggle {
      position: fixed;
      top: 15px;
      left: 15px;
      font-size: 1.8rem;
      cursor: pointer;
      color: #fff;
      z-index: 1101;
      background: #212529;
      padding: 8px;
      border-radius: 50%;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
    }

    .menu-toggle:hover {
      background: #343a40;
    }

    @media (max-width: 768px) {
      .sidebar {
        left: -240px;
        height: 100vh;
        overflow-y: auto;
        z-index: 1001;
        /* Higher than content */
      }

      .sidebar.show {
        left: 0;
      }


      .menu-toggle {
        color: #fff;
        background: #212529;
      }
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

    /* Profile Photo Wrapper (WhatsApp/Instagram Style) */
    .profile-photo-wrapper {
      position: relative;
      display: inline-block;
      margin-top: -45px;
    }

    .profile-photo-wrapper:hover .profile-photo-edit-btn {
      opacity: 1;
    }

    .profile-photo-edit-btn {
      position: absolute;
      bottom: 5px;
      right: 5px;
      width: 32px;
      height: 32px;
      border-radius: 50%;
      background: #0d6efd;
      border: 2px solid white;
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      opacity: 0.9;
      transition: all 0.3s ease;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    }

    .profile-photo-edit-btn:hover {
      background: #0b5ed7;
      transform: scale(1.1);
      opacity: 1;
    }

    .profile-photo-edit-btn i {
      font-size: 14px;
    }

    /* Photo Menu Dropdown */
    .profile-photo-actions {
      position: relative;
    }

    .photo-menu {
      position: absolute;
      bottom: 45px;
      right: 0;
      background: white;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
      padding: 8px;
      min-width: 180px;
      display: none;
      z-index: 1000;
      animation: menuSlideUp 0.2s ease-out;
    }

    .photo-menu.active {
      display: block;
    }

    @keyframes menuSlideUp {
      from {
        transform: translateY(10px);
        opacity: 0;
      }

      to {
        transform: translateY(0);
        opacity: 1;
      }
    }

    .photo-menu button {
      width: 100%;
      padding: 10px 15px;
      border: none;
      background: white;
      text-align: left;
      border-radius: 8px;
      cursor: pointer;
      transition: background 0.2s;
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 14px;
      color: #333;
    }

    .photo-menu button:hover {
      background: #f0f2f5;
    }

    .photo-menu button i {
      font-size: 18px;
      color: #0d6efd;
    }

    /* Avatar Picker Modal */
    .avatar-picker-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
      gap: 15px;
      margin: 20px 0;
      max-height: 400px;
      overflow-y: auto;
    }

    .avatar-option {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      cursor: pointer;
      border: 3px solid transparent;
      transition: all 0.3s;
      object-fit: cover;
      background: #f8f9fa;
    }

    .avatar-option:hover {
      transform: scale(1.1);
      border-color: #0d6efd;
      box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
    }

    .avatar-option.selected {
      border-color: #0d6efd;
      box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.2);
    }

    /* Emoji Picker Modal */
    .emoji-picker-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(60px, 1fr));
      gap: 10px;
      margin: 20px 0;
      max-height: 400px;
      overflow-y: auto;
    }

    .emoji-option {
      width: 60px;
      height: 60px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 32px;
      cursor: pointer;
      border-radius: 12px;
      border: 2px solid transparent;
      transition: all 0.2s;
      background: #f8f9fa;
    }

    .emoji-option:hover {
      transform: scale(1.2);
      background: #e9ecef;
      border-color: #0d6efd;
    }

    .emoji-option.selected {
      background: #e7f1ff;
      border-color: #0d6efd;
    }

    /* Upload Modal Overlay */
    .photo-upload-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.7);
      display: none;
      align-items: center;
      justify-content: center;
      z-index: 9999;
      backdrop-filter: blur(5px);
    }

    .photo-upload-overlay.active {
      display: flex;
    }

    .photo-upload-modal {
      background: white;
      border-radius: 20px;
      padding: 30px;
      max-width: 500px;
      width: 90%;
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
      animation: modalSlideUp 0.3s ease-out;
    }

    @keyframes modalSlideUp {
      from {
        transform: translateY(50px);
        opacity: 0;
      }

      to {
        transform: translateY(0);
        opacity: 1;
      }
    }

    .preview-photo-container {
      width: 200px;
      height: 200px;
      border-radius: 50%;
      overflow: hidden;
      margin: 20px auto;
      border: 3px solid #0d6efd;
      display: flex;
      align-items: center;
      justify-content: center;
      background: #f8f9fa;
    }

    .preview-photo-container img {
      width: 100%;
      height: 100%;
      object-fit: cover;
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
  <!-- Sidebar -->
  <div class="sidebar d-flex flex-column p-0" id="sidebarMenu">
    <div class="sidebar-header">
      <img src="{{ asset('asset/images/grabbasket.png') }}" alt="Logo" class="sidebar-logo-img">
      <x-notification-bell />
    </div>

    <div class="sidebar-content">
      <ul class="nav nav-pills flex-column" style="margin-top: 20px;">
        <li>
          <a class="nav-link" href="{{ route('seller.createProduct') }}">
            <i class="bi bi-plus-circle"></i> Add Product
          </a>
        </li>
        <li>
          <a class="nav-link" href="{{ route('seller.imageLibrary') }}">
            <i class="bi bi-images"></i> Image Library
          </a>
        </li>
        <li>
          <a class="nav-link" href="{{ route('seller.bulkUploadForm') }}">
            <i class="bi bi-cloud-upload"></i> Bulk Upload Excel
          </a>
        </li>
        <li>
          <a class="nav-link" href="{{ route('seller.bulkImageReupload') }}">
            <i class="bi bi-images"></i> Bulk Image Re-upload
          </a>
        </li>
        <li>
          <a class="nav-link" href="{{ route('seller.createCategorySubcategory') }}">
            <i class="bi bi-plus-square"></i> Add Category
          </a>
        </li>
        <li>
          <a class="nav-link " href="{{ route('seller.dashboard') }}">
            <i class="bi bi-speedometer2"></i> Dashboard
          </a>
        </li>
        <li>
          <a class="nav-link " href="{{ route('seller.transactions') }}">
            <i class="bi bi-cart-check"></i> Orders
          </a>
        </li>
        <li>
          <a class="nav-link" href="{{ route('seller.importExport') }}">
            <i class="bi bi-arrow-down-up"></i> Import / Export
          </a>
        </li>
        <li>
          <a class="nav-link" href="{{ route('tracking.form') }}">
            <i class="bi bi-truck"></i> Track Package
          </a>
        </li>
        <li>
          <a class="nav-link" href="{{ route('notifications.index') }}">
            <i class="bi bi-bell"></i> Notifications
          </a>
        </li>
        <li>
          <a class="nav-link active" href="{{ route('seller.profile') }}">
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
      <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
      </form>
    </div>
  </div>

  <!-- Main Content -->
  <div class="container py-5 content">
    <div class="row justify-content-center">
      <div class="col-lg-10">

        {{-- Seller Profile --}}
        <div class="card profile-card shadow mb-4">
          <div class="profile-header">
            <h2>Seller Profile</h2>
          </div>
          <div class="card-body text-center">
            @auth
            @php
            $profilePhoto = Auth::user()->profile_picture
            ? Auth::user()->profile_picture
            : "https://ui-avatars.com/api/?name=" . urlencode($seller->name) . "&background=0d6efd&color=fff";
            @endphp
            @else
            @php
            $profilePhoto = "https://ui-avatars.com/api/?name=" . urlencode($seller->name) . "&background=0d6efd&color=fff";
            @endphp
            @endauth

            <!-- Clickable Profile Photo (WhatsApp/Instagram Style) -->
            <div class="profile-photo-wrapper position-relative d-inline-block">
              <img src="{{ $profilePhoto }}"
                alt="Avatar" class="profile-avatar shadow" id="profileAvatarImg">

              @auth
              @if(Auth::user()->email === $seller->email)
              <!-- Camera overlay button with dropdown menu (only for own profile) -->
              <div class="profile-photo-actions">
                <button type="button" class="profile-photo-edit-btn" onclick="togglePhotoMenu()" title="Change profile photo">
                  <i class="bi bi-camera-fill"></i>
                </button>

                <!-- Dropdown menu for photo options -->
                <div class="photo-menu" id="photoMenu">
                  <button type="button" onclick="document.getElementById('quickProfilePhotoInput').click(); togglePhotoMenu();">
                    <i class="bi bi-camera"></i> Upload Photo
                  </button>
                  <button type="button" onclick="showAvatarPicker(); togglePhotoMenu();">
                    <i class="bi bi-person-circle"></i> Choose Avatar
                  </button>
                  <button type="button" onclick="showEmojiPicker(); togglePhotoMenu();">
                    <i class="bi bi-emoji-smile"></i> Choose Emoji
                  </button>
                </div>
              </div>

              <!-- Hidden file input for quick photo change -->
              <form id="quickPhotoUploadForm" method="POST" action="{{ route('seller.updateProfile') }}" enctype="multipart/form-data" style="display: none;">
                @csrf
                <input type="file" name="profile_photo" id="quickProfilePhotoInput" accept="image/jpeg,image/jpg,image/png,image/gif" onchange="handleQuickPhotoUpload(this)">
              </form>
              @endif
              @endauth
            </div>

            <h4 class="mt-3">{{ $seller->name }}</h4>
            <p class="text-muted">📍 {{ $seller->city }}, {{ $seller->state }}</p>
            <div class="mt-3">
              <p><i class="bi bi-envelope-fill text-primary"></i> <strong>Email:</strong> {{ $seller->email }}</p>
              <p><i class="bi bi-telephone-fill text-success"></i> <strong>Phone:</strong> {{ $seller->phone }}</p>
            </div>
          </div>
        </div>

        {{-- Store Information --}}
        <div class="row g-3 mb-4">
          <div class="col-md-6">
            <div class="info-box"><strong>Store Name:</strong> {{ $seller->store_name ?? 'N/A' }}</div>
          </div>
          <div class="col-md-6">
            <div class="info-box"><strong>GST Number:</strong> {{ $seller->gst_number ?? 'N/A' }}</div>
          </div>
          <div class="col-md-6">
            <div class="info-box"><strong>Store Address:</strong> {{ $seller->store_address ?? 'N/A' }}</div>
          </div>
          <div class="col-md-6">
            <div class="info-box"><strong>Store Contact:</strong> {{ $seller->store_contact ?? 'N/A' }}</div>
          </div>
        </div>

        {{-- Authenticated Seller Options --}}
        @auth
        @if(Auth::user()->email === $seller->email)
        <div class="card shadow-sm mb-4">
          <div class="card-body">
            <a href="{{ route('seller.createProduct') }}" class="btn btn-warning w-100 mb-3 fw-semibold shadow">
              <i class="bi bi-plus-circle"></i> Add Product
            </a>

            <form method="POST" action="{{ route('seller.updateProfile') }}" class="border rounded p-3 bg-light" enctype="multipart/form-data">
              @csrf
              <h5 class="fw-bold mb-3">Update Store Info</h5>

              <!-- Profile Photo Upload -->
              <div class="mb-3">
                <label class="form-label fw-bold">Profile Photo</label>
                @if(Auth::user()->profile_picture)
                <div class="mb-2">
                  <img src="{{ Auth::user()->profile_picture }}" alt="Current Photo"
                    class="img-thumbnail" style="max-width: 150px; max-height: 150px;">
                  <p class="text-muted small mt-1">Current profile photo</p>
                </div>
                @endif
                <input type="file" name="profile_photo" class="form-control" accept="image/jpeg,image/jpg,image/png,image/gif" id="profilePhotoInput">
                <small class="text-muted">Accepted formats: JPEG, JPG, PNG, GIF (Max: 2MB)</small>
                @if($errors->has('profile_photo'))
                <div class="text-danger small mt-1">{{ $errors->first('profile_photo') }}</div>
                @endif
                <!-- Image Preview -->
                <div id="imagePreview" class="mt-2" style="display: none;">
                  <img id="previewImg" src="" alt="Preview" class="img-thumbnail" style="max-width: 150px; max-height: 150px;">
                  <p class="text-muted small mt-1">New photo preview</p>
                </div>
              </div>

              <div class="mb-3">
                <label class="form-label fw-bold">Store Name</label>
                <input type="text" name="store_name" class="form-control"
                  value="{{ old('store_name', $seller->store_name) }}">
              </div>
              <div class="mb-3">
                <label class="form-label fw-bold">GST Number (optional)</label>
                <input type="text" name="gst_number" class="form-control"
                  value="{{ old('gst_number', $seller->gst_number) }}">
              </div>
              <div class="mb-3">
                <label class="form-label fw-bold">Store Address</label>
                <input type="text" name="store_address" class="form-control"
                  value="{{ old('store_address', $seller->store_address) }}">
              </div>
              <div class="mb-3">
                <label class="form-label fw-bold">Store Contact</label>
                <input type="text" name="store_contact" class="form-control"
                  value="{{ old('store_contact', $seller->store_contact) }}">
              </div>
              <button type="submit" class="btn btn-primary w-100 fw-semibold">Update</button>
            </form>
          </div>
        </div>
        @endif
        @endauth

        {{-- Products --}}
        <div class="card shadow">
          <div class="card-body">
            <h4 class="fw-bold text-secondary mb-4">Products</h4>
            @if($products->count())
            <div class="row g-4">
              @foreach($products->sortByDesc('created_at') as $p)
              <div class="col-md-6 col-lg-4">
                <div class="card product-card h-100 shadow-sm">
                  <div class="card-body text-center">
                    @if($p->image || $p->image_data)
                    <img src="{{ $p->image_url }}" class="rounded mb-3 border shadow-sm"
                      style="width:120px; height:120px; object-fit:cover;" alt="{{ $p->name }}">
                    @else
                    <div class="text-muted fs-1">🖼</div>
                    @endif
                    <h6 class="fw-bold text-primary">{{ $p->name }}</h6>
                    <div class="text-muted small mb-2">{{ optional($p->category)->name }} / {{ optional($p->subcategory)->name }}</div>
                    <div class="fw-bold text-success">₹{{ number_format($p->price, 2) }}</div>
                  </div>
                </div>
              </div>
              @endforeach
            </div>
            @else
            <p class="text-center text-muted">No products yet.</p>
            @endif
          </div>
        </div>

      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    // Photo Menu Toggle
    function togglePhotoMenu() {
      const menu = document.getElementById('photoMenu');
      menu.classList.toggle('active');
    }

    // Close menu when clicking outside
    document.addEventListener('click', function(e) {
      const menu = document.getElementById('photoMenu');
      const btn = document.querySelector('.profile-photo-edit-btn');
      if (menu && btn && !menu.contains(e.target) && !btn.contains(e.target)) {
        menu.classList.remove('active');
      }
    });

    // Human Avatar Options (Professional, Diverse, Inclusive)
    const humanAvatars = [
      'https://api.dicebear.com/7.x/avataaars/svg?seed=Felix&backgroundColor=b6e3f4',
      'https://api.dicebear.com/7.x/avataaars/svg?seed=Aneka&backgroundColor=c0aede',
      'https://api.dicebear.com/7.x/avataaars/svg?seed=Mittens&backgroundColor=ffd5dc',
      'https://api.dicebear.com/7.x/avataaars/svg?seed=Leo&backgroundColor=ffdfbf',
      'https://api.dicebear.com/7.x/avataaars/svg?seed=Sophia&backgroundColor=d1d4f9',
      'https://api.dicebear.com/7.x/avataaars/svg?seed=John&backgroundColor=c7ceea',
      'https://api.dicebear.com/7.x/avataaars/svg?seed=Emma&backgroundColor=b6e3f4',
      'https://api.dicebear.com/7.x/avataaars/svg?seed=Oliver&backgroundColor=ffd5dc',
      'https://api.dicebear.com/7.x/avataaars/svg?seed=Ava&backgroundColor=c0aede',
      'https://api.dicebear.com/7.x/avataaars/svg?seed=William&backgroundColor=ffdfbf',
      'https://api.dicebear.com/7.x/avataaars/svg?seed=Isabella&backgroundColor=d1d4f9',
      'https://api.dicebear.com/7.x/avataaars/svg?seed=James&backgroundColor=c7ceea',
      'https://api.dicebear.com/7.x/micah/svg?seed=Alex&backgroundColor=b6e3f4',
      'https://api.dicebear.com/7.x/micah/svg?seed=Sam&backgroundColor=c0aede',
      'https://api.dicebear.com/7.x/micah/svg?seed=Jordan&backgroundColor=ffd5dc',
      'https://api.dicebear.com/7.x/micah/svg?seed=Taylor&backgroundColor=ffdfbf',
      'https://api.dicebear.com/7.x/micah/svg?seed=Morgan&backgroundColor=d1d4f9',
      'https://api.dicebear.com/7.x/micah/svg?seed=Riley&backgroundColor=c7ceea',
      'https://api.dicebear.com/7.x/personas/svg?seed=Charlie&backgroundColor=b6e3f4',
      'https://api.dicebear.com/7.x/personas/svg?seed=Dakota&backgroundColor=c0aede',
      'https://api.dicebear.com/7.x/personas/svg?seed=Skyler&backgroundColor=ffd5dc',
      'https://api.dicebear.com/7.x/personas/svg?seed=Cameron&backgroundColor=ffdfbf',
      'https://api.dicebear.com/7.x/personas/svg?seed=Avery&backgroundColor=d1d4f9',
      'https://api.dicebear.com/7.x/personas/svg?seed=Quinn&backgroundColor=c7ceea'
    ];

    // Store & Business Emoji Options
    const storeEmojis = [
      '🏪', '🏬', '🏭', '🏢', '🏛️', '🏗️', '🏚️', '🏘️',
      '🛍️', '🛒', '🛵', '🚚', '📦', '📮', '🎁', '🎀',
      '💼', '💰', '💳', '💎', '💍', '👔', '👗', '👠',
      '🍔', '🍕', '🍜', '🍰', '☕', '🍷', '🥘', '🍱',
      '📱', '💻', '⌚', '📷', '🎮', '🎸', '🎨', '📚',
      '🌟', '⭐', '✨', '🔥', '💫', '🌈', '🎯', '🎪',
      '🏆', '🥇', '🎖️', '🏅', '🎗️', '🎫', '🎉', '🎊',
      '🌸', '🌺', '🌻', '🌹', '🌷', '🌼', '🍀', '🌿'
    ];

    // Show Avatar Picker
    function showAvatarPicker() {
      const overlay = document.createElement('div');
      overlay.className = 'photo-upload-overlay active';
      overlay.innerHTML = `
      <div class="photo-upload-modal" style="max-width: 600px;">
        <div class="text-center">
          <h5 class="mb-3"><i class="bi bi-person-circle"></i> Choose Your Avatar</h5>
          <p class="text-muted small">Select a professional avatar for your profile</p>
          <div class="avatar-picker-grid">
            ${humanAvatars.map((avatar, index) => `
              <img src="${avatar}" 
                   class="avatar-option" 
                   data-avatar="${avatar}"
                   onclick="selectAvatar(this, '${avatar}')"
                   alt="Avatar ${index + 1}">
            `).join('')}
          </div>
          <div class="d-flex gap-2 justify-content-center mt-3">
            <button type="button" class="btn btn-secondary" onclick="closePhotoModal()">
              <i class="bi bi-x-circle"></i> Cancel
            </button>
            <button type="button" class="btn btn-primary" id="confirmAvatarBtn" disabled onclick="confirmAvatar()">
              <i class="bi bi-check-circle"></i> Use This Avatar
            </button>
          </div>
        </div>
      </div>
    `;
      document.body.appendChild(overlay);

      // Close on overlay click
      overlay.addEventListener('click', function(e) {
        if (e.target === overlay) {
          closePhotoModal();
        }
      });
    }

    let selectedAvatarUrl = '';

    function selectAvatar(element, url) {
      // Remove previous selection
      document.querySelectorAll('.avatar-option').forEach(el => el.classList.remove('selected'));

      // Mark as selected
      element.classList.add('selected');
      selectedAvatarUrl = url;

      // Enable confirm button
      document.getElementById('confirmAvatarBtn').disabled = false;
    }

    function confirmAvatar() {
      if (!selectedAvatarUrl) return;

      // Show loading
      const modal = document.querySelector('.photo-upload-modal');
      modal.innerHTML = `
      <div class="text-center py-4">
        <div class="spinner-border text-primary mb-3" role="status">
          <span class="visually-hidden">Updating...</span>
        </div>
        <h5>Updating your avatar...</h5>
        <p class="text-muted">Please wait</p>
      </div>
    `;

      // Update avatar via AJAX
      fetch('{{ route("seller.updateProfile") }}', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-Requested-With': 'XMLHttpRequest'
          },
          body: JSON.stringify({
            avatar_url: selectedAvatarUrl
          })
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            // Update profile photo
            const cacheBuster = '?t=' + new Date().getTime();
            document.getElementById('profileAvatarImg').src = selectedAvatarUrl + cacheBuster;

            modal.innerHTML = `
          <div class="text-center py-4">
            <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
            <h5 class="mt-3 text-success">Success!</h5>
            <p class="text-muted">Avatar updated successfully</p>
          </div>
        `;

            setTimeout(() => {
              closePhotoModal();
              window.location.reload(true);
            }, 1500);
          } else {
            throw new Error(data.message || 'Update failed');
          }
        })
        .catch(error => {
          console.error('Avatar update error:', error);
          modal.innerHTML = `
        <div class="text-center py-4">
          <i class="bi bi-x-circle-fill text-danger" style="font-size: 4rem;"></i>
          <h5 class="mt-3 text-danger">Update Failed</h5>
          <p class="text-muted">${error.message}</p>
          <button class="btn btn-secondary mt-3" onclick="closePhotoModal()">Close</button>
        </div>
      `;
        });
    }

    // Show Emoji Picker
    function showEmojiPicker() {
      const overlay = document.createElement('div');
      overlay.className = 'photo-upload-overlay active';
      overlay.innerHTML = `
      <div class="photo-upload-modal" style="max-width: 600px;">
        <div class="text-center">
          <h5 class="mb-3"><i class="bi bi-emoji-smile"></i> Choose Your Store Emoji</h5>
          <p class="text-muted small">Pick an emoji that represents your business</p>
          <div class="emoji-picker-grid">
            ${storeEmojis.map(emoji => `
              <div class="emoji-option" 
                   data-emoji="${emoji}"
                   onclick="selectEmoji(this, '${emoji}')">
                ${emoji}
              </div>
            `).join('')}
          </div>
          <div class="d-flex gap-2 justify-content-center mt-3">
            <button type="button" class="btn btn-secondary" onclick="closePhotoModal()">
              <i class="bi bi-x-circle"></i> Cancel
            </button>
            <button type="button" class="btn btn-primary" id="confirmEmojiBtn" disabled onclick="confirmEmoji()">
              <i class="bi bi-check-circle"></i> Use This Emoji
            </button>
          </div>
        </div>
      </div>
    `;
      document.body.appendChild(overlay);

      // Close on overlay click
      overlay.addEventListener('click', function(e) {
        if (e.target === overlay) {
          closePhotoModal();
        }
      });
    }

    let selectedEmoji = '';

    function selectEmoji(element, emoji) {
      // Remove previous selection
      document.querySelectorAll('.emoji-option').forEach(el => el.classList.remove('selected'));

      // Mark as selected
      element.classList.add('selected');
      selectedEmoji = emoji;

      // Enable confirm button
      document.getElementById('confirmEmojiBtn').disabled = false;
    }

    function confirmEmoji() {
      if (!selectedEmoji) return;

      // Generate emoji avatar URL (using a service that renders emoji as image)
      const emojiUrl = `https://emojicdn.elk.sh/${selectedEmoji}?style=apple`;

      // Show loading
      const modal = document.querySelector('.photo-upload-modal');
      modal.innerHTML = `
      <div class="text-center py-4">
        <div class="spinner-border text-primary mb-3" role="status">
          <span class="visually-hidden">Updating...</span>
        </div>
        <h5>Updating your emoji...</h5>
        <p class="text-muted">Please wait</p>
      </div>
    `;

      // Update emoji via AJAX
      fetch('{{ route("seller.updateProfile") }}', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-Requested-With': 'XMLHttpRequest'
          },
          body: JSON.stringify({
            avatar_url: emojiUrl
          })
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            // Update profile photo
            const cacheBuster = '?t=' + new Date().getTime();
            document.getElementById('profileAvatarImg').src = emojiUrl + cacheBuster;

            modal.innerHTML = `
          <div class="text-center py-4">
            <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
            <h5 class="mt-3 text-success">Success!</h5>
            <p class="text-muted">Emoji updated successfully</p>
          </div>
        `;

            setTimeout(() => {
              closePhotoModal();
              window.location.reload(true);
            }, 1500);
          } else {
            throw new Error(data.message || 'Update failed');
          }
        })
        .catch(error => {
          console.error('Emoji update error:', error);
          modal.innerHTML = `
        <div class="text-center py-4">
          <i class="bi bi-x-circle-fill text-danger" style="font-size: 4rem;"></i>
          <h5 class="mt-3 text-danger">Update Failed</h5>
          <p class="text-muted">${error.message}</p>
          <button class="btn btn-secondary mt-3" onclick="closePhotoModal()">Close</button>
        </div>
      `;
        });
    }

    document.addEventListener("DOMContentLoaded", function() {
      const toggleBtn = document.querySelector(".menu-toggle");
      const sidebar = document.getElementById("sidebarMenu");

      toggleBtn.addEventListener("click", function() {
        sidebar.classList.toggle("show");
      });
    });

    // Avatar and Emoji Options
    const avatarOptions = [
      'https://api.dicebear.com/7.x/avataaars/svg?seed=Felix',
      'https://api.dicebear.com/7.x/avataaars/svg?seed=Aneka',
      'https://api.dicebear.com/7.x/avataaars/svg?seed=Sam',
      'https://api.dicebear.com/7.x/avataaars/svg?seed=Luna',
      'https://api.dicebear.com/7.x/avataaars/svg?seed=Jasper',
      'https://api.dicebear.com/7.x/avataaars/svg?seed=Emma',
      'https://api.dicebear.com/7.x/avataaars/svg?seed=Oliver',
      'https://api.dicebear.com/7.x/avataaars/svg?seed=Sophie',
      'https://api.dicebear.com/7.x/avataaars/svg?seed=Lucas',
      'https://api.dicebear.com/7.x/avataaars/svg?seed=Mia',
      'https://api.dicebear.com/7.x/avataaars/svg?seed=Max',
      'https://api.dicebear.com/7.x/avataaars/svg?seed=Lily'
    ];

    const emojiOptions = [
      '🏪', '🛒', '🛍️', '📦', '🎁', '👔', '👗', '🍕', '🍔', '🍰',
      '☕', '🌮', '🎂', '🧁', '🥤', '💼', '🏬', '🏭', '🏢', '📱',
      '💻', '⌚', '👟', '👜', '🎒', '🎨', '📚', '🎵', '🎮', '⚽'
    ];

    // Toggle photo menu dropdown
    function togglePhotoMenu() {
      const menu = document.getElementById('photoMenuDropdown');
      if (menu) {
        menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
      }
    }

    // Close menu when clicking outside
    document.addEventListener('click', function(e) {
      const menu = document.getElementById('photoMenuDropdown');
      const cameraBtn = document.querySelector('.profile-photo-edit-btn');

      if (menu && cameraBtn && !menu.contains(e.target) && !cameraBtn.contains(e.target)) {
        menu.style.display = 'none';
      }
    });

    // Show Avatar Picker Modal
    function showAvatarPicker() {
      document.getElementById('photoMenuDropdown').style.display = 'none';

      const avatarGrid = avatarOptions.map(url =>
        `<img src="${url}" class="avatar-option" onclick="selectAvatar('${url}')" alt="Avatar">`
      ).join('');

      const modal = document.createElement('div');
      modal.className = 'photo-upload-overlay active';
      modal.innerHTML = `
          <div class="photo-upload-modal">
              <h4 class="text-center mb-3">Choose Your Avatar</h4>
              <div class="avatar-grid">
                  ${avatarGrid}
              </div>
              <button class="btn btn-secondary w-100 mt-3" onclick="this.closest('.photo-upload-overlay').remove()">Cancel</button>
          </div>
      `;
      document.body.appendChild(modal);
    }

    // Show Emoji Picker Modal
    function showEmojiPicker() {
      document.getElementById('photoMenuDropdown').style.display = 'none';

      const emojiGrid = emojiOptions.map(emoji =>
        `<div class="emoji-option" onclick="selectEmoji('${emoji}')">${emoji}</div>`
      ).join('');

      const modal = document.createElement('div');
      modal.className = 'photo-upload-overlay active';
      modal.innerHTML = `
          <div class="photo-upload-modal">
              <h4 class="text-center mb-3">Choose Your Store Icon</h4>
              <div class="emoji-grid">
                  ${emojiGrid}
              </div>
              <button class="btn btn-secondary w-100 mt-3" onclick="this.closest('.photo-upload-overlay').remove()">Cancel</button>
          </div>
      `;
      document.body.appendChild(modal);
    }

    // Select Avatar
    function selectAvatar(avatarUrl) {
      console.log('Selecting avatar:', avatarUrl);

      const formData = new FormData();
      formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
      formData.append('avatar_url', avatarUrl);

      // Show loading
      const modal = document.querySelector('.photo-upload-modal');
      if (modal) {
        modal.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div><p class="mt-2">Updating...</p></div>';
      }

      fetch('{{ route("seller.updateProfile") }}', {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
          },
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          console.log('Avatar response:', data);
          if (data.success) {
            // Update image with cache-busting
            const cacheBuster = '?t=' + new Date().getTime();
            document.getElementById('profileAvatarImg').src = data.photo_url + cacheBuster;

            // Show success
            if (modal) {
              modal.innerHTML = `
                      <div class="text-center py-4">
                          <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                          <h5 class="mt-3 text-success">Success!</h5>
                          <p class="text-muted">Avatar updated successfully</p>
                      </div>
                  `;
            }

            // Hard reload after 1.5 seconds
            setTimeout(() => {
              document.querySelector('.photo-upload-overlay').remove();
              window.location.reload(true);
            }, 1500);
          } else {
            throw new Error(data.message || 'Failed to update avatar');
          }
        })
        .catch(error => {
          console.error('Avatar update error:', error);
          if (modal) {
            modal.innerHTML = `
                  <div class="text-center py-4">
                      <i class="bi bi-x-circle-fill text-danger" style="font-size: 4rem;"></i>
                      <h5 class="mt-3 text-danger">Update Failed</h5>
                      <p class="text-muted">${error.message}</p>
                      <button class="btn btn-secondary mt-3" onclick="this.closest('.photo-upload-overlay').remove()">Close</button>
                  </div>
              `;
          }
        });
    }

    // Select Emoji
    function selectEmoji(emoji) {
      console.log('Selecting emoji:', emoji);

      // Generate DiceBear avatar URL from emoji
      const avatarUrl = `https://api.dicebear.com/7.x/shapes/svg?seed=${encodeURIComponent(emoji)}`;

      const formData = new FormData();
      formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
      formData.append('avatar_url', avatarUrl);

      // Show loading
      const modal = document.querySelector('.photo-upload-modal');
      if (modal) {
        modal.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div><p class="mt-2">Updating...</p></div>';
      }

      fetch('{{ route("seller.updateProfile") }}', {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
          },
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          console.log('Emoji response:', data);
          if (data.success) {
            // Update image with cache-busting
            const cacheBuster = '?t=' + new Date().getTime();
            document.getElementById('profileAvatarImg').src = data.photo_url + cacheBuster;

            // Show success
            if (modal) {
              modal.innerHTML = `
                      <div class="text-center py-4">
                          <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                          <h5 class="mt-3 text-success">Success!</h5>
                          <p class="text-muted">Store icon updated successfully</p>
                      </div>
                  `;
            }

            // Hard reload after 1.5 seconds
            setTimeout(() => {
              document.querySelector('.photo-upload-overlay').remove();
              window.location.reload(true);
            }, 1500);
          } else {
            throw new Error(data.message || 'Failed to update store icon');
          }
        })
        .catch(error => {
          console.error('Emoji update error:', error);
          if (modal) {
            modal.innerHTML = `
                  <div class="text-center py-4">
                      <i class="bi bi-x-circle-fill text-danger" style="font-size: 4rem;"></i>
                      <h5 class="mt-3 text-danger">Update Failed</h5>
                      <p class="text-muted">${error.message}</p>
                      <button class="btn btn-secondary mt-3" onclick="this.closest('.photo-upload-overlay').remove()">Close</button>
                  </div>
              `;
          }
        });
    }

    // Quick Photo Upload (WhatsApp/Instagram Style)
    function handleQuickPhotoUpload(input) {
      const file = input.files[0];
      if (!file) return;

      // Validate file size (2MB max)
      if (file.size > 2097152) {
        alert('❌ File size must be less than 2MB');
        input.value = '';
        return;
      }

      // Validate file type
      const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
      if (!validTypes.includes(file.type)) {
        alert('❌ Please select a valid image file (JPEG, JPG, PNG, or GIF)');
        input.value = '';
        return;
      }

      // Show preview in modal
      const reader = new FileReader();
      reader.onload = function(e) {
        showPhotoPreviewModal(e.target.result, file);
      };
      reader.readAsDataURL(file);
    }

    // Show Photo Preview Modal (Instagram Style)
    function showPhotoPreviewModal(imageData, file) {
      // Create modal overlay
      const overlay = document.createElement('div');
      overlay.className = 'photo-upload-overlay active';
      overlay.innerHTML = `
        <div class="photo-upload-modal">
          <div class="text-center">
            <h5 class="mb-3"><i class="bi bi-image"></i> Update Profile Photo</h5>
            <div class="preview-photo-container">
              <img src="${imageData}" alt="Preview">
            </div>
            <p class="text-muted small mb-3">
              <i class="bi bi-info-circle"></i> ${file.name} (${(file.size / 1024).toFixed(2)} KB)
            </p>
            <div class="d-flex gap-2 justify-content-center">
              <button type="button" class="btn btn-secondary" onclick="closePhotoModal()">
                <i class="bi bi-x-circle"></i> Cancel
              </button>
              <button type="button" class="btn btn-primary" onclick="submitQuickPhoto()">
                <i class="bi bi-check-circle"></i> Update Photo
              </button>
            </div>
          </div>
        </div>
      `;
      document.body.appendChild(overlay);

      // Close on overlay click
      overlay.addEventListener('click', function(e) {
        if (e.target === overlay) {
          closePhotoModal();
        }
      });
    }

    // Close photo modal
    function closePhotoModal() {
      const overlay = document.querySelector('.photo-upload-overlay');
      if (overlay) {
        overlay.classList.remove('active');
        setTimeout(() => overlay.remove(), 300);
      }
      // Reset file input
      document.getElementById('quickProfilePhotoInput').value = '';
    }

    // Submit photo via AJAX
    function submitQuickPhoto() {
      const form = document.getElementById('quickPhotoUploadForm');
      const formData = new FormData(form);

      // Show loading state
      const modal = document.querySelector('.photo-upload-modal');
      modal.innerHTML = `
        <div class="text-center py-4">
          <div class="spinner-border text-primary mb-3" role="status">
            <span class="visually-hidden">Uploading...</span>
          </div>
          <h5>Uploading your photo...</h5>
          <p class="text-muted">Please wait</p>
        </div>
      `;

      console.log('Uploading photo...');

      // Submit form
      fetch(form.action, {
          method: 'POST',
          body: formData,
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
          }
        })
        .then(response => {
          console.log('Response status:', response.status);
          return response.json();
        })
        .then(data => {
          console.log('Response data:', data);
          if (data.success) {
            // Update profile photo with cache-busting timestamp
            const cacheBuster = '?t=' + new Date().getTime();
            const newPhotoUrl = data.photo_url + cacheBuster;
            console.log('New photo URL:', newPhotoUrl);
            document.getElementById('profileAvatarImg').src = newPhotoUrl;

            // Show success message
            modal.innerHTML = `
            <div class="text-center py-4">
              <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
              <h5 class="mt-3 text-success">Success!</h5>
              <p class="text-muted">Profile photo updated successfully</p>
            </div>
          `;

            setTimeout(() => {
              closePhotoModal();
              // Force reload to show new photo everywhere (header, sidebar, etc.)
              window.location.reload(true);
            }, 1500);
          } else {
            throw new Error(data.message || 'Upload failed');
          }
        })
        .catch(error => {
          console.error('Upload error:', error);
          modal.innerHTML = `
          <div class="text-center py-4">
            <i class="bi bi-x-circle-fill text-danger" style="font-size: 4rem;"></i>
            <h5 class="mt-3 text-danger">Upload Failed</h5>
            <p class="text-muted">${error.message || 'An error occurred. Please try again.'}</p>
            <button class="btn btn-secondary mt-3" onclick="closePhotoModal()">Close</button>
          </div>
        `;
        });
    }

    // Profile Photo Preview (for form upload)
    const photoInput = document.getElementById('profilePhotoInput');
    const imagePreview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    const profileAvatarImg = document.getElementById('profileAvatarImg');

    if (photoInput) {
      photoInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
          // Check file size (2MB = 2097152 bytes)
          if (file.size > 2097152) {
            alert('File size must be less than 2MB');
            photoInput.value = '';
            imagePreview.style.display = 'none';
            return;
          }

          // Check file type
          const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
          if (!validTypes.includes(file.type)) {
            alert('Please select a valid image file (JPEG, JPG, PNG, or GIF)');
            photoInput.value = '';
            imagePreview.style.display = 'none';
            return;
          }

          // Show preview
          const reader = new FileReader();
          reader.onload = function(e) {
            previewImg.src = e.target.result;
            imagePreview.style.display = 'block';
          };
          reader.readAsDataURL(file);
        } else {
          imagePreview.style.display = 'none';
        }
      });
    }
    });
  </script>

</body>

</html>
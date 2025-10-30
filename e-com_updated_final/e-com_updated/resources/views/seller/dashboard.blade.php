<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Seller Dashboard</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('asset/images/grabbasket.jpg') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
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
            margin-top: 60px;
            /* Prevent logout from sticking to bottom */
        }

        .sidebar-content::-webkit-scrollbar {
            width: 6px;
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

        /* Content area */
        .content {
            margin-left: 240px;
            padding: 20px;
        }

        .dashboard-header {
            background: linear-gradient(90deg, #0d6efd, #6610f2);
            color: white;
            padding: 40px 20px;
            border-radius: 12px;
            margin-bottom: 30px;
            text-align: center;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
        }

        .dashboard-header img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 3px solid white;
            margin-bottom: 10px;
        }

        /* Stat cards */
        .stat-card {
            border-radius: 12px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.08);
            transition: transform 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        /* Orders Table */
        .orders-table {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
            background: white;
        }

        .table thead {
            background: #343a40;
            color: white;
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

        /* Search Bar */
        .search-bar form {
            display: flex;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
            padding: 8px;
        }

        .search-bar input {
            border: none;
            border-radius: 10px;
            box-shadow: none;
            flex-grow: 1;
            padding: 12px 15px;
            font-size: 1rem;
        }

        .search-bar input:focus {
            outline: none;
            box-shadow: none;
        }

        .search-bar button {
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 500;
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

        .nav-pills {
            position: relative;
            bottom: 50px;
        }
    </style>
</head>

<body>
    <!-- Toggle Button (mobile) -->
    <div class="menu-toggle d-md-none">
        <i class="bi bi-list"></i>
    </div>

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
                    <a class="nav-link active" href="{{ route('seller.dashboard') }}">
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
                    <a class="nav-link" href="{{ route('seller.profile') }}">
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

    <!-- Content -->
    <div class="content">
        <!-- Dashboard Header -->
        <div class="dashboard-header">
            @php
            $user = Auth::user();
            $dashboardPhoto = $user && $user->profile_picture
            ? $user->profile_picture
            : asset('asset/images/grabbasket.png');
            @endphp
            <img src="{{ $dashboardPhoto }}" alt="Seller Profile">
            <h2>Welcome, {{ Auth::user()->name ?? 'Seller' }}!</h2>
            <p class="mb-0">Here's an overview of your store performance.</p>
        </div>

        <!-- Stats -->
        <div class="row g-4 mb-5">
            <div class="col-md-3">
                <div class="card stat-card text-center p-3 bg-light">
                    <div class="text-primary fs-2"><i class="bi bi-currency-dollar"></i></div>
                    <h6>Revenue</h6>
                    <p class="display-6 fw-bold">
                        ₹{{ number_format(\App\Models\Order::where('seller_id', Auth::id())->sum('amount'), 2) }}</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card text-center p-3 bg-light">
                    <div class="text-success fs-2"><i class="bi bi-box-seam"></i></div>
                    <h6>Products</h6>
                    <p class="display-6 fw-bold">{{ $products->count() }}</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card text-center p-3 bg-light">
                    <div class="text-warning fs-2"><i class="bi bi-cart-check"></i></div>
                    <h6>Orders</h6>
                    <p class="display-6 fw-bold">{{ \App\Models\Order::where('seller_id', Auth::id())->count() }}</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card text-center p-3 bg-light">
                    <div class="text-warning fs-2"><i class="bi bi-star-fill"></i></div>
                    <h6>Reviews</h6>
                    <p class="display-6 fw-bold">
                        {{ \App\Models\Review::whereIn('product_id', $products->pluck('id'))->count() }}
                    </p>
                </div>
            </div>
        </div>
        <div class="search-bar mb-4 col-md-8 mx-auto">
            <form action="{{ route('seller.dashboard') }}" method="GET">
                <input type="text" name="search" placeholder="Search products, orders, or reviews..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Search</button>
            </form>
        </div>
        <!-- Products Table -->
        <div class="orders-table p-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0"><i class="bi bi-clock-history"></i> Your Products</h4>
                <div class="btn-group" role="group">
                    <a href="{{ route('seller.importExport') }}" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-arrow-down-up"></i> Import/Export
                    </a>
                    <form action="{{ route('seller.products.export.excel') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-success">
                            <i class="bi bi-file-earmark-excel"></i> Quick Export
                        </button>
                    </form>
                </div>
            </div>
            @if(isset($products) && $products->count())
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Unique ID</th>
                            <th>Category</th>
                            <th>Subcategory</th>
                            <th>Price</th>
                            <th>Discount</th>
                            <th>Delivery</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $p)
                        <tr>
                            <td>
                                @if($p->image_url)
                                <a href="{{ route('product.details', $p->id) }}" class="d-inline-block">
                                    <img src="{{ $p->image_url }}"
                                        alt="{{ $p->name }}"
                                        style="height:48px; width:48px; object-fit:cover; border-radius:8px; border:1px solid #eee; cursor:pointer; transition: transform 0.2s;"
                                        onmouseover="this.style.transform='scale(1.1)'"
                                        onmouseout="this.style.transform='scale(1)'"
                                        onerror="this.onerror=null; if(this.src.includes('githubusercontent.com')) { const path = this.src.split('/storage/app/public/')[1]; this.src = '{{ url('/serve-image/') }}/' + path.split('/')[0] + '/' + path.split('/').slice(1).join('/'); }">
                                </a>
                                @endif
                                @if($p->image)
                                <div class="mt-1 small text-secondary">Legacy: <span style="word-break:break-all">{{ $p->image }}</span></div>
                                @endif
                            </td>
                            <td><a href="{{ route('product.details', $p->id) }}" class="text-decoration-none text-dark">{{ $p->name }}</a></td>
                            <td><span class="badge bg-secondary">{{ $p->unique_id ?? '-' }}</span></td>
                            <td>{{ optional($p->category)->name ?? '-' }}</td>
                            <td>{{ optional($p->subcategory)->name ?? '-' }}</td>
                            <td>₹{{ number_format($p->price, 2) }}</td>
                            <td>{{ $p->discount ? $p->discount . '%' : '-' }}</td>
                            <td>{{ $p->delivery_charge ? '₹' . number_format($p->delivery_charge, 2) : 'Free' }}</td>
                            <td>{{ $p->created_at?->format('d M Y') }}</td>
                            <td class="d-flex gap-2">
                                <a href="{{ route('seller.editProduct', $p) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                <a href="{{ route('seller.productGallery', $p) }}" class="btn btn-sm btn-outline-info">
                                    Gallery
                                    @if($p->productImages->count() > 0)
                                    <span class="badge bg-info">{{ $p->productImages->count() }}</span>
                                    @endif
                                </a>
                                <form action="{{ route('seller.destroyProduct', $p) }}" method="POST" onsubmit="return confirm('Delete this product? This will remove its images too.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <p class="mb-0">You haven't added any products yet.</p>
            @endif
        </div>

        <!-- Update Product Images by ZIP -->
        <div class="container mt-4">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <i class="bi bi-images me-2"></i>Update Product Images by ZIP
                </div>
                <div class="card-body">
                    <form action="{{ route('seller.updateImagesByZip') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="images_zip" class="form-label fw-bold">Upload ZIP File (image filenames must match
                                product unique IDs)</label>
                            <input type="file" class="form-control" id="images_zip" name="images_zip" accept=".zip"
                                required>
                            <div class="form-text">Each image filename (without extension) must match a product's unique
                                ID. Example: <code>PROD-123.jpg</code> will update the image for product with unique ID
                                <code>PROD-123</code>.
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-upload me-1"></i>Upload & Update Images
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JS for toggle -->
    <script>
        const toggleBtn = document.querySelector('.menu-toggle');
        const sidebar = document.getElementById('sidebarMenu');

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('show');
        });

        // Tamil Voice Greeting Function
        function playTamilGreeting(userName) {
            if ('speechSynthesis' in window) {
                // Tamil greeting message
                const tamilMessage = `வணக்கம் ${userName}! கிராப்பாஸ்கெட்டுக்கு தங்களை அன்புடன் வரவேற்கிறோம்!`;

                const utterance = new SpeechSynthesisUtterance(tamilMessage);

                // Try to find Tamil voice
                const voices = speechSynthesis.getVoices();
                const tamilVoice = voices.find(voice =>
                    voice.lang.includes('ta') ||
                    voice.lang.includes('hi') ||
                    voice.name.toLowerCase().includes('tamil')
                );

                if (tamilVoice) {
                    utterance.voice = tamilVoice;
                } else {
                    // Fallback to any available voice
                    utterance.voice = voices[0] || null;
                }

                utterance.rate = 0.8;
                utterance.pitch = 1.1;
                utterance.volume = 0.7;

                // Add visual feedback with enhanced Tamil styling
                const notification = document.createElement('div');
                notification.innerHTML = `
                    <div class="alert alert-success d-flex align-items-center tamil-greeting-notification" style="
                      position: fixed; 
                      top: 20px; 
                      right: 20px; 
                      z-index: 9999; 
                      border-radius: 15px; 
                      box-shadow: 0 8px 32px rgba(0,123,255,0.3);
                      background: linear-gradient(135deg, #28a745, #20c997);
                      border: 2px solid #ffd700;
                      min-width: 300px;
                      animation: tamilSlideIn 0.8s ease-out;
                    ">
                      <div class="d-flex align-items-center">
                        <i class="bi bi-volume-up-fill me-2" style="font-size: 1.5rem; color: #ffd700;"></i>
                        <div>
                          <div style="color: white; font-weight: bold; font-size: 1.1rem;">
                            🔊 வணக்கம் ${userName}! 🎉
                          </div>
                          <div style="color: #f8f9fa; font-size: 0.9rem; margin-top: 2px;">
                            கிராப்பாஸ்கெட்டுக்கு வரவேற்கிறோம்!
                          </div>
                        </div>
                      </div>
                    </div>
                `;

                document.body.appendChild(notification);

                // Remove notification after 5 seconds with fade out
                setTimeout(() => {
                    notification.style.animation = 'tamilFadeOut 0.5s ease-in forwards';
                    setTimeout(() => notification.remove(), 500);
                }, 5000);

                // Play the speech
                speechSynthesis.speak(utterance);
            }
        }

        // Add Tamil greeting animations CSS
        if (!document.querySelector('#tamilAnimations')) {
            const style = document.createElement('style');
            style.id = 'tamilAnimations';
            style.textContent = `
              @keyframes tamilSlideIn {
                0% {
                  opacity: 0;
                  transform: translateX(100%) scale(0.8);
                }
                50% {
                  transform: translateX(-10px) scale(1.05);
                }
                100% {
                  opacity: 1;
                  transform: translateX(0) scale(1);
                }
              }
              
              @keyframes tamilFadeOut {
                0% {
                  opacity: 1;
                  transform: scale(1);
                }
                100% {
                  opacity: 0;
                  transform: scale(0.9) translateX(50px);
                }
              }
              
              .tamil-greeting-notification:hover {
                transform: scale(1.02);
                transition: transform 0.2s ease;
              }
            `;
            document.head.appendChild(style);
        }
    </script>

    @if(session('tamil_greeting') && auth()->check())
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Wait for voices to be loaded
            if (speechSynthesis.getVoices().length === 0) {
                speechSynthesis.addEventListener('voiceschanged', function() {
                    setTimeout(() => {
                        playTamilGreeting('{{ auth()->user()->name }}');
                    }, 1000);
                });
            } else {
                setTimeout(() => {
                    playTamilGreeting('{{ auth()->user()->name }}');
                }, 1000);
            }
        });
    </script>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
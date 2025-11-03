<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ strlen($searchQuery ?? '') ? 'Search: ' . e($searchQuery) : 'Products' }} - GrabBaskets</title>
  <link rel="icon" type="image/jpeg" href="{{ asset('asset/images/grabbaskets.jpg') }}">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    :root {
      --primary-color: #0C831F;
      --primary-hover: #0A6B19;
      --secondary-color: #F8CB46;
      --text-dark: #1C1C1C;
      --text-light: #666;
      --bg-light: #F7F7F7;
      --bg-white: #FFFFFF;
      --border-color: #E5E5E5;
    }

    body {
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', sans-serif;
      background-color: var(--bg-light);
      color: var(--text-dark);
      line-height: 1.6;
    }

    .search-header {
      background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
      color: white;
      padding: 20px 0;
      margin-bottom: 20px;
    }

    .category-sidebar {
      background: var(--bg-white);
      border-radius: 12px;
      box-shadow: 0 2px 12px rgba(0,0,0,0.08);
      max-height: 80vh;
      overflow-y: auto;
      position: sticky;
      top: 100px;
    }

    .category-link {
      transition: all 0.2s ease;
      border-radius: 8px;
      margin-bottom: 4px;
    }

    .category-link:hover {
      background-color: #f8f9fa;
      transform: translateX(4px);
    }

    .category-link.active {
      background-color: var(--primary-color);
      color: white;
    }

    .mobile-category-scroll {
      overflow-x: auto;
      -webkit-overflow-scrolling: touch;
      scrollbar-width: none;
      -ms-overflow-style: none;
    }

    .mobile-category-scroll::-webkit-scrollbar {
      display: none;
    }

    .product-card {
      border: none;
      border-radius: 12px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.08);
      transition: all 0.3s ease;
      overflow: hidden;
      background: var(--bg-white);
    }

    .product-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    .product-image {
      width: 100%;
      height: 180px;
      object-fit: cover;
      transition: transform 0.3s ease;
    }

    .product-card:hover .product-image {
      transform: scale(1.05);
    }

    .btn-cart {
      background: var(--primary-color);
      border: none;
      border-radius: 20px;
      font-weight: 600;
      transition: all 0.2s ease;
    }

    .btn-cart:hover {
      background: var(--primary-hover);
      transform: scale(1.05);
    }

    .subcategory-bar {
      background: var(--bg-white);
      border-radius: 8px;
      padding: 12px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.06);
      margin-bottom: 20px;
    }

    .back-home {
      position: fixed;
      top: 20px;
      left: 20px;
      z-index: 1050;
      background: var(--bg-white);
      border: none;
      border-radius: 50px;
      padding: 8px 16px;
      box-shadow: 0 2px 12px rgba(0,0,0,0.15);
      text-decoration: none;
      color: var(--text-dark);
      font-weight: 600;
      transition: all 0.2s ease;
    }

    .back-home:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 20px rgba(0,0,0,0.2);
      color: var(--primary-color);
    }
  </style>
</head>

<body>
  <a href="{{ route('home') }}" class="back-home">
    <i class="bi bi-arrow-left"></i> Home
  </a>

  <div class="search-header">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-md-8">
          <h3 class="mb-1"><i class="bi bi-search me-2"></i>{{ strlen($searchQuery ?? '') ? 'Search Results' : 'All Products' }}</h3>
          @if(strlen($searchQuery ?? ''))
            <p class="mb-0 opacity-90">Results for "<strong>{{ e($searchQuery) }}</strong>"</p>
          @endif
        </div>
        <div class="col-md-4 text-md-end">
          @if(isset($totalResults))
            <span class="badge bg-light text-dark fs-6">{{ number_format($totalResults) }} products</span>
          @endif
        </div>
      </div>
    </div>
  </div>

  <div class="container pb-5">

    <div class="row">
        <!-- Sidebar categories (desktop) -->
        <aside class="col-md-3 d-none d-md-block">
            <div class="card sticky-top" style="top:80px;">
                <div class="card-body p-2">
                    <h6 class="fw-bold">Categories</h6>
                    <div class="category-list" style="max-height:70vh;overflow:auto;">
                        @foreach($categories as $cat)
                            <a href="{{ route('products.index', array_merge(request()->query(), ['category_id' => $cat->id])) }}" class="d-flex align-items-center p-2 rounded mb-1 text-decoration-none {{ request()->input('category_id') == $cat->id ? 'bg-light border' : '' }}">
                                <span class="me-2">{!! $cat->emoji ?? '📦' !!}</span>
                                <div>
                                    <div class="small fw-semibold">{{ $cat->name }}</div>
                                    <div class="small text-muted">{{ $cat->subcategories->count() }} sub</div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </aside>

        <main class="col-12 col-md-9">
            <!-- Mobile horizontal categories -->
            <div class="d-block d-md-none mb-3">
                <div style="overflow:auto; white-space:nowrap; -webkit-overflow-scrolling:touch;">
                    @foreach($categories as $cat)
                        <a href="{{ route('products.index', array_merge(request()->query(), ['category_id' => $cat->id])) }}" class="btn btn-sm me-2 mb-1 {{ request()->input('category_id') == $cat->id ? 'btn-primary' : 'btn-outline-secondary' }}" style="min-width:110px;">
                            {!! $cat->emoji ?? '📦' !!} {{ Str::limit($cat->name, 14) }}
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Subcategories bar (shown when a category is selected) --}}
            @php $selectedCategoryId = request()->input('category_id'); @endphp
            @if($selectedCategoryId)
                @php $selected = $categories->firstWhere('id', $selectedCategoryId); @endphp
                @if($selected && $selected->subcategories->count())
                    <div class="mb-3">
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="{{ route('products.index', request()->except('subcategory_id') ) }}" class="btn btn-sm btn-outline-dark">All</a>
                            @foreach($selected->subcategories as $sub)
                                <a href="{{ route('products.index', array_merge(request()->query(), ['subcategory_id' => $sub->id])) }}" class="btn btn-sm btn-outline-secondary">{!! $sub->emoji ?? '📦' !!} {{ Str::limit($sub->name, 20) }}</a>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endif

            <!-- Products grid -->
            <div class="row">
                @forelse($products as $product)
                    @php
                        $image = $product->image_url ?? $product->image ?? '/images/placeholder.png';
                        $discount = $product->discount ? round($product->discount) : 0;
                    @endphp
                    <div class="col-6 col-md-4 col-lg-3 mb-4">
                        <div class="card h-100 shadow-sm">
                            <a href="{{ route('product.details', $product->id) }}" class="text-decoration-none text-dark">
                                <div class="position-relative">
                                    @if($discount > 0)
                                        <span class="badge bg-danger position-absolute top-0 start-0 m-2">-{{ $discount }}%</span>
                                    @endif
                                    <img src="{{ $image }}" onerror="this.src='/images/placeholder.png'" class="card-img-top" style="height:160px;object-fit:cover;">
                                </div>
                                <div class="card-body p-2 d-flex flex-column">
                                    <h6 class="mb-1 small fw-bold">{{ Str::limit($product->name, 60) }}</h6>
                                    <p class="mb-2 small text-muted">{{ Str::limit($product->description ?? '', 60) }}</p>
                                    <div class="mt-auto d-flex justify-content-between align-items-center">
                                        <div class="fw-bold">₹{{ number_format($product->price,2) }}</div>
                                        @if(auth()->check())
                                            <button class="btn btn-cart btn-sm text-white" data-product-id="{{ $product->id }}"><i class="bi bi-cart-plus"></i></button>
                                        @else
                                            <a href="{{ route('login') }}" class="btn btn-primary btn-sm">Login</a>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info">No products found.</div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">{{ $products->links() }}</div>
        </main>
    </div>
</div>

  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Add to cart functionality with proper event listeners
    document.addEventListener('DOMContentLoaded', function() {
        // Add event listeners to all cart buttons
        document.querySelectorAll('[data-product-id]').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const productId = this.getAttribute('data-product-id');
                addToCart(productId);
            });
        });
    });

    async function addToCart(productId) {
        try {
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const button = document.querySelector(`[data-product-id="${productId}"]`);
            
            // Show loading state
            const originalHTML = button.innerHTML;
            button.innerHTML = '<i class="bi bi-hourglass-split"></i>';
            button.disabled = true;
            
            const res = await fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ 
                    product_id: productId, 
                    quantity: 1,
                    delivery_type: 'standard'
                })
            });
            
            const data = await res.json();
            
            if (res.ok) {
                // Success feedback
                button.innerHTML = '<i class="bi bi-check-circle"></i>';
                button.classList.remove('btn-cart');
                button.classList.add('btn-success');
                
                // Show success message
                showMessage('✅ Added to cart successfully!', 'success');
                
                // Reset button after 2 seconds
                setTimeout(() => {
                    button.innerHTML = originalHTML;
                    button.classList.remove('btn-success');
                    button.classList.add('btn-cart');
                    button.disabled = false;
                }, 2000);
            } else {
                // Error feedback
                button.innerHTML = originalHTML;
                button.disabled = false;
                showMessage('❌ ' + (data.message || 'Failed to add to cart'), 'error');
            }
        } catch (err) {
            console.error('Cart error:', err);
            showMessage('❌ Network error. Please try again.', 'error');
            
            // Reset button
            const button = document.querySelector(`[data-product-id="${productId}"]`);
            if (button) {
                button.innerHTML = '<i class="bi bi-cart-plus"></i>';
                button.disabled = false;
            }
        }
    }

    function showMessage(message, type) {
        // Remove existing alerts
        const existingAlert = document.querySelector('.alert-custom');
        if (existingAlert) {
            existingAlert.remove();
        }
        
        // Create new alert
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert ${alertClass} alert-dismissible fade show alert-custom position-fixed`;
        alertDiv.style.cssText = 'top: 80px; right: 20px; z-index: 1060; min-width: 300px; max-width: 400px;';
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(alertDiv);
        
        // Auto remove after 4 seconds
        setTimeout(() => {
            if (alertDiv) {
                alertDiv.remove();
            }
        }, 4000);
    }
  </script>
</body>
</html>

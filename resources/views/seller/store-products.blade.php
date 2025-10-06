{{-- resources/views/store/products.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $seller->store_name ?? $seller->name }} - Products</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f5f7fa, #eaeef5);
        }

        /* Navbar Styling */
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
        }

        .store-header {
            background: linear-gradient(90deg, #0a1a3f, #2e3d74);
            color: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            text-align: center;
            margin-bottom: 2rem;
        }

        .store-header h2 {
            font-weight: 700;
            font-size: 2rem;
        }

        .product-card {
            border-radius: 15px;
            overflow: hidden;
            border: none;
            background: #fff;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.08);
        }

        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.15);
        }

        .product-img {
            max-height: 250px;
            object-fit: cover;
            border-bottom: 1px solid #f1f1f1;
        }

        .product-card .card-body {
            text-align: center;
            padding: 1.2rem;
        }

        .product-title {
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
            color: #0a1a3f;
        }

        .product-price {
            font-size: 1.2rem;
            font-weight: 700;
            color: #1a237e;
            margin-bottom: 0.8rem;
        }

        .btn-view {
            background: linear-gradient(45deg, #0a1a3f, #3949ab);
            border: none;
            color: white;
            border-radius: 30px;
            padding: 0.5rem 1.5rem;
            font-weight: 600;
            transition: 0.3s;
        }

        .btn-view:hover {
            background: linear-gradient(45deg, #3949ab, #0a1a3f);
            color: #fff;
        }

        /* Pagination Custom */
        .pagination .page-link {
            border-radius: 50px;
            margin: 0 4px;
            border: none;
            color: #0a1a3f;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        }

        .pagination .page-item.active .page-link {
            background: linear-gradient(45deg, #0a1a3f, #3949ab);
            color: white;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .pagination .page-link:hover {
            background: #f1f3f9;
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="bi bi-shop"></i> {{ $seller->store_name ?? $seller->name }}
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('cart.index') }}">
                            <i class="bi bi-cart"></i> Cart
                        </a>
                    </li>
                   
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container py-5">

        <!-- Store Header -->
        <div class="store-header">
            <h2><i class="bi bi-shop"></i> {{ $seller->store_name ?? $seller->name }}</h2>
            <p class="mb-0">Browse premium products curated for you</p>
        </div>

        <!-- Product List -->
        @if($products->count())
            <div class="row g-4">
                @foreach($products as $p)
                    <div class="col-sm-6 col-lg-4">
                        <div class="card product-card h-100">
                            @if($p->image)
                                <img src="{{ asset('storage/'.$p->image) }}" alt="{{ $p->name }}" class="card-img-top product-img">
                            @endif
                            <div class="card-body">
                                <h5 class="product-title">{{ $p->name }}</h5>
                                <div class="product-price">₹{{ number_format($p->price,2) }}</div>
                                <a href="{{ route('product.details', $p->id) }}" class="btn btn-view">
                                    <i class="bi bi-cart-fill"></i> Buy Now
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-5">
                {{ $products->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <h4 class="text-muted">No products found for this store.</h4>
            </div>
        @endif

    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

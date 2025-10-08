<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GrabBaskets - Test Index</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-12 text-center">
                <h1>🛒 GrabBaskets</h1>
                <p class="lead">E-commerce Platform</p>
                <hr>
                
                @if(isset($database_error))
                    <div class="alert alert-warning">
                        <strong>Database Issue:</strong> {{ $database_error }}
                    </div>
                @endif
                
                <div class="row">
                    <div class="col-md-6">
                        <h3>Categories ({{ $categories->count() }})</h3>
                        @if($categories->count() > 0)
                            <ul class="list-group">
                                @foreach($categories->take(5) as $category)
                                    <li class="list-group-item">{{ $category->name }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted">No categories found</p>
                        @endif
                    </div>
                    
                    <div class="col-md-6">
                        <h3>Products</h3>
                        @if($products->count() > 0)
                            <p class="text-success">{{ $products->total() }} products available</p>
                            <div class="row">
                                @foreach($products->take(3) as $product)
                                    <div class="col-12 mb-2">
                                        <div class="card">
                                            <div class="card-body p-2">
                                                <h6 class="card-title">{{ $product->name }}</h6>
                                                <p class="card-text small">₹{{ $product->price }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted">No products found</p>
                        @endif
                    </div>
                </div>
                
                <hr class="my-4">
                <div class="d-flex justify-content-center gap-3">
                    <a href="/health" class="btn btn-outline-info">Health Check</a>
                    <a href="/login" class="btn btn-outline-primary">Login</a>
                    <a href="/register" class="btn btn-outline-success">Register</a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Simple Chatbot Widget -->
    <x-chatbot-widget />
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
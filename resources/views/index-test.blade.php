<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Index Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Test Index Page Working!</h1>
        <p class="text-center">If you can see this, the basic template works.</p>
        
        <div class="row">
            <div class="col-12">
                <h3>Categories Count: {{ $categories->count() ?? 0 }}</h3>
                <h3>Products Count: {{ $products->total() ?? 0 }}</h3>
                <h3>Trending Count: {{ $trending->count() ?? 0 }}</h3>
            </div>
        </div>
        
        @if($categories->count() > 0)
            <div class="row mt-4">
                <div class="col-12">
                    <h4>Sample Categories:</h4>
                    <ul>
                        @foreach($categories->take(5) as $category)
                            <li>{{ $category->name }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
        
        @if($products->count() > 0)
            <div class="row mt-4">
                <div class="col-12">
                    <h4>Sample Products:</h4>
                    <ul>
                        @foreach($products->take(5) as $product)
                            <li>{{ $product->name }} - ${{ $product->price }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
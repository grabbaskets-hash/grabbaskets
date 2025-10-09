
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product - Grabbaskets</title>
    
    <link rel="icon" type="image/jpeg" href="{{ asset('asset/images/grabbasket.jpg') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
    body {
        background: linear-gradient(135deg, #e0f7fa, #80deea);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 20px;
        margin: 0;
    }
    .container-card {
        display: flex;
        flex-wrap: wrap;
        max-width: 950px;
        width: 100%;
        border-radius: 1rem;
        overflow: hidden;
        box-shadow: 0 20px 50px rgba(0,0,0,0.15);
        background: #fff;
    }
    .left-box {
        flex: 1 1 35%;
        background: linear-gradient(135deg, #3b82f6, #06b6d4);
        color: #fff;
        padding: 50px 25px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
    }
    .left-box img {
        width: 180px;
        border-radius: 1rem;
        margin-bottom: 25px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.3);
    }
    .left-box p {
        font-size: 1.1rem;
        line-height: 1.6;
        font-weight: 500;
    }
    .right-box {
        flex: 1 1 65%;
        background: #f3f4f6;
        padding: 50px 30px;
    }
    input.form-control, select.form-select, textarea.form-control {
        border-radius: 0.8rem;
        border: 1px solid #d1d5db;
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
        margin-bottom: 15px;
        transition: all 0.3s ease;
    }
    input.form-control:focus, select.form-select:focus, textarea.form-control:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 0.2rem rgba(59,130,246,0.25);
        outline: none;
    }
    textarea.form-control { resize: vertical; min-height: 100px; }
    label { font-weight: 600; color: #374151; margin-bottom: 5px; }
    .btn-gradient {
        background: linear-gradient(90deg, #3b82f6, #06b6d4);
        border: none;
        border-radius: 1rem;
        font-weight: 600;
        padding: 12px;
        width: 100%;
        color: #fff;
        transition: all 0.4s ease;
    }
    .btn-gradient:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(59,130,246,0.4);
    }
    .btn-outline-pro {
        border-radius: 1rem;
        border: 2px solid #3b82f6;
        padding: 12px;
        font-weight: 600;
        width: 100%;
        color: #3b82f6;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
        text-align: center;
    }
    .btn-outline-pro:hover {
        background: #3b82f6;
        color: #fff;
        transform: translateY(-2px);
        text-decoration: none;
    }
    .btn-horizontal-group {
        display: flex;
        gap: 15px;
        margin-top: 20px;
    }
    .btn-horizontal-group .btn {
        flex: 1;
    }
    .text-danger { font-size: 0.85rem; margin-top: 3px; }
    @media(max-width: 768px) {
        body { align-items: flex-start; padding: 10px; }
        .container-card { flex-direction: column; margin-top: 20px; }
        .left-box, .right-box { flex: 1 1 100%; padding: 30px 20px; }
        .btn-horizontal-group { flex-direction: column; gap: 15px; }
    }
    </style>
</head>

<body>
<div class="container-card">
    <!-- Left Box -->
    <div class="left-box">
        <img src="{{ asset('asset/images/grabbasket.png') }}" alt="Grabbasket Logo">
        <p>Welcome to <strong>Grabbasket</strong>!<br>
           Edit your product details and keep your inventory up to date.</p>
        
        @if($product->image)
            <div class="mt-4">
                <img src="{{ asset('storage/' . $product->image) }}" 
                     alt="{{ $product->name }}" 
                     style="max-width: 100%; max-height: 220px; border-radius: 1rem; border: 2px solid #fff; box-shadow: 0 4px 16px rgba(0,0,0,0.09); background:#fafafa;"
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                <div style="display: none; padding: 20px; background: rgba(255,255,255,0.1); border-radius: 1rem; margin-top: 10px;">
                    <i class="fas fa-image" style="font-size: 2rem; opacity: 0.5;"></i>
                    <div class="text-white small mt-2">Image not found</div>
                </div>
                <div class="text-white small mt-2">Current Product Image</div>
            </div>
        @else
            <div class="mt-4" style="padding: 20px; background: rgba(255,255,255,0.1); border-radius: 1rem;">
                <i class="fas fa-upload" style="font-size: 2rem; opacity: 0.5;"></i>
                <div class="text-white small mt-2">Upload an image for this product</div>
            </div>
        @endif
    </div>

    <!-- Right Box -->
    <div class="right-box">
        <h2 class="mb-4">Edit Product</h2>
        
        @if(session('success'))
            <div class="alert alert-success text-dark">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger text-dark">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('seller.updateProduct', $product) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row g-3">

                <div class="col-md-6">
                    <label for="name">Product Name</label>
                    <input type="text" id="name" name="name" class="form-control" required value="{{ old('name', $product->name) }}">
                    @error('name') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label for="category_id">Category</label>
                    <select id="category_id" name="category_id" class="form-select" required>
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label for="subcategory_id">Subcategory</label>
                    <select id="subcategory_id" name="subcategory_id" class="form-select" required>
                        <option value="">Select Subcategory</option>
                        @foreach($subcategories as $subcategory)
                            <option value="{{ $subcategory->id }}" data-category-id="{{ $subcategory->category_id }}" 
                                {{ old('subcategory_id', $product->subcategory_id) == $subcategory->id ? 'selected' : '' }}>
                                {{ $subcategory->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('subcategory_id') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label for="price">Price</label>
                    <input type="number" step="0.01" id="price" name="price" class="form-control" required value="{{ old('price', $product->price) }}">
                    @error('price') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label for="discount">Discount (%)</label>
                    <input type="number" step="0.01" id="discount" name="discount" class="form-control" value="{{ old('discount', $product->discount) }}">
                    @error('discount') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label for="delivery_charge">Delivery Charge</label>
                    <input type="number" step="0.01" id="delivery_charge" name="delivery_charge" class="form-control" value="{{ old('delivery_charge', $product->delivery_charge) }}">
                    @error('delivery_charge') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label for="gift_option">Gift Option Available?</label>
                    <select id="gift_option" name="gift_option" class="form-select" required>
                        <option value="">Select</option>
                        <option value="yes" {{ old('gift_option', $product->gift_option) == 'yes' ? 'selected' : '' }}>Yes</option>
                        <option value="no" {{ old('gift_option', $product->gift_option) == 'no' ? 'selected' : '' }}>No</option>
                    </select>
                    @error('gift_option') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label for="stock">Number of Stock Held</label>
                    <input type="number" id="stock" name="stock" class="form-control" min="0" required value="{{ old('stock', $product->stock) }}">
                    @error('stock') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="col-12">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" class="form-control" required>{{ old('description', $product->description) }}</textarea>
                    @error('description') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="col-12">
                    <label for="image">Product Image</label>
                    <input type="file" id="image" name="image" class="form-control" accept="image/*" onchange="previewImage(this)">
                    @error('image') <div class="text-danger">{{ $message }}</div> @enderror
                    <small class="text-muted">Choose a new image to replace the current one. Max size: 2MB</small>
                    
                    <!-- Image Preview -->
                    <div id="imagePreview" style="display: none; margin-top: 10px;">
                        <img id="previewImg" src="" alt="Preview" style="max-width: 150px; max-height: 150px; border-radius: 8px; border: 2px solid #ddd;">
                        <div><small class="text-muted">New image preview</small></div>
                    </div>
                </div>

            </div>

            <!-- Horizontal Buttons -->
            <div class="btn-horizontal-group">
                <button type="submit" class="btn btn-gradient">Update Product</button>
                <a href="{{ route('seller.dashboard') }}" class="btn btn-outline-pro">Back to Dashboard</a>
            </div>

        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const categorySelect = document.getElementById('category_id');
    const subcategorySelect = document.getElementById('subcategory_id');
    
    function filterSubcategories(catId) {
        let hasAny = false;
        for (let i = 0; i < subcategorySelect.options.length; i++) {
            const opt = subcategorySelect.options[i];
            const match = String(opt.dataset.categoryId) === String(catId);
            opt.hidden = !match;
            opt.disabled = !match;
            if (match) hasAny = true;
        }
        subcategorySelect.disabled = !catId || !hasAny;
        if (!catId || !subcategorySelect.selectedOptions[0] || subcategorySelect.selectedOptions[0].disabled) {
            subcategorySelect.selectedIndex = 0;
        }
    }
    
    categorySelect.addEventListener('change', function () {
        filterSubcategories(this.value);
    });
    
    filterSubcategories(categorySelect.value);
});

// Image preview function
function previewImage(input) {
    const preview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.style.display = 'block';
        };
        
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.style.display = 'none';
    }
}
</script>

</body>
</html>

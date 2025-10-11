<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add Product - Grabbaskets</title>

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

/* Left panel */
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

/* Right panel */
.right-box {
    flex: 1 1 65%;
    background: #f3f4f6;
    padding: 50px 30px;
}

/* Inputs */
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

/* Labels */
label { font-weight: 600; color: #374151; margin-bottom: 5px; }

/* Buttons */
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
}
.btn-outline-pro:hover {
    background: #3b82f6;
    color: #fff;
    transform: translateY(-2px);
}

/* Horizontal button group */
.btn-horizontal-group {
    display: flex;
    gap: 15px;
    margin-top: 20px;
}
.btn-horizontal-group .btn {
    flex: 1;
}

/* Error */
.text-danger { font-size: 0.85rem; margin-top: 3px; }

/* Loading spinner */
.spinner-border-sm {
    width: 1rem;
    height: 1rem;
}

/* Required field indicator */
.required::after {
    content: " *";
    color: #dc3545;
}

/* Responsive */
@media(max-width: 768px) {
    .container-card { flex-direction: column; }
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
           Add new products easily and manage your inventory professionally.</p>
    </div>

    <!-- Right Box -->
    <div class="right-box">
        <h2 class="mb-4">Add New Product</h2>

        <!-- Information Banner -->
        <div class="alert alert-info border-0 mb-4" style="background: linear-gradient(90deg, #e7f3ff, #f0f9ff); border-radius: 0.8rem;">
            <div class="d-flex align-items-start">
                <i class="bi bi-info-circle-fill text-primary me-3 mt-1"></i>
                <div>
                    <h6 class="mb-2 text-primary">📝 Product Upload Guidelines</h6>
                    <ul class="mb-0 small text-dark" style="padding-left: 1rem;">
                        <li><strong>Images:</strong> Stored securely in database (cloud-safe, no file system issues)</li>
                        <li><strong>Size/Format:</strong> Max 2MB, JPEG/PNG/JPG/GIF, 400x400px+ recommended</li>
                        <li><strong>Required:</strong> All fields marked with * must be completed</li>
                        <li><strong>Categories:</strong> Select category first, then matching subcategory</li>
                        <li><strong>Success:</strong> You'll see a confirmation message after successful upload</li>
                    </ul>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success text-dark">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger text-dark">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('seller.storeProduct') }}" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">

                <div class="col-md-6">
                    <label for="name" class="required">Product Name</label>
                    <input type="text" id="name" name="name" class="form-control" required value="{{ old('name') }}">
                    @error('name') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label for="category_id" class="required">Category</label>
                    <select id="category_id" name="category_id" class="form-select" required>
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label for="subcategory_id" class="required">Subcategory</label>
                    <select id="subcategory_id" name="subcategory_id" class="form-select" required>
                        <option value="">Select Subcategory</option>
                        @foreach($subcategories as $subcategory)
                            <option value="{{ $subcategory->id }}" data-category-id="{{ $subcategory->category_id }}" 
                                {{ old('subcategory_id') == $subcategory->id ? 'selected' : '' }}>
                                {{ $subcategory->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('subcategory_id') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label for="price" class="required">Price</label>
                    <input type="number" step="0.01" id="price" name="price" class="form-control" required value="{{ old('price') }}">
                    @error('price') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label for="discount">Discount (%)</label>
                    <input type="number" step="0.01" id="discount" name="discount" class="form-control" value="{{ old('discount') }}">
                    @error('discount') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label for="delivery_charge">Delivery Charge</label>
                    <input type="number" step="0.01" id="delivery_charge" name="delivery_charge" class="form-control" value="{{ old('delivery_charge') }}">
                    @error('delivery_charge') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label for="gift_option" class="required">Gift Option Available?</label>
                    <select id="gift_option" name="gift_option" class="form-select" required>
                        <option value="">Select</option>
                        <option value="yes" {{ old('gift_option') == 'yes' ? 'selected' : '' }}>Yes</option>
                        <option value="no" {{ old('gift_option') == 'no' ? 'selected' : '' }}>No</option>
                    </select>
                    @error('gift_option') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="col-12">
                    <label for="description" class="required">Description</label>
                    <textarea id="description" name="description" class="form-control" required>{{ old('description') }}</textarea>
                    @error('description') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label for="stock" class="required">Number of Stock Held</label>
                    <input type="number" id="stock" name="stock" class="form-control" min="0" required value="{{ old('stock') }}">
                    @error('stock') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label for="image">Product Image</label>
                    <input type="file" id="image" name="image" class="form-control" accept="image/jpeg,image/png,image/jpg,image/gif" onchange="previewImage(this)">
                    @error('image') <div class="text-danger">{{ $message }}</div> @enderror
                    <div class="mt-1">
                        <small class="text-muted">
                            <i class="bi bi-info-circle"></i> 
                            Max size: 2MB | Formats: JPEG, PNG, JPG, GIF | <strong>Stored in cloud (AWS)</strong>
                        </small>
                        <br>
                        <small class="text-info">
                            <i class="bi bi-images"></i> 
                            <strong>Tip:</strong> Add more images after creating the product using the Gallery feature!
                        </small>
                    </div>
                    <div id="imagePreview" class="mt-2" style="display: none;">
                        <img id="preview" src="" alt="Image Preview" style="max-width: 200px; max-height: 200px; border-radius: 0.5rem; border: 2px solid #e5e7eb;">
                        <div class="mt-1">
                            <small class="text-success"><i class="bi bi-check-circle"></i> Image ready for cloud storage</small>
                        </div>
                    </div>
                    <div id="uploadError" class="mt-1" style="display: none;">
                        <small class="text-danger"><i class="bi bi-exclamation-triangle"></i> <span id="errorMessage"></span></small>
                    </div>
                </div>

            </div>

            <!-- Horizontal Buttons -->
            <div class="btn-horizontal-group">
                <button type="submit" id="submitBtn" class="btn btn-gradient">
                    <span id="submitText">Add Product</span>
                    <span id="submitSpinner" style="display: none;">
                        <i class="spinner-border spinner-border-sm me-2" role="status"></i>
                        Adding Product...
                    </span>
                </button>
                <a href="/seller/dashboard" class="btn btn-outline-pro">Dashboard</a>
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

function previewImage(input) {
    const preview = document.getElementById('preview');
    const previewContainer = document.getElementById('imagePreview');
    const errorContainer = document.getElementById('uploadError');
    const errorMessage = document.getElementById('errorMessage');
    
    // Hide error message
    errorContainer.style.display = 'none';
    
    if (input.files && input.files[0]) {
        const file = input.files[0];
        
        // Validate file size (2MB = 2097152 bytes)
        if (file.size > 2097152) {
            errorMessage.textContent = 'File size must be less than 2MB. Please choose a smaller image.';
            errorContainer.style.display = 'block';
            previewContainer.style.display = 'none';
            input.value = ''; // Clear the file input
            return;
        }
        
        // Validate file type
        const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
        if (!allowedTypes.includes(file.type)) {
            errorMessage.textContent = 'Please select a valid image file (JPEG, PNG, JPG, or GIF).';
            errorContainer.style.display = 'block';
            previewContainer.style.display = 'none';
            input.value = ''; // Clear the file input
            return;
        }
        
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.src = e.target.result;
            previewContainer.style.display = 'block';
        }
        
        reader.readAsDataURL(file);
    } else {
        previewContainer.style.display = 'none';
    }
}

// Form submission handling
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    const submitSpinner = document.getElementById('submitSpinner');
    
    form.addEventListener('submit', function(e) {
        // Show loading state
        submitBtn.disabled = true;
        submitText.style.display = 'none';
        submitSpinner.style.display = 'inline';
        
        // Validate form before submission
        const requiredFields = ['name', 'category_id', 'subcategory_id', 'description', 'price', 'gift_option', 'stock'];
        let isValid = true;
        
        requiredFields.forEach(function(fieldName) {
            const field = document.querySelector(`[name="${fieldName}"]`);
            if (!field || !field.value.trim()) {
                isValid = false;
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            submitBtn.disabled = false;
            submitText.style.display = 'inline';
            submitSpinner.style.display = 'none';
            alert('Please fill in all required fields before submitting.');
            return;
        }
        
        // Re-enable button after 5 seconds in case of server issues
        setTimeout(function() {
            submitBtn.disabled = false;
            submitText.style.display = 'inline';
            submitSpinner.style.display = 'none';
        }, 5000);
    });
});
</script>

</body>
</html>
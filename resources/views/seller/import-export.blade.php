<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Import / Export Products</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('asset/images/grabbasket.jpg') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar d-flex flex-column p-3" id="sidebarMenu">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <img src="{{ asset('asset/images/grablogo.jpg') }}" alt="Logo" class="logoimg" width="150px">
        </div>
        <ul class="nav nav-pills flex-column" style="margin-top:65px;">
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
                <a class="nav-link" href="{{ route('seller.dashboard') }}">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>
            <li>
                <a class="nav-link" href="{{ route('seller.transactions') }}">
                    <i class="bi bi-cart-check"></i> Orders
                </a>
            </li>
            <li>
                <a class="nav-link active" href="{{ route('seller.importExport') }}">
                    <i class="bi bi-arrow-down-up"></i> Import / Export
                </a>
            </li>
            <li>
                <a class="nav-link" href="{{ route('seller.profile') }}">
                    <i class="bi bi-person-circle"></i> Profile
                </a>
            </li>
            <li>
                <a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </li>
        </ul>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </div>

    <!-- Main Content -->
    <div class="content">
        <div class="container-fluid px-4 py-4">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="bi bi-arrow-down-up me-2"></i>Import / Export Products
                    </h1>
                    <p class="text-muted mb-0">Manage your product listings in bulk</p>
                </div>
                <a href="{{ route('seller.dashboard') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Back to Dashboard
                </a>
            </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (isset($errors) && $errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Please fix the following errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- Export Section -->
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-download me-2"></i>Export Products
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-4">
                        Download your product listings in your preferred format. All your products will be included.
                    </p>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Total Products:</strong> {{ $productsCount }} products
                    </div>

                    <div class="d-grid gap-3">
                        <!-- Export to Excel -->
                        <form action="{{ route('seller.products.export.excel') }}" method="POST" class="export-form">
                            @csrf
                            <button type="submit" class="btn btn-success btn-lg w-100">
                                <i class="fas fa-file-excel me-2"></i>
                                Export to Excel (.xlsx)
                                <small class="d-block mt-1 opacity-75">Best for editing and calculations</small>
                            </button>
                        </form>

                        <!-- Export to CSV -->
                        <form action="{{ route('seller.products.export.csv') }}" method="POST" class="export-form">
                            @csrf
                            <button type="submit" class="btn btn-outline-success btn-lg w-100">
                                <i class="fas fa-file-csv me-2"></i>
                                Export to CSV
                                <small class="d-block mt-1 opacity-75">Universal format, compatible everywhere</small>
                            </button>
                        </form>

                        <!-- Export to PDF -->
                        <form action="{{ route('seller.products.export.pdf') }}" method="POST" class="export-form">
                            @csrf
                            <button type="submit" class="btn btn-outline-success btn-lg w-100">
                                <i class="fas fa-file-pdf me-2"></i>
                                Export to PDF
                                <small class="d-block mt-1 opacity-75">For printing and sharing</small>
                            </button>
                        </form>
                    </div>

                    <div class="mt-4">
                        <h6 class="fw-bold mb-2">Exported Data Includes:</h6>
                        <ul class="small text-muted">
                            <li>Product ID, Name, Description</li>
                            <li>Category & Subcategory</li>
                            <li>Pricing (Price, Original Price, Discount)</li>
                            <li>Stock, SKU, Barcode</li>
                            <li>Product Details (Brand, Model, Color, Size, etc.)</li>
                            <li>SEO Information (Meta Title, Description, Tags)</li>
                            <li>Image URLs</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Import Section -->
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-upload me-2"></i>Import Products
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-4">
                        Upload your product listing file. We support Excel (.xlsx, .xls) and CSV formats.
                    </p>

                    <div class="alert alert-warning">
                        <i class="fas fa-magic me-2"></i>
                        <strong>Smart Header Detection:</strong> Our system automatically detects your column headers, even if they're different from our template!
                    </div>

                    <!-- Download Template -->
                    <div class="mb-4">
                        <a href="{{ route('seller.products.template') }}" class="btn btn-outline-primary w-100">
                            <i class="fas fa-download me-2"></i>
                            Download Sample Template
                            <small class="d-block mt-1 opacity-75">Start with our pre-formatted template</small>
                        </a>
                    </div>

                    <!-- Import Form -->
                    <form action="{{ route('seller.products.import') }}" method="POST" enctype="multipart/form-data" id="importForm">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="importFile" class="form-label fw-bold">
                                <i class="fas fa-file-upload me-2"></i>Choose File to Import
                            </label>
                            <input type="file" 
                                   class="form-control form-control-lg @error('file') is-invalid @enderror" 
                                   id="importFile" 
                                   name="file" 
                                   accept=".xlsx,.xls,.csv"
                                   required>
                            @error('file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Accepted formats: .xlsx, .xls, .csv (Max: 10MB)
                            </small>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg" id="importBtn">
                                <i class="fas fa-cloud-upload-alt me-2"></i>
                                Import Products
                            </button>
                        </div>
                    </form>

                    <!-- Import Instructions -->
                    <div class="mt-4">
                        <h6 class="fw-bold mb-2">How Import Works:</h6>
                        <ol class="small text-muted">
                            <li><strong>Auto-detects headers:</strong> Works with any column names (e.g., "Name", "Product Name", "Title")</li>
                            <li><strong>Updates existing products:</strong> Matches by Product ID or Name</li>
                            <li><strong>Creates new products:</strong> Automatically adds products that don't exist</li>
                            <li><strong>Smart mapping:</strong> Finds categories by name, creates if missing</li>
                        </ol>
                    </div>

                    <div class="mt-3">
                        <h6 class="fw-bold mb-2">Supported Header Variations:</h6>
                        <div class="table-responsive">
                            <table class="table table-sm small text-muted">
                                <tbody>
                                    <tr>
                                        <td><strong>Name:</strong></td>
                                        <td>"Product Name", "Name", "Title", "Item Name"</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Price:</strong></td>
                                        <td>"Price", "Selling Price", "Sale Price", "MRP"</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Stock:</strong></td>
                                        <td>"Stock", "Quantity", "Qty", "Inventory"</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="text-center">...and many more! Try any format.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-4">
                        <i class="fas fa-star me-2 text-warning"></i>Key Features
                    </h5>
                    <div class="row g-4">
                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="feature-icon mb-3">
                                    <i class="fas fa-brain fa-3x text-primary"></i>
                                </div>
                                <h6>Smart Detection</h6>
                                <p class="small text-muted">Automatically recognizes different header formats and column names</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="feature-icon mb-3">
                                    <i class="fas fa-sync-alt fa-3x text-success"></i>
                                </div>
                                <h6>Update or Create</h6>
                                <p class="small text-muted">Updates existing products or creates new ones automatically</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="feature-icon mb-3">
                                    <i class="fas fa-file-alt fa-3x text-info"></i>
                                </div>
                                <h6>Multiple Formats</h6>
                                <p class="small text-muted">Supports Excel, CSV, and PDF for exports</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="feature-icon mb-3">
                                    <i class="fas fa-shield-alt fa-3x text-warning"></i>
                                </div>
                                <h6>Safe & Secure</h6>
                                <p class="small text-muted">Only your products are affected, fully validated data</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
body {
    background-color: #f8f9fa;
}

/* Sidebar */
.sidebar {
    position: fixed;
    top: 0;
    bottom: 0;
    left: 0;
    width: 240px;
    background: #212529;
    color: #fff;
    padding-top: 20px;
    transition: all 0.3s;
    z-index: 1000;
    overflow-y: auto;
}

.sidebar .nav-link {
    color: #adb5bd;
    margin: 6px 0;
    border-radius: 6px;
}

.sidebar .nav-link.active,
.sidebar .nav-link:hover {
    background: #0d6efd;
    color: #fff;
}

.sidebar .nav-link i {
    margin-right: 8px;
}

/* Content area */
.content {
    margin-left: 240px;
    padding: 20px;
}

.export-form button {
    transition: all 0.3s ease;
}

.export-form button:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.feature-icon i {
    opacity: 0.8;
}

.card {
    border: none;
    border-radius: 10px;
    box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.08);
}

.card-header {
    border-radius: 10px 10px 0 0 !important;
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
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.getElementById('importForm')?.addEventListener('submit', function(e) {
    const btn = document.getElementById('importBtn');
    const fileInput = document.getElementById('importFile');
    
    if (!fileInput.files.length) {
        e.preventDefault();
        alert('Please select a file to import');
        return;
    }
    
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Importing... Please wait';
});

// Show file name when selected
document.getElementById('importFile')?.addEventListener('change', function(e) {
    if (this.files.length) {
        const fileName = this.files[0].name;
        const fileSize = (this.files[0].size / 1024 / 1024).toFixed(2);
        console.log(`Selected: ${fileName} (${fileSize} MB)`);
    }
});
</script>
</body>
</html>
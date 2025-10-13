@extends('layouts.seller')

@section('title', 'Import / Export Products')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-exchange-alt me-2"></i>Import / Export Products
            </h1>
            <p class="text-muted mb-0">Manage your product listings in bulk</p>
        </div>
        <a href="{{ route('seller.dashboard') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
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

    @if ($errors->any())
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
}

.card-header {
    border-radius: 10px 10px 0 0 !important;
}
</style>

<script>
document.getElementById('importForm').addEventListener('submit', function(e) {
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
document.getElementById('importFile').addEventListener('change', function(e) {
    if (this.files.length) {
        const fileName = this.files[0].name;
        const fileSize = (this.files[0].size / 1024 / 1024).toFixed(2);
        console.log(`Selected: ${fileName} (${fileSize} MB)`);
    }
});
</script>
@endsection

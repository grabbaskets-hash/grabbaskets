



<?php $__env->startSection('content'); ?>
<style>
body {
    background: linear-gradient(135deg, #e0f7fa, #80deea);
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
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
    margin: 40px auto;
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
}
.btn-outline-pro:hover {
    background: #3b82f6;
    color: #fff;
    transform: translateY(-2px);
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
    .container-card { flex-direction: column; }
    .left-box, .right-box { flex: 1 1 100%; padding: 30px 20px; }
    .btn-horizontal-group { flex-direction: column; gap: 15px; }
}
</style>
<div class="container-card">
    <!-- Left Box -->
    <div class="left-box">
        <img src="<?php echo e(asset('asset/images/grabbasket.png')); ?>" alt="Grabbasket Logo">
        <p>Welcome to <strong>Grabbasket</strong>!<br>
           Edit your product details and keep your inventory up to date.</p>
        <?php if($product->image && file_exists(public_path('storage/'.$product->image))): ?>
            <div class="mt-4">
                <img src="<?php echo e(asset('storage/'.$product->image)); ?>" alt="<?php echo e($product->name); ?>" style="max-width: 100%; max-height: 220px; border-radius: 1rem; border: 2px solid #fff; box-shadow: 0 4px 16px rgba(0,0,0,0.09); background:#fafafa;">
                <div class="text-white small mt-2">Current Product Image</div>
            </div>
        <?php endif; ?>
    </div>
    <!-- Right Box -->
    <div class="right-box">
        <h2 class="mb-4">Edit Product</h2>
        <?php if(session('success')): ?>
            <div class="alert alert-success text-dark"><?php echo e(session('success')); ?></div>
        <?php endif; ?>
        <?php if(session('error')): ?>
            <div class="alert alert-danger text-dark"><?php echo e(session('error')); ?></div>
        <?php endif; ?>
        <form method="POST" action="<?php echo e(route('seller.updateProduct', $product)); ?>" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="name">Product Name</label>
                    <input type="text" id="name" name="name" class="form-control" required value="<?php echo e(old('name', $product->name)); ?>">
                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-6">
                    <label for="category_id">Category</label>
                    <select id="category_id" name="category_id" class="form-select" required>
                        <option value="">Select Category</option>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($category->id); ?>" <?php echo e(old('category_id', $product->category_id) == $category->id ? 'selected' : ''); ?>>
                                <?php echo e($category->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-6">
                    <label for="subcategory_id">Subcategory</label>
                    <select id="subcategory_id" name="subcategory_id" class="form-select" required>
                        <option value="">Select Subcategory</option>
                        <?php $__currentLoopData = $subcategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subcategory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($subcategory->id); ?>" data-category-id="<?php echo e($subcategory->category_id); ?>" 
                                <?php echo e(old('subcategory_id', $product->subcategory_id) == $subcategory->id ? 'selected' : ''); ?>>
                                <?php echo e($subcategory->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['subcategory_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-6">
                    <label for="price">Price</label>
                    <input type="number" step="0.01" id="price" name="price" class="form-control" required value="<?php echo e(old('price', $product->price)); ?>">
                    <?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-6">
                    <label for="discount">Discount (%)</label>
                    <input type="number" step="0.01" id="discount" name="discount" class="form-control" value="<?php echo e(old('discount', $product->discount)); ?>">
                    <?php $__errorArgs = ['discount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-6">
                    <label for="delivery_charge">Delivery Charge</label>
                    <input type="number" step="0.01" id="delivery_charge" name="delivery_charge" class="form-control" value="<?php echo e(old('delivery_charge', $product->delivery_charge)); ?>">
                    <?php $__errorArgs = ['delivery_charge'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-12">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" class="form-control" required><?php echo e(old('description', $product->description)); ?></textarea>
                    <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-6">
                    <label for="image">Product Image</label>
                    <input type="file" id="image" name="image" class="form-control" accept="image/*">
                    <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
            <div class="btn-horizontal-group">
                <button type="submit" class="btn btn-gradient">Update Product</button>
                <a href="/seller/dashboard" class="btn btn-outline-pro">Dashboard</a>
            </div>
        </form>
        </div>
</div>
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
</script>
        </div>
</div>
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
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\e-com_updated_final\e-com_updated\resources\views/seller/edit-product.blade.php ENDPATH**/ ?>
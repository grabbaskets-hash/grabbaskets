<div class="bg-white rounded-xl shadow p-4 mb-6">
    <h3 class="text-xl font-bold text-indigo-700 mb-4">Browse Categories</h3>
    <div class="flex flex-col gap-4">
        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="bg-gradient-to-br from-indigo-50 via-pink-50 to-purple-50 rounded-xl shadow p-4 flex flex-col hover:scale-105 transition" style="cursor:pointer;">
                <!-- Main Category Link -->
                <a href="<?php echo e(route('buyer.productsByCategory', $cat->id)); ?>" class="flex items-center mb-2 text-decoration-none">
                    <?php
                        $sampleImages = [
                            'FASHION & CLOTHING' => 'https://images.unsplash.com/photo-1512436991641-6745cdb1723f?auto=format&fit=crop&w=80&q=80',
                            'ELECTRONICS' => 'https://images.unsplash.com/photo-1519389950473-47ba0277781c?auto=format&fit=crop&w=80&q=80',
                            'HOME' => 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=80&q=80',
                            'BEAUTY' => 'https://images.unsplash.com/photo-1515378791036-0c623066013b?auto=format&fit=crop&w=80&q=80',
                            'SPORTS' => 'https://images.unsplash.com/photo-1517649763962-0c623066013b?auto=format&fit=crop&w=80&q=80',
                        ];
                        $img = $cat->image ?? ($sampleImages[strtoupper($cat->name)] ?? 'https://via.placeholder.com/80');
                    ?>
                    <img src="<?php echo e($img); ?>" alt="<?php echo e($cat->name); ?>" class="rounded-lg shadow mr-3" style="width:56px;height:56px;object-fit:cover;">
                    <span class="font-bold text-lg text-indigo-700"><?php echo e($cat->name); ?></span>
                </a>

                <!-- Subcategories -->
                <?php if($cat->subcategories && $cat->subcategories->count()): ?>
                    <div class="mt-2 flex flex-col gap-2">
                        <?php $__currentLoopData = $cat->subcategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subcat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a href="<?php echo e(route('buyer.productsBySubcategory', $subcat->id)); ?>"
                               class="block px-3 py-2 rounded-lg bg-white hover:bg-pink-100 text-gray-700 font-medium shadow transition flex items-center">
                                <span class="material-icons text-pink-400 mr-2">subdirectory_arrow_right</span>
                                <?php echo e($subcat->name); ?>

                            </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div><?php /**PATH E:\e-com_updated_final\e-com_updated\resources\views/components/category-menu.blade.php ENDPATH**/ ?>
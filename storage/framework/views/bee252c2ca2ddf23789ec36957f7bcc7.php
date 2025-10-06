<nav class="bg-[#232f3e] text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <div class="flex items-center gap-6">
                <a href="<?php echo e(route('home')); ?>" class="text-2xl font-bold text-[#ff9900]">grabbasket</a>
                <a href="<?php echo e(route('buyer.dashboard')); ?>" class="hidden md:inline-block hover:text-[#ff9900]">Shop</a>
                <?php if(auth()->guard()->check()): ?>
                    <a href="<?php echo e(route('cart.index')); ?>" class="hidden md:inline-block hover:text-[#ff9900]">Cart</a>
                <?php endif; ?>
            </div>
            <div class="flex-1 max-w-xl mx-4 hidden md:block">
                <form action="<?php echo e(route('buyer.dashboard')); ?>" method="GET" class="flex">
                    <input type="text" name="q" placeholder="Search products, brands..." class="flex-1 px-3 py-2 rounded-l bg-white text-gray-800" />
                    <button class="px-4 py-2 bg-[#ff9900] text-[#232f3e] font-semibold rounded-r">Search</button>
                </form>
            </div>
            <div class="flex items-center gap-4">
                <?php if(auth()->guard()->check()): ?>
                    <span class="hidden md:inline">Hello, <?php echo e(Auth::user()->name); ?></span>
                    <form method="POST" action="<?php echo e(route('logout')); ?>">
                        <?php echo csrf_field(); ?>
                        <button class="px-3 py-2 bg-white/10 rounded hover:bg-white/20">Logout</button>
                    </form>
                <?php else: ?>
                    <a href="<?php echo e(route('login')); ?>" class="px-3 py-2 bg-white/10 rounded hover:bg-white/20">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>
<?php /**PATH E:\e-com_updated_final\e-com_updated\resources\views/layouts/navigation.blade.php ENDPATH**/ ?>
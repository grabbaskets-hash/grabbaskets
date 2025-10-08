<nav class="bg-[#232f3e] text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <div class="flex items-center gap-6">
                <a href="{{ route('home') }}" class="text-2xl font-bold text-[#ff9900]">grabbasket</a>
                <a href="{{ route('buyer.dashboard') }}" class="hidden md:inline-block hover:text-[#ff9900]">Shop</a>
                @auth
                    <a href="{{ route('cart.index') }}" class="hidden md:inline-block hover:text-[#ff9900]">Cart</a>
                @endauth
            </div>
            <div class="flex-1 max-w-xl mx-4 hidden md:block">
                <form action="{{ route('buyer.dashboard') }}" method="GET" class="flex">
                    <input type="text" name="q" placeholder="Search products, brands..." class="flex-1 px-3 py-2 rounded-l bg-white text-gray-800" />
                    <button class="px-4 py-2 bg-[#ff9900] text-[#232f3e] font-semibold rounded-r">Search</button>
                </form>
            </div>
            <div class="flex items-center gap-4">
                @auth
                    <span class="hidden md:inline">Hello, {{ Auth::user()->name ?? 'User' }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="px-3 py-2 bg-white/10 rounded hover:bg-white/20">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="px-3 py-2 bg-white/10 rounded hover:bg-white/20">Login</a>
                @endauth
            </div>
        </div>
    </div>
</nav>

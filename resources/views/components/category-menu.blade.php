<div class="bg-white rounded-xl shadow p-4 mb-6">
    <h3 class="text-xl font-bold text-indigo-700 mb-4">🛍️ Browse Categories</h3>
    <div class="flex flex-col gap-4">
        @foreach($categories as $cat)
            <div class="bg-gradient-to-br from-indigo-50 via-pink-50 to-purple-50 rounded-xl shadow p-4 flex flex-col hover:scale-105 transition" style="cursor:pointer;">
                <!-- Main Category Link -->
                <a href="{{ route('buyer.productsByCategory', $cat->id) }}" class="flex items-center mb-2 text-decoration-none">
                    @php
                        $categoryEmojis = [
                            'Electronics' => '📱',
                            'Fashion' => '👕',
                            'Home & Kitchen' => '🏠',
                            'Books' => '📚',
                            'Sports' => '⚽',
                            'Beauty' => '💄',
                            'Toys' => '🧸',
                            'Automotive' => '🚗',
                            'Health' => '🏥',
                            'Garden' => '🌱',
                            'Jewelry' => '💍',
                            'Music' => '🎵',
                            'Pet Supplies' => '🐕',
                            'Office' => '🖥️',
                            'Travel' => '✈️',
                            'Art & Crafts' => '🎨',
                        ];
                        $emoji = $categoryEmojis[$cat->name] ?? '🏷️';
                    @endphp
                    <div class="rounded-lg shadow mr-3 d-flex align-items-center justify-content-center" 
                         style="width:56px;height:56px;background:linear-gradient(45deg, #8B4513, #A0522D);font-size:1.5rem;">
                        {{ $emoji }}
                    </div>
                    <span class="font-bold text-lg text-indigo-700">{{ $emoji }} {{ $cat->name }}</span>
                </a>

                <!-- Subcategories -->
                @if($cat->subcategories && $cat->subcategories->count())
                    <div class="mt-2 flex flex-col gap-2">
                        @foreach($cat->subcategories as $subcat)
                            <a href="{{ route('buyer.productsBySubcategory', $subcat->id) }}"
                               class="block px-3 py-2 rounded-lg bg-white hover:bg-pink-100 text-gray-700 font-medium shadow transition flex items-center">
                                <span class="mr-2" style="font-size:0.9rem;">➤</span>
                                {{ $subcat->name }}
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>
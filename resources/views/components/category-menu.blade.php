<div class="modern-category-container">
    <div class="category-header">
        <h3 class="category-title">� Browse Categories</h3>
        <div class="category-subtitle">Discover amazing products in every category</div>
    </div>
    
    <div class="categories-grid">
        @foreach($categories as $cat)
            <div class="category-card-modern" data-category="{{ $cat->id }}">
                <!-- Category Header -->
                <div class="category-card-top">
                    <div class="category-emoji-container">
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
                        <span class="category-emoji">{{ $emoji }}</span>
                    </div>
                    <div class="category-info">
                        <h4 class="category-name">{{ $cat->name }}</h4>
                        <span class="category-count">{{ $cat->subcategories ? $cat->subcategories->count() : 0 }} items</span>
                    </div>
                </div>

                <!-- Main Category Link -->
                <a href="{{ route('buyer.productsByCategory', $cat->id) }}" class="category-main-link">
                    <span class="link-text">View All {{ $cat->name }}</span>
                    <span class="link-arrow">→</span>
                </a>

                <!-- Subcategories -->
                @if($cat->subcategories && $cat->subcategories->count())
                    <div class="subcategories-modern">
                        <div class="subcategories-label">Popular in {{ $cat->name }}:</div>
                        <div class="subcategories-list">
                            @foreach($cat->subcategories->take(4) as $subcat)
                                <a href="{{ route('buyer.productsBySubcategory', $subcat->id) }}" class="subcategory-tag">
                                    {{ $subcat->name }}
                                </a>
                            @endforeach
                            @if($cat->subcategories->count() > 4)
                                <a href="{{ route('buyer.productsByCategory', $cat->id) }}" class="subcategory-tag more-tag">
                                    +{{ $cat->subcategories->count() - 4 }} more
                                </a>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
    
    <div class="category-footer">
        <a href="{{ route('buyer.dashboard') }}" class="view-all-categories-btn">
            <span class="btn-emoji">🛍️</span>
            <span class="btn-text">Explore All Categories</span>
            <span class="btn-arrow">→</span>
        </a>
    </div>
</div>

<style>
.modern-category-container {
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    border-radius: 20px;
    padding: 24px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    max-height: 70vh;
    overflow-y: auto;
}

.category-header {
    text-align: center;
    margin-bottom: 24px;
    padding-bottom: 16px;
    border-bottom: 2px solid rgba(139, 69, 19, 0.1);
}

.category-title {
    font-size: 1.5rem;
    font-weight: 800;
    color: #8B4513;
    margin: 0 0 8px 0;
    background: linear-gradient(45deg, #8B4513, #A0522D);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.category-subtitle {
    color: #64748b;
    font-size: 0.9rem;
    font-weight: 500;
}

.categories-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 16px;
    margin-bottom: 20px;
}

.category-card-modern {
    background: white;
    border-radius: 16px;
    padding: 20px;
    box-shadow: 0 4px 15px rgba(139, 69, 19, 0.08);
    border: 2px solid rgba(139, 69, 19, 0.05);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.category-card-modern::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 3px;
    background: linear-gradient(90deg, #8B4513, #A0522D, #CD853F);
    transition: left 0.5s ease;
}

.category-card-modern:hover::before {
    left: 0;
}

.category-card-modern:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(139, 69, 19, 0.15);
    border-color: rgba(139, 69, 19, 0.15);
}

.category-card-top {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 16px;
}

.category-emoji-container {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #8B4513, #A0522D);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 12px rgba(139, 69, 19, 0.2);
    flex-shrink: 0;
}

.category-emoji {
    font-size: 1.5rem;
    filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.2));
}

.category-info {
    flex-grow: 1;
}

.category-name {
    font-size: 1.1rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0 0 4px 0;
    line-height: 1.3;
}

.category-count {
    font-size: 0.8rem;
    color: #64748b;
    font-weight: 600;
    background: rgba(139, 69, 19, 0.1);
    padding: 2px 8px;
    border-radius: 12px;
}

.category-main-link {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 16px;
    background: linear-gradient(135deg, rgba(139, 69, 19, 0.05), rgba(139, 69, 19, 0.1));
    border-radius: 10px;
    text-decoration: none;
    margin-bottom: 16px;
    transition: all 0.3s ease;
    border: 1px solid rgba(139, 69, 19, 0.1);
}

.category-main-link:hover {
    background: linear-gradient(135deg, rgba(139, 69, 19, 0.1), rgba(139, 69, 19, 0.15));
    transform: translateX(4px);
    border-color: rgba(139, 69, 19, 0.2);
}

.link-text {
    color: #8B4513;
    font-weight: 600;
    font-size: 0.9rem;
}

.link-arrow {
    color: #8B4513;
    font-weight: bold;
    font-size: 1.1rem;
    transition: transform 0.3s ease;
}

.category-main-link:hover .link-arrow {
    transform: translateX(4px);
}

.subcategories-modern {
    margin-top: 12px;
}

.subcategories-label {
    font-size: 0.8rem;
    color: #64748b;
    font-weight: 600;
    margin-bottom: 8px;
}

.subcategories-list {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
}

.subcategory-tag {
    display: inline-block;
    padding: 4px 10px;
    background: rgba(139, 69, 19, 0.08);
    color: #8B4513;
    border-radius: 16px;
    font-size: 0.75rem;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s ease;
    border: 1px solid rgba(139, 69, 19, 0.1);
}

.subcategory-tag:hover {
    background: rgba(139, 69, 19, 0.15);
    color: #654321;
    transform: translateY(-1px);
    box-shadow: 0 2px 5px rgba(139, 69, 19, 0.2);
}

.more-tag {
    background: #8B4513;
    color: white;
    font-weight: 600;
}

.more-tag:hover {
    background: #654321;
    color: white;
}

.category-footer {
    text-align: center;
    padding-top: 16px;
    border-top: 2px solid rgba(139, 69, 19, 0.1);
}

.view-all-categories-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    background: linear-gradient(135deg, #8B4513, #A0522D);
    color: white;
    text-decoration: none;
    border-radius: 25px;
    font-weight: 600;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(139, 69, 19, 0.3);
}

.view-all-categories-btn:hover {
    background: linear-gradient(135deg, #A0522D, #8B4513);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(139, 69, 19, 0.4);
    color: white;
}

.btn-emoji {
    font-size: 1.1rem;
}

.btn-arrow {
    transition: transform 0.3s ease;
    font-weight: bold;
}

.view-all-categories-btn:hover .btn-arrow {
    transform: translateX(4px);
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .modern-category-container {
        padding: 16px;
        max-height: 60vh;
    }
    
    .categories-grid {
        grid-template-columns: 1fr;
        gap: 12px;
    }
    
    .category-card-modern {
        padding: 16px;
    }
    
    .category-title {
        font-size: 1.3rem;
    }
}

/* Scrollbar Styling */
.modern-category-container::-webkit-scrollbar {
    width: 6px;
}

.modern-category-container::-webkit-scrollbar-track {
    background: rgba(139, 69, 19, 0.1);
    border-radius: 3px;
}

.modern-category-container::-webkit-scrollbar-thumb {
    background: rgba(139, 69, 19, 0.3);
    border-radius: 3px;
}

.modern-category-container::-webkit-scrollbar-thumb:hover {
    background: rgba(139, 69, 19, 0.5);
}
</style>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>{{ $food->name }} — GrabBasket</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
body{ font-family:"Poppins",sans-serif; background:#f5f4f7; margin:0; }
.nav-app{ background:#fff; padding:14px 20px; box-shadow:0 4px 10px rgba(0,0,0,0.06); position:sticky; top:0; z-index:1000; display:flex; align-items:center; justify-content:space-between; }
.brand{ font-weight:700; font-size:1.4rem; color:#FF6B00; cursor:pointer; }
.food-details{ max-width:700px; margin:20px auto; background:#fff; padding:20px; border-radius:12px; box-shadow:0 10px 28px rgba(0,0,0,0.06);}
.food-details img{ width:100%; max-height:250px; object-fit:cover; border-radius:12px; }
.food-details h3{ margin-top:12px; font-weight:700; }
.meta-row{ display:flex; gap:15px; margin:10px 0; font-weight:600; color:#555; }
.add-cart{ background:#FF6B00; color:#fff; border:none; margin-left: 70%; border-radius:30px; padding:10px 20px; font-weight:600; display:flex; align-items:center; gap:8px; cursor:pointer; transition:.3s;}
.add-cart:hover{ background:#e66100; }
.recommended-items{ margin-top:25px; }
.recommended-scroll{ display:flex; gap:16px; overflow-x:auto; scroll-behavior:smooth; }
.recommended-scroll::-webkit-scrollbar{ display:none; }
.food-card{ background:#fff;border-radius:12px; padding:8px; box-shadow:0 6px 14px rgba(0,0,0,0.08); cursor:pointer; min-width:140px; }
.food-card img{ width:100%; height:100px; object-fit:cover; border-radius:8px; }
.food-title{ font-size:0.85rem; font-weight:600; margin-top:5px; }
.food-meta{ font-size:0.8rem; display:flex; justify-content:space-between; }
.brand {
  font-weight: 700;
  font-size: 1.4rem;
  color: #FF6B00;
  cursor: pointer;
  text-decoration: none; /* already added above */
}
</style>
</head>
<body>

<div class="nav-app">
<a href="{{ route('customer.food.index') }}" class="brand text-decoration-none">GrabBaskets</a>
  <button onclick="window.history.back()" class="btn btn-outline-secondary">Back</button>
</div>

<div class="food-details">
  <!-- Image -->
  @if(!empty($food->images) && is_array($food->images))
    <img src="{{ $food->images[0] }}" alt="{{ $food->name }}">
  @else
    <img src="https://via.placeholder.com/480x300?text=No+Image" alt="{{ $food->name }}">
  @endif

  <!-- Name -->
  <h3>{{ $food->name }}</h3>

  <!-- Meta Row -->
  <div class="meta-row">
    <span class="price">₹{{ number_format($food->getFinalPrice(), 0) }}</span>
    <span class="rating">
      <i class="fa-solid fa-star"></i> {{ number_format($food->rating ?? 4.0, 1) }}
    </span>
    <span>
      <i class="fa-solid fa-clock"></i> {{ $food->preparation_time ?? 'N/A' }} mins
    </span>
  </div>

  <!-- Description -->
  <p>{{ $food->description ?: 'Delicious food prepared with fresh ingredients.' }}</p>

  <!-- Add to Cart Button (Form) -->
  <form action="{{ route('customer.food.cart.add') }}" method="POST">
    @csrf
    <input type="hidden" name="food_id" value="{{ $food->id }}">
    <button type="submit" class="add-cart">
      <i class="fa-solid fa-cart-plus"></i> Add to Cart
    </button>
  </form>

  <!-- Recommended Items -->
  <div class="recommended-items">
    <h5>Recommended for you</h5>
    <div class="recommended-scroll">
      @php
        // Get up to 6 other available items from the same hotel
        $recommended = $food->hotelOwner
            ? $food->hotelOwner->foodItems()
                ->where('id', '!=', $food->id)
                ->where('is_available', 1)
                ->take(6)
                ->get()
            : collect();
      @endphp

      @if($recommended->count() > 0)
        @foreach($recommended as $item)
         <a href="{{ route('customer.food.details', $item->id) }}" class="food-card text-decoration-none text-dark">
  <img src="{{ $item->images[0] ?? 'https://via.placeholder.com/100x100?text=No+Image' }}" alt="{{ $item->name }}">
  <div class="food-title">{{ $item->name }}</div>
  <div class="food-meta">
    <span class="price">₹{{ number_format($item->getFinalPrice(), 0) }}</span>
    <span class="rating">
      <i class="fa-solid fa-star"></i> {{ number_format($item->rating ?? 4.0, 1) }}
    </span>
  </div>
</a>
        @endforeach
      @else
        <div class="text-muted">No other items available from this restaurant.</div>
      @endif
    </div>
  </div>
</div>

</body>
</html>
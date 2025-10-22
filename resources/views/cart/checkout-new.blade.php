@extends('layouts.app')

@section('title', 'Checkout - GrabBaskets')

@section('head')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}&libraries=places" async defer></script>
<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
  :root {
    --zepto-green: #0C831F;
    --blinkit-yellow: #F8CB46;
    --express-red: #FF3B3B;
    --bg-light: #F8F9FA;
  }

  body {
    background: var(--bg-light);
  }

  .checkout-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 20px;
  }

  .checkout-header {
    background: linear-gradient(135deg, var(--zepto-green), #0A6917);
    color: white;
    padding: 30px;
    border-radius: 20px;
    margin-bottom: 30px;
    box-shadow: 0 10px 40px rgba(12, 131, 31, 0.3);
  }

  .checkout-grid {
    display: grid;
    grid-template-columns: 1fr 400px;
    gap: 30px;
  }

  .section-card {
    background: white;
    border-radius: 20px;
    padding: 30px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    margin-bottom: 20px;
  }

  .section-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 24px;
    padding-bottom: 16px;
    border-bottom: 2px solid #f0f0f0;
  }

  .section-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
  }

  .delivery-selector {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    margin-bottom: 24px;
  }

  .delivery-option {
    border: 2px solid #e0e0e0;
    border-radius: 16px;
    padding: 20px;
    cursor: pointer;
    transition: all 0.3s;
    position: relative;
  }

  .delivery-option:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
  }

  .delivery-option.selected {
    border-color: var(--zepto-green);
    background: linear-gradient(135deg, rgba(12, 131, 31, 0.05), rgba(12, 131, 31, 0.02));
  }

  .delivery-option.express {
    border-color: var(--express-red);
  }

  .delivery-option.express.selected {
    border-color: var(--express-red);
    background: linear-gradient(135deg, rgba(255, 59, 59, 0.05), rgba(255, 59, 59, 0.02));
  }

  .delivery-badge {
    position: absolute;
    top: 12px;
    right: 12px;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 700;
  }

  .badge-express {
    background: var(--express-red);
    color: white;
    animation: pulse 2s infinite;
  }

  .badge-standard {
    background: #2196F3;
    color: white;
  }

  #map {
    height: 300px;
    width: 100%;
    border-radius: 16px;
    margin-top: 16px;
    box-shadow: inset 0 2px 8px rgba(0, 0, 0, 0.1);
  }

  .cart-items-section {
    max-height: 400px;
    overflow-y: auto;
  }

  .cart-item {
    display: flex;
    gap: 16px;
    padding: 16px;
    border-radius: 12px;
    background: #f8f9fa;
    margin-bottom: 12px;
    transition: all 0.3s;
  }

  .cart-item:hover {
    background: #e9ecef;
    transform: translateX(4px);
  }

  .cart-item-image {
    width: 80px;
    height: 80px;
    border-radius: 12px;
    object-fit: cover;
  }

  .cart-item-info {
    flex: 1;
  }

  .delivery-tag {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 700;
    margin-top: 8px;
  }

  .tag-express {
    background: rgba(255, 59, 59, 0.1);
    color: var(--express-red);
  }

  .tag-standard {
    background: rgba(33, 150, 243, 0.1);
    color: #2196F3;
  }

  .order-summary {
    position: sticky;
    top: 20px;
  }

  .summary-row {
    display: flex;
    justify-content: space-between;
    padding: 12px 0;
    border-bottom: 1px solid #f0f0f0;
  }

  .summary-row.total {
    border-top: 2px solid var(--zepto-green);
    border-bottom: none;
    font-size: 1.3rem;
    font-weight: 700;
    padding-top: 16px;
    margin-top: 12px;
  }

  .place-order-btn {
    width: 100%;
    padding: 18px;
    border: none;
    border-radius: 16px;
    background: linear-gradient(135deg, var(--zepto-green), #0A6917);
    color: white;
    font-size: 1.1rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s;
    box-shadow: 0 6px 24px rgba(12, 131, 31, 0.3);
    margin-top: 20px;
  }

  .place-order-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 32px rgba(12, 131, 31, 0.4);
  }

  .place-order-btn:active {
    transform: translateY(0);
  }

  .form-input {
    width: 100%;
    padding: 14px 18px;
    border: 2px solid #e0e0e0;
    border-radius: 12px;
    font-size: 1rem;
    transition: all 0.3s;
  }

  .form-input:focus {
    outline: none;
    border-color: var(--zepto-green);
    box-shadow: 0 0 0 4px rgba(12, 131, 31, 0.1);
  }

  .location-btn {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 12px;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .location-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.3);
  }

  .eligibility-badge {
    padding: 12px 20px;
    border-radius: 12px;
    font-weight: 700;
    margin-top: 16px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
  }

  .eligible {
    background: linear-gradient(135deg, rgba(76, 175, 80, 0.1), rgba(76, 175, 80, 0.05));
    color: #2E7D32;
    border: 2px solid #4CAF50;
  }

  .not-eligible {
    background: linear-gradient(135deg, rgba(255, 152, 0, 0.1), rgba(255, 152, 0, 0.05));
    color: #E65100;
    border: 2px solid #FF9800;
  }

  @media (max-width: 1024px) {
    .checkout-grid {
      grid-template-columns: 1fr;
    }

    .delivery-selector {
      grid-template-columns: 1fr;
    }

    .order-summary {
      position: relative;
      top: 0;
    }
  }

  @keyframes pulse {
    0%, 100% {
      transform: scale(1);
    }
    50% {
      transform: scale(1.05);
    }
  }

  .loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 9999;
  }

  .loading-overlay.active {
    display: flex;
  }

  .spinner {
    width: 60px;
    height: 60px;
    border: 4px solid rgba(255, 255, 255, 0.3);
    border-top-color: white;
    border-radius: 50%;
    animation: spin 1s linear infinite;
  }

  @keyframes spin {
    to {
      transform: rotate(360deg);
    }
  }
</style>
@endsection

@section('content')
<div class="checkout-container">
  <!-- Header -->
  <div class="checkout-header">
    <div class="d-flex justify-content-between align-items-center">
      <div>
        <h1 class="mb-2">🛒 Secure Checkout</h1>
        <p class="mb-0 opacity-90">Review your order and complete payment</p>
      </div>
      <div class="text-end">
        <div style="background: rgba(255,255,255,0.2); padding: 12px 24px; border-radius: 12px;">
          <div style="font-size: 0.9rem; opacity: 0.9;">Total Items</div>
          <div style="font-size: 2rem; font-weight: 900;">{{ $expressCartItems->count() + $standardCartItems->count() }}</div>
        </div>
      </div>
    </div>
  </div>

  <form id="checkout-form" method="POST" action="{{ route('cart.checkout') }}">
    @csrf
    
    <div class="checkout-grid">
      <!-- Left Column - Main Content -->
      <div>
        <!-- Delivery Type Selection -->
        <div class="section-card">
          <div class="section-header">
            <div class="section-icon" style="background: linear-gradient(135deg, #F8CB46, #F4A261);">
              ⚡
            </div>
            <div>
              <h4 class="mb-0">Choose Delivery Speed</h4>
              <small class="text-muted">Select how fast you want your order</small>
            </div>
          </div>

          <div class="delivery-selector">
            <!-- Express 10-Min Delivery -->
            <div class="delivery-option express" id="express-option" onclick="selectDeliveryType('express')">
              <div class="delivery-badge badge-express">⚡ FASTEST</div>
              <div style="font-size: 2rem; margin-bottom: 12px;">🚀</div>
              <h5 class="mb-2">10-Minute Express</h5>
              <p class="text-muted mb-0" style="font-size: 0.9rem;">Lightning fast delivery</p>
              <div class="mt-3">
                <strong style="color: var(--express-red);">{{ $expressCartItems->count() }} items</strong>
                <div style="font-size: 0.85rem; color: #666; margin-top: 4px;">
                  @if($expressCartItems->count() > 0)
                    ₹{{ number_format($expressTotal, 2) }}
                  @else
                    No express items
                  @endif
                </div>
              </div>
            </div>

            <!-- Standard Delivery -->
            <div class="delivery-option" id="standard-option" onclick="selectDeliveryType('standard')">
              <div class="delivery-badge badge-standard">📦 STANDARD</div>
              <div style="font-size: 2rem; margin-bottom: 12px;">🚚</div>
              <h5 class="mb-2">Standard Delivery</h5>
              <p class="text-muted mb-0" style="font-size: 0.9rem;">1-2 days delivery</p>
              <div class="mt-3">
                <strong style="color: #2196F3;">{{ $standardCartItems->count() }} items</strong>
                <div style="font-size: 0.85rem; color: #666; margin-top: 4px;">
                  @if($standardCartItems->count() > 0)
                    ₹{{ number_format($standardTotal, 2) }}
                  @else
                    No standard items
                  @endif
                </div>
              </div>
            </div>
          </div>

          <input type="hidden" name="delivery_type" id="delivery-type-input" value="express">
        </div>

        <!-- Delivery Address with Google Maps -->
        <div class="section-card">
          <div class="section-header">
            <div class="section-icon" style="background: linear-gradient(135deg, #4facfe, #00f2fe);">
              📍
            </div>
            <div>
              <h4 class="mb-0">Delivery Address</h4>
              <small class="text-muted">Where should we deliver?</small>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Street Address</label>
            <textarea name="address" id="address-input" class="form-input" rows="2" required 
                      placeholder="House no, Street name, Area">{{ old('address', auth()->user()->address ?? '') }}</textarea>
          </div>

          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label fw-semibold">City</label>
              <input type="text" name="city" id="city-input" class="form-input" required 
                     value="{{ old('city', auth()->user()->city ?? '') }}" placeholder="Enter city">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">State</label>
              <input type="text" name="state" id="state-input" class="form-input" required 
                     value="{{ old('state', auth()->user()->state ?? '') }}" placeholder="Enter state">
            </div>
          </div>

          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label fw-semibold">Pincode</label>
              <input type="text" name="pincode" id="pincode-input" class="form-input" required 
                     value="{{ old('pincode', auth()->user()->pincode ?? '') }}" 
                     placeholder="6-digit pincode" pattern="[0-9]{6}">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Phone Number</label>
              <input type="tel" name="phone" class="form-input" required 
                     value="{{ old('phone', auth()->user()->phone ?? '') }}" 
                     placeholder="10-digit mobile" pattern="[0-9]{10}">
            </div>
          </div>

          <button type="button" class="location-btn" onclick="detectLocation()">
            <i class="bi bi-crosshair"></i>
            Use Current Location
          </button>

          <div id="eligibility-status"></div>

          <!-- Google Map -->
          <div id="map"></div>

          <input type="hidden" name="latitude" id="latitude-input">
          <input type="hidden" name="longitude" id="longitude-input">
        </div>

        <!-- Cart Items Preview -->
        <div class="section-card">
          <div class="section-header">
            <div class="section-icon" style="background: linear-gradient(135deg, #f093fb, #f5576c);">
              🛍️
            </div>
            <div>
              <h4 class="mb-0">Order Items</h4>
              <small class="text-muted">{{ $expressCartItems->count() + $standardCartItems->count() }} items in cart</small>
            </div>
          </div>

          <div class="cart-items-section">
            @if($expressCartItems->count() > 0)
              <h6 class="mb-3 text-danger">⚡ Express Delivery (10 mins)</h6>
              @foreach($expressCartItems as $item)
                <div class="cart-item">
                  <img src="{{ $item->product->image ?? '/images/placeholder.png' }}" 
                       alt="{{ $item->product->name }}" class="cart-item-image">
                  <div class="cart-item-info">
                    <h6 class="mb-1">{{ $item->product->name }}</h6>
                    <div class="d-flex justify-content-between align-items-center">
                      <span class="text-muted">Qty: {{ $item->quantity }}</span>
                      <strong>₹{{ number_format($item->product->price * $item->quantity, 2) }}</strong>
                    </div>
                    <span class="delivery-tag tag-express">⚡ 10-Min Delivery</span>
                  </div>
                </div>
              @endforeach
            @endif

            @if($standardCartItems->count() > 0)
              <h6 class="mb-3 text-primary mt-4">📦 Standard Delivery (1-2 days)</h6>
              @foreach($standardCartItems as $item)
                <div class="cart-item">
                  <img src="{{ $item->product->image ?? '/images/placeholder.png' }}" 
                       alt="{{ $item->product->name }}" class="cart-item-image">
                  <div class="cart-item-info">
                    <h6 class="mb-1">{{ $item->product->name }}</h6>
                    <div class="d-flex justify-content-between align-items-center">
                      <span class="text-muted">Qty: {{ $item->quantity }}</span>
                      <strong>₹{{ number_format($item->product->price * $item->quantity, 2) }}</strong>
                    </div>
                    <span class="delivery-tag tag-standard">📦 Standard Delivery</span>
                  </div>
                </div>
              @endforeach
            @endif
          </div>
        </div>

        <!-- Payment Method -->
        <div class="section-card">
          <div class="section-header">
            <div class="section-icon" style="background: linear-gradient(135deg, #667eea, #764ba2);">
              💳
            </div>
            <div>
              <h4 class="mb-0">Payment Method</h4>
              <small class="text-muted">Secure payment gateway</small>
            </div>
          </div>

          <div class="form-check mb-3 p-3" style="background: #f8f9fa; border-radius: 12px;">
            <input class="form-check-input" type="radio" name="payment_method" id="razorpay" value="razorpay" checked>
            <label class="form-check-label fw-semibold" for="razorpay">
              💳 Razorpay (Cards, UPI, Wallets, Net Banking)
            </label>
          </div>

          <div class="form-check p-3" style="background: #f8f9fa; border-radius: 12px;">
            <input class="form-check-input" type="radio" name="payment_method" id="cod" value="cod">
            <label class="form-check-label fw-semibold" for="cod">
              💵 Cash on Delivery (COD)
            </label>
          </div>
        </div>
      </div>

      <!-- Right Column - Order Summary -->
      <div>
        <div class="section-card order-summary">
          <h4 class="mb-4">📊 Order Summary</h4>

          <div class="summary-row">
            <span>Express Items ({{ $expressCartItems->count() }})</span>
            <strong>₹{{ number_format($expressTotal, 2) }}</strong>
          </div>

          <div class="summary-row">
            <span>Standard Items ({{ $standardCartItems->count() }})</span>
            <strong>₹{{ number_format($standardTotal, 2) }}</strong>
          </div>

          <div class="summary-row">
            <span>Delivery Charges</span>
            <strong class="text-success">FREE</strong>
          </div>

          <div class="summary-row">
            <span>Taxes & Fees</span>
            <strong>₹{{ number_format(($expressTotal + $standardTotal) * 0.18, 2) }}</strong>
          </div>

          <div class="summary-row total">
            <span>Total Amount</span>
            <strong style="color: var(--zepto-green);">₹{{ number_format(($expressTotal + $standardTotal) * 1.18, 2) }}</strong>
          </div>

          <button type="submit" class="place-order-btn">
            <i class="bi bi-shield-check"></i> Place Secure Order
          </button>

          <div class="text-center mt-3">
            <small class="text-muted">
              <i class="bi bi-lock-fill"></i> Secure SSL Encrypted Payment
            </small>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>

<!-- Loading Overlay -->
<div class="loading-overlay" id="loading-overlay">
  <div class="text-center">
    <div class="spinner"></div>
    <div class="mt-3 text-white">Processing your order...</div>
  </div>
</div>

<script>
  let map;
  let marker;
  let selectedDeliveryType = 'express';

  // Initialize Google Map
  function initMap() {
    const defaultLocation = { lat: 12.9716, lng: 77.5946 }; // Bangalore
    
    map = new google.maps.Map(document.getElementById('map'), {
      zoom: 14,
      center: defaultLocation,
      styles: [
        {
          featureType: 'poi',
          elementType: 'labels',
          stylers: [{ visibility: 'off' }]
        }
      ]
    });

    marker = new google.maps.Marker({
      position: defaultLocation,
      map: map,
      draggable: true,
      title: 'Your Delivery Location'
    });

    // Update coordinates when marker is dragged
    marker.addListener('dragend', function() {
      const position = marker.getPosition();
      updateCoordinates(position.lat(), position.lng());
      checkEligibility();
    });

    // Get current location on load if available
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition((position) => {
        const pos = {
          lat: position.coords.latitude,
          lng: position.coords.longitude
        };
        map.setCenter(pos);
        marker.setPosition(pos);
        updateCoordinates(pos.lat, pos.lng);
        geocodeLocation(pos.lat, pos.lng);
      });
    }
  }

  function selectDeliveryType(type) {
    selectedDeliveryType = type;
    document.getElementById('delivery-type-input').value = type;
    
    // Update UI
    document.getElementById('express-option').classList.toggle('selected', type === 'express');
    document.getElementById('standard-option').classList.toggle('selected', type === 'standard');
  }

  function detectLocation() {
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition((position) => {
        const lat = position.coords.latitude;
        const lng = position.coords.longitude;
        
        const pos = { lat, lng };
        map.setCenter(pos);
        marker.setPosition(pos);
        updateCoordinates(lat, lng);
        geocodeLocation(lat, lng);
        checkEligibility();
      }, () => {
        alert('Unable to retrieve your location. Please enter address manually.');
      });
    } else {
      alert('Geolocation is not supported by your browser.');
    }
  }

  function updateCoordinates(lat, lng) {
    document.getElementById('latitude-input').value = lat;
    document.getElementById('longitude-input').value = lng;
  }

  function geocodeLocation(lat, lng) {
    const geocoder = new google.maps.Geocoder();
    geocoder.geocode({ location: { lat, lng } }, (results, status) => {
      if (status === 'OK' && results[0]) {
        const addressComponents = results[0].address_components;
        
        // Auto-fill address fields
        addressComponents.forEach(component => {
          if (component.types.includes('locality')) {
            document.getElementById('city-input').value = component.long_name;
          }
          if (component.types.includes('administrative_area_level_1')) {
            document.getElementById('state-input').value = component.long_name;
          }
          if (component.types.includes('postal_code')) {
            document.getElementById('pincode-input').value = component.long_name;
          }
        });

        document.getElementById('address-input').value = results[0].formatted_address;
      }
    });
  }

  function checkEligibility() {
    const lat = document.getElementById('latitude-input').value;
    const lng = document.getElementById('longitude-input').value;
    const city = document.getElementById('city-input').value;
    const state = document.getElementById('state-input').value;
    const pincode = document.getElementById('pincode-input').value;
    const address = document.getElementById('address-input').value;

    if (!lat || !lng || !city || !state || !pincode) {
      return;
    }

    fetch('{{ route("orders.checkQuickDelivery") }}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
      },
      body: JSON.stringify({
        address, city, state, pincode,
        store_id: 1 // Default store
      })
    })
    .then(res => res.json())
    .then(data => {
      const statusDiv = document.getElementById('eligibility-status');
      
      if (data.eligible) {
        statusDiv.innerHTML = `
          <div class="eligibility-badge eligible">
            <i class="bi bi-check-circle-fill"></i>
            <span>⚡ 10-Minute Delivery Available! (${data.distance_km} km away)</span>
          </div>
        `;
      } else {
        statusDiv.innerHTML = `
          <div class="eligibility-badge not-eligible">
            <i class="bi bi-info-circle-fill"></i>
            <span>📦 Standard Delivery Available (${data.distance_km} km away)</span>
          </div>
        `;
      }
    })
    .catch(error => console.error('Error checking eligibility:', error));
  }

  // Initialize map when Google Maps loads
  window.initMap = initMap;

  // Check eligibility when address changes
  document.getElementById('address-input').addEventListener('blur', checkEligibility);
  document.getElementById('city-input').addEventListener('blur', checkEligibility);
  document.getElementById('pincode-input').addEventListener('blur', checkEligibility);

  // Form submission
  document.getElementById('checkout-form').addEventListener('submit', function(e) {
    e.preventDefault();
    document.getElementById('loading-overlay').classList.add('active');
    
    // Submit after animation
    setTimeout(() => {
      this.submit();
    }, 500);
  });

  // Initialize
  selectDeliveryType('express');
</script>
@endsection

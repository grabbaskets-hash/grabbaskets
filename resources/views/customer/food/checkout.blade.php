<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Checkout - 10-mins Delivery</title>
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');
* { margin: 0; padding: 0; box-sizing: border-box; }
body {
    font-family: 'Inter', sans-serif;
    background: #f8fafc;
    padding: 16px;
    min-height: 100vh;
}
.container { max-width: 600px; margin: auto; }
header {
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    padding: 14px 18px;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(99,102,241,.25);
    margin-bottom: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.logo { font-size: 18px; font-weight: 800; color: #fff; display: flex; gap: 6px; align-items: center; }
.speed-badge {
    background: rgba(255,255,255,0.2);
    padding: 6px 12px; border-radius: 20px;
    color: white; font-size: 11px; font-weight: 700;
}
.section-box {
    background: #fff; border-radius: 20px; padding: 24px;
    box-shadow: 0 2px 12px rgba(15,23,42,.08);
    border: 1px solid #e2e8f0; margin-bottom: 20px;
}
h2 {
    font-size: 18px; color: #0f172a; margin-bottom: 20px;
    font-weight: 700; display: flex; gap: 8px; align-items: center;
}
.delivery-address {
    background: #f8fafc; border: 2px solid #e2e8f0;
    padding: 18px; border-radius: 14px; margin-bottom: 16px;
    transition: .3s; cursor: pointer; position: relative;
}
.edit-btn {
    position: absolute;
    right: 14px;
    top: 14px;
    font-size: 12px;
    background: #eef2ff;
    padding: 4px 10px;
    border-radius: 8px;
    font-weight: 600;
    color: #6366f1;
    border: 1px solid #c7d2fe;
}
.address-type {
    background: linear-gradient(135deg,#6366f1,#8b5cf6);
    color: white; padding: 5px 12px; border-radius: 8px;
    font-size: 10px; font-weight: 800;
}
.address-form input {
    width: 100%; padding: 13px 15px; border: 1.5px solid #e2e8f0;
    margin-bottom: 12px; border-radius: 12px; font-size: 14px;
}
.location-buttons {
    display: flex;
    gap: 10px;
    margin-bottom: 16px;
}
.location-btn {
    flex: 1;
    padding: 12px;
    font-size: 14px;
    font-weight: 600;
    border-radius: 12px;
    border: 1.5px solid #e2e8f0;
    background: #f8fafc;
    cursor: pointer;
    transition: all 0.2s;
}
.location-btn:hover {
    background: #e2e8f0;
}
.save-btn {
    background: linear-gradient(135deg,#6366f1,#8b5cf6);
    color: white; border: none; padding: 14px; width: 100%;
    border-radius: 12px; font-weight: 700; font-size: 15px;
}
.place-order-btn {
    width: 100%;
    padding: 16px;
    background: linear-gradient(135deg,#6366f1,#8b5cf6);
    color: #fff;
    border: none;
    border-radius: 14px;
    font-size: 17px;
    font-weight: 700;
    margin-top: 10px;
}
#page2 { display: none; }
.info-row {
    display: flex;
    gap: 8px;
    margin-bottom: 6px;
    font-size: 14px;
}
.info-label { font-weight: 700; color: #334155; width: 90px; }
.info-value { color: #475569; }
#popup {
    display: none; position: fixed; top: 0; left: 0; width: 100%;
    height: 100%; background: rgba(0,0,0,0.5);
    backdrop-filter: blur(5px); justify-content: center; align-items: center;
    z-index: 999; padding: 20px;
}
.popup-box { background: white; padding: 36px; border-radius: 20px; text-align: center; }
.success-icon {
    width: 80px; height: 80px; background: #10b981; color: white;
    border-radius: 50%; display: flex; justify-content: center; align-items: center;
    font-size: 40px; margin: 0 auto 20px;
}
.payment-option {
    padding: 14px;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    margin-bottom: 10px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}
.payment-option.selected {
    border-color: #6366f1;
    background: #f0f4ff;
}
#mapPreview {
    height: 220px;
    border-radius: 12px;
    margin-bottom: 16px;
    overflow: hidden;
    border: 1px solid #e2e8f0;
    display: none;
}
@media (max-width: 480px) {
    #mapPreview {
        height: 180px;
    }
}
.alert {
    background: #fffbeb; color: #d97706; padding: 10px; border-radius: 10px;
    margin-bottom: 16px; font-weight: 600; border-left: 4px solid #f59e0b;
}
</style>
</head>
<body>
<div class="container">
    <header>
        <div class="logo"><span>⚡</span>10-mins Delivery</div>
        <div class="speed-badge">⚡ Fast</div>
    </header>
    
    <div id="page1">
        <div class="section-box">
            <h2>📍 Delivery Address</h2>
            
            <div id="addressForm" class="address-form" style="display:none;">
                <input id="inputName" placeholder="Name" value="{{ $customerName }}">
                <input type="text" id="locationSearch" placeholder="Search your delivery address..." />
                <div class="location-buttons">
                    <button type="button" class="location-btn" onclick="useCurrentLocationForMap()">📍 Use Current Location</button>
                    <button type="button" class="location-btn" onclick="showMap()">🗺️ Pick on Map</button>
                </div>
                <div id="mapPreview">
                    <div id="googleMap" style="width:100%; height:100%;"></div>
                </div>
                <input id="inputAddress" placeholder="Address" value="{{ $deliveryAddress }}">
                <input id="inputPincode" placeholder="Pincode" value="600043">
                <input id="inputLandmark" placeholder="Landmark" value="Near ABC School">
                <input id="inputEmail" placeholder="Email" value="{{ $customerEmail }}">
                <input id="inputPhone" placeholder="Phone" value="{{ $customerPhone }}">
                <button class="save-btn" onclick="saveAddress()">Save Address</button>
            </div>
            
            <div id="addressDisplay" class="delivery-address">
                <span class="address-type">HOME</span>
                <button class="edit-btn" onclick="editAddress(event)">Edit</button>
                <div id="addressText" style="margin-top:16px;">
                    <div class="info-row"><div class="info-label">Name:</div><div class="info-value" id="displayName">{{ $customerName }}</div></div>
                    <div class="info-row"><div class="info-label">Address:</div><div class="info-value" id="displayAddress">{{ $deliveryAddress }}</div></div>
                    <div class="info-row"><div class="info-label">Pincode:</div><div class="info-value">600043</div></div>
                    <div class="info-row"><div class="info-label">Landmark:</div><div class="info-value">Near ABC School</div></div>
                    <div class="info-row"><div class="info-label">Email:</div><div class="info-value" id="displayEmail">{{ $customerEmail }}</div></div>
                    <div class="info-row"><div class="info-label">Phone:</div><div class="info-value" id="displayPhone">{{ $customerPhone }}</div></div>
                </div>
            </div>
        </div>

<!-- ORDER SUMMARY FOR EACH SELLER -->
@foreach($sellerGroups as $group)
<div class="section-box">
    <h2>🛒 Order from {{ $group['hotel_name'] ?? $group['restaurant_name'] ?? 'Unknown Restaurant' }}</h2>
    
    <div class="info-row" style="margin-bottom: 16px; padding-bottom: 12px; border-bottom: 1px dashed #e2e8f0;">
        <div class="info-label">From:</div>
        <div><strong>{{ $group['hotel_name'] ?? $group['restaurant_name'] ?? 'Unknown Restaurant' }}</strong></div>
    </div>
    
    @foreach($group['items'] as $item)
    <div class="info-row">
        <div class="info-label">{{ $item->name }} × {{ $item->quantity }}</div>
        <strong>₹{{ number_format($item->price * $item->quantity, 2) }}</strong>
    </div>
    @endforeach
    
    <div class="info-row"><span>Subtotal</span><span>₹{{ number_format($group['food_total'], 2) }}</span></div>
    <div class="info-row"><span>Delivery Fee</span><span>₹{{ number_format($group['delivery_fee'], 2) }}</span></div>
    <div class="info-row" style="font-weight:700;"><span>Total</span><span>₹{{ number_format($group['total'], 2) }}</span></div>
</div>
@endforeach
        
        <button class="place-order-btn" onclick="goToPayment()">Continue to Payment</button>
    </div>

    <!-- PAGE 2: PAYMENT METHODS -->
    <div id="page2">
        <button class="place-order-btn" style="margin-bottom:18px;background:#e2e8f0;color:#000" onclick="goBack()">⬅ Back</button>
        
        <div class="section-box">
            <h2>💳 Select Payment Method</h2>
            <div class="payment-option" onclick="selectPayment(this, 'cod')">💵 Cash on Delivery</div>
            <div class="payment-option" onclick="selectPayment(this, 'upi')">📱 UPI</div>
            <div class="payment-option" onclick="selectPayment(this, 'card')">💳 Card</div>
        </div>
        
        <p id="selectedPayment" style="text-align:center; margin:12px 0; color:#475569; font-weight:600;">
            Select a payment method
        </p>
        
        <button class="place-order-btn" onclick="submitOrder()">Place Order</button>
    </div>
</div>

<!-- POPUP -->
<div id="popup">
    <div class="popup-box">
        <div class="success-icon">✔</div>
        <h2>Order Placed!</h2>
        <p>Your food will arrive in 10 minutes! 🚀</p>
        <button class="place-order-btn" onclick="closePopup()">OK</button>
    </div>
</div>

<script>
    let selectedPaymentMethod = null;
    
    function saveAddress() {
        document.getElementById("displayName").innerText = document.getElementById("inputName").value;
        document.getElementById("displayAddress").innerText = document.getElementById("inputAddress").value;
        document.getElementById("displayEmail").innerText = document.getElementById("inputEmail").value;
        document.getElementById("displayPhone").innerText = document.getElementById("inputPhone").value;
        document.getElementById("addressForm").style.display = "none";
        document.getElementById("addressDisplay").style.display = "block";
    }
    
    function editAddress(event) {
        event.stopPropagation();
        document.getElementById("addressForm").style.display = "block";
        document.getElementById("addressDisplay").style.display = "none";
    }
    
    function goToPayment() {
        document.getElementById("page1").style.display = "none";
        document.getElementById("page2").style.display = "block";
    }
    
    function goBack() {
        document.getElementById("page2").style.display = "none";
        document.getElementById("page1").style.display = "block";
    }
    
    function selectPayment(el, method) {
        document.querySelectorAll(".payment-option").forEach(e => e.classList.remove("selected"));
        el.classList.add("selected");
        selectedPaymentMethod = method;
        const label = document.getElementById('selectedPayment');
        if (method === 'cod') label.innerHTML = 'You selected: 💵 Cash on Delivery';
        else if (method === 'upi') label.innerHTML = 'You selected: 📱 UPI';
        else if (method === 'card') label.innerHTML = 'You selected: 💳 Card';
    }
    
    function submitOrder() {
        if (!selectedPaymentMethod) {
            alert("Please select a payment method.");
            return;
        }
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const formData = new FormData();
        
        formData.append('delivery_address', document.getElementById("displayAddress").innerText);
        formData.append('phone', document.getElementById("displayPhone").innerText);
        formData.append('email', document.getElementById("displayEmail").innerText);
        formData.append('payment_method', selectedPaymentMethod);
        formData.append('_token', csrfToken);
        
        // Show loading state
        const btn = document.querySelector('.place-order-btn');
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Placing Order...';
        
        fetch("{{ route('customer.food.checkout.place') }}", {
            method: 'POST',
            body: formData,
            headers: { 'Accept': 'application/json' }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById("popup").style.display = "flex";
                setTimeout(() => {
                    window.location.href = data.redirect_url;
                }, 2000);
            } else {
                alert(data.message || "Failed to place order. Please try again.");
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert("Failed to place order. Please try again.");
            btn.disabled = false;
            btn.innerHTML = originalText;
        });
    }
    
    function closePopup() {
        document.getElementById("popup").style.display = "none";
    }
    
    function showMap() {
        document.getElementById("mapPreview").style.display = "block";
    }
    
    function useCurrentLocationForMap() {
        if (!navigator.geolocation) {
            alert("Geolocation not supported by your browser.");
            return;
        }
        
        navigator.geolocation.getCurrentPosition(
            (position) => {
                alert("Location detected. In a full implementation, this would show on map.");
                document.getElementById("mapPreview").style.display = "block";
            },
            (error) => {
                let msg = "Unable to get your location.";
                if (error.code === 1) {
                    msg = "Please allow location access in your browser settings.";
                }
                alert(msg);
            }
        );
    }
    
    // Initialize page state
    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById("addressForm").style.display = "none";
        document.getElementById("addressDisplay").style.display = "block";
    });
</script>

{{-- Google Maps API - Only load if enabled --}}
@if(env('GOOGLE_MAPS_ENABLED', false) && env('GOOGLE_MAPS_API_KEY'))
<script>
    const GOOGLE_MAPS_API_KEY = "{{ env('GOOGLE_MAPS_API_KEY') }}";
    if (GOOGLE_MAPS_API_KEY.trim()) {
        const script = document.createElement('script');
        script.src = `https://maps.googleapis.com/maps/api/js?key=${GOOGLE_MAPS_API_KEY}&libraries=places`;
        script.async = true;
        script.defer = true;
        document.head.appendChild(script);
    }
</script>
@endif
</body>
</html>
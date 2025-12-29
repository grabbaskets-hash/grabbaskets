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
justify-content: space-between;
margin-bottom: 6px;
font-size: 14px;
}
.info-label { font-weight: 700; color: #334155; }
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

        {{-- Show alert only if ANY seller order is below ₹200 --}}
        @php
            $hasMinError = false;
            foreach($orders as $orderGroup) {
                if ($orderGroup['subtotal'] < 200) {
                    $hasMinError = true;
                    break;
                }
            }
        @endphp

        @if($hasMinError)
            <div class="alert" style="background: #fffbeb; color: #d97706; padding: 10px; border-radius: 10px; margin-bottom: 16px; font-weight: 600; border-left: 4px solid #f59e0b;">
                ⚠️ One or more stores require a minimum of ₹200.
            </div>
        @endif

        <div id="addressForm" class="address-form" style="display:none;">
            <input id="inputName" placeholder="Name" value="{{ $customerName }}">
            <input type="text" id="locationSearch" placeholder="Search your delivery address..." />
            <div class="location-buttons">
                <button type="button" class="location-btn">📍 Use Current Location</button>
                <button type="button" class="location-btn">🗺️ Pick on Map</button>
            </div>
            <input id="inputAddress" placeholder="Address" value="{{ $deliveryAddress }}">
            <input id="inputPincode" placeholder="Pincode" value="600043">
            <input id="inputLandmark" placeholder="Landmark" value="Near ABC School">
            <input id="inputEmail" placeholder="Email" value="{{ $customerEmail }}">
            <input id="inputPhone" placeholder="Phone" value="{{ $customerPhone }}">
            <button class="save-btn" onclick="saveAddress()">Save Address</button>
        </div>

        <div id="addressDisplay" class="delivery-address" onclick="editAddress(event)">
            <span class="address-type">HOME</span>
            <button class="edit-btn" onclick="event.stopPropagation(); editAddress(event)">Edit</button>
            <div id="addressText" style="margin-top:16px;">
                <div class="info-row"><span class="info-label">Name:</span> <span class="info-value" id="displayName">{{ $customerName }}</span></div>
                <div class="info-row"><span class="info-label">Address:</span> <span class="info-value" id="displayAddress">{{ $deliveryAddress }}</span></div>
                <div class="info-row"><span class="info-label">Pincode:</span> <span class="info-value">600043</span></div>
                <div class="info-row"><span class="info-label">Landmark:</span> <span class="info-value">Near ABC School</span></div>
                <div class="info-row"><span class="info-label">Email:</span> <span class="info-value" id="displayEmail">{{ $customerEmail }}</span></div>
                <div class="info-row"><span class="info-label">Phone:</span> <span class="info-value" id="displayPhone">{{ $customerPhone }}</span></div>
            </div>
        </div>
    </div>

    <!-- ORDER SUMMARY -->
    <div class="section-box">
        <h2>🛒 Your Orders</h2>
        @foreach($orders as $orderGroup)
            <div style="margin-bottom: 20px; padding-bottom: 12px; border-bottom: 1px dashed #e2e8f0;">
                <div class="info-row" style="margin-bottom: 12px;">
                    <strong>Store: {{ $orderGroup['store_name'] }}</strong>
                </div>
                @foreach($orderGroup['items'] as $item)
                    <div class="info-row">
                        <span>{{ $item->name }} × {{ $item->quantity }}</span>
                        <strong>₹{{ number_format($item->price * $item->quantity, 2) }}</strong>
                    </div>
                @endforeach
                <div class="info-row"><span>Subtotal</span> <span>₹{{ number_format($orderGroup['subtotal'], 2) }}</span></div>
                <div class="info-row"><span>Delivery Fee</span> <span>₹{{ number_format($orderGroup['delivery_fee'], 2) }}</span></div>
                <div class="info-row" style="font-weight:700;"><span>Total</span> <span>₹{{ number_format($orderGroup['total'], 2) }}</span></div>
            </div>
        @endforeach
    </div>

    <button class="place-order-btn" onclick="goToPayment()">Continue to Payment</button>
</div>

<!-- PAGE 2: Payment -->
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

<!-- SUCCESS POPUP -->
<div id="popup">
    <div class="popup-box">
        <div class="success-icon">✔</div>
        <h2>Order Placed!</h2>
        <p>Your groceries will arrive in 10 minutes! 🚀</p>
        <button class="place-order-btn" onclick="closePopup()">OK</button>
    </div>
</div>

<script>
let selectedPaymentMethod = null;

function saveAddress(){
    document.getElementById("displayName").innerText = document.getElementById("inputName").value;
    document.getElementById("displayAddress").innerText = document.getElementById("inputAddress").value;
    document.getElementById("displayEmail").innerText = document.getElementById("inputEmail").value;
    document.getElementById("displayPhone").innerText = document.getElementById("inputPhone").value;
    document.getElementById("addressForm").style.display = "none";
    document.getElementById("addressDisplay").style.display = "block";
}

function editAddress(event){
    event.stopPropagation();
    document.getElementById("addressForm").style.display = "block";
    document.getElementById("addressDisplay").style.display = "none";
}

function goToPayment(){
    document.getElementById("page1").style.display = "none";
    document.getElementById("page2").style.display = "block";
}

function goBack(){
    document.getElementById("page2").style.display = "none";
    document.getElementById("page1").style.display = "block";
}

function selectPayment(el, method) {
    document.querySelectorAll(".payment-option").forEach(e => e.classList.remove("selected"));
    el.classList.add("selected");
    selectedPaymentMethod = method;
    const label = document.getElementById('selectedPayment');
    label.innerHTML = 'You selected: ' + (method === 'cod' ? '💵 Cash on Delivery' : method === 'upi' ? '📱 UPI' : '💳 Card');
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

    fetch("{{ route('tenmin.grocery.order.place') }}", {
        method: 'POST',
        body: formData,
        headers: { 'Accept': 'application/json' }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.redirect_url) {
            document.getElementById("popup").style.display = "flex";
            setTimeout(() => window.location.href = data.redirect_url, 2000);
        } else {
            alert(data.message || "Failed to place order.");
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert("Failed to place order. Please try again.");
    });
}

function closePopup() {
    document.getElementById("popup").style.display = "none";
}

document.addEventListener('DOMContentLoaded', () => {
    document.getElementById("addressForm").style.display = "none";
    document.getElementById("addressDisplay").style.display = "block";
});
</script>
</body>
</html>
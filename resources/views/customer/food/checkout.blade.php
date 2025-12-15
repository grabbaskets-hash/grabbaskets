<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

        /* Mobile-friendly map */
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
    </style>
</head>
 
<body>
 
<div class="container">
 
    <header>
        <div class="logo"><span>⚡</span>10-mins Delivery</div>
        <div class="speed-badge">⚡ Fast</div>
    </header>
 
    <!-- PAGE 1 -->
    <div id="page1">
 
        <div class="section-box">
            <h2>📍 Delivery Address</h2>
 
            <!-- ADDRESS FORM -->
            <div id="addressForm" class="address-form">
                <input id="inputName" placeholder="Name" value="{{ $customerName }}">
                
                <!-- 🔍 Address Search -->
                <input
                    type="text"
                    id="locationSearch"
                    placeholder="Search your delivery address..."
                    style="width:100%; padding:13px 15px; border:1.5px solid #e2e8f0; margin-bottom:12px; border-radius:12px; font-size:14px;"
                />

                <!-- 📍 Location Buttons -->
                <div class="location-buttons">
                    <button type="button" class="location-btn" onclick="useCurrentLocationForMap()">📍 Use Current Location</button>
                    <button type="button" class="location-btn" onclick="showMap()">🗺️ Pick on Map</button>
                </div>

                <!-- 🗺️ Map Preview -->
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
 
            <!-- DISPLAY SAVED ADDRESS -->
            <div id="addressDisplay" class="delivery-address" style="display:none;">
                <span class="address-type">HOME</span>
                <button class="edit-btn" onclick="editAddress(event)">Edit</button>
 
                <div id="addressText" style="margin-top:16px;">
                    <div class="info-row">
                        <div class="info-label">Name:</div>
                        <div class="info-value" id="name">{{ $customerName }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Address:</div>
                        <div class="info-value" id="address">{{ $deliveryAddress }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Pincode:</div>
                        <div class="info-value" id="pincode">600043</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Landmark:</div>
                        <div class="info-value" id="landmark">Near ABC School</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Email:</div>
                        <div class="info-value" id="email">{{ $customerEmail }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Phone:</div>
                        <div class="info-value" id="phone">{{ $customerPhone }}</div>
                    </div>
                </div>
            </div>
        </div>
 
        <!-- ORDER SUMMARY -->
        <div class="section-box">
            <h2>🛒 Your Order</h2>
            
            <div class="info-row" style="margin-bottom: 16px; padding-bottom: 12px; border-bottom: 1px dashed #e2e8f0;">
                <div class="info-label">From:</div>
                <div><strong>{{ $hotelName }}</strong></div>
            </div>

            @foreach($cartItems as $item)
            <div class="info-row">
                <div class="info-label">{{ $item->name }} × {{ $item->quantity }}</div>
                <strong>₹{{ number_format($item->price * $item->quantity, 2) }}</strong>
            </div>
            @endforeach
 
            <div class="info-row"><span>Subtotal</span><span>₹{{ number_format($foodTotal, 2) }}</span></div>
            <div class="info-row"><span>Delivery Fee</span><span>₹{{ number_format($deliveryFee, 2) }}</span></div>
            <div class="info-row" style="font-weight:700;"><span>Total</span><span>₹{{ number_format($total, 2) }}</span></div>
        </div>
 
        <form id="checkoutForm" method="POST" action="{{ route('customer.food.checkout.place') }}" style="display:none;">
            @csrf
            <input type="hidden" name="delivery_address" id="formAddress">
            <input type="hidden" name="phone" id="formPhone">
            <input type="hidden" name="email" id="formEmail">
            <input type="hidden" name="payment_method" id="formPaymentMethod">
        </form>
 
        <button class="place-order-btn" onclick="goToPayment()">Continue to Payment</button>
    </div>
 
    <!-- PAGE 2 -->
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
        <p>Your yummy food will arrive soon 🚀</p>
        <button class="place-order-btn" onclick="closePopup()">OK</button>
    </div>
</div>
 
<script>
function saveAddress(){
    document.getElementById("name").innerText = document.getElementById("inputName").value;
    document.getElementById("address").innerText = document.getElementById("inputAddress").value;
    document.getElementById("pincode").innerText = document.getElementById("inputPincode").value;
    document.getElementById("landmark").innerText = document.getElementById("inputLandmark").value;
    document.getElementById("email").innerText = document.getElementById("inputEmail").value;
    document.getElementById("phone").innerText = document.getElementById("inputPhone").value;
 
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
 
let selectedPaymentMethod = null;

function selectPayment(el, method) {
    document.querySelectorAll(".payment-option").forEach(e => {
        e.classList.remove("selected");
    });
    el.classList.add("selected");
    selectedPaymentMethod = method;

    const label = document.getElementById('selectedPayment');
    if (method === 'cod') {
        label.innerHTML = 'You selected: 💵 Cash on Delivery';
    } else if (method === 'upi') {
        label.innerHTML = 'You selected: 📱 UPI';
    } else if (method === 'card') {
        label.innerHTML = 'You selected: 💳 Card';
    }
}

function submitOrder(){
    document.getElementById("formAddress").value = document.getElementById("address").innerText;
    document.getElementById("formPhone").value = document.getElementById("phone").innerText;
    document.getElementById("formEmail").value = document.getElementById("email").innerText;
    document.getElementById("formPaymentMethod").value = selectedPaymentMethod;
    document.getElementById("checkoutForm").submit();
}

function openPopup(){ 
    document.getElementById("popup").style.display = "flex"; 
}
function closePopup(){ 
    document.getElementById("popup").style.display = "none"; 
}

// =============================
// Google Maps Logic (Auto-save enabled)
// =============================
let googleMap, googleMarker;

function showMap() {
    document.getElementById("mapPreview").style.display = "block";
    if (!googleMap && typeof google !== 'undefined') {
        initGoogleMap();
    } else if (googleMap && googleMarker) {
        // Fix blank map when shown after being hidden
        google.maps.event.trigger(googleMap, 'resize');
        googleMap.setCenter(googleMarker.getPosition());
    }
}

function initGoogleMap() {
    if (typeof google === 'undefined') return;

    const defaultLatlng = { lat: 13.0827, lng: 80.2707 }; // Default: Chennai

    googleMap = new google.maps.Map(document.getElementById("googleMap"), {
        zoom: 14,
        center: defaultLatlng,
        disableDefaultUI: true,
        zoomControl: true,
        streetViewControl: false,
        mapTypeControl: false
    });

    googleMarker = new google.maps.Marker({
        position: defaultLatlng,
        map: googleMap,
        draggable: true
    });

    // Auto-save when marker is dropped
    googleMarker.addListener("dragend", (event) => {
        reverseGeocodeAndSave(event.latLng);
    });

    // Setup Autocomplete
    const input = document.getElementById("locationSearch");
    const autocomplete = new google.maps.places.Autocomplete(input, {
        types: ["geocode"],
        componentRestrictions: { country: "IN" }
    });

    autocomplete.addListener("place_changed", () => {
        const place = autocomplete.getPlace();
        if (!place.geometry) return;

        const location = place.geometry.location;
        googleMap.setCenter(location);
        googleMap.setZoom(16);
        googleMarker.setPosition(location);
        document.getElementById("mapPreview").style.display = "block";
        fillAndSaveAddressFromPlace(place);
    });
}

function reverseGeocodeAndSave(latlng) {
    const geocoder = new google.maps.Geocoder();
    geocoder.geocode({ location: latlng }, (results, status) => {
        if (status === "OK" && results[0]) {
            fillAndSaveAddressFromPlace(results[0]);
        }
    });
}

function fillAndSaveAddressFromPlace(place) {
    const components = place.address_components || [];
    const getComponent = (type) =>
        components.find(c => c.types.includes(type))?.long_name || "";

    const street = [getComponent("street_number"), getComponent("route")].filter(Boolean).join(" ");
    const pincode = getComponent("postal_code");
    const landmark = getComponent("sublocality") || getComponent("locality") || "";

    document.getElementById("inputAddress").value = street || (place.formatted_address?.split(",")[0] || "");
    document.getElementById("inputPincode").value = pincode || "";
    if (landmark && !document.getElementById("inputLandmark").value) {
        document.getElementById("inputLandmark").value = "Near " + landmark;
    }

    saveAddressPreview();
}

function saveAddressPreview() {
    document.getElementById("name").innerText = document.getElementById("inputName").value;
    document.getElementById("address").innerText = document.getElementById("inputAddress").value;
    document.getElementById("pincode").innerText = document.getElementById("inputPincode").value;
    document.getElementById("landmark").innerText = document.getElementById("inputLandmark").value;
    document.getElementById("email").innerText = document.getElementById("inputEmail").value;
    document.getElementById("phone").innerText = document.getElementById("inputPhone").value;
    
    if (document.getElementById("addressDisplay").style.display === "block") {
        document.getElementById("addressDisplay").style.display = "block";
    }
}
function useCurrentLocationForMap() {
    if (!navigator.geolocation) {
        alert("Geolocation not supported.");
        return;
    }

    // ⚠️ Only use Google if it's loaded
    if (typeof google === 'undefined') {
        alert("Map is still loading... Please wait or try 'Pick on Map' first.");
        return;
    }

    navigator.geolocation.getCurrentPosition(
        (position) => {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            const latLng = { lat, lng };

            if (!googleMap) {
                googleMap = new google.maps.Map(document.getElementById("googleMap"), {
                    zoom: 16,
                    center: latLng,
                    disableDefaultUI: true,
                    zoomControl: true,
                    streetViewControl: false
                });
                googleMarker = new google.maps.Marker({
                    position: latLng,
                    map: googleMap,
                    draggable: true
                });
                googleMarker.addListener("dragend", (event) => {
                    reverseGeocodeAndSave(event.latLng);
                });
            } else {
                googleMap.setCenter(latLng);
                googleMap.setZoom(16);
                googleMarker.setPosition(latLng);
                reverseGeocodeAndSave(latLng);
            }
            document.getElementById("mapPreview").style.display = "block";
        },
        (error) => {
            let msg = "Unable to get location.";
            if (error.code === 1) msg = "Please allow location access in your browser.";
            alert(msg);
        }
    );
}
</script>

{{-- Conditionally load Google Maps API --}}
@if(env('GOOGLE_MAPS_ENABLED', false))
<script>
    const GOOGLE_MAPS_API_KEY = "{{ env('GOOGLE_MAPS_API_KEY') }}";
    if (GOOGLE_MAPS_API_KEY.trim()) {
        const script = document.createElement('script');
        script.src = `https://maps.googleapis.com/maps/api/js?key=${GOOGLE_MAPS_API_KEY}&libraries=places&loading=async&callback=initGoogleMap`;
        script.async = true;
        script.defer = true;
        document.head.appendChild(script);
    }
</script>
@endif

 <!-- DEBUG -->
@if(env('GOOGLE_MAPS_ENABLED'))
    <div style="color:red; font-weight:bold;">✅ Google Maps ENABLED</div>
@else
    <div style="color:red; font-weight:bold;">❌ Google Maps DISABLED</div>
@endif
</body>
</html>
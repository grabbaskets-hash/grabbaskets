<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Checkout</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
  <style>
    body {
      background: #f8f9fa;
    }

    .card {
      border-radius: 1rem;
    }

    .btn-orange {
      background-color: #ff9900;
      color: #232f3e;
      font-weight: bold;
    }

    .btn-orange:hover {
      background-color: #e68a00;
      color: #232f3e;
    }
  </style>
</head>

<body>
  <x-back-button />

  <!-- Navbar -->
  <nav class="navbar navbar-light bg-white shadow-sm">
    <div class="container-fluid">
      <a class="navbar-brand fw-bold text-dark" href="#">
        <span class="material-icons align-middle text-warning">storefront</span> MyShop
      </a>
      <div class="d-flex">
        <a href="/cart" class="btn btn-outline-dark d-flex align-items-center gap-1">
          <span class="material-icons">shopping_cart</span> Cart
        </a>
      </div>
    </div>
  </nav>

  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-lg-8">

        <div class="card shadow border-0">
          <div class="card-body p-5">
            <div class="text-center mb-4">
              <span class="material-icons" style="font-size:3rem;color:#ff9900;">shopping_cart_checkout</span>
              <h2 class="fw-bold mt-2 mb-0" style="color:#232f3e;">Checkout</h2>
            </div>

            <form id="checkout-form" method="POST" action="{{ route('cart.checkout') }}">
              @csrf
              <div class="row g-4 mb-4">
                <div class="col-md-6">
                  <div class="card p-3 mb-3">
                    <h5 class="fw-semibold mb-3">
                      <span class="material-icons align-middle text-primary">location_on</span> Delivery Address
                    </h5>

                    <div class="mb-3">
                      <label class="form-label">
                        <span class="material-icons align-middle text-secondary">home</span> Select Address
                      </label>
                      <select name="address" class="form-select">
                        @foreach($addresses as $addr)
                          <option value="{{ $addr }}">{{ $addr }}</option>
                        @endforeach
                      </select>
                    </div>

                    <div class="mb-3">
                      <label class="form-label">
                        <span class="material-icons align-middle text-info">edit_location_alt</span> Or enter new
                        address
                      </label>
                      <input type="text" name="new_address"
                        class="form-control @error('new_address') is-invalid @enderror" placeholder="Flat, Street, Area"
                        value="{{ old('new_address') }}">
                      @error('new_address') <div class="invalid-feedback">{{ $message }}</div> @enderror

                    </div>

                    <div class="mb-3">
                      <label class="form-label">
                        <span class="material-icons align-middle text-warning">apartment</span> Address Type
                      </label>
                      <select name="address_type" class="form-select @error('address_type') is-invalid @enderror">
                        <option value="home" {{ old('address_type') == 'home' ? 'selected' : '' }}>Home</option>
                        <option value="office" {{ old('address_type') == 'office' ? 'selected' : '' }}>Office</option>
                        <option value="other" {{ old('address_type') == 'other' ? 'selected' : '' }}>Other</option>
                      </select>
                      @error('address_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <button type="button"
                      class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center gap-2"
                      onclick="getLocation()">
                      <span class="material-icons">my_location</span> Use Current Location
                    </button>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="card p-3 mb-3">
                    <h5 class="fw-semibold mb-3">
                      <span class="material-icons align-middle text-success">map</span> Location Details
                    </h5>

                    <div class="mb-3">
                      <label class="form-label">City</label>
                      <input type="text" name="city" id="city" class="form-control @error('city') is-invalid @enderror"
                        placeholder="City" value="{{ old('city', $user->city) }}">
                      @error('city') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                      <label class="form-label">State</label>
                      <input type="text" name="state" id="state"
                        class="form-control @error('state') is-invalid @enderror" placeholder="State"
                        value="{{ old('state', $user->state) }}">
                      @error('state') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                      <label class="form-label">Pincode</label>
                      <input type="text" name="pincode" id="pincode"
                        class="form-control @error('pincode') is-invalid @enderror" placeholder="Pincode"
                        value="{{ old('pincode', $user->pincode) }}">
                      @error('pincode') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                  </div>
                </div>

              </div>

              <div class="row g-4 mb-4">

                <div class="col-md-6">
                  <div class="card p-3 mb-3">
                    <h5 class="fw-semibold mb-3">
                      <span class="material-icons align-middle text-indigo-700">receipt_long</span> Order Summary
                    </h5>

                    <ul class="list-group mb-3 rounded-3 shadow-sm">
                      @foreach($items as $item)
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                          <div class="flex-grow-1">
                            <span class="fw-semibold">{{ $item->product->name }} x {{ $item->quantity }}</span>
                            @if($item->product->gift_option === 'yes')
                              <div class="text-success small">
                                <span class="material-icons" style="font-size: 16px;">card_giftcard</span> Gift option
                                available
                              </div>
                            @endif
                          </div>
                          <span class="fw-bold">₹{{ number_format($item->price * $item->quantity, 2) }}</span>
                        </li>
                      @endforeach
                    </ul>

                    <div class="mb-2"><span class="material-icons align-middle text-muted">calculate</span> Subtotal:
                      ₹{{ number_format($totals['subtotal'], 2) }}</div>
                    <div class="mb-2"><span class="material-icons align-middle text-danger">discount</span> Discount:
                      -₹{{ number_format($totals['discountTotal'], 2) }}</div>
                    <div class="mb-2"><span class="material-icons align-middle text-info">local_shipping</span>
                      Delivery: ₹{{ number_format($totals['deliveryTotal'], 2) }}</div>
                    <div class="fw-bold"><span class="material-icons align-middle text-success">payments</span> Total:
                      ₹{{ number_format($totals['total'], 2) }}</div>
                  </div>
                </div>
              </div>

              <div class="col-md-6">
                <div class="card p-3 mb-3">
                  <h5 class="fw-semibold mb-3">
                    <span class="material-icons align-middle text-warning">credit_card</span> Payment Method
                  </h5>

                  <div class="mb-3">
                    <select name="payment_method" class="form-select" id="payment-method">
                      <option value="razorpay">Razorpay (Card/UPI/Wallet)</option>
                      <option value="cod">Cash on Delivery</option>
                    </select>
                  </div>

                  <div id="payment-gateway" class="mb-3"></div>

                  <button type="button" class="btn btn-orange w-100 py-2" id="place-order-btn">
                    <span class="material-icons">shopping_bag</span> <span id="btn-text">Place Order</span>
                  </button>
                </div>
              </div>

          </div>

          </form>

        </div>
      </div>

    </div>
  </div>
  </div>

  <script>
    const select = document.getElementById('payment-method');
    const gatewayDiv = document.getElementById('payment-gateway');
    const placeOrderBtn = document.getElementById('place-order-btn');
    const btnText = document.getElementById('btn-text');

    if (select && gatewayDiv) {
      select.addEventListener('change', function () {
        if (this.value === 'razorpay') {
          gatewayDiv.innerHTML = '<div class="alert alert-info"><span class="material-icons">info</span> Secure payment with Razorpay</div>';
          btnText.textContent = 'Pay with Razorpay';
        } else if (this.value === 'cod') {
          gatewayDiv.innerHTML = '<div class="alert alert-warning"><span class="material-icons">local_shipping</span> Pay when your order is delivered</div>';
          btnText.textContent = 'Place Order (COD)';
        }
      });

      // Trigger change event on page load
      select.dispatchEvent(new Event('change'));
    }

    // Handle place order button click
    placeOrderBtn.addEventListener('click', function () {
      const paymentMethod = select.value;

      if (paymentMethod === 'razorpay') {
        initiateRazorpayPayment();
      } else {
        // For COD, submit the form normally
        document.getElementById('checkout-form').submit();
      }
    });

    function initiateRazorpayPayment() {
      // Show loading
      placeOrderBtn.disabled = true;
      btnText.textContent = 'Processing...';

      // Get form data
      const formData = new FormData(document.getElementById('checkout-form'));

      // Create Razorpay order
      fetch('{{ route("payment.createOrder") }}', {
        method: 'POST',
        body: formData,
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
      })
        .then(response => response.json())
        .then(data => {
          if (data.error) {
            alert(data.error);
            placeOrderBtn.disabled = false;
            btnText.textContent = 'Pay with Razorpay';
            return;
          }

          // Initialize Razorpay
          const options = {
            key: '{{ config("services.razorpay.key") }}',
            amount: data.amount,
            currency: data.currency,
            name: data.name,
            description: data.description,
            order_id: data.order_id,
            prefill: data.prefill,
            theme: {
              color: '#ff9900'
            },
            handler: function (response) {
              verifyPayment(response);
            },
            modal: {
              ondismiss: function () {
                placeOrderBtn.disabled = false;
                btnText.textContent = 'Pay with Razorpay';
              }
            }
          };

          const rzp = new Razorpay(options);
          rzp.open();
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Payment initialization failed. Please try again.');
          placeOrderBtn.disabled = false;
          btnText.textContent = 'Pay with Razorpay';
        });
    }

    function verifyPayment(paymentData) {
      fetch('{{ route("payment.verify") }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(paymentData)
      })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            alert(data.message);
            window.location.href = data.redirect;
          } else {
            alert(data.error || 'Payment verification failed');
            placeOrderBtn.disabled = false;
            btnText.textContent = 'Pay with Razorpay';
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Payment verification failed. Please contact support.');
          placeOrderBtn.disabled = false;
          btnText.textContent = 'Pay with Razorpay';
        });
    }

    function getLocation() {
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (pos) {
          const lat = pos.coords.latitude;
          const lon = pos.coords.longitude;
          fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}`)
            .then(res => res.json())
            .then(data => {
              if (data.address) {
                document.querySelector('input[name="new_address"]').value = data.display_name || '';
                document.getElementById('city').value = data.address.city || data.address.town || data.address.village || '';
                document.getElementById('state').value = data.address.state || '';
                document.getElementById('pincode').value = data.address.postcode || '';
              }
            });
        }, function () {
          alert('Unable to fetch location.');
        });
      } else {
        alert('Geolocation is not supported by your browser.');
      }
    }
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
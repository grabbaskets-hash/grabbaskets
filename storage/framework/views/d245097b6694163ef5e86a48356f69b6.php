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
  <?php if (isset($component)) { $__componentOriginal5c84f04e4e4c3f6b2afa5416a6776687 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5c84f04e4e4c3f6b2afa5416a6776687 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.back-button','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('back-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5c84f04e4e4c3f6b2afa5416a6776687)): ?>
<?php $attributes = $__attributesOriginal5c84f04e4e4c3f6b2afa5416a6776687; ?>
<?php unset($__attributesOriginal5c84f04e4e4c3f6b2afa5416a6776687); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5c84f04e4e4c3f6b2afa5416a6776687)): ?>
<?php $component = $__componentOriginal5c84f04e4e4c3f6b2afa5416a6776687; ?>
<?php unset($__componentOriginal5c84f04e4e4c3f6b2afa5416a6776687); ?>
<?php endif; ?>

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

            <form id="checkout-form" method="POST" action="<?php echo e(route('cart.checkout')); ?>">
              <?php echo csrf_field(); ?>
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
                        <?php $__currentLoopData = $addresses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $addr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                          <option value="<?php echo e($addr); ?>"><?php echo e($addr); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                      </select>
                    </div>

                    <div class="mb-3">
                      <label class="form-label">
                        <span class="material-icons align-middle text-info">edit_location_alt</span> Or enter new
                        address
                      </label>
                      <input type="text" name="new_address"
                        class="form-control <?php $__errorArgs = ['new_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Flat, Street, Area"
                        value="<?php echo e(old('new_address')); ?>">
                      <?php $__errorArgs = ['new_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                    </div>

                    <div class="mb-3">
                      <label class="form-label">
                        <span class="material-icons align-middle text-warning">apartment</span> Address Type
                      </label>
                      <select name="address_type" class="form-select <?php $__errorArgs = ['address_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <option value="home" <?php echo e(old('address_type') == 'home' ? 'selected' : ''); ?>>Home</option>
                        <option value="office" <?php echo e(old('address_type') == 'office' ? 'selected' : ''); ?>>Office</option>
                        <option value="other" <?php echo e(old('address_type') == 'other' ? 'selected' : ''); ?>>Other</option>
                      </select>
                      <?php $__errorArgs = ['address_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
                      <input type="text" name="city" id="city" class="form-control <?php $__errorArgs = ['city'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                        placeholder="City" value="<?php echo e(old('city', $user->city)); ?>">
                      <?php $__errorArgs = ['city'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="mb-3">
                      <label class="form-label">State</label>
                      <input type="text" name="state" id="state"
                        class="form-control <?php $__errorArgs = ['state'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="State"
                        value="<?php echo e(old('state', $user->state)); ?>">
                      <?php $__errorArgs = ['state'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="mb-3">
                      <label class="form-label">Pincode</label>
                      <input type="text" name="pincode" id="pincode"
                        class="form-control <?php $__errorArgs = ['pincode'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Pincode"
                        value="<?php echo e(old('pincode', $user->pincode)); ?>">
                      <?php $__errorArgs = ['pincode'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
                      <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                          <div class="flex-grow-1">
                            <span class="fw-semibold"><?php echo e($item->product->name); ?> x <?php echo e($item->quantity); ?></span>
                            <?php if($item->product->gift_option === 'yes'): ?>
                              <div class="text-success small">
                                <span class="material-icons" style="font-size: 16px;">card_giftcard</span> Gift option
                                available
                              </div>
                            <?php endif; ?>
                          </div>
                          <span class="fw-bold">₹<?php echo e(number_format($item->price * $item->quantity, 2)); ?></span>
                        </li>
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>

                    <div class="mb-2"><span class="material-icons align-middle text-muted">calculate</span> Subtotal:
                      ₹<?php echo e(number_format($totals['subtotal'], 2)); ?></div>
                    <div class="mb-2"><span class="material-icons align-middle text-danger">discount</span> Discount:
                      -₹<?php echo e(number_format($totals['discountTotal'], 2)); ?></div>
                    <div class="mb-2"><span class="material-icons align-middle text-info">local_shipping</span>
                      Delivery: ₹<?php echo e(number_format($totals['deliveryTotal'], 2)); ?></div>
                    <div class="fw-bold"><span class="material-icons align-middle text-success">payments</span> Total:
                      ₹<?php echo e(number_format($totals['total'], 2)); ?></div>
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
      fetch('<?php echo e(route("payment.createOrder")); ?>', {
        method: 'POST',
        body: formData,
        headers: {
          'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
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
            key: '<?php echo e(config("services.razorpay.key")); ?>',
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
      fetch('<?php echo e(route("payment.verify")); ?>', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
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

</html><?php /**PATH E:\e-com_updated_final\e-com_updated\resources\views/cart/checkout.blade.php ENDPATH**/ ?>
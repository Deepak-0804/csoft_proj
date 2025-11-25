<?php

if (!defined('APP_INIT')) {
    http_response_code(403);
    exit("Access denied");
}

$data = CartService::getCartData();
extract($data);

?>


<!-- Place this near the top of cart.php -->
<div class="container mt-4">
    <!-- Step Indicator -->
    <div class="d-flex justify-content-between align-items-center text-center">
        <div class="step active flex-fill" data-step="cart">
            <div class="step-circle rounded-circle bg-primary text-white mx-auto" style="width:40px;height:40px;line-height:40px;">1</div>
            <small class="d-block mt-2 fw-bold text-primary">Cart</small>
        </div>
        <div class="flex-fill border-top mx-2"></div>

        <div class="step inactive flex-fill" data-step="address">
            <div class="step-circle rounded-circle bg-light text-dark mx-auto" style="width:40px;height:40px;line-height:40px;">2</div>
            <small class="d-block mt-2">Address</small>
        </div>
        <div class="flex-fill border-top mx-2"></div>

        <div class="step inactive flex-fill" data-step="payment">
            <div class="step-circle rounded-circle bg-light text-dark mx-auto" style="width:40px;height:40px;line-height:40px;">3</div>
            <small class="d-block mt-2">Payment</small>
        </div>
    </div>

    <!-- Step Content -->
    <div class="content mt-4 bg-white p-4 rounded">

        <!-- CART STEP -->
        <div id="cart" class="step-content">
            <?php
            if (!empty($_SESSION['cart'])) {


                $result = $resultFull;

            ?>
                <div class="container bg-white shadow-sm rounded-4 p-4 mb-5" style="max-width: 900px;">
                    <h5 class="mb-5 fw-bold text-primary">Your Cart</h5>

                    <?php
                    $total = 0;
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)):
                        $productId = $row['id'];
                        $quantity = $_SESSION['cart'][$productId]['quantity'];
                        $subtotal = $row['discounted_price'] * $quantity;
                        $total += $subtotal;
                        $totalsubtotal = $row['original_price'] * $quantity;
                    ?>
                        <div class="card mb-3 border-0 border-bottom pb-3">
                            <div class="row g-3 align-items-center">
                                <div class="col-md-2 text-center">
                                    <img src="<?= htmlspecialchars($row['image']) ?>"
                                        alt="<?= htmlspecialchars($row['name']) ?>"
                                        class="img-fluid rounded-2" style="max-height:80px; border: 2px solid #dee2e6;">
                                </div>
                                <div class="col-md-6">
                                    <h6 class="mb-1 fw-semibold"><?= htmlspecialchars($row['name']) ?></h6>
                                    <p class="text-muted small mb-1"><?= htmlspecialchars($row['description']) ?></p>
                                    <span class="text-muted fw-normal fs-6">
                                        <?= htmlspecialchars($row['base_quantity']) . ' ' . htmlspecialchars($row['order_unit']) ?>
                                    </span>
                                </div>
                                <div class="col-md-4 text-center">
                                    <div class="d-flex justify-content-center align-items-center" style="gap: 1.5rem;">
                                        <!-- Quantity Controls -->
                                        <div class="cart-quantity-controls d-inline-flex justify-content-center align-items-center">
                                            <button class="btn btn-outline-secondary decrease-qty me-2" data-product-id="<?= $productId ?>">−</button>
                                            <span id="qty-<?= $productId ?>" class="fw-bold fs-6"><?= $quantity ?></span>
                                            <button class="btn btn-outline-secondary increase-qty ms-2" data-product-id="<?= $productId ?>">+</button>
                                        </div>

                                        <!-- Price Column -->
                                        <div class="price-info d-inline-flex flex-column align-items-start">
                                            <p class="mb-0 fw-bold text-success small">
                                                ₹ <?= number_format($subtotal) ?>
                                            </p>
                                            <p class="mb-0 text-muted text-decoration-line-through small">
                                                ₹ <?= number_format($totalsubtotal) ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>
                    <?php endwhile; ?>

                    <div class="text-end mt-3">
                        <h5 class="fw-bold total-amount">Total: ₹ <?= number_format($total) ?></h5>
                        <button class="btn btn-primary next-step mt-2" id="proceedAddressBtn">Proceed to Address</button>

                    </div>

                </div>
            <?php
            } else {
                echo "<div class='alert alert-info mt-3'>Your cart is empty.</div>";
            }
            ?>

        </div>

        <!-- ADDRESS STEP -->
        <div id="address" class="step-content d-none">
            <h5 class="fw-bold mb-5 text-primary">Shipping Address</h5>

            <div class="row g-4">
                <!-- Left: Address Form -->
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm p-4 rounded-4">
                        <form id="addressForm" method="post" action="save_address.php" novalidate>
                            <div class="row">
                                <!-- Full Name -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" name="full_name" class="form-control" placeholder="John Doe" required>
                                </div>

                                <!-- Phone -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Telephone / Mobile <span class="text-danger">*</span></label>
                                    <input type="tel" name="phone" class="form-control" placeholder="0000000000" required>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Email -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control" placeholder="you@example.com" required>
                                </div>

                                <!-- State -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">State <span class="text-danger">*</span></label>
                                    <select name="state" id="state" class="form-select" required>
                                        <option value="">Select State</option>
                                        <?php foreach ($states as $s): ?>
                                            <option value="<?= $s['StateID'] ?>"><?= htmlspecialchars($s['StateName']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Zip -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Zip / Postal Code <span class="text-danger">*</span></label>
                                    <input type="text" name="zip" class="form-control" placeholder="500001" required>
                                </div>

                                <!-- District -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">District <span class="text-danger">*</span></label>
                                    <select name="district" id="district" class="form-select" required>
                                        <option value="">Select District</option>
                                        <?php foreach ($districts as $d): ?>
                                            <option value="<?= $d['DistrictID'] ?>"><?= htmlspecialchars($d['DistrictName']) ?></option>
                                        <?php endforeach; ?>

                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Flat/Building -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Flat / House No. / Floor / Building <span class="text-danger">*</span></label>
                                    <input type="text" name="flat" class="form-control" placeholder="Flat 204, Green Apartments" required>
                                </div>

                                <!-- Colony / Street -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Colony / Street / Locality <span class="text-danger">*</span></label>
                                    <input type="text" name="colony" class="form-control" placeholder="Madhapur Main Road" required>
                                </div>
                            </div>

                            <!-- Address Type -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Address Type</label>
                                <select name="address_type" class="form-select">
                                    <option value="">Select Type</option>
                                    <?php foreach ($addressTypes as $a): ?>
                                        <option value="<?= $a['AddressTypeID'] ?>"><?= htmlspecialchars($a['AddressTypeName']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>


                            <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                                <button class="btn btn-secondary prev-step px-4" data-prev="cart">← Back</button>
                                <button type="submit" class="btn btn-primary next-step px-4" data-next="payment">Proceed to Payment →</button>
                            </div>
                        </form>
                    </div>
                </div>


                <!-- Right: Cart Summary -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm p-4 rounded-4">
                        <h6 class="fw-bold mb-5 text-primary">Items in Your Cart</h6>

                        <div id="cartSummary" class="small">
                            <?php
                            $total = 0;
                            if (!empty($_SESSION['cart'])) {

                                $result = $resultFull;
                                $result->execute(); // <<< add this


                                while ($row = $result->fetch(PDO::FETCH_ASSOC)):
                                    $productId = $row['id'];
                                    $quantity = $_SESSION['cart'][$productId]['quantity'];
                                    $subtotal = $row['discounted_price'] * $quantity;
                                    $total += $subtotal;
                            ?>
                                    <div class="d-flex align-items-center mb-3">
                                        <img src="<?= $BASE . '/' . htmlspecialchars($row['image']) ?>">
                                        alt="<?= htmlspecialchars($row['name']) ?>"
                                        class="rounded-3 me-3"
                                        style="width: 50px; height: 50px; object-fit: cover; border: 1px solid #dee2e6;">
                                        <div class="flex-grow-1">
                                            <div class="fw-semibold"><?= htmlspecialchars($row['name']) ?></div>
                                            <div class="text-muted">
                                                <?= htmlspecialchars($row['base_quantity']) . ' ' . htmlspecialchars($row['order_unit']) ?> × <?= $quantity ?>
                                            </div>
                                        </div>
                                        <div class="fw-bold text-success">₹ <?= number_format($subtotal) ?></div>
                                    </div>
                                    <hr class="my-2">
                            <?php
                                endwhile;
                            } else {
                                echo "<p class='text-muted'>Your cart is empty.</p>";
                            }
                            ?>
                        </div>

                        <div class="d-flex justify-content-between fw-bold mt-3">
                            <span>Total</span>
                            <span>₹ <?= number_format($total) ?></span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Bottom Buttons -->

        </div>

        <!-- PAYMENT STEP -->
        <div id="payment" class="step-content d-none">
            <h5 class="mb-3">Payment Method</h5>

            <!-- Main Option -->
            <div class="form-check mb-2">
                <label class="form-check-label d-flex justify-content-between align-items-center collapsed"
                    for="upi"
                    data-bs-toggle="collapse"
                    data-bs-target="#upiCollapse"
                    aria-expanded="false"
                    aria-controls="upiCollapse">

                    <div class="d-flex align-items-center gap-2">
                        <img src="assets/images/upiimg.png" alt="UPI" width="28">
                        <span>UPI</span>
                    </div>

                    <i class="bi bi-chevron-down transition-arrow"></i>
                </label>
            </div>


            <!-- Collapsible Child Section -->
            <div class="collapse" id="upiCollapse">
                <div class="card card-body border-0 ps-2">
                    <div class="form-check d-flex flex-column mb-3">
                        <div class="d-flex align-items-center gap-2">
                            <input class="form-check-input" type="radio" name="upi_option" id="paypal" checked>
                            <img src="assets/images/paytm.png" alt="Paypal" width="28">
                            <label class="form-check-label" for="paypal">Paypal</label>
                        </div>

                        <button class="btn btn-warning btn-sm pay-btn mt-2" id="paypalBtn"
                            data-order-id="<?php echo $orderId; ?>">
                            Pay ₹ <?php echo number_format($totaal, 2); ?>
                        </button>

                        <div id="paypal-ui" class="collapse-area d-none mt-3">
                            <div id="paypal-button-container"></div>
                            <div id="paypal-message" class="mt-2 text-danger"></div>
                        </div>

                    </div>


                    <div class="form-check d-flex flex-column mb-3">
                        <div class="d-flex align-items-center gap-2">
                            <input class="form-check-input" type="radio" name="upi_option" id="gpay">
                            <img src="assets/images/gpay.png" alt="Google Pay" width="28">
                            <label class="form-check-label" for="gpay">Google Pay</label>
                        </div>
                        <button class="btn btn-warning btn-sm pay-btn mt-2 d-none" id="gpayBtn">
                            Pay ₹ <?php echo number_format($total, 2); ?>
                        </button>

                    </div>


                    <div class="form-check d-flex flex-column mb-3">
                        <div class="d-flex align-items-center gap-2">
                            <input class="form-check-input" type="radio" name="upi_option" id="phonepe">
                            <img src="assets/images/phonepe.png" alt="PhonePe" width="28">
                            <label class="form-check-label" for="phonepe">PhonePe</label>
                        </div>
                        <button class="btn btn-warning btn-sm pay-btn mt-2 d-none" id="phonepeBtn">
                            Pay ₹ <?php echo number_format($total, 2); ?>
                        </button>
                    </div>

                </div>
            </div>

            <!-- Only Back Button -->
            <div class="d-flex justify-content-start mt-3">
                <button class="btn btn-secondary prev-step" data-prev="address">Back</button>
            </div>
        </div>

    </div>
</div>


<script>
    //cart.php 

    const steps = document.querySelectorAll('.step');
    const contents = document.querySelectorAll('.step-content');

    function showStep(stepId) {
        contents.forEach(c => c.classList.add('d-none'));
        const stepContent = document.getElementById(stepId);
        if (stepContent) stepContent.classList.remove('d-none');

        steps.forEach(s => {
            const circle = s.querySelector('.step-circle');
            const label = s.querySelector('small');

            if (!circle || !label) return;

            if (s.dataset.step === stepId) {
                s.classList.add('active');
                s.classList.remove('inactive');
                circle.classList.add('bg-primary', 'text-white');
                circle.classList.remove('bg-light', 'text-dark');
                label.classList.add('fw-bold', 'text-primary');
            } else {
                s.classList.remove('active');
                s.classList.add('inactive');
                circle.classList.add('bg-light', 'text-dark');
                circle.classList.remove('bg-primary', 'text-white');
                label.classList.remove('fw-bold', 'text-primary');
            }
        });
    }

    // Next / Prev step buttons
    document.querySelectorAll('.next-step').forEach(btn =>
        btn.addEventListener('click', (e) => {
            // If this button is inside a form, let the form handle it
            if (btn.closest('form')) {
                return; // ❌ do nothing — form's submit handler will decide
            }
            // Otherwise, proceed normally
            showStep(btn.dataset.next);
        })
    );

    document.querySelectorAll('.prev-step').forEach(btn =>
        btn.addEventListener('click', () => showStep(btn.dataset.prev))
    );

    // ✅ Proceed to Address button (moved inside same block)
    const proceedBtn = document.getElementById('proceedAddressBtn');
    if (proceedBtn) {
        proceedBtn.addEventListener('click', async function() {
            // ✅ Check if user session still valid
            try {
                const authResponse = await fetch('check_auth.php', {
                    cache: 'no-store'
                });
                const auth = await authResponse.json();

                if (!auth.logged_in) {
                    toastr.error(auth.reason === 'expired' ?
                        "Session expired. Please log in again." :
                        "You must log in first."
                    );
                    window.location.href = "<?php echo $BASE; ?>/index.php?page=login";
                    return;
                }

                // continue normal sync + showStep
            } catch (err) {
                console.error("Auth check failed:", err);
                toastr.error("Something went wrong. Please log in again.");
                window.location.href = "<?php echo $BASE; ?>/index.php?page=login";
            }


            fetch('synccart.php', {
                    method: 'POST'
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {

                        // ✅ Update right-side cart summary dynamically
                        const summary = document.getElementById('cartSummary');
                        summary.innerHTML = ''; // clear old data

                        data.items.forEach(item => {
                            summary.innerHTML += `
                            <div class="d-flex align-items-center mb-3">
                                <img src="../${item.image}" 
                                    alt="${item.name}" 
                                    class="rounded-3 me-3"
                                    style="width: 50px; height: 50px; object-fit: cover; border: 1px solid #dee2e6;">
                                <div class="flex-grow-1">
                                    <div class="fw-semibold">${item.name}</div>
                                    <div class="text-muted">${item.base_quantity} ${item.order_unit} × ${item.quantity}</div>
                                </div>
                                <div class="fw-bold text-success">₹ ${item.subtotal.toLocaleString()}</div>
                            </div>
                            <hr class="my-2">
                        `;
                        });

                        // ✅ Update total value
                        const totalElem = document.querySelector('.fw-bold.mt-3 span:last-child');
                        if (totalElem) totalElem.textContent = `₹ ${data.total.toLocaleString()}`;

                        // ✅ Move to address step
                        showStep('address');
                    } else {
                        alert(data.message);
                    }
                })
                .catch(err => console.error('Sync error:', err));
        });
    }


    // ✅ Handle address form submission
    const addressForm = document.getElementById('addressForm');

    addressForm.addEventListener('submit', async function(e) {

        const clickedButton = e.submitter; // find which button triggered submit
        if (clickedButton && clickedButton.classList.contains('prev-step')) {
            // ✅ If it's the Back button, just go back — don't save address
            e.preventDefault();
            showStep('cart');
            return;
        }

        e.preventDefault(); // stop normal form submit only for forward button

        // Step 1: make sure the form is visible now
        if (addressForm.classList.contains('d-none')) {
            toastr.warning("Please open the address step before submitting.");
            return;
        }

        // Step 2: check for empty required fields
        const requiredFields = addressForm.querySelectorAll('[required]');
        for (const field of requiredFields) {
            if (!field.value.trim()) {
                const label = field.closest('.mb-3')?.querySelector('label')?.innerText || field.name;
                toastr.warning(`Please fill out the "${label.replace('*', '').trim()}" field.`);
                setTimeout(() => field.focus(), 500); // wait half a second

                return; // stop checking further
            }
        }

        try {
            const authResponse = await fetch('check_auth.php', {
                cache: 'no-store'
            });
            const auth = await authResponse.json();

            if (!auth.logged_in) {
                toastr.error(auth.reason === 'expired' ?
                    "Session expired. Please log in again." :
                    "You must log in first."
                );
                window.location.href = "<?php echo $BASE; ?>/index.php?page=login";
                return;
            }

            // continue normal sync + showStep
        } catch (err) {
            console.error("Auth check failed:", err);
            toastr.error("Something went wrong. Please log in again.");
            window.location.href = "<?php echo $BASE; ?>/index.php?page=login";
        }



        // Step 3: collect and send data
        const formData = new FormData(addressForm);
        try {
            const response = await fetch('save_address.php', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                toastr.success("Address saved successfully!");
                showStep('payment'); // ✅ updates circle highlight + visibility


            } else {
                toastr.error(result.message || "Error saving address.");
            }
        } catch (error) {
            toastr.error("Something went wrong while saving.");
            console.error(error);
        }
    });




    document.addEventListener('click', function(e) {
        // Check if user clicked + or −
        if (e.target.classList.contains('increase-qty') || e.target.classList.contains('decrease-qty')) {
            const productId = e.target.dataset.productId;
            const action = e.target.classList.contains('increase-qty') ? 'increase' : 'decrease';

            fetch('update_cart.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        action,
                        product_id: productId
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        const qtyElem = document.querySelector(`#qty-${productId}`);
                        const priceInfo = qtyElem.closest('.col-md-4').querySelector('.price-info');

                        // Update quantity
                        if (data.quantity > 0) {
                            qtyElem.textContent = data.quantity;

                            // Update subtotals dynamically
                            if (priceInfo && data.subtotal && data.totalsubtotal) {
                                priceInfo.innerHTML = `
              <p class="mb-0 fw-bold text-success small">₹ ${data.subtotal.toLocaleString()}</p>
              <p class="mb-0 text-muted text-decoration-line-through small">₹ ${data.totalsubtotal.toLocaleString()}</p>
            `;
                                // Update overall total dynamically
                                const totalElem = document.querySelector('.total-amount');
                                if (totalElem && data.total !== undefined) {
                                    totalElem.innerHTML = `Total: ₹ ${data.total.toLocaleString()}`;
                                }

                            }
                        } else {
                            // Quantity is 0 → remove the product from cart visually
                            const card = qtyElem.closest('.card');
                            card.style.transition = 'opacity 0.4s ease';
                            card.style.opacity = 0;

                            setTimeout(() => {
                                card.remove();
                                location.reload(); // ✅ reload page to refresh cart UI and totals
                            }, 400);
                        }


                        // Update cart badge in navbar
                        const cartBadge = document.querySelector('.nav-link .badge.bg-danger');
                        if (cartBadge && data.cartCount !== undefined) {
                            cartBadge.textContent = data.cartCount;
                        }
                    }
                })
                .catch(err => console.error('Error updating cart:', err));
        }
    });


    document.querySelectorAll('input[name="upi_option"]').forEach(radio => {
        radio.addEventListener('change', () => {
            document.querySelectorAll('.pay-btn').forEach(btn => btn.classList.add('d-none'));
            const selected = radio.id + 'Btn';
            document.getElementById(selected).classList.remove('d-none');
        });
    });


    // --- Control visibility of PayPal UI ---
    const paypalRadio = document.getElementById('paypal');
    const paypalBtn = document.getElementById('paypalBtn');
    const paypalUI = document.getElementById('paypal-ui');

    // When PayPal radio is selected → show Pay button
    paypalRadio.addEventListener('change', () => {
        if (paypalRadio.checked) {
            paypalBtn.classList.remove('d-none');
            paypalUI.classList.add('d-none'); // hide PayPal area until click
        }
    });

    paypalBtn.addEventListener('click', async function() {
        paypalUI.classList.remove('d-none'); // show the button container + message

        const orderId = this.dataset.orderId;
        const messageEl = document.getElementById('paypal-message');
        messageEl.innerText = "";

        // 🔒 Wait until PayPal SDK is ready
        async function waitForPayPal() {
            for (let i = 0; i < 20; i++) {
                if (window.paypal && typeof paypal.Buttons === "function") return true;
                await new Promise(r => setTimeout(r, 200));
            }
            throw new Error("PayPal SDK not loaded");
        }

        try {
            await waitForPayPal();

            const res = await fetch('create_order.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `order_id=${orderId}`
            });

            const data = await res.json();
            if (data.error) {
                messageEl.innerText = data.error;
                return;
            }

            console.log('✅ PayPal order created:', data);

            document.getElementById('paypal-button-container').innerHTML = "";

            paypal.Buttons({
                createOrder: function() {
                    return data.paypal_order_id;
                },
                onApprove: function(data, actions) {
                    window.location.href = "<?php echo $BASE; ?>/paypalsuccess.php?token=" + data.orderID;

                },
                onCancel: function() {
                    window.location.href = "<?php echo $BASE; ?>/paypalcancel.php";

                },
                onError: function(err) {
                    console.error(err);
                    messageEl.innerText = "PayPal error occurred.";
                }
            }).render('#paypal-button-container');

        } catch (err) {
            console.error("❌ PayPal flow error:", err);
            messageEl.innerText = "PayPal SDK failed to load.";
        }
    });
</script>
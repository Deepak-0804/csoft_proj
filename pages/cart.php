<?php

$pdo = $GLOBALS['pdo'];        // Use the global PDO instance
$config = $GLOBALS['config'];  // Use global config

require_auth();  // forces login

$BASE = rtrim($config['base_url'], '/');

if (!defined('APP_INIT')) {
    http_response_code(403);
    exit("Access denied");
}


$totaal = 0;
if (!empty($_SESSION['cart'])) {
    $productIds = array_keys($_SESSION['cart']);
    $idsString = implode(',', array_map('intval', $productIds));
    $sql = "SELECT id, discounted_price FROM products WHERE id IN ($idsString)";
    $result = $pdo->query($sql);
    while ($r = $result->fetch(PDO::FETCH_ASSOC)) {
        $pid = $r['id'];
        $qty = $_SESSION['cart'][$pid]['quantity'] ?? 0;
        $totaal += $r['discounted_price'] * $qty;
    }
}

$orderId = $_SESSION['current_order_id'] ?? null;


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
                $productIds = array_keys($_SESSION['cart']);
                $idsString = implode(',', array_map('intval', $productIds));

                $query = "SELECT id, name, description, original_price ,discounted_price, image, base_quantity ,order_unit FROM products WHERE id IN ($idsString)";
                $result = $pdo->query($query);
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
                                    <img src="../<?= htmlspecialchars($row['image']) ?>"
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
                                        <?php
                                        $stmt = $pdo->query("SELECT StateID, StateName FROM MasState WHERE Active = 1 ORDER BY StateName");
                                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                            echo "<option value='{$row['StateID']}'>" . htmlspecialchars($row['StateName']) . "</option>";
                                        }
                                        ?>
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
                                        <?php
                                        $stmt = $pdo->query("SELECT DistrictID, DistrictName FROM MasDistrict WHERE Active = 1 ORDER BY DistrictName");
                                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                            echo "<option value='{$row['DistrictID']}'>" . htmlspecialchars($row['DistrictName']) . "</option>";
                                        }
                                        ?>
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
                                    <?php
                                    $stmt = $pdo->query("SELECT AddressTypeID, AddressTypeName FROM AddressType ORDER BY AddressTypeName");
                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                        echo "<option value='{$row['AddressTypeID']}'>" . htmlspecialchars($row['AddressTypeName']) . "</option>";
                                    }
                                    ?>
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
                                $productIds = array_keys($_SESSION['cart']);
                                $idsString = implode(',', array_map('intval', $productIds));
                                $query = "SELECT id, name, discounted_price, image, base_quantity, order_unit FROM products WHERE id IN ($idsString)";
                                $result = $pdo->query($query);

                                while ($row = $result->fetch(PDO::FETCH_ASSOC)):
                                    $productId = $row['id'];
                                    $quantity = $_SESSION['cart'][$productId]['quantity'];
                                    $subtotal = $row['discounted_price'] * $quantity;
                                    $total += $subtotal;
                            ?>
                                    <div class="d-flex align-items-center mb-3">
                                        <img src="<?= htmlspecialchars($row['image']) ?>"
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


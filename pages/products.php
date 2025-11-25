<?php
if (!defined('APP_INIT')) {
    http_response_code(403);
    exit("Access denied");
}

if (is_array($data)) {
    extract($data);
}

?>

<div class="product-container">
    <div class="product-header">
        <div class="product-title">
            <h2>Browse Our Products</h2>
            <p>Displaying <strong><?php echo $offset + 1; ?></strong> to <strong><?php echo $offset + $stmt->rowCount(); ?></strong> of <strong><?php echo $total_products; ?></strong> products</p>
        </div>

    </div>

    <div class="product-filter">
        <form method="GET" action="" class="filter-form">
            <select name="category" class="filter-field">
                <option value="">Category</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo htmlspecialchars($category); ?>" <?php if (isset($_GET['category']) && $_GET['category'] == $category) echo 'selected'; ?>><?php echo htmlspecialchars($category); ?></option>
                <?php endforeach; ?>
            </select>

            <select name="brand" class="filter-field">
                <option value="">Brand</option>
                <?php foreach ($brands as $brand): ?>
                    <option value="<?php echo htmlspecialchars($brand); ?>" <?php if (isset($_GET['brand']) && $_GET['brand'] == $brand) echo 'selected'; ?>><?php echo htmlspecialchars($brand); ?></option>
                <?php endforeach; ?>
            </select>

            <input type="number" name="price_min" class="filter-field" placeholder="Min Price" value="<?php echo isset($_GET['price_min']) ? $_GET['price_min'] : ''; ?>">
            <input type="number" name="price_max" class="filter-field" placeholder="Max Price" value="<?php echo isset($_GET['price_max']) ? $_GET['price_max'] : ''; ?>">

            <button type="submit" class="filter-btn">Apply Filters</button>
        </form>
    </div>

    <div class="product-list">
        <?php if ($stmt->rowCount() > 0): ?>
            <?php while ($product = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                <?php
                $productId = $product['id'];
                $inCart = isset($cart[$productId]);
                $quantity = $inCart ? $cart[$productId]['quantity'] : 0;
                ?>
                <div class="product-card" id="product-<?php echo $productId; ?>">
                    <img src="<?php echo htmlspecialchars($product['image']); ?>"
                        alt="<?php echo htmlspecialchars($product['name']); ?>"
                        class="product-image">

                    <div class="product-info">
                        <h3 class="product-name"><?php echo htmlspecialchars($product['name']); ?></h3>
                        <p class="product-price">
                            <strong class="discounted-price">
                                $<?php echo number_format($product['discounted_price'], 2); ?>
                            </strong>
                            <del class="original-price">
                                $<?php echo number_format($product['original_price'], 2); ?>
                            </del>
                        </p>
                        <p class="product-description"><?php echo htmlspecialchars($product['description']); ?></p>

                        <!-- Add to Cart Button -->
                        <button class="btn btn-primary w-100 mt-3 add-to-cart"
                            data-product-id="<?php echo $productId; ?>"
                            style="<?php echo $inCart ? 'display:none;' : ''; ?>">
                            Add to Cart
                        </button>

                        <!-- Quantity Controls -->
                        <div class="quantity-controls mt-3 justify-content-center align-items-center 
                      <?php echo $inCart ? 'show d-flex' : ''; ?>"
                            style="<?php echo $inCart ? 'display:flex;' : 'display:none;'; ?>">
                            <button class="btn btn-sm btn-outline-secondary me-2 decrease-qty"
                                data-product-id="<?php echo $productId; ?>">−</button>
                            <span id="qty-<?php echo $productId; ?>">
                                <?php echo $quantity > 0 ? $quantity : 1; ?>
                            </span>
                            <button class="btn btn-sm btn-outline-secondary ms-2 increase-qty"
                                data-product-id="<?php echo $productId; ?>">+</button>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No products found matching your filters.</p>
        <?php endif; ?>
    </div>


    <div class="pagination">
        <?php if ($currentPage > 1): ?>
            <a href="?<?php echo $filterQuery; ?>pg=<?php echo $currentPage - 1; ?>">Previous</a>
        <?php endif; ?>
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?<?php echo $filterQuery; ?>pg=<?php echo $i; ?>" <?php if ($i == $currentPage) echo 'class="active"'; ?>><?php echo $i; ?></a>
        <?php endfor; ?>
        <?php if ($currentPage < $total_pages): ?>
            <a href="?<?php echo $filterQuery; ?>pg=<?php echo $currentPage + 1; ?>">Next</a>
        <?php endif; ?>
    </div>

</div>



<script>
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.dataset.productId;
            fetch('update_cart.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        action: 'add',
                        product_id: productId
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        const card = document.getElementById('product-' + productId);
                        card.querySelector('.add-to-cart').style.display = 'none';

                        const qtyControls = card.querySelector('.quantity-controls');
                        qtyControls.style.display = 'flex';
                        setTimeout(() => qtyControls.classList.add('show'), 10);

                        card.querySelector('#qty-' + productId).textContent = data.quantity;
                    }
                })
                .catch(err => console.error('Error:', err));
        });
    });



    // ✅ Handle + / − dynamically
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('increase-qty') || e.target.classList.contains('decrease-qty')) {
            const productId = e.target.dataset.productId;
            const action = e.target.classList.contains('increase-qty') ? 'increase' : 'decrease';
            const card = document.getElementById('product-' + productId);
            const qtyControls = card.querySelector('.quantity-controls');

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
                        const qtyElem = card.querySelector('#qty-' + productId);

                        if (data.quantity > 0) {
                            qtyElem.textContent = data.quantity;
                        } else {
                            qtyControls.classList.remove('show');
                            setTimeout(() => {
                                qtyControls.style.display = 'none';
                                card.querySelector('.add-to-cart').style.display = 'block';
                            }, 400);
                        }
                        const cartBadge = document.querySelector('.nav-link .badge.bg-danger');
                        if (cartBadge && data.cartCount !== undefined) {
                            cartBadge.textContent = data.cartCount;
                        }
                    }
                })
                .catch(err => console.error('Error:', err));
        }
    });
</script>
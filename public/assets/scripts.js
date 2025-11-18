// For Public App



const swiper = new Swiper('.swiper', {
    effect: 'coverflow',
    grabCursor: true,
    centeredSlides: true,
    slidesPerView: 'auto',
    loop: true,
    speed: 800,
    autoplay: {
        delay: 3000,
        disableOnInteraction: false,
    },
    coverflowEffect: {
        rotate: 30,
        stretch: 0,
        depth: 100,
        modifier: 1,
        slideShadows: true,
    },
    pagination: {
        el: '.swiper-pagination',
        clickable: true,
    },
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
    },
});



//about.php 

const container = document.querySelector('.reveal');

const observer = new IntersectionObserver(entries => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('active');
        }
        else {
            entry.target.classList.remove('active'); // hide animation
        }
    });
}, { threshold: 0.1 });

observer.observe(container);



const items = document.querySelectorAll('.slide-left, .slide-right');

const observerr = new IntersectionObserver(entries => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('active');    // slide in
        } else {
            entry.target.classList.remove('active'); // optional: reverse
        }
    });
}, { threshold: 0.2 });

items.forEach(item => observerr.observe(item));



//cart.php 

document.addEventListener('DOMContentLoaded', function () {
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
        proceedBtn.addEventListener('click', async function () {
            // ✅ Check if user session still valid
            try {
                const authResponse = await fetch('../public/check_auth.php', {
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
    if (!addressForm) return;

    addressForm.addEventListener('submit', async function (e) {

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
            const authResponse = await fetch('../public/check_auth.php', {
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
});



document.addEventListener('click', function (e) {
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

paypalBtn.addEventListener('click', async function () {
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
            createOrder: function () {
                return data.paypal_order_id;
            },
            onApprove: function (data, actions) {
                window.location.href = "<?php echo $BASE; ?>/paypalsuccess.php?token=" + data.orderID;

            },
            onCancel: function () {
                window.location.href = "<?php echo $BASE; ?>/paypalcancel.php";

            },
            onError: function (err) {
                console.error(err);
                messageEl.innerText = "PayPal error occurred.";
            }
        }).render('#paypal-button-container');

    } catch (err) {
        console.error("❌ PayPal flow error:", err);
        messageEl.innerText = "PayPal SDK failed to load.";
    }
});

//products.php 

document.querySelectorAll('.add-to-cart').forEach(button => {
    button.addEventListener('click', function () {
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
document.addEventListener('click', function (e) {
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



// For Admin App

// for logout popup
const logoutContainer = document.querySelector('.logout-container');
const logoutPopup = document.querySelector('.logout-popup');

logoutContainer.addEventListener('click', () => {
    logoutPopup.style.display = logoutPopup.style.display === 'block' ? 'none' : 'block';
});
window.addEventListener('click', (e) => {
    if (!logoutContainer.contains(e.target)) {
        logoutPopup.style.display = 'none';
    }
});


// for Role creation 
const createBtn = document.getElementById('createRoleBtn');
const modal = document.getElementById('roleModal');
const cancelBtn = document.getElementById('cancelRole');

createBtn.addEventListener('click', () => {
    modal.style.display = 'flex'; // show modal
});

cancelBtn.addEventListener('click', () => {
    modal.style.display = 'none'; // hide modal
});


const saveBtn = document.getElementById('saveRole');

saveBtn.addEventListener('click', () => {
    const roleName = document.getElementById('roleName').value.trim();
    const csrf = document.getElementById('csrf').value.trim();

    if (roleName === '') {
        alert('Enter a role name');
        return;
    }

    debugger;

    console.log('Sending role:', roleName, 'CSRF:', csrf);

    fetch('index.php?page=saverole&area=admin', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'roleName=' + encodeURIComponent(roleName) + '&csrf=' + encodeURIComponent(csrf)
    })

        .then(res => res.text())
        .then(data => {
            alert('Role saved!');
            modal.style.display = 'none';
            location.reload(); // refresh to see new role
        });
});


// for Role deletion 
const deleteModal = document.getElementById('deleteModal');

let selectedRoleId = null;

document.querySelectorAll('.delete-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        selectedRoleId = btn.dataset.roleid;
        const roleName = btn.dataset.rolename;
        document.getElementById('deleteMessage').textContent =
            `Are you sure you want to remove the role "${roleName}"?`;
        deleteModal.style.display = 'flex';
    });
});

// Cancel button
document.getElementById('cancelDelete').addEventListener('click', () => {
    deleteModal.style.display = 'none';
});



confirmDelete.addEventListener('click', () => {
    if (!selectedRoleId) return;

    const csrf = document.getElementById('csrf').value; // reuse CSRF token


    fetch('index.php?page=deleterole&area=admin', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'roleId=' + encodeURIComponent(selectedRoleId) + '&csrf=' + encodeURIComponent(csrf)
    })
        .then(res => res.text())
        .then(data => {
            alert('Role Deleted!'); // or use a nicer toast message
            deleteModal.style.display = 'none';
            location.reload(); // refresh table
        });
});



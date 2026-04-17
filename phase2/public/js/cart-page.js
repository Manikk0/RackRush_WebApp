// Cart page item controls and summary sync.
var cartUpdateTimeouts = {};
var cartShippingFee = 0;

if (typeof window.CART_SHIPPING_FEE === 'number') {
    cartShippingFee = window.CART_SHIPPING_FEE;
}

// Discount code input visibility toggle on cart page.
document.addEventListener('DOMContentLoaded', function () {
    var discountBtn = document.getElementById('btn-discount');
    if (!discountBtn) {
        return;
    }

    discountBtn.addEventListener('click', function () {
        var wrap = document.getElementById('discount-input-wrap');
        if (wrap) {
            wrap.classList.toggle('visible');
        }
    });
});

// Read CSRF token for API requests on cart page.
function getCartCsrf() {
    var meta = document.querySelector('meta[name="csrf-token"]');
    if (!meta) {
        return '';
    }
    return meta.getAttribute('content');
}

// Escape text before inserting into HTML string.
function escapeHtmlForCart(str) {
    if (str === null || str === undefined) {
        return '';
    }

    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
}

// Normalize image URL path for cart row rendering.
function cartRowImageSrc(path) {
    if (!path) {
        return '/assets/grapes_white_tray.png';
    }

    var normalizedPath = String(path);
    if (normalizedPath.indexOf('http://') === 0 || normalizedPath.indexOf('https://') === 0) {
        return normalizedPath;
    }
    if (normalizedPath.charAt(0) === '/') {
        return normalizedPath;
    }

    return '/' + normalizedPath;
}

// Apply server cart JSON to page rows and totals.
window.applyCartPageDomFromData = function (data) {
    var container = document.getElementById('cart-items-container');
    if (!container) {
        return;
    }

    var emptyState = document.getElementById('cart-empty-state');
    var idsInCart = Object.keys(data);
    var idLookup = {};
    var i = 0;

    for (i = 0; i < idsInCart.length; i++) {
        idLookup[String(idsInCart[i])] = true;
    }

    var existingRows = container.querySelectorAll('.cart-row[data-id]');
    for (i = 0; i < existingRows.length; i++) {
        var rowEl = existingRows[i];
        var rowId = rowEl.getAttribute('data-id');
        if (!idLookup[rowId]) {
            rowEl.remove();
        }
    }

    var subtotal = 0;
    var oldSubtotal = 0;
    var count = 0;

    for (i = 0; i < idsInCart.length; i++) {
        var id = idsInCart[i];
        var item = data[id];
        if (!item) {
            continue;
        }

        count++;
        subtotal += item.price * item.quantity;
        if (item.old_price && (item.discount || 0) > 0) {
            oldSubtotal += item.old_price * item.quantity;
        } else {
            oldSubtotal += item.price * item.quantity;
        }

        var row = container.querySelector('.cart-row[data-id="' + id + '"]');
        if (!row) {
            row = buildCartRowFromItem(id, item);
            container.appendChild(row);
        } else {
            var qtyInput = document.getElementById('qty-cart-' + id);
            if (qtyInput) {
                qtyInput.value = item.quantity;
            }

            var priceElement = row.querySelector('.cart-row__price');
            if (priceElement) {
                priceElement.innerText = (item.price * item.quantity).toFixed(2).replace('.', ',') + ' €';
            }

            var oldPriceElement = row.querySelector('.cart-row__old-price');
            if (oldPriceElement && item.old_price) {
                oldPriceElement.innerText = (item.old_price * item.quantity).toFixed(2).replace('.', ',') + ' €';
            }
        }
    }

    var savings = oldSubtotal - subtotal;

    if (emptyState) {
        emptyState.style.display = count === 0 ? 'block' : 'none';
    }

    var titleElement = document.getElementById('cart-page-title');
    if (titleElement) {
        titleElement.innerText = 'Prehľad objednávky (' + count + ')';
    }

    var summarySubtotal = document.getElementById('summary-subtotal');
    if (summarySubtotal) {
        summarySubtotal.innerText = subtotal.toFixed(2).replace('.', ',') + ' €';
    }

    var summaryShipping = document.getElementById('summary-shipping');
    if (summaryShipping) {
        summaryShipping.innerText = cartShippingFee.toFixed(2).replace('.', ',') + ' €';
    }

    var summaryTotal = document.getElementById('summary-total');
    if (summaryTotal) {
        var totalWithShipping = subtotal + cartShippingFee;
        summaryTotal.innerText = totalWithShipping.toFixed(2).replace('.', ',') + ' €';
    }

    var summarySavings = document.getElementById('summary-savings');
    var savingsContainer = document.querySelector('.cart-summary__savings');
    if (summarySavings && savingsContainer) {
        summarySavings.innerText = savings.toFixed(2).replace('.', ',') + ' €';
        savingsContainer.style.display = savings > 0 ? 'block' : 'none';
    }

    var proceedButton = document.getElementById('btn-proceed-checkout');
    if (proceedButton) {
        proceedButton.disabled = count === 0;
        if (count === 0) {
            proceedButton.classList.add('opacity-50');
        } else {
            proceedButton.classList.remove('opacity-50');
        }
    }
};

// Create one cart row element from JSON item.
function buildCartRowFromItem(id, item) {
    var idNumber = parseInt(id, 10);
    var nameSafe = escapeHtmlForCart(item.name);
    var weightSafe = escapeHtmlForCart(item.weight || '');
    var imageSrc = cartRowImageSrc(item.image);
    var qty = item.quantity;

    var oldPriceHtml = '';
    if ((item.discount || 0) > 0 && item.old_price != null) {
        oldPriceHtml = '<span class="text-decoration-line-through cart-row__old-price" style="color: #ff6b6b; font-size: 0.8rem;">' +
            (item.old_price * qty).toFixed(2).replace('.', ',') + ' €</span>';
    }

    var lineTotal = (item.price * qty).toFixed(2).replace('.', ',') + ' €';
    var div = document.createElement('div');
    div.className = 'cart-row position-relative';
    div.setAttribute('data-id', String(idNumber));

    div.innerHTML = '' +
        '<div class="cart-row__img-wrap me-2" style="align-self: flex-start;">' +
        '<a href="/product/' + idNumber + '">' +
        '<img src="' + escapeHtmlForCart(imageSrc) + '" alt="' + nameSafe + '" class="cart-row__img">' +
        '</a></div>' +
        '<div class="cart-row__content d-flex flex-column flex-grow-1 w-100" style="min-width: 0;">' +
        '<div class="d-flex justify-content-between align-items-start mb-2">' +
        '<a href="/product/' + idNumber + '" class="text-decoration-none pe-2">' +
        '<p class="cart-row__name text-wrap m-0" style="white-space: normal; line-height: 1.2;">' + nameSafe + '</p>' +
        '</a>' +
        '<button onclick="removeCartPageItem(' + idNumber + ')" class="btn p-0 d-flex align-items-center justify-content-center" style="opacity: 0.6; margin-top: 2px;">' +
        '<img src="/assets/close.png" alt="Odstrániť" class="icon-sm icon-white" style="width: 12px; height: 12px;">' +
        '</button></div>' +
        '<div class="d-flex justify-content-between align-items-end mt-auto">' +
        '<div class="d-flex flex-column justify-content-end">' +
        oldPriceHtml +
        '<span class="cart-row__price fw-bold m-0" style="color: var(--neon-ice); font-size: 1.15rem; text-align: left;">' + lineTotal + '</span>' +
        '</div>' +
        '<div class="cart-row__qty d-flex align-items-center justify-content-center gap-2">' +
        '<button class="btn p-0 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; border: 1px solid var(--tropical-teal); background-color: transparent; color: var(--tropical-teal); border-radius: 6px; font-weight: bold; font-size: 18px;" onclick="updateCartPageItem(' + idNumber + ', -1)">−</button>' +
        '<input type="text" inputmode="numeric" pattern="[0-9]*" id="qty-cart-' + idNumber + '" value="' + qty + '" class="form-control text-center text-white bg-transparent border-0 fw-bold p-0 m-0" style="width: 34px; box-shadow: none; outline: none; height: 32px; font-size: 1rem;" oninput="this.value = this.value.replace(/[^0-9]/g, \'\'); if(parseInt(this.value, 10) > 99) this.value = 99;" onkeyup="debounceCartUpdate(' + idNumber + ', this.value)">' +
        '<button class="btn p-0 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background-color: var(--tropical-teal); border: none; color: var(--space-indigo, #000); border-radius: 6px; font-weight: bold; font-size: 18px;" onclick="updateCartPageItem(' + idNumber + ', 1)">+</button>' +
        '</div></div></div>';

    return div;
}

// Handle plus/minus click in one cart row.
function updateCartPageItem(id, change) {
    var qtyInput = document.getElementById('qty-cart-' + id);
    var currentQty = parseInt(qtyInput.value, 10);
    if (isNaN(currentQty)) {
        currentQty = 1;
    }

    var newQty = currentQty + change;
    if (newQty > 99) {
        newQty = 99;
    }

    if (newQty <= 0) {
        removeCartPageItem(id);
    } else {
        qtyInput.value = newQty;
        debounceCartUpdate(id, newQty);
    }
}

// Delay quantity update requests while user is typing.
function debounceCartUpdate(id, amount) {
    var key = String(id);
    if (cartUpdateTimeouts[key]) {
        clearTimeout(cartUpdateTimeouts[key]);
    }

    cartUpdateTimeouts[key] = setTimeout(function () {
        delete cartUpdateTimeouts[key];
        setCartPageItemExact(id, amount);
    }, 800);
}

// Send exact quantity update to backend.
function setCartPageItemExact(id, amount) {
    var newQty = parseInt(amount, 10);
    if (isNaN(newQty) || newQty <= 0) {
        removeCartPageItem(id);
        return;
    }

    fetch('/cart/add/' + id, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': getCartCsrf(),
            'Content-Type': 'application/json',
            Accept: 'application/json',
        },
        body: JSON.stringify({
            quantity: newQty,
            exact: true,
        }),
    }).then(function () {
        refreshLocalCartDisplay();
    });
}

// Remove one row from cart via backend endpoint.
function removeCartPageItem(id) {
    fetch('/cart/remove/' + id, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': getCartCsrf(),
            Accept: 'application/json',
        },
    }).then(function () {
        var row = document.querySelector('.cart-row[data-id="' + id + '"]');
        if (row) {
            row.remove();
        }
        refreshLocalCartDisplay();
    });
}

// Refresh shared drawer + cart page UI after updates.
function refreshLocalCartDisplay() {
    if (typeof loadCartPopup === 'function') {
        loadCartPopup();
    }
}

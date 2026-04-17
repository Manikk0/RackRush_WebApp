// Shared cart drawer sync and layout interactions.
document.addEventListener('DOMContentLoaded', function () {
    // Show auth modal/toast based on backend flags from Blade.
    if (window.bootstrap) {
        if (window.showLoginModal === true) {
            var loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
            loginModal.show();
        }

        if (window.showRegisterModal === true) {
            var registerModal = new bootstrap.Modal(document.getElementById('registerModal'));
            registerModal.show();
        }

        if (window.showLogoutToast === true) {
            var logoutToast = new bootstrap.Toast(document.getElementById('logoutToast'));
            logoutToast.show();
        }
    }

    // Initial cart load so badges and drawer data are correct.
    loadCartPopup();

    var cartTriggers = document.querySelectorAll('.cart-trigger-btn');
    var cartClose = document.getElementById('cart-close');
    var cartOverlay = document.getElementById('cart-overlay');

    // Navbar cart trigger opens drawer (or cart page on mobile).
    for (var i = 0; i < cartTriggers.length; i++) {
        cartTriggers[i].addEventListener('click', function (e) {
            if (window.location.pathname === '/cart') {
                return;
            }

            if (window.innerWidth <= 768) {
                window.location.href = '/cart';
                return;
            }

            e.preventDefault();
            openCartDrawer();
        });
    }

    // Drawer close controls.
    if (cartClose) {
        cartClose.addEventListener('click', function () {
            closeCartDrawer();
        });
    }

    if (cartOverlay) {
        cartOverlay.addEventListener('click', function () {
            closeCartDrawer();
        });
    }
});

// Browser Back/Forward cache sync.
window.addEventListener('pageshow', function () {
    loadCartPopup();
});

// Read CSRF token from page meta.
function getCsrfToken() {
    var meta = document.querySelector('meta[name="csrf-token"]');
    if (!meta) {
        return '';
    }
    return meta.getAttribute('content');
}

// Normalize image URL from cart row data.
function cartImageUrl(path) {
    if (!path) {
        return '/assets/grapes_white_tray.png';
    }
    if (path.indexOf('http://') === 0 || path.indexOf('https://') === 0) {
        return path;
    }
    if (path.charAt(0) === '/') {
        return path;
    }
    return '/' + path;
}

// Open cart drawer UI and refresh data.
function openCartDrawer() {
    var drawer = document.getElementById('cart-drawer');
    var overlay = document.getElementById('cart-overlay');

    if (drawer) {
        drawer.classList.add('cart-drawer--open');
    }
    if (overlay) {
        overlay.classList.add('cart-overlay--visible');
    }

    loadCartPopup();
}

// Close cart drawer UI.
function closeCartDrawer() {
    var drawer = document.getElementById('cart-drawer');
    var overlay = document.getElementById('cart-overlay');

    if (drawer) {
        drawer.classList.remove('cart-drawer--open');
    }
    if (overlay) {
        overlay.classList.remove('cart-overlay--visible');
    }
}

// Fetch current cart JSON and redraw drawer + badges.
function loadCartPopup() {
    fetch('/cart/api')
        .then(function (res) {
            return res.json();
        })
        .then(function (data) {
            var cartBody = document.getElementById('cart-body');
            if (!cartBody) {
                return;
            }

            var html = '';
            var totalItems = 0;
            var totalPrice = 0;

            for (var id in data) {
                if (!Object.prototype.hasOwnProperty.call(data, id)) {
                    continue;
                }

                var item = data[id];
                totalItems += parseInt(item.quantity, 10);
                totalPrice += item.price * item.quantity;

                var oldPriceHtml = '';
                var savedHtml = '';
                if (item.discount && item.discount > 0) {
                    oldPriceHtml = '<span style="text-decoration: line-through; color: #aaa; margin-right: 5px;">' +
                        (item.old_price * item.quantity).toFixed(2).replace('.', ',') + ' €</span>';
                    savedHtml = '<div style="color: #ff4d4d; font-size: 12px; margin-top: 5px;">Ušetríte ' + item.discount + ' %</div>';
                }

                var detailUrl = '/product/' + id;
                html += '' +
                    '<div class="d-flex align-items-center mb-3 pb-3" style="border-bottom: 1px solid #333; position:relative;">' +
                    '<a href="' + detailUrl + '" style="display:block;">' +
                    '<img src="' + cartImageUrl(item.image) + '" alt="' + item.name + '" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px; background: white; padding: 2px;">' +
                    '</a>' +
                    '<div class="ms-3 flex-grow-1">' +
                    '<h6 class="mb-1" style="font-size: 14px;">' +
                    '<a href="' + detailUrl + '" style="text-decoration:none;color:white;">' + item.name + '</a>' +
                    '</h6>' +
                    '<div class="d-flex align-items-end justify-content-between mt-2">' +
                    '<div class="d-flex align-items-center" style="border: 1px solid #444; border-radius: 5px; padding: 2px;">' +
                    '<button class="btn btn-sm text-white p-0 m-0 fs-5" style="width: 25px;" onclick="updatePopupItem(' + id + ', -1)">-</button>' +
                    '<span class="text-white mx-2 fw-bold" style="font-size: 14px;">' + item.quantity + '</span>' +
                    '<button class="btn btn-sm text-white p-0 m-0 fs-5" style="width: 25px;" onclick="updatePopupItem(' + id + ', 1)">+</button>' +
                    '</div>' +
                    '<div class="text-end">' +
                    '<div class="fw-bold" style="color: ' + (item.discount > 0 ? '#ff4d4d' : 'white') + ';">' + oldPriceHtml + (item.price * item.quantity).toFixed(2).replace('.', ',') + ' €</div>' +
                    savedHtml +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '<button onclick="removePopupItem(' + id + ')" class="btn p-0 position-absolute" style="top: -5px; right: 0; color: #888;">✕</button>' +
                    '</div>';
            }

            if (totalItems === 0) {
                html = '<p class="text-center text-muted mt-4">Váš košík je prázdny.</p>';
            }

            cartBody.innerHTML = html;

            var badges = document.querySelectorAll('.cart-badge');
            for (var j = 0; j < badges.length; j++) {
                badges[j].innerText = totalItems;
                if (totalItems > 0) {
                    badges[j].classList.remove('d-none');
                } else {
                    badges[j].classList.add('d-none');
                }
            }

            var totalEl = document.getElementById('cart-total-price');
            if (totalEl) {
                totalEl.innerText = totalPrice.toFixed(2).replace('.', ',') + ' €';
            }

            if (typeof window.syncProductCardCartFromServer === 'function') {
                window.syncProductCardCartFromServer(data);
            }

            if (typeof window.applyCartPageDomFromData === 'function') {
                window.applyCartPageDomFromData(data);
            }
        });
}

// Update one item quantity from drawer plus/minus controls.
function updatePopupItem(id, change) {
    fetch('/cart/api')
        .then(function (res) {
            return res.json();
        })
        .then(function (cart) {
            var currentQty = 0;
            if (cart && cart[id] && cart[id].quantity) {
                currentQty = parseInt(cart[id].quantity, 10) || 0;
            }

            var nextQty = currentQty + change;
            if (nextQty < 0) {
                nextQty = 0;
            }
            if (nextQty > 99) {
                nextQty = 99;
            }

            return fetch('/cart/add/' + id, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': getCsrfToken(),
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                },
                body: JSON.stringify({
                    quantity: nextQty,
                    exact: true,
                }),
            });
        })
        .then(function () {
            loadCartPopup();
        });
}

// Remove one item from drawer.
function removePopupItem(id) {
    fetch('/cart/remove/' + id, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': getCsrfToken(),
            Accept: 'application/json',
        },
    }).then(function () {
        loadCartPopup();
    });
}

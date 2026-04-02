// GLOBAL VARIABLES
const header = document.getElementById("main-header");
const topRow = document.getElementById("top-row");
const stickySearch = document.getElementById("sticky-search-container");
const stickyCart = document.getElementById("sticky-cart");
const btnLists = document.getElementById("btn-lists");

const productCardTemplate = `
<div class="product-card">
    <div class="product-card__image-wrap">
    <a href="/product-detail">
        <img src="assets/grapes_white_tray.png" alt="Hrozno biele, bezsemenné" class="product-card__image">
    </a>
    <button class="product-card__wishlist" aria-label="Wishlist"><img src="assets/heart.png" class="wishlist-icon"></button>
    <button class="product-card__add" aria-label="Pridať do košíka"><img src="assets/plus.png" class="icon-sm icon-white"></button>
    </div>
    <a href="/product-detail" class="text-decoration-none"><p class="product-card__name">Hrozno biele, bezsemenné</p></a>
    <p class="product-card__price">1.65€</p>
    <p class="product-card__meta"><span>250g</span><span>2.60€/kg</span></p>
</div>`;

let cartItems = [];

// NAVIGATION & HEADER
window.onscroll = function () {
    if (window.innerWidth >= 768) {
        if (window.scrollY > 40) {
            header.classList.add("scrolled");
            topRow.classList.add("d-none");
            stickySearch.classList.remove("d-none");
            stickyCart.classList.remove("d-none");
            btnLists.classList.add("d-none");
        } else {
            header.classList.remove("scrolled");
            topRow.classList.remove("d-none");
            stickySearch.classList.add("d-none");
            stickyCart.classList.add("d-none");
            btnLists.classList.remove("d-none");
        }
    }
};

// AUTHENTICATION STATE
function toggleAuthState(isLoggedIn) {
    const loggedInEl = document.getElementById('dropdown-logged-in');
    const loggedOutEl = document.getElementById('dropdown-logged-out');
    const logoutBtn = document.getElementById('logout-btn');
    const restrictedLinks = document.querySelectorAll('.auth-restricted');

    const mobileLoggedInEl = document.getElementById('mobile-logged-in');
    const mobileLoggedOutEl = document.getElementById('mobile-logged-out');
    const mobileLogoutSection = document.getElementById('mobile-logout-section');

    if (isLoggedIn) {
        if (loggedInEl) { loggedInEl.classList.remove('d-none'); }
        if (loggedOutEl) { loggedOutEl.classList.add('d-none'); }
        if (logoutBtn) { logoutBtn.classList.remove('d-none'); }

        if (mobileLoggedInEl) { mobileLoggedInEl.classList.remove('d-none'); }
        if (mobileLoggedOutEl) { mobileLoggedOutEl.classList.add('d-none'); }
        if (mobileLogoutSection) { mobileLogoutSection.classList.remove('d-none'); }

        for (let i = 0; i < restrictedLinks.length; i++) {
            restrictedLinks[i].classList.remove('link-greyed');
        }
    } else {
        if (loggedInEl) { loggedInEl.classList.add('d-none'); }
        if (loggedOutEl) { loggedOutEl.classList.remove('d-none'); }
        if (logoutBtn) { logoutBtn.classList.add('d-none'); }

        if (mobileLoggedInEl) { mobileLoggedInEl.classList.add('d-none'); }
        if (mobileLoggedOutEl) { mobileLoggedOutEl.classList.remove('d-none'); }
        if (mobileLogoutSection) { mobileLogoutSection.classList.add('d-none'); }

        for (let i = 0; i < restrictedLinks.length; i++) {
            restrictedLinks[i].classList.add('link-greyed');
        }
    }
}

// Initial state
// toggleAuthState(true); // Removed for Laravel Auth

// AUTHENTICATION EVENTS
/* Removed for Laravel Auth
const userMenu = document.getElementById('userMenu');
...
*/

// PAGE SPECIFIC LOGIC (CART)
function renderCart() {
    const container = document.getElementById('cart-items-container');
    const emptyState = document.getElementById('cart-empty-state');
    const title = document.getElementById('cart-page-title');

    if (!container) { return; }

    if (cartItems.length === 0) {
        container.innerHTML = '';
        if (emptyState) {
            container.appendChild(emptyState);
            emptyState.style.display = 'block';
        }
        if (title) { title.textContent = 'Prehľad objednávky (0)'; }
        updateSummary();
        return;
    }

    // Group items by category (simplified)
    const groups = {};
    for (let i = 0; i < cartItems.length; i++) {
        const item = cartItems[i];
        if (!groups[item.group]) { groups[item.group] = []; }
        groups[item.group].push(item);
    }

    let html = '';
    const groupEntries = Object.entries(groups);
    for (let i = 0; i < groupEntries.length; i++) {
        const groupName = groupEntries[i][0];
        const items = groupEntries[i][1];
        
        html += '<p class="cart-group-label">' + groupName + '</p>';
        for (let j = 0; j < items.length; j++) {
            const item = items[j];
            html += `
            <div class="cart-row" data-id="${item.id}">
                <div class="cart-row__img-wrap">
                    <a href="/product-detail">
                        <img src="https://placehold.co/56x56" alt="${item.name}" class="cart-row__img">
                    </a>
                </div>
                <div class="cart-row__info">
                    <a href="/product-detail" class="text-decoration-none">
                        <p class="cart-row__name">${item.name}</p>
                    </a>
                    <span class="cart-row__weight">${item.weight}</span>
                </div>
                <div class="cart-row__qty">
                    <button class="cart-row__qty-btn" data-action="minus" data-id="${item.id}">−</button>
                    <span class="cart-row__qty-val">${item.qty}</span>
                    <button class="cart-row__qty-btn" data-action="plus" data-id="${item.id}">+</button>
                </div>
                <span class="cart-row__price">${(item.price * item.qty).toFixed(2).replace('.', ',')} €</span>
                <button class="cart-row__remove" data-id="${item.id}" aria-label="Odstrániť">×</button>
            </div>`;
        }
    }

    container.innerHTML = html;
    if (title) { title.textContent = 'Prehľad objednávky (' + cartItems.length + ')'; }
    updateSummary();
    bindCartEvents();
}

function updateSummary() {
    let subtotal = 0;
    for (let i = 0; i < cartItems.length; i++) {
        subtotal += cartItems[i].price * cartItems[i].qty;
    }

    const subtotalEl = document.getElementById('summary-subtotal');
    const totalEl = document.getElementById('summary-total');
    const savingsEl = document.getElementById('summary-savings');

    if (subtotalEl) { subtotalEl.textContent = subtotal.toFixed(2).replace('.', ',') + ' €'; }
    if (totalEl) { totalEl.textContent = subtotal.toFixed(2).replace('.', ',') + ' €'; }
    if (savingsEl) { savingsEl.textContent = '0 €'; }
}

function bindCartEvents() {
    const qtyButtons = document.querySelectorAll('.cart-row__qty-btn');
    for (let i = 0; i < qtyButtons.length; i++) {
        qtyButtons[i].addEventListener('click', function () {
            const id = parseInt(this.dataset.id);
            let itemIndex = -1;
            for (let j = 0; j < cartItems.length; j++) {
                if (cartItems[j].id === id) {
                    itemIndex = j;
                    break;
                }
            }
            
            if (itemIndex === -1) { return; }
            const item = cartItems[itemIndex];

            if (this.dataset.action === 'plus') {
                item.qty++;
            } else {
                item.qty--;
                if (item.qty <= 0) {
                    cartItems.splice(itemIndex, 1);
                }
            }
            renderCart();
        });
    }

    const removeButtons = document.querySelectorAll('.cart-row__remove');
    for (let i = 0; i < removeButtons.length; i++) {
        removeButtons[i].addEventListener('click', function () {
            const id = parseInt(this.dataset.id);
            for (let j = 0; j < cartItems.length; j++) {
                if (cartItems[j].id === id) {
                    cartItems.splice(j, 1);
                    break;
                }
            }
            renderCart();
        });
    }
}

const clearCartBtn = document.getElementById('btn-clear');
if (clearCartBtn) {
    clearCartBtn.addEventListener('click', function () {
        cartItems = [];
        renderCart();
    });
}

const discountBtn = document.getElementById('btn-discount');
if (discountBtn) {
    discountBtn.addEventListener('click', function () {
        const wrap = document.getElementById('discount-input-wrap');
        if (wrap) { wrap.classList.toggle('visible'); }
    });
}

const recContainer = document.getElementById('recommendations');
if (recContainer) {
    recContainer.innerHTML = productCardTemplate.repeat(6);
}

// INITIALIZE
if (document.getElementById('cart-items-container')) {
    renderCart();
}
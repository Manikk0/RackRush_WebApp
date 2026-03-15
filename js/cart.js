// ── Shared product card template (same as index.js) ──
const card = `
<div class="product-card">
    <div class="product-card__image-wrap">
        <img src="https://placehold.co/200x160" alt="Rajo Kyslá smotana" class="product-card__image">
        <button class="product-card__wishlist" aria-label="Wishlist"><img src="assets/heart.png" class="wishlist-icon"></button>
        <button class="product-card__add" aria-label="Pridať do košíka"><img src="assets/plus.png" class="icon-sm icon-white"></button>
    </div>
    <p class="product-card__name">Rajo Kyslá smotana</p>
    <p class="product-card__price">0.65€</p>
    <p class="product-card__meta"><span>250g</span><span>2.60€/kg</span></p>
</div>`;

let cartItems = [];

function renderCart() {
    const container = document.getElementById('cart-items-container');
    const emptyState = document.getElementById('cart-empty-state');
    const title = document.getElementById('cart-page-title');

    if (cartItems.length === 0) {
        container.innerHTML = '';
        container.appendChild(emptyState);
        emptyState.style.display = 'block';
        title.textContent = 'Prehľad objednávky (0)';
        updateSummary();
        return;
    }

    // Group items by category
    const groups = {};
    cartItems.forEach(item => {
        if (!groups[item.group]) groups[item.group] = [];
        groups[item.group].push(item);
    });

    let html = '';
    Object.entries(groups).forEach(([groupName, items]) => {
        html += `<p class="cart-group-label">${groupName}</p>`;
        items.forEach(item => {
            html += `
            <div class="cart-row" data-id="${item.id}">
                <div class="cart-row__img-wrap">
                    <img src="https://placehold.co/56x56" alt="${item.name}" class="cart-row__img">
                </div>
                <div class="cart-row__info">
                    <p class="cart-row__name">${item.name}</p>
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
        });
    });

    container.innerHTML = html;
    title.textContent = `Prehľad objednávky (${cartItems.length})`;
    updateSummary();
    bindCartEvents();
}

// ── Update summary box ──
function updateSummary() {
    const subtotal = cartItems.reduce((sum, i) => sum + i.price * i.qty, 0);
    document.getElementById('summary-subtotal').textContent = subtotal.toFixed(2).replace('.', ',') + ' €';
    document.getElementById('summary-total').textContent = subtotal.toFixed(2).replace('.', ',') + ' €';
    document.getElementById('summary-savings').textContent = '0 €';
}

// ── Bind qty / remove buttons ──
function bindCartEvents() {
    document.querySelectorAll('.cart-row__qty-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = parseInt(this.dataset.id);
            const item = cartItems.find(i => i.id === id);
            if (!item) return;
            if (this.dataset.action === 'plus') {
                item.qty++;
            } else {
                item.qty--;
                if (item.qty <= 0) {
                    cartItems = cartItems.filter(i => i.id !== id);
                }
            }
            renderCart();
        });
    });

    document.querySelectorAll('.cart-row__remove').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = parseInt(this.dataset.id);
            cartItems = cartItems.filter(i => i.id !== id);
            renderCart();
        });
    });
}

// ── Clear cart ──
document.getElementById('btn-clear').addEventListener('click', function () {
    cartItems = [];
    renderCart();
});

// ── Discount code toggle ──
document.getElementById('btn-discount').addEventListener('click', function () {
    const wrap = document.getElementById('discount-input-wrap');
    wrap.classList.toggle('visible');
});

// ── Sticky scroll (same as index) ──
const header = document.getElementById('main-header');
const topRow = document.getElementById('top-row');
const stickySearch = document.getElementById('sticky-search-container');
const stickyCart = document.getElementById('sticky-cart');
const btnLists = document.getElementById('btn-lists');

window.onscroll = function () {
    if (window.innerWidth >= 768) {
        if (window.scrollY > 40) {
            header.classList.add('scrolled');
            topRow.classList.add('d-none');
            stickySearch.classList.remove('d-none');
            stickyCart.classList.remove('d-none');
            btnLists.classList.add('d-none');
        } else {
            header.classList.remove('scrolled');
            topRow.classList.remove('d-none');
            stickySearch.classList.add('d-none');
            stickyCart.classList.add('d-none');
            btnLists.classList.remove('d-none');
        }
    }
};

// ── Auth state (same as index) ──
function toggleAuthState(isLoggedIn) {
    const loggedInEl = document.getElementById('dropdown-logged-in');
    const loggedOutEl = document.getElementById('dropdown-logged-out');
    const logoutBtn = document.getElementById('logout-btn');
    const restrictedLinks = document.querySelectorAll('.auth-restricted');

    // Mobile elements
    const mobileLoggedInEl = document.getElementById('mobile-logged-in');
    const mobileLoggedOutEl = document.getElementById('mobile-logged-out');
    const mobileLogoutSection = document.getElementById('mobile-logout-section');

    if (isLoggedIn) {
        if(loggedInEl) loggedInEl.classList.remove('d-none');
        if(loggedOutEl) loggedOutEl.classList.add('d-none');
        if(logoutBtn) logoutBtn.classList.remove('d-none');
        
        if(mobileLoggedInEl) mobileLoggedInEl.classList.remove('d-none');
        if(mobileLoggedOutEl) mobileLoggedOutEl.classList.add('d-none');
        if(mobileLogoutSection) mobileLogoutSection.classList.remove('d-none');

        restrictedLinks.forEach(l => l.classList.remove('link-greyed'));
    } else {
        if(loggedInEl) loggedInEl.classList.add('d-none');
        if(loggedOutEl) loggedOutEl.classList.remove('d-none');
        if(logoutBtn) logoutBtn.classList.add('d-none');

        if(mobileLoggedInEl) mobileLoggedInEl.classList.add('d-none');
        if(mobileLoggedOutEl) mobileLoggedOutEl.classList.remove('d-none');
        if(mobileLogoutSection) mobileLogoutSection.classList.add('d-none');

        restrictedLinks.forEach(l => l.classList.add('link-greyed'));
    }
}

toggleAuthState(true);

document.getElementById('logout-btn-mobile')?.addEventListener('click', function(e) {
    e.preventDefault();
    toggleAuthState(false);
    
    // Close offcanvas if it's open
    const mobileMenuEl = document.getElementById('mobileMenu');
    if (mobileMenuEl) {
        const offcanvas = bootstrap.Offcanvas.getInstance(mobileMenuEl);
        if (offcanvas) offcanvas.hide();
    }

    const toastEl = document.getElementById('logoutToast');
    if (toastEl) new bootstrap.Toast(toastEl).show();
});

document.getElementById('logout-btn')?.addEventListener('click', function (e) {
    e.preventDefault();
    toggleAuthState(false);
    const toastEl = document.getElementById('logoutToast');
    if (toastEl) new bootstrap.Toast(toastEl).show();
});

document.getElementById('login-form')?.addEventListener('submit', function (e) {
    e.preventDefault();
    toggleAuthState(true);
    bootstrap.Modal.getInstance(document.getElementById('loginModal'))?.hide();
});

document.getElementById('register-form')?.addEventListener('submit', function (e) {
    e.preventDefault();
    toggleAuthState(true);
    bootstrap.Modal.getInstance(document.getElementById('registerModal'))?.hide();
});

document.getElementById('userMenu')?.addEventListener('click', function (e) {
    e.stopPropagation();
});

// ── Recommendations ──
const recContainer = document.getElementById('recommendations');
if (recContainer) {
    recContainer.innerHTML = card.repeat(6);
}

// ── Init ──
renderCart();
// ── Read list name from URL ──
const params = new URLSearchParams(window.location.search);
const listName = params.get('name') || 'Zoznam';

document.getElementById('sld-title').textContent = listName;
document.title = listName + ' – RackRush';

// ── Recommendations (same card as index) ──
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

const recoContainer = document.getElementById('sld-recommendations');
if (recoContainer) recoContainer.innerHTML = card.repeat(6);

// ── Sticky header ──
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

// ── Auth state ──
function toggleAuthState(isLoggedIn) {
    const loggedInEl = document.getElementById('dropdown-logged-in');
    const loggedOutEl = document.getElementById('dropdown-logged-out');
    const logoutBtn = document.getElementById('logout-btn');
    const mobileLoggedIn = document.getElementById('mobile-logged-in');
    const mobileLoggedOut = document.getElementById('mobile-logged-out');
    const mobileLogoutSection = document.getElementById('mobile-logout-section');
    const restrictedLinks = document.querySelectorAll('.auth-restricted');

    if (isLoggedIn) {
        loggedInEl?.classList.remove('d-none');
        loggedOutEl?.classList.add('d-none');
        logoutBtn?.classList.remove('d-none');
        mobileLoggedIn?.classList.remove('d-none');
        mobileLoggedOut?.classList.add('d-none');
        mobileLogoutSection?.classList.remove('d-none');
        restrictedLinks.forEach(l => l.classList.remove('link-greyed'));
    } else {
        loggedInEl?.classList.add('d-none');
        loggedOutEl?.classList.remove('d-none');
        logoutBtn?.classList.add('d-none');
        mobileLoggedIn?.classList.add('d-none');
        mobileLoggedOut?.classList.remove('d-none');
        mobileLogoutSection?.classList.add('d-none');
        restrictedLinks.forEach(l => l.classList.add('link-greyed'));
    }
}

toggleAuthState(true);

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

// ── Cart drawer ──
const cartTrigger = document.getElementById('cart-trigger');
const cartDrawer = document.getElementById('cart-drawer');
const cartOverlay = document.getElementById('cart-overlay');
const cartClose = document.getElementById('cart-close');

function openCart() {
    cartDrawer.classList.add('cart-drawer--open');
    cartOverlay.classList.add('cart-overlay--visible');
    document.body.classList.add('cart-open');
}
function closeCart() {
    cartDrawer.classList.remove('cart-drawer--open');
    cartOverlay.classList.remove('cart-overlay--visible');
    document.body.classList.remove('cart-open');
}

cartTrigger?.addEventListener('click', function (e) {
    e.preventDefault();
    if (window.innerWidth <= 768) {
        window.location.href = 'cart.html';
    } else {
        cartDrawer.classList.contains('cart-drawer--open') ? closeCart() : openCart();
    }
});
cartClose?.addEventListener('click', closeCart);
cartOverlay?.addEventListener('click', closeCart);
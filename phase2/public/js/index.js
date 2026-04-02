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

// CART DRAWER
(function () {
    const BREAKPOINT = 768;
    const triggers = document.querySelectorAll('.cart-trigger-btn');
    const drawer = document.getElementById('cart-drawer');
    const overlay = document.getElementById('cart-overlay');
    const closeBtn = document.getElementById('cart-close');

    if (!drawer || !overlay) { return; }

    function getScrollbarWidth() {
        return window.innerWidth - document.documentElement.clientWidth;
    }

    function openDrawer() {
        const sw = getScrollbarWidth();
        if (sw > 0) {
            document.body.style.paddingRight = sw + 'px';
            const h = document.getElementById("main-header");
            if (h) { h.style.paddingRight = sw + 'px'; }
        }
        drawer.classList.add('cart-drawer--open');
        overlay.classList.add('cart-overlay--visible');
        document.body.classList.add('cart-open');
    }

    function closeDrawer() {
        drawer.classList.remove('cart-drawer--open');
        overlay.classList.remove('cart-overlay--visible');
        setTimeout(function () {
            document.body.classList.remove('cart-open');
            document.body.style.paddingRight = '';
            const h = document.getElementById("main-header");
            if (h) { h.style.paddingRight = ''; }
        }, 400);
    }

    for (let i = 0; i < triggers.length; i++) {
        triggers[i].addEventListener('click', function (e) {
            e.preventDefault();
            if (window.innerWidth <= BREAKPOINT) {
                window.location.href = '/cart';
            } else {
                if (drawer.classList.contains('cart-drawer--open')) {
                    closeDrawer();
                } else {
                    openDrawer();
                }
            }
        });
    }

    if (closeBtn) {
        closeBtn.addEventListener('click', closeDrawer);
    }
    if (overlay) {
        overlay.addEventListener('click', closeDrawer);
    }
})();

// PAGE SPECIFIC LOGIC
function createSection(title) {
    return `
    <section class="product-section">
    <h2 class="product-section__title">${title}</h2>
    <div class="product-row">${productCardTemplate.repeat(12)}</div>
    <div class="product-section__footer">
        <button class="product-section__more">Zobraziť viac</button>
    </div>
    </section>`;
}

if (document.getElementById("products-top-1")) {
    document.getElementById("products-top-1").innerHTML = createSection("Pre vás");
}
if (document.getElementById("products-top-2")) {
    document.getElementById("products-top-2").innerHTML = createSection("Najpredávanejšie");
}
if (document.getElementById("products-bottom")) {
    document.getElementById("products-bottom").innerHTML = createSection("Aktuálne v zľave");
}

// CATEGORIES SCROLL
document.addEventListener('DOMContentLoaded', function () {
    const scrollWrapper = document.querySelector('.categories-scroll-wrapper');
    const prevBtn = document.getElementById('categories-prev');
    const nextBtn = document.getElementById('categories-next');

    if (scrollWrapper) {
        if (prevBtn) {
            prevBtn.addEventListener('click', function () {
                scrollWrapper.scrollBy({ left: -300, behavior: 'smooth' });
            });
        }
        if (nextBtn) {
            nextBtn.addEventListener('click', function () {
                scrollWrapper.scrollBy({ left: 300, behavior: 'smooth' });
            });
        }
    }
});

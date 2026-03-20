const header = document.getElementById("main-header");
const topRow = document.getElementById("top-row");
const stickySearch = document.getElementById("sticky-search-container");
const stickyCart = document.getElementById("sticky-cart");
const btnLists = document.getElementById("btn-lists");
const card = `
<div class="product-card">
    <div class="product-card__image-wrap">
    <a href="product-detail.html">
        <img src="https://placehold.co/200x160" alt="Rajo Kyslá smotana" class="product-card__image">
    </a>
    <button class="product-card__wishlist" aria-label="Wishlist"><img src="assets/heart.png" class="wishlist-icon"></button>
    <button class="product-card__add" aria-label="Pridať do košíka"><img src="assets/plus.png" class="icon-sm icon-white"></button>
    </div>
    <a href="product-detail.html" class="text-decoration-none"><p class="product-card__name">Rajo Kyslá smotana</p></a>
    <p class="product-card__price">0.65€</p>
    <p class="product-card__meta"><span>250g</span><span>2.60€/kg</span></p>
</div>`;

document.getElementById('userMenu').addEventListener('click', function (e) {
    e.stopPropagation();
});

window.onscroll = function() {
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

const createSection = (title) => `
    <section class="product-section">
    <h2 class="product-section__title">${title}</h2>
    <div class="product-row">${card.repeat(12)}</div>
    <div class="product-section__footer">
        <button class="product-section__more">Zobraziť viac</button>
    </div>
    </section>`;

if (document.getElementById("products-top-1")) {
    document.getElementById("products-top-1").innerHTML = createSection("Pre vás");
}
if (document.getElementById("products-top-2")) {
    document.getElementById("products-top-2").innerHTML = createSection("Najpredávanejšie");
}
if (document.getElementById("products-bottom")) {
    document.getElementById("products-bottom").innerHTML = createSection("Aktuálne v zľave");
}

// Auth state handling
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

        restrictedLinks.forEach(link => link.classList.remove('link-greyed'));
    } else {
        if(loggedInEl) loggedInEl.classList.add('d-none');
        if(loggedOutEl) loggedOutEl.classList.remove('d-none');
        if(logoutBtn) logoutBtn.classList.add('d-none');

        if(mobileLoggedInEl) mobileLoggedInEl.classList.add('d-none');
        if(mobileLoggedOutEl) mobileLoggedOutEl.classList.remove('d-none');
        if(mobileLogoutSection) mobileLogoutSection.classList.add('d-none');

        restrictedLinks.forEach(link => link.classList.add('link-greyed'));
    }
}

// Initialize state
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
    if (toastEl) {
        const toast = new bootstrap.Toast(toastEl);
        toast.show();
    }
});

document.getElementById('logout-btn')?.addEventListener('click', function(e) {
    e.preventDefault();
    toggleAuthState(false);
    const toastEl = document.getElementById('logoutToast');
    if (toastEl) {
        const toast = new bootstrap.Toast(toastEl);
        toast.show();
    }
});

document.getElementById('login-form')?.addEventListener('submit', function(e) {
    e.preventDefault();
    toggleAuthState(true);
    const loginModalElement = document.getElementById('loginModal');
    if (loginModalElement) {
        const loginModal = bootstrap.Modal.getInstance(loginModalElement);
        if(loginModal) loginModal.hide();
    }
});

document.getElementById('register-form')?.addEventListener('submit', function(e) {
    e.preventDefault();
    toggleAuthState(true);
    const registerModalElement = document.getElementById('registerModal');
    if (registerModalElement) {
        const registerModal = bootstrap.Modal.getInstance(registerModalElement);
        if(registerModal) registerModal.hide();
    }
});

(function () {
    var BREAKPOINT = 768;
    var triggers = document.querySelectorAll('.cart-trigger-btn');
    var drawer  = document.getElementById('cart-drawer');
    var overlay = document.getElementById('cart-overlay');
    var closeBtn = document.getElementById('cart-close');

    if (!drawer || !overlay) return;

    function getScrollbarWidth() {
        return window.innerWidth - document.documentElement.clientWidth;
    }

    function openDrawer() {
        const sw = getScrollbarWidth();
        if (sw > 0) {
            document.body.style.paddingRight = sw + 'px';
            const h = document.getElementById("main-header");
            if (h) h.style.paddingRight = sw + 'px';
        }
        drawer.classList.add('cart-drawer--open');
        overlay.classList.add('cart-overlay--visible');
        document.body.classList.add('cart-open');
    }

    function closeDrawer() {
        drawer.classList.remove('cart-drawer--open');
        overlay.classList.remove('cart-overlay--visible');
        setTimeout(() => {
            document.body.classList.remove('cart-open');
            document.body.style.paddingRight = '';
            const h = document.getElementById("main-header");
            if (h) h.style.paddingRight = '';
        }, 400); // Match CSS transition
    }

    triggers.forEach(trigger => {
        trigger.addEventListener('click', function (e) {
            e.preventDefault();
            if (window.innerWidth <= BREAKPOINT) {
                window.location.href = 'cart.html';
            } else {
                drawer.classList.contains('cart-drawer--open') ? closeDrawer() : openDrawer();
            }
        });
    });

    closeBtn?.addEventListener('click', closeDrawer);
    overlay?.addEventListener('click', closeDrawer);
})();

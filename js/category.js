const header = document.getElementById("main-header");
const topRow = document.getElementById("top-row");
const stickySearch = document.getElementById("sticky-search-container");
const stickyCart = document.getElementById("sticky-cart");
const btnLists = document.getElementById("btn-lists");

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

const categoryProducts = document.getElementById("category-products");
if (categoryProducts) {
    categoryProducts.innerHTML = card.repeat(16);
}

const pills = document.querySelectorAll('.sort-pill');
pills.forEach(pill => {
    pill.addEventListener('click', () => {
        pills.forEach(p => p.classList.remove('active'));
        pill.classList.add('active');
    });
});

const moreBtn = document.getElementById('show-more-origins-btn');
if (moreBtn) {
    moreBtn.addEventListener('click', function() {
        const textSpan = this.querySelector('.show-more-text');
        setTimeout(() => {
            const isExpanded = this.getAttribute('aria-expanded') === 'true';
            textSpan.textContent = isExpanded ? 'Zobraziť menej' : 'Zobraziť viac';
        }, 10);
    });
}

// Auth state handling
function toggleAuthState(isLoggedIn) {
    const loggedInEl = document.getElementById('dropdown-logged-in');
    const loggedOutEl = document.getElementById('dropdown-logged-out');
    const logoutBtn = document.getElementById('logout-btn');
    const restrictedLinks = document.querySelectorAll('.auth-restricted');

    if (isLoggedIn) {
        if(loggedInEl) loggedInEl.classList.remove('d-none');
        if(loggedOutEl) loggedOutEl.classList.add('d-none');
        if(logoutBtn) logoutBtn.classList.remove('d-none');
        restrictedLinks.forEach(link => link.classList.remove('link-greyed'));
    } else {
        if(loggedInEl) loggedInEl.classList.add('d-none');
        if(loggedOutEl) loggedOutEl.classList.remove('d-none');
        if(logoutBtn) logoutBtn.classList.add('d-none');
        restrictedLinks.forEach(link => link.classList.add('link-greyed'));
    }
}

// Initialize state
toggleAuthState(true);

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

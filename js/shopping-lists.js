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

let lists = [];

const grid = document.getElementById('sl-grid');
const overlay = document.getElementById('sl-modal-overlay');
const modalInput = document.getElementById('sl-modal-input');
const submitBtn = document.getElementById('sl-modal-submit');

const listIcons = ['🛒', '🥦', '🍖', '🥐', '🧴', '🐾', '🍫', '🥤', '👶', '🏠'];

function renderLists() {
    document.querySelectorAll('.sl-list-card').forEach(c => c.remove());

    lists.forEach(function (list) {
        const card = document.createElement('div');
        card.className = 'sl-list-card';
        card.innerHTML = `
            <button class="sl-list-card__delete" data-id="${list.id}" title="Odstrániť">×</button>
            <div class="sl-list-card__icon">${list.icon}</div>
            <span class="sl-list-card__name">${list.name}</span>
            <span class="sl-list-card__count">0 položiek</span>
        `;
        document.getElementById('sl-create-btn').insertAdjacentElement('afterend', card);
    });

    document.querySelectorAll('.sl-list-card__delete').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            const id = parseInt(this.dataset.id);
            lists = lists.filter(l => l.id !== id);
            renderLists();
        });
    });
    document.querySelectorAll('.sl-list-card').forEach(function (card) {
    card.addEventListener('click', function (e) {
        if (e.target.closest('.sl-list-card__delete')) return;
        const name = this.querySelector('.sl-list-card__name').textContent;
        window.location.href = 'shopping-list-detail.html?name=' + encodeURIComponent(name);
    });
    });
}


function openModal() {
    modalInput.value = '';
    submitBtn.disabled = true;
    overlay.classList.add('visible');
    setTimeout(() => modalInput.focus(), 100);
}

function closeModal() {
    overlay.classList.remove('visible');
}

document.getElementById('sl-create-btn').addEventListener('click', openModal);
document.getElementById('sl-modal-close').addEventListener('click', closeModal);
document.getElementById('sl-modal-cancel').addEventListener('click', closeModal);

overlay.addEventListener('click', function (e) {
    if (e.target === overlay) closeModal();
});

modalInput.addEventListener('input', function () {
    submitBtn.disabled = this.value.trim().length === 0;
});

document.querySelectorAll('.sl-chip').forEach(function (chip) {
    chip.addEventListener('click', function () {
        modalInput.value = this.textContent;
        submitBtn.disabled = false;
        modalInput.focus();
    });
});

// Submit
submitBtn.addEventListener('click', function () {
    const name = modalInput.value.trim();
    if (!name) return;
    lists.push({
        id: Date.now(),
        name: name,
        icon: listIcons[lists.length % listIcons.length]
    });
    renderLists();
    closeModal();
});


modalInput.addEventListener('keydown', function (e) {
    if (e.key === 'Enter' && !submitBtn.disabled) submitBtn.click();
});

// Init
submitBtn.disabled = true;
renderLists();
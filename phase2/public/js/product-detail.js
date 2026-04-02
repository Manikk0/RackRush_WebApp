// GLOBAL VARIABLES
const header = document.getElementById("main-header");
const topRow = document.getElementById("top-row");
const stickySearch = document.getElementById("sticky-search-container");
const stickyCart = document.getElementById("sticky-cart");
const btnLists = document.getElementById("btn-lists");

// NAVIGATION & HEADER
window.onscroll = function () {
    if (window.innerWidth >= 768) {
        if (header && topRow && stickySearch && stickyCart && btnLists) {
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
toggleAuthState(true);

// AUTHENTICATION EVENTS
const userMenu = document.getElementById('userMenu');
if (userMenu) {
    userMenu.addEventListener('click', function (e) {
        e.stopPropagation();
    });
}

const logoutBtnMobile = document.getElementById('logout-btn-mobile');
if (logoutBtnMobile) {
    logoutBtnMobile.addEventListener('click', function (e) {
        e.preventDefault();
        toggleAuthState(false);

        const mobileMenuEl = document.getElementById('mobileMenu');
        if (mobileMenuEl) {
            const offcanvas = bootstrap.Offcanvas.getInstance(mobileMenuEl);
            if (offcanvas) { offcanvas.hide(); }
        }

        const toastEl = document.getElementById('logoutToast');
        if (toastEl) {
            const toast = new bootstrap.Toast(toastEl);
            toast.show();
        }
    });
}

const logoutBtn = document.getElementById('logout-btn');
if (logoutBtn) {
    logoutBtn.addEventListener('click', function (e) {
        e.preventDefault();
        toggleAuthState(false);
        const toastEl = document.getElementById('logoutToast');
        if (toastEl) {
            const toast = new bootstrap.Toast(toastEl);
            toast.show();
        }
    });
}

const loginForm = document.getElementById('login-form');
if (loginForm) {
    loginForm.addEventListener('submit', function (e) {
        e.preventDefault();
        toggleAuthState(true);
        const loginModalElement = document.getElementById('loginModal');
        if (loginModalElement) {
            const loginModal = bootstrap.Modal.getInstance(loginModalElement);
            if (loginModal) { loginModal.hide(); }
        }
    });
}

const registerForm = document.getElementById('register-form');
if (registerForm) {
    registerForm.addEventListener('submit', function (e) {
        e.preventDefault();
        toggleAuthState(true);
        const registerModalElement = document.getElementById('registerModal');
        if (registerModalElement) {
            const registerModal = bootstrap.Modal.getInstance(registerModalElement);
            if (registerModal) { registerModal.hide(); }
        }
    });
}

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

// PAGE SPECIFIC LOGIC (PRODUCT DETAIL GALLERY)
document.addEventListener('DOMContentLoaded', function () {
    const galleryTrack = document.getElementById('gallery-track');
    const slides = document.querySelectorAll('.gallery-slide-img');
    const prevBtn = document.getElementById('gallery-prev');
    const nextBtn = document.getElementById('gallery-next');
    
    const zoomBtn = document.getElementById('gallery-zoom');
    const zoomModal = document.getElementById('zoom-modal');
    const zoomModalImg = document.getElementById('zoom-modal-img');
    const zoomModalClose = document.getElementById('zoom-modal-close');
    
    let currentIndex = 0;
    const totalSlides = slides.length;

    function updateGallery() {
        if (galleryTrack) {
            galleryTrack.style.transform = 'translateX(-' + (currentIndex * 100) + '%)';
        }
    }

    if (prevBtn) {
        prevBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            currentIndex = (currentIndex - 1 + totalSlides) % totalSlides;
            updateGallery();
        });
    }

    if (nextBtn) {
        nextBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            currentIndex = (currentIndex + 1) % totalSlides;
            updateGallery();
        });
    }

    // Modal Control
    if (zoomBtn && zoomModal && zoomModalImg) {
        zoomBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            const currentImg = slides[currentIndex];
            if (currentImg) {
                zoomModalImg.src = currentImg.src;
                zoomModal.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
        });
    }

    function closeModal() {
        if (zoomModal) {
            zoomModal.classList.remove('active');
            document.body.style.overflow = '';
        }
    }

    if (zoomModalClose) {
        zoomModalClose.addEventListener('click', closeModal);
    }

    if (zoomModal) {
        zoomModal.addEventListener('click', function (e) {
            if (e.target === zoomModal) {
                closeModal();
            }
        });
    }

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeModal();
        }
    });

    const wishlistBtn = document.querySelector('.gallery-wishlist-btn');
    if (wishlistBtn) {
        wishlistBtn.addEventListener('click', function () {
            const self = this;
            self.style.transform = 'scale(1.3)';
            setTimeout(function () {
                self.style.transform = 'scale(1.15)';
            }, 200);
        });
    }
});

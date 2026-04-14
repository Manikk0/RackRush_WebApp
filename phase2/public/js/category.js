// Category page interactions.
var header = document.getElementById('main-header');
var topRow = document.getElementById('top-row');
var stickySearch = document.getElementById('sticky-search-container');
var stickyCart = document.getElementById('sticky-cart');
var btnLists = document.getElementById('btn-lists');

// Sticky header behavior for desktop when scrolling.
window.onscroll = function () {
    if (window.innerWidth < 768) {
        return;
    }

    if (!header || !topRow || !stickySearch || !stickyCart || !btnLists) {
        return;
    }

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
};

// Toggle "show more" text for filter collapse.
var moreBtn = document.getElementById('show-more-origins-btn');
if (moreBtn) {
    moreBtn.addEventListener('click', function () {
        var text = moreBtn.querySelector('.show-more-text');
        setTimeout(function () {
            var isExpanded = moreBtn.getAttribute('aria-expanded') === 'true';
            if (text) {
                text.textContent = isExpanded ? 'Zobraziť menej' : 'Zobraziť viac';
            }
        }, 10);
    });
}

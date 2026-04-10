<!DOCTYPE html>
<html lang="sk">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'RackRush')</title>
    <link href="{{ asset('bootstrap/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('styles/layout.css') }}">
    @stack('styles')
</head>

<body class="@yield('body-class')">
    @include('partials.navbar')

    <div class="page-fill">
        <!-- MAIN CONTENT -->
        <main class="container-fluid px-4 site-main">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @yield('content')
        </main>

        @include('partials.footer')
    </div>

    @include('partials.modals')

    <script src="{{ asset('bootstrap/bootstrap.bundle.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if ($errors->has('email') && !old('first_name'))
                var loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
                loginModal.show();
            @endif

            @if ($errors->any() && old('first_name'))
                var registerModal = new bootstrap.Modal(document.getElementById('registerModal'));
                registerModal.show();
            @endif

            @if (session('logout_success'))
                var logoutToast = new bootstrap.Toast(document.getElementById('logoutToast'));
                logoutToast.show();
            @endif

            // Nahrat popup prvy raz pri nacitani stranky
            loadCartPopup();
            
            // Kliknutie na kosik v navbare otvori drawer
            const cartTriggers = document.querySelectorAll('.cart-trigger-btn');
            const cartDrawer = document.getElementById('cart-drawer');
            const cartOverlay = document.getElementById('cart-overlay');
            const cartClose = document.getElementById('cart-close');

            cartTriggers.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    if (window.location.pathname === '/cart') return;
                    if (window.innerWidth <= 768) {
                        window.location.href = '/cart';
                        return;
                    }
                    e.preventDefault();
                    openCartDrawer();
                });
            });

            if (cartClose) {
                cartClose.addEventListener('click', () => {
                    closeCartDrawer();
                });
            }
            if (cartOverlay) {
                cartOverlay.addEventListener('click', () => {
                    closeCartDrawer();
                });
            }
        });

        function getCsrfToken() {
            var meta = document.querySelector('meta[name="csrf-token"]');
            return meta ? meta.getAttribute('content') : '';
        }

        function cartImageUrl(path) {
            if (!path) return '/assets/grapes_white_tray.png';
            if (path.indexOf('http://') === 0 || path.indexOf('https://') === 0) return path;
            if (path.charAt(0) === '/') return path;
            return '/' + path;
        }

        function openCartDrawer() {
            var drawer = document.getElementById('cart-drawer');
            var overlay = document.getElementById('cart-overlay');
            if (drawer) drawer.classList.add('cart-drawer--open');
            if (overlay) overlay.classList.add('cart-overlay--visible');
            loadCartPopup();
        }

        function closeCartDrawer() {
            var drawer = document.getElementById('cart-drawer');
            var overlay = document.getElementById('cart-overlay');
            if (drawer) drawer.classList.remove('cart-drawer--open');
            if (overlay) overlay.classList.remove('cart-overlay--visible');
        }

        function loadCartPopup() {
            fetch('/cart/api')
                .then(res => res.json())
                .then(data => {
                    const cartBody = document.getElementById('cart-body');
                    if (!cartBody) return;

                    let html = '';
                    let totalItems = 0;
                    let totalPrice = 0;

                    for (const [id, item] of Object.entries(data)) {
                        totalItems += parseInt(item.quantity);
                        totalPrice += item.price * item.quantity;
                        
                        // Vypocet zlavnenia (do popisu ak bola zlava)
                        let ustriteHodnota = '';
                        let oldPriceHtml = '';
                        
                        if (item.discount && item.discount > 0) {
                            oldPriceHtml = `<span style="text-decoration: line-through; color: #aaa; margin-right: 5px;">${(item.old_price * item.quantity).toFixed(2).replace('.', ',')} €</span>`;
                            ustriteHodnota = `<div style="color: #ff4d4d; font-size: 12px; margin-top: 5px;">Ušetríte ${item.discount} %</div>`;
                        }

                        // Noob-friendly generovanie HTML pre kazdy produkt v popupe
                        var detailUrl = '/product/' + id;
                        html += `
                        <div class="d-flex align-items-center mb-3 pb-3" style="border-bottom: 1px solid #333; position:relative;">
                            <a href="${detailUrl}" style="display:block;">
                                <img src="${cartImageUrl(item.image)}" alt="${item.name}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px; background: white; padding: 2px;">
                            </a>
                            
                            <div class="ms-3 flex-grow-1">
                                <h6 class="mb-1" style="font-size: 14px;">
                                    <a href="${detailUrl}" style="text-decoration:none;color:white;">
                                        ${item.name}
                                    </a>
                                    <span style="font-style:italic; color:#ccc;">${item.weight ? item.weight : ''}</span>
                                </h6>
                                
                                <div class="d-flex align-items-end justify-content-between mt-2">
                                    <div class="d-flex align-items-center" style="border: 1px solid #444; border-radius: 5px; padding: 2px;">
                                        <button class="btn btn-sm text-white p-0 m-0 fs-5" style="width: 25px;" onclick="updatePopupItem(${id}, -1)">−</button>
                                        <span class="text-white mx-2 fw-bold" style="font-size: 14px;">${item.quantity}</span>
                                        <button class="btn btn-sm text-white p-0 m-0 fs-5" style="width: 25px;" onclick="updatePopupItem(${id}, 1)">+</button>
                                    </div>
                                    
                                    <div class="text-end">
                                        <div class="fw-bold" style="color: ${item.discount > 0 ? '#ff4d4d' : 'white'};">
                                            ${oldPriceHtml}
                                            ${(item.price * item.quantity).toFixed(2).replace('.', ',')} €
                                        </div>
                                        ${ustriteHodnota}
                                    </div>
                                </div>
                            </div>
                            
                            <button onclick="removePopupItem(${id})" class="btn p-0 position-absolute" style="top: -5px; right: 0; color: #888;">
                                ✕
                            </button>
                        </div>`;
                    }

                    if (totalItems === 0) {
                        html = '<p class="text-center text-muted mt-4">Váš košík je prázdny.</p>';
                    }

                    cartBody.innerHTML = html;
                    
                    // Updatni cisielka vsetkych badges v UI
                    const badges = document.querySelectorAll('.cart-badge');
                    badges.forEach(badge => {
                        badge.innerText = totalItems;
                        if (totalItems > 0) {
                            badge.classList.remove('d-none');
                        } else {
                            badge.classList.add('d-none');
                        }
                    });

                    const totalEl = document.getElementById('cart-total-price');
                    if (totalEl) {
                        totalEl.innerText = totalPrice.toFixed(2).replace('.', ',') + ' €';
                    }

                    if (typeof window.syncProductCardCartFromServer === 'function') {
                        window.syncProductCardCartFromServer(data);
                    }
                });
        }

        // Pomocne funkcie priamo v popupe
        function updatePopupItem(id, change) {
            fetch('/cart/api')
                .then(function (res) { return res.json(); })
                .then(function (cart) {
                    var currentQty = 0;
                    if (cart && cart[id] && cart[id].quantity) {
                        currentQty = parseInt(cart[id].quantity, 10) || 0;
                    }
                    var nextQty = currentQty + change;
                    if (nextQty < 0) nextQty = 0;
                    if (nextQty > 99) nextQty = 99;

                    return fetch('/cart/add/' + id, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': getCsrfToken(),
                            'Content-Type': 'application/json',
                            Accept: 'application/json',
                        },
                        body: JSON.stringify({ quantity: nextQty, exact: true })
                    });
                })
                .then(function () {
                    loadCartPopup();
                });
        }

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
    </script>
    <script src="{{ asset('js/product-card-cart.js') }}?v=5"></script>
    @stack('scripts')
</body>

</html>

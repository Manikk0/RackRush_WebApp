@extends('layouts.app')

@section('title', 'Košík – RackRush')

@push('styles')
    <link rel="stylesheet" href="{{ asset('styles/index.css') }}">
    <link rel="stylesheet" href="{{ asset('styles/cart.css') }}">
@endpush

@section('content')
    <h1 class="cart-page-title" id="cart-page-title">Prehľad objednávky ({{ count(session()->get('cart', [])) }})</h1>

    <div class="row g-4">
        <!-- CART ITEMS SECTION -->
        <div class="col-lg-8">
            <div id="cart-items-container">
                <!-- EMPTY STATE: Pridame ho vzdy do DOMu, len je skryty ak v nom nieco je -->
                <div class="cart-empty-state" id="cart-empty-state" style="{{ count(session()->get('cart', [])) == 0 ? 'display:block;' : 'display:none;' }}">
                    Váš košík je prázdny.
                </div>

                @if(count(session()->get('cart', [])) > 0)
                    @foreach(session()->get('cart', []) as $id => $item)
                        <div class="cart-row position-relative" data-id="{{ $id }}">
                            <div class="cart-row__img-wrap me-2" style="align-self: flex-start;">
                                <a href="{{ route('product-detail', $id) }}">
                                    <img src="{{ asset($item['image'] ?? 'assets/grapes_white_tray.png') }}" alt="{{ $item['name'] }}" class="cart-row__img">
                                </a>
                            </div>
                            
                            <div class="cart-row__content d-flex flex-column flex-grow-1 w-100" style="min-width: 0;">
                                <!-- Horny riadok: Nazov a X tlacidlo -->
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <a href="{{ route('product-detail', $id) }}" class="text-decoration-none pe-2">
                                        <!-- Pridal som styl normal pre povolenie zalamovat dlhy text namiesto schovavania -->
                                        <p class="cart-row__name text-wrap m-0" style="white-space: normal; line-height: 1.2;">{{ $item['name'] }}</p>
                                        <span class="text-muted small mt-1 d-block">{{ $item['weight'] ?? '' }}</span>
                                    </a>
                                    
                                    <button onclick="removeCartPageItem({{ $id }})" class="btn p-0 d-flex align-items-center justify-content-center" style="opacity: 0.6; margin-top: 2px;">
                                        <img src="{{ asset('assets/close.png') }}" alt="Odstrániť" class="icon-sm icon-white" style="width: 12px; height: 12px;">
                                    </button>
                                </div>

                                <!-- Dolny riadok: Cena a Poctovac -->
                                <div class="d-flex justify-content-between align-items-end mt-auto">
                                    <div class="d-flex flex-column justify-content-end">
                                        @if(($item['discount'] ?? 0) > 0 && isset($item['old_price']))
                                            <span class="text-decoration-line-through cart-row__old-price" style="color: #ff6b6b; font-size: 0.8rem;">{{ number_format($item['old_price'] * $item['quantity'], 2) }} €</span>
                                        @endif
                                        <span class="cart-row__price fw-bold m-0" style="color: var(--neon-ice); font-size: 1.15rem; text-align: left;">{{ number_format($item['price'] * $item['quantity'], 2) }} €</span>
                                    </div>
                                    
                                    <div class="cart-row__qty d-flex align-items-center justify-content-center gap-2">
                                        <button class="btn p-0 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; border: 1px solid var(--tropical-teal); background-color: transparent; color: var(--tropical-teal); border-radius: 6px; font-weight: bold; font-size: 18px;" onclick="updateCartPageItem({{ $id }}, -1)">−</button>
                                        <input type="text" inputmode="numeric" pattern="[0-9]*" id="qty-cart-{{ $id }}" value="{{ $item['quantity'] }}" class="form-control text-center text-white bg-transparent border-0 fw-bold p-0 m-0" style="width: 34px; box-shadow: none; outline: none; height: 32px; font-size: 1rem;" 
                                               oninput="this.value = this.value.replace(/[^0-9]/g, ''); if(parseInt(this.value) > 99) this.value = 99;" 
                                               onkeyup="debounceCartUpdate({{ $id }}, this.value)">
                                        <button class="btn p-0 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background-color: var(--tropical-teal); border: none; color: var(--space-indigo, #000); border-radius: 6px; font-weight: bold; font-size: 18px;" onclick="updateCartPageItem({{ $id }}, 1)">+</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            <!-- ACTIONS BAR -->
            <div class="cart-actions">
                <button class="btn-discount" id="btn-discount">
                    <img src="{{ asset('assets/tag.png') }}" class="icon-sm icon-white">
                    Vložiť zľavový kód
                </button>
                <form action="{{ route('cart.empty') }}" method="POST" style="margin: 0; display: inline-block;">
                    @csrf
                    <button type="submit" class="btn-clear" id="btn-clear">
                        <img src="{{ asset('assets/trash.png') }}" class="icon-sm icon-white">
                        Vyprázdniť košík
                    </button>
                </form>
            </div>

            <!-- DISCOUNT INPUT -->
            <div class="discount-input-wrap" id="discount-input-wrap">
                <input type="text" placeholder="Zadajte kód...">
                <button>Uplatniť</button>
            </div>
        </div>

        <!-- SUMMARY SECTION -->
        <div class="col-lg-4">
            <div class="cart-summary">
                <p class="cart-summary__title">Zhrnutie objednávky</p>

                @php
                    $subtotal = 0;
                    $old_subtotal = 0;
                    foreach(session()->get('cart', []) as $item) {
                        $subtotal += $item['price'] * $item['quantity'];
                        if(isset($item['old_price']) && ($item['discount'] ?? 0) > 0) {
                            $old_subtotal += $item['old_price'] * $item['quantity'];
                        } else {
                            $old_subtotal += $item['price'] * $item['quantity'];
                        }
                    }
                    $savings = $old_subtotal - $subtotal;
                @endphp

                <div class="cart-summary__row">
                    <span>Medzisúčet</span>
                    <span id="summary-subtotal">{{ number_format($subtotal, 2) }} €</span>
                </div>
                <div class="cart-summary__row">
                    <span>Doprava</span>
                    <span id="summary-shipping">Zdarma</span>
                </div>
                <div class="cart-summary__row">
                    <span>Zľava</span>
                    <span id="summary-discount">–0.00 €</span>
                </div>

                <div class="cart-summary__row cart-summary__row--total">
                    <span>Spolu</span>
                    <span id="summary-total">{{ number_format($subtotal, 2) }} €</span>
                </div>

                <p class="cart-summary__savings" style="{{ $savings > 0 ? 'display:block;' : 'display:none;' }}">Ušetrené: <span id="summary-savings">{{ number_format($savings, 2) }} €</span></p>

                <a href="{{ route('order_details') }}" class="btn-proceed">Pokračovať k platbe a doprave</a>
            </div>
        </div>
    </div>

    <!-- RECOMMENDATIONS SECTION -->
    <section class="recommendations-section">
        <h2 class="recommendations-section__title">Mohlo by sa vám páčiť</h2>
        <div class="product-row" id="recommendations"></div>
    </section>
@endsection

@push('scripts')
    <script src="{{ asset('js/cart.js') }}"></script>
    <script>
        var cartUpdateTimeouts = {};

        function getCartCsrf() {
            var m = document.querySelector('meta[name="csrf-token"]');
            return m ? m.getAttribute('content') : '';
        }

        function updateCartPageItem(id, change) {
            var elem = document.getElementById('qty-cart-' + id);
            var currentQty = parseInt(elem.value, 10);
            if (isNaN(currentQty)) currentQty = 1;
            var newQty = currentQty + change;
            if (newQty > 99) newQty = 99;

            if (newQty <= 0) {
                removeCartPageItem(id);
            } else {
                elem.value = newQty;
                debounceCartUpdate(id, newQty);
            }
        }

        function debounceCartUpdate(id, amount) {
            var key = String(id);
            if (cartUpdateTimeouts[key]) {
                clearTimeout(cartUpdateTimeouts[key]);
            }
            cartUpdateTimeouts[key] = setTimeout(function () {
                delete cartUpdateTimeouts[key];
                setCartPageItemExact(id, amount);
            }, 800);
        }

        function setCartPageItemExact(id, amount) {
            var newQty = parseInt(amount, 10);

            if (isNaN(newQty) || newQty <= 0) {
                removeCartPageItem(id);
            } else {
                fetch('/cart/add/' + id, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': getCartCsrf(),
                        'Content-Type': 'application/json',
                        Accept: 'application/json',
                    },
                    body: JSON.stringify({ quantity: newQty, exact: true }),
                }).then(function () {
                    refreshLocalCartDisplay();
                });
            }
        }

        function removeCartPageItem(id) {
            fetch('/cart/remove/' + id, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': getCartCsrf(), Accept: 'application/json' },
            }).then(function () {
                var row = document.querySelector('.cart-row[data-id="' + id + '"]');
                if (row) row.remove();
                refreshLocalCartDisplay();
            });
        }

        function refreshLocalCartDisplay() {
            fetch('/cart/api')
                .then(res => res.json())
                .then(data => {
                    let subtotal = 0;
                    let old_subtotal = 0;
                    let count = 0;
                    
                    document.querySelectorAll('.cart-row').forEach(row => {
                        let id = row.getAttribute('data-id');
                        if (data[id]) {
                            let item = data[id];
                            subtotal += item.price * item.quantity;
                            if (item.old_price && item.discount > 0) {
                                old_subtotal += item.old_price * item.quantity;
                            } else {
                                old_subtotal += item.price * item.quantity;
                            }
                            count++;
                            
                            // Edit price text
                            let priceElem = row.querySelector('.cart-row__price');
                            if (priceElem) {
                                priceElem.innerText = (item.price * item.quantity).toFixed(2).replace('.', ',') + ' €';
                            }
                            
                            // Edit old crossed out price text
                            let oldPriceElem = row.querySelector('.cart-row__old-price');
                            if (oldPriceElem && item.old_price) {
                                oldPriceElem.innerText = (item.old_price * item.quantity).toFixed(2).replace('.', ',') + ' €';
                            }
                        } else {
                            row.remove();
                        }
                    });

                    let savings = old_subtotal - subtotal;

                    // Update suhrov
                    let titleElem = document.getElementById('cart-page-title');
                    if (titleElem) titleElem.innerText = `Prehľad objednávky (${count})`;
                    
                    let summarySub = document.getElementById('summary-subtotal');
                    if (summarySub) summarySub.innerText = subtotal.toFixed(2).replace('.', ',') + ' €';
                    
                    let summaryTotal = document.getElementById('summary-total');
                    if (summaryTotal) summaryTotal.innerText = subtotal.toFixed(2).replace('.', ',') + ' €';
                    
                    let calcSavingsEl = document.getElementById('summary-savings');
                    let savingsContainer = document.querySelector('.cart-summary__savings');
                    if (calcSavingsEl && savingsContainer) {
                        calcSavingsEl.innerText = savings.toFixed(2).replace('.', ',') + ' €';
                        if (savings > 0) {
                            savingsContainer.style.display = 'block';
                        } else {
                            savingsContainer.style.display = 'none';
                        }
                    }

                    if (count === 0) {
                        let emptyState = document.getElementById('cart-empty-state');
                        if (emptyState) emptyState.style.display = 'block';
                    }
                    
                    if (typeof loadCartPopup === 'function') {
                        loadCartPopup();
                    }
                });
        }
    </script>
@endpush

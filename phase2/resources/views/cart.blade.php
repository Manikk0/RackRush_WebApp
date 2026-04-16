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
                <!-- EMPTY STATE (HIDDEN WHEN CART HAS ROWS) -->
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
                                <!-- ROW: TITLE & REMOVE -->
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <a href="{{ route('product-detail', $id) }}" class="text-decoration-none pe-2">
                                        <!-- LONG PRODUCT NAMES: WRAP -->
                                        <p class="cart-row__name text-wrap m-0" style="white-space: normal; line-height: 1.2;">{{ $item['name'] }}</p>
                                        <span class="text-muted small mt-1 d-block">{{ $item['weight'] ?? '' }}</span>
                                    </a>
                                    
                                    <button onclick="removeCartPageItem({{ $id }})" class="btn p-0 d-flex align-items-center justify-content-center" style="opacity: 0.6; margin-top: 2px;">
                                        <img src="{{ asset('assets/close.png') }}" alt="Odstrániť" class="icon-sm icon-white" style="width: 12px; height: 12px;">
                                    </button>
                                </div>

                                <!-- ROW: PRICE & QTY -->
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
                    <span id="summary-shipping">{{ number_format($shippingFee, 2) }} €</span>
                </div>
                <div class="cart-summary__row">
                    <span>Zľava</span>
                    <span id="summary-discount">–0.00 €</span>
                </div>

                <div class="cart-summary__row cart-summary__row--total">
                    <span>Spolu</span>
                    <span id="summary-total">{{ number_format($subtotal + $shippingFee, 2) }} €</span>
                </div>

                <p class="cart-summary__savings" style="{{ $savings > 0 ? 'display:block;' : 'display:none;' }}">Ušetrené: <span id="summary-savings">{{ number_format($savings, 2) }} €</span></p>

                <form action="{{ route('order_details') }}" method="GET" class="m-0">
                    <button
                        type="submit"
                        id="btn-proceed-checkout"
                        class="btn-proceed border-0 w-100 {{ count(session()->get('cart', [])) === 0 ? 'opacity-50' : '' }}"
                        @disabled(count(session()->get('cart', [])) === 0)
                    >
                        Pokračovať k platbe a doprave
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- RECOMMENDATIONS SECTION -->
    <section class="recommendations-section">
        <h2 class="recommendations-section__title">Mohlo by sa vám páčiť</h2>
        <div class="product-row" id="recommendations">
            @foreach($odporucaneProdukty as $produkt)
                @include('partials.product-card', ['produkt' => $produkt])
            @endforeach
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        window.CART_SHIPPING_FEE = {{ json_encode((float) $shippingFee) }};
    </script>
    <script src="{{ asset('js/cart-page.js') }}"></script>
@endpush

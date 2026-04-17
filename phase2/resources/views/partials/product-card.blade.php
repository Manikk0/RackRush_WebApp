<div class="product-card">
    <div class="product-card__image-wrap">
        <a href="{{ route('product-detail', $produkt->id) }}">
            <img src="{{ $produkt->hlavnyObrazok ? (strpos($produkt->hlavnyObrazok->url, 'assets/') === 0 ? asset($produkt->hlavnyObrazok->url) : '/storage/' . $produkt->hlavnyObrazok->url) : asset('assets/grapes_white_tray.png') }}"
                 alt="{{ $produkt->name }}" class="product-card__image">
        </a>
        <button class="product-card__wishlist" aria-label="Wishlist">
            <img src="{{ asset('assets/heart.png') }}" class="wishlist-icon">
        </button>
        @php
            $cart = session()->get('cart', []);
            $cardQty = isset($cart[$produkt->id]) ? (int) $cart[$produkt->id]['quantity'] : 0;
            if ($cardQty < 0) $cardQty = 0;
            if ($cardQty > 99) $cardQty = 99;
        @endphp

        <div class="product-card__cart-anchor js-card-cart-anchor" data-product-id="{{ $produkt->id }}">
            <div class="product-card__cart-stack">
                <button
                    type="button"
                    class="product-card__add js-card-cart-add {{ $cardQty > 0 ? 'd-none' : '' }}"
                    aria-label="Pridať do košíka"
                    data-product-id="{{ $produkt->id }}"
                >
                    <img src="{{ asset('assets/plus.png') }}" class="icon-sm icon-white">
                </button>

                <button
                    type="button"
                    class="product-card__cart-compact js-card-cart-compact {{ $cardQty > 0 ? '' : 'd-none' }}"
                    data-product-id="{{ $produkt->id }}"
                    aria-label="V košíku"
                >
                    <span class="product-card__cart-compact-qty">{{ $cardQty }}</span>
                    <img src="{{ asset('assets/shopping-cart.png') }}" class="product-card__cart-compact-icon" alt="">
                </button>

                <div
                    class="product-card__cart-control js-card-cart-control d-none"
                    data-product-id="{{ $produkt->id }}"
                    aria-label="Množstvo v košíku"
                >
                    <button type="button" class="product-card__cart-btn product-card__cart-btn--minus" data-cart-action="minus" aria-label="Znížiť množstvo"><span class="product-card__cart-symbol">−</span></button>
                    <input
                        class="product-card__cart-qty"
                        type="text"
                        inputmode="numeric"
                        pattern="[0-9]*"
                        value="{{ $cardQty }}"
                        maxlength="2"
                        aria-label="Množstvo"
                    >
                    <button type="button" class="product-card__cart-btn product-card__cart-btn--plus" data-cart-action="plus" aria-label="Zvýšiť množstvo"><span class="product-card__cart-symbol">+</span></button>
                </div>
            </div>
        </div>
    </div>
    <div class="product-card__body">
        <a href="{{ route('product-detail', $produkt->id) }}" class="text-decoration-none product-card__title-link">
            <p class="product-card__name">{{ $produkt->name }}</p>
        </a>
        @if($produkt->discount > 0)
            <div class="product-card__sale-price">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="product-card__sale-old">{{ number_format($produkt->price, 2) }}{{ '€' }}</span>
                    <span class="product-card__sale-badge">-{{ $produkt->discount }}%</span>
                </div>
                <div class="product-card__sale-current">{{ number_format($produkt->cena_po_zlave, 2) }}{{ '€' }}</div>
            </div>
        @else
            <p class="product-card__price">{{ number_format($produkt->price, 2) }}{{ '€' }}</p>
        @endif
        <p class="product-card__meta">
            <span>{{ $produkt->mnozstvo_display }}</span>
            @if($produkt->cenaNaJednotku)
                <span>{{ $produkt->cenaNaJednotku }}</span>
            @endif
        </p>
    </div>
</div>

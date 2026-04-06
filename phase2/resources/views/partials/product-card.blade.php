<div class="product-card">
    <div class="product-card__image-wrap">
        <a href="{{ route('product-detail', $produkt->id) }}">
            <img src="{{ asset($produkt->hlavnyObrazok?->url ?? 'assets/grapes_white_tray.png') }}"
                 alt="{{ $produkt->name }}" class="product-card__image">
        </a>
        <button class="product-card__wishlist" aria-label="Wishlist">
            <img src="{{ asset('assets/heart.png') }}" class="wishlist-icon">
        </button>
        <button class="product-card__add" aria-label="Pridať do košíka">
            <img src="{{ asset('assets/plus.png') }}" class="icon-sm icon-white">
        </button>
    </div>
    <a href="{{ route('product-detail', $produkt->id) }}" class="text-decoration-none">
        <p class="product-card__name">{{ $produkt->name }}</p>
    </a>
    @if($produkt->discount > 0)
        <div class="product-card__sale-price">
            <div class="product-card__sale-top">
                <span class="product-card__sale-old">{{ number_format($produkt->price, 2) }}€</span>
                <span class="product-card__sale-badge">-{{ $produkt->discount }}%</span>
            </div>
            <div class="product-card__sale-current">{{ number_format($produkt->cena_po_zlave, 2) }}€</div>
        </div>
    @else
        <p class="product-card__price">{{ number_format($produkt->price, 2) }}€</p>
    @endif
    <p class="product-card__meta">
        <span>{{ $produkt->mnozstvo_display }}</span>
        @if($produkt->cenaNaJednotku)
            <span>{{ $produkt->cenaNaJednotku }}</span>
        @endif
    </p>
</div>
